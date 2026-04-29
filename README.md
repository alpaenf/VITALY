<p align="center">
  <img src="https://img.shields.io/badge/Laravel-11-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" />
  <img src="https://img.shields.io/badge/Vue.js-3-4FC08D?style=for-the-badge&logo=vue.js&logoColor=white" />
  <img src="https://img.shields.io/badge/Python-3.10+-3776AB?style=for-the-badge&logo=python&logoColor=white" />
  <img src="https://img.shields.io/badge/Inertia.js-Powered-9E3ACF?style=for-the-badge" />
</p>

<h1 align="center">VITALY - Sistem Monitor Kesehatan Cerdas</h1>

<p align="center">
  Platform monitoring kesehatan berbasis AI untuk Health Agent dan pasien. VITALY memusatkan data vital (tensi, gula darah, IMT, dan metrik lain), menghasilkan analisis AI, edukasi, serta alur rujukan dan tindak lanjut yang cepat.
</p>

---

## Daftar Isi

- [Ringkasan Produk](#ringkasan-produk)
- [Fitur Unggulan](#fitur-unggulan)
- [Fitur Lengkap](#fitur-lengkap)
- [Tutorial Cara Kerja Aplikasi](#tutorial-cara-kerja-aplikasi)
- [Kebutuhan Sistem](#kebutuhan-sistem)
- [Instalasi Awal](#instalasi-awal)
- [Cara Menjalankan Aplikasi](#cara-menjalankan-aplikasi)
- [Akses dari HP](#akses-dari-hp--perangkat-lain)
- [Struktur Proyek](#struktur-proyek)
- [LKTI Draft Helper](#lkti-draft-helper)
- [Referensi Medis](#referensi-medis)

---

## Ringkasan Produk

VITALY adalah sistem monitoring kesehatan cerdas yang menargetkan percepatan deteksi dini, triase, dan tindak lanjut. Sistem ini menghubungkan Health Agent dengan data pasien secara rapi, memadukan pencatatan manual dan integrasi perangkat (smartwatch dan tensimeter Bluetooth), serta menampilkan analisis AI sebagai bahan keputusan.

Peran utama di aplikasi:
- **Pasien**: melihat data kesehatan pribadi, memahami hasil, dan mengikuti rekomendasi.
- **Health Agent**: mengelola pasien, input data pemeriksaan, melihat tren, dan menindaklanjuti risiko.
- **Admin**: pengelolaan akun dan monitoring sistem.

---

## Fitur Unggulan

1. **Integrasi Smartwatch & IoMT**
  - Pairing perangkat sekali, data vital sinkron otomatis pada pemeriksaan berikutnya.
  - Mengurangi input manual dan error pencatatan.
2. **Smart Triage (Prioritas Risiko)**
  - Sistem membantu menandai pasien berisiko agar ditangani lebih cepat.
3. **Analisis AI dan Ringkasan Klinis**
  - Merangkum kondisi pasien untuk mempermudah pengambilan keputusan.
4. **Rujukan & Tindak Lanjut Cepat**
  - Dokumen rujukan dan laporan analisis siap dibagikan.
5. **Offline-First Sync**
  - Aplikasi tetap usable pada koneksi minim, lalu sinkron saat online.

---

## Fitur Lengkap

### Modul Utama (Health Agent)
- **Dashboard**: ringkasan kondisi, indikator risiko, tren.
- **Data Pasien**: pencarian, detail pasien, dan histori pemeriksaan.
- **Input Pemeriksaan**: pencatatan tekanan darah, gula darah, IMT, suhu, SpO2, dan metrik vital lain.
- **Analisis AI**: ringkasan kondisi, rekomendasi tindak lanjut.
- **Riwayat**: arsip data pemeriksaan per pasien.

### Modul Pasien
- **Cek Data Kesehatan**: akses data pribadi berbasis NIK.
- **Ringkasan & Edukasi**: pemahaman hasil dan materi edukasi.

### Modul Umum
- **Chat AI**: tanya jawab seputar kesehatan.
- **Edukasi Kesehatan**: konten video edukatif.
- **Standar Normal**: referensi nilai normal parameter kesehatan.
- **Profil**: pengelolaan akun pengguna.

### Output & Distribusi Informasi
- **Unduh PDF**: laporan pemeriksaan dan analisis.
- **Bagikan WhatsApp**: ringkas laporan untuk kolaborasi tindak lanjut.

---

## Tutorial Cara Kerja Aplikasi

### A. Alur untuk Health Agent (pemeriksaan pasien)
1. **Masuk ke Dashboard**
  - Login sebagai Health Agent.
2. **Cari atau Tambah Pasien**
  - Gunakan pencarian atau buat data pasien baru.
3. **Input Data Pemeriksaan**
  - Catat metrik vital (tensi, gula darah, IMT, suhu, SpO2, dan lainnya).
  - Jika perangkat IoMT tersedia, lakukan pairing dan sinkronisasi otomatis.
4. **Analisis AI**
  - Sistem menghasilkan ringkasan kondisi dan rekomendasi tindak lanjut.
5. **Rujukan dan Distribusi Laporan**
  - Unduh PDF atau bagikan ringkasan ke WhatsApp.
6. **Pantau Riwayat**
  - Lihat tren dan perubahan kondisi per pasien.

### B. Alur untuk Pasien
1. **Masuk dengan NIK**
  - Pasien melihat data kesehatan pribadi.
2. **Baca Ringkasan**
  - Memahami status kesehatan dan rekomendasi.
3. **Akses Edukasi**
  - Menonton video atau membaca standar normal.

### C. Cara Kerja Smartwatch (IoMT)
1. **Pairing perangkat**
  - Health Agent menghubungkan smartwatch/tensimeter via Bluetooth.
2. **Sinkron data otomatis**
  - Data vital ditarik langsung ke profil pasien.
3. **Validasi & analisis**
  - Sistem menampilkan data terbaru dan menjalankan analisis AI.

---

## Kebutuhan Sistem

| Kebutuhan | Versi |
|-----------|-------|
| PHP | 8.2+ |
| Composer | 2.x |
| Node.js | 18+ |
| Python | 3.10+ |
| Laragon / XAMPP | Terbaru |
| pip | Terbaru |

---

## Instalasi Awal

> Lakukan sekali saat setup awal.

### 1. Clone / Salin Proyek

```bash
git clone <url-repo> Vitaly
cd Vitaly
```

### 2. Install Dependensi PHP

```bash
composer install
```

### 3. Konfigurasi Environment

```bash
copy .env.example .env
php artisan key:generate
```

Contoh konfigurasi `.env`:

```env
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=Vitaly
DB_USERNAME=root
DB_PASSWORD=

# URL layanan AI Python (default)
HEALTH_AI_URL=http://localhost:8001

# Opsional untuk Chat AI berbasis Gemini
GEMINI_API_KEY=
```

### 4. Migrasi Database

```bash
php artisan migrate
```

### 5. Storage Link

```bash
php artisan storage:link
```

### 6. Dependensi Frontend & Build

```bash
npm install
npm run build
```

---

## Cara Menjalankan Aplikasi

Setiap kali ingin menggunakan VITALY, ada **2 layanan** yang harus aktif:

### Langkah 1 - Jalankan Python AI Service (`start.bat`)

1. Buka folder `python-ai`.
2. Jalankan `start.bat`.

```bash
cd python-ai
start.bat
```

Tunggu hingga muncul pesan:

```
INFO:     Uvicorn running on http://0.0.0.0:8001
```

> Jangan tutup jendela ini selama aplikasi digunakan.

### Langkah 2 - Jalankan Laravel via Laragon

1. Buka Laragon dan klik **Start All**.
2. Akses aplikasi:

```
http://localhost/Vitaly/public
```

Jika memakai virtual host:

```
http://vitaly.test
```

---

## Akses dari HP / Perangkat Lain

> PC dan HP harus berada pada jaringan WiFi yang sama.

### Langkah 1 - Cek IP Lokal PC

```powershell
ipconfig
```

Catat `IPv4 Address`, contoh: `192.168.1.5`.

### Langkah 2 - Buka Port di Firewall

```powershell
netsh advfirewall firewall add rule name="Laragon Apache" dir=in action=allow protocol=TCP localport=80
netsh advfirewall firewall add rule name="VITALY AI Service" dir=in action=allow protocol=TCP localport=8001
```

### Langkah 3 - Update APP_URL

```env
APP_URL=http://192.168.1.5
```

Lalu:

```bash
php artisan config:clear
```

### Langkah 4 - Akses dari HP

```
http://192.168.1.5/Vitaly/public
```

---

## Struktur Proyek

```
Vitaly/
+-- app/Http/Controllers/    # Controller Laravel
+-- app/Models/              # Model Eloquent
+-- app/Services/            # GeminiService (AI bridge)
+-- python-ai/
|   +-- start.bat            # Jalankan untuk AI Service
|   +-- main.py              # FastAPI server (port 8001)
|   +-- analyzer.py          # Logika analisis
|   +-- requirements.txt     # Dependensi Python
+-- resources/js/
|   +-- Pages/               # Halaman Vue (Inertia.js)
|   +-- Layouts/
+-- routes/web.php           # Route aplikasi
+-- .env                     # Konfigurasi environment
```

---

## LKTI Draft Helper

Bagian ini dirancang agar mudah disalin ke GPT untuk membantu menyusun dokumen LKTI.

**Judul singkat produk:**
VITALY - Sistem Monitor Kesehatan Cerdas berbasis AI untuk Health Agent dan pasien.

**Latar belakang masalah (ringkas):**
Pencatatan kesehatan masih manual, data pasien tersebar, deteksi dini terlambat, dan tindak lanjut sering tidak terstandarisasi. Diperlukan sistem terintegrasi yang mempercepat pencatatan, analisis, dan rujukan.

**Tujuan solusi:**
1. Mempercepat pengumpulan data vital dan mengurangi human error.
2. Menyediakan analisis AI sebagai ringkasan kondisi.
3. Meningkatkan respons tindak lanjut melalui triase dan rujukan cepat.
4. Memudahkan edukasi dan literasi kesehatan pasien.

**Inovasi utama:**
- Integrasi IoMT (smartwatch dan tensimeter Bluetooth) untuk input otomatis.
- Smart triage untuk prioritas risiko.
- Laporan analisis AI yang mudah dibagikan.
- Offline-first sync untuk area dengan koneksi terbatas.

**Skema alur kerja:**
1. Pasien didata -> input metrik vital -> sinkron perangkat -> analisis AI.
2. Health Agent menerima ringkasan -> menentukan tindak lanjut -> distribusi laporan.
3. Pasien mengakses ringkasan dan edukasi.

**Dampak yang diharapkan:**
- Efisiensi waktu pemeriksaan.
- Peningkatan akurasi data.
- Deteksi dini lebih cepat.
- Kolaborasi tindak lanjut lebih baik.

**Kata kunci teknis:**
Laravel 11, Vue 3, Inertia.js, FastAPI, Python AI Service, PWA, IoMT, WhatsApp sharing.

**Paragraf kesesuaian tema (SDG 3 dan Indonesia Emas 2045):**
VITALY sejalan dengan tema inovasi kesehatan berbasis teknologi karena memanfaatkan AI, IoMT, dan sistem digital terintegrasi untuk mempercepat deteksi dini, meningkatkan efisiensi layanan, serta memperluas akses informasi kesehatan. Dengan memperbaiki kualitas data, mempercepat pengambilan keputusan, dan mendorong literasi kesehatan, VITALY mendukung SDG 3 (Good Health and Well-being) sekaligus berkontribusi pada transformasi kesehatan digital menuju Indonesia Emas 2045.

**Mapping fitur -> SDG 3 -> Indonesia Emas 2045:**
| Fitur | Kontribusi SDG 3 | Kontribusi Indonesia Emas 2045 |
|---|---|---|
| Integrasi IoMT (smartwatch, tensimeter) | Peningkatan pemantauan dini dan akurasi data pasien | Fondasi ekosistem kesehatan digital yang terukur dan berbasis data |
| Smart triage (prioritas risiko) | Mengurangi keterlambatan penanganan kasus berisiko | Efisiensi layanan kesehatan dan produktivitas SDM kesehatan |
| Analisis AI & ringkasan klinis | Dukungan keputusan klinis lebih cepat dan tepat | Peningkatan mutu layanan kesehatan berbasis AI |
| Laporan PDF & sharing WhatsApp | Continuity of care dan kolaborasi tindak lanjut | Akselerasi layanan kesehatan terhubung lintas peran |
| Offline-first sync | Akses layanan di wilayah keterbatasan konektivitas | Pemerataan layanan kesehatan digital nasional |

**Metodologi evaluasi dampak (indikator terukur):**
- Waktu rata-rata input pemeriksaan (menit) sebelum vs sesudah IoMT.
- Tingkat kelengkapan data pasien (% field terisi per kunjungan).
- Waktu respon tindak lanjut kasus prioritas (jam/hari).
- Frekuensi rujukan tepat waktu (jumlah rujukan per kasus berisiko).
- Peningkatan kepatuhan pemantauan (jumlah kunjungan/rekam per pasien).

**Tantangan & mitigasi:**
- Keterbatasan koneksi internet -> Offline-first sync dan auto-sync saat online.
- Variasi akurasi perangkat -> Validasi input dan standar perangkat kompatibel.
- Literasi digital pengguna -> Pelatihan singkat, UI sederhana, dan panduan visual.
- Privasi data -> Role-based access dan pembatasan akses data pasien.

---

## Referensi Medis

- Kemenkes RI - Pedoman Klinis & Panduan Kesehatan Nasional
- WHO - World Health Organization Guidelines
- PERKENI - Perkumpulan Endokrinologi Indonesia (Diabetes)
- PERKI - Perhimpunan Dokter Spesialis Kardiovaskular Indonesia
- AHA - American Heart Association (Tekanan Darah)

> Informasi dari aplikasi ini bersifat edukatif dan tidak menggantikan konsultasi medis profesional.
