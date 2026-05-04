# Lampiran 1.6 Dokumentasi Uji Coba Terbatas

## 1. Deskripsi Singkat Uji Coba

Uji coba ini dilakukan untuk memastikan fungsi utama aplikasi VITALY berjalan sesuai kebutuhan pengguna, menggunakan metode black box testing secara terbatas pada skenario-skenario inti. Pengujian berfokus pada fitur login, registrasi, input data kesehatan, sinkronisasi IoMT, analisis, dashboard, chat, konten edukasi, serta monitoring oleh Health Facilitator. Seluruh skenario dieksekusi berdasarkan masukan dan keluaran yang diharapkan tanpa menilai detail teknis di dalam sistem.

## 2. Dokumentasi Visual Pengujian

**Gambar 1. Pengujian Fitur Login**

[Tempatkan screenshot/foto pengujian login di sini]

**Gambar 2. Pengujian Input Data Kesehatan**

[Tempatkan screenshot/foto input data di sini]

**Gambar 3. Pengujian Analisis**

[Tempatkan screenshot/foto hasil analisis di sini]

**Gambar 4. Pengujian Dashboard Monitoring**

[Tempatkan screenshot dashboard di sini]

## 3. Tabel Hasil Pengujian Sistem

| No | Fitur | Skenario Uji | Input | Output yang Diharapkan | Output Aktual | Status | Dokumentasi |
|---|---|---|---|---|---|---|---|
| 1 | Login | Login dengan kredensial valid | Email terdaftar dan kata sandi benar | Pengguna masuk ke dashboard | Pengguna masuk ke dashboard | Berhasil | Lihat Gambar 1 |
| 2 | Login | Login dengan kata sandi salah | Email terdaftar dan kata sandi salah | Muncul pesan penolakan login | Muncul pesan penolakan login | Berhasil | Lihat Gambar 1 |
| 3 | Registrasi | Membuat akun baru | Nama, email baru, kata sandi | Akun baru tersimpan dan dapat login | Akun baru tersimpan dan dapat login | Berhasil | Lihat Gambar 1 |
| 4 | Input data kesehatan | Mengisi data tekanan darah dan gula darah | Nilai tekanan darah dan gula darah | Data tersimpan dan tampil di riwayat | Data tersimpan dan tampil di riwayat | Berhasil | Lihat Gambar 2 |
| 5 | Sinkronisasi IoMT | Sinkronisasi dari perangkat aktif | Perangkat terhubung dan mengirim data | Data masuk otomatis ke riwayat | Data masuk otomatis ke riwayat | Berhasil | Lihat Gambar 2 |
| 6 | Sinkronisasi IoMT | Sinkronisasi saat perangkat tidak aktif | Perangkat tidak terhubung | Muncul pesan gagal sinkronisasi | Tidak ada data masuk dan muncul pesan gagal | Gagal | Lihat Gambar 2 |
| 7 | Analisis | Melakukan analisis dari data terbaru | Riwayat data kesehatan terkini | Ringkasan analisis tampil | Ringkasan analisis tampil | Berhasil | Lihat Gambar 3 |
| 8 | Dashboard | Melihat ringkasan pemantauan | Pengguna membuka halaman dashboard | Grafik dan ringkasan tampil | Grafik dan ringkasan tampil | Berhasil | Lihat Gambar 4 |
| 9 | Chat | Konsultasi melalui chat | Pertanyaan terkait pola hidup | Respon informasi ditampilkan | Respon informasi ditampilkan | Berhasil | Lihat Gambar 3 |
| 10 | Konten edukasi | Membuka artikel edukasi | Memilih salah satu artikel | Artikel tampil lengkap | Artikel tampil lengkap | Berhasil | Lihat Gambar 4 |
| 11 | Monitoring oleh Health Facilitator | Melihat status pasien | Akun fasilitator membuka daftar pasien | Status dan catatan pasien terlihat | Status dan catatan pasien terlihat | Berhasil | Lihat Gambar 4 |
