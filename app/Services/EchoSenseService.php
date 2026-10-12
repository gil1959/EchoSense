<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class EchoSenseService
{
    /**
     * Get aggregated environmental data for a location
     */
    public function getEnvironmentData($lat, $lon, $lang = 'id')
    {
        // 1. Fetch Air Quality (OpenAQ)
        $aqiData = $this->fetchOpenAQ($lat, $lon);
        
        // 2. Fetch Traffic (TomTom)
        $trafficData = $this->fetchTomTom($lat, $lon);
        
        // 3. Fetch Weather (Real Data via Open-Meteo)
        $weatherData = $this->fetchWeather($lat, $lon);

        // 4. Generate Voice Briefing (Gemini) based on preferred language
        $briefing = $this->generateVoiceBriefing($aqiData, $trafficData, $weatherData, $lang);

        return [
            'raw_data' => [
                'aqi' => $aqiData,
                'traffic' => $trafficData,
                'weather' => $weatherData
            ],
            'briefing' => $briefing
        ];
    }

    /**
     * Convert integer to Indonesian spelled-out words
     */
    private function intToIndonesianWords($n)
    {
        if ($n === 0) return "nol";
        $words = ["", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas"];
        if ($n < 0) return "minus " . $this->intToIndonesianWords(-$n);
        if ($n < 12) return $words[$n];
        if ($n < 20) return $this->intToIndonesianWords($n - 10) . " belas";
        if ($n < 100) {
            $ten = (int)($n / 10);
            $rem = $n % 10;
            return $words[$ten] . " puluh" . ($rem > 0 ? " " . $words[$rem] : "");
        }
        if ($n < 200) return "seratus" . ($n % 100 > 0 ? " " . $this->intToIndonesianWords($n % 100) : "");
        if ($n < 1000) {
            $hundred = (int)($n / 100);
            $rem = $n % 100;
            return $words[$hundred] . " ratus" . ($rem > 0 ? " " . $this->intToIndonesianWords($rem) : "");
        }
        if ($n < 2000) return "seribu" . ($n % 1000 > 0 ? " " . $this->intToIndonesianWords($n % 1000) : "");
        if ($n < 1000000) {
            $thousand = (int)($n / 1000);
            $rem = $n % 1000;
            return $this->intToIndonesianWords($thousand) . " ribu" . ($rem > 0 ? " " . $this->intToIndonesianWords($rem) : "");
        }
        if ($n < 1000000000) {
            $million = (int)($n / 1000000);
            $rem = $n % 1000000;
            return $this->intToIndonesianWords($million) . " juta" . ($rem > 0 ? " " . $this->intToIndonesianWords($rem) : "");
        }
        return (string)$n;
    }

    /**
     * Convert float or numeric string to Indonesian words (including decimals)
     */
    public function numberToIndonesianWords($numStr)
    {
        $numStr = str_replace(',', '.', trim((string)$numStr));
        if (!is_numeric($numStr)) return $numStr;
        
        if (str_contains($numStr, '.')) {
            [$intPart, $decPart] = explode('.', $numStr, 2);
            $intWords = $this->intToIndonesianWords((int)$intPart);
            $decDigits = str_split($decPart);
            $digitsMap = [
                '0' => 'nol', '1' => 'satu', '2' => 'dua', '3' => 'tiga', '4' => 'empat',
                '5' => 'lima', '6' => 'enam', '7' => 'tujuh', '8' => 'delapan', '9' => 'sembilan'
            ];
            $decWords = array_map(fn($d) => $digitsMap[$d] ?? $d, $decDigits);
            return $intWords . ' koma ' . implode(' ', $decWords);
        }
        
        return $this->intToIndonesianWords((int)$numStr);
    }

    /**
     * Sanitize text to ensure natural, flawless pronunciation by TTS engine
     */
    public function sanitizeForTTS($text, $lang = 'id')
    {
        if (empty($text)) return '';

        // 1. Remove markdown formatting
        $text = preg_replace('/[*_#`~>]/', '', $text);

        if (str_starts_with($lang, 'en')) {
            $text = preg_replace('/(\d+(?:[\.,]\d+)?)\s*°\s*C\b/iu', '$1 degrees Celsius', $text);
            $text = preg_replace('/(\d+(?:[\.,]\d+)?)\s*km\/h\b/iu', '$1 kilometers per hour', $text);
            $text = preg_replace('/(\d+(?:[\.,]\d+)?)\s*%/iu', '$1 percent', $text);
            $text = preg_replace('/PM\s*2[\.,]5/iu', 'PM two point five particles', $text);
            $text = preg_replace('/AQI\s*(\d+)/iu', 'air quality index $1', $text);
            return trim($text);
        }

        // --- BAHASA INDONESIA SPELLING OF ALL NUMBERS ---
        // 2. Temperature: 30°C, 30.5°C, 30%c, 30 derajat Celcius, 30 derajat c
        $text = preg_replace_callback('/(\d+(?:[\.,]\d+)?)\s*(?:°\s*C|%\s*c|derajat\s*Celcius|derajat\s*c)\b/iu', function($m) {
            return $this->numberToIndonesianWords($m[1]) . ' derajat Celcius';
        }, $text);

        // 3. Speed: 40km/jam, 40 km/h, 40 kilometer per jam
        $text = preg_replace_callback('/(\d+(?:[\.,]\d+)?)\s*(?:km\/j(?:am)?|km\/h|kilometer\s*per\s*jam)\b/iu', function($m) {
            return $this->numberToIndonesianWords($m[1]) . ' kilometer per jam';
        }, $text);

        // 4. Percentage: 65%
        $text = preg_replace_callback('/(\d+(?:[\.,]\d+)?)\s*%/iu', function($m) {
            return $this->numberToIndonesianWords($m[1]) . ' persen';
        }, $text);

        // 5. AQI & PM2.5
        $text = preg_replace('/PM\s*2[\.,]5/iu', 'partikel PM dua koma lima', $text);
        $text = preg_replace_callback('/(?:indeks\s+)?AQI\s*(\d+)/iu', function($m) {
            return 'indeks kualitas udara ' . $this->numberToIndonesianWords($m[1]);
        }, $text);

        // 6. Common abbreviations
        $text = preg_replace('/\bdll\.?\b/iu', 'dan lain-lain', $text);
        $text = preg_replace('/\bdst\.?\b/iu', 'dan seterusnya', $text);
        $text = preg_replace('/\bsbb\.?\b/iu', 'sebagai berikut', $text);

        // 7. Any remaining standalone numbers convert to words so TTS never stumbles on digits
        $text = preg_replace_callback('/\b\d+(?:[\.,]\d+)?\b/u', function($m) {
            return $this->numberToIndonesianWords($m[0]);
        }, $text);

        return trim($text);
    }

    /**
     * Loosely extract core destination name from colloquial phrases
     */
    public function extractDestination($text)
    {
        $cleaned = trim($text);
        $patterns = [
            '/^(saya|aku|kami|kita)\s+(mau|ingin|pengen|akan|rencana|hendak)?\s*(pergi\s+ke|menuju\s+ke|jalan\s+ke|berangkat\s+ke|ke|menuju|otw\s+ke|otw)\s+/iu',
            '/^(mau|ingin|pengen|akan|rencana|hendak)\s+(pergi\s+ke|menuju\s+ke|jalan\s+ke|berangkat\s+ke|ke|menuju|otw\s+ke|otw)\s+/iu',
            '/^(tujuan\s+saya|tujuannya|tujuan\s+aku|tujuan|tujuannya\s+ke|tujuan\s+ke)\s+/iu',
            '/^(tolong\s+)?(cek|periksa|lihat|bagaimana\s+di|kondisi\s+di|cek\s+di)\s+(daerah\s+|kota\s+|wilayah\s+)?/iu',
            '/^(pergi\s+ke|menuju\s+ke|jalan\s+ke|berangkat\s+ke|arah\s+ke|otw\s+ke|otw|ke)\s+/iu',
            '/^(daerah|kota|wilayah|kabupaten)\s+/iu',
            '/^(di)\s+(daerah\s+|kota\s+|wilayah\s+)?/iu',
        ];

        foreach ($patterns as $pattern) {
            $cleaned = preg_replace($pattern, '', $cleaned);
        }

        $cleaned = preg_replace('/\s+(dong|ya|yah|nih|sih|deh|aja|saja|dulu|tolong|kak|min)[\.!\?]?$/iu', '', trim($cleaned));
        return trim($cleaned, " \t\n\r\0\x0B.,!?");
    }

    /**
     * Geocode a location name to coordinates using Nominatim with resilient multi-step fallback
     */
    public function geocodeLocation($query)
    {
        $cleaned = $this->extractDestination($query);
        $searchTerms = array_unique(array_filter([$cleaned, $query]));

        foreach ($searchTerms as $term) {
            try {
                $response = Http::get("https://nominatim.openstreetmap.org/search", [
                    'format' => 'json',
                    'q' => $term,
                    'limit' => 1,
                    'accept-language' => 'id'
                ]);

                if ($response->successful() && !empty($response->json())) {
                    $result = $response->json()[0];
                    return [
                        'status' => 'success',
                        'lat' => $result['lat'],
                        'lon' => $result['lon'],
                        'name' => $result['display_name']
                    ];
                }
            } catch (\Exception $e) {
                Log::error('Geocode Error: ' . $e->getMessage());
            }
        }

        // Fallback to Gemini location entity extraction if direct geocode fails
        $key = env('GEMINI_API_KEY');
        if ($key) {
            try {
                $prompt = "Ambil HANYA 1 nama kota, kabupaten, atau daerah tujuan dari kalimat ini: '{$query}'. Jawab HANYA nama lokasinya saja tanpa tanda baca apapun.";
                $extracted = trim($this->callGemini($prompt, $key, ''));
                if (!empty($extracted) && $extracted !== $query) {
                    $response = Http::get("https://nominatim.openstreetmap.org/search", [
                        'format' => 'json',
                        'q' => $extracted,
                        'limit' => 1,
                        'accept-language' => 'id'
                    ]);
                    if ($response->successful() && !empty($response->json())) {
                        $result = $response->json()[0];
                        return [
                            'status' => 'success',
                            'lat' => $result['lat'],
                            'lon' => $result['lon'],
                            'name' => $result['display_name']
                        ];
                    }
                }
            } catch (\Exception $e) {}
        }

        return ['status' => 'error', 'message' => 'Lokasi tidak ditemukan'];
    }

    /**
     * Generate TTS alert narration for environmental warnings
     */
    public function generateAlertNarration($type, $data, $lang = 'id')
    {
        $key = env('GEMINI_API_KEY');

        $langKey = str_starts_with($lang, 'en') ? 'en' : 'id';
        $alertDesc = '';

        if ($type === 'aqi') {
            $val = $data['value'] ?? 150;
            $cat = $data['category'] ?? 'Tidak Sehat';
            $alertDesc = $langKey === 'en'
                ? "Air quality near the user's location has become hazardous. PM2.5 is now at {$val}. Category: {$cat}."
                : "Kualitas udara di sekitar lokasi pengguna memburuk. PM2.5 sekarang di angka {$val}. Kategori: {$cat}.";
        } elseif ($type === 'weather') {
            $cond = $data['condition'] ?? 'Cuaca Buruk';
            $temp = $data['temperature'] ?? 28;
            $alertDesc = $langKey === 'en'
                ? "Weather conditions have changed to {$cond} with temperature {$temp}°C."
                : "Kondisi cuaca berubah menjadi {$cond} dengan suhu {$temp}°C.";
        } elseif ($type === 'traffic') {
            $cong = $data['congestion'] ?? 'Macet';
            $spd = $data['currentSpeed'] ?? 10;
            $alertDesc = $langKey === 'en'
                ? "Traffic conditions have changed to {$cong}. Current speed is {$spd} km/h."
                : "Lalu lintas berubah menjadi {$cong}. Kecepatan saat ini {$spd} km/jam.";
        } else {
            $alertDesc = $langKey === 'en'
                ? "Environmental conditions have changed around your location."
                : "Kondisi lingkungan di sekitar lokasi Anda mengalami perubahan penting.";
        }

        if ($langKey === 'en') {
            $prompt = "You are EchoSense, a warm and friendly voice assistant for the visually impaired. Generate a SHORT, calming alert narration (2-3 sentences max) in English. Be direct, sweet, and caring. Include actionable advice. Use words like degrees Celsius instead of symbols.\n\nAlert: {$alertDesc}\n\nStart directly with 'Attention.' or 'Warning.' — no greetings.";
        } else {
            $prompt = "Kamu adalah EchoSense, sahabat suara tunanetra yang sangat ramah, hangat, dan peduli. Buat narasi peringatan SINGKAT yang menenangkan dan manis (2-3 kalimat maksimal) dalam Bahasa Indonesia. Langsung ke inti dan berikan saran tindakan yang penuh perhatian. Tulis suhu dengan kata 'derajat Celcius', jangan gunakan simbol (°C) atau markdown (**).\n\nPeringatan: {$alertDesc}\n\nMulai langsung dengan 'Perhatian.' atau 'Peringatan.' — tanpa sapaan.";
        }

        $result = $this->callGemini($prompt, $key, $alertDesc);
        return $this->sanitizeForTTS($result, $langKey);
    }

    /**
     * Generate destination briefing comparing origin and destination conditions
     */
    public function generateDestinationBriefing($destData, $destName, $lang = 'id')
    {
        $key = env('GEMINI_API_KEY');
        $aqi = $destData['raw_data']['aqi'];
        $traffic = $destData['raw_data']['traffic'];
        $weather = $destData['raw_data']['weather'];

        if (str_starts_with($lang, 'en')) {
            $prompt = "You are EchoSense, an inclusive voice assistant for the visually impaired. Create a natural, sweet and conversational briefing (2-3 short sentences) in English about conditions at {$destName}.\n\nDestination: {$destName}\n- Air Quality: PM2.5 is {$aqi['pm25']} ({$aqi['category']})\n- Weather: {$weather['condition']}, {$weather['temperature']} degrees Celsius\n- Traffic: {$traffic['congestion']}, speed {$traffic['currentSpeed']} kilometers per hour\n\nInclude sweet, practical travel advice (e.g. bring an umbrella or enjoy the ride). Write temperatures with 'degrees Celsius' and speeds with 'kilometers per hour'. Do not use degree symbols or markdown. Start directly — no greetings.";
            $fallback = "At {$destName}, the weather is currently {$weather['condition']} with a temperature of {$weather['temperature']} degrees Celsius. Air quality is {$aqi['category']} and traffic is {$traffic['congestion']}. Have a wonderful trip and enjoy your day!";
        } else {
            $prompt = "Kamu adalah EchoSense, sahabat suara tunanetra yang sangat ramah, hangat, dan manis.
Buat briefing perjalanan singkat (2-3 kalimat manis dan peduli) untuk tujuan {$destName} dengan sapaan 'kamu'.
Kondisi di {$destName}:
- Kualitas Udara: PM2.5 di angka {$aqi['pm25']} ({$aqi['category']}).
- Cuaca: {$weather['condition']} dengan suhu {$weather['temperature']} derajat Celcius.
- Lalu Lintas: {$traffic['congestion']}, kecepatan sekitar {$traffic['currentSpeed']} kilometer per jam.

Aturan penting:
1. Berikan perhatian manis dan saran praktis (misal: membawa payung jika hujan, menikmati jalan jika lancar, atau tetap santai jika padat).
2. Tulis suhu dengan kata 'derajat Celcius', dan kecepatan dengan 'kilometer per jam'. Jangan gunakan simbol derajat (°C) atau markdown bintang (**).
3. Mulai langsung tanpa sapaan halo.";
            $fallback = "Di {$destName} saat ini cuaca {$weather['condition']} dengan suhu {$weather['temperature']} derajat Celcius. Kualitas udara {$aqi['category']} dan lalu lintas {$traffic['congestion']}. " . ($weather['condition'] === 'Hujan Ringan' || $weather['condition'] === 'Hujan Deras' ? "Jangan lupa siapkan payung ya agar perjalananmu tetap nyaman." : "Semoga perjalananmu menyenangkan dan kamu bisa santai ya!");
        }

        $result = $this->callGemini($prompt, $key, $fallback);
        return $this->sanitizeForTTS($result, $lang);
    }

    /**
     * Generate the onboarding greeting message
     */
    public function generateOnboardingGreeting($lang = 'id')
    {
        if (str_starts_with($lang, 'en')) {
            $text = "Hello! Welcome to EchoSense. I am your inclusive voice assistant that helps you understand the environmental conditions around you in real-time. I can provide updates on air quality, weather, and traffic conditions. Please note that as an AI-powered system, I may occasionally make mistakes or experience slight data delays, so always prioritize your safety. How it works: you can listen to my voice guidance and speak directly whenever the microphone is active after the chime, or by pressing the spacebar on your keyboard. I am currently detecting your location and environmental data in the background.";
        } else {
            $text = "Halo! Selamat datang di EchoSense. Saya adalah asisten suara inklusif yang membantu Anda memahami kondisi lingkungan sekitar secara real-time. Saya dapat memberikan informasi tentang kualitas udara, cuaca, dan kondisi lalu lintas. Harap diperhatikan bahwa sistem berbasis kecerdasan buatan ini dapat menimbulkan kesalahan informasi atau keterlambatan data, sehingga tetap utamakan keselamatan dan intuisi Anda. Cara kerjanya, Anda cukup mendengarkan panduan suara saya dan dapat berbicara langsung saat mikrofon aktif setelah bunyi nada, atau dengan menekan tombol spasi di keyboard. Saat ini, saya sedang mendeteksi lokasi dan data lingkungan Anda di latar belakang.";
        }

        return $this->sanitizeForTTS($text, $lang);
    }

    /**
     * Process voice command text and determine intent
     */
    public function processVoiceIntent($text, $lang = 'id')
    {
        $textLower = mb_strtolower(trim($text));

        // 1. Precise Affirmative responses (e.g. 'ya', 'iya benar', 'betul')
        if (preg_match('/^(ya|iya|benar|betul|bener|yoi|yes|ok|oke|yep|yup)(\s+(ya|iya|benar|betul|bener|dong|sih|deh|aja|saja|banget|kok))*$/iu', $textLower)) {
            return ['intent' => 'confirm_yes', 'text' => $text];
        }

        // 2. Precise Negative responses (e.g. 'tidak', 'bukan', 'salah', 'nggak')
        if (preg_match('/^(tidak|bukan|salah|no|nope|nggak|enggak|gak|ndak)(\s+(tidak|bukan|salah|kok|dong|sih|deh|aja|saja))*$/iu', $textLower)) {
            return ['intent' => 'confirm_no', 'text' => $text];
        }

        // 3. Current condition queries
        $conditionQueries = ['kondisi', 'bagaimana', 'cuaca', 'udara', 'lalu lintas', 'macet', 'hujan', 'panas', 'suhu', 'how', 'condition', 'weather', 'traffic', 'sekarang', 'saat ini'];
        foreach ($conditionQueries as $word) {
            if (str_contains($textLower, $word)) {
                return ['intent' => 'query_condition', 'text' => $text];
            }
        }

        // 4. Help command
        $helpQueries = ['bantuan', 'bantu', 'help', 'tolong', 'apa saja', 'bisa apa', 'panduan'];
        foreach ($helpQueries as $word) {
            if (str_contains($textLower, $word)) {
                return ['intent' => 'help', 'text' => $text];
            }
        }

        // 5. Destination intent (loosely extracted)
        $extractedDest = $this->extractDestination($text);
        if (!empty($extractedDest)) {
            return ['intent' => 'set_destination', 'destination' => $extractedDest, 'text' => $text];
        }

        // Default: treat as general input
        return ['intent' => 'general_input', 'text' => $text];
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

    private function generateVoiceBriefing($aqi, $traffic, $weather, $lang = 'id')
    {
        $key = env('GEMINI_API_KEY');
        if (str_starts_with($lang, 'en')) {
            $weatherEn = match($weather['condition']) {
                'Hujan Ringan' => 'Light Rain',
                'Hujan Deras' => 'Heavy Rain',
                'Badai Petir' => 'Thunderstorm',
                'Berkabut' => 'Foggy',
                'Cerah Berawan' => 'Partly Cloudy',
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
            $prompt = "You are EchoSense, a warm and friendly voice companion for the visually impaired.
Create a sweet, natural, and caring environmental briefing (2-3 short sentences) in English.
Data:
- Air Quality: PM2.5 is {$aqi['pm25']} (Status: {$aqiEn}).
- Traffic: Status: {$trafficEn}.
- Weather: Condition: {$weatherEn}, Temp: {$weather['temperature']} degrees Celsius.

Rules:
- Be caring and give practical advice (e.g., remember an umbrella or jacket, stay hydrated, or enjoy the fresh air).
- Write temperatures as 'degrees Celsius' without symbols. Do not use degree symbols or markdown (**).
- Start directly — no greetings.";
            $fallback = "Weather around you is {$weatherEn} at {$weather['temperature']} degrees Celsius. Traffic is {$trafficEn}, and air quality is {$aqiEn}. Have a relaxing day!";
        } else {
            $prompt = "Kamu adalah EchoSense, sahabat suara tunanetra yang sangat ramah, hangat, manis, dan peduli.
Buat ringkasan kondisi lingkungan singkat (2-3 kalimat santai dan manis) menggunakan sapaan 'kamu'.
Kondisi saat ini:
- Kualitas Udara: PM2.5 adalah {$aqi['pm25']} ({$aqi['category']}).
- Lalu Lintas: {$traffic['congestion']}.
- Cuaca: {$weather['condition']} dengan suhu {$weather['temperature']} derajat Celcius.

Aturan penting:
1. Berikan perhatian manis dan saran praktis sesuai kondisi:
   - Jika hujan atau mendung: ingatkan dengan manis untuk membawa payung atau pakaian hangat agar tidak kedinginan.
   - Jika cerah atau panas: ingatkan untuk minum air yang cukup dan kamu bisa santai menikmati hari.
   - Jika udara segar/baik: beri tahu bahwa udaranya sejuk dan sangat nyaman untuk santai beraktivitas.
   - Jika udara buruk/berdebu: ingatkan dengan lembut untuk mengenakan masker.
   - Jika lalu lintas lancar: sampaikan perjalananmu akan nyaman dan santai.
   - Jika macet: beri semangat agar tetap tenang dan sabar di perjalanan.
2. Gunakan gaya bahasa tutur lisan yang mengalir manis, bersahabat, dan menenangkan.
3. Jangan gunakan tanda kurung, simbol derajat (°C), atau markdown bintang (**). Tulis suhu dengan 'derajat Celcius'. Mulai langsung tanpa sapaan halo.";

            $fallback = "Cuaca di sekitarmu saat ini {$weather['condition']} dengan suhu {$weather['temperature']} derajat Celcius. Kualitas udara {$aqi['category']} dan jalanan {$traffic['congestion']}. " . ($weather['condition'] === 'Hujan Ringan' || $weather['condition'] === 'Hujan Deras' ? "Jangan lupa siapkan payung ya agar tetap nyaman." : "Kamu bisa santai beraktivitas hari ini.");
        }

        $result = $this->callGemini($prompt, $key, $fallback);
        return $this->sanitizeForTTS($result, $lang);
    }

    /**
     * Sweetly answer user conversational queries during monitoring mode
     */
    public function answerUserQuery($query, $currentData, $lang = 'id')
    {
        $key = env('GEMINI_API_KEY');
        $raw = $currentData['raw_data'] ?? [];
        $aqi = $raw['aqi'] ?? ['pm25' => 20, 'category' => 'Baik'];
        $weather = $raw['weather'] ?? ['condition' => 'Cerah', 'temperature' => 28];
        $traffic = $raw['traffic'] ?? ['congestion' => 'Lancar', 'currentSpeed' => 35];

        if (str_starts_with($lang, 'en')) {
            $prompt = "You are EchoSense, a warm and sweet voice assistant for the visually impaired.
Answer the user's question directly, sweetly, and concisely (1-3 sentences).
User question: '{$query}'

Current environment data:
- Weather: {$weather['condition']}, {$weather['temperature']} degrees Celsius.
- Air Quality: PM2.5 is {$aqi['pm25']} ({$aqi['category']}).
- Traffic: {$traffic['congestion']}, speed {$traffic['currentSpeed']} kilometers per hour.

Rules:
- Give sweet, caring advice.
- Write temperatures with 'degrees Celsius' without degree symbols or markdown (**).
- Speak naturally and warmly like a close friend.";
            $fallback = "Currently the weather is {$weather['condition']} with a temperature of {$weather['temperature']} degrees Celsius. Air quality is {$aqi['category']} and traffic is {$traffic['congestion']}. Stay safe and comfortable!";
        } else {
            $prompt = "Kamu adalah EchoSense, sahabat suara untuk tunanetra yang sangat ramah, hangat, manis, dan peduli.
Jawab pertanyaan pengguna secara ringkas, manis, dan jelas (1-3 kalimat). Gunakan sapaan 'kamu'.
Pertanyaan pengguna: '{$query}'

Data lingkungan saat ini di lokasi pengguna:
- Cuaca: {$weather['condition']}, suhu {$weather['temperature']} derajat Celcius.
- Kualitas Udara: PM2.5 di angka {$aqi['pm25']} ({$aqi['category']}).
- Lalu Lintas: {$traffic['congestion']}, kecepatan {$traffic['currentSpeed']} kilometer per jam.

Aturan penting:
- Berikan saran praktis dan perhatian manis (misal: bawa payung jika tanya cuaca dan hujan, atau jalanan lancar untuk santai bepergian).
- Tulis suhu dengan kata 'derajat Celcius', kecepatan dengan 'kilometer per jam'. Jangan gunakan simbol (°C) atau markdown (**).
- Jangan awali dengan sapaan kaku, bicaralah langsung seperti teman baik yang ramah.";

            $fallback = "Saat ini cuaca {$weather['condition']} dengan suhu {$weather['temperature']} derajat Celcius. Kualitas udara {$aqi['category']} dan jalanan {$traffic['congestion']}. " . ($weather['condition'] === 'Hujan Ringan' || $weather['condition'] === 'Hujan Deras' ? "Jangan lupa pakai payung ya." : "Kamu bisa santai beraktivitas hari ini.");
        }

        $result = $this->callGemini($prompt, $key, $fallback);
        return $this->sanitizeForTTS($result, $lang);
    }

    /**
     * Call Gemini API with a prompt and return text, or fallback on error
     */
    private function callGemini($prompt, $key, $fallback)
    {
        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json'
            ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key={$key}", [
                'contents' => [
                    ['parts' => [['text' => $prompt]]]
                ]
            ]);

            if ($response->successful()) {
                $candidates = $response->json('candidates');
                if (!empty($candidates)) {
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

        return $fallback;
    }
}
