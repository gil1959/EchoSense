<!DOCTYPE html><html lang="id"><head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>EchoSense - Pengaturan Preferensi Suara & Peringatan</title>
<link rel="icon" type="image/png" href="{{ asset('favicon.png') }}"/>
<!-- Google Fonts: Atkinson Hyperlegible Next -->
<link href="https://fonts.googleapis.com" rel="preconnect">
<link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect">
<link href="https://fonts.googleapis.com/css2?family=Atkinson+Hyperlegible+Next:ital,wght@0,400;0,600;0,700;1,400&amp;display=swap" rel="stylesheet">
<!-- Tailwind CSS v3 with Plugins -->
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<!-- Tailwind Configuration -->
<script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            page: '#F4F9FB',
            navy: '#033067',
            deep: '#0A1F33',
            muted: '#42586A',
            bordercol: '#D6E6ED',
            slatecol: '#6B8899',
            ocean: '#0675A3',
            tealcol: '#14C5D9',
            sunrise: '#FEB161',
            crimson: '#C4442A',
            trackbg: '#E6F2F7',
          },
          fontFamily: {
            sans: ['"Atkinson Hyperlegible Next"', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'Roboto', 'sans-serif'],
          },
          boxShadow: {
            panel: '0 4px 20px -2px rgba(10, 31, 51, 0.05), 0 2px 6px -1px rgba(10, 31, 51, 0.03)',
            dock: '0 8px 30px -4px rgba(3, 48, 103, 0.08)',
          }
        }
      }
    }
  </script>
<!-- Custom Sliders, Form Controls & Focus Accessibility Styles -->
<style data-purpose="custom-inputs">
    *:focus-visible {
      outline: none;
      box-shadow: 0 0 0 2px #F4F9FB, 0 0 0 5px #0675A3 !important;
    }

    /* Custom Styled Range Sliders */
    input[type=range] {
      -webkit-appearance: none;
      background: transparent;
    }
    input[type=range]:focus {
      outline: none;
    }
    input[type=range]::-webkit-slider-thumb {
      -webkit-appearance: none;
      height: 22px;
      width: 22px;
      border-radius: 9999px;
      background: #033067;
      border: 3px solid #FFFFFF;
      cursor: pointer;
      box-shadow: 0 1px 4px rgba(3, 48, 103, 0.4);
      margin-top: -7px;
      transition: transform 0.15s ease, background-color 0.15s ease;
    }
    input[type=range]::-webkit-slider-thumb:hover {
      transform: scale(1.15);
      background: #0675A3;
    }
    input[type=range]::-moz-range-thumb {
      height: 22px;
      width: 22px;
      border-radius: 9999px;
      background: #033067;
      border: 3px solid #FFFFFF;
      cursor: pointer;
      box-shadow: 0 1px 4px rgba(3, 48, 103, 0.4);
    }
    input[type=range]::-webkit-slider-runnable-track {
      width: 100%;
      height: 8px;
      cursor: pointer;
      border-radius: 9999px;
      border: 1px solid #6B8899;
    }
    input[type=range]::-moz-range-track {
      width: 100%;
      height: 8px;
      cursor: pointer;
      border-radius: 9999px;
      border: 1px solid #6B8899;
    }
  </style>
</head>
<body class="bg-page text-deep min-h-screen flex flex-col font-sans antialiased selection:bg-ocean selection:text-white">
<!-- BEGIN: MainHeader -->
<x-navbar />
<!-- END: MainHeader -->
<!-- BEGIN: MainContent -->
<main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
<!-- Page Title & Description -->
<section aria-labelledby="heading-title" class="space-y-2">
<h1 class="text-3xl font-bold tracking-tight text-deep" id="heading-title">
        Pengaturan preferensi
      </h1>
<p class="text-muted text-base max-w-4xl leading-relaxed">
        Personalisasi suara narasi, bahasa, kecepatan bicara, dan ambang batas peringatan lingkungan untuk pengalaman EchoSense yang optimal.
      </p>
</section>
<!-- Two-Column Preferences Grid Form -->
<form class="space-y-8" id="preferences-form" onsubmit="event.preventDefault();">
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">
<!-- BEGIN: LeftPanel - Preferensi Suara & Bahasa -->
<section aria-labelledby="section-voice" class="bg-white border border-bordercol rounded-[20px] p-7 shadow-panel space-y-7">
<div class="border-b border-bordercol pb-4 flex items-center justify-between">
<h2 class="text-lg font-bold text-deep flex items-center gap-2.5" id="section-voice">
<span aria-hidden="true" class="w-2.5 h-2.5 rounded-full bg-ocean"></span>
              Preferensi suara
            </h2>
<span class="text-xs font-semibold text-muted bg-trackbg px-3 py-1 rounded-full border border-bordercol">
              Narasi TTS
            </span>
</div>
<!-- Dropdowns Section -->
<div class="space-y-5">
<!-- Bahasa Narasi Dropdown -->
<div class="space-y-1.5">
<label class="block text-sm font-semibold text-deep" for="select-language">
                Bahasa narasi
              </label>
<div class="flex space-x-2">
<div class="relative flex-1">
<select class="w-full h-12 rounded-[10px] bg-white border-[1.5px] border-slatecol text-deep px-4 pr-10 text-sm focus:border-ocean focus:ring-0 transition" id="select-language" name="language">
<option selected="" value="id">Bahasa Indonesia</option>
<option value="en">English</option>
</select>
</div>
<!-- Voice Sample Button -->
<button id="btn-test-language" aria-label="Uji suara narasi bahasa" class="h-12 px-4 bg-trackbg hover:bg-slate-200 border-[1.5px] border-slatecol text-navy font-semibold rounded-[10px] flex items-center justify-center transition" title="Uji suara narasi" type="button">
<svg aria-hidden="true" class="w-4 h-4 text-ocean" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" viewBox="0 0 24 24">
<polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"></polygon>
<path d="M19 12a7 7 0 0 0-7-7"></path>
<path d="M12 19a7 7 0 0 0 7-7"></path>
</svg>
</button>
</div>
</div>
<!-- Jenis Suara Dropdown -->
<div class="space-y-1.5">
<label class="block text-sm font-semibold text-deep" for="select-voice">
                Jenis Suara (Karakter TTS)
              </label>
<div class="flex space-x-2">
<div class="relative flex-1">
<select class="w-full h-12 rounded-[10px] bg-white border-[1.5px] border-slatecol text-deep px-4 pr-10 text-sm focus:border-ocean focus:ring-0 transition" id="select-voice" name="voice">
<option value="default">Default OS</option>
</select>
</div>
</div>
</div>
</div>
<!-- Sliders Section -->
<div class="pt-4 border-t border-bordercol space-y-6">
<!-- Kecepatan Bicara Slider -->
<div class="space-y-2">
<div class="flex items-center justify-between text-sm">
<label class="text-deep font-semibold" for="speed-range">
                  Kecepatan bicara
                </label>
<div class="flex items-center gap-1.5">
<span class="text-ocean font-bold" id="speed-val">1.0x</span>
</div>
</div>
<div class="relative flex items-center">
<input aria-label="Atur kecepatan bicara TTS" class="w-full h-2 rounded-lg cursor-pointer bg-trackbg" id="speed-range" max="150" min="75" oninput="updateSpeedSlider(this)" style="background: linear-gradient(90deg, #0675A3 0%, #14C5D9 33%, #E6F2F7 33%, #E6F2F7 100%);" type="range" value="100">
</div>
<div class="flex justify-between text-xs text-muted">
<span>0.75x</span>
<span>1.0x</span>
<span>1.5x</span>
</div>
</div>
<!-- Volume TTS Slider -->
<div class="space-y-2">
<div class="flex items-center justify-between text-sm">
<label class="text-deep font-semibold" for="volume-range">
                  Volume narasi
                </label>
<div class="flex items-center gap-1.5">
<span class="text-ocean font-bold" id="volume-val">80%</span>
</div>
</div>
<div class="relative flex items-center">
<input aria-label="Atur volume narasi TTS" class="w-full h-2 rounded-lg cursor-pointer bg-trackbg" id="volume-range" max="100" min="10" oninput="updateSliderTrack(this, 'volume-val', '%')" style="background: linear-gradient(90deg, #0675A3 0%, #14C5D9 80%, #E6F2F7 80%, #E6F2F7 100%);" type="range" value="80">
</div>
</div>
</div>
<!-- Toggles Section -->
<div class="pt-4 border-t border-bordercol space-y-4">
<!-- Auto-start greeting toggle -->
<label class="flex items-center justify-between p-3.5 rounded-xl border border-bordercol hover:border-slatecol bg-white transition cursor-pointer">
<div class="flex items-center space-x-3">
<div>
<span class="text-sm font-semibold text-deep block">Sapaan otomatis</span>
<span class="text-xs text-muted">Otomatis menyapa saat membuka website</span>
</div>
</div>
<input class="h-6 w-6 rounded text-ocean border-slatecol focus:ring-ocean" name="auto_greet" type="checkbox" checked>
</label>
<!-- Interval monitoring -->
<div class="space-y-1.5">
<label class="block text-sm font-semibold text-deep" for="select-interval">
                Interval pemantauan real-time
              </label>
<select class="w-full h-12 rounded-[10px] bg-white border-[1.5px] border-slatecol text-deep px-4 pr-10 text-sm focus:border-ocean focus:ring-0 transition" id="select-interval" name="interval">
<option value="30">Setiap 30 detik</option>
<option value="60" selected>Setiap 60 detik (Disarankan)</option>
<option value="120">Setiap 2 menit</option>
<option value="300">Setiap 5 menit</option>
</select>
</div>
</div>
</section>
<!-- END: LeftPanel -->
<!-- BEGIN: RightPanel - Peringatan lingkungan -->
<section aria-labelledby="section-peringatan" class="bg-white border border-bordercol rounded-[20px] p-7 shadow-panel space-y-7">
<div class="border-b border-bordercol pb-4 flex items-center justify-between">
<h2 class="text-lg font-bold text-deep flex items-center gap-2.5" id="section-peringatan">
<span aria-hidden="true" class="w-2.5 h-2.5 rounded-full bg-sunrise"></span>
              Peringatan lingkungan
            </h2>
<span class="text-xs font-semibold text-muted bg-trackbg px-3 py-1 rounded-full border border-bordercol">
              Narasi peringatan
            </span>
</div>
<!-- Info banner -->
<div class="bg-trackbg border border-bordercol rounded-xl p-4 flex items-start gap-3">
<svg class="w-5 h-5 text-ocean flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
<circle cx="12" cy="12" r="10"></circle>
<line x1="12" y1="16" x2="12" y2="12"></line>
<line x1="12" y1="8" x2="12.01" y2="8"></line>
</svg>
<p class="text-sm text-muted leading-relaxed">
              Semua peringatan disampaikan melalui <strong class="text-deep">narasi suara TTS</strong> secara otomatis. Sistem akan membacakan peringatan dengan suara natural saat terdeteksi perubahan kondisi lingkungan yang signifikan.
            </p>
</div>
<!-- Ambang Peringatan Checkbox Fieldset -->
<fieldset class="space-y-3">
<legend class="block text-sm font-semibold text-deep mb-2">
              Ambang batas kondisi
            </legend>
<div class="space-y-3">
<label class="flex items-center justify-between p-3.5 rounded-xl border border-bordercol hover:border-slatecol bg-white transition cursor-pointer">
<div class="flex items-center space-x-3">
<input class="h-6 w-6 rounded text-ocean border-slatecol focus:ring-ocean alert-cb" name="alert_aqi" type="checkbox" onchange="updateAlertBadge(this, 'badge-aqi', 'Waspada', 'bg-sunrise text-navy')">
<div>
<span class="text-sm font-semibold text-deep block">Polusi udara tinggi</span>
<span class="text-xs text-muted">AQI di atas 150 — narasi peringatan otomatis</span>
</div>
</div>
<span id="badge-aqi" class="text-xs font-medium px-2.5 py-1 rounded-md bg-trackbg text-muted border border-bordercol">Nonaktif</span>
</label>
<label class="flex items-center justify-between p-3.5 rounded-xl border border-bordercol hover:border-slatecol bg-white transition cursor-pointer">
<div class="flex items-center space-x-3">
<input class="h-6 w-6 rounded text-ocean border-slatecol focus:ring-ocean alert-cb" name="alert_weather" type="checkbox" onchange="updateAlertBadge(this, 'badge-weather', 'Kritis', 'bg-crimson text-white')">
<div>
<span class="text-sm font-semibold text-deep block">Cuaca berbahaya</span>
<span class="text-xs text-muted">Hujan deras, badai petir — narasi peringatan otomatis</span>
</div>
</div>
<span id="badge-weather" class="text-xs font-medium px-2.5 py-1 rounded-md bg-trackbg text-muted border border-bordercol">Nonaktif</span>
</label>
<label class="flex items-center justify-between p-3.5 rounded-xl border border-bordercol hover:border-slatecol bg-white transition cursor-pointer">
<div class="flex items-center space-x-3">
<input class="h-6 w-6 rounded text-ocean border-slatecol focus:ring-ocean alert-cb" name="alert_traffic" type="checkbox" onchange="updateAlertBadge(this, 'badge-traffic', 'Aktif', 'bg-ocean text-white')">
<div>
<span class="text-sm font-semibold text-deep block">Lalu lintas berat</span>
<span class="text-xs text-muted">Kemacetan total — narasi peringatan otomatis</span>
</div>
</div>
<span id="badge-traffic" class="text-xs font-medium px-2.5 py-1 rounded-md bg-trackbg text-muted border border-bordercol">Nonaktif</span>
</label>
</div>
</fieldset>
<!-- Repeat alert toggle -->
<fieldset class="pt-4 border-t border-bordercol space-y-3">
<legend class="block text-sm font-semibold text-deep mb-2">
              Opsi peringatan
            </legend>
<label class="flex items-center justify-between p-3.5 rounded-xl border border-bordercol hover:border-slatecol bg-white transition cursor-pointer">
<div class="flex items-center space-x-3">
<div>
<span class="text-sm font-semibold text-deep block">Ulangi peringatan</span>
<span class="text-xs text-muted">Bacakan ulang peringatan jika user tidak merespons dalam 2 menit</span>
</div>
</div>
<input class="h-6 w-6 rounded text-ocean border-slatecol focus:ring-ocean" name="repeat_alert" type="checkbox">
</label>
</fieldset>
<!-- Lokasi Dipantau Section -->
<fieldset class="pt-4 border-t border-bordercol space-y-3">
<div class="flex items-center justify-between mb-1">
<legend class="text-sm font-semibold text-deep">
                Lokasi dipantau
              </legend>
<span id="locations-count" class="text-xs text-muted font-medium">Memuat...</span>
</div>
<div id="locations-list-container" aria-label="Pemilihan kota pemantauan" class="flex flex-wrap gap-2.5">
<!-- Dynamic Locations go here -->
</div>
</fieldset>
</section>
<!-- END: RightPanel -->
</div>
<!-- BEGIN: BottomActionDock -->
<section aria-label="Aksi pengaturan" class="p-5 rounded-[20px] bg-white border border-bordercol flex flex-wrap items-center justify-between gap-4 shadow-panel">
<div class="flex flex-wrap items-center gap-3.5">
<!-- Primary: Simpan Pengaturan -->
<button class="min-h-[44px] px-6 py-2.5 rounded-[10px] bg-ocean hover:bg-[#056087] text-white text-sm font-bold shadow-sm flex items-center space-x-2.5 transition active:scale-[0.98]" type="submit">
<!-- Checkmark Icon -->
<svg aria-hidden="true" class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" viewBox="0 0 24 24">
<polyline points="20 6 9 17 4 12"></polyline>
</svg>
<span class="">Simpan pengaturan</span>
</button>
<!-- Secondary: Pratinjau Suara -->
<button class="min-h-[44px] px-6 py-2.5 rounded-[10px] bg-transparent border-2 border-navy text-navy hover:bg-navy/5 text-sm font-bold flex items-center space-x-2.5 transition active:scale-[0.98]" id="btn-preview" type="button">
<!-- Audio Play Icon -->
<svg aria-hidden="true" class="w-4 h-4 text-navy" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" viewBox="0 0 24 24">
<polygon points="5 3 19 12 5 21 5 3"></polygon>
</svg>
<span class="">Pratinjau suara</span>
</button>
</div>
<!-- Tertiary/Ghost: Setel Ulang -->
<button class="min-h-[44px] px-5 py-2.5 rounded-[10px] border-2 border-slatecol text-muted hover:text-navy hover:border-navy hover:bg-page text-sm font-semibold flex items-center space-x-2 transition" id="btn-reset" type="button">
<!-- Reset Icon -->
<svg aria-hidden="true" class="w-4 h-4 text-muted" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" viewBox="0 0 24 24">
<path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path>
<path d="M3 3v5h5"></path>
</svg>
<span class="">Setel ulang</span>
</button>
</section>
<!-- END: BottomActionDock -->
</form>
</main>
<!-- END: MainContent -->
<!-- BEGIN: MainFooter -->
<x-footer />
<!-- END: MainFooter -->
<!-- Skrip Interaksi Pengaturan -->
<script data-purpose="settings-interaction">
    // Preferensi bawaan
    const defaultPrefs = {
        volume: 80,
        speed: 100,
        language: 'id',
        voiceURI: 'elevenlabs:Xb7hH8MSUJpSbSDYk0k2',
        autoGreet: true,
        monitorInterval: 60,
        repeatAlert: false
    };

    // Memuat preferensi tersimpan
    let prefs = JSON.parse(localStorage.getItem('echosense_prefs')) || defaultPrefs;
    // Migrasi nilai bawaan jika belum tersedia
    if (prefs.speed === undefined) prefs.speed = defaultPrefs.speed;
    if (prefs.autoGreet === undefined) prefs.autoGreet = defaultPrefs.autoGreet;
    if (prefs.monitorInterval === undefined) prefs.monitorInterval = defaultPrefs.monitorInterval;
    if (prefs.repeatAlert === undefined) prefs.repeatAlert = defaultPrefs.repeatAlert;
    if (prefs.voiceURI === undefined || prefs.voiceURI === 'default') prefs.voiceURI = defaultPrefs.voiceURI;

    function initSettings() {
        const language = document.getElementById('select-language');
        const volumeRange = document.getElementById('volume-range');
        const speedRange = document.getElementById('speed-range');
        const intervalSelect = document.getElementById('select-interval');
        const autoGreetCb = document.querySelector('input[name="auto_greet"]');
        const repeatAlertCb = document.querySelector('input[name="repeat_alert"]');
        
        if (language) language.value = prefs.language || 'id';
        
        if (volumeRange) {
            volumeRange.value = prefs.volume || 80;
            updateSliderTrack(volumeRange, 'volume-val', '%');
        }
        
        if (speedRange) {
            speedRange.value = prefs.speed || 100;
            updateSpeedSlider(speedRange);
        }
        
        if (intervalSelect) intervalSelect.value = prefs.monitorInterval || 60;
        if (autoGreetCb) autoGreetCb.checked = prefs.autoGreet !== false;
        if (repeatAlertCb) repeatAlertCb.checked = prefs.repeatAlert === true;
        
        setTimeout(populateVoices, 100);
        
        // Memuat status toggle peringatan
        const alerts = JSON.parse(localStorage.getItem('echosense_alerts')) || { alert_aqi: true, alert_weather: true, alert_traffic: false };
        const alertAqiEl = document.querySelector('input[name="alert_aqi"]');
        const alertWeatherEl = document.querySelector('input[name="alert_weather"]');
        const alertTrafficEl = document.querySelector('input[name="alert_traffic"]');
        
        if (alertAqiEl) {
            alertAqiEl.checked = alerts.alert_aqi;
            updateAlertBadge(alertAqiEl, 'badge-aqi', 'Waspada', 'bg-sunrise text-navy');
        }
        if (alertWeatherEl) {
            alertWeatherEl.checked = alerts.alert_weather;
            updateAlertBadge(alertWeatherEl, 'badge-weather', 'Kritis', 'bg-crimson text-white');
        }
        if (alertTrafficEl) {
            alertTrafficEl.checked = alerts.alert_traffic;
            updateAlertBadge(alertTrafficEl, 'badge-traffic', 'Aktif', 'bg-ocean text-white');
        }
    }

    function updateAlertBadge(checkbox, badgeId, activeText, activeClass) {
        const badge = document.getElementById(badgeId);
        if (!badge) return;
        if (checkbox.checked) {
            badge.className = `text-xs font-bold px-2.5 py-1 rounded-md ${activeClass}`;
            badge.textContent = activeText;
        } else {
            badge.className = 'text-xs font-medium px-2.5 py-1 rounded-md bg-trackbg text-muted border border-bordercol';
            badge.textContent = 'Nonaktif';
        }
    }

    function updateSliderTrack(slider, valueDisplayId, unit) {
      const val = slider.value;
      const displayElem = document.getElementById(valueDisplayId);
      if (displayElem) {
        displayElem.innerText = val + unit;
      }
      const percent = ((val - slider.min) / (slider.max - slider.min)) * 100;
      slider.style.background = `linear-gradient(90deg, #0675A3 0%, #14C5D9 ${percent}%, #E6F2F7 ${percent}%, #E6F2F7 100%)`;
    }

    function updateSpeedSlider(slider) {
      const val = slider.value;
      const speedX = (val / 100).toFixed(2).replace(/\.?0+$/, '') + 'x';
      document.getElementById('speed-val').innerText = speedX;
      const percent = ((val - slider.min) / (slider.max - slider.min)) * 100;
      slider.style.background = `linear-gradient(90deg, #0675A3 0%, #14C5D9 ${percent}%, #E6F2F7 ${percent}%, #E6F2F7 100%)`;
    }

    // Inisialisasi pengaturan saat halaman siap
    document.addEventListener('DOMContentLoaded', initSettings);

    let availableVoices = [];
    async function populateVoices() {
        const languageEl = document.getElementById('select-language');
        if(!languageEl) return;
        const selectLang = languageEl.value;
        const targetLang = selectLang === 'id' ? 'id' : 'en'; 
        const selectVoice = document.getElementById('select-voice');
        if(!selectVoice) return;
        
        availableVoices = window.speechSynthesis.getVoices();
        const filteredVoices = availableVoices.filter(v => v.lang.toLowerCase().startsWith(targetLang));
        
        selectVoice.innerHTML = '<option value="default">Default OS (Disarankan)</option>';
        
        // Menambahkan opsi suara lokal atau browser
        const optGroup1 = document.createElement('optgroup');
        optGroup1.label = "Suara Bawaan (Browser/OS)";
        filteredVoices.forEach((voice) => {
            const isLocal = voice.localService ? 'Lokal' : 'Cloud';
            const isDefault = voice.default ? ' - Bawaan' : '';
            const option = document.createElement('option');
            option.value = 'local:' + voice.voiceURI;
            option.textContent = `${voice.name} (${isLocal}${isDefault})`;
            if (prefs.voiceURI === option.value) option.selected = true;
            optGroup1.appendChild(option);
        });
        selectVoice.appendChild(optGroup1);
        
        // Mengambil daftar suara dari ElevenLabs
        try {
            const response = await fetch('/api/voices');
            const data = await response.json();
            if (data && data.voices && data.voices.length > 0) {
                const optGroup2 = document.createElement('optgroup');
                optGroup2.label = "ElevenLabs (Premium API)";
                data.voices.forEach(voice => {
                    const option = document.createElement('option');
                    option.value = 'elevenlabs:' + voice.voice_id;
                    option.textContent = `${voice.name} (${voice.category})`;
                    if (prefs.voiceURI === option.value) option.selected = true;
                    optGroup2.appendChild(option);
                });
                selectVoice.appendChild(optGroup2);
            }
        } catch(e) {
            console.error("ElevenLabs API error:", e);
        }
    }

    if (window.speechSynthesis) {
        window.speechSynthesis.onvoiceschanged = populateVoices;
    }
    
    document.getElementById('select-language')?.addEventListener('change', populateVoices);

    // Menyimpan perubahan preferensi
    document.querySelector('form')?.addEventListener('submit', function(e) {
        e.preventDefault();
        prefs.volume = parseInt(document.getElementById('volume-range').value);
        prefs.speed = parseInt(document.getElementById('speed-range').value);
        prefs.language = document.getElementById('select-language').value;
        prefs.voiceURI = document.getElementById('select-voice').value;
        prefs.autoGreet = document.querySelector('input[name="auto_greet"]').checked;
        prefs.monitorInterval = parseInt(document.getElementById('select-interval').value);
        prefs.repeatAlert = document.querySelector('input[name="repeat_alert"]').checked;
        
        localStorage.setItem('echosense_prefs', JSON.stringify(prefs));
        
        // Simpan preferensi peringatan
        const alert_aqi = document.querySelector('input[name="alert_aqi"]').checked;
        const alert_weather = document.querySelector('input[name="alert_weather"]').checked;
        const alert_traffic = document.querySelector('input[name="alert_traffic"]').checked;
        localStorage.setItem('echosense_alerts', JSON.stringify({ alert_aqi, alert_weather, alert_traffic }));
        
        // Simpan status lokasi aktif
        const activeLocs = {};
        document.querySelectorAll('.loc-checkbox').forEach(cb => {
            activeLocs[cb.dataset.id] = cb.checked;
        });
        localStorage.setItem('echosense_active_locations', JSON.stringify(activeLocs));
        
        const btn = document.querySelector('button[type="submit"]');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<span>Tersimpan!</span>';
        setTimeout(() => btn.innerHTML = originalText, 2000);
    });

    // Uji coba pratinjau suara
    document.getElementById('btn-preview')?.addEventListener('click', function() {
        const langVal = document.getElementById('select-language').value;
        const voiceURI = document.getElementById('select-voice').value;
        const speedVal = parseInt(document.getElementById('speed-range').value) / 100;
        
        let text = langVal === 'id' 
            ? "Ini adalah contoh suara narasi EchoSense. Saat ini kondisi lingkungan sekitar Anda baik dan aman untuk beraktivitas di luar ruangan."
            : "This is a sample EchoSense narration voice. Current environmental conditions around you are good and safe for outdoor activities.";
        
        speakText(text, voiceURI, langVal, speedVal);
    });

    // Uji coba tes bahasa
    document.getElementById('btn-test-language')?.addEventListener('click', function() {
        const langVal = document.getElementById('select-language').value;
        const voiceURI = document.getElementById('select-voice').value;
        const speedVal = parseInt(document.getElementById('speed-range').value) / 100;
        
        let text = langVal === 'id'
            ? "Halo, saya EchoSense, asisten suara Anda."
            : "Hello, I am EchoSense, your voice assistant.";
        
        this.classList.add('bg-ocean', 'text-white');
        this.classList.remove('bg-trackbg', 'text-navy');
        const resetBtn = () => {
            this.classList.remove('bg-ocean', 'text-white');
            this.classList.add('bg-trackbg', 'text-navy');
        };
        
        speakText(text, voiceURI, langVal, speedVal, resetBtn);
    });

    function speakText(text, voiceURI, lang, rate, onEnd) {
        if (voiceURI && voiceURI.startsWith('elevenlabs:')) {
            const elVoiceId = voiceURI.replace('elevenlabs:', '');
            fetch('/api/tts', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ text: text, voice_id: elVoiceId })
            }).then(async res => {
                if (!res.ok) {
                    const err = await res.json();
                    throw new Error(err.error || "ElevenLabs API Error");
                }
                return res.blob();
            }).then(blob => {
                const url = URL.createObjectURL(blob);
                const audio = new Audio(url);
                audio.playbackRate = rate || 1.0;
                if (onEnd) audio.onended = onEnd;
                audio.play();
            }).catch(e => {
                console.error(e);
                alert("ElevenLabs API Error: " + e.message);
                if (onEnd) onEnd();
            });
        } else {
            const utterance = new SpeechSynthesisUtterance(text);
            utterance.lang = lang === 'id' ? 'id-ID' : 'en-US';
            utterance.rate = rate || 1.0;
            
            if (voiceURI && voiceURI !== 'default') {
                const actualUri = voiceURI.replace('local:', '');
                const voices = window.speechSynthesis.getVoices();
                const voice = voices.find(v => v.voiceURI === actualUri);
                if (voice) utterance.voice = voice;
            }
            
            if (onEnd) utterance.onend = onEnd;
            window.speechSynthesis.speak(utterance);
        }
    }

    // Setel ulang preferensi ke default
    document.getElementById('btn-reset')?.addEventListener('click', function() {
      localStorage.setItem('echosense_prefs', JSON.stringify(defaultPrefs));
      prefs = {...defaultPrefs};
      initSettings();
    });

    function loadDynamicLocations() {
        const locs = JSON.parse(localStorage.getItem('echosense_locations')) || [];
        const container = document.getElementById('locations-list-container');
        const countSpan = document.getElementById('locations-count');
        const activeLocs = JSON.parse(localStorage.getItem('echosense_active_locations')) || {};
        
        if (!container || !countSpan) return;
        
        let activeCount = 0;
        let html = '';
        
        locs.forEach((loc, idx) => {
            const shortName = loc.name.split(',')[0];
            const isChecked = activeLocs[loc.id] !== false;
            if (isChecked) activeCount++;
            
            const badgeClass = isChecked ? 'text-ocean bg-white border-ocean/30' : 'text-muted bg-trackbg border-bordercol';
            const badgeText = isChecked ? 'Aktif' : 'Tersimpan';
            const wrapperClass = isChecked ? 'border-ocean bg-page text-navy' : 'border-bordercol bg-white text-muted opacity-75';
            
            html += `
            <label class="flex items-center space-x-2 px-4 py-2 rounded-xl border-2 ${wrapperClass} text-sm font-semibold cursor-pointer hover:bg-sky-50 transition" onclick="setTimeout(updateLocationCount, 50)">
            <input ${isChecked ? 'checked' : ''} data-id="${loc.id}" class="loc-checkbox h-5 w-5 rounded text-ocean border-slatecol focus:ring-ocean" type="checkbox">
            <span class="">${shortName}</span>
            <span class="loc-badge text-xs font-bold px-2 py-0.5 rounded-md border ${badgeClass}">${badgeText}</span>
            </label>`;
        });
        
        countSpan.textContent = `${activeCount} dari ${locs.length} aktif`;
        
        if (locs.length === 0) {
            html = '<p class="text-xs text-muted">Belum ada lokasi tersimpan. Buka Dashboard untuk mendeteksi lokasi.</p>';
        }
        
        container.innerHTML = html;
    }

    function updateLocationCount() {
        const checkboxes = document.querySelectorAll('.loc-checkbox');
        let activeCount = 0;
        checkboxes.forEach(cb => {
            const badge = cb.parentElement.querySelector('.loc-badge');
            if (cb.checked) {
                activeCount++;
                badge.className = 'loc-badge text-xs font-bold px-2 py-0.5 rounded-md border text-ocean bg-white border-ocean/30';
                badge.textContent = 'Aktif';
                cb.parentElement.className = 'flex items-center space-x-2 px-4 py-2 rounded-xl border-2 border-ocean bg-page text-navy text-sm font-semibold cursor-pointer hover:bg-sky-50 transition';
            } else {
                badge.className = 'loc-badge text-xs font-bold px-2 py-0.5 rounded-md border text-muted bg-trackbg border-bordercol';
                badge.textContent = 'Tersimpan';
                cb.parentElement.className = 'flex items-center space-x-2 px-4 py-2 rounded-xl border-2 border-bordercol bg-white text-muted opacity-75 text-sm font-semibold cursor-pointer hover:bg-sky-50 transition';
            }
        });
        const countSpan = document.getElementById('locations-count');
        if (countSpan) countSpan.textContent = `${activeCount} dari ${checkboxes.length} aktif`;
    }
    
    // Muat lokasi dinamis setelah DOM siap
    document.addEventListener('DOMContentLoaded', loadDynamicLocations);
  </script>
</body></html>