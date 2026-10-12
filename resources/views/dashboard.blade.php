<!DOCTYPE html>

<html lang="id"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>EchoSense - Asisten Suara Lingkungan Aksesibel</title>
<link rel="icon" type="image/png" href="{{ asset('favicon.png') }}"/>
<link href="https://fonts.googleapis.com" rel="preconnect"/>
<link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
<link href="https://fonts.googleapis.com/css2?family=Atkinson+Hyperlegible+Next:ital,wght@0,400..700;1,400..700&amp;display=swap" rel="stylesheet"/>
<!-- Tailwind CSS CDN with forms and container queries plugins -->
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<!-- Tailwind Configuration for EchoSense Design System -->
<script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            deepNavy: '#033067',
            navyHover: '#02234d',
            aquaBlue: '#0675A3',
            aquaHover: '#055e83',
            softCyan: '#14C5D9',
            pageBg: '#F4F9FB',
            cardBg: '#FFFFFF',
            cardBorder: '#D6E6ED',
            textPrimary: '#0A1F33',
            textSecondary: '#42586A',
            trackBg: '#E6F2F7',
            borderInput: '#6B8899',
            sunrise: {
              start: '#FEB161',
              end: '#FD7659'
            },
            tealMint: '#21B9BE'
          },
          fontFamily: {
            sans: ['"Atkinson Hyperlegible Next"', '-apple-system', 'BlinkMacSystemFont', '"Segoe UI"', 'Roboto', 'sans-serif'],
            mono: ['"Atkinson Hyperlegible Next"', 'ui-monospace', 'monospace']
          },
          borderRadius: {
            'card': '20px'
          },
          boxShadow: {
            subtle: '0 1px 3px rgba(3, 48, 103, 0.08)'
          }
        }
      }
    };
  </script>
<style data-purpose="custom-animations">
    @keyframes pulse-mic {
      0%, 100% { box-shadow: 0 0 0 0 rgba(6, 117, 163, 0.5); }
      50% { box-shadow: 0 0 0 16px rgba(6, 117, 163, 0); }
    }
    @keyframes recording-pulse {
      0%, 100% { opacity: 1; }
      50% { opacity: 0.4; }
    }
    .mic-pulsing {
      animation: pulse-mic 1.5s ease-in-out infinite;
    }
    .recording-dot {
      animation: recording-pulse 1s ease-in-out infinite;
    }
    @keyframes fade-in-up {
      from { opacity: 0; transform: translateY(8px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .msg-animate {
      animation: fade-in-up 0.3s ease-out forwards;
    }
    @keyframes typing-dots {
      0%, 80%, 100% { transform: scale(0.6); opacity: 0.4; }
      40% { transform: scale(1); opacity: 1; }
    }
    .typing-dot-1 { animation: typing-dots 1.4s ease-in-out infinite; }
    .typing-dot-2 { animation: typing-dots 1.4s ease-in-out infinite 0.2s; }
    .typing-dot-3 { animation: typing-dots 1.4s ease-in-out infinite 0.4s; }
  </style>
<style data-purpose="accessibility-focus">
    /* High contrast focus ring for accessible keyboard navigation */
    a:focus-visible, button:focus-visible, input:focus-visible {
      outline: 3px solid #0675A3;
      outline-offset: 2px;
    }
  </style>
</head>
<body class="bg-pageBg text-textPrimary font-sans min-h-screen flex flex-col antialiased">
<!-- BEGIN: MainHeader -->
<x-navbar />
<!-- END: MainHeader -->
<!-- BEGIN: MainContentContainer -->
<main class="flex-1 max-w-7xl mx-auto w-full px-4 lg:px-8 py-6 flex flex-col gap-6">
<div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
<!-- BEGIN: LeftSidebar -->
<aside aria-label="Lokasi dan informasi" class="lg:col-span-4 flex flex-col gap-4">
<!-- Lokasi Tersimpan -->
<div class="flex items-center justify-between px-1">
<h2 class="text-sm font-bold text-textPrimary flex items-center gap-2">
<svg aria-hidden="true" class="w-4 h-4 text-aquaBlue" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewbox="0 0 24 24">
<circle cx="12" cy="12" r="10"></circle>
<polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"></polygon>
</svg>
            Lokasi tersimpan
          </h2>
<span id="location-count" class="text-xs text-textSecondary bg-trackBg px-2 py-0.5 rounded-full border border-cardBorder">Memuat...</span>
</div>
<div class="flex flex-col gap-3" id="location-list-container">
<!-- Location cards will be dynamically injected here by Javascript -->
</div>
<!-- Panduan Suara -->
<div class="rounded-card border border-cardBorder bg-cardBg p-4 text-xs text-textSecondary shadow-subtle">
<p class="font-semibold text-textPrimary mb-1">Panduan interaksi suara</p>
<p class="leading-relaxed">Tekan tombol mikrofon atau spasi untuk berbicara dengan EchoSense. Sistem akan merespons dengan narasi suara natural.</p>
</div>
</aside>
<!-- END: LeftSidebar -->
<!-- BEGIN: MainVoicePanel -->
<section aria-label="Panel asisten suara" class="lg:col-span-8 flex flex-col gap-6">
<!-- Voice Conversation Panel (Active Immediately) -->
<div id="voice-panel" class="flex flex-col gap-6">
<!-- Status Header -->
<div class="rounded-card border-2 border-deepNavy bg-cardBg p-5 shadow-subtle flex flex-col gap-4" data-purpose="voice-controller">
<div class="flex flex-wrap items-center justify-between gap-3 border-b border-cardBorder pb-4">
<div>
<span class="text-xs uppercase tracking-wide text-aquaBlue font-semibold">Asisten Suara EchoSense</span>
<h1 class="text-xl md:text-2xl font-bold text-textPrimary mt-0.5" id="voice-header-title">Menginisialisasi...</h1>
</div>
<div class="flex items-center gap-3">
<div id="status-badge" class="flex items-center gap-2 bg-trackBg px-3.5 py-1.5 rounded-full border border-cardBorder text-xs text-textSecondary font-medium">
<span aria-hidden="true" class="inline-block w-2.5 h-2.5 rounded-full bg-tealMint" id="status-dot"></span>
<span id="status-text">Memulai...</span>
</div>
</div>
<!-- Hidden Audio Element for Direct Low-Latency Playback -->
<audio id="echosense-audio" autoplay playsinline preload="auto" class="hidden"></audio>

<!-- Conversation Transcript -->
<div id="transcript-area" class="min-h-[280px] max-h-[420px] overflow-y-auto p-4 rounded-xl bg-trackBg/30 border border-cardBorder space-y-3 scroll-smooth" role="log" aria-live="polite" aria-label="Riwayat percakapan">
<!-- Messages will be injected here -->
</div>

<!-- Voice Action Controls: Play/Pause, Mic, Replay & Text Input Fallback -->
<div class="flex flex-col items-center gap-3 pt-2">
<div class="flex items-center justify-center gap-4">
<!-- Play / Pause Button -->
<button id="btn-play-pause" class="w-12 h-12 rounded-full border-2 border-deepNavy hover:bg-deepNavy/10 text-deepNavy flex items-center justify-center transition shadow-sm focus:outline-none" type="button" aria-label="Jeda atau lanjutkan suara" title="Jeda atau lanjutkan suara">
<svg id="icon-pause" class="w-5 h-5 hidden" fill="currentColor" viewBox="0 0 24 24">
<path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/>
</svg>
<svg id="icon-play" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
<polygon points="5 3 19 12 5 21 5 3"></polygon>
</svg>
</button>

<!-- Main Mic Button -->
<button id="btn-mic" class="w-16 h-16 rounded-full bg-aquaBlue hover:bg-aquaHover text-white flex items-center justify-center transition-all shadow-lg focus:outline-none disabled:opacity-50 disabled:cursor-not-allowed" type="button" aria-label="Tekan untuk berbicara" disabled>
<svg id="mic-icon" class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
<path stroke-linecap="round" stroke-linejoin="round" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/>
</svg>
<svg id="mic-stop-icon" class="w-7 h-7 hidden" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
<rect x="6" y="6" width="12" height="12" rx="2" stroke-linecap="round" stroke-linejoin="round"/>
</svg>
</button>

<!-- Replay Button -->
<button id="btn-replay" class="w-12 h-12 rounded-full border-2 border-slatecol/60 hover:border-deepNavy hover:bg-deepNavy/10 text-deepNavy flex items-center justify-center transition shadow-sm focus:outline-none" type="button" aria-label="Ulangi narasi terakhir" title="Ulangi suara narasi">
<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
<path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
</svg>
</button>
</div>

<!-- Accessible Status Text for Controls -->
<div class="flex items-center gap-4 text-xs text-textSecondary font-medium">
<span id="play-pause-label">Putar / Jeda</span>
<span class="text-cardBorder">•</span>
<span id="mic-label">Bicara (Spasi)</span>
<span class="text-cardBorder">•</span>
<span>Ulangi suara</span>
</div>

<!-- Accessible Text Input Fallback -->
<form id="text-input-form" class="w-full max-w-lg flex items-center gap-2 mt-1" onsubmit="handleTextInputSubmit(event)">
<div class="relative flex-1">
<input type="text" id="manual-text-input" placeholder="Atau ketik jawaban di sini (contoh: Ya / Ke Bekasi)..." class="w-full text-sm rounded-xl border border-cardBorder bg-white px-4 py-2.5 text-textPrimary placeholder:text-textSecondary/60 focus:border-aquaBlue focus:outline-none transition shadow-sm" autocomplete="off" />
</div>
<button type="submit" id="btn-send-text" class="px-4 py-2.5 rounded-xl bg-deepNavy hover:bg-navyHover text-white text-sm font-semibold flex items-center gap-1.5 transition shadow-sm" aria-label="Kirim pesan teks">
<span>Kirim</span>
<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
<line x1="22" y1="2" x2="11" y2="13"></line>
<polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
</svg>
</button>
</form>
</div>
</div>

<!-- 3. Telemetry Metric Grid (3 Data Cards) -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4" data-purpose="telemetry-grid">
<!-- Card 1: Kualitas Udara -->
<article class="rounded-card border border-cardBorder bg-cardBg p-5 shadow-subtle flex flex-col justify-between hover:border-aquaBlue transition">
<div>
<div class="flex items-center justify-between mb-3">
<span class="text-xs font-semibold text-textSecondary">Kualitas udara</span>
<svg aria-hidden="true" class="w-4 h-4 text-sunrise-end" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewbox="0 0 24 24">
<path d="M8 2h8M4 6h16M2 10h20M6 14h12M10 18h4"></path>
</svg>
</div>
<div class="flex items-baseline gap-2 mb-2">
<span class="text-2xl font-bold text-textPrimary" id="ui-aqi-value">AQI --</span>
<span class="text-xs font-bold px-2.5 py-0.5 rounded-full bg-sunrise-start text-deepNavy" id="ui-aqi-category">Memuat</span>
</div>
<p class="text-xs text-textSecondary">Data real-time <span class="font-medium text-textPrimary">OpenAQ API</span></p>
</div>
</article>
<!-- Card 2: Cuaca -->
<article class="rounded-card border border-cardBorder bg-cardBg p-5 shadow-subtle flex flex-col justify-between hover:border-aquaBlue transition">
<div>
<div class="flex items-center justify-between mb-3">
<span class="text-xs font-semibold text-textSecondary">Cuaca</span>
<svg aria-hidden="true" class="w-4 h-4 text-tealMint" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewbox="0 0 24 24">
<path d="M17.5 19H9a7 7 0 1 1 6.71-9h1.79a4.5 4.5 0 1 1 0 9Z"></path>
</svg>
</div>
<div class="flex items-baseline gap-2 mb-2">
<span class="text-lg font-bold text-textPrimary" id="ui-weather-value">--°C</span>
</div>
<div class="mb-2">
<span class="text-xs font-bold px-2.5 py-0.5 rounded-full bg-tealMint text-deepNavy" id="ui-weather-condition">Memuat</span>
</div>
<p class="text-xs text-textSecondary">Data real-time <span class="font-medium text-textPrimary">Open-Meteo</span></p>
</div>
</article>
<!-- Card 3: Lalu Lintas -->
<article class="rounded-card border border-cardBorder bg-cardBg p-5 shadow-subtle flex flex-col justify-between hover:border-aquaBlue transition">
<div>
<div class="flex items-center justify-between mb-3">
<span class="text-xs font-semibold text-textSecondary">Lalu lintas</span>
<svg aria-hidden="true" class="w-4 h-4 text-tealMint" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewbox="0 0 24 24">
<rect height="13" width="15" x="1" y="3"></rect>
<polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>
<circle cx="5.5" cy="18.5" r="2.5"></circle>
<circle cx="18.5" cy="18.5" r="2.5"></circle>
</svg>
</div>
<div class="flex items-baseline gap-2 mb-2">
<span class="text-2xl font-bold text-textPrimary" id="ui-traffic-congestion">--</span>
<span class="text-xs font-bold px-2.5 py-0.5 rounded-full bg-tealMint text-deepNavy" id="ui-traffic-speed">-- km/j</span>
</div>
<p class="text-xs text-textSecondary">Data real-time <span class="font-medium text-textPrimary">TomTom Traffic</span></p>
</div>
</article>
</div>
</div>
</section>
<!-- END: MainVoicePanel -->
</div>
<!-- Keyboard Shortcuts Bar -->
<footer aria-label="Daftar pintasan keyboard" class="mt-auto py-3.5 px-5 rounded-card bg-deepNavy text-white text-xs flex flex-wrap items-center justify-between gap-4 shadow-subtle">
<div class="flex items-center gap-2 font-semibold">
<svg aria-hidden="true" class="w-4 h-4 text-sunrise-start" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewbox="0 0 24 24">
<rect height="16" rx="2" width="20" x="2" y="4"></rect>
<path d="M6 8h.001M10 8h.001M14 8h.001M18 8h.001M8 12h.001M12 12h.001M16 12h.001M6 16h12"></path>
</svg>
<span class="">Pintasan keyboard:</span>
</div>
<div class="flex flex-wrap items-center gap-4 md:gap-8">
<div class="flex items-center gap-2">
<kbd class="px-2 py-0.5 rounded bg-white/15 border border-white/20 text-white font-mono text-xs">Spasi</kbd>
<span class="text-slate-200">Bicara</span>
</div>
<div class="flex items-center gap-2">
<kbd class="px-2 py-0.5 rounded bg-white/15 border border-white/20 text-white font-mono text-xs">P</kbd>
<span class="text-slate-200">Jeda / Putar</span>
</div>
<div class="flex items-center gap-2">
<kbd class="px-2 py-0.5 rounded bg-white/15 border border-white/20 text-white font-mono text-xs">R</kbd>
<span class="text-slate-200">Ulangi suara</span>
</div>
<div class="flex items-center gap-2">
<kbd class="px-2 py-0.5 rounded bg-white/15 border border-white/20 text-white font-mono text-xs">Esc</kbd>
<span class="text-slate-200">Hentikan suara</span>
</div>
<div class="flex items-center gap-2">
<kbd class="px-2 py-0.5 rounded bg-white/15 border border-white/20 text-white font-mono text-xs">S</kbd>
<span class="text-slate-200">Pengaturan</span>
</div>
</div>
</footer>
</main>
<!-- END: MainContentContainer -->
<!-- BEGIN: MainFooter -->
<x-footer />
<!-- END: MainFooter -->

<!-- Location Search Modal -->
<div id="location-modal" class="fixed inset-0 bg-[#0A1F33]/80 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white w-full max-w-md rounded-[32px] p-8 shadow-2xl relative">
        <button onclick="document.getElementById('location-modal').classList.add('hidden')" class="absolute top-6 right-6 text-[#42586A] hover:text-[#0A1F33] focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
        </button>
        
        <h2 class="text-2xl font-bold text-[#0A1F33] mb-2">Tambah Lokasi Lainnya</h2>
        <p class="text-sm text-[#42586A] mb-6">Cari kota, kabupaten, kecamatan, atau kelurahan spesifik untuk dipantau secara real-time.</p>
        
        <form id="add-location-form" onsubmit="searchLocation(event)" class="space-y-4">
            <div>
                <label for="search-loc-input" class="block text-sm font-semibold text-[#0A1F33] mb-1.5">Nama Lokasi</label>
                <input type="text" id="search-loc-input" placeholder="Contoh: Plaju, Palembang..." class="w-full text-base rounded-2xl border-2 border-[#D6E6ED] px-4 py-3.5 text-[#0A1F33] focus:outline-none focus:border-[#0675A3] transition">
            </div>
            <p id="search-loc-status" class="text-sm text-[#0675A3] font-medium hidden">Mencari di peta...</p>
            
            <button type="submit" class="w-full bg-[#0675A3] hover:bg-[#033067] text-white py-3.5 rounded-2xl font-bold text-base transition">
                Cari & Tambahkan
            </button>
        </form>

        <div class="relative flex py-5 items-center">
            <div class="flex-grow border-t border-[#D6E6ED]"></div>
            <span class="flex-shrink-0 mx-4 text-[#42586A] text-sm font-medium">ATAU</span>
            <div class="flex-grow border-t border-[#D6E6ED]"></div>
        </div>

        <button onclick="addCurrentLocation(); document.getElementById('location-modal').classList.add('hidden')" aria-label="Gunakan GPS saat ini" class="w-full py-3.5 px-4 rounded-2xl border-2 border-dashed border-[#0675A3] bg-[#E6F2F7] hover:bg-[#D6E6ED] text-[#0675A3] transition flex items-center justify-center gap-2 text-base font-bold focus:outline-none" type="button">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            <span>Deteksi GPS Saat Ini</span>
        </button>
    </div>
</div>

<!-- BEGIN: Voice Assistant Core Script -->
<script data-purpose="voice-assistant-core">
    // ============================================================
    // STATE MANAGEMENT
    // ============================================================
    const STATES = {
        IDLE: 'idle',
        GREETING: 'greeting',
        EXPLAIN_SYSTEM: 'explain_system',
        DETECT_LOCATION: 'detect_location',
        CONFIRM_LOCATION: 'confirm_location',
        LISTENING: 'listening',
        FETCH_ORIGIN: 'fetch_origin',
        BRIEF_ORIGIN: 'brief_origin',
        ASK_DESTINATION: 'ask_destination',
        LISTEN_DESTINATION: 'listen_destination',
        FETCH_DESTINATION: 'fetch_destination',
        BRIEF_DESTINATION: 'brief_destination',
        MONITORING: 'monitoring',
        PROCESSING: 'processing'
    };

    let currentState = STATES.IDLE;
    let locations = JSON.parse(localStorage.getItem('echosense_locations')) || [];
    let activeLocationIndex = 0;
    let currentData = null;
    let currentLocation = null;
    let destination = null;
    let monitoringInterval = null;
    let previousData = null;
    let isSpeaking = false;
    let isListening = false;
    let audioCtx = null;
    let currentAudio = null; // Track current audio element for stopping

    let prefs = JSON.parse(localStorage.getItem('echosense_prefs')) || {
        volume: 80, speed: 100, language: 'id', voiceURI: 'elevenlabs:Xb7hH8MSUJpSbSDYk0k2',
        autoGreet: true, monitorInterval: 60, repeatAlert: false
    };
    if (prefs.speed === undefined) prefs.speed = 100;
    if (prefs.autoGreet === undefined) prefs.autoGreet = true;
    if (prefs.monitorInterval === undefined) prefs.monitorInterval = 60;
    // Pastikan selalu menggunakan ElevenLabs sebagai suara utama
    if (!prefs.voiceURI || prefs.voiceURI === 'default') {
        prefs.voiceURI = 'elevenlabs:Xb7hH8MSUJpSbSDYk0k2';
    }

    let isPaused = false;
    let lastSpokenText = '';
    let lastOnEnd = null;
    let hasAutoStarted = false;
    let locationPromise = null;
    let preloadedOriginData = null;

    const alerts = JSON.parse(localStorage.getItem('echosense_alerts')) || { alert_aqi: true, alert_weather: true, alert_traffic: false };
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    // ============================================================
    // SPEECH RECOGNITION SETUP
    // ============================================================
    const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
    let recognition = null;
    let speechSupported = false;

    if (SpeechRecognition) {
        recognition = new SpeechRecognition();
        recognition.lang = prefs.language === 'en' ? 'en-US' : 'id-ID';
        recognition.continuous = false;
        recognition.interimResults = true;
        recognition.maxAlternatives = 1;
        speechSupported = true;

        recognition.onresult = (event) => {
            let transcript = '';
            let isFinal = false;
            for (let i = event.resultIndex; i < event.results.length; i++) {
                transcript = event.results[i][0].transcript;
                isFinal = event.results[i].isFinal;
            }
            
            if (isFinal) {
                stopListening();
                handleUserSpeech(transcript);
            } else {
                // Show interim results
                updateMicLabel(`🎙️ "${transcript}..."`);
            }
        };

        recognition.onerror = (event) => {
            console.error('Speech Recognition Error:', event.error);
            stopListening();
            if (event.error === 'no-speech') {
                addMessage('system', 'Saya tidak mendengar suara. Silakan coba lagi.');
                enableMic();
            } else if (event.error === 'not-allowed') {
                addMessage('system', 'Akses mikrofon ditolak. Silakan izinkan akses mikrofon di browser Anda.');
            }
        };

        recognition.onend = () => {
            if (isListening) {
                stopListening();
            }
        };
    }

    // ============================================================
    // UI ELEMENTS
    // ============================================================
    const voicePanel = document.getElementById('voice-panel');
    const btnPlayPause = document.getElementById('btn-play-pause');
    const iconPlay = document.getElementById('icon-play');
    const iconPause = document.getElementById('icon-pause');
    const playPauseLabel = document.getElementById('play-pause-label');
    const btnReplay = document.getElementById('btn-replay');
    const btnMic = document.getElementById('btn-mic');
    const micIcon = document.getElementById('mic-icon');
    const micStopIcon = document.getElementById('mic-stop-icon');
    const micLabel = document.getElementById('mic-label');
    const transcriptArea = document.getElementById('transcript-area');
    const statusText = document.getElementById('status-text');
    const statusDot = document.getElementById('status-dot');
    const voiceHeaderTitle = document.getElementById('voice-header-title');
    const locationListContainer = document.getElementById('location-list-container');

    // ============================================================
    // TTS ENGINE & AUDIO CONTROLS
    // ============================================================
    function updateAudioControls(speaking) {
        if (speaking) {
            iconPause?.classList.remove('hidden');
            iconPlay?.classList.add('hidden');
            if (playPauseLabel) playPauseLabel.textContent = 'Jeda suara';
        } else {
            iconPause?.classList.add('hidden');
            iconPlay?.classList.remove('hidden');
            if (playPauseLabel) playPauseLabel.textContent = 'Putar suara';
        }
    }

    function speak(text, onEnd) {
        lastSpokenText = text;
        lastOnEnd = onEnd;
        isSpeaking = true;
        isPaused = false;
        updateAudioControls(true);
        updateStatus('Berbicara...', '#FEB161');
        disableMic();

        const voiceURI = prefs.voiceURI || 'elevenlabs:Xb7hH8MSUJpSbSDYk0k2';
        const rate = (prefs.speed || 100) / 100;

        if (voiceURI.startsWith('elevenlabs:')) {
            const elVoiceId = voiceURI.replace('elevenlabs:', '');
            
            fetch('/api/tts', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken || '' },
                body: JSON.stringify({ text: text, voice_id: elVoiceId })
            }).then(async res => {
                if (!res.ok) {
                    const err = await res.json();
                    throw new Error(err.error || "ElevenLabs API Error");
                }
                return res.blob();
            }).then(blob => {
                const url = URL.createObjectURL(blob);
                const audio = document.getElementById('echosense-audio') || new Audio();
                audio.src = url;
                audio.playbackRate = rate;
                audio.volume = (prefs.volume || 80) / 100;
                currentAudio = audio;
                
                audio.onended = () => {
                    currentAudio = null;
                    isSpeaking = false;
                    isPaused = false;
                    updateAudioControls(false);
                    if (onEnd) onEnd();
                };
                audio.onerror = (e) => {
                    console.error("Audio playback error:", e);
                    currentAudio = null;
                    isSpeaking = false;
                    updateAudioControls(false);
                };
                
                const playPromise = audio.play();
                if (playPromise !== undefined) {
                    playPromise.catch(err => {
                        console.warn("Autoplay held by browser policy until interaction:", err);
                        // JANGAN switch ke Web Speech API bawaan perangkat!
                        // Dengarkan event keyboard / focus / sentuhan apapun secara silent tanpa banner visual
                        const resumeAudio = () => {
                            if (audio.paused && isSpeaking) {
                                audio.play().catch(e => console.error("Play error on resume:", e));
                            }
                            ['keydown', 'keyup', 'focus', 'pointerdown', 'mousedown', 'touchstart'].forEach(evt => {
                                window.removeEventListener(evt, resumeAudio, true);
                                document.removeEventListener(evt, resumeAudio, true);
                            });
                        };
                        ['keydown', 'keyup', 'focus', 'pointerdown', 'mousedown', 'touchstart'].forEach(evt => {
                            window.addEventListener(evt, resumeAudio, { once: true, capture: true, passive: true });
                            document.addEventListener(evt, resumeAudio, { once: true, capture: true, passive: true });
                        });
                    });
                }
            }).catch(e => {
                console.error("ElevenLabs error:", e);
                addMessage('system', 'Kendala suara ElevenLabs: ' + e.message);
                isSpeaking = false;
                updateAudioControls(false);
                enableMic();
            });
        } else {
            speakBrowser(text, rate, onEnd);
        }
    }

    function speakBrowser(text, rate, onEnd) {
        if (!window.speechSynthesis) {
            isSpeaking = false;
            updateAudioControls(false);
            if (onEnd) onEnd();
            return;
        }

        window.speechSynthesis.cancel();
        window.speechSynthesis.resume();

        const utterance = new SpeechSynthesisUtterance(text);
        utterance.lang = prefs.language === 'en' ? 'en-US' : 'id-ID';
        utterance.rate = rate || 1.0;
        utterance.volume = (prefs.volume || 80) / 100;

        const voices = window.speechSynthesis.getVoices();
        const targetLang = prefs.language === 'en' ? 'en' : 'id';
        const langVoice = voices.find(v => v.lang.toLowerCase().startsWith(targetLang));
        if (langVoice) {
            utterance.voice = langVoice;
        }

        let hasEnded = false;
        const done = () => {
            if (hasEnded) return;
            hasEnded = true;
            clearInterval(chromeResumeLoop);
            isSpeaking = false;
            isPaused = false;
            updateAudioControls(false);
            if (onEnd) onEnd();
        };

        utterance.onend = done;
        utterance.onerror = (e) => {
            console.warn('SpeechSynthesis error:', e);
            done();
        };

        // Workaround for Chrome speech pause bug on long sentences
        const chromeResumeLoop = setInterval(() => {
            if (window.speechSynthesis.speaking) {
                window.speechSynthesis.pause();
                window.speechSynthesis.resume();
            } else {
                clearInterval(chromeResumeLoop);
            }
        }, 8000);

        window.speechSynthesis.speak(utterance);
    }

    function stopSpeaking() {
        window.speechSynthesis.cancel();
        if (currentAudio) {
            currentAudio.pause();
            currentAudio.currentTime = 0;
            currentAudio = null;
        }
        isSpeaking = false;
        isPaused = false;
        updateAudioControls(false);
    }

    function playMicChime() {
        try {
            const ctx = audioCtx || new (window.AudioContext || window.webkitAudioContext)();
            if (ctx.state === 'suspended') ctx.resume();
            const now = ctx.currentTime;
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();
            osc.type = 'sine';
            osc.frequency.setValueAtTime(587.33, now); // D5
            osc.frequency.setValueAtTime(880, now + 0.1); // A5
            gain.gain.setValueAtTime(0.18, now);
            gain.gain.exponentialRampToValueAtTime(0.001, now + 0.35);
            osc.connect(gain);
            gain.connect(ctx.destination);
            osc.start(now);
            osc.stop(now + 0.35);
        } catch(e) {}
    }

    function togglePlayPause() {
        if (isSpeaking) {
            // Pause
            if (currentAudio) {
                currentAudio.pause();
            }
            if (window.speechSynthesis && window.speechSynthesis.speaking) {
                window.speechSynthesis.pause();
            }
            isSpeaking = false;
            isPaused = true;
            updateAudioControls(false);
            updateStatus('Dijeda', '#42586A');
        } else if (isPaused) {
            // Resume
            if (currentAudio && currentAudio.paused) {
                currentAudio.play();
                isSpeaking = true;
                isPaused = false;
                updateAudioControls(true);
                updateStatus('Berbicara...', '#FEB161');
            } else if (window.speechSynthesis && window.speechSynthesis.paused) {
                window.speechSynthesis.resume();
                isSpeaking = true;
                isPaused = false;
                updateAudioControls(true);
                updateStatus('Berbicara...', '#FEB161');
            } else if (lastSpokenText) {
                speak(lastSpokenText, lastOnEnd);
            }
        } else if (lastSpokenText) {
            speak(lastSpokenText, lastOnEnd);
        } else {
            startConversation();
        }
    }

    function replayLastNarration() {
        if (lastSpokenText) {
            stopSpeaking();
            stopListening();
            speak(lastSpokenText, lastOnEnd);
        }
    }

    // ============================================================
    // MICROPHONE CONTROL
    // ============================================================
    function startListening() {
        if (!speechSupported) {
            addMessage('system', 'Browser Anda tidak mendukung input suara. Silakan gunakan Chrome atau Edge.');
            return;
        }
        if (isSpeaking) {
            stopSpeaking();
        }
        
        isListening = true;
        recognition.lang = prefs.language === 'en' ? 'en-US' : 'id-ID';
        
        try {
            recognition.start();
        } catch(e) {
            // Already started
        }
        
        btnMic.classList.add('mic-pulsing', 'bg-red-500', 'hover:bg-red-600');
        btnMic.classList.remove('bg-aquaBlue', 'hover:bg-aquaHover');
        micIcon.classList.add('hidden');
        micStopIcon.classList.remove('hidden');
        updateMicLabel('🎙️ Mendengarkan...');
        updateStatus('Mendengarkan...', '#FD7659');
    }

    function stopListening() {
        isListening = false;
        try {
            recognition.stop();
        } catch(e) {}
        
        btnMic.classList.remove('mic-pulsing', 'bg-red-500', 'hover:bg-red-600');
        btnMic.classList.add('bg-aquaBlue', 'hover:bg-aquaHover');
        micIcon.classList.remove('hidden');
        micStopIcon.classList.add('hidden');
        updateMicLabel('Tekan untuk berbicara (atau spasi)');
    }

    function enableMic() {
        btnMic.disabled = false;
        btnMic.classList.remove('opacity-50', 'cursor-not-allowed');
    }

    function disableMic() {
        btnMic.disabled = true;
        btnMic.classList.add('opacity-50', 'cursor-not-allowed');
    }

    function updateMicLabel(text) {
        micLabel.textContent = text;
    }

    // ============================================================
    // UI HELPERS
    // ============================================================
    function addMessage(role, text) {
        const div = document.createElement('div');
        div.className = 'msg-animate';
        
        if (role === 'assistant') {
            div.innerHTML = `
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-full bg-deepNavy flex-shrink-0 flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/>
                        </svg>
                    </div>
                    <div class="bg-white rounded-2xl rounded-tl-md px-4 py-3 shadow-subtle border border-cardBorder max-w-[85%]">
                        <p class="text-sm text-textPrimary leading-relaxed">${text}</p>
                    </div>
                </div>
            `;
        } else if (role === 'user') {
            div.innerHTML = `
                <div class="flex items-start gap-3 justify-end">
                    <div class="bg-deepNavy rounded-2xl rounded-tr-md px-4 py-3 shadow-subtle max-w-[85%]">
                        <p class="text-sm text-white leading-relaxed">${text}</p>
                    </div>
                    <div class="w-8 h-8 rounded-full bg-aquaBlue flex-shrink-0 flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                        </svg>
                    </div>
                </div>
            `;
        } else if (role === 'system') {
            div.innerHTML = `
                <div class="flex justify-center">
                    <div class="bg-sunrise-start/20 rounded-xl px-4 py-2 border border-sunrise-start/30">
                        <p class="text-xs text-textSecondary text-center">${text}</p>
                    </div>
                </div>
            `;
        } else if (role === 'alert') {
            div.innerHTML = `
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-full bg-red-500 flex-shrink-0 flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                        </svg>
                    </div>
                    <div class="bg-red-50 rounded-2xl rounded-tl-md px-4 py-3 shadow-subtle border border-red-200 max-w-[85%]">
                        <p class="text-sm text-red-800 leading-relaxed font-medium">${text}</p>
                    </div>
                </div>
            `;
        }
        
        transcriptArea.appendChild(div);
        transcriptArea.scrollTop = transcriptArea.scrollHeight;
    }

    function addTypingIndicator() {
        const div = document.createElement('div');
        div.id = 'typing-indicator';
        div.className = 'msg-animate';
        div.innerHTML = `
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-full bg-deepNavy flex-shrink-0 flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/>
                    </svg>
                </div>
                <div class="bg-white rounded-2xl rounded-tl-md px-4 py-3 shadow-subtle border border-cardBorder flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-textSecondary typing-dot-1"></span>
                    <span class="w-2 h-2 rounded-full bg-textSecondary typing-dot-2"></span>
                    <span class="w-2 h-2 rounded-full bg-textSecondary typing-dot-3"></span>
                </div>
            </div>
        `;
        transcriptArea.appendChild(div);
        transcriptArea.scrollTop = transcriptArea.scrollHeight;
    }

    function removeTypingIndicator() {
        document.getElementById('typing-indicator')?.remove();
    }

    function updateStatus(text, color) {
        statusText.textContent = text;
        statusDot.style.backgroundColor = color || '#21B9BE';
    }

    function updateTelemetryCards(data) {
        if (!data || !data.raw_data) return;
        
        document.getElementById('ui-aqi-value').textContent = `AQI ${Math.round(data.raw_data.aqi.pm25)}`;
        document.getElementById('ui-aqi-category').textContent = data.raw_data.aqi.category;
        
        const aqiTag = document.getElementById('ui-aqi-category');
        if (data.raw_data.aqi.pm25 > 150) {
            aqiTag.className = "text-xs font-bold px-2.5 py-0.5 rounded-full bg-red-600 text-white";
        } else if (data.raw_data.aqi.pm25 > 50) {
            aqiTag.className = "text-xs font-bold px-2.5 py-0.5 rounded-full bg-red-300 text-red-900";
        } else if (data.raw_data.aqi.pm25 > 25) {
            aqiTag.className = "text-xs font-bold px-2.5 py-0.5 rounded-full bg-yellow-300 text-yellow-900";
        } else {
            aqiTag.className = "text-xs font-bold px-2.5 py-0.5 rounded-full bg-sunrise-start text-deepNavy";
        }

        document.getElementById('ui-weather-value').textContent = `${data.raw_data.weather.temperature}°C`;
        document.getElementById('ui-weather-condition').textContent = data.raw_data.weather.condition;
        document.getElementById('ui-traffic-congestion').textContent = data.raw_data.traffic.congestion;
        document.getElementById('ui-traffic-speed').textContent = `${Math.round(data.raw_data.traffic.currentSpeed)} km/j`;
    }

    // ============================================================
    // LOCATION MANAGEMENT
    // ============================================================
    async function reverseGeocode(lat, lon) {
        try {
            const res = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}&accept-language=id`);
            const data = await res.json();
            return data.display_name || 'Lokasi Anda';
        } catch(e) {
            return 'Lokasi Anda';
        }
    }

    function renderLocations() {
        if(!locationListContainer) return;
        const countSpan = document.getElementById('location-count');
        if (countSpan) countSpan.textContent = `${locations.length} terpantau`;

        let html = '';
        locations.forEach((loc, idx) => {
            const isActive = idx === activeLocationIndex;
            if (isActive) {
                html += `
                <button onclick="switchLocation(${idx})" aria-pressed="true" class="w-full text-left p-4 rounded-card bg-[#033067] text-[#F4F9FB] border-2 border-[#033067] shadow-subtle transition relative group focus:outline-none" type="button">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-2.5">
                            <span aria-hidden="true" class="relative flex h-3 w-3">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#FEB161] opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3 w-3 bg-[#FEB161]"></span>
                            </span>
                            <span class="font-bold text-sm text-white">${loc.name.split(',')[0]} (saat ini)</span>
                        </div>
                        <span class="text-xs px-2.5 py-0.5 rounded-full bg-[#FEB161] text-[#033067] font-semibold">Aktif</span>
                    </div>
                    <div class="flex items-center justify-between text-xs text-slate-200 pt-2 border-t border-white/15">
                        <span id="loc-status-${idx}">Memuat data...</span>
                    </div>
                </button>`;
            } else {
                html += `
                <button onclick="switchLocation(${idx})" aria-pressed="false" class="w-full text-left p-4 rounded-card bg-[#FFFFFF] border border-[#D6E6ED] hover:border-[#0675A3] text-[#0A1F33] shadow-subtle transition group focus:outline-none" type="button">
                    <div class="flex items-center justify-between mb-1.5">
                        <span class="font-bold text-sm text-[#0A1F33] group-hover:text-[#0675A3] transition">${loc.name.split(',')[0]}</span>
                        <span class="text-xs text-[#42586A]">${loc.region || 'Indonesia'}</span>
                    </div>
                    <div class="flex items-center justify-between text-xs text-[#42586A] pt-1">
                        <span class="">Ketuk untuk memantau</span>
                    </div>
                </button>`;
            }
        });

        html += `
        <button onclick="document.getElementById('location-modal').classList.remove('hidden')" aria-label="Tambah lokasi lainnya" class="w-full mt-3 py-3.5 px-4 rounded-card border-2 border-dashed border-[#D6E6ED] hover:border-[#0675A3] bg-[#FFFFFF] text-[#0675A3] transition flex items-center justify-center gap-2 text-sm font-semibold focus:outline-none" type="button">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            <span>+ Tambah Lokasi</span>
        </button>`;
        
        locationListContainer.innerHTML = html;
        
        // Update header
        if (locations.length > 0) {
            const activeLoc = locations[activeLocationIndex];
            voiceHeaderTitle.textContent = activeLoc.name.split(',')[0];
        }
    }

    async function searchLocation(e) {
        e.preventDefault();
        const input = document.getElementById('search-loc-input');
        const status = document.getElementById('search-loc-status');
        const query = input.value.trim();
        if(!query) return;

        status.textContent = 'Mencari...';
        status.classList.remove('hidden');

        try {
            const res = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}`);
            const data = await res.json();
            if(data && data.length > 0) {
                const loc = data[0];
                locations.push({
                    id: Date.now(),
                    name: loc.display_name,
                    lat: loc.lat,
                    lon: loc.lon,
                    region: 'Manual Input'
                });
                localStorage.setItem('echosense_locations', JSON.stringify(locations));
                input.value = '';
                status.classList.add('hidden');
                document.getElementById('location-modal').classList.add('hidden');
                switchLocation(locations.length - 1);
            } else {
                status.textContent = 'Lokasi tidak ditemukan.';
            }
        } catch(e) {
            status.textContent = 'Error mencari lokasi.';
        }
    }

    async function switchLocation(idx) {
        if(idx === activeLocationIndex && currentData) return;
        activeLocationIndex = idx;
        renderLocations();
        
        const loc = locations[idx];
        currentLocation = { lat: loc.lat, lon: loc.lon, name: loc.name };
        
        addTypingIndicator();
        const data = await fetchEnvironmentData(loc.lat, loc.lon);
        removeTypingIndicator();
        
        if (data) {
            currentData = data;
            updateTelemetryCards(data);
            addMessage('assistant', data.briefing);
            speak(data.briefing, () => {
                enableMic();
                updateStatus('Siap mendengarkan', '#21B9BE');
            });
        }
    }

    function addCurrentLocation() {
        if ("geolocation" in navigator) {
            navigator.geolocation.getCurrentPosition(async (position) => {
                const lat = position.coords.latitude;
                const lon = position.coords.longitude;
                const cityName = await reverseGeocode(lat, lon);
                
                locations.push({
                    id: Date.now(),
                    name: cityName,
                    lat: lat,
                    lon: lon,
                    region: 'GPS Real-time'
                });
                localStorage.setItem('echosense_locations', JSON.stringify(locations));
                switchLocation(locations.length - 1);
            }, () => {
                addMessage('system', 'Gagal mendapatkan lokasi. Pastikan izin GPS diberikan.');
            });
        }
    }

    // ============================================================
    // DATA FETCHING
    // ============================================================
    async function fetchEnvironmentData(lat, lon) {
        try {
            const response = await fetch(`/api/briefing?lat=${lat}&lon=${lon}&lang=${prefs.language}`);
            return await response.json();
        } catch(e) {
            console.error('Fetch error:', e);
            return null;
        }
    }

    async function fetchDestinationData(destName) {
        try {
            const response = await fetch('/api/destination', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken || '' },
                body: JSON.stringify({ destination: destName, lang: prefs.language })
            });
            return await response.json();
        } catch(e) {
            console.error('Destination fetch error:', e);
            return null;
        }
    }

    // ============================================================
    // CONVERSATION FLOW (PARALLEL INITIALIZATION)
    // ============================================================
    function startParallelBackgroundFetch() {
        locationPromise = new Promise(async (resolve) => {
            if (!("geolocation" in navigator)) {
                resolve(null);
                return;
            }

            navigator.geolocation.getCurrentPosition(async (position) => {
                try {
                    const lat = position.coords.latitude;
                    const lon = position.coords.longitude;
                    
                    // 1. Reverse geocoding in parallel
                    const locName = await reverseGeocode(lat, lon);
                    currentLocation = { lat, lon, name: locName };
                    
                    // 2. Fetch environment data (AQI, weather, traffic) in parallel!
                    const data = await fetchEnvironmentData(lat, lon);
                    preloadedOriginData = data;
                    currentData = data;
                    previousData = JSON.parse(JSON.stringify(data));
                    
                    // Save and update UI
                    if (locations.length === 0 || !locations.find(l => l.lat == lat && l.lon == lon)) {
                        locations = [{
                            id: Date.now(),
                            name: locName,
                            lat: lat,
                            lon: lon,
                            region: 'GPS Real-time'
                        }, ...locations.filter(l => l.lat != lat || l.lon != lon)];
                        activeLocationIndex = 0;
                        localStorage.setItem('echosense_locations', JSON.stringify(locations));
                    }
                    renderLocations();
                    if (data) {
                        updateTelemetryCards(data);
                    }
                    
                    resolve({ location: currentLocation, data: data });
                } catch(err) {
                    console.error("Parallel background fetch error:", err);
                    resolve(null);
                }
            }, (error) => {
                console.warn("GPS access denied or error:", error);
                resolve(null);
            }, { enableHighAccuracy: false, timeout: 8000 });
        });
    }

    async function startConversation() {
        // Initialize and resume AudioContext for browser audio policy
        if (!audioCtx) {
            audioCtx = new (window.AudioContext || window.webkitAudioContext)();
        }
        if (audioCtx && audioCtx.state === 'suspended') {
            audioCtx.resume();
        }
        if (window.speechSynthesis) {
            window.speechSynthesis.resume();
        }

        currentState = STATES.GREETING;
        updateStatus('Menyapa & memuat data...', '#FEB161');

        // PARALEL 1: Langsung ambil lokasi GPS & data lingkungan di background!
        startParallelBackgroundFetch();
        
        // PARALEL 2: Bersamaan langsung mulai suara TTS menyapa dan menjelaskan sistem!
        const greeting = prefs.language === 'en'
            ? "Hello! Welcome to EchoSense. I am your inclusive voice assistant that helps you understand the environmental conditions around you in real-time. I provide updates on air quality, weather, and traffic conditions. Please note that as an AI-powered system, I may occasionally make mistakes or experience slight data delays, so always prioritize your safety. How it works: you can listen to my voice guidance and respond directly using your voice whenever the microphone is active after the chime. I am currently detecting your location and retrieving environmental data in the background."
            : "Halo! Selamat datang di EchoSense. Saya adalah asisten suara inklusif yang membantu Anda memahami kondisi lingkungan sekitar secara real-time. Saya dapat memberikan informasi tentang kualitas udara, cuaca, dan kondisi lalu lintas. Harap diperhatikan bahwa sistem berbasis kecerdasan buatan ini dapat menimbulkan kesalahan informasi atau keterlambatan data, sehingga tetap utamakan keselamatan dan intuisi Anda. Cara kerjanya, Anda cukup mendengarkan panduan suara saya dan dapat berbicara langsung saat mikrofon aktif setelah bunyi nada. Saat ini, saya sedang mendeteksi lokasi dan data lingkungan Anda di latar belakang.";
        
        addMessage('assistant', greeting);
        
        speak(greeting, async () => {
            // Setelah TTS sapaan awal selesai berbicara:
            updateStatus('Menyiapkan konfirmasi...', '#14C5D9');
            
            // Tunggu data paralel (biasanya sudah selesai selama sapaan dibacakan)
            let result = null;
            try {
                result = await Promise.race([
                    locationPromise,
                    new Promise(res => setTimeout(() => res(null), 3000))
                ]);
            } catch(e) {}

            promptLocationConfirmation(result);
        });
    }

    function promptLocationConfirmation(result) {
        currentState = STATES.CONFIRM_LOCATION;

        if (result && result.location) {
            const shortName = result.location.name ? result.location.name.split(',').slice(0, 3).join(',') : 'lokasi Anda';
            const confirmMsg = prefs.language === 'en'
                ? `I detected your location at ${shortName}. Is that correct? You can answer with your voice right after the chime. Please say: Yes, that's correct, or say your actual location if incorrect.`
                : `Saya mendeteksi lokasi Anda saat ini di ${shortName}. Apakah benar? Anda dapat langsung menjawab menggunakan suara setelah bunyi nada ini. Silakan sebutkan: Ya benar, atau sebutkan nama lokasi Anda jika salah.`;
            
            addMessage('assistant', confirmMsg);
            speak(confirmMsg, () => {
                playMicChime();
                enableMic();
                updateStatus('Mendengarkan jawaban...', '#FD7659');
                setTimeout(() => {
                    if (!isListening && !isSpeaking) startListening();
                }, 350);
            });
        } else {
            const manualMsg = prefs.language === 'en'
                ? "I couldn't detect your GPS location automatically. You can tell me your location now after the chime. Please say the name of your city or area."
                : "Saya tidak dapat mendeteksi lokasi GPS Anda secara otomatis. Anda dapat menyebutkan lokasi Anda sekarang setelah bunyi nada ini. Silakan sebutkan nama kota atau daerah Anda saat ini.";
            
            addMessage('assistant', manualMsg);
            speak(manualMsg, () => {
                playMicChime();
                enableMic();
                updateStatus('Menunggu nama lokasi...', '#FD7659');
                setTimeout(() => {
                    if (!isListening && !isSpeaking) startListening();
                }, 350);
            });
        }
    }

    function handleTextInputSubmit(e) {
        if (e) e.preventDefault();
        const input = document.getElementById('manual-text-input');
        if (!input) return;
        const text = input.value.trim();
        if (!text) return;
        input.value = '';
        if (isSpeaking) stopSpeaking();
        if (isListening) stopListening();
        handleUserSpeech(text);
    }

    async function handleUserSpeech(text) {
        addMessage('user', text);
        
        switch (currentState) {
            case STATES.CONFIRM_LOCATION:
                await handleLocationConfirmation(text);
                break;
            case STATES.ASK_DESTINATION:
            case STATES.LISTEN_DESTINATION:
                await handleDestinationInput(text);
                break;
            case STATES.MONITORING:
                await handleMonitoringCommand(text);
                break;
            default:
                await handleGeneralCommand(text);
                break;
        }
    }

    async function handleLocationConfirmation(text) {
        addTypingIndicator();
        updateStatus('Memproses jawaban...', '#14C5D9');

        const response = await fetch('/api/voice-command', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken || '' },
            body: JSON.stringify({ text: text, lang: prefs.language })
        });
        const intent = await response.json();
        removeTypingIndicator();
        
        if (intent.intent === 'confirm_yes') {
            // Lokasi dikonfirmasi benar!
            // Karena data lingkungan SUDAH DIAMBIL PARALEL saat sapaan, langsung bacakan!
            if (preloadedOriginData) {
                currentData = preloadedOriginData;
                previousData = JSON.parse(JSON.stringify(preloadedOriginData));
                updateTelemetryCards(currentData);
                
                const locStatus = document.getElementById(`loc-status-${activeLocationIndex}`);
                if (locStatus && currentData.raw_data) {
                    locStatus.textContent = `AQI ${Math.round(currentData.raw_data.aqi.pm25)} • ${currentData.raw_data.weather.temperature}°C`;
                }

                currentState = STATES.BRIEF_ORIGIN;
                addMessage('assistant', currentData.briefing);
                
                speak(currentData.briefing, () => {
                    askDestination();
                });
            } else {
                await fetchAndBriefOrigin();
            }
        } else if (intent.intent === 'confirm_no') {
            const msg = prefs.language === 'en'
                ? "I see. Please tell me your current location after the chime."
                : "Baik. Silakan sebutkan lokasi Anda saat ini setelah bunyi nada ini.";
            addMessage('assistant', msg);
            speak(msg, () => {
                playMicChime();
                enableMic();
                setTimeout(() => {
                    if (!isListening && !isSpeaking) startListening();
                }, 350);
            });
        } else {
            // Pengguna langsung menyebutkan nama lokasi
            addTypingIndicator();
            updateStatus('Mencari lokasi...', '#14C5D9');
            
            try {
                const geoRes = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(text)}&limit=1`);
                const geoData = await geoRes.json();
                removeTypingIndicator();
                
                if (geoData && geoData.length > 0) {
                    const loc = geoData[0];
                    currentLocation = { lat: loc.lat, lon: loc.lon, name: loc.display_name };
                    
                    locations = [{
                        id: Date.now(), name: loc.display_name,
                        lat: loc.lat, lon: loc.lon, region: 'Manual'
                    }, ...locations];
                    activeLocationIndex = 0;
                    localStorage.setItem('echosense_locations', JSON.stringify(locations));
                    renderLocations();
                    
                    await fetchAndBriefOrigin();
                } else {
                    const msg = prefs.language === 'en'
                        ? "Sorry, I couldn't find that location. Please try again after the chime with a more specific name."
                        : "Maaf, saya tidak dapat menemukan lokasi tersebut. Silakan coba lagi setelah nada berbunyi dengan nama kota yang lebih spesifik.";
                    addMessage('assistant', msg);
                    speak(msg, () => {
                        playMicChime();
                        enableMic();
                        setTimeout(() => {
                            if (!isListening && !isSpeaking) startListening();
                        }, 350);
                    });
                }
            } catch(e) {
                removeTypingIndicator();
                addMessage('system', 'Gagal mencari lokasi.');
                enableMic();
            }
        }
    }

    async function fetchAndBriefOrigin() {
        currentState = STATES.FETCH_ORIGIN;
        addTypingIndicator();
        updateStatus('Mengambil data lingkungan...', '#14C5D9');
        
        const data = await fetchEnvironmentData(currentLocation.lat, currentLocation.lon);
        removeTypingIndicator();
        
        if (data) {
            currentData = data;
            previousData = JSON.parse(JSON.stringify(data));
            updateTelemetryCards(data);
            
            const locStatus = document.getElementById(`loc-status-${activeLocationIndex}`);
            if (locStatus) locStatus.textContent = `AQI ${Math.round(data.raw_data.aqi.pm25)} • ${data.raw_data.weather.temperature}°C`;
            
            currentState = STATES.BRIEF_ORIGIN;
            addMessage('assistant', data.briefing);
            
            speak(data.briefing, () => {
                askDestination();
            });
        } else {
            addMessage('system', 'Gagal mengambil data lingkungan.');
            askDestination();
        }
    }

    function askDestination() {
        currentState = STATES.ASK_DESTINATION;
        const msg = prefs.language === 'en'
            ? "Are you planning to travel anywhere today? If yes, please tell me your destination after the chime. If not, say: No."
            : "Apakah Anda berencana bepergian hari ini? Jika ya, silakan sebutkan tujuan Anda setelah bunyi nada ini. Jika tidak, sebutkan: Tidak.";
        
        addMessage('assistant', msg);
        speak(msg, () => {
            playMicChime();
            enableMic();
            updateStatus('Menunggu tujuan...', '#FD7659');
            setTimeout(() => {
                if (!isListening && !isSpeaking) startListening();
            }, 350);
        });
    }

    async function handleDestinationInput(text) {
        const response = await fetch('/api/voice-command', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken || '' },
            body: JSON.stringify({ text: text, lang: prefs.language })
        });
        const intent = await response.json();
        
        if (intent.intent === 'confirm_no') {
            // No destination, go to monitoring
            startMonitoring();
            return;
        }
        
        // Extract destination
        let destName = text;
        if (intent.intent === 'set_destination' && intent.destination) {
            destName = intent.destination;
        }
        
        currentState = STATES.FETCH_DESTINATION;
        addTypingIndicator();
        updateStatus(`Mencari data ${destName}...`, '#14C5D9');
        
        const destData = await fetchDestinationData(destName);
        removeTypingIndicator();
        
        if (destData && !destData.error) {
            destination = destData.destination;
            
            currentState = STATES.BRIEF_DESTINATION;
            addMessage('assistant', destData.briefing);
            
            speak(destData.briefing, () => {
                startMonitoring();
            });
        } else {
            const errorMsg = destData?.fallback_message || (prefs.language === 'en'
                ? `Sorry, I couldn't find information about ${destName}. Please try a different name.`
                : `Maaf, aku belum dapat menemukan informasi untuk lokasi ${destName}. Kamu bisa coba sebutkan nama kota atau daerah tujuan lainnya ya.`);
            
            addMessage('assistant', errorMsg);
            speak(errorMsg, () => {
                currentState = STATES.ASK_DESTINATION;
                enableMic();
                setTimeout(() => {
                    if (!isListening && !isSpeaking) startListening();
                }, 500);
            });
        }
    }

    // ============================================================
    // REAL-TIME MONITORING
    // ============================================================
    function startMonitoring() {
        currentState = STATES.MONITORING;
        
        const msg = prefs.language === 'en'
            ? "I am now in active monitoring mode. I will regularly monitor environmental conditions and alert you if anything changes. You can speak or ask me questions anytime by pressing the spacebar on your keyboard, tapping the microphone button, or tapping your screen, for example ask about the weather, air quality, or traffic."
            : "Saya sekarang dalam mode pemantauan aktif. Saya akan terus memeriksa kondisi secara berkala dan langsung memberi peringatan jika ada perubahan. Kamu bisa berbicara atau bertanya kapan saja dengan menekan tombol Spasi di keyboard, mengetuk layar, atau menekan tombol mikrofon. Misalnya tanyakan tentang cuaca, kualitas udara, atau lalu lintas.";
        
        addMessage('assistant', msg);
        speak(msg, () => {
            enableMic();
            updateStatus('Memantau (Tekan Spasi untuk bicara)', '#21B9BE');
        });
        
        const intervalMs = (prefs.monitorInterval || 60) * 1000;
        
        if (monitoringInterval) clearInterval(monitoringInterval);
        monitoringInterval = setInterval(async () => {
            if (!currentLocation || isSpeaking) return;
            
            const newData = await fetchEnvironmentData(currentLocation.lat, currentLocation.lon);
            if (!newData) return;
            
            updateTelemetryCards(newData);
            checkForAlerts(previousData, newData);
            
            previousData = JSON.parse(JSON.stringify(newData));
            currentData = newData;
        }, intervalMs);
        
        // Also start GPS tracking if user has a destination
        if (destination) {
            startGPSTracking();
        }
    }

    function startGPSTracking() {
        if (!("geolocation" in navigator)) return;
        
        navigator.geolocation.watchPosition((position) => {
            const lat = position.coords.latitude;
            const lon = position.coords.longitude;
            currentLocation = { ...currentLocation, lat, lon };
        }, null, { enableHighAccuracy: false, maximumAge: 30000 });
    }

    function checkForAlerts(oldData, newData) {
        if (!oldData || !newData) return;
        
        // AQI Alert
        if (alerts.alert_aqi) {
            const oldAQI = oldData.raw_data?.aqi?.pm25 || 0;
            const newAQI = newData.raw_data?.aqi?.pm25 || 0;
            if (newAQI > 150 && oldAQI <= 150) {
                triggerAlert('aqi', {
                    value: newAQI,
                    category: newData.raw_data.aqi.category
                });
            }
        }
        
        // Weather Alert
        if (alerts.alert_weather) {
            const oldCondition = oldData.raw_data?.weather?.condition || '';
            const newCondition = newData.raw_data?.weather?.condition || '';
            const dangerConditions = ['Hujan Deras', 'Badai Petir'];
            if (dangerConditions.includes(newCondition) && !dangerConditions.includes(oldCondition)) {
                triggerAlert('weather', {
                    condition: newCondition,
                    temperature: newData.raw_data.weather.temperature
                });
            }
        }
        
        // Traffic Alert
        if (alerts.alert_traffic) {
            const oldCongestion = oldData.raw_data?.traffic?.congestion || '';
            const newCongestion = newData.raw_data?.traffic?.congestion || '';
            if (newCongestion === 'Macet Parah' && oldCongestion !== 'Macet Parah') {
                triggerAlert('traffic', {
                    congestion: newCongestion,
                    currentSpeed: newData.raw_data.traffic.currentSpeed
                });
            }
        }
    }

    async function triggerAlert(type, data) {
        // Stop any current speech
        stopSpeaking();
        
        addTypingIndicator();
        
        try {
            const response = await fetch('/api/alert', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken || '' },
                body: JSON.stringify({ type, data, lang: prefs.language })
            });
            const result = await response.json();
            removeTypingIndicator();
            
            addMessage('alert', result.narration);
            speak(result.narration, () => {
                enableMic();
                updateStatus('Memantau (Tekan Spasi untuk bicara)', '#21B9BE');
            });
        } catch(e) {
            removeTypingIndicator();
            // Fallback alert
            const fallbackMsg = type === 'aqi' 
                ? `Perhatian. Kualitas udara memburuk. PM2.5 sekarang di angka ${data.value}.`
                : type === 'weather'
                ? `Peringatan cuaca. Kondisi berubah menjadi ${data.condition}.`
                : `Peringatan lalu lintas. Kondisi saat ini ${data.congestion}.`;
            
            addMessage('alert', fallbackMsg);
            speak(fallbackMsg, () => {
                enableMic();
                updateStatus('Memantau (Tekan Spasi untuk bicara)', '#21B9BE');
            });
        }
    }

    async function handleMonitoringCommand(text) {
        const response = await fetch('/api/voice-command', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken || '' },
            body: JSON.stringify({ text: text, lang: prefs.language, current_data: currentData })
        });
        const intent = await response.json();
        
        if (intent.intent === 'query_condition') {
            const answer = intent.answer || (currentData ? currentData.briefing : null);
            if (answer) {
                addMessage('assistant', answer);
                speak(answer, () => {
                    enableMic();
                    updateStatus('Memantau (Tekan Spasi untuk bicara)', '#21B9BE');
                });
            } else {
                // Re-fetch current conditions
                addTypingIndicator();
                updateStatus('Mengambil data terbaru...', '#14C5D9');
                const data = await fetchEnvironmentData(currentLocation.lat, currentLocation.lon);
                removeTypingIndicator();
                
                if (data) {
                    currentData = data;
                    updateTelemetryCards(data);
                    addMessage('assistant', data.briefing);
                    speak(data.briefing, () => {
                        enableMic();
                        updateStatus('Memantau (Tekan Spasi untuk bicara)', '#21B9BE');
                    });
                }
            }
        } else if (intent.intent === 'set_destination') {
            await handleDestinationInput(text);
        } else if (intent.intent === 'help') {
            const helpMsg = prefs.language === 'en'
                ? "You can ask me questions anytime by pressing the spacebar or speaking, such as 'How is the weather today?', 'Is traffic heavy?', or tell me a new destination like 'To Palembang'."
                : "Kamu bisa bertanya kapan saja dengan menekan tombol Spasi atau langsung berbicara, misalnya: 'Bagaimana cuaca sekarang?', 'Apakah lalu lintas macet?', atau sebutkan tujuan perjalanan baru seperti 'Mau ke Palembang'.";
            addMessage('assistant', helpMsg);
            speak(helpMsg, () => {
                enableMic();
                updateStatus('Memantau (Tekan Spasi untuk bicara)', '#21B9BE');
            });
        } else {
            // Try as conversational query or destination
            if (intent.answer) {
                addMessage('assistant', intent.answer);
                speak(intent.answer, () => {
                    enableMic();
                    updateStatus('Memantau (Tekan Spasi untuk bicara)', '#21B9BE');
                });
            } else {
                await handleDestinationInput(text);
            }
        }
    }

    async function handleGeneralCommand(text) {
        addTypingIndicator();
        const data = await fetchEnvironmentData(currentLocation?.lat || '-6.1751', currentLocation?.lon || '106.8272');
        removeTypingIndicator();
        
        if (data) {
            currentData = data;
            updateTelemetryCards(data);
            addMessage('assistant', data.briefing);
            speak(data.briefing, () => {
                enableMic();
                updateStatus('Siap mendengarkan', '#21B9BE');
            });
        }
    }

    // ============================================================
    // EVENT LISTENERS
    // ============================================================
    
    // Play/Pause button
    btnPlayPause?.addEventListener('click', togglePlayPause);

    // Replay button
    btnReplay?.addEventListener('click', replayLastNarration);

    // Mic button
    btnMic?.addEventListener('click', () => {
        if (isListening) {
            stopListening();
        } else {
            startListening();
        }
    });

    // Keyboard shortcuts
    document.addEventListener('keydown', (event) => {
        if (event.target.tagName === 'INPUT' || event.target.tagName === 'TEXTAREA') return;
        
        if (event.code === 'Space') {
            event.preventDefault();
            if (!btnMic.disabled) {
                if (isListening) {
                    stopListening();
                } else {
                    startListening();
                }
            } else if (isSpeaking || isPaused) {
                togglePlayPause();
            }
        } else if (event.code === 'KeyP') {
            event.preventDefault();
            togglePlayPause();
        } else if (event.code === 'KeyR') {
            event.preventDefault();
            replayLastNarration();
        } else if (event.code === 'Escape') {
            stopSpeaking();
            stopListening();
            updateStatus('Dihentikan', '#42586A');
            setTimeout(() => {
                enableMic();
                updateStatus('Siap mendengarkan', '#21B9BE');
            }, 1000);
        } else if (event.code === 'KeyS') {
            window.location.href = '/pengaturan';
        }
    });

    function unlockAndStartAudio() {
        if (!audioCtx) {
            audioCtx = new (window.AudioContext || window.webkitAudioContext)();
        }
        if (audioCtx && audioCtx.state === 'suspended') {
            audioCtx.resume();
        }
        if (currentAudio && currentAudio.paused && isSpeaking) {
            currentAudio.play().catch(() => {});
        }
        if (!hasAutoStarted || currentState === STATES.IDLE) {
            hasAutoStarted = true;
            startConversation();
        }
    }

    // Auto-start initialization (Starts immediately when visually impaired user opens the page)
    function initAutoStart() {
        if (hasAutoStarted) return;
        hasAutoStarted = true;
        startConversation();
    }

    // Auto-start on load
    document.addEventListener('DOMContentLoaded', () => {
        renderLocations();
        initAutoStart();
    });

    window.addEventListener('load', () => {
        if (!hasAutoStarted) initAutoStart();
    });

    // Global listeners: any user activity immediately un-suspends audio context & starts/resumes playback without any visual barrier
    ['click', 'keydown', 'touchstart', 'focus'].forEach(evt => {
        window.addEventListener(evt, () => {
            unlockAndStartAudio();
        }, { passive: true });
    });
  </script>
</body></html>