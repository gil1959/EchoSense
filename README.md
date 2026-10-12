# EchoSense

EchoSense adalah aplikasi web asisten suara inklusif yang dirancang khusus untuk membantu penyandang tunanetra dan gangguan penglihatan dalam memahami kondisi lingkungan sekitar secara mandiri. Aplikasi ini menggabungkan kecerdasan buatan dan data sensor lingkungan untuk menyajikan informasi penting mengenai kualitas udara, cuaca, serta situasi lalu lintas melalui interaksi suara yang alami, hangat, dan bersahabat.

Pengguna tidak dituntut untuk melihat layar antarmuka visual. Begitu halaman web dibuka, asisten suara akan langsung menyapa, mendeteksi lokasi geografis pengguna, menghimpun data sensor lingkungan dari stasiun pemantauan terdekat, serta menyampaikannya secara lisan. Pengguna juga dapat berbincang secara interaktif untuk memeriksa kondisi lokasi tujuan maupun menanyakan situasi lingkungan terkini kapan saja.

## Cara Kerja Sistem

Cara kerja EchoSense dirancang runtut, cepat, dan mengutamakan aksesibilitas penuh bagi pengguna:

1. Inisialisasi Otomatis dan Sapaan Awal
Saat halaman web dimuat, sistem secara otomatis mengaktifkan asisten suara tanpa mengharuskan pengguna menekan tombol tertentu terlebih dahulu. Asisten suara menyapa pengguna dengan hangat, memperkenalkan fungsi aplikasi, serta memberikan petunjuk bahwa pengguna dapat berbicara setelah bunyi nada pengingat terdengar.

2. Pengambilan Data Lingkungan secara Paralel
Bersamaan dengan asisten suara yang sedang menyampaikan sapaan pengenalan, sistem di latar belakang secara paralel mendeteksi koordinat GPS pengguna dan mengambil data lingkungan dari berbagai stasiun pemantauan:
Kualitas udara dihimpun melalui stasiun OpenAQ untuk mengukur tingkat partikel debu halus.
Kondisi cuaca serta suhu lingkungan dihimpun melalui Open Meteo.
Kecepatan arus dan kepadatan lalu lintas dihimpun melalui TomTom Traffic.

3. Konfirmasi Lokasi Asal
Setelah sapaan selesai dibacakan dan data lokasi berhasil diperoleh, asisten suara menyebutkan nama wilayah keberadaan pengguna saat ini lalu menanyakan kebenaran lokasi tersebut. Sistem kemudian mengaktifkan mikrofon dan membunyikan nada halus agar pengguna dapat langsung mengonfirmasi dengan suara, misalnya dengan menjawab ya atau benar.

4. Penentuan Tujuan Perjalanan
Setelah lokasi asal terkonfirmasi, asisten suara menanyakan apakah pengguna memiliki rencana bepergian ke daerah lain. Pengguna dapat menjawab dengan menyebutkan nama kota atau tempat tujuan secara santai, misalnya mau ke Gambir atau menuju Bogor. Sistem memproses ucapan tersebut untuk menemukan titik koordinat serta kondisi lingkungan di lokasi tujuan.

5. Penyampaian Ringkasan Suara yang Ramah
Kecerdasan buatan Gemini merangkum seluruh parameter data menjadi narasi percakapan yang manis, santun, dan sarat perhatian. Sebagai contoh, jika cuaca hujan, asisten menyarankan pengguna membawa payung. Semua angka suhu, kecepatan kendaraan, dan indeks kualitas udara dieja menjadi kata terbilang penuh bahasa Indonesia agar terdengar fasih dan merdu melalui teknologi suara ElevenLabs.

6. Mode Pemantauan Aktif dan Peringatan Otomatis
Setelah ringkasan disampaikan, sistem masuk ke mode pemantauan berkala. Sistem terus mengawasi perubahan lingkungan sekitar. Apabila terjadi perubahan kondisi secara signifikan, misalnya cuaca memburuk menjadi badai atau kualitas udara melonjak ke level tidak sehat, asisten suara akan seketika memberikan peringatan lisan kepada pengguna.

7. Kemudahan Berbicara di Ponsel dan Komputer
Pengguna dapat berbicara dengan asisten kapan saja saat berada pada mode pemantauan. Pada ponsel pintar atau perangkat layar sentuh, pengguna cukup mengetuk sembarang area layar untuk mengaktifkan mikrofon dan mulai berbicara. Pada komputer, pengguna cukup menekan tombol Spasi pada papan ketik.

## Komponen Teknologi Utama

1. Kerangka Kerja Laravel 11 untuk arsitektur server, manajemen rute antarmuka, dan penyimpanan tembolok audio.
2. Tailwind CSS dan Atkinson Hyperlegible Next untuk tampilan visual kontras tinggi yang ramah aksesibilitas.
3. Google Gemini Flash untuk pemrosesan bahasa alami dan penyusunan narasi yang empatik.
4. ElevenLabs Text to Speech untuk sintesis suara manusia berkualitas tinggi.
5. Web Speech Recognition untuk mengenali ucapan lisan pengguna secara langsung di peramban.
6. OpenStreetMap Nominatim untuk geocoding nama wilayah menjadi titik koordinat.
7. OpenAQ, Open Meteo, dan TomTom Traffic sebagai sumber data kualitas udara, prakiraan cuaca, dan kepadatan lalu lintas.

## Panduan Menjalankan Aplikasi

1. Duplikasi berkas konfigurasi lingkungan dari template yang tersedia ke berkas dot env.
2. Lengkapi kunci API yang dibutuhkan pada konfigurasi lingkungan, yaitu kunci Gemini, ElevenLabs, TomTom, dan OpenAQ.
3. Pasang pustaka dependensi PHP dengan menjalankan composer install.
4. Buat kunci enkripsi aplikasi dengan menjalankan php artisan key:generate.
5. Jalankan server lokal menggunakan perintah php artisan serve.
6. Buka peramban web dan akses alamat http://localhost:8000 untuk mulai menggunakan aplikasi.
