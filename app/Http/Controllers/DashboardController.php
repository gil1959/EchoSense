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
     * Mengambil data ringkasan lingkungan untuk suatu koordinat lokasi
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
     * Mengambil data tujuan: geocoding dan ringkasan kondisi di lokasi tujuan
     */
    public function getDestinationData(Request $request)
    {
        $destination = $request->input('destination');
        $lang = $request->input('lang', 'id');

        if (!$destination) {
            return response()->json(['error' => 'Destination is required'], 400);
        }

        // Ekstraksi dan geocoding nama lokasi tujuan
        $cleanedDest = $this->echoSense->extractDestination($destination);
        $geocoded = $this->echoSense->geocodeLocation($destination);

        if ($geocoded['status'] !== 'success') {
            return response()->json([
                'error' => 'Lokasi tidak ditemukan',
                'fallback_message' => "Maaf, aku belum dapat menemukan informasi untuk lokasi {$cleanedDest}. Kamu bisa coba sebutkan nama kota atau daerah tujuan lainnya ya."
            ], 404);
        }

        // Mengambil data lingkungan lokasi tujuan
        $envData = $this->echoSense->getEnvironmentData($geocoded['lat'], $geocoded['lon'], $lang);

        // Membuat narasi ringkasan kondisi tujuan
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
     * Memproses perintah suara pengguna dan menentukan intensi
     */
    public function processVoiceCommand(Request $request)
    {
        $text = $request->input('text');
        $lang = $request->input('lang', 'id');

        if (!$text) {
            return response()->json(['error' => 'Text input is required'], 400);
        }

        $intent = $this->echoSense->processVoiceIntent($text, $lang);

        // Jika menanyakan kondisi dan data tersedia, buat jawaban ramah langsung
        if ($intent['intent'] === 'query_condition' && $request->has('current_data')) {
            $intent['answer'] = $this->echoSense->answerUserQuery($text, $request->input('current_data'), $lang);
        }

        return response()->json($intent);
    }

    /**
     * Menghasilkan narasi peringatan kondisi lingkungan berbahaya
     */
    public function generateAlert(Request $request)
    {
        $type = $request->input('type'); 
        $data = $request->input('data');
        $lang = $request->input('lang', 'id');

        if (!$type || !$data) {
            return response()->json(['error' => 'Type and data are required'], 400);
        }

        $narration = $this->echoSense->generateAlertNarration($type, $data, $lang);

        return response()->json(['narration' => $narration]);
    }

    /**
     * Mengambil daftar pilihan suara dari ElevenLabs
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
     * Menghasilkan audio ucapan melalui ElevenLabs
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

        // Sanitasi teks agar pelafalan angka dan satuan terdengar alami
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
            'model_id' => 'eleven_multilingual_v2',
        ]);
        
        if ($response->successful()) {
            // Simpan audio ke cache selama 7 hari untuk menghemat kuota
            \Illuminate\Support\Facades\Cache::put($cacheKey, $response->body(), 86400 * 7);
            return response($response->body(), 200)->header('Content-Type', 'audio/mpeg');
        }
        
        $errorBody = $response->json();
        $errorMsg = $errorBody['detail']['message'] ?? 'ElevenLabs API Error';
        
        return response()->json(['error' => $errorMsg], $response->status());
    }
}

