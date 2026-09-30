# **PROPOSAL WEB DEVELOPMENT** 

EchoSense: Web Sonifikasi Data Lingkungan Adaptif Berbasis Kecerdasan Buatan untuk Mewujudkan Pengalaman Web Inklusif bagi Penyandang Tunanetra 



# Disusun oleh Tim SONIC : 

1. Ragil Kurniawan G1A024063 2. Muhammad Nathan Algibran G1A024057 3. Muhammad Ihsan G1A024108 

**WEB DEVELOPMENT RAFATECH STUDY CLUB SISTEM INFORMASI UIN RADEN FATAH PALEMBANG** 

**2026** 

# **Kata Pengantar** 

Puji dan syukur penulis panjatkan kehadirat Tuhan Yang Maha Esa atas segala rahmat, karunia, serta kemudahan yang diberikan sehingga proposal dengan judul "EchoSense: Web Sonifikasi Data Lingkungan Adaptif Berbasis Kecerdasan Buatan untuk Mewujudkan Pengalaman Web Inklusif bagi Penyandang Tunanetra" ini dapat diselesaikan dengan baik guna memenuhi persyaratan mengikuti Lomba Web Development Internasional RafaTech 2026 yang diselenggarakan oleh Study Club Sistem Informasi UIN Raden Fatah Palembang. 

Perkembangan teknologi kecerdasan buatan yang begitu pesat dalam beberapa tahun terakhir telah membuka peluang besar bagi lahirnya inovasi-inovasi baru di berbagai bidang kehidupan, tidak terkecuali dalam upaya mewujudkan teknologi yang lebih inklusif bagi seluruh lapisan masyarakat. Sayangnya, di tengah derasnya perkembangan tersebut, kelompok penyandang disabilitas, khususnya tunanetra, masih kerap tertinggal dalam menikmati manfaat teknologi digital secara setara. 

Proposal ini disusun sebagai wujud kontribusi penulis dalam menjawab tantangan kompetisi bertema APEX, AI Powered Experience for the Web, dengan mengusung sebuah pendekatan yang memadukan kecerdasan buatan dan konsep sonifikasi data untuk menjembatani kesenjangan akses informasi lingkungan bagi penyandang tunanetra. Melalui EchoSense, penulis berupaya menunjukkan bahwa kecerdasan buatan tidak hanya dapat dimanfaatkan untuk kebutuhan yang bersifat umum dan komersial, tetapi juga dapat diarahkan secara spesifik untuk menjawab persoalan sosial yang selama ini kurang mendapat perhatian, khususnya dalam ranah aksesibilitas digital di Indonesia. 

Dalam proses penyusunan proposal ini, penulis berupaya menggali secara mendalam mengenai konsep sonifikasi data, kondisi eksisting teknologi asistif bagi tunanetra, serta bagaimana kecerdasan buatan dapat berperan optimal dalam memetakan data lingkungan multidimensi menjadi representasi suara yang bermakna dan mudah dipahami. Penulis berharap gagasan ini tidak hanya menjadi sebuah karya kompetisi semata, tetapi juga dapat menjadi langkah awal yang bermanfaat bagi pengembangan teknologi aksesibilitas yang lebih luas di masa mendatang. 

Akhir kata, penulis berharap proposal EchoSense ini dapat memberikan gambaran yang jelas dan komprehensif mengenai konsep, perancangan, serta potensi pengembangan platform ini, sekaligus dapat menjadi salah satu kontribusi nyata bagi terwujudnya ekosistem teknologi digital yang lebih inklusif dan ramah bagi seluruh kalangan masyarakat Indonesia, tanpa terkecuali. 

# **Daftar Isi** 

|**Kata Pengantar**|**2**|
|---|---|
|**Daftar Isi**|**3**|
|**BAB I**|**4**|
|**1.1 Latar Belakang**|**4**|
|**1.2 Rumusan Masalah**|**4**|
|**1.3 Tujuan**|**4**|
|**1.4 Manfaat**|**4**|
|**BAB II**|**5**|
|**2.1  Deskripsi Website**|**5**|
|**2.2 Unique Selling Point (USP)**|**5**|
|**2.3 Target Market dan Pengguna**|**5**|
|**2.4 Potensi Pengembangan dan Monetisasi**|5|
|**2.5 Metode Pembuatan/Pengembangan**|**5**|
|**BAB III**|**6**|
|**3.1 Fitur Website**|**6**|
|**3.2 Arsitektur Sistem & User Flow**|**6**|
|**3.3 Wireframe Website**|**6**|
|**3.4  Tampilan UI Website**|6|
|**BAB IV**|**7**|
|**4.1 Kesimpulan**|**7**|
|**4.2 Saran**|**7**|



# **BAB I** 

# **PENDAHULUAN** 

# **1.1 Latar Belakang** 

Indra penglihatan berperan besar dalam memberikan informasi mengenai kondisi lingkungan sekitar, termasuk kualitas udara, cuaca, dan situasi lalu lintas di suatu wilayah. Bagi penyandang tunanetra, keterbatasan pada indra ini menyebabkan mereka bergantung pada indra lain seperti pendengaran dan perabaan untuk mengetahui kondisi di sekitarnya sebelum melakukan aktivitas atau berpindah tempat(Putra, 2024) 

Berdasarkan hasil Long Form Sensus Penduduk  2020 yang dipublikasikan Badan Pusat Statistik menggunakan kerangka Washington Group on Disability Statistics, secara nasional tercatat 3,31 persen penduduk Indonesia mengalami gangguan penglihatan (disabilitas tipe 1), sementara 0,38 persen di antaranya mengalami gangguan penglihatan pada tingkat yang lebih berat meski telah menggunakan alat bantu seperti kacamata (disabilitas tipe 3) (Badan Pusat Statistik, 2024). Angka ini menunjukkan bahwa populasi dengan gangguan penglihatan di Indonesia bukanlah jumlah yang kecil, sehingga kebutuhan akan teknologi yang mendukung aksesibilitas informasi bagi kelompok ini menjadi semakin mendesak untuk direspons. 

Sejumlah teknologi asistif berbasis suara telah dikembangkan untuk membantu penyandang tunanetra. Salah satunya adalah screen reader Non-Visual Desktop Access (NVDA) yang membacakan teks pada layar komputer menjadi suara dan terbukti membantu siswa tunanetra dalam mengoperasikan komputer, membaca dokumen, serta mengakses materi pembelajaran (Azzahra & Safitri, 2024). Selain itu, aplikasi berbasis pengenalan gambar seperti Vision juga telah dikembangkan untuk membantu tunanetra mengidentifikasi objek fisik di sekitar mereka melalui kombinasi teknologi kecerdasan buatan dan text-to-speech (Putra, 2024). Meskipun kedua jenis teknologi tersebut terbukti bermanfaat, keduanya masih berfokus pada konversi informasi visual berupa teks atau objek diam menjadi suara, dan belum menjangkau data lingkungan yang bersifat dinamis dan berubah secara real-time, seperti kualitas udara, cuaca, maupun kepadatan lalu lintas di suatu wilayah. 

Pendekatan sonifikasi data (data sonification), yaitu proses menerjemahkan data ke dalam representasi suara yang bermakna, menawarkan potensi untuk menjembatani kesenjangan ini. Sonifikasi data memungkinkan penyampaian informasi multidimensi secara efisien melalui perubahan parameter suara seperti frekuensi, tempo, dan timbre, dan bermanfaat khususnya bagi individu dengan gangguan penglihatan yang tidak dapat memanfaatkan visualisasi data konvensional. Pendekatan ini juga terbukti dapat meningkatkan pemahaman terhadap data kompleks tanpa memerlukan keahlian teknis sebelumnya, karena sistem pendengaran manusia secara alami mampu mendeteksi pola dan perubahan dalam sinyal suara secara simultan (Sawe dkk., 2020). 

Bertolak dari keterbatasan teknologi asistif yang ada saat ini serta potensi besar dari pendekatan sonifikasi data yang belum banyak diterapkan pada data lingkungan di Indonesia, dan sejalan dengan tema kompetisi APEX, AI Powered Experience for the Web, penulis mengusung gagasan EchoSense, sebuah platform web berbasis kecerdasan buatan yang mengubah data lingkungan real-time menjadi soundscape adaptif dan visual sinkron, guna meningkatkan aksesibilitas informasi lingkungan bagi penyandang tunanetra sekaligus menghadirkan pengalaman web yang inovatif dan inklusif. 

# **1.2 Rumusan Masalah** 

1. Bagaimana penyandang tunanetra dapat memperoleh informasi kondisi lingkungan secara real-time (kualitas udara, cuaca, dan kepadatan lalu lintas) dengan cara yang intuitif, mengingat teknologi asistif yang tersedia saat ini masih terbatas pada konversi teks atau objek statis menjadi suara? 

2. Bagaimana merancang sebuah sistem yang mampu mengubah data lingkungan yang bersifat dinamis dan multidimensi menjadi representasi suara (sonifikasi) yang mudah dipahami tanpa memerlukan keahlian teknis atau literasi data sebelumnya? 

3. Bagaimana peran kecerdasan buatan (AI) dapat dioptimalkan untuk memetakan kombinasi data lingkungan menjadi _soundscape_ adaptif yang kontekstual, sekaligus menghasilkan narasi suara (voice briefing) yang informatif bagi pengguna? 

- **1.3 Tujuan** 

   1. Menyediakan sarana bagi penyandang tunanetra untuk memperoleh informasi kondisi lingkungan secara real-time, mencakup kualitas udara, cuaca, dan kepadatan lalu lintas, dengan cara yang lebih intuitif dibandingkan teknologi asistif berbasis teks yang sudah ada. 

   2. Merancang dan mengimplementasikan sistem sonifikasi data yang mampu menerjemahkan data lingkungan multidimensi menjadi soundscape adaptif yang mudah dipahami, tanpa menuntut pengguna memiliki keahlian teknis atau literasi data tertentu. 

   3. Mengoptimalkan peran kecerdasan buatan dalam memetakan kombinasi data lingkungan menjadi parameter suara yang kontekstual, serta menghasilkan narasi suara (voice briefing) yang informatif dan relevan dengan kondisi pengguna. 

# **1.4 Manfaat** 

1. Manfaat bagi Pengguna (Penyandang Tunanetra) 

- Memberikan akses yang lebih setara terhadap informasi lingkungan sehari-hari, sehingga penyandang tunanetra dapat merencanakan aktivitas dan mobilitas dengan lebih mandiri dan aman, tanpa harus bergantung penuh pada bantuan orang lain. 

2. Manfaat bagi Pengembangan Ilmu Pengetahuan Menjadi salah satu rujukan penerapan sonifikasi data berbasis kecerdasan buatan untuk data lingkungan di Indonesia, mengingat penelitian sejenis pada konteks lokal masih terbatas, sehingga dapat mendorong penelitian dan pengembangan lebih lanjut di bidang aksesibilitas digital. 

3. Manfaat bagi Masyarakat dan Institusi 

   - Mendorong kesadaran akan pentingnya desain teknologi yang inklusif (accessibility by design), sekaligus dapat menjadi contoh implementasi nyata bagi pemerintah daerah atau penyedia data publik (BMKG, dinas lingkungan hidup, dan sejenisnya) untuk turut membuka akses data secara lebih ramah disabilitas. 

4. Manfaat Keberlanjutan 

   - Arsitektur EchoSense yang modular (data layer terpisah dari AI mapping engine dan sonification engine) memungkinkan platform ini dikembangkan lebih lanjut pasca-kompetisi, baik dari sisi cakupan wilayah (tidak terbatas satu kota), jenis data lingkungan lain yang dapat ditambahkan di masa depan, maupun perluasan 

ke kelompok pengguna disabilitas lain (misalnya melalui penyesuaian antarmuka bagi pengguna low-vision atau gangguan pendengaran), sehingga manfaat platform ini tidak berhenti pada masa penilaian lomba semata. 

# **BAB II** 

# **PERENCANAAN PENGEMBANGAN WEBSITE** 

# **2.1 Deskripsi Website** 

EchoSense adalah sebuah platform web berbasis kecerdasan buatan yang mengubah data lingkungan real-time, meliputi kualitas udara, kondisi cuaca, dan kepadatan lalu lintas suatu wilayah, menjadi soundscape adaptif yang dapat dipahami secara intuitif oleh penyandang tunanetra. Berbeda dengan teknologi asistif yang sudah ada dan umumnya berfokus pada konversi teks atau objek statis menjadi suara, EchoSense hadir untuk menjembatani kesenjangan akses terhadap data lingkungan yang bersifat dinamis dan terus berubah. 

Sistem bekerja dengan mengumpulkan data dari berbagai sumber terbuka (open API), kemudian AI mapping engine menerjemahkan kombinasi data tersebut menjadi parameter suara yang bermakna, seperti frekuensi, tempo, timbre, dan tekstur suara. Selain lapisan audio, EchoSense juga dilengkapi dengan voice briefing berbasis AI yang menyampaikan ringkasan kondisi lingkungan dalam bahasa natural, serta visualisasi sinkron sebagai pelengkap bagi pengguna dengan penglihatan normal, seperti juri maupun pihak-pihak lain yang berkepentingan. 

Fungsi utama website ini adalah menjadi sarana bagi penyandang tunanetra untuk mengenali kondisi lingkungan sekitar sebelum beraktivitas atau berpindah tempat, sehingga mereka dapat merencanakan mobilitas dengan lebih mandiri, aman, dan percaya diri. 

# **2.2 Unique Selling Point (USP)** 

1. Pendekatan sonifikasi data yang belum banyak diterapkan di Indonesia, sebagian besar teknologi asistif yang ada saat ini berfokus pada konversi teks atau objek diam menjadi suara, seperti screen reader dan aplikasi identifikasi objek, sementara EchoSense menjadi salah satu yang pertama menerapkan sonifikasi data lingkungan yang dinamis dan multidimensi pada konteks lokal Indonesia. 

2. Kecerdasan buatan sebagai context-aware mixing engine, bukan sekadar mapping linear, AI pada EchoSense tidak hanya menerjemahkan satu data menjadi satu suara, tetapi mempertimbangkan kombinasi seluruh parameter lingkungan secara 

simultan agar soundscape yang dihasilkan tetap informatif dan tidak saling tumpang tindih, layaknya proses mixing audio profesional. 

3. Aksesibilitas sebagai inti desain, bukan fitur tambahan, EchoSense dirancang mengikuti prinsip accessibility by design dengan navigasi penuh berbasis keyboard, dukungan ARIA live region, dan pengalaman yang tidak bergantung pada elemen visual, sehingga benar-benar dapat digunakan secara mandiri oleh penyandang tunanetra. 

4. Voice briefing kontekstual berbasis AI, selain soundscape, sistem turut menghasilkan narasi suara natural yang merangkum kondisi lingkungan secara ringkas, memberikan konfirmasi eksplisit di samping pengalaman audio ambient. 

5. Arsitektur modular yang mendukung keberlanjutan, pemisahan antara data layer, AI mapping engine, dan sonification engine memungkinkan pengembangan lanjutan, baik perluasan cakupan wilayah, penambahan jenis data lingkungan baru, maupun adaptasi untuk kelompok disabilitas lain di masa mendatang. 

# **2.3 Target Market dan Pengguna** 

# **Target pengguna utama (primary user):** 

Penyandang tunanetra dan low vision di Indonesia, dengan prioritas awal pada kelompok usia produktif (18-45 tahun) yang memiliki mobilitas tinggi dan kebutuhan mendesak akan informasi lingkungan sebelum bepergian, seperti mahasiswa, pekerja, maupun individu yang menggunakan transportasi umum secara mandiri. 

# **Target pengguna sekunder:** 

1. Keluarga dan pendamping penyandang tunanetra, yang dapat memanfaatkan fitur ini sebagai alat bantu komunikasi kondisi lingkungan. 

2. Lembaga dan komunitas disabilitas, seperti sekolah luar biasa (SLB) bagian tunanetra dan organisasi advokasi disabilitas, sebagai mitra edukasi dan uji coba. 

# **Segmentasi pasar untuk pengembangan lanjutan (potential B2B/B2G):** 

1. Instansi pemerintah daerah dan penyedia data publik (BMKG, dinas lingkungan hidup, dinas perhubungan), sebagai mitra strategis dalam membuka akses data yang lebih ramah disabilitas. 

2. Lembaga pendidikan dan penelitian, sebagai referensi penerapan sonifikasi data berbasis AI pada konteks lokal. 

Berdasarkan data Badan Pusat Statistik tahun 2024, populasi penyandang gangguan penglihatan di Indonesia mencapai 3,31 persen dari total penduduk, sebuah angka yang menunjukkan potensi pasar sekaligus urgensi sosial yang signifikan untuk direspons melalui teknologi ini. 

# **2.4 Potensi Pengembangan dan Monetisasi Model B2C (freemium):** 

Versi dasar EchoSense (sonifikasi satu lokasi dengan parameter terbatas) disediakan gratis sebagai bentuk komitmen terhadap aksesibilitas, sementara fitur lanjutan seperti route sonification multi-titik, personalisasi profil pendengaran, dan riwayat data historis dapat ditawarkan melalui skema langganan berbiaya rendah. **Model B2B/B2G (kemitraan):** 

1. Kerja sama dengan instansi pemerintah daerah atau penyedia data publik untuk integrasi data resmi secara lebih mendalam, sekaligus mendukung inisiatif smart city yang inklusif. 

2. Kemitraan dengan lembaga pendidikan luar biasa (SLB) dan organisasi disabilitas untuk penyediaan lisensi penggunaan dalam skala institusi. 

3. Potensi kolaborasi dengan penyedia layanan transportasi publik untuk integrasi fitur route sonification sebagai bagian dari layanan aksesibilitas mereka. 

# **Potensi pengembangan jangka panjang:** 

Perluasan cakupan wilayah di luar kota percontohan awal, penambahan jenis data lingkungan lain (misalnya tingkat kebisingan atau kualitas air), serta adaptasi antarmuka bagi kelompok disabilitas lain seperti pengguna low vision atau gangguan pendengaran, sehingga EchoSense dapat berkembang menjadi platform aksesibilitas lingkungan yang lebih komprehensif pasca-kompetisi. 

# **2.5 Metode Pembuatan/Pengembangan** 

# **Metodologi pengembangan:** 

Pengembangan EchoSense menggunakan pendekatan Rapid Application Development (RAD), yang dipilih karena kesesuaiannya dengan timeline kompetisi yang terbatas namun tetap membutuhkan iterasi cepat terhadap prototype, khususnya dalam pengujian kualitas pengalaman audio yang bersifat subjektif dan memerlukan penyesuaian berulang. 

# **Teknologi dan tools yang digunakan:** 

1. Frontend menggunakan Blade Templating Engine sebagai komponen native dari framework Laravel yang berfungsi untuk menyusun struktur utama halaman dan komponen antarmuka, dikombinasikan dengan HTML5 dan CSS3 untuk penyusunan tata letak dan styling, serta JavaScript dengan pemanfaatan Web Audio API secara native untuk sintesis suara real-time (OscillatorNode, GainNode, BiquadFilterNode, dan ConvolverNode). 

2. Backend menggunakan Laravel (PHP) sebagai kerangka kerja utama yang berfungsi sebagai data aggregator untuk mengambil dan menormalisasi data dari sumber eksternal secara berkala, sekaligus mengatur alur komunikasi antara frontend, database, dan layanan AI eksternal. 

3. AI Mapping Engine dibangun melalui kombinasi pendekatan rule-based weighted scoring yang diimplementasikan langsung pada sisi Laravel untuk pemetaan awal parameter data lingkungan ke parameter suara, serta pemanfaatan Large Language Model melalui Google AI Studio (Gemini API) yang diintegrasikan dari backend Laravel untuk menghasilkan voice briefing dalam bahasa natural berdasarkan kondisi data terkini, sehingga tidak memerlukan service Python terpisah untuk kebutuhan prototype ini. 

4. Sumber data eksternal mencakup OpenAQ API untuk data kualitas udara, API BMKG (data.bmkg.go.id) untuk data cuaca wilayah Indonesia, serta TomTom Traffic API untuk data kepadatan lalu lintas, dengan data proksi historis sebagai cadangan pada wilayah yang cakupan datanya masih terbatas, seluruhnya diintegrasikan melalui HTTP Client yang tersedia pada framework Laravel. 

5. Text-to-Speech menggunakan Web Speech API pada sisi client untuk menghasilkan narasi voice briefing secara langsung di browser, tanpa memerlukan pemrosesan tambahan di server. 

6. Database menggunakan MySQL sebagai basis data untuk menyimpan riwayat data lingkungan, preferensi pengguna, dan profil personalisasi pendengaran. 

7. Standar aksesibilitas mengacu pada Web Content Accessibility Guidelines (WCAG) 2.1 level AA, mencakup dukungan penuh navigasi keyboard, ARIA live region, dan kompatibilitas dengan screen reader. 

8. Version control dan kolaborasi tim menggunakan Git dan GitHub untuk manajemen kode secara kolaboratif antaranggota tim. 

# **BAB III** 

# **PERANCANGAN WEBSITE** 

# **3.1 Fitur Website** 

EchoSense dirancang dengan berbagai fitur utama yang berorientasi pada peningkatan aksesibilitas dan pengalaman pengguna (user experience) yang inklusif bagi penyandang tunanetra maupun pengguna umum. Fitur-fitur utama platform ini meliputi: 

1. **Real-Time Environmental Sonification** : Mengubah data lingkungan real-time (kualitas udara dari OpenAQ API, kondisi cuaca dari API BMKG, serta kepadatan lalu lintas dari TomTom Traffic API) menjadi soundscape adaptif. Perubahan parameter lingkungan langsung ditransformasikan menjadi perubahan frekuensi, tempo, timbre, dan tekstur suara menggunakan Web Audio API secara real-time. 

2. **AI-Powered Voice Briefing** : Menyajikan narasi ringkasan kondisi lingkungan secara real-time dalam bahasa natural. Narasi ini dihasilkan melalui integrasi Google AI Studio (Gemini API) pada backend Laravel, lalu disuarakan langsung di peramban pengguna menggunakan Web Speech API (Text-to-Speech). 

3. **Location-Based Data Retrieval** : Pengambilan data lingkungan secara otomatis berdasarkan lokasi pengguna saat ini (Geolocation API peramban) dengan dukungan opsi pencarian manual (nama kota/wilayah) sebagai penanganan cadangan (fallback). 

4. **Adaptive Sonification Preferences** : Modul pengaturan preferensi yang memungkinkan pengguna menyesuaikan parameter soundscape (seperti tempo, frekuensi dasar, intensitas volume, dan pilihan jenis instrumen) serta kecepatan narasi suara agar sesuai dengan kenyamanan dan sensitivitas pendengaran pengguna. 

5. **Environmental Alert System** : Sistem peringatan proaktif yang memberikan notifikasi audio dan visual secara otomatis apabila sistem mendeteksi kondisi lingkungan yang ekstrem atau berbahaya (misalnya tingkat polusi udara tinggi, cuaca buruk, atau kemacetan parah). 

6. **Accessible Web Interface (WCAG 2.1 AA)** : Antarmuka web yang dibangun dengan kerangka kerja Laravel dan Blade Templating Engine yang mematuhi standar WCAG 2.1 Level AA. Menyediakan dukungan penuh keyboard navigation, semantic HTML5, ARIA live regions, kontras warna tinggi untuk pengguna low vision, serta kompatibilitas penuh dengan screen reader (seperti NVDA). 

7. **Multi-Location Tracking & Favorite Locations** : Memungkinkan pengguna menyimpan dan memantau beberapa lokasi penting (seperti rumah, tempat kerja, atau kampus) secara cepat dan bersamaan melalui antarmuka lokasi favorit. 

8. **Historical Data & Trend Analysis** : Fasilitas untuk melihat riwayat data kondisi lingkungan serta analisis tren jangka panjang dalam skala harian, mingguan, maupun bulanan yang tersimpan dalam basis data MySQL. 

# **3.2 Arsitektur Sistem dan User Flow** 

# **3.2.1 Arsitektur Sistem** 

EchoSense mengusung arsitektur sistem yang terintegrasi secara modular berbasis framework Laravel sebagai core backend dan data aggregator. Arsitektur sistem terdiri dari beberapa lapisan (layers) utama: 

1. **Frontend Presentation Layer** : Blade Templating Engine (Laravel), HTML5, CSS3, dan JavaScript murni. Menampilkan antarmuka yang responsif dan ramah aksesibilitas (WCAG 2.1 AA), menangani navigasi papan ketik, mengelola ARIA live regions untuk pembaruan pembaca layar, serta memproses interaksi pengguna. 

2. **Audio Synthesis & TTS Layer (Client-Side Processing)** : Native Web Audio API (OscillatorNode, GainNode, BiquadFilterNode, ConvolverNode) dan Web Speech API. Melakukan sintesis audio secara langsung pada browser pengguna berdasarkan parameter yang dikirim oleh backend, menghasilkan soundscape adaptif tanpa membebani server, serta mengeksekusi text-to-speech untuk narasi voice briefing. 

3. **Backend Core & Data Aggregator Layer (Server-Side)** : Laravel Framework (PHP). Bertindak sebagai central hub yang menangani otentikasi pengguna, mengelola preferensi, melakukan ekstraksi dan normalisasi data dari API eksternal secara teratur melalui HTTP Client, serta mengatur alur komunikasi sistem. 

4. **External Data Integration Layer** : OpenAQ API (data kualitas udara), API BMKG (data cuaca wilayah Indonesia), TomTom Traffic API (data kepadatan dan kelancaran lalu lintas). Menyediakan pasokan data lingkungan aktual yang dinormalisasi oleh backend Laravel. 

5. **AI Mapping Engine & LLM Layer** : Rule-based weighted scoring pada backend Laravel dan Google AI Studio (Gemini API). Algoritma weighted scoring memetakan data lingkungan teragregasi menjadi parameter audio (pitch, tempo, timbre, filter). 

Gemini API memproses kondisi data terkini untuk menghasilkan teks narasi kontekstual (voice briefing) dalam bahasa Indonesia natural. 

6. **Database Layer** : MySQL Database. Menyimpan data pengguna, kredensial login, preferensi sonifikasi, daftar lokasi favorit, riwayat log alert, serta simpanan data historis lingkungan. 

# **3.2.2 User Flow** 

Alur penggunaan (user flow) platform EchoSense dirancang sederhana dan ramah disabilitas penglihatan: 

1. **Akses & Onboarding Aksesibilitas** : Pengguna membuka platform web EchoSense. Sistem secara otomatis mengaktifkan penyesuaian screen reader dan memberikan instruksi awal dalam bentuk narasi audio/suara. 

2. **Otentikasi / Pengaturan Lokasi** : Pengguna dapat masuk (login) menggunakan akun terdaftar atau langsung memilih opsi penentuan lokasi. 

3. **Deteksi / Input Lokasi** : Sistem mendeteksi lokasi pengguna secara otomatis melalui Geolocation API peramban, atau pengguna dapat memasukkan nama kota/wilayah secara manual via input field berlabel ARIA. 

4. **Agregasi & Pemrosesan Data** : Backend Laravel mengambil data dari OpenAQ, BMKG, dan TomTom Traffic API. Data diolah oleh algoritma weighted scoring untuk parameter audio dan diserahkan ke Gemini API untuk pembentukan narasi. 

5. **Sintesis Audio & Voice Briefing** : Browser menerima parameter audio dan teks narasi. Web Audio API membangkitkan soundscape lingkungan adaptif secara real-time, diikuti oleh Web Speech API yang membacakan ringkasan voice briefing. 

6. **Interaksi & Kontrol Pengguna** : Pengguna mendengarkan kondisi lingkungan dan dapat berinteraksi menggunakan pintasan papan ketik (misal: Spacebar untuk Play/Pause, tombol Replay, Switch Location, atau penyesuaian volume dan tempo). 

7. **Peringatan Lingkungan (Alerts)** : Jika terdeteksi kondisi ekstrem (misal AQI sangat buruk atau cuaca ekstrem), Alert Engine memicu sinyal peringatan suara khas dan notifikasi visual ber-kontras tinggi. 

8. **Eksplorasi Riwayat & Tren** : Pengguna dapat menavigasi ke menu riwayat untuk mendengarkan atau membaca tren perubahan data lingkungan harian/mingguan. 

# **3.3 Wireframe Website** 

Rancangan antarmuka berstruktur low-fidelity disusun dengan mengutamakan tata letak yang bersih, hierarki elemen yang jelas, serta navigasi yang terstruktur untuk pengguna aksesibilitas: 



<!-- Start of picture text -->
HALAMAN 1: DASHBOARD UTAMA / KONTROL AUDIO<br>_ & |<br>PTS<br>teu toast<br>3 | {spot} fc cm<br>3) o o ao<br><!-- End of picture text -->

Gambar 3.3.1 Dashboard Utama / Kontrol Audio 



<!-- Start of picture text -->
HALAMAN 2: Pengaturan Preferensi Audio & Peringatan<br>CO Grayscale only — [X) [X] Placeholder Visual<br>PENGATURAN PREFERENST<br>Senpo — x] Polust wdara tingst (AQK > 150) LT<br>bo) Haptse se oman<br>Bahasa indonesia -<br>[x] Jakarta [x] Bandung [ ] Surabaya<br><!-- End of picture text -->

Gambar 3.3.2 Pengaturan Referensi Audio / Kontrol Audio 

# **3.4** **_Tampilan UI Website_** 



<!-- Start of picture text -->
EchoSense | Sonifias! Dashboard —Pengaturan —-Riwayat_—_Peringatan<br>O Pane<br>“ . — 9 Volume 0% © Progres perutaran or12 0320 (359%)<br>= oe<br>1D ingkasansuara At (30-60 detik) secepaton: QJ 25<br><!-- End of picture text -->

Gambar 3.4.1 Tampilan Dashboard Utama 



<!-- Start of picture text -->
sw) EchoSense © re oo<br>Pengaturan preferensi<br>© Sonifikasi & narasi ‘Siesis audio Peringatan lingkungan ree)<br>———Tempo soniias! GS: 0% 20 Armbang betas kondist<br>————Pitch nada dasar | 459% (0 Polisi udaa tinggi (AGL datas150) wend<br>— 7<br>Prono akustik mies<br>Bahasa Indonesia . 2 satio Meptie<br>Mode pemutaran<br>° at Seton<br>berurtan<br>YSimean pengaturan © setetuang<br><!-- End of picture text -->

Gambar 3.4.2 Tampilan Pengaturan Referensi 

# **BAB IV** 

# **PENUTUP** 

# **4.1 Kesimpulan** 

Keterbatasan akses penyandang tunanetra terhadap informasi kondisi lingkungan secara real-time, seperti kualitas udara, cuaca, dan kepadatan lalu lintas, masih menjadi persoalan yang belum banyak terjawab oleh teknologi asistif yang ada saat ini. Teknologi seperti screen reader NVDA dan aplikasi identifikasi objek berbasis AI memang telah membantu penyandang tunanetra dalam berbagai aktivitas, namun keduanya masih berfokus pada konversi teks atau objek statis menjadi suara, belum menjangkau data lingkungan yang bersifat dinamis dan terus berubah. Kondisi ini diperkuat oleh data Badan Pusat Statistik (2024) yang mencatat 3,31 persen penduduk Indonesia mengalami gangguan penglihatan, sebuah angka yang menegaskan urgensi pengembangan solusi teknologi yang lebih inklusif. 

Berdasarkan permasalahan tersebut, EchoSense dirancang sebagai platform web berbasis kecerdasan buatan yang menerapkan pendekatan sonifikasi data untuk mengubah data lingkungan multidimensi menjadi _soundscape_ adaptif dan voice briefing kontekstual. Melalui kombinasi rule-based weighted scoring dan Large Language Model (Gemini API), EchoSense tidak sekadar memetakan data menjadi suara secara linear, melainkan memproses kombinasi parameter lingkungan secara simultan layaknya proses _mixing_ audio profesional, sehingga informasi yang disampaikan tetap jelas dan tidak tumpang tindih. 

Dari sisi implementasi, EchoSense dikembangkan menggunakan Laravel sebagai backend dan data aggregator, dipadukan dengan Blade Templating Engine, Web Audio API native, dan Web Speech API pada sisi _client_ untuk menghasilkan pengalaman audio yang responsif tanpa membebani server. Integrasi data dari OpenAQ, API BMKG, dan TomTom Traffic API memastikan informasi yang disampaikan bersumber dari data aktual dan tepercaya. Seluruh proses perancangan turut memprioritaskan prinsip accessibility by design dengan mengacu pada standar WCAG 2.1 Level AA, sehingga aksesibilitas tidak diposisikan sebagai fitur tambahan, melainkan menjadi fondasi utama dari keseluruhan sistem. 

# **4.2 Saran** 

Pengembangan EchoSense kedepannya disarankan untuk diarahkan pada perluasan cakupan wilayah di luar kota percontohan awal, mengingat ketersediaan sensor kualitas udara maupun data lalu lintas di sejumlah wilayah Indonesia masih terbatas, sehingga diperlukan strategi data cadangan yang lebih matang bagi daerah dengan ketersediaan data minim. Selain itu, sistem juga dapat dikembangkan untuk mengintegrasikan jenis data lingkungan lain di luar tiga parameter awal, seperti tingkat kebisingan atau kualitas air, guna memperkaya konteks informasi yang disampaikan kepada pengguna. Modul personalisasi profil pendengaran pun perlu dikembangkan lebih jauh dengan mempertimbangkan variasi sensitivitas pendengaran antar individu, sehingga pemetaan parameter suara dapat disesuaikan secara lebih presisi bagi masing-masing pengguna. 

Dari sisi validasi dan keberlanjutan, disarankan dilakukan pengujian _usability_ secara langsung bersama penyandang tunanetra, baik secara individu maupun melalui kerja sama dengan lembaga seperti SLB atau organisasi advokasi disabilitas, untuk memvalidasi efektivitas pemetaan suara (sonifikasi) dan kenyamanan pengalaman pengguna secara nyata, bukan hanya berdasarkan asumsi desain. Mengingat arsitektur EchoSense yang modular, pengembangan lanjutan juga dapat diarahkan untuk mengakomodasi kebutuhan kelompok disabilitas lain, seperti penyesuaian antarmuka bagi pengguna _low vision_ atau adaptasi bagi pengguna dengan gangguan pendengaran. Terakhir, kolaborasi dengan pemangku kepentingan seperti instansi penyedia data publik (BMKG, dinas lingkungan hidup, dinas perhubungan) perlu terus dijalin untuk memperoleh akses data yang lebih stabil dan mendalam, sekaligus mendukung terwujudnya inisiatif _smart city_ yang lebih inklusif bagi penyandang disabilitas. 

# **DAFTAR PUSTAKA** 

- Azzahra, A. H., & Safitri, D. (2024). _Peran Teknologi Non-Visual Desktop Access ( NVDA ) Untuk Siswa Tunanetra dalam Proses Pembelajaran_ . _4_ , 1–7. 

- Badan Pusat Statistik, (2024). Potret Penyandang Disabilitas di Indonesia Hasil Long Form Sensus Penduduk 2020 

- Putra, K. P. (2024). _Implementasi Sistem Pendukung Penyadang Tunanetra Dalam Mengidentifikasi Objek ( Vision )_ . _7_ (2), 178–181. 

- Sawe, N., Chafe, C., & Treviño, J. (2020). _Using Data Sonification to Overcome Science Literacy , Numeracy , and Visualization Barriers in Science Communication_ . _5_ (July), 1–7. https://doi.org/10.3389/fcomm.2020.00046 

# **LAMPIRAN** 

Lampiran Link prototype/website Lampiran Dokumentasi Desain 

Lampiran Surat Orisinalitas Karya (Template surat download  disini.) 

