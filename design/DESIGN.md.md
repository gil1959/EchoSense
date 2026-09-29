# Design Document: EchoSense

Sonifikasi Lingkungan Aksesibel. Dengarkan kondisi lingkungan Anda.

## Overview

EchoSense adalah design system untuk platform web berbasis AI yang mengubah data lingkungan real-time (kualitas udara, cuaca, lalu lintas) menjadi soundscape adaptif dan voice briefing bagi penyandang tunanetra. Warna dominan diambil langsung dari logo: navy laut yang dalam sebagai fondasi, biru laut sebagai warna interaktif, cyan dan teal aqua sebagai aliran gelombang, dan oranye sunrise yang hangat sebagai aksen pada momen penting, sama seperti batang gelombang suara pada logo. Antarmuka berjalan di atas light mode bernuansa putih kebiruan yang bersih, dengan permukaan navy untuk navbar, hero, dan footer. Tipografi memakai satu keluarga sans-serif yang dirancang untuk keterbacaan pengguna low-vision. Gerakan dibuat seminimal mungkin dan hanya menjelaskan perubahan, kecuali satu momen khas: visualizer gelombang yang bergerak sinkron dengan audio. Tidak ada emoji dan tidak ada dash dekoratif. Semua karakter visual dibangun dari warna, tipografi, bentuk gelombang, dan gerakan yang bermakna.

**Framework:** Next.js 14 (React) dengan TypeScript, state management Redux Toolkit. **Audio:** Tone.js dan Web Audio API, TTS untuk voice briefing. **Sumber data:** BMKG dan layanan lalu lintas (semua dari proposal).

**Target pengguna:** Penyandang tunanetra (utama, pengguna screen reader dan keyboard), pengguna low-vision, pengguna gangguan pendengaran (lewat visual cues sinkron), pendamping dan keluarga, serta institusi yang membutuhkan data lingkungan yang ramah disabilitas.

**Dasar dokumen:** proposal EchoSense dan tiga wireframe low-fidelity (Beranda, Dashboard, Pengaturan). Isi yang tidak ada di keduanya ditandai **[Usulan]**.

## Prinsip Utama

1. Navy adalah warna dominan, biru laut adalah aksen interaktif utama, oranye sunrise hadir hanya di momen penting (aksi utama di atas latar gelap, batang visualizer, penanda aktif). Ketiganya membentuk hierarki visual yang jelas.
2. Audio adalah antarmuka utama. Tampilan visual mendampingi dan tidak pernah menjadi satu-satunya cara memperoleh informasi.
3. Semua fungsi bisa dijalankan dengan keyboard. Pintasan global: `Spasi` Play/Pause, `H` Bantuan, `S` Pengaturan.
4. Light mode adalah tampilan bawaan. Mode kontras tinggi tersedia sebagai pilihan pengguna **[Usulan]** (lihat bagian Accessibility).
5. Satu aksi utama per area. Hanya satu tombol solid dominan pada satu blok (Mulai Mendengarkan, Play / Pause, Simpan pengaturan).
6. Informasi tidak boleh bergantung pada warna atau bunyi saja. Setiap status punya teks dan ikon.
7. Warna cyan, teal, dan oranye dari logo tidak dipakai sebagai warna teks di atas latar terang karena kontrasnya tidak cukup. Di latar terang, teks memakai navy atau biru laut.
8. Gerakan tidak pernah otomatis mengganggu. Tidak ada autoplay suara, tidak ada animasi berulang yang tidak terkait audio, dan `prefers-reduced-motion` selalu dihormati.
9. Notifikasi dan konfirmasi memakai toast atau modal custom yang dapat diakses, bukan `alert()` bawaan browser.
10. Tidak ada emoji dan tidak ada dash dekoratif pada teks UI atau copywriting. Dash hanya untuk kebutuhan teknis.

## Colors

### Warna dari Logo

Nilai di bawah diambil dari sampel piksel logo, lalu disesuaikan bila perlu agar memenuhi kontras WCAG.

| Warna logo | Sampel logo | Token turunan | Catatan |
| --- | --- | --- | --- |
| Navy tua (lingkaran luar kiri) | `#033067` | Primary | Dipakai apa adanya |
| Biru gelombang | `#02437B`, `#136193` | Surface Accent, Ocean | Panel sekunder, hover pada elemen gelap |
| Biru laut (bagian bawah batang) | `#0675A3` | Secondary | Dipakai apa adanya |
| Cyan (ujung kanan atas) | `#14C5D9` | Aqua | Hanya di latar gelap dan dekorasi |
| Teal aqua | `#21B9BE`, `#24A6BF` | Lagoon | Dekorasi, badge status Baik |
| Oranye batang | `#FEB161` | Tertiary (Sunrise) | Dipakai apa adanya |
| Koral (titik ujung) | `#FD7659` | Pulse | Aksen kecil dan status peringatan |

### Brand Palette

| Token | Hex | Role |
| --- | --- | --- |
| Primary | `#033067` | Navy laut, latar navbar, hero, footer, teks judul di latar terang |
| Secondary | `#0675A3` | Biru laut, tombol utama, tautan, indikator fokus, isi slider |
| Tertiary | `#FEB161` | Sunrise, aksi utama di latar gelap, penanda item aktif, batang visualizer |
| Aqua | `#14C5D9` | Aksen di latar gelap (garis, ikon, teks kecil di navy), ujung gradient |
| Pulse | `#FD7659` | Koral, titik aksen, ujung gradient sunrise, status Tidak sehat |

### Surface Palette

| Token | Hex | Role |
| --- | --- | --- |
| Background | `#F4F9FB` | Latar halaman utama, putih kebiruan |
| Surface | `#FFFFFF` | Latar kartu, panel, blok konten |
| Surface Alt | `#E6F2F7` | Latar section alternatif, trek slider, skeleton |
| Surface Dark | `#033067` | Latar navbar, footer, hero, CTA section |
| Surface Accent | `#02437B` | Hover pada elemen gelap, panel sekunder di latar gelap |

### Content Palette

| Token | Hex | Kontras | Role |
| --- | --- | --- | --- |
| Text Primary | `#0A1F33` | 16.7:1 di Surface | Teks utama pada latar terang |
| Text Secondary | `#42586A` | 7.4:1 di Surface | Deskripsi, label, meta info |
| Text Tertiary | `#5F7383` | 4.9:1 di Surface, 4.6:1 di Background | Placeholder, teks pendukung (tidak dipakai untuk teks penting) |
| Text On Dark | `#F4F9FB` | 12.2:1 di Primary | Teks utama pada latar navy |
| Text On Dark Secondary | `#BFD9E8` | 8.8:1 di Primary, 6.8:1 di Surface Accent | Teks sekunder pada latar gelap |
| Link | `#0675A3` | 5.2:1 di Surface | Tautan di latar terang, selalu diberi garis bawah |

### Air Quality Palette (Kualitas Udara)

Badge status kualitas udara dipakai konsisten di Dashboard, Riwayat, dan Peringatan. Setiap badge selalu memuat label teks dan ikon berbeda bentuk. Rentang AQI di kolom kanan adalah **[Usulan]** dan perlu disamakan dengan sumber data yang dipakai.

| Status | Background | Text | Kontras | AQI |
| --- | --- | --- | --- | --- |
| Baik | `#21B9BE` | `#033067` | 5.4:1 | 0 sampai 50 |
| Sedang | `#FEB161` | `#033067` | 7.2:1 | 51 sampai 100 |
| Tidak sehat | `#FD7659` | `#033067` | 4.9:1 | 101 sampai 150 |
| Sangat tidak sehat | `#C4442A` | `#FFFFFF` | 5.0:1 | 151 sampai 200 |
| Berbahaya | `#6B1F8A` | `#FFFFFF` | 9.6:1 | 201 ke atas |

Ambang peringatan bawaan pada wireframe adalah AQI di atas 150, yaitu status Sangat tidak sehat.

### Border Palette

| Token | Hex | Usage |
| --- | --- | --- |
| Border Subtle | `#D6E6ED` | Border kartu pasif, pemisah ringan |
| Border Medium | `#6B8899` | Border input, checkbox, trek slider (kontras 3.5:1 terhadap Surface) |
| Border Strong | `#033067` | Border tombol sekunder, panel utama |
| Focus Ring (terang) | `#0675A3` | Cincin fokus di latar terang |
| Focus Ring (gelap) | `#FEB161` | Cincin fokus di latar navy |

### Semantic Colors

| Token | Hex | Usage |
| --- | --- | --- |
| Success | `#0B7A5E` | Berhasil disimpan, lokasi terdeteksi (teks putih 5.3:1) |
| Warning | `#FEB161` | Perhatian, data kedaluwarsa (teks navy) |
| Error | `#C4442A` | Gagal, galat form, peringatan kritis (teks putih 5.0:1) |
| Info | `#0675A3` | Informasi, petunjuk |

### Status Lalu Lintas dan Cuaca **[Usulan]**

| Status | Style |
| --- | --- |
| Lancar | Badge Baik (`#21B9BE` teks navy) |
| Padat | Badge Sedang (`#FEB161` teks navy) |
| Macet | Badge Sangat tidak sehat (`#C4442A` teks putih) |
| Cuaca berbahaya | Badge Berbahaya (`#6B1F8A` teks putih) |

## Typography

### Font Stack

| Role | Font |
| --- | --- |
| Semua teks (heading dan body) | Atkinson Hyperlegible Next, 'Segoe UI', system-ui, -apple-system, Helvetica, sans-serif |
| Angka data | Font yang sama dengan `font-variant-numeric: tabular-nums` |

Atkinson Hyperlegible dipilih karena dirancang untuk keterbacaan pengguna low-vision, dengan bentuk huruf yang tidak mudah tertukar (I, l, 1, O, 0). Satu keluarga dipakai di semua tempat agar konsisten dan ringan dimuat. Ini menggantikan monospace pada wireframe, yang bersifat placeholder low-fidelity **[Usulan]**.

### Type Scale

| Level | Size | Weight | Line Height | Letter Spacing | Usage |
| --- | --- | --- | --- | --- | --- |
| Display | 48px | 700 | 1.15 | -0.01em | Headline hero |
| Headline | 36px | 700 | 1.2 | 0 | Judul halaman, H1 |
| Subhead | 28px | 700 | 1.25 | 0 | Judul section, H2 |
| Title | 22px | 700 | 1.3 | 0 | Judul kartu dan panel, H3 |
| Data Value | 28px | 700 | 1.2 | 0 | Nilai utama kartu data (AQI 80, 28°C) |
| Body Large | 18px | 400 | 1.6 | 0 | Lead paragraph, narasi |
| Body | 16px | 400 | 1.65 | 0 | Teks default |
| Body Small | 14px | 400 | 1.55 | 0 | Caption, teks pendukung |
| Label | 14px | 700 | 1.4 | 0 | Label form, label kartu |

Aturan: tidak ada teks di bawah 14px, sentence case di semua tempat (tidak ada huruf kapital semua, karena sebagian screen reader mengeja huruf kapital satu per satu), panjang baris maksimal 70 karakter, ukuran memakai `rem` dan harus tetap terbaca pada pembesaran 200%.

## Spacing

| Property | Value |
| --- | --- |
| Base unit | 8px |
| Scale | 4, 8, 16, 24, 32, 48, 64, 96 |
| Component padding small | 8px |
| Component padding medium | 16px |
| Component padding large | 32px |
| Section spacing mobile | 48px |
| Section spacing desktop | 96px |
| Margin sisi konten | 16px mobile, 48px desktop |
| Lebar konten maksimum | 1200px |
| Target klik dan sentuh minimum | 44 x 44px |

## Border Radius

Bentuk membulat mengikuti lengkung gelombang pada logo.

| Token | Value | Usage |
| --- | --- | --- |
| Small | 6px | Badge, chip |
| Medium | 10px | Button, input, select |
| Large | 14px | Kartu kecil, dropdown |
| XL | 20px | Kartu besar, panel, modal |
| Full | 9999px | Trek slider, batang visualizer, avatar, tombol ikon bulat |

## Shadows

**Filosofi:** bayangan bernuansa navy, lembut, seperti cahaya yang memantul di air. Tidak pernah hitam pekat.

| Level | CSS Value | Usage |
| --- | --- | --- |
| Subtle | `0 1px 3px rgba(3, 48, 103, 0.08)` | Kartu default, input |
| Medium | `0 4px 14px rgba(3, 48, 103, 0.12)` | Kartu hover, dropdown |
| Large | `0 12px 36px rgba(3, 48, 103, 0.16)` | Popover |
| Overlay | `0 24px 64px rgba(3, 48, 103, 0.40)` | Modal, dialog penting |

**Focus Ring (latar terang):** `0 0 0 2px #F4F9FB, 0 0 0 5px #0675A3`, cincin 3px biru laut.

**Focus Ring (latar gelap):** `0 0 0 2px #033067, 0 0 0 5px #FEB161`, cincin 3px sunrise.

**Penanda item aktif:** garis bawah 3px `#0675A3` di latar terang, `#FEB161` di latar navy. Item aktif juga memakai `aria-current`.

## Gradient Specs

| Nama | CSS Value | Usage |
| --- | --- | --- |
| Deep Wave | `linear-gradient(180deg, #033067 0%, #02437B 100%)` | Navbar, footer, latar hero |
| Ocean Flow | `linear-gradient(135deg, #033067 0%, #0675A3 55%, #14C5D9 100%)` | Ilustrasi hero, panel aksen besar |
| Aqua Tide | `linear-gradient(90deg, #0675A3 0%, #14C5D9 100%)` | Isi slider Volume dan Progres |
| Sunrise Pulse | `linear-gradient(180deg, #FEB161 0%, #FD7659 100%)` | Batang visualizer, garis aksen |
| Mist | `linear-gradient(180deg, #F4F9FB 0%, #E6F2F7 100%)` | Latar section alternatif |

**Aturan:** gradient hanya boleh dipakai dari tabel ini dan tidak boleh dipakai pada teks. Teks di atas Ocean Flow hanya boleh berada di zona navy sampai biru laut (setengah kiri), karena ujung cyan tidak cukup kontras dengan teks putih. Isi slider bersifat dekoratif, nilainya tetap ditampilkan sebagai teks.

## Motion & Animasi

Karakter gerakan EchoSense adalah **tenang, singkat, dan bermakna**. Gerakan hanya dipakai untuk menunjukkan apa yang berubah. Berbeda dari situs biasa, tidak ada animasi dekoratif karena pengguna utama bergantung pada suara dan pembaca layar.

| Jenis | Durasi | Easing | Catatan |
| --- | --- | --- | --- |
| Hover (warna, border) | 150ms | ease-out | Perubahan warna tombol, kartu, tautan |
| Klik/Active | 100ms | ease-in | Warna lebih gelap, tanpa perubahan ukuran |
| Buka/tutup kartu data | 200ms | ease-in-out | Tinggi berubah halus, `aria-expanded` ikut berubah |
| Modal masuk | 200ms | ease-out | Fade dan scale 0.98 ke 1, backdrop fade |
| Modal keluar | 150ms | ease-in | Fade |
| Toast masuk | 200ms | ease-out | Fade dan slide pendek dari atas |
| Loading spinner | 800ms | linear, infinite | Rotasi konsisten di semua tempat |
| Loading skeleton | 1200ms | ease-in-out, infinite alternate | Shimmer Surface Alt ke Background |
| Visualizer gelombang | Mengikuti amplitudo audio | linear | Hanya bergerak saat audio diputar, lihat Components |

Aturan wajib:

- Tidak ada scroll reveal, count-up angka, animasi masuk bertahap, atau elemen berayun pada halaman.
- Semua gerakan dinonaktifkan atau dijadikan perubahan instan saat `prefers-reduced-motion: reduce`. Visualizer menjadi batang statis.
- Setiap proses async wajib menampilkan loading state yang juga diumumkan lewat live region, tidak boleh blank atau freeze.
- Tidak ada konten yang berkedip lebih dari tiga kali per detik.

## Components

### Navbar

- Background: `#033067` (Surface Dark), teks `#F4F9FB`
- Tinggi: 64px mobile, 72px desktop, sticky top
- Item aktif: background `#02437B`, garis bawah 3px `#FEB161`, `aria-current="page"`
- Hover: background `#02437B`
- Menu publik (Beranda): Beranda, Dashboard, Fitur, Bantuan, tombol **Masuk**
- Menu aplikasi (Dashboard dan Pengaturan): Dashboard, Pengaturan, Riwayat, Peringatan, tombol **Akun**
- Tombol Masuk atau Akun: varian Accent (sunrise)
- Mobile: tombol menu (hamburger) dengan label aksesibel "Buka menu", panel turun dari atas 200ms
- Logo: ikon logo dan teks "EchoSense" dengan tagline "Sonifikasi Lingkungan Aksesibel" (Body Small, Text On Dark Secondary)
- Logo di atas navy: garis navy pada logo tidak terlihat di latar `#033067`. Letakkan logo pada chip putih berradius 10px, atau sediakan versi logo putih monokrom **[Usulan]**
- Tautan pertama di halaman: **Lewati ke konten utama**, terlihat saat menerima fokus

### Hero Section

- Latar: gradient `Deep Wave`, tinggi minimum 80vh desktop, mengikuti isi di mobile
- Badge: "Audio-first, WCAG 2.1 AA", border 1px `#14C5D9`, teks `#F4F9FB`, radius Small
- Headline: **Dengarkan Kondisi Lingkungan Anda**, Display 48px, warna `#F4F9FB`, lebar maksimum 12 kolom bagian kiri
- Deskripsi: Body Large, warna `#BFD9E8`. Isi: sonifikasi data udara, cuaca, dan lalu lintas ditambah voice briefing AI 30 sampai 60 detik
- CTA primer **Mulai Mendengarkan**: varian Accent (`#FEB161`, teks `#033067`), radius 10px
- CTA sekunder **Panduan Keyboard**: transparan, border 2px `#F4F9FB`, teks `#F4F9FB`
- Sisi kanan (mobile: di atas judul): ilustrasi gelombang suara bergaya logo, memakai gradient `Ocean Flow` dan batang `Sunrise Pulse`. Dekoratif, `alt=""`
- Tidak ada suara otomatis dan tidak ada animasi masuk

### Section Heading

- Garis aksen: lebar 48px, tinggi 4px, `#0675A3`, radius Full, di atas judul
- Judul: Subhead 28px, warna `#033067`
- Subtitle: Body, warna `#42586A`, lebar maksimum 40rem
- Rata tengah (bawaan) atau rata kiri
- Margin bawah: 32px mobile, 48px desktop

### Buttons

Tinggi minimum semua tombol 44px. Font Atkinson Hyperlegible Next 16px, weight 700, radius 10px.

**Primary**
- Background `#0675A3`, teks `#FFFFFF` (5.2:1), tanpa border
- Padding 14px 28px
- Hover: background `#055F85`, shadow Medium
- Active: background `#033067`
- Fokus: Focus Ring terang
- Loading: spinner putih di tengah, label tetap ada untuk pembaca layar ("Memuat…")

**Accent (Sunrise)**
- Background `#FEB161`, teks `#033067` (7.2:1)
- Hover: background `#FFC488`
- Active: background `#FD7659`
- Dipakai untuk aksi paling penting di latar navy (Mulai Mendengarkan, tombol Masuk atau Akun)

**Secondary**
- Background transparan, teks `#033067`, border `2px solid #033067`
- Hover: background `#033067`, teks `#F4F9FB`
- Varian di latar gelap: teks dan border `#F4F9FB`, hover background `#02437B`

**Ghost**
- Background transparan, teks `#42586A`, tanpa border
- Hover: teks `#033067`, background `#E6F2F7`

**Destructive**
- Background `#C4442A`, teks `#FFFFFF` (5.0:1)
- Hover: background `#A73620`

**Tersier**
- Teks `#0675A3` dengan garis bawah, dipakai untuk aksi tambahan di dalam daftar, contoh: Tambah lokasi

**Sizes:** Small 10px 20px / 14px (tetap 44px tinggi klik), Medium 14px 28px / 16px, Large 18px 36px / 18px

**Play / Pause:** varian Primary, lebar penuh panel, tinggi minimum 56px, menampilkan pintasan di label ("Play / Pause [Spasi]"). Memakai `aria-pressed`, label berubah antara "Putar" dan "Jeda".

**Disabled:** `aria-disabled="true"`, opacity 0.5, disertai teks alasan bila relevan.

### Cards

**Default**
- Background `#FFFFFF`, border `1px solid #D6E6ED`, radius 20px, padding 24px, shadow Subtle
- Hover (bila interaktif): shadow Medium, border `#0675A3`, transisi 150ms

**Feature Card (Beranda, 4 kolom desktop, tumpuk di mobile)**
- Empat kartu: Sonifikasi Real-time, Voice Briefing AI, Lokasi Favorit, Peringatan Dini
- Ikon 48px pada wadah bulat `#E6F2F7`, ikon warna `#0675A3`
- Judul: Title 22px, warna `#033067`. Deskripsi: Body, warna `#42586A`, satu sampai dua kalimat
- Kartu bukan tautan kecuali diarahkan ke bagian Fitur

**Data Card (Dashboard: Kualitas Udara, Cuaca, Lalu Lintas)**
- Struktur: label kategori (Label 14px, `#42586A`), nilai utama (Data Value 28px, `#033067`), detail ringkas (Body Small), badge status, pemicu perluas
- Contoh isi: AQI 80 dengan badge Sedang, PM2.5 35 dan PM10 55; Cerah berawan 28°C, kelembapan 65%, angin 12 km/j; Lancar, kecepatan rata-rata 45 km/j
- Pola disclosure: seluruh judul kartu adalah tombol dengan `aria-expanded` dan `aria-controls`, panel detail terbuka 200ms
- Badge status: warna sesuai Air Quality Palette, selalu dengan ikon dan teks

**Panel (Kontrol Audio, Ringkasan Suara AI, Sonifikasi & Narasi, Peringatan Lingkungan)**
- Background `#FFFFFF`, border `2px solid #033067`, radius 20px, padding 24px
- Judul `h2`, Title 22px, warna `#033067`

### Audio Control Panel

- Baris atas: "Saat ini: Jakarta, ID" dan "Diperbarui: 14:55 WIB" (Body Small, `#42586A`)
- Play / Pause di bawahnya, lalu slider Volume dan Progres
- Tombol **Ulang** dan **Lokasi Berikutnya**: varian Secondary
- Perubahan lokasi atau data diumumkan lewat `aria-live="polite"` tanpa memindahkan fokus

### Waveform Visualizer

Elemen khas EchoSense, mengulang bentuk batang pada logo.

- Tujuh batang vertikal dengan lebar 12px, radius Full, jarak 8px, tinggi relatif 30, 55, 100, 70, 50, 30 persen dan satu titik bulat di ujung (mengikuti logo)
- Warna batang: gradient `Sunrise Pulse`. Di latar terang, letakkan di atas panel navy agar kontras cukup
- Saat audio diputar: tinggi batang mengikuti amplitudo audio
- Saat dijeda atau `prefers-reduced-motion`: batang statis
- `aria-hidden="true"`. Informasi yang sama tersedia sebagai teks pada Ringkasan Suara AI
- Visual cue sinkron ini juga membantu pengguna gangguan pendengaran (proposal: "visual sinkron")

### Ringkasan Suara AI

- Panel dengan transkrip teks narasi yang sedang diputar, panjang setara 30 sampai 60 detik
- Body Large, `#0A1F33`, baris maksimal 70 karakter
- Kalimat yang sedang dibacakan diberi background `#E6F2F7` (bukan hanya warna teks)
- Saat data memuat: skeleton tiga baris

### Sidebar Lokasi

- Judul "Lokasi tersimpan", daftar tombol vertikal: Jakarta (saat ini), Bandung, Surabaya, lalu **Tambah lokasi**
- Item aktif: background `#033067`, teks `#F4F9FB`, `aria-current="true"`
- Item lain: background `#FFFFFF`, border 1px `#6B8899`, hover `#E6F2F7`
- Mobile: berubah menjadi dropdown di atas halaman
- Elemen `aside` dengan label "Lokasi tersimpan"

### Slider (Volume, Progres, Tempo, Pitch, Intensitas)

- Trek: `#E6F2F7` dengan border 1px `#6B8899`, tinggi 12px, radius Full
- Isi: gradient `Aqua Tide`
- Knob: lingkaran 24px, `#033067`, border putih 2px, Focus Ring saat fokus
- Nilai persen ditampilkan sebagai teks di kanan (Volume 30%, Tempo 60%, dan seterusnya) dan menjadi `aria-valuetext`
- Elemen `input type="range"`. Panah kiri dan kanan mengubah 5%, `Home` dan `End` ke ujung
- Progres pemutaran berupa `role="progressbar"` (baca saja)

### Inputs

- Tinggi 48px, background `#FFFFFF`, border `1.5px solid #6B8899`, radius 10px, padding 12px 16px
- Font 16px, teks `#0A1F33`, placeholder `#5F7383`
- Focus: border `#0675A3` dan Focus Ring terang
- Error: border `#C4442A` 2px, ikon, dan pesan teks di bawah kolom, dihubungkan dengan `aria-describedby`
- Disabled: background `#E6F2F7`, opacity 0.6
- Label: Label 14px, weight 700, warna `#033067`, selalu terlihat di atas kolom

### Select, Radio, Checkbox

- **Select (Instrumen, Bahasa narasi):** elemen `select` asli, tinggi 48px, gaya sama dengan Inputs
- **Radio (Mode pemutaran: Bersamaan atau Berurutan):** dikelompokkan dalam `fieldset` dan `legend`, lingkaran 24px, border `#6B8899`, terpilih `#0675A3`
- **Checkbox (ambang peringatan, jenis notifikasi, lokasi dipantau):** kotak 24px, radius 6px, terpilih `#0675A3` dengan tanda centang putih, label bisa diklik, dikelompokkan dalam `fieldset`

### Alert dan Toast

- Background `#FFFFFF`, border `1px solid #D6E6ED`, radius 14px, padding 12px 18px, shadow Medium
- Aksen kiri 4px: Success `#0B7A5E`, Error `#C4442A`, Info `#0675A3`, Warning `#FEB161`
- Selalu memuat ikon dan teks. Toast tampil 6 detik atau lebih, dapat ditutup dengan keyboard, dan tidak menghilang saat menerima fokus
- Peringatan kritis (AQI di atas ambang, cuaca berbahaya): `role="alert"`, disertai bunyi khusus dan getaran (haptic) bila pengguna mengaktifkannya dan perangkat mendukung
- Peringatan biasa: `role="status"`

### Popup / Modal

Semua konfirmasi dan pesan penting memakai komponen ini. `alert()` bawaan browser dilarang.

- Background `#FFFFFF`, border `1px solid #D6E6ED`, radius 20px, shadow Overlay
- Backdrop `rgba(3, 48, 103, 0.60)`
- Judul Title 22px `#033067`, isi Body `#42586A`
- Fokus dipindah ke judul saat dibuka, terkunci di dalam modal, `Esc` menutup, fokus kembali ke pemicu
- Contoh: konfirmasi Setel ulang, "Kembalikan semua pengaturan ke nilai awal?" dengan tombol **Setel ulang** dan **Batal**

### Loading State

- Spinner: cincin `#0675A3`, track `#E6F2F7`, rotasi 800ms linear
- Skeleton: `#E6F2F7` dengan shimmer `#F4F9FB`, 1200ms
- Teks "Memuat data lingkungan" diumumkan lewat live region
- Semua tombol dengan aksi async punya state loading

### Footer

- Background `#033067`, teks `#BFD9E8`, judul kolom `#F4F9FB`
- Isi: "© EchoSense 2026, Sistem sonifikasi lingkungan untuk tunanetra", tautan Kebijakan Privasi, Panduan Aksesibilitas, Kontak
- Tautan bergaris bawah, hover `#F4F9FB`
- Layout: 1 kolom mobile, 3 kolom desktop

### Bottom Navigation (mobile, halaman aplikasi)

- Empat item: Pengaturan, Riwayat, Peringatan, Bantuan
- Latar `#FFFFFF`, border atas 1px `#D6E6ED`, ikon 24px dengan label teks 14px
- Item aktif: teks dan ikon `#0675A3`, garis atas 3px `#0675A3`
- Perlu diputuskan apakah dipakai bersama menu hamburger (lihat Celah)

## Site Map

```
/                    Beranda / Landing (semua pengguna)
/dashboard           Dashboard Utama / Kontrol Audio
/pengaturan          Pengaturan Preferensi Audio & Peringatan
/riwayat             Riwayat dan Tren (belum ada wireframe)
/peringatan          Daftar dan Pengaturan Peringatan (belum ada wireframe)
/fitur               Penjelasan fitur (bagian di Beranda atau halaman)
/bantuan             Bantuan, Panduan Keyboard, legenda suara (belum ada wireframe)
/masuk               Masuk dan Daftar (belum ada wireframe)
/onboarding          Onboarding aksesibilitas pengguna baru (belum ada wireframe)
```

## Halaman

### Halaman 1: Beranda / Landing

Urutan blok:

1. Navbar publik dan tautan Lewati ke konten utama
2. Hero: badge, headline "Dengarkan Kondisi Lingkungan Anda", deskripsi, tombol Mulai Mendengarkan dan Panduan Keyboard, ilustrasi gelombang
3. Fitur utama: judul section dan empat Feature Card
4. Footer

Latar: hero navy, section fitur `Background`, kartu putih. Fokus awal ada pada tautan lewati konten.

### Halaman 2: Dashboard Utama / Kontrol Audio

Desktop: sidebar lokasi di kiri, area utama di kanan. Mobile: dropdown lokasi, panel ditumpuk, bottom navigation.

| Blok | Isi |
| --- | --- |
| Sidebar Lokasi | Jakarta (saat ini), Bandung, Surabaya, Tambah lokasi |
| Kontrol Audio | Lokasi dan waktu pembaruan, Play / Pause, Volume 30%, Progres 35%, Ulang, Lokasi Berikutnya |
| Ringkasan Suara AI | Transkrip narasi 30 sampai 60 detik |
| Data Card | Kualitas Udara, Cuaca, Lalu Lintas (dapat dibuka) |
| Baris pintasan | Spasi Play/Pause, H Bantuan, S Pengaturan |

Perilaku: pintasan `Spasi`, `H`, `S` hanya aktif saat fokus tidak berada di kontrol yang sudah memakai tombol tersebut, dan dapat dimatikan (WCAG 2.1.4). Data diperbarui secara berkala dan diumumkan sopan lewat live region.

### Halaman 3: Pengaturan Preferensi Audio & Peringatan

Dua panel berdampingan di desktop, ditumpuk di mobile.

**Panel Sonifikasi & Narasi:** slider Tempo, Pitch, Intensitas; select Instrumen (contoh Piano) dan Bahasa narasi (contoh Bahasa Indonesia); radio Mode pemutaran (Bersamaan atau Berurutan).

**Panel Peringatan Lingkungan:** checkbox Polusi udara tinggi (AQI di atas 150), Cuaca berbahaya, Lalu lintas berat; jenis notifikasi Audio dan Haptic; lokasi dipantau Jakarta, Bandung, Surabaya.

**Aksi:** **Simpan pengaturan** (Primary), **Pratinjau suara** (Secondary), **Setel ulang** (Secondary, dengan konfirmasi modal).

Perilaku: Pratinjau suara memutar contoh dengan nilai slider saat ini tanpa menyimpan. Simpan memunculkan toast "Pengaturan disimpan" yang diumumkan. Meninggalkan halaman dengan perubahan yang belum disimpan memicu konfirmasi.

## Desain Audio dan Narasi

| Data | Parameter suara | Arah pemetaan |
| --- | --- | --- |
| Kualitas udara | Pitch dan tempo | Udara buruk menghasilkan pitch lebih tinggi dan tempo lebih cepat (contoh dari proposal) |
| Cuaca | Melodi dan timbre | Cuaca cerah menghasilkan melodi harmonis (contoh dari proposal) |
| Lalu lintas | Kepadatan ritme | **[Usulan]** Padat berarti ritme rapat, lancar berarti ritme renggang |
| Intensitas keseluruhan | Loudness | Diatur pengguna lewat slider Intensitas |

Aturan:

- Voice briefing 30 sampai 60 detik, kalimat pendek, urutan tetap: lokasi, udara, cuaca, lalu lintas, saran singkat.
- Mode Bersamaan menurunkan volume soundscape saat narasi berbicara agar kata tetap jelas.
- Peringatan kritis boleh menyela pemutaran, peringatan biasa menunggu jeda.
- Volume awal moderat dan tidak pernah memutar di volume maksimum secara otomatis.
- Sediakan legenda suara di halaman Bantuan yang menjelaskan arti tiap bunyi dengan contoh audio.

## Accessibility

- [ ] Kontras teks minimal 4.5:1 dan komponen UI minimal 3:1 (seluruh palet di atas sudah dihitung).
- [ ] Semua fungsi dapat dijalankan dengan keyboard, tanpa jebakan fokus, urutan tab mengikuti urutan baca.
- [ ] Pintasan satu karakter dapat dimatikan atau diubah.
- [ ] Semua kontrol punya nama aksesibel, peran, dan status (`aria-pressed`, `aria-expanded`, `aria-current`).
- [ ] Landmark lengkap: `header`, `nav`, `main`, `aside`, `footer`.
- [ ] Perubahan data dan peringatan diumumkan lewat live region tanpa memindahkan fokus.
- [ ] Ada versi teks untuk setiap keluaran audio dan visual cues sinkron.
- [ ] Tidak ada informasi yang hanya disampaikan lewat warna atau bunyi.
- [ ] Teks dapat diperbesar sampai 200% dan tata letak tetap terbaca pada lebar 320px.
- [ ] `prefers-reduced-motion` dan `prefers-contrast` dihormati.
- [ ] `lang="id"` pada halaman, narasi bahasa lain diberi `lang` sendiri.
- [ ] Diuji dengan NVDA (sudah disebut di proposal), VoiceOver, dan TalkBack, serta bersama pengguna tunanetra **[Usulan]**.
- [ ] Mode kontras tinggi **[Usulan]**: latar `#033067`, teks `#F4F9FB`, border `#F4F9FB`, aksen `#FEB161`, dipilih pengguna di Pengaturan atau mengikuti `prefers-contrast`.

## Design Tokens (CSS)

```css
:root {
  --navy: #033067;
  --navy-accent: #02437B;
  --ocean: #0675A3;
  --ocean-hover: #055F85;
  --aqua: #14C5D9;
  --lagoon: #21B9BE;
  --sunrise: #FEB161;
  --sunrise-hover: #FFC488;
  --pulse: #FD7659;
  --bg: #F4F9FB;
  --surface: #FFFFFF;
  --surface-alt: #E6F2F7;
  --text: #0A1F33;
  --text-2: #42586A;
  --text-3: #5F7383;
  --text-on-dark: #F4F9FB;
  --text-on-dark-2: #BFD9E8;
  --border-subtle: #D6E6ED;
  --border-medium: #6B8899;
  --error: #C4442A;
  --success: #0B7A5E;
  --radius-s: 6px;
  --radius-m: 10px;
  --radius-l: 14px;
  --radius-xl: 20px;
  --focus-ring: 0 0 0 2px #F4F9FB, 0 0 0 5px #0675A3;
  --focus-ring-dark: 0 0 0 2px #033067, 0 0 0 5px #FEB161;
}
@media (prefers-reduced-motion: reduce) {
  *, *::before, *::after { animation: none !important; transition: none !important; }
}
```

## Do's and Don'ts

1. **Do** jadikan `#F4F9FB` sebagai latar dominan, `#033067` untuk permukaan gelap, dan `#0675A3` sebagai aksen interaktif utama.
2. **Do** simpan oranye sunrise untuk aksi utama di latar navy, penanda aktif, dan batang visualizer. **Don't** memakainya untuk teks di latar terang.
3. **Don't** memakai cyan `#14C5D9` atau teal `#21B9BE` sebagai warna teks di latar terang. Kontrasnya di bawah 3:1. Keduanya hanya untuk dekorasi, badge dengan teks navy, atau teks kecil di latar navy.
4. **Do** gunakan Atkinson Hyperlegible Next di seluruh antarmuka. **Don't** mencampur font lain.
5. **Don't** memakai teks di bawah 14px, huruf kapital semua, atau warna sebagai satu-satunya penanda status.
6. **Do** sertakan ikon dan label teks pada setiap badge status.
7. **Don't** memakai `alert()` bawaan browser. Gunakan toast atau modal custom yang dapat diakses.
8. **Do** tampilkan loading state yang konsisten dan diumumkan pada semua proses async.
9. **Don't** menambahkan animasi dekoratif, autoplay suara, scroll reveal, atau count-up. Satu-satunya gerakan khas adalah visualizer.
10. **Don't** memakai emoji atau dash dekoratif di UI dan copywriting.
11. **Do** pastikan teks terang (`#F4F9FB`) hanya di latar gelap dan teks gelap (`#0A1F33`) hanya di latar terang. **Don't** memakai `#033067` sebagai warna teks di atas latar `#033067`.
12. **Don't** memakai bayangan hitam atau cool-toned. Semua bayangan bernuansa navy.
13. **Don't** menambahkan warna atau gradient di luar dokumen ini tanpa persetujuan.
14. **Do** jaga radius, spacing, dan tipografi mengikuti token di dokumen ini.

## Copywriting

- Bahasa Indonesia sehari-hari, kalimat pendek, sentence case.
- Tombol memakai kata kerja yang menyebut hasilnya: "Simpan pengaturan", bukan "Kirim". Nama aksi konsisten sepanjang alur (tombol "Simpan pengaturan" menghasilkan toast "Pengaturan disimpan").
- Istilah teknis sistem disembunyikan dari pengguna: tulis "Peringatan", bukan "Alert Engine".
- Galat tidak meminta maaf dan tidak samar. Contoh: "Data cuaca belum bisa diambil. Periksa koneksi Anda, lalu pilih Coba lagi."
- Kondisi kosong memberi arahan. Contoh: "Belum ada lokasi tersimpan. Tambahkan lokasi untuk mulai mendengarkan."
- Angka AQI selalu disertai kategori. Contoh: "AQI 80, Sedang". Satuan konsisten: km/j, °C, %.

## Celah dan Keputusan yang Perlu Diambil

1. **Halaman Riwayat dan Peringatan** ada di menu dan proposal (fitur 8 dan 5), tetapi belum punya wireframe.
2. **Halaman Masuk, Onboarding aksesibilitas, Bantuan, dan Panduan Keyboard** disebut di user flow atau tombol tetapi belum digambar.
3. **Menu berbeda** antara Beranda dan halaman aplikasi. Tombol Bantuan ada di bottom nav mobile tetapi tidak ada di menu header aplikasi.
4. **Mobile Dashboard** punya hamburger dan bottom nav sekaligus. Pilih satu pola utama agar tidak ada dua jalur navigasi yang tumpang tindih.
5. **Versi logo untuk latar navy** perlu dibuat (putih monokrom atau chip putih), karena logo saat ini berlatar putih dan garis navy-nya tidak terlihat di navbar.
6. **Voice commands** disebut di user flow tetapi belum ada di wireframe.
7. **Istilah** perlu diseragamkan: "Lokasi Favorit" (Beranda) dan "Lokasi tersimpan" (Dashboard); "Peringatan Dini" (Beranda) dan "Peringatan" (menu).
8. **Rentang AQI** pada Air Quality Palette perlu disesuaikan dengan sumber data yang dipakai.
9. **Perubahan dari wireframe:** wireframe berupa grayscale dengan monospace dan sudut tajam. Dokumen ini memakai warna logo, Atkinson Hyperlegible Next, dan sudut membulat mengikuti lengkung logo. Bila ingin tetap tegas seperti wireframe, ubah Border Radius menjadi 0 dan ganti font.
