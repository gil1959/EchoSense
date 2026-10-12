<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\EchoSenseService;

class DashboardController extends Controller
{
    protected $echoSense;

    public function __construct(EchoSenseService $echoSense)
    {
        $this->echoSense = $echoSense;
    }

    /**
     * Get environment briefing data for a location (replaces old getSonificationData)
     */
    public function getEnvironmentBriefing(Request $request)
    {
        $lat = $request->query('lat', '-6.1751');
        $lon = $request->query('lon', '106.8272');
        $lang = $request->query('lang', 'id-ID');

        $data = $this->echoSense->getEnvironmentData($lat, $lon, $lang);

        return response()->json($data);
    }

    /**
     * Get destination data: geocode + environment briefing for a destination
     */
    public function getDestinationData(Request $request)
    {
        $destination = $request->input('destination');
        $lang = $request->input('lang', 'id');

        if (!$destination) {
            return response()->json(['error' => 'Destination is required'], 400);
        }

        // 1. Geocode the destination name to coordinates with robust extraction
        $cleanedDest = $this->echoSense->extractDestination($destination);
        $geocoded = $this->echoSense->geocodeLocation($destination);

        if ($geocoded['status'] !== 'success') {
            return response()->json([
                'error' => 'Lokasi tidak ditemukan',
                'fallback_message' => "Maaf, aku belum dapat menemukan informasi untuk lokasi {$cleanedDest}. Kamu bisa coba sebutkan nama kota atau daerah tujuan lainnya ya."
            ], 404);
        }

        // 2. Fetch environment data for the destination
        $envData = $this->echoSense->getEnvironmentData($geocoded['lat'], $geocoded['lon'], $lang);

        // 3. Generate destination-specific briefing
        $destBriefing = $this->echoSense->generateDestinationBriefing($envData, $geocoded['name'], $lang);

        return response()->json([
            'destination' => [
                'name' => $geocoded['name'],
                'lat' => $geocoded['lat'],
                'lon' => $geocoded['lon']
            ],
            'raw_data' => $envData['raw_data'],
            'briefing' => $destBriefing
        ]);
    }

    /**
     * Process a voice command from the user
     */
    public function processVoiceCommand(Request $request)
    {
        $text = $request->input('text');
        $lang = $request->input('lang', 'id');

        if (!$text) {
            return response()->json(['error' => 'Text input is required'], 400);
        }

        $intent = $this->echoSense->processVoiceIntent($text, $lang);

        // If user is querying conditions and provided current telemetry, generate immediate warm answer
        if ($intent['intent'] === 'query_condition' && $request->has('current_data')) {
            $intent['answer'] = $this->echoSense->answerUserQuery($text, $request->input('current_data'), $lang);
        }

        return response()->json($intent);
    }

    /**
     * Generate alert narration for environment warnings
     */
    public function generateAlert(Request $request)
    {
        $type = $request->input('type'); // 'aqi', 'weather', 'traffic'
        $data = $request->input('data');
        $lang = $request->input('lang', 'id');

        if (!$type || !$data) {
            return response()->json(['error' => 'Type and data are required'], 400);
        }

        $narration = $this->echoSense->generateAlertNarration($type, $data, $lang);

        return response()->json(['narration' => $narration]);
    }

    /**
     * Get available TTS voices from ElevenLabs
     */
    public function getVoices()
    {
        $key = env('ELEVENLABS_API_KEY');
        if (!$key) {
            return response()->json(['voices' => []]);
        }
        
        $response = \Illuminate\Support\Facades\Http::withHeaders([
            'xi-api-key' => $key
        ])->get('https://api.elevenlabs.io/v1/voices');
        
        if ($response->successful()) {
            return response()->json($response->json());
        }
        
        return response()->json(['voices' => []]);
    }
    
    /**
     * Generate TTS audio via ElevenLabs
     */
    public function generateTTS(Request $request)
    {
        $key = env('ELEVENLABS_API_KEY');
        if (!$key) {
            return response()->json(['error' => 'API Key not configured'], 500);
        }
        
        $rawText = $request->input('text');
        $voiceId = $request->input('voice_id');
        $lang = $request->input('lang', 'id');
        
        if (!$rawText || !$voiceId) {
            return response()->json(['error' => 'Missing text or voice_id'], 400);
        }

        // Sanitize text so ElevenLabs pronounces "30 derajat Celcius", "kilometer per jam", etc. naturally
        $text = $this->echoSense->sanitizeForTTS($rawText, $lang);

        $cacheKey = 'tts_' . md5($voiceId . '_' . trim($text));
        $cachedAudio = \Illuminate\Support\Facades\Cache::get($cacheKey);
        if ($cachedAudio) {
            return response($cachedAudio, 200)->header('Content-Type', 'audio/mpeg');
        }
        
        $response = \Illuminate\Support\Facades\Http::withHeaders([
            'xi-api-key' => $key,
            'Content-Type' => 'application/json'
        ])->post("https://api.elevenlabs.io/v1/text-to-speech/{$voiceId}?output_format=mp3_44100_128", [
            'text' => $text,
            'model_id' => 'eleven_multilingual_v2', // Supports ID and EN
        ]);
        
        if ($response->successful()) {
            \Illuminate\Support\Facades\Cache::put($cacheKey, $response->body(), 86400 * 7); // Simpan 7 hari untuk menghemat kuota
            return response($response->body(), 200)->header('Content-Type', 'audio/mpeg');
        }
        
        $errorBody = $response->json();
        $errorMsg = $errorBody['detail']['message'] ?? 'ElevenLabs API Error';
        
        return response()->json(['error' => $errorMsg], $response->status());
    }
}
