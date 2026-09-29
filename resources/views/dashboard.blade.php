<!DOCTYPE html>

<html lang="id"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
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
<span class="text-xs text-textSecondary bg-trackBg px-2 py-0.5 rounded-full border border-cardBorder">3 terpantau</span>
</div>
<div class="flex flex-col gap-3">
<!-- Active Location Card: Jakarta -->
<button aria-pressed="true" class="w-full text-left p-4 rounded-card bg-deepNavy text-pageBg border-2 border-deepNavy shadow-subtle transition relative group focus:outline-none" type="button">
<div class="flex items-center justify-between mb-2">
<div class="flex items-center gap-2.5">
<span aria-hidden="true" class="relative flex h-3 w-3">
<span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-sunrise-start opacity-75"></span>
<span class="relative inline-flex rounded-full h-3 w-3 bg-sunrise-start"></span>
</span>
<span class="font-bold text-base text-white">Jakarta (saat ini)</span>
</div>
<span class="text-xs px-2.5 py-0.5 rounded-full bg-sunrise-start text-deepNavy font-semibold">Aktif</span>
</div>
<div class="flex items-center justify-between text-xs text-slate-200 pt-2 border-t border-white/15">
<span class="">AQI 80 • 28°C</span>
<span class="text-xs text-sunrise-start font-medium">Memutar audio</span>
</div>
</button>
<!-- Inactive Location Card: Bandung -->
<button aria-pressed="false" class="w-full text-left p-4 rounded-card bg-cardBg border border-cardBorder hover:border-aquaBlue text-textPrimary shadow-subtle transition group focus:outline-none" type="button">
<div class="flex items-center justify-between mb-1.5">
<span class="font-bold text-base text-textPrimary group-hover:text-aquaBlue transition">Bandung</span>
<span class="text-xs text-textSecondary">Jawa Barat</span>
</div>
<div class="flex items-center justify-between text-xs text-textSecondary pt-1">
<span class="">AQI 42 (baik) • 22°C</span>
<span class="text-xs px-2 py-0.5 rounded bg-trackBg text-textSecondary">Siaga lembut</span>
</div>
</button>
<!-- Inactive Location Card: Surabaya -->
<button aria-pressed="false" class="w-full text-left p-4 rounded-card bg-cardBg border border-cardBorder hover:border-aquaBlue text-textPrimary shadow-subtle transition group focus:outline-none" type="button">
<div class="flex items-center justify-between mb-1.5">
<span class="font-bold text-base text-textPrimary group-hover:text-aquaBlue transition">Surabaya</span>
<span class="text-xs text-textSecondary">Jawa Timur</span>
</div>
<div class="flex items-center justify-between text-xs text-textSecondary pt-1">
<span class="">AQI 95 (sedang) • 31°C</span>
<span class="text-xs px-2 py-0.5 rounded bg-trackBg text-textSecondary">Audio statis</span>
</div>
</button>
<!-- Add Location Button -->
<button aria-label="Tambah lokasi baru" class="w-full py-3.5 px-4 rounded-card border-2 border-dashed border-cardBorder hover:border-aquaBlue bg-cardBg text-aquaBlue hover:bg-trackBg/40 transition flex items-center justify-center gap-2 text-sm font-semibold focus:outline-none" type="button">
<svg aria-hidden="true" class="w-4 h-4" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" viewbox="0 0 24 24">
<line x1="12" x2="12" y1="5" y2="19"></line>
<line x1="5" x2="19" y1="12" y2="12"></line>
</svg>
<span class="">+ Tambah lokasi</span>
</button>
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
<span class="text-deepNavy font-bold" id="volume-readout">80%</span>
</div>
<input aria-label="Tingkat volume audio" class="w-full cursor-pointer focus:outline-none" id="volume-slider" max="100" min="0" oninput="updateVolume(this.value)" style="--vol: 80%;" type="range" value="80"/>
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
<span class="bg-trackBg px-1.5 py-0.5 rounded border border-cardBorder font-medium text-textPrimary">Kualitas udara Jakarta tergolong sedang dengan indeks 80.</span> Suhu saat ini 28°C kondisi berawan, hembusan angin 12 km/j dari barat laut. Kondisi lalu lintas jalan protokol lancar dengan kecepatan rata-rata kendaraan 45 km/j.
            </p>
</div>
<!-- Subtext Details -->
<div class="flex flex-wrap items-center justify-between text-xs text-textSecondary pt-1">
<span class="flex items-center gap-1.5">
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
<span class="text-2xl font-bold text-textPrimary">AQI 80</span>
<span class="text-xs font-bold px-2.5 py-0.5 rounded-full bg-sunrise-start text-deepNavy">Sedang</span>
</div>
<p class="text-xs text-textSecondary">PM2.5: <span class="font-medium text-textPrimary">35</span> • PM10: <span class="font-medium text-textPrimary">55</span></p>
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
<span class="text-lg font-bold text-textPrimary">Cerah berawan 28°C</span>
</div>
<div class="mb-2">
<span class="text-xs font-bold px-2.5 py-0.5 rounded-full bg-tealMint text-deepNavy">Baik</span>
</div>
<p class="text-xs text-textSecondary">Kelembapan: <span class="font-medium text-textPrimary">65%</span> • Angin: <span class="font-medium text-textPrimary">12 km/j</span></p>
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
<span class="text-2xl font-bold text-textPrimary">Lancar</span>
<span class="text-xs font-bold px-2.5 py-0.5 rounded-full bg-tealMint text-deepNavy">Lancar / Baik</span>
</div>
<p class="text-xs text-textSecondary">Kecepatan rata-rata: <span class="font-medium text-textPrimary">45 km/j</span></p>
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
    function updateVolume(val) {
      document.getElementById('volume-readout').textContent = val + '%';
      document.getElementById('volume-slider').style.setProperty('--vol', val + '%');
    }

    // Spacebar listener for accessible Play/Pause toggle
    document.addEventListener('keydown', function(event) {
      if (event.code === 'Space' && event.target.tagName !== 'INPUT' && event.target.tagName !== 'TEXTAREA') {
        event.preventDefault();
        const playBtn = document.getElementById('btn-play-pause');
        if (playBtn) {
          playBtn.classList.add('ring-4', 'ring-aquaBlue');
          const isPressed = playBtn.getAttribute('aria-pressed') === 'true';
          playBtn.setAttribute('aria-pressed', !isPressed);
          setTimeout(() => {
            playBtn.classList.remove('ring-4', 'ring-aquaBlue');
          }, 200);
        }
      }
    });
  </script>
<!-- END: InteractiveAccessibilityScript -->
</body></html>