<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class EchoSenseService
{
    /**
     * Mengambil gabungan data lingkungan untuk suatu lokasi koordinat
     */
    public function getEnvironmentData($lat, $lon, $lang = 'id')
    {
        // Kualitas udara dari OpenAQ
        $aqiData = $this->fetchOpenAQ($lat, $lon);
        
        // Lalu lintas dari TomTom
        $trafficData = $this->fetchTomTom($lat, $lon);
        
        // Cuaca dari Open-Meteo
        $weatherData = $this->fetchWeather($lat, $lon);

        // Ringkasan suara dari Gemini
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
     * Mengubah angka bulat menjadi kata terbilang dalam bahasa Indonesia
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
     * Mengubah angka desimal atau pecahan menjadi kata terbilang dalam bahasa Indonesia
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
     * Sanitasi teks agar pelafalan oleh mesin TTS terdengar fasih dan alami
     */
    public function sanitizeForTTS($text, $lang = 'id')
    {
        if (empty($text)) return '';

        // Hapus karakter format markdown
        $text = preg_replace('/[*_#`~>]/', '', $text);

        if (str_starts_with($lang, 'en')) {
            $text = preg_replace('/(\d+(?:[\.,]\d+)?)\s*°\s*C\b/iu', '$1 degrees Celsius', $text);
            $text = preg_replace('/(\d+(?:[\.,]\d+)?)\s*km\/h\b/iu', '$1 kilometers per hour', $text);
            $text = preg_replace('/(\d+(?:[\.,]\d+)?)\s*%/iu', '$1 percent', $text);
            $text = preg_replace('/PM\s*2[\.,]5/iu', 'PM two point five particles', $text);
            $text = preg_replace('/AQI\s*(\d+)/iu', 'air quality index $1', $text);
            return trim($text);
        }

        // Konversi suhu ke terbilang kata
        $text = preg_replace_callback('/(\d+(?:[\.,]\d+)?)\s*(?:°\s*C|%\s*c|derajat\s*Celcius|derajat\s*c)\b/iu', function($m) {
            return $this->numberToIndonesianWords($m[1]) . ' derajat Celcius';
        }, $text);

        // Konversi kecepatan ke terbilang kata
        $text = preg_replace_callback('/(\d+(?:[\.,]\d+)?)\s*(?:km\/j(?:am)?|km\/h|kilometer\s*per\s*jam)\b/iu', function($m) {
            return $this->numberToIndonesianWords($m[1]) . ' kilometer per jam';
        }, $text);

        // Konversi persentase ke terbilang kata
        $text = preg_replace_callback('/(\d+(?:[\.,]\d+)?)\s*%/iu', function($m) {
            return $this->numberToIndonesianWords($m[1]) . ' persen';
        }, $text);

        // Standarisasi istilah kualitas udara
        $text = preg_replace('/PM\s*2[\.,]5/iu', 'partikel PM dua koma lima', $text);
        $text = preg_replace_callback('/(?:indeks\s+)?AQI\s*(\d+)/iu', function($m) {
            return 'indeks kualitas udara ' . $this->numberToIndonesianWords($m[1]);
        }, $text);

        // Singkatan umum
        $text = preg_replace('/\bdll\.?\b/iu', 'dan lain-lain', $text);
        $text = preg_replace('/\bdst\.?\b/iu', 'dan seterusnya', $text);
        $text = preg_replace('/\bsbb\.?\b/iu', 'sebagai berikut', $text);

        // Konversi angka mandiri yang tersisa menjadi kata terbilang
        $text = preg_replace_callback('/\b\d+(?:[\.,]\d+)?\b/u', function($m) {
            return $this->numberToIndonesianWords($m[0]);
        }, $text);

        return trim($text);
    }

    /**
     * Mengekstrak nama lokasi tujuan dari percakapan santai
     */
    public function extractDestination($text)
    {
        $cleaned = trim($text);
        $cleaned = trim($cleaned, " \t\n\r\0\x0B.,!?");

        // Pola awalan percakapan umum bahasa Indonesia
        $patterns = [
            '/^(saya|aku|kami|kita)\s+(rencana|jadwal)?\s*(mau|ingin|pengen|akan|hendak)?\s*(pergi\s+ke|menuju\s+ke|jalan\s+ke|berangkat\s+ke|ke|menuju|otw\s+ke|otw|arah\s+ke|arah)\s+/iu',
            '/^(rencana|jadwal)?\s*(mau|ingin|pengen|akan|hendak)?\s*(pergi\s+ke|menuju\s+ke|jalan\s+ke|berangkat\s+ke|ke|menuju|otw\s+ke|otw|arah\s+ke|arah)\s+/iu',
            '/^(mau|ingin|pengen|akan|rencana|hendak)\s+(ke|jalan|pergi|otw|berangkat)\s+/iu',
            '/^(tujuan\s+saya|tujuannya|tujuan\s+aku|tujuanku|tujuan\s+kami|tujuan\s+kita|tujuan|tujuannya\s+ke|tujuan\s+ke)\s+/iu',
            '/^(tolong\s+)?(cek|periksa|lihat|bagaimana\s+di|kondisi\s+di|cek\s+di|info\s+di)\s+/iu',
            '/^(pergi\s+ke|menuju\s+ke|jalan\s+ke|berangkat\s+ke|arah\s+ke|arah|menuju|otw\s+ke|otw|ke)\s+/iu',
            '/^(di)\s+(daerah\s+|kota\s+|wilayah\s+)?/iu',
            '/^(mau|ingin|pengen)\s+/iu',
        ];

        foreach ($patterns as $pattern) {
            $cleaned = preg_replace($pattern, '', $cleaned);
        }

        // Bersihkan partikel santai di akhir
        $cleaned = preg_replace('/\s+(dong|ya|yah|nih|sih|deh|aja|saja|dulu|tolong|kak|min|bro|sis)[\.!\?]?$/iu', '', trim($cleaned));
        
        // Bersihkan kata awalan wilayah di awal
        $cleaned = preg_replace('/^(ke\s+)?(kota|daerah|wilayah|kabupaten)\s+/iu', '', trim($cleaned));
        $cleaned = preg_replace('/^ke\s+/iu', '', trim($cleaned));

        return trim($cleaned, " \t\n\r\0\x0B.,!?");
    }

    /**
     * Mengubah nama lokasi menjadi koordinat lintang dan bujur
     */
    public function geocodeLocation($query)
    {
        $cleaned = $this->extractDestination($query);
        $searchTerms = [];
        
        if (!empty($cleaned)) {
            $searchTerms[] = $cleaned;
            $stripped = preg_replace('/^(kota|kabupaten|daerah|wilayah)\s+/iu', '', $cleaned);
            if (!empty($stripped) && $stripped !== $cleaned) {
                $searchTerms[] = $stripped;
            }
        }
        $searchTerms[] = $query;
        $searchTerms = array_unique(array_filter($searchTerms));

        // 1. Coba pencarian langsung ke Nominatim dengan header User-Agent valid
        foreach ($searchTerms as $term) {
            try {
                $response = Http::timeout(4)->withHeaders([
                    'User-Agent' => 'EchoSense-App/1.0 (contact: support@echosense.local)'
                ])->get("https://nominatim.openstreetmap.org/search", [
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

        // 2. Ekstraksi entitas lokasi melalui Gemini jika pencarian langsung belum berhasil
        $key = env('GEMINI_API_KEY');
        if ($key) {
            try {
                $prompt = "Ambil HANYA 1 nama kota, kabupaten, atau daerah tujuan dari kalimat ini: '{$query}'. Jawab HANYA nama lokasinya saja tanpa kata tambahan atau tanda baca apapun. Contoh: jika input 'mau ke kota palembang', jawab 'Palembang'.";
                $extracted = trim($this->callGemini($prompt, $key, ''));
                $extracted = trim($extracted, " \t\n\r\0\x0B.,!?'\"");
                if (!empty($extracted)) {
                    $response = Http::timeout(4)->withHeaders([
                        'User-Agent' => 'EchoSense-App/1.0 (contact: support@echosense.local)'
                    ])->get("https://nominatim.openstreetmap.org/search", [
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
            } catch (\Exception $e) {
                Log::error('Geocode Gemini Error: ' . $e->getMessage());
            }
        }

        return ['status' => 'error', 'message' => 'Lokasi tidak ditemukan'];
    }

    /**
     * Menghasilkan narasi peringatan kondisi lingkungan
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
            $prompt = "You are EchoSense, a friendly, respectful, and helpful voice assistant for the visually impaired. Generate a SHORT, calming alert narration (2-3 sentences max) in English. Be clear, calm, and supportive. Include actionable advice. Do not use terms of endearment like 'darling', 'honey', or 'sweetheart'. Use words like degrees Celsius instead of symbols.\n\nAlert: {$alertDesc}\n\nStart directly with 'Attention.' or 'Warning.' — no greetings.";
        } else {
            $prompt = "Kamu adalah EchoSense, asisten suara inklusif untuk tunanetra yang ramah, sopan, dan peduli. Buat narasi peringatan SINGKAT yang menenangkan dan jelas (2-3 kalimat maksimal) dalam Bahasa Indonesia. Langsung ke inti dan berikan saran tindakan yang bermanfaat. Tulis suhu dengan kata 'derajat Celcius', jangan gunakan simbol (°C) atau markdown (**). DILARANG KERAS menggunakan kata 'sayang', 'sayangku', atau panggilan berlebihan lainnya.\n\nPeringatan: {$alertDesc}\n\nMulai langsung dengan 'Perhatian.' atau 'Peringatan.' — tanpa sapaan.";
        }

        $result = $this->callGemini($prompt, $key, $alertDesc);
        return $this->sanitizeForTTS($result, $langKey);
    }

    /**
     * Menghasilkan ringkasan kondisi lingkungan di lokasi tujuan
     */
    public function generateDestinationBriefing($destData, $destName, $lang = 'id')
    {
        $key = env('GEMINI_API_KEY');
        $aqi = $destData['raw_data']['aqi'];
        $traffic = $destData['raw_data']['traffic'];
        $weather = $destData['raw_data']['weather'];

        if (str_starts_with($lang, 'en')) {
            $prompt = "You are EchoSense, an inclusive voice assistant for the visually impaired. Create a natural, clear and friendly briefing (2-3 short sentences) in English about conditions at {$destName}.\n\nDestination: {$destName}\n- Air Quality: PM2.5 is {$aqi['pm25']} ({$aqi['category']})\n- Weather: {$weather['condition']}, {$weather['temperature']} degrees Celsius\n- Traffic: {$traffic['congestion']}, speed {$traffic['currentSpeed']} kilometers per hour\n\nInclude practical travel advice (e.g. bring an umbrella, wear a mask if air is bad, or enjoy the ride). Do not use terms of endearment. Write temperatures with 'degrees Celsius' and speeds with 'kilometers per hour'. Do not use degree symbols or markdown. Start directly — no greetings.";
            $fallback = "At {$destName}, the weather is currently {$weather['condition']} with a temperature of {$weather['temperature']} degrees Celsius. Air quality is {$aqi['category']} and traffic is {$traffic['congestion']}. Have a wonderful trip and enjoy your day!";
        } else {
            $prompt = "Kamu adalah EchoSense, asisten suara inklusif untuk tunanetra yang ramah, sopan, dan peduli.
Buat ringkasan kondisi perjalanan singkat (2-3 kalimat yang bersahabat dan santai) untuk tujuan {$destName}.
Kondisi di {$destName}:
- Kualitas Udara: PM2.5 di angka {$aqi['pm25']} ({$aqi['category']}).
- Cuaca: {$weather['condition']} dengan suhu {$weather['temperature']} derajat Celcius.
- Lalu Lintas: {$traffic['congestion']}, kecepatan sekitar {$traffic['currentSpeed']} kilometer per jam.

Aturan penting:
1. Berikan perhatian dan saran praktis (misal: ingatkan dengan sopan untuk memakai masker jika kualitas udara tidak sehat atau buruk, membawa payung jika hujan, atau menikmati jalan jika lancar).
2. Tulis suhu dengan kata 'derajat Celcius', dan kecepatan dengan 'kilometer per jam'. Jangan gunakan simbol derajat (°C) atau markdown bintang (**).
3. DILARANG KERAS menggunakan kata panggilan sayang seperti 'sayang', 'sayangku', 'cintaku', 'manisku', atau sejenisnya. Bicaralah wajar dan bersahabat dengan sapaan 'kamu'.
4. Mulai langsung tanpa sapaan halo.";
            $fallback = "Di {$destName} saat ini cuaca {$weather['condition']} dengan suhu {$weather['temperature']} derajat Celcius. Kualitas udara {$aqi['category']} dan lalu lintas {$traffic['congestion']}. " . ($weather['condition'] === 'Hujan Ringan' || $weather['condition'] === 'Hujan Deras' ? "Jangan lupa siapkan payung ya agar perjalananmu tetap nyaman." : ($aqi['category'] === 'Tidak Sehat' || $aqi['category'] === 'Sangat Tidak Sehat' ? "Jangan lupa pakai masker ya karena kualitas udara sedang kurang baik." : "Semoga perjalananmu menyenangkan dan kamu bisa santai ya!"));
        }

        $result = $this->callGemini($prompt, $key, $fallback);
        return $this->sanitizeForTTS($result, $lang);
    }

    /**
     * Menghasilkan teks sapaan pengenalan awal aplikasi
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
     * Memproses teks perintah suara dan menentukan intensi pengguna
     */
    public function processVoiceIntent($text, $lang = 'id')
    {
        $textLower = mb_strtolower(trim($text));

        // Jawaban konfirmasi setuju (ya, benar, dll)
        if (preg_match('/^(ya|iya|benar|betul|bener|yoi|yes|ok|oke|yep|yup)(\s+(ya|iya|benar|betul|bener|dong|sih|deh|aja|saja|banget|kok))*$/iu', $textLower)) {
            return ['intent' => 'confirm_yes', 'text' => $text];
        }

        // Jawaban konfirmasi menolak (tidak, bukan, dll)
        if (preg_match('/^(tidak|bukan|salah|no|nope|nggak|enggak|gak|ndak)(\s+(tidak|bukan|salah|kok|dong|sih|deh|aja|saja))*$/iu', $textLower)) {
            return ['intent' => 'confirm_no', 'text' => $text];
        }

        // Pertanyaan kondisi lingkungan
        $conditionQueries = ['kondisi', 'bagaimana', 'cuaca', 'udara', 'lalu lintas', 'macet', 'hujan', 'panas', 'suhu', 'how', 'condition', 'weather', 'traffic', 'sekarang', 'saat ini'];
        foreach ($conditionQueries as $word) {
            if (str_contains($textLower, $word)) {
                return ['intent' => 'query_condition', 'text' => $text];
            }
        }

        // Permintaan bantuan
        $helpQueries = ['bantuan', 'bantu', 'help', 'tolong', 'apa saja', 'bisa apa', 'panduan'];
        foreach ($helpQueries as $word) {
            if (str_contains($textLower, $word)) {
                return ['intent' => 'help', 'text' => $text];
            }
        }

        // Deteksi lokasi tujuan
        $extractedDest = $this->extractDestination($text);
        if (!empty($extractedDest)) {
            return ['intent' => 'set_destination', 'destination' => $extractedDest, 'text' => $text];
        }

        // Input umum lainnya
        return ['intent' => 'general_input', 'text' => $text];
    }

    private function fetchOpenAQ($lat, $lon)
    {
        // Mengambil data kualitas udara real-time dari Open-Meteo Air Quality
        try {
            $response = Http::timeout(5)->get("https://air-quality-api.open-meteo.com/v1/air-quality", [
                'latitude' => $lat,
                'longitude' => $lon,
                'current' => 'pm2_5,us_aqi'
            ]);

            if ($response->successful()) {
                $current = $response->json('current');
                $pm25 = isset($current['pm2_5']) ? round((float)$current['pm2_5'], 1) : null;
                $usAqi = isset($current['us_aqi']) ? (int)$current['us_aqi'] : null;

                if ($pm25 !== null) {
                    // Penentuan kategori kualitas udara berdasarkan standar ISPU dan US AQI
                    $category = 'Baik';
                    if ($pm25 > 150.4 || ($usAqi && $usAqi > 200)) {
                        $category = 'Sangat Tidak Sehat';
                    } elseif ($pm25 > 55.4 || ($usAqi && $usAqi > 100)) {
                        $category = 'Tidak Sehat';
                    } elseif ($pm25 > 15.5 || ($usAqi && $usAqi > 50)) {
                        $category = 'Sedang';
                    }

                    return [
                        'status' => 'success',
                        'pm25' => $pm25,
                        'aqi' => $usAqi,
                        'category' => $category,
                        'source' => 'Open-Meteo'
                    ];
                }
            }
        } catch (\Exception $e) {
            Log::error('Air Quality Error (Open-Meteo): ' . $e->getMessage());
        }

        // Coba layanan OpenAQ v3 jika tersedia API key
        $key = env('OPENAQ_API_KEY');
        if ($key) {
            try {
                $resV3 = Http::timeout(5)->withHeaders([
                    'X-API-Key' => $key
                ])->get("https://api.openaq.org/v3/locations", [
                    'coordinates' => "{$lat},{$lon}",
                    'radius' => 25000,
                    'limit' => 1
                ]);

                if ($resV3->successful() && !empty($resV3->json('results'))) {
                    $locId = $resV3->json('results')[0]['id'];
                    $resLatest = Http::timeout(5)->withHeaders([
                        'X-API-Key' => $key
                    ])->get("https://api.openaq.org/v3/locations/{$locId}/latest");

                    if ($resLatest->successful() && !empty($resLatest->json('results'))) {
                        foreach ($resLatest->json('results') as $item) {
                            if (isset($item['value']) && $item['value'] > 0) {
                                $pm25 = round((float)$item['value'], 1);
                                $category = $pm25 > 150.4 ? 'Sangat Tidak Sehat' : ($pm25 > 55.4 ? 'Tidak Sehat' : ($pm25 > 15.5 ? 'Sedang' : 'Baik'));
                                return [
                                    'status' => 'success',
                                    'pm25' => $pm25,
                                    'category' => $category,
                                    'source' => 'OpenAQ'
                                ];
                            }
                        }
                    }
                }
            } catch (\Exception $e) {
                Log::error('Air Quality Error (OpenAQ): ' . $e->getMessage());
            }
        }

        // Data cadangan jika layanan tidak dapat dihubungi
        return ['status' => 'fallback', 'pm25' => 15, 'category' => 'Baik', 'source' => 'fallback'];
    }

    private function fetchTomTom($lat, $lon)
    {
        $key = env('TOMTOM_API_KEY');
        try {
            // Mengambil data arus lalu lintas dari TomTom dengan batas waktu cepat
            $response = Http::timeout(3)->get("https://api.tomtom.com/traffic/services/4/flowSegmentData/absolute/10/json", [
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
            // Mengambil prakiraan cuaca dari Open-Meteo
            $response = Http::timeout(4)->get("https://api.open-meteo.com/v1/forecast", [
                'latitude' => $lat,
                'longitude' => $lon,
                'current_weather' => true,
                'timezone' => 'auto'
            ]);

            if ($response->successful()) {
                $current = $response->json('current_weather');
                $temp = $current['temperature'];
                $code = $current['weathercode'];

                // Pemetaan kode cuaca WMO ke bahasa Indonesia
                $condition = 'Cerah';
                if ($code >= 1 && $code <= 3) $condition = 'Cerah Berawan';
                elseif ($code >= 45 && $code <= 48) $condition = 'Berkabut';
                elseif ($code >= 51 && $code <= 67) $condition = 'Hujan Ringan';
                elseif ($code >= 71 && $code <= 77) $condition = 'Bersalju';
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
            $prompt = "Kamu adalah EchoSense, asisten suara inklusif untuk tunanetra yang ramah, sopan, dan peduli.
Buat ringkasan kondisi lingkungan singkat (2-3 kalimat santai dan informatif) menggunakan sapaan 'kamu'.
Kondisi saat ini:
- Kualitas Udara: PM2.5 adalah {$aqi['pm25']} ({$aqi['category']}).
- Lalu Lintas: {$traffic['congestion']}.
- Cuaca: {$weather['condition']} dengan suhu {$weather['temperature']} derajat Celcius.

Aturan penting:
1. Berikan perhatian dan saran praktis sesuai kondisi:
   - Jika hujan atau mendung: ingatkan untuk membawa payung atau pakaian hangat agar tidak kedinginan.
   - Jika cerah atau panas: ingatkan untuk minum air yang cukup dan santai beraktivitas.
   - Jika udara segar/baik: beri tahu bahwa udaranya sejuk dan sangat nyaman untuk beraktivitas.
   - Jika udara buruk/tidak sehat/berdebu: ingatkan dengan sopan untuk mengenakan masker.
   - Jika lalu lintas lancar: sampaikan perjalanan akan nyaman dan lancar.
   - Jika macet: beri dorongan semangat agar tetap tenang di perjalanan.
2. Gunakan gaya bahasa tutur lisan yang mengalir, bersahabat, sopan, dan menenangkan.
3. DILARANG KERAS menggunakan kata panggilan sayang seperti 'sayang', 'sayangku', 'cintaku', 'manisku', atau sejenisnya.
4. Jangan gunakan tanda kurung, simbol derajat (°C), atau markdown bintang (**). Tulis suhu dengan 'derajat Celcius'. Mulai langsung tanpa sapaan halo.";

            $fallback = "Cuaca di sekitarmu saat ini {$weather['condition']} dengan suhu {$weather['temperature']} derajat Celcius. Kualitas udara {$aqi['category']} dan jalanan {$traffic['congestion']}. " . ($weather['condition'] === 'Hujan Ringan' || $weather['condition'] === 'Hujan Deras' ? "Jangan lupa siapkan payung ya agar tetap nyaman." : "Kamu bisa santai beraktivitas hari ini.");
        }

        $result = $this->callGemini($prompt, $key, $fallback);
        return $this->sanitizeForTTS($result, $lang);
    }

    /**
     * Memberikan jawaban ramah untuk pertanyaan pengguna saat mode pemantauan
     */
    public function answerUserQuery($query, $currentData, $lang = 'id')
    {
        $key = env('GEMINI_API_KEY');
        $raw = $currentData['raw_data'] ?? [];
        $aqi = $raw['aqi'] ?? ['pm25' => 20, 'category' => 'Baik'];
        $weather = $raw['weather'] ?? ['condition' => 'Cerah', 'temperature' => 28];
        $traffic = $raw['traffic'] ?? ['congestion' => 'Lancar', 'currentSpeed' => 35];

        if (str_starts_with($lang, 'en')) {
            $prompt = "You are EchoSense, a friendly, respectful, and helpful voice assistant for the visually impaired.
Answer the user's question directly, clearly, and concisely (1-3 sentences). Do not use terms of endearment.
User question: '{$query}'

Current environment data:
- Weather: {$weather['condition']}, {$weather['temperature']} degrees Celsius.
- Air Quality: PM2.5 is {$aqi['pm25']} ({$aqi['category']}).
- Traffic: {$traffic['congestion']}, speed {$traffic['currentSpeed']} kilometers per hour.

Rules:
- Give practical, supportive advice.
- Write temperatures with 'degrees Celsius' without degree symbols or markdown (**).
- Speak naturally and politely like a reliable assistant.";
            $fallback = "Currently the weather is {$weather['condition']} with a temperature of {$weather['temperature']} degrees Celsius. Air quality is {$aqi['category']} and traffic is {$traffic['congestion']}. Stay safe and comfortable!";
        } else {
            $prompt = "Kamu adalah EchoSense, asisten suara inklusif untuk tunanetra yang ramah, sopan, dan peduli.
Jawab pertanyaan pengguna secara ringkas, sopan, dan jelas (1-3 kalimat). Gunakan sapaan 'kamu'.
Pertanyaan pengguna: '{$query}'

Data lingkungan saat ini di lokasi pengguna:
- Cuaca: {$weather['condition']}, suhu {$weather['temperature']} derajat Celcius.
- Kualitas Udara: PM2.5 di angka {$aqi['pm25']} ({$aqi['category']}).
- Lalu Lintas: {$traffic['congestion']}, kecepatan {$traffic['currentSpeed']} kilometer per jam.

Aturan penting:
- Berikan saran praktis dan perhatian yang bermanfaat (misal: bawa payung jika tanya cuaca dan hujan, atau masker jika kualitas udara tidak sehat).
- Tulis suhu dengan kata 'derajat Celcius', kecepatan dengan 'kilometer per jam'. Jangan gunakan simbol (°C) atau markdown (**).
- DILARANG KERAS menggunakan kata panggilan sayang seperti 'sayang', 'sayangku', 'cintaku', atau sejenisnya. Bicaralah wajar, ramah, dan sopan.";

            $fallback = "Saat ini cuaca {$weather['condition']} dengan suhu {$weather['temperature']} derajat Celcius. Kualitas udara {$aqi['category']} dan jalanan {$traffic['congestion']}. " . ($weather['condition'] === 'Hujan Ringan' || $weather['condition'] === 'Hujan Deras' ? "Jangan lupa pakai payung ya." : "Kamu bisa santai beraktivitas hari ini.");
        }

        $result = $this->callGemini($prompt, $key, $fallback);
        return $this->sanitizeForTTS($result, $lang);
    }

    /**
     * Memanggil API Gemini untuk menghasilkan teks respons dengan model 3.5-flash
     */
    private function callGemini($prompt, $key, $fallback)
    {
        $models = ['gemini-3.5-flash', 'gemini-2.5-flash'];

        foreach ($models as $model) {
            try {
                $response = Http::timeout(8)->withHeaders([
                    'Content-Type' => 'application/json'
                ])->post("https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$key}", [
                    'contents' => [
                        ['parts' => [['text' => $prompt]]]
                    ]
                ]);

                if ($response->successful()) {
                    $candidates = $response->json('candidates');
                    if (!empty($candidates)) {
                        return $candidates[0]['content']['parts'][0]['text'] ?? $fallback;
                    }
                } else {
                    Log::error("Gemini API Error ({$model}): " . $response->body());
                }
            } catch (\Exception $e) {
                Log::error("Gemini Error ({$model}): " . $e->getMessage());
            }
        }

        return $fallback;
    }
}
