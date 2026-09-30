<!DOCTYPE html><html lang="id"><head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<title>EchoSense - Pengaturan Preferensi Audio &amp; Peringatan</title>
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
        Personalisasi tempo, pitch, intensitas, instrumen, bahasa narasi, mode pemutaran, dan ambang batas peringatan sonifikasi data iklim real-time.
      </p>
</section>
<!-- Two-Column Preferences Grid Form -->
<form class="space-y-8" id="preferences-form" onsubmit="event.preventDefault();">
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">
<!-- BEGIN: LeftPanel - Sonifikasi & narasi -->
<section aria-labelledby="section-sonifikasi" class="bg-white border border-bordercol rounded-[20px] p-7 shadow-panel space-y-7">
<div class="border-b border-bordercol pb-4 flex items-center justify-between">
<h2 class="text-lg font-bold text-deep flex items-center gap-2.5" id="section-sonifikasi">
<span aria-hidden="true" class="w-2.5 h-2.5 rounded-full bg-ocean"></span>
              Sonifikasi &amp; narasi
            </h2>
<span class="text-xs font-semibold text-muted bg-trackbg px-3 py-1 rounded-full border border-bordercol">
              Sintesis audio
            </span>
</div>
<!-- Parameter Sliders -->
<div class="space-y-6">
<!-- Tempo Slider -->
<div class="space-y-2">
<div class="flex items-center justify-between text-sm">
<label class="text-deep font-semibold" for="tempo-range">
                  Tempo sonifikasi
                </label>
<div class="flex items-center gap-1.5">
<span class="text-ocean font-bold" id="tempo-val">60%</span>
<span class="text-muted text-xs">(120 BPM)</span>
</div>
</div>
<div class="relative flex items-center">
<input aria-label="Atur tempo sonifikasi dalam persen" aria-valuemax="100" aria-valuemin="0" aria-valuenow="60" class="w-full h-2 rounded-lg cursor-pointer bg-trackbg" id="tempo-range" max="100" min="0" oninput="updateSliderTrack(this, 'tempo-val', '%', 120)" style="background: linear-gradient(90deg, #0675A3 0%, #14C5D9 60%, #E6F2F7 60%, #E6F2F7 100%);" type="range" value="60">
</div>
</div>
<!-- Pitch Slider -->
<div class="space-y-2">
<div class="flex items-center justify-between text-sm">
<label class="text-deep font-semibold" for="pitch-range">
                  Pitch / nada dasar
                </label>
<div class="flex items-center gap-1.5">
<span class="text-ocean font-bold" id="pitch-val">45%</span>
<span class="text-muted text-xs">(440 Hz)</span>
</div>
</div>
<div class="relative flex items-center">
<input aria-label="Atur pitch atau nada dasar dalam persen" aria-valuemax="100" aria-valuemin="0" aria-valuenow="45" class="w-full h-2 rounded-lg cursor-pointer bg-trackbg" id="pitch-range" max="100" min="0" oninput="updateSliderTrack(this, 'pitch-val', '%', 440)" style="background: linear-gradient(90deg, #0675A3 0%, #14C5D9 45%, #E6F2F7 45%, #E6F2F7 100%);" type="range" value="45">
</div>
</div>
<!-- Intensitas Slider -->
<div class="space-y-2">
<div class="flex items-center justify-between text-sm">
<label class="text-deep font-semibold" for="intensitas-range">
                  Intensitas suara
                </label>
<div class="flex items-center gap-1.5">
<span class="text-ocean font-bold" id="intensitas-val">70%</span>
<span class="text-muted text-xs">(-3.5 dB)</span>
</div>
</div>
<div class="relative flex items-center">
<input aria-label="Atur intensitas suara dalam persen" aria-valuemax="100" aria-valuemin="0" aria-valuenow="70" class="w-full h-2 rounded-lg cursor-pointer bg-trackbg" id="intensitas-range" max="100" min="0" oninput="updateSliderTrack(this, 'intensitas-val', '%', -3.5)" style="background: linear-gradient(90deg, #0675A3 0%, #14C5D9 70%, #E6F2F7 70%, #E6F2F7 100%);" type="range" value="70">
</div>
</div>
</div>
<!-- Dropdowns Section -->
<div class="pt-4 border-t border-bordercol space-y-5">
<!-- Instrumen Dropdown -->
<div class="space-y-1.5">
<label class="block text-sm font-semibold text-deep" for="select-instrument">
                Instrumen
              </label>
<div class="flex space-x-2">
<div class="relative flex-1">
<select class="w-full h-12 rounded-[10px] bg-white border-[1.5px] border-slatecol text-deep px-4 pr-10 text-sm focus:border-ocean focus:ring-0 transition" id="select-instrument" name="instrument">
<option value="triangle">Piano akustik (Triangle)</option>
<option value="sine">Sintesis halus (Sine)</option>
<option value="square">Nada harmonis (Square)</option>
</select>
</div>
<!-- Tone Test Button -->
<button id="btn-test-instrument" aria-label="Dengarkan contoh instrumen terpilih" class="h-12 px-4 bg-trackbg hover:bg-slate-200 border-[1.5px] border-slatecol text-navy font-semibold rounded-[10px] flex items-center justify-center transition" title="Dengarkan contoh instrumen" type="button">
<svg aria-hidden="true" class="w-4 h-4 text-ocean" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" viewBox="0 0 24 24">
<polygon points="5 3 19 12 5 21 5 3"></polygon>
</svg>
</button>
</div>
</div>
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
</div>
<!-- Mode Pemutaran Radio Fieldset -->
<fieldset class="pt-4 border-t border-bordercol space-y-3">
<legend class="block text-sm font-semibold text-deep mb-2">
              Mode pemutaran
            </legend>
<div class="space-y-3">
<label class="flex items-start space-x-3.5 cursor-pointer p-3 rounded-xl border border-transparent hover:border-bordercol hover:bg-page transition">
<input checked="" class="mt-0.5 h-6 w-6 text-ocean border-slatecol focus:ring-ocean rounded-full" name="playback_mode" type="radio" value="bersamaan">
<div class="text-sm">
<span class="font-bold text-deep block">Bersamaan</span>
<span class="text-muted leading-relaxed">Sonifikasi nada latar berbunyi serentak bersama panduan narasi suara AI.</span>
</div>
</label>
<label class="flex items-start space-x-3.5 cursor-pointer p-3 rounded-xl border border-transparent hover:border-bordercol hover:bg-page transition">
<input class="mt-0.5 h-6 w-6 text-ocean border-slatecol focus:ring-ocean rounded-full" name="playback_mode" type="radio" value="berurutan">
<div class="text-sm">
<span class="font-bold text-deep block">Berurutan</span>
<span class="text-muted leading-relaxed">Sonifikasi frekuensi diputar terlebih dahulu, disusul ringkasan suara.</span>
</div>
</label>
</div>
</fieldset>
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
              Kanal peringatan
            </span>
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
<span class="text-sm font-semibold text-deep">Polusi udara tinggi (AQI di atas 150)</span>
</div>
<span id="badge-aqi" class="text-xs font-medium px-2.5 py-1 rounded-md bg-trackbg text-muted border border-bordercol">Nonaktif</span>
</label>
<label class="flex items-center justify-between p-3.5 rounded-xl border border-bordercol hover:border-slatecol bg-white transition cursor-pointer">
<div class="flex items-center space-x-3">
<input class="h-6 w-6 rounded text-ocean border-slatecol focus:ring-ocean alert-cb" name="alert_weather" type="checkbox" onchange="updateAlertBadge(this, 'badge-weather', 'Kritis', 'bg-crimson text-white')">
<span class="text-sm font-semibold text-deep">Cuaca berbahaya &amp; potensi badai</span>
</div>
<span id="badge-weather" class="text-xs font-medium px-2.5 py-1 rounded-md bg-trackbg text-muted border border-bordercol">Nonaktif</span>
</label>
<label class="flex items-center justify-between p-3.5 rounded-xl border border-bordercol hover:border-slatecol bg-white transition cursor-pointer">
<div class="flex items-center space-x-3">
<input class="h-6 w-6 rounded text-ocean border-slatecol focus:ring-ocean alert-cb" name="alert_traffic" type="checkbox" onchange="updateAlertBadge(this, 'badge-traffic', 'Aktif', 'bg-ocean text-white')">
<span class="text-sm font-medium text-muted">Lalu lintas berat / kemacetan total</span>
</div>
<span id="badge-traffic" class="text-xs font-medium px-2.5 py-1 rounded-md bg-trackbg text-muted border border-bordercol">Nonaktif</span>
</label>
</div>
</fieldset>
<!-- Jenis Notifikasi Section -->
<fieldset class="pt-4 border-t border-bordercol space-y-3">
<legend class="block text-sm font-semibold text-deep mb-2">
              Jenis notifikasi
            </legend>
<div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
<!-- Audio Output -->
<label class="flex items-center space-x-3 p-3.5 rounded-xl border-2 border-ocean/40 bg-page cursor-pointer hover:border-ocean transition">
<input checked="" class="h-6 w-6 rounded text-ocean border-slatecol focus:ring-ocean" name="notify_audio" type="checkbox">
<div>
<span class="text-sm font-bold text-deep block">Audio</span>
<span class="text-xs text-muted">Chime &amp; voice prompt</span>
</div>
</label>
<!-- Haptic Output -->
<label class="flex items-center space-x-3 p-3.5 rounded-xl border-2 border-ocean/40 bg-page cursor-pointer hover:border-ocean transition">
<input checked="" class="h-6 w-6 rounded text-ocean border-slatecol focus:ring-ocean" name="notify_haptic" type="checkbox">
<div>
<span class="text-sm font-bold text-deep block">Haptic</span>
<span class="text-xs text-muted">Pola getaran taktil layar</span>
</div>
</label>
</div>
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
<!-- JavaScript for Interactive Controls & Dynamic Slider Gradients -->
<script data-purpose="slider-interaction">
    // Default Preferences
    const defaultPrefs = {
        tempo: 60,
        pitch: 45,
        volume: 70,
        instrument: 'sine',
        language: 'id-ID',
        playMode: 'bersamaan'
    };

    // Load preferences
    let prefs = JSON.parse(localStorage.getItem('echosense_prefs')) || defaultPrefs;

    function initSettings() {
        const tempo = document.getElementById('tempo-range');
        const pitch = document.getElementById('pitch-range');
        const intensitas = document.getElementById('intensitas-range');
        const instrument = document.getElementById('select-instrument');
        const language = document.getElementById('select-language');
        
        if (tempo) {
            tempo.value = prefs.tempo;
            updateSliderTrack(tempo, 'tempo-val', '%');
        }
        if (pitch) {
            pitch.value = prefs.pitch;
            updateSliderTrack(pitch, 'pitch-val', '%');
        }
        if (intensitas) {
            intensitas.value = prefs.volume;
            updateSliderTrack(intensitas, 'intensitas-val', '%');
        }
        if (instrument) instrument.value = prefs.instrument || 'triangle';
        if (language) language.value = prefs.language || 'id';

        const playModeRadio = document.querySelector(`input[name="playback_mode"][value="${prefs.playMode}"]`);
        if (playModeRadio) playModeRadio.checked = true;
        
        // Load Alerts
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
      slider.style.background = `linear-gradient(90deg, #0675A3 0%, #14C5D9 ${val}%, #E6F2F7 ${val}%, #E6F2F7 100%)`;
    }

    // Initialize on load
    document.addEventListener('DOMContentLoaded', initSettings);

    // Form Submit (Save Settings)
    document.querySelector('form')?.addEventListener('submit', function(e) {
        e.preventDefault();
        prefs.tempo = parseInt(document.getElementById('tempo-range').value);
        prefs.pitch = parseInt(document.getElementById('pitch-range').value);
        prefs.volume = parseInt(document.getElementById('intensitas-range').value);
        prefs.instrument = document.getElementById('select-instrument').value;
        prefs.language = document.getElementById('select-language').value;
        
        const modeRadio = document.querySelector('input[name="playback_mode"]:checked');
        if (modeRadio) prefs.playMode = modeRadio.value;
        
        localStorage.setItem('echosense_prefs', JSON.stringify(prefs));
        
        // Save Alerts
        const alert_aqi = document.querySelector('input[name="alert_aqi"]').checked;
        const alert_weather = document.querySelector('input[name="alert_weather"]').checked;
        const alert_traffic = document.querySelector('input[name="alert_traffic"]').checked;
        localStorage.setItem('echosense_alerts', JSON.stringify({ alert_aqi, alert_weather, alert_traffic }));
        
        // Save Active Locations
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

    function playTestTone(buttonEl, testVoice = false) {
      const originalHTML = buttonEl.innerHTML;
      buttonEl.classList.add('bg-navy/10');
      
      const ctx = new (window.AudioContext || window.webkitAudioContext)();
      const osc = ctx.createOscillator();
      const gain = ctx.createGain();
      
      const pitchVal = document.getElementById('pitch-range').value;
      const volVal = document.getElementById('intensitas-range').value;
      const instrVal = document.getElementById('select-instrument').value;
      const langVal = document.getElementById('select-language').value;
      const modeVal = document.querySelector('input[name="playback_mode"]:checked')?.value || 'bersamaan';
      
      const maxVol = volVal / 100;
      osc.type = instrVal; // sine, square, triangle
      osc.frequency.value = 220 + (pitchVal * 2); // Map 0-100 to 220-420Hz
      
      const now = ctx.currentTime;
      // ADSR
      if (instrVal === 'sine') {
          gain.gain.setValueAtTime(0, now);
          gain.gain.linearRampToValueAtTime(maxVol, now + 0.3);
          gain.gain.exponentialRampToValueAtTime(0.001, now + 0.8);
          osc.start(now);
          osc.stop(now + 0.8);
      } else if (instrVal === 'square') {
          gain.gain.setValueAtTime(0, now);
          gain.gain.linearRampToValueAtTime(maxVol * 0.7, now + 0.1);
          gain.gain.exponentialRampToValueAtTime(maxVol * 0.2, now + 0.3);
          gain.gain.exponentialRampToValueAtTime(0.001, now + 0.6);
          osc.start(now);
          osc.stop(now + 0.6);
      } else {
          gain.gain.setValueAtTime(0, now);
          gain.gain.linearRampToValueAtTime(maxVol, now + 0.02);
          gain.gain.exponentialRampToValueAtTime(0.001, now + 0.5);
          osc.start(now);
          osc.stop(now + 0.5);
      }
      
      osc.connect(gain);
      gain.connect(ctx.destination);
      
      // Voice test logic if the main button is clicked
      if (testVoice) {
          const text = langVal === 'id' ? "Pratinjau suara EchoSense." : "EchoSense voice preview.";
          const utterance = new SpeechSynthesisUtterance(text);
          utterance.lang = langVal === 'id' ? 'id-ID' : 'en-US';
          
          if (modeVal === 'berurutan') {
              setTimeout(() => {
                  window.speechSynthesis.speak(utterance);
              }, 600); // Play voice after tone
          } else {
              window.speechSynthesis.speak(utterance);
          }
      }
      
      setTimeout(() => {
        buttonEl.innerHTML = originalHTML;
        buttonEl.classList.remove('bg-navy/10');
      }, 1500);
    }

    // Audio preview trigger (Main big button)
    document.getElementById('btn-preview')?.addEventListener('click', function() {
        playTestTone(this, true); // True means test voice as well
    });

    // Audio preview trigger (Instrument small button)
    document.getElementById('btn-test-instrument')?.addEventListener('click', function() {
        playTestTone(this, false); // False means only tone
    });

    // Language Test trigger
    document.getElementById('btn-test-language')?.addEventListener('click', function() {
        const langVal = document.getElementById('select-language').value;
        let text = "Ini adalah contoh suara dalam Bahasa Indonesia.";
        if (langVal === 'en') {
            text = "This is a sample voice in English.";
        }
        
        const utterance = new SpeechSynthesisUtterance(text);
        utterance.lang = langVal === 'id' ? 'id-ID' : 'en-US';
        
        // Change button color to active
        this.classList.add('bg-ocean', 'text-white');
        this.classList.remove('bg-trackbg', 'text-navy');
        
        utterance.onend = () => {
            this.classList.remove('bg-ocean', 'text-white');
            this.classList.add('bg-trackbg', 'text-navy');
        };
        
        window.speechSynthesis.speak(utterance);
    });

    // Reset settings trigger
    document.getElementById('btn-reset')?.addEventListener('click', function() {
      localStorage.setItem('echosense_prefs', JSON.stringify(defaultPrefs));
      prefs = defaultPrefs;
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
            const isChecked = activeLocs[loc.id] !== false; // Default true if not set
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
            html = '<p class="text-xs text-muted">Belum ada lokasi tersimpan.</p>';
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
    
    // Call loadDynamicLocations on DOMContentLoaded
    document.addEventListener('DOMContentLoaded', loadDynamicLocations);
  </script>










</body></html>