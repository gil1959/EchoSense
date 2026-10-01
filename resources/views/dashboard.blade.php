<!DOCTYPE html>

<html lang="id"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>EchoSense - Dashboard utama dan kontrol audio</title>
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
    @keyframes soundwave {
      0%, 100% { height: 10px; }
      50% { height: 42px; }
    }
    .wave-bar-1 { animation: soundwave 1.1s ease-in-out infinite; }
    .wave-bar-2 { animation: soundwave 1.1s ease-in-out infinite 0.16s; }
    .wave-bar-3 { animation: soundwave 1.1s ease-in-out infinite 0.32s; }
    .wave-bar-4 { animation: soundwave 1.1s ease-in-out infinite 0.48s; }
    .wave-bar-5 { animation: soundwave 1.1s ease-in-out infinite 0.28s; }
    .wave-bar-6 { animation: soundwave 1.1s ease-in-out infinite 0.42s; }
    .wave-bar-7 { animation: soundwave 1.1s ease-in-out infinite 0.2s; }
  </style>
<style data-purpose="accessibility-focus">
    /* High contrast focus ring for accessible keyboard navigation */
    a:focus-visible, button:focus-visible, input:focus-visible {
      outline: 3px solid #0675A3;
      outline-offset: 2px;
    }
    /* Accessible custom range styling */
    input[type=range] {
      -webkit-appearance: none;
      background: transparent;
    }
    input[type=range]::-webkit-slider-thumb {
      height: 22px;
      width: 22px;
      border-radius: 9999px;
      background: #033067;
      border: 2px solid #FFFFFF;
      box-shadow: 0 1px 4px rgba(3, 48, 103, 0.35);
      cursor: pointer;
      -webkit-appearance: none;
      margin-top: -6px;
    }
    input[type=range]::-webkit-slider-runnable-track {
      width: 100%;
      height: 10px;
      border-radius: 9999px;
      border: 1px solid #6B8899;
      background: linear-gradient(90deg, #0675A3 0%, #14C5D9 var(--vol, 80%), #E6F2F7 var(--vol, 80%), #E6F2F7 100%);
    }
    input[type=range]::-moz-range-thumb {
      height: 22px;
      width: 22px;
      border-radius: 9999px;
      background: #033067;
      border: 2px solid #FFFFFF;
      box-shadow: 0 1px 4px rgba(3, 48, 103, 0.35);
      cursor: pointer;
    }
    input[type=range]::-moz-range-track {
      width: 100%;
      height: 10px;
      border-radius: 9999px;
      border: 1px solid #6B8899;
      background: #E6F2F7;
    }
    input[type=range]::-moz-range-progress {
      height: 10px;
      border-radius: 9999px;
      background: linear-gradient(90deg, #0675A3 0%, #14C5D9 100%);
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
<!-- BEGIN: SavedLocationsSidebar -->
<aside aria-label="Lokasi tersimpan" class="lg:col-span-4 flex flex-col gap-4">
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
<!-- Accessible Hint Card -->
<div class="rounded-card border border-cardBorder bg-cardBg p-4 text-xs text-textSecondary shadow-subtle">
<p class="font-semibold text-textPrimary mb-1">Panduan sonifikasi</p>
<p class="leading-relaxed">Frekuensi nada mencerminkan indeks AQI, ritme modulasi mengikuti kepadatan lalu lintas jalan raya.</p>
</div>
</aside>
<!-- END: SavedLocationsSidebar -->
<!-- BEGIN: MainAudioAndTelemetryPanel -->
<section aria-label="Panel kontrol dan telemetri" class="lg:col-span-8 flex flex-col gap-6">
<!-- 1. Audio Control Module -->
<div class="rounded-card border-2 border-deepNavy bg-cardBg p-6 shadow-subtle flex flex-col gap-5" data-purpose="audio-controller">
<!-- Controller Status Header -->
<div class="flex flex-wrap items-center justify-between gap-3 border-b border-cardBorder pb-4">
<div>
<span class="text-xs uppercase tracking-wide text-aquaBlue font-semibold">Kontrol audio</span>
<h1 class="text-xl md:text-2xl font-bold text-textPrimary mt-0.5">Saat ini: Jakarta, ID</h1>
</div>
<div class="flex items-center gap-2 bg-trackBg px-3.5 py-1.5 rounded-full border border-cardBorder text-xs text-textSecondary font-medium">
<span aria-hidden="true" class="inline-block w-2.5 h-2.5 rounded-full bg-tealMint"></span>
<span class="">Diperbarui: 14:35 WIB</span>
</div>
</div>
<!-- Play / Pause Action Hero Button -->
<div class="flex flex-col gap-4">
<button aria-label="Putar atau jeda audio sonifikasi, pintasan tombol spasi" aria-pressed="true" class="w-full py-4 px-6 rounded-card bg-aquaBlue hover:bg-aquaHover text-white font-bold text-base flex items-center justify-center gap-3 transition shadow-sm active:scale-[0.99] focus:outline-none" id="btn-play-pause" type="button">
<span aria-hidden="true" class="w-8 h-8 rounded-full bg-white text-aquaBlue flex items-center justify-center shadow-inner">
<!-- Pause / Play Icon -->
<svg class="w-4 h-4 fill-current" id="play-pause-icon" viewbox="0 0 24 24">
<rect height="16" width="4" x="6" y="4"></rect>
<rect height="16" width="4" x="14" y="4"></rect>
</svg>
</span>
<span class="">Play / Pause</span>
</button>
<!-- Waveform Visualizer (7 vertical bars with Sunrise Pulse gradient on navy container) -->
<div aria-label="Visualisasi spektrum audio sonifikasi" class="h-16 bg-deepNavy rounded-xl px-6 flex items-center justify-center gap-3.5 overflow-hidden" role="img">
<span class="wave-bar-1 w-2 rounded-full bg-gradient-to-t from-sunrise-start to-sunrise-end"></span>
<span class="wave-bar-2 w-2 rounded-full bg-gradient-to-t from-sunrise-start to-sunrise-end"></span>
<span class="wave-bar-3 w-2 rounded-full bg-gradient-to-t from-sunrise-start to-sunrise-end"></span>
<span class="wave-bar-4 w-2 rounded-full bg-gradient-to-t from-sunrise-start to-sunrise-end"></span>
<span class="wave-bar-5 w-2 rounded-full bg-gradient-to-t from-sunrise-start to-sunrise-end"></span>
<span class="wave-bar-6 w-2 rounded-full bg-gradient-to-t from-sunrise-start to-sunrise-end"></span>
<span class="wave-bar-7 w-2 rounded-full bg-gradient-to-t from-sunrise-start to-sunrise-end"></span>
</div>
</div>
<!-- Volume & Timeline Controls -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-1">
<!-- Volume Slider -->
<div class="flex flex-col gap-2 bg-trackBg/50 p-4 rounded-xl border border-cardBorder">
<div class="flex items-center justify-between text-xs">
<span class="text-textSecondary font-medium flex items-center gap-1.5">
<svg aria-hidden="true" class="w-4 h-4 text-aquaBlue" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewbox="0 0 24 24">
<polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"></polygon>
<path d="M19.07 4.93a10 10 0 0 1 0 14.14M15.54 8.46a5 5 0 0 1 0 7.07"></path>
</svg>
                  Volume
                </span>
<span class="text-deepNavy font-bold" id="volume-readout">30%</span>
</div>
<input aria-label="Tingkat volume audio" class="w-full cursor-pointer focus:outline-none" id="volume-slider" max="100" min="0" oninput="updateVolume(this.value)" style="--vol: 30%;" type="range" value="30"/>
</div>
<!-- Progress Timeline Control -->
<div class="flex flex-col gap-2 bg-trackBg/50 p-4 rounded-xl border border-cardBorder">
<div class="flex items-center justify-between text-xs">
<span class="text-textSecondary font-medium flex items-center gap-1.5">
<svg aria-hidden="true" class="w-4 h-4 text-aquaBlue" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewbox="0 0 24 24">
<circle cx="12" cy="12" r="10"></circle>
<polyline points="12 6 12 12 16 14"></polyline>
</svg>
                  Progres pemutaran
                </span>
<span class="text-textSecondary">01:12 / 03:20 <span class="text-deepNavy font-bold ml-1">(35%)</span></span>
</div>
<div aria-label="Waktu pemutaran audio" aria-valuemax="100" aria-valuemin="0" aria-valuenow="35" class="w-full bg-trackBg h-2.5 rounded-full overflow-hidden border border-borderInput relative" role="progressbar">
<div class="bg-gradient-to-r from-aquaBlue to-softCyan h-full rounded-full" style="width: 35%"></div>
</div>
</div>
</div>
<!-- Secondary Audio Actions: Ulang & Lokasi berikutnya -->
<div class="flex flex-wrap items-center gap-3 pt-1">
<button aria-label="Ulang pemutaran audio sonifikasi" class="flex-1 min-w-[140px] py-2.5 px-4 rounded-xl border-2 border-deepNavy text-deepNavy bg-white hover:bg-deepNavy hover:text-white text-xs font-semibold flex items-center justify-center gap-2 transition focus:outline-none" type="button">
<svg aria-hidden="true" class="w-4 h-4" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewbox="0 0 24 24">
<path d="M1 4v6h6"></path>
<path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"></path>
</svg>
<span class="">Ulang</span>
</button>
<button aria-label="Pindah ke pemutaran lokasi berikutnya" class="flex-1 min-w-[180px] py-2.5 px-4 rounded-xl border-2 border-deepNavy text-deepNavy bg-white hover:bg-deepNavy hover:text-white text-xs font-semibold flex items-center justify-center gap-2 transition focus:outline-none" type="button">
<span class="">Lokasi berikutnya</span>
<svg aria-hidden="true" class="w-4 h-4" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewbox="0 0 24 24">
<line x1="5" x2="19" y1="12" y2="12"></line>
<polyline points="12 5 19 12 12 19"></polyline>
</svg>
</button>
</div>
</div>
<!-- 2. AI Speech Summary Card -->
<article aria-labelledby="ai-summary-heading" class="rounded-card border border-cardBorder bg-cardBg p-6 shadow-subtle flex flex-col gap-4" data-purpose="ai-summary">
<div class="flex flex-wrap items-center justify-between gap-2">
<h2 class="text-sm font-bold text-textPrimary flex items-center gap-2" id="ai-summary-heading">
<svg aria-hidden="true" class="w-4 h-4 text-aquaBlue" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewbox="0 0 24 24">
<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
</svg>
              Ringkasan suara AI (30–60 detik)
            </h2>
<div aria-label="Pilihan kecepatan suara" class="flex items-center gap-1.5 text-xs text-textSecondary" role="group">
<span class="">Kecepatan:</span>
<button aria-pressed="true" class="px-2.5 py-1 rounded-lg font-semibold bg-aquaBlue text-white" type="button">1.0x</button>
<button aria-pressed="false" class="px-2.5 py-1 rounded-lg font-semibold bg-trackBg text-textSecondary hover:text-textPrimary border border-cardBorder" type="button">1.25x</button>
</div>
</div>
<!-- Transcript Preview with Highlight on Current Sentence -->
<div aria-live="polite" class="p-4 rounded-xl bg-trackBg/40 border border-cardBorder text-sm text-textPrimary leading-relaxed">
<p class="">
<span class="bg-trackBg px-1.5 py-0.5 rounded border border-cardBorder font-medium text-textPrimary" id="ai-summary-text">Memuat ringkasan AI...</span>
            </p>
</div>
<!-- Subtext Details -->
<div class="flex flex-wrap items-center justify-between text-xs text-textSecondary pt-1">
<span class="flex items-center gap-1.5" id="ui-synth-lang">
<span aria-hidden="true" class="w-2 h-2 rounded-full bg-tealMint"></span>
Sintesis suara: Bahasa Indonesia (Aksara Sonik v1)
</span>
<button aria-expanded="false" class="text-aquaBlue font-semibold hover:underline focus:outline-none" type="button">Perluas teks lengkap</button>
</div>
</article>
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
<button aria-expanded="false" class="text-xs font-semibold text-aquaBlue text-left pt-3 border-t border-cardBorder mt-4 hover:underline focus:outline-none" type="button">
              Perluas detail
            </button>
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
<p class="text-xs text-textSecondary">Data real-time <span class="font-medium text-textPrimary">Open-Meteo API</span></p>
</div>
<button aria-expanded="false" class="text-xs font-semibold text-aquaBlue text-left pt-3 border-t border-cardBorder mt-4 hover:underline focus:outline-none" type="button">
              Perluas detail
            </button>
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
<button aria-expanded="false" class="text-xs font-semibold text-aquaBlue text-left pt-3 border-t border-cardBorder mt-4 hover:underline focus:outline-none" type="button">
              Perluas detail
            </button>
</article>
</div>
</section>
<!-- END: MainAudioAndTelemetryPanel -->
</div>
<!-- BEGIN: QuickKeyboardShortcutsBar -->
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
<span class="text-slate-200">Play/Pause</span>
</div>
<div class="flex items-center gap-2">
<kbd class="px-2 py-0.5 rounded bg-white/15 border border-white/20 text-white font-mono text-xs">H</kbd>
<span class="text-slate-200">Bantuan</span>
</div>
<div class="flex items-center gap-2">
<kbd class="px-2 py-0.5 rounded bg-white/15 border border-white/20 text-white font-mono text-xs">S</kbd>
<span class="text-slate-200">Pengaturan</span>
</div>
<div class="flex items-center gap-2">
<kbd class="px-2 py-0.5 rounded bg-white/15 border border-white/20 text-white font-mono text-xs">M</kbd>
<span class="text-slate-200">Bisukan audio</span>
</div>
</div>
</footer>
<!-- END: QuickKeyboardShortcutsBar -->
</main>
<!-- END: MainContentContainer -->
<!-- BEGIN: MainFooter -->
<x-footer />
<!-- END: MainFooter -->
<!-- BEGIN: InteractiveAccessibilityScript -->
<script data-purpose="keyboard-handlers">
    // --- State Management ---
    let locations = JSON.parse(localStorage.getItem('echosense_locations')) || [];
    let activeLocationIndex = 0;
    let beatInterval = null;
    
    async function reverseGeocode(lat, lon) {
        try {
            const res = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}`);
            const data = await res.json();
            return data.display_name || 'Lokasi Anda'; // Use full detailed address
        } catch(e) {
            return 'Lokasi Anda';
        }
    }



    let prefs = JSON.parse(localStorage.getItem('echosense_prefs')) || {
        tempo: 60, pitch: 45, volume: 30, instrument: 'sine', language: 'id', playMode: 'bersamaan', voiceURI: 'elevenlabs:Xb7hH8MSUJpSbSDYk0k2'
    };
    
    if (prefs.voiceURI === undefined) {
        prefs.voiceURI = 'elevenlabs:Xb7hH8MSUJpSbSDYk0k2';
        localStorage.setItem('echosense_prefs', JSON.stringify(prefs));
    }

    // --- Audio Nodes ---
    let audioCtx, compressor, masterGain, analyser;
    let aqiDroneOsc, aqiDroneGain;
    let weatherOsc, weatherGain;
    let trafficPulseOsc, trafficPulseGain;
    let isPlaying = false;
    let currentData = null;
    let progressInterval;
    let playbackTime = 0;
    let totalTime = 30; // 30 seconds loop
    let animationId;
    let utterance = null;

    // --- UI Elements ---
    const playBtn = document.getElementById('btn-play-pause');
    const volumeSlider = document.getElementById('volume-slider');
    const volumeReadout = document.getElementById('volume-readout');
    const progressBar = document.querySelector('[role="progressbar"] > div');
    const progressText = document.querySelector('[role="progressbar"]').previousElementSibling.querySelector('span:last-child');
    const locationListContainer = document.getElementById('location-list-container');
    
    function init() {
        volumeSlider.value = prefs.volume;
        updateVolume(prefs.volume);
        renderLocations();
        
        if (locations.length > 0) {
            loadDataAndPlay(false); // Fetch on load, but don't play automatically (Browser policy)
        } else {
            // Automatically detect location if first time
            if ("geolocation" in navigator) {
                playBtn.innerHTML = '<span aria-hidden="true" class="w-8 h-8 rounded-full bg-white text-[#0675A3] flex items-center justify-center shadow-inner"><svg class="w-4 h-4 fill-current animate-spin" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none" stroke-dasharray="31.4 31.4"></circle></svg></span><span>Meminta Akses GPS...</span>';
                document.getElementById('ai-summary-text').innerHTML = '<span class="animate-pulse">Menunggu persetujuan lokasi dari browser...</span>';
                
                navigator.geolocation.getCurrentPosition(async (position) => {
                    document.getElementById('ai-summary-text').innerHTML = '<span class="animate-pulse">Menyelaraskan titik koordinat GPS...</span>';
                    const lat = position.coords.latitude;
                    const lon = position.coords.longitude;
                    const cityName = await reverseGeocode(lat, lon);
                    
                    locations = [{
                        id: Date.now(),
                        name: cityName,
                        lat: lat,
                        lon: lon,
                        region: 'GPS Real-time'
                    }];
                    localStorage.setItem('echosense_locations', JSON.stringify(locations));
                    renderLocations();
                    loadDataAndPlay(false); // Fetch initial data right after getting GPS
                }, () => {
                    playBtn.innerHTML = '<span aria-hidden="true" class="w-8 h-8 rounded-full bg-white text-[#0675A3] flex items-center justify-center shadow-inner"><svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><rect x="6" y="4" width="4" height="16"></rect><rect x="14" y="4" width="4" height="16"></rect></svg></span><span>Izin Lokasi Ditolak</span>';
                    document.getElementById('ai-summary-text').innerHTML = 'Gagal mengakses GPS. Silakan tambah lokasi pemantauan secara manual.';
                    renderLocations();
                });
            }
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
                            <span class="font-bold text-base text-white">${loc.name} (saat ini)</span>
                        </div>
                        <span class="text-xs px-2.5 py-0.5 rounded-full bg-[#FEB161] text-[#033067] font-semibold">Aktif</span>
                    </div>
                    <div class="flex items-center justify-between text-xs text-slate-200 pt-2 border-t border-white/15">
                        <span class="">Memuat data...</span>
                        <span class="text-xs text-[#FEB161] font-medium">${isPlaying ? 'Memutar audio' : 'Jeda'}</span>
                    </div>
                </button>`;
            } else {
                // If it's saved in active status in settings it's "Siaga lembut", else "Audio statis"
                const activeLocs = JSON.parse(localStorage.getItem('echosense_active_locations')) || {};
                const isActiveInSettings = activeLocs[loc.id] !== false; // Default true
                const statusLabel = isActiveInSettings ? 'Siaga lembut' : 'Audio statis';
                
                html += `
                <button onclick="switchLocation(${idx})" aria-pressed="false" class="w-full text-left p-4 rounded-card bg-[#FFFFFF] border border-[#D6E6ED] hover:border-[#0675A3] text-[#0A1F33] shadow-subtle transition group focus:outline-none" type="button">
                    <div class="flex items-center justify-between mb-1.5">
                        <span class="font-bold text-base text-[#0A1F33] group-hover:text-[#0675A3] transition">${loc.name.split(',')[0]}</span>
                        <span class="text-xs text-[#42586A]">${loc.region || 'Indonesia'}</span>
                    </div>
                    <div class="flex items-center justify-between text-xs text-[#42586A] pt-1">
                        <span class="">Ketuk untuk memantau</span>
                        <span class="text-xs text-[#42586A] bg-slate-100 px-2 py-0.5 rounded-full font-medium">${statusLabel}</span>
                    </div>
                </button>`;
            }
        });

        html += `
        <button onclick="document.getElementById('location-modal').classList.remove('hidden')" aria-label="Tambah lokasi lainnya" class="w-full mt-3 py-3.5 px-4 rounded-card border-2 border-dashed border-[#D6E6ED] hover:border-[#0675A3] bg-[#FFFFFF] text-[#0675A3] transition flex items-center justify-center gap-2 text-sm font-semibold focus:outline-none" type="button">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            <span>+ Tambah Lokasi Lainnya</span>
        </button>`;
        
        locationListContainer.innerHTML = html;
        
        // Update Main Header
        const mainHeader = document.querySelector('[data-purpose="audio-controller"] h1');
        if (locations.length > 0) {
            const activeLoc = locations[activeLocationIndex];
            if(mainHeader) {
                const shortName = activeLoc.name.split(',')[0]; // Ambil nama terpendek untuk header besar
                mainHeader.textContent = `Saat ini: ${shortName}`;
            }
        } else {
            if(mainHeader) mainHeader.textContent = `Pilih atau Tambah Lokasi`;
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
        if(idx === activeLocationIndex) return; // Ignore if same
        activeLocationIndex = idx;
        renderLocations();
        await loadDataAndPlay(true);
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
                alert("Gagal mendapatkan lokasi. Pastikan izin GPS diberikan.");
            });
        } else {
            alert("Geolokasi tidak didukung di browser ini.");
        }
    }

    function updateVolume(val) {
      volumeReadout.textContent = val + '%';
      volumeSlider.style.setProperty('--vol', val + '%');
      prefs.volume = val;
      localStorage.setItem('echosense_prefs', JSON.stringify(prefs));
      
      if (masterGain && isPlaying) {
          const now = audioCtx.currentTime;
          const isDucked = window.speechSynthesis.speaking;
          masterGain.gain.cancelScheduledValues(now);
          masterGain.gain.setValueAtTime(masterGain.gain.value, now);
          masterGain.gain.linearRampToValueAtTime((val / 100) * (isDucked ? 0.25 : 0.8), now + 0.5);
      }
    }
    
    function formatTime(seconds) {
        const m = Math.floor(seconds / 60);
        const s = Math.floor(seconds % 60);
        return `0${m}:${s < 10 ? '0' : ''}${s}`;
    }

    function startProgress() {
        clearInterval(progressInterval);
        playbackTime = 0;
        // Progress bar is now handled in a smaller interval for smoothness
        progressInterval = setInterval(() => {
            playbackTime += 0.1;
            if (playbackTime >= totalTime) {
                playbackTime = totalTime;
            }
            const percent = Math.floor((playbackTime / totalTime) * 100);
            progressBar.style.width = percent + '%';
            progressText.innerHTML = `${formatTime(Math.floor(playbackTime))} / ${formatTime(Math.floor(totalTime))} <span class="text-[#033067] font-bold ml-1">(${percent}%)</span>`;
        }, 100);
    }

    function drawWaveform() {
        if(!analyser) return;
        animationId = requestAnimationFrame(drawWaveform);
        const dataArray = new Uint8Array(analyser.frequencyBinCount);
        analyser.getByteFrequencyData(dataArray);
        
        for(let i = 1; i <= 7; i++) {
            const bar = document.querySelector(`.wave-bar-${i}`);
            if(bar) {
                const value = dataArray[i * 2] || 10;
                const height = Math.max(10, (value / 255) * 60);
                bar.style.height = `${height}px`;
                bar.style.animation = 'none';
            }
        }
    }

    async function loadDataAndPlay(autoPlay = true) {
        if (locations.length === 0) return alert('Silakan deteksi atau tambah lokasi terlebih dahulu!');
        
        playBtn.innerHTML = '<span aria-hidden="true" class="w-8 h-8 rounded-full bg-white text-[#0675A3] flex items-center justify-center shadow-inner"><svg class="w-4 h-4 fill-current animate-spin" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none" stroke-dasharray="31.4 31.4"></circle></svg></span><span>Memuat Data...</span>';
        document.getElementById('ai-summary-text').innerHTML = '<span class="animate-pulse">Mengambil data telemetri terbaru dari satelit...</span>';
        document.getElementById('ui-aqi-value').innerHTML = '<span class="animate-pulse text-sm">Memuat...</span>';
        document.getElementById('ui-weather-value').innerHTML = '<span class="animate-pulse text-sm">Memuat...</span>';
        document.getElementById('ui-traffic-congestion').innerHTML = '<span class="animate-pulse text-sm">Memuat...</span>';
        document.getElementById('ui-traffic-speed').innerHTML = '';
        
        try {
            const activeLoc = locations[activeLocationIndex];
            const response = await fetch(`/api/sonify?lat=${activeLoc.lat}&lon=${activeLoc.lon}&lang=${prefs.language}`);
            const data = await response.json();
            currentData = data;
            
            document.getElementById('ai-summary-text').textContent = data.briefing;
            document.getElementById('ui-aqi-value').textContent = `AQI ${Math.round(data.raw_data.aqi.pm25)}`;
            document.getElementById('ui-aqi-category').textContent = data.raw_data.aqi.category;
            
            const synthLangEl = document.getElementById('ui-synth-lang');
            if (synthLangEl) {
                if (prefs.language.startsWith('en')) {
                    synthLangEl.innerHTML = '<span aria-hidden="true" class="w-2 h-2 rounded-full bg-tealMint"></span>Sintesis suara: English (Aksara Sonik v1)';
                } else {
                    synthLangEl.innerHTML = '<span aria-hidden="true" class="w-2 h-2 rounded-full bg-tealMint"></span>Sintesis suara: Bahasa Indonesia (Aksara Sonik v1)';
                }
            }
            
            const aqiTag = document.getElementById('ui-aqi-category');
            if (data.raw_data.aqi.pm25 > 150) {
                aqiTag.className = "text-xs font-bold px-2.5 py-0.5 rounded-full bg-red-600 text-white";
                if(JSON.parse(localStorage.getItem('echosense_alerts') || '{}').alert_aqi && autoPlay) playAlertTone();
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
            
            const currentLocEl = document.querySelector('button[aria-pressed="true"] .border-t span:first-child');
            if(currentLocEl) currentLocEl.textContent = `AQI ${Math.round(data.raw_data.aqi.pm25)} • ${data.raw_data.weather.temperature}°C`;
            
            const timeStr = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }) + ' WIB';
            const timeTag = document.querySelector('.bg-trackBg > span:last-child');
            if (timeTag) timeTag.textContent = `Diperbarui: ${timeStr}`;
            
            if (autoPlay) {
                if (!isPlaying) {
                    startSoundscape(data);
                    isPlaying = true;
                    startProgress();
                    drawWaveform();
                } else {
                    updateSoundscape(data);
                }
                playBtn.innerHTML = '<span aria-hidden="true" class="w-8 h-8 rounded-full bg-white text-[#0675A3] flex items-center justify-center shadow-inner"><svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><rect x="6" y="4" width="4" height="16"></rect><rect x="14" y="4" width="4" height="16"></rect></svg></span><span>Jeda Audio</span>';
                playBtn.setAttribute('aria-pressed', 'true');
            } else {
                playBtn.innerHTML = '<span aria-hidden="true" class="w-8 h-8 rounded-full bg-white text-[#0675A3] flex items-center justify-center shadow-inner"><svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><polygon points="5 3 19 12 5 21 5 3"></polygon></svg></span><span>Play / Pause</span>';
                playBtn.setAttribute('aria-pressed', 'false');
            }
            
        } catch(e) {
            console.error(e);
            playBtn.innerHTML = '<span>Error memuat data</span>';
        }
    }

    function initAudio() {
        if (!audioCtx) {
            audioCtx = new (window.AudioContext || window.webkitAudioContext)();
            
            compressor = audioCtx.createDynamicsCompressor();
            compressor.threshold.value = -24;
            compressor.ratio.value = 4;
            
            masterGain = audioCtx.createGain();
            analyser = audioCtx.createAnalyser();
            analyser.fftSize = 64;
            
            masterGain.connect(compressor);
            compressor.connect(analyser);
            analyser.connect(audioCtx.destination);
        }
    }

    function startSoundscape(data) {
        initAudio();
        const now = audioCtx.currentTime;
        
        masterGain.gain.cancelScheduledValues(now);
        masterGain.gain.setValueAtTime(0, now);
        masterGain.gain.linearRampToValueAtTime((prefs.volume/100) * 0.8, now + 1); 
        
        const pitchOffset = (prefs.pitch - 50);
        
        // AQI Drone (Base Sine + User Instrument Overtone + Filter)
        aqiDroneOsc = audioCtx.createOscillator();
        aqiDroneOsc.type = 'sine';
        aqiDroneOsc.frequency.value = data.audio_params.frequency + pitchOffset;
        
        window.aqiOvertoneOsc = audioCtx.createOscillator();
        window.aqiOvertoneOsc.type = prefs.instrument || 'triangle';
        window.aqiOvertoneOsc.frequency.value = data.audio_params.frequency + pitchOffset;
        
        const aqiFilter = audioCtx.createBiquadFilter();
        aqiFilter.type = 'lowpass';
        aqiFilter.frequency.value = 1000;
        aqiFilter.Q.value = 0.8;
        
        aqiDroneGain = audioCtx.createGain();
        aqiDroneGain.gain.setValueAtTime(0, now);
        aqiDroneGain.gain.linearRampToValueAtTime(0.2, now + 1);
        
        // Base sine at full internal gain
        const baseGain = audioCtx.createGain();
        baseGain.gain.value = 1.0;
        aqiDroneOsc.connect(baseGain);
        baseGain.connect(aqiFilter);
        
        // Overtone at low internal gain for subtle texture
        const overtoneGain = audioCtx.createGain();
        overtoneGain.gain.value = 0.05;
        window.aqiOvertoneOsc.connect(overtoneGain);
        overtoneGain.connect(aqiFilter);
        
        aqiFilter.connect(aqiDroneGain);
        aqiDroneGain.connect(masterGain);
        
        aqiDroneOsc.start();
        window.aqiOvertoneOsc.start();
        
        // Weather (Triangle)
        weatherOsc = audioCtx.createOscillator();
        weatherGain = audioCtx.createGain();
        weatherOsc.type = 'triangle';
        weatherOsc.frequency.value = (data.audio_params.frequency + pitchOffset) * 1.5; 
        weatherGain.gain.value = 0;
        weatherGain.gain.linearRampToValueAtTime(0.15, now + 1);
        weatherOsc.connect(weatherGain);
        weatherGain.connect(masterGain);
        weatherOsc.start();
        
        trafficPulseGain = audioCtx.createGain();
        trafficPulseGain.gain.value = 0;
        trafficPulseGain.connect(masterGain);
        
        startTrafficPulse(data);
        playVoice(data);
    }
    
    function updateSoundscape(data) {
        if (!audioCtx) return;
        const now = audioCtx.currentTime;
        const pitchOffset = (prefs.pitch - 50);
        
        if (aqiDroneOsc) {
            aqiDroneOsc.frequency.cancelScheduledValues(now);
            aqiDroneOsc.frequency.setValueAtTime(aqiDroneOsc.frequency.value, now);
            aqiDroneOsc.frequency.linearRampToValueAtTime(data.audio_params.frequency + pitchOffset, now + 1);
        }
        if (window.aqiOvertoneOsc) {
            window.aqiOvertoneOsc.frequency.cancelScheduledValues(now);
            window.aqiOvertoneOsc.frequency.setValueAtTime(window.aqiOvertoneOsc.frequency.value, now);
            window.aqiOvertoneOsc.frequency.linearRampToValueAtTime(data.audio_params.frequency + pitchOffset, now + 1);
        }
        if (weatherOsc) {
            weatherOsc.frequency.linearRampToValueAtTime((data.audio_params.frequency + pitchOffset) * 1.5, now + 1);
        }
        
        startTrafficPulse(data);
        playVoice(data);
    }

    function startTrafficPulse(data) {
        if (beatInterval) clearInterval(beatInterval);
        const tempoMs = (60 / data.audio_params.tempo) * 1000;
        
        beatInterval = setInterval(() => {
            if (!isPlaying) return;
            const now = audioCtx.currentTime;
            
            trafficPulseOsc = audioCtx.createOscillator();
            trafficPulseOsc.type = 'triangle';
            trafficPulseOsc.frequency.value = (data.audio_params.frequency + (prefs.pitch - 50)) * 0.5;
            
            trafficPulseGain.gain.cancelScheduledValues(now);
            trafficPulseGain.gain.setValueAtTime(0, now);
            trafficPulseGain.gain.linearRampToValueAtTime(0.3, now + 0.05); // attack
            trafficPulseGain.gain.exponentialRampToValueAtTime(0.001, now + 0.2); // decay
            
            trafficPulseOsc.connect(trafficPulseGain);
            trafficPulseOsc.start(now);
            trafficPulseOsc.stop(now + 0.25);
        }, tempoMs);
    }

    function playAlertTone() {
        if (!audioCtx) return;
        const now = audioCtx.currentTime;
        const osc = audioCtx.createOscillator();
        const gain = audioCtx.createGain();
        osc.type = 'sawtooth';
        osc.frequency.value = 600;
        
        gain.gain.setValueAtTime(0, now);
        gain.gain.linearRampToValueAtTime(0.4, now + 0.1);
        gain.gain.exponentialRampToValueAtTime(0.001, now + 0.5);
        
        osc.connect(gain);
        gain.connect(masterGain);
        osc.start(now);
        osc.stop(now + 0.5);
    }
    
    function playVoice(data) {
        if(utterance) window.speechSynthesis.cancel();
        
        const voiceURI = prefs.voiceURI || 'default';
        const rateBtn = document.querySelector('button[aria-pressed="true"]');
        const playbackRate = (rateBtn && rateBtn.textContent.includes('1.25x')) ? 1.25 : 1.0;
        
        totalTime = Math.max(5, data.briefing.length / 14);
        
        const duckAmbient = () => {
            if (!audioCtx) return;
            const now = audioCtx.currentTime;
            if (aqiDroneGain) {
                aqiDroneGain.gain.cancelScheduledValues(now);
                aqiDroneGain.gain.setValueAtTime(aqiDroneGain.gain.value, now);
                aqiDroneGain.gain.linearRampToValueAtTime(0.05, now + 0.3);
            }
            if (weatherGain) {
                weatherGain.gain.cancelScheduledValues(now);
                weatherGain.gain.setValueAtTime(weatherGain.gain.value, now);
                weatherGain.gain.linearRampToValueAtTime(0.03, now + 0.3);
            }
        };
        
        const unduckAmbient = () => {
            if (!audioCtx || !isPlaying) return;
            const now = audioCtx.currentTime;
            if (aqiDroneGain) {
                aqiDroneGain.gain.cancelScheduledValues(now);
                aqiDroneGain.gain.setValueAtTime(aqiDroneGain.gain.value, now);
                aqiDroneGain.gain.linearRampToValueAtTime(0.2, now + 0.8);
            }
            if (weatherGain) {
                weatherGain.gain.cancelScheduledValues(now);
                weatherGain.gain.setValueAtTime(weatherGain.gain.value, now);
                weatherGain.gain.linearRampToValueAtTime(0.15, now + 0.8);
            }
        };

        if (voiceURI.startsWith('elevenlabs:')) {
            const elVoiceId = voiceURI.replace('elevenlabs:', '');
            
            // Get CSRF Token from meta tag
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            
            fetch('/api/tts', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken || '' },
                body: JSON.stringify({ text: data.briefing, voice_id: elVoiceId })
            }).then(async res => {
                if (!res.ok) {
                    const err = await res.json();
                    throw new Error(err.error || "ElevenLabs API Error");
                }
                return res.arrayBuffer();
            })
              .then(buffer => audioCtx.decodeAudioData(buffer))
              .then(audioBuffer => {
                  if (!isPlaying) return;
                  const source = audioCtx.createOscillator();
                  const ttsSource = audioCtx.createBufferSource();
                  ttsSource.buffer = audioBuffer;
                  ttsSource.playbackRate.value = playbackRate;
                  
                  const ttsGain = audioCtx.createGain();
                  ttsGain.gain.value = 1.0;
                  
                  ttsSource.connect(ttsGain);
                  ttsGain.connect(masterGain);
                  
                  ttsSource.onended = unduckAmbient;
                  
                  const playDelay = (prefs.playMode === 'berurutan') ? (totalTime / 2) : 0;
                  
                  setTimeout(() => {
                      if (!isPlaying) return;
                      duckAmbient();
                      ttsSource.start();
                  }, playDelay * 1000);
                  
              }).catch(e => {
                  console.error("ElevenLabs Playback Error:", e);
                  alert("Gagal memutar suara ElevenLabs: " + e.message + "\n\nSistem beralih ke suara Default OS.");
                  
                  // Fallback to local TTS
                  utterance = new SpeechSynthesisUtterance(data.briefing);
                  utterance.lang = (prefs.language === 'en') ? 'en-US' : 'id-ID';
                  utterance.rate = playbackRate;
                  utterance.onstart = duckAmbient;
                  utterance.onend = unduckAmbient;
                  window.speechSynthesis.speak(utterance);
              });
              
        } else {
            utterance = new SpeechSynthesisUtterance(data.briefing);
            utterance.lang = (prefs.language === 'en') ? 'en-US' : 'id-ID';
            
            if (voiceURI && voiceURI !== 'default') {
                const actualUri = voiceURI.replace('local:', '');
                const voices = window.speechSynthesis.getVoices();
                const selectedVoice = voices.find(v => v.voiceURI === actualUri);
                if (selectedVoice) {
                    utterance.voice = selectedVoice;
                }
            }
            
            utterance.rate = playbackRate;
            utterance.onstart = duckAmbient;
            utterance.onend = unduckAmbient;
            
            if (prefs.playMode === 'berurutan') {
                setTimeout(() => {
                    if (isPlaying) window.speechSynthesis.speak(utterance);
                }, (totalTime / 2) * 1000);
            } else {
                window.speechSynthesis.speak(utterance);
            }
        }
    }

    function stopSoundscape() {
        if (!audioCtx) return;
        const now = audioCtx.currentTime;
        const currentVol = masterGain.gain.value;
        
        masterGain.gain.cancelScheduledValues(now);
        masterGain.gain.setValueAtTime(currentVol, now);
        masterGain.gain.linearRampToValueAtTime(0.001, now + 0.5); 
        
        if (beatInterval) clearInterval(beatInterval);
        window.speechSynthesis.cancel();
        
        setTimeout(() => {
            if (aqiDroneOsc) { aqiDroneOsc.stop(); aqiDroneOsc.disconnect(); }
            if (window.aqiOvertoneOsc) { window.aqiOvertoneOsc.stop(); window.aqiOvertoneOsc.disconnect(); }
            if (weatherOsc) { weatherOsc.stop(); weatherOsc.disconnect(); }
        }, 600);
    }

    function stopAudioPlayback() {
        stopSoundscape();
        if (progressInterval) clearInterval(progressInterval);
        if (animationId) cancelAnimationFrame(animationId);
        isPlaying = false;
        
        for(let i = 1; i <= 7; i++) {
            const bar = document.querySelector(`.wave-bar-${i}`);
            if(bar) bar.style.height = '10px';
        }
        
        playBtn.innerHTML = '<span aria-hidden="true" class="w-8 h-8 rounded-full bg-white text-[#0675A3] flex items-center justify-center shadow-inner"><svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><polygon points="5 3 19 12 5 21 5 3"></polygon></svg></span><span>Play / Pause</span>';
        playBtn.setAttribute('aria-pressed', 'false');
    }

    function toggleSonification() {
        if (!isPlaying) {
            if (currentData) {
               startSoundscape(currentData);
               isPlaying = true;
               startProgress();
               drawWaveform();
               playBtn.innerHTML = '<span aria-hidden="true" class="w-8 h-8 rounded-full bg-white text-[#0675A3] flex items-center justify-center shadow-inner"><svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><rect x="6" y="4" width="4" height="16"></rect><rect x="14" y="4" width="4" height="16"></rect></svg></span><span>Jeda Audio</span>';
               playBtn.setAttribute('aria-pressed', 'true');
            } else {
               loadDataAndPlay();
            }
        } else {
            stopAudioPlayback();
        }
    }

    playBtn.addEventListener('click', toggleSonification);
    
    // Bind Ulang
    document.querySelector('button[aria-label="Ulang pemutaran audio sonifikasi"]')?.addEventListener('click', () => {
        playbackTime = 0;
        if(isPlaying) {
            toggleSonification(); 
            setTimeout(toggleSonification, 300);
        }
    });

    // Bind Lokasi Berikutnya
    document.querySelector('button[aria-label="Pindah ke pemutaran lokasi berikutnya"]')?.addEventListener('click', () => {
        let next = activeLocationIndex + 1;
        if(next >= locations.length) next = 0;
        switchLocation(next);
    });

    // Spacebar listener
    document.addEventListener('keydown', function(event) {
      if (event.code === 'Space' && event.target.tagName !== 'INPUT' && event.target.tagName !== 'TEXTAREA') {
        event.preventDefault();
        playBtn.classList.add('ring-4', 'ring-[#0675A3]');
        toggleSonification();
        setTimeout(() => playBtn.classList.remove('ring-4', 'ring-[#0675A3]'), 200);
      }
    });
    
    // Init on load
    document.addEventListener('DOMContentLoaded', init);
  </script>

  <!-- Location Search Modal -->
  <div id="location-modal" class="fixed inset-0 bg-[#0A1F33]/80 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
      <div class="bg-white w-full max-w-md rounded-[32px] p-8 shadow-2xl relative">
          <button onclick="document.getElementById('location-modal').classList.add('hidden')" class="absolute top-6 right-6 text-[#42586A] hover:text-[#0A1F33] focus:outline-none">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
          </button>
          
          <h2 class="text-2xl font-bold text-[#0A1F33] font-outfit mb-2">Tambah Lokasi Lainnya</h2>
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
<!-- END: InteractiveAccessibilityScript -->
</body></html>