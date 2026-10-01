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

    public function getSonificationData(Request $request)
    {
        $lat = $request->query('lat', '-6.1751');
        $lon = $request->query('lon', '106.8272');
        $lang = $request->query('lang', 'id-ID');

        $data = $this->echoSense->getEnvironmentData($lat, $lon, $lang);

        return response()->json($data);
    }
    
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
    
    public function generateTTS(Request $request)
    {
        $key = env('ELEVENLABS_API_KEY');
        if (!$key) {
            return response()->json(['error' => 'API Key not configured'], 500);
        }
        
        $text = $request->input('text');
        $voiceId = $request->input('voice_id');
        
        if (!$text || !$voiceId) {
            return response()->json(['error' => 'Missing text or voice_id'], 400);
        }
        
        $response = \Illuminate\Support\Facades\Http::withHeaders([
            'xi-api-key' => $key,
            'Content-Type' => 'application/json'
        ])->post("https://api.elevenlabs.io/v1/text-to-speech/{$voiceId}?output_format=mp3_44100_128", [
            'text' => $text,
            'model_id' => 'eleven_multilingual_v2', // Supports ID and EN
        ]);
        
        if ($response->successful()) {
            return response($response->body(), 200)->header('Content-Type', 'audio/mpeg');
        }
        
        $errorBody = $response->json();
        $errorMsg = $errorBody['detail']['message'] ?? 'ElevenLabs API Error';
        
        return response()->json(['error' => $errorMsg], $response->status());
    }
}
