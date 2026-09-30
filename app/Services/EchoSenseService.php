<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class EchoSenseService
{
    /**
     * Get aggregated environmental data
     */
    public function getEnvironmentData($lat, $lon, $lang = 'id')
    {
        // 1. Fetch Air Quality (OpenAQ)
        $aqiData = $this->fetchOpenAQ($lat, $lon);
        
        // 2. Fetch Traffic (TomTom)
        $trafficData = $this->fetchTomTom($lat, $lon);
        
        // 3. Fetch Weather (Real Data via Open-Meteo)
        $weatherData = $this->fetchWeather($lat, $lon);

        // 4. Map to Audio Parameters (Rule-Based Weighted Scoring)
        $audioParams = $this->mapToAudioParameters($aqiData, $trafficData, $weatherData);

        // 5. Generate Voice Briefing (Gemini) based on preferred language
        $briefing = $this->generateVoiceBriefing($aqiData, $trafficData, $weatherData, $lang);

        return [
            'raw_data' => [
                'aqi' => $aqiData,
                'traffic' => $trafficData,
                'weather' => $weatherData
            ],
            'audio_params' => $audioParams,
            'briefing' => $briefing
        ];
    }

    private function fetchOpenAQ($lat, $lon)
    {
        $key = env('OPENAQ_API_KEY');
        try {
            $response = Http::withHeaders([
                'X-API-Key' => $key
            ])->get("https://api.openaq.org/v2/latest", [
                'coordinates' => "{$lat},{$lon}",
                'radius' => 10000,
                'limit' => 1
            ]);

            if ($response->successful() && !empty($response->json('results'))) {
                $measurements = $response->json('results')[0]['measurements'];
                $pm25 = collect($measurements)->firstWhere('parameter', 'pm25')['value'] ?? 20;
                return [
                    'status' => 'success',
                    'pm25' => $pm25,
                    'category' => $pm25 > 50 ? 'Buruk' : ($pm25 > 25 ? 'Sedang' : 'Baik')
                ];
            }
        } catch (\Exception $e) {
            Log::error('OpenAQ Error: ' . $e->getMessage());
        }

        // Fallback
        return ['status' => 'fallback', 'pm25' => 15, 'category' => 'Baik'];
    }

    private function fetchTomTom($lat, $lon)
    {
        $key = env('TOMTOM_API_KEY');
        try {
            $response = Http::get("https://api.tomtom.com/traffic/services/4/flowSegmentData/absolute/10/json", [
                'point' => "{$lat},{$lon}",
                'key' => $key
            ]);

            if ($response->successful()) {
                $flow = $response->json('flowSegmentData');
                $currentSpeed = $flow['currentSpeed'] ?? 30;
                $freeFlowSpeed = $flow['freeFlowSpeed'] ?? 30;
                $ratio = $freeFlowSpeed > 0 ? ($currentSpeed / $freeFlowSpeed) : 1;
                
                $congestion = 'Lancar';
                if ($ratio < 0.4) $congestion = 'Macet Parah';
                elseif ($ratio < 0.7) $congestion = 'Padat Merayap';

                return [
                    'status' => 'success',
                    'currentSpeed' => $currentSpeed,
                    'congestion' => $congestion
                ];
            }
        } catch (\Exception $e) {
            Log::error('TomTom Error: ' . $e->getMessage());
        }

        return ['status' => 'fallback', 'currentSpeed' => 40, 'congestion' => 'Lancar'];
    }

    private function fetchWeather($lat, $lon)
    {
        try {
            // Open-Meteo is free and doesn't require an API key
            $response = Http::get("https://api.open-meteo.com/v1/forecast", [
                'latitude' => $lat,
                'longitude' => $lon,
                'current_weather' => true,
                'timezone' => 'auto'
            ]);

            if ($response->successful()) {
                $current = $response->json('current_weather');
                $temp = $current['temperature'];
                $code = $current['weathercode'];

                // Map WMO weather codes to Indonesian conditions
                $condition = 'Cerah';
                if ($code >= 1 && $code <= 3) $condition = 'Cerah Berawan';
                elseif ($code >= 45 && $code <= 48) $condition = 'Berkabut';
                elseif ($code >= 51 && $code <= 67) $condition = 'Hujan Ringan';
                elseif ($code >= 71 && $code <= 77) $condition = 'Bersalju'; // Unlikely in Indo but mapping it
                elseif ($code >= 80 && $code <= 82) $condition = 'Hujan Deras';
                elseif ($code >= 95) $condition = 'Badai Petir';

                return [
                    'status' => 'success',
                    'temperature' => $temp,
                    'condition' => $condition
                ];
            }
        } catch (\Exception $e) {
            Log::error('Weather Error: ' . $e->getMessage());
        }

        return [
            'status' => 'fallback',
            'temperature' => 28,
            'condition' => 'Berawan'
        ];
    }

    private function mapToAudioParameters($aqi, $traffic, $weather)
    {
        // AQI maps to Pitch/Frequency (higher pollution = higher pitch / dissonant)
        // PM2.5 range usually 0-100.
        $baseFreq = 220; // A3
        $pitchMod = min(2.0, max(0.5, 1 + ($aqi['pm25'] / 100)));
        $frequency = $baseFreq * $pitchMod;

        // Traffic maps to Tempo (congestion = faster tempo/heartbeat)
        $tempo = 60; // 60 BPM (Lancar)
        if ($traffic['congestion'] === 'Macet Parah') $tempo = 120;
        elseif ($traffic['congestion'] === 'Padat Merayap') $tempo = 90;

        // Weather maps to Timbre/Filter cutoff
        $filterCutoff = 2000;
        if ($weather['condition'] === 'Hujan') $filterCutoff = 800; // Muffled
        
        return [
            'frequency' => $frequency,
            'tempo' => $tempo,
            'filterCutoff' => $filterCutoff,
            'waveform' => $aqi['pm25'] > 50 ? 'sawtooth' : 'sine' // Sawtooth is harsher
        ];
    }

    private function generateVoiceBriefing($aqi, $traffic, $weather, $lang = 'id')
    {
        $key = env('GEMINI_API_KEY');
        if (str_starts_with($lang, 'en')) {
            $weatherEn = match($weather['condition']) {
                'Hujan' => 'Rainy',
                'Berawan' => 'Cloudy',
                default => 'Clear'
            };
            $trafficEn = match($traffic['congestion']) {
                'Macet Parah' => 'Heavy Traffic',
                'Padat Merayap' => 'Moderate Traffic',
                default => 'Light Traffic'
            };
            $aqiEn = match($aqi['category']) {
                'Buruk' => 'Unhealthy',
                'Sedang' => 'Moderate',
                default => 'Good'
            };
            $prompt = "You are an inclusive voice assistant for the visually impaired named EchoSense. 
Create a short, calming environmental summary (max 2 short sentences) based on this data.
IMPORTANT: You MUST write the summary entirely in ENGLISH.
Data:
- Air Quality: PM2.5 is {$aqi['pm25']} (Status: {$aqiEn}).
- Traffic: Status: {$trafficEn}.
- Weather: Condition: {$weatherEn}, Temp: {$weather['temperature']}°C.
Do not say 'Hello'. Respond purely in English.";
        } else {
            $prompt = "Kamu adalah asisten suara inklusif untuk tunanetra bernama EchoSense. 
Buat ringkasan kondisi lingkungan maksimal 2 kalimat pendek yang ramah dan menenangkan berdasarkan data ini:
Kualitas Udara: PM2.5 adalah {$aqi['pm25']} ({$aqi['category']}).
Lalu Lintas: {$traffic['congestion']}.
Cuaca: {$weather['condition']} dengan suhu {$weather['temperature']}°C.
Katakan langsung kondisinya tanpa pembukaan 'Halo'.";
        }

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json'
            ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-3.5-flash-lite:generateContent?key={$key}", [
                'contents' => [
                    ['parts' => [['text' => $prompt]]]
                ]
            ]);

            if ($response->successful()) {
                $candidates = $response->json('candidates');
                if (!empty($candidates)) {
                    $fallback = "Kondisi saat ini: Cuaca {$weather['condition']}, lalu lintas {$traffic['congestion']}, dan kualitas udara {$aqi['category']}.";
                    if (isset($weatherEn)) {
                        $fallback = "Current condition: Weather is {$weatherEn}, traffic is {$trafficEn}, and air quality is {$aqiEn}.";
                    }
                    return $candidates[0]['content']['parts'][0]['text'] ?? $fallback;
                } else {
                    Log::error('Gemini API success but no candidates: ' . $response->body());
                }
            } else {
                Log::error('Gemini API Error Response: ' . $response->body());
            }
        } catch (\Exception $e) {
            Log::error('Gemini Error: ' . $e->getMessage());
        }

        $fallback = "Kondisi saat ini: Cuaca {$weather['condition']}, lalu lintas {$traffic['congestion']}, dan kualitas udara {$aqi['category']}.";
        if (isset($weatherEn)) {
            $fallback = "Current condition: Weather is {$weatherEn}, traffic is {$trafficEn}, and air quality is {$aqiEn}.";
        }
        return $fallback;
    }
}
