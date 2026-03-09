<p align="center">
  <img src="https://img.shields.io/badge/Laravel-11-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" />
  <img src="https://img.shields.io/badge/Vue.js-3-4FC08D?style=for-the-badge&logo=vue.js&logoColor=white" />
  <img src="https://img.shields.io/badge/Python-3.10+-3776AB?style=for-the-badge&logo=python&logoColor=white" />
  <img src="https://img.shields.io/badge/Inertia.js-Powered-9E3ACF?style=for-the-badge" />
</p>

<h1 align="center">🏥 Medix — Sistem Monitor Kesehatan Cerdas</h1>

<p align="center">
  Aplikasi monitoring kesehatan berbasis AI yang memantau tekanan darah, gula darah, BMI, dan kondisi vital lainnya — dilengkapi analisis cerdas, edukasi kesehatan, dan chat AI berbasis Kemenkes RI.
</p>

---

## 📋 Daftar Isi

- [Kebutuhan Sistem](#kebutuhan-sistem)
- [Instalasi Awal](#instalasi-awal)
- [Cara Menjalankan Aplikasi](#cara-menjalankan-aplikasi)
- [Akses dari HP](#akses-dari-hp--perangkat-lain)
- [Fitur Lengkap](#fitur-lengkap)
- [Struktur Proyek](#struktur-proyek)

---

## 🖥️ Kebutuhan Sistem

| Kebutuhan | Versi |
|-----------|-------|
| PHP | 8.2+ |
| Composer | 2.x |
| Node.js | 18+ |
| Python | 3.10+ |
| Laragon / XAMPP | Terbaru |
| pip | Terbaru |

---

## 🚀 Instalasi Awal

> Lakukan **sekali saja** saat pertama kali menjalankan proyek.

### 1. Clone / Salin Proyek

```bash
git clone <url-repo> MEDIX
cd MEDIX
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

Buka `.env` dan sesuaikan:

```env
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=medix
DB_USERNAME=root
DB_PASSWORD=

# URL layanan AI Python (jangan diubah jika pakai default)
HEALTH_AI_URL=http://localhost:8001

# Opsional — untuk Chat AI berbasis Gemini
GEMINI_API_KEY=
```

### 4. Migrasi Database

```bash
php artisan migrate
```

### 5. Buat Storage Link

```bash
php artisan storage:link
```

### 6. Install Dependensi Frontend & Build

```bash
npm install
npm run build
```

---

## ▶️ Cara Menjalankan Aplikasi

Setiap kali ingin menggunakan Medix, ada **2 hal yang harus dijalankan**:

---

### Langkah 1 — Jalankan Python AI Service (`start.bat`)

Layanan AI wajib berjalan agar fitur Analisis AI dan Chat AI berfungsi.

**Cara termudah — klik dua kali:**

1. Buka File Explorer
2. Masuk ke folder `python-ai` di dalam direktori proyek
3. **Double-click** file `start.bat`

```
MEDIX/
└── python-ai/
    └── start.bat  ← double-click ini
```

**Atau via terminal:**

```bash
cd python-ai
start.bat
```
cd c:\laragon\www\MEDIX\python-ai
.\start.bat


Tunggu hingga muncul pesan:

```
Installing dependencies...
...
INFO:     Uvicorn running on http://0.0.0.0:8001 (Press CTRL+C to quit)
```

> ⚠️ **Jangan tutup jendela CMD ini** selama aplikasi digunakan.
>
> Saat pertama kali dijalankan, `start.bat` otomatis menginstall semua dependensi Python (`pip install -r requirements.txt`).

---

### Langkah 2 — Jalankan Laravel via Laragon

1. Buka aplikasi **Laragon**
2. Klik **Start All** (pastikan indikator Apache & MySQL hijau)
3. Buka browser dan akses:

```
http://localhost/MEDIX/public
```

Atau jika sudah dikonfigurasi virtual host:

```
http://medix.test
```

---

### ✅ Cheatsheet Harian

| # | Yang Dilakukan | Cara |
|---|---------------|------|
| 1 | Nyalakan Laragon | Klik **Start All** |
| 2 | Jalankan AI Service | Double-click `python-ai/start.bat` |
| 3 | Buka Aplikasi | Browser → `http://localhost/MEDIX/public` |

---

## 📱 Akses dari HP / Perangkat Lain

> HP dan PC **harus terhubung ke WiFi yang sama**.

---

### Langkah 1 — Cari IP Lokal PC

Buka **CMD** atau **PowerShell**, ketik:

```powershell
ipconfig
```

Cari `IPv4 Address` di bagian **Wi-Fi** atau **Ethernet**:

```
Wireless LAN adapter Wi-Fi:
   IPv4 Address. . . . . : 192.168.1.5   ← catat ini
```

---

### Langkah 2 — Izinkan Port di Windows Firewall

Buka **PowerShell sebagai Administrator**:

```powershell
# Izinkan Apache (port 80)
netsh advfirewall firewall add rule name="Laragon Apache" dir=in action=allow protocol=TCP localport=80

# Izinkan Python AI Service (port 8001)
netsh advfirewall firewall add rule name="Medix AI Service" dir=in action=allow protocol=TCP localport=8001
```

---

### Langkah 3 — Update APP_URL di `.env`

```env
APP_URL=http://192.168.1.5
```

> Ganti `192.168.1.5` dengan IP PC Anda.

Lalu:

```bash
php artisan config:clear
```

---

### Langkah 4 — Buka dari HP

Buka browser di HP, ketik:

```
http://192.168.1.5/MEDIX/public
```

Tampilan sudah responsive — optimal di layar HP.

---

### 🛠️ Troubleshooting Akses HP

| Masalah | Solusi |
|---------|--------|
| Tidak bisa konek | Pastikan HP & PC di WiFi yang **sama** |
| `ERR_CONNECTION_REFUSED` | Jalankan perintah firewall di Langkah 2 |
| Halaman error 500 | `php artisan config:clear` lalu refresh |
| AI tidak bekerja dari HP | Pastikan `start.bat` berjalan & port 8001 sudah dibuka |
| IP berubah setiap hari | Set IP statis di Pengaturan WiFi Windows |
| Tampilan SSL/HTTPS error | Gunakan `http://` bukan `https://` |

---

## ✨ Fitur Lengkap

| Fitur | URL | Keterangan |
|-------|-----|------------|
| Dashboard | `/dashboard` | Ringkasan kesehatan & grafik tren |
| Input Data | `/input-data` | Catat tekanan darah, gula darah, BMI, dll |
| Analisis AI | `/ai-analysis` | Analisis lengkap data kesehatan dengan AI |
| Riwayat | `/history` | Semua riwayat catatan kesehatan |
| Chat AI Kesehatan | `/ai-chat` | Tanya-jawab AI berbasis Kemenkes RI |
| Edukasi Kesehatan | `/edukasi` | Video edukasi dari Kemenkes, WHO, PERKENI |
| Standar Normal | `/standar-normal` | Nilai normal semua parameter kesehatan |
| Profil | `/profile` | Kelola akun pengguna |

**Fitur tambahan di Analisis AI:**
- 📄 **Unduh PDF** — simpan/cetak laporan analisis
- 💬 **Kirim WhatsApp** — bagikan laporan ke WA untuk konsultasi dokter
- 🤖 **Tanya AI** — lanjut ke halaman Chat AI

---

## 📁 Struktur Proyek

```
MEDIX/
├── app/Http/Controllers/    # Controller Laravel
├── app/Models/              # Model Eloquent
├── app/Services/            # GeminiService (AI bridge)
├── python-ai/
│   ├── start.bat            # ← JALANKAN INI untuk AI Service
│   ├── main.py              # FastAPI server (port 8001)
│   ├── analyzer.py          # Logika analisis & klasifikasi
│   └── requirements.txt     # Dependensi Python
├── resources/js/
│   ├── Pages/               # Halaman Vue (Inertia.js)
│   │   ├── Dashboard.vue
│   │   ├── AiAnalysis.vue
│   │   ├── AiChat.vue
│   │   ├── Edukasi.vue
│   │   └── StandarNormal.vue
│   └── Layouts/AppLayout.vue
├── routes/web.php           # Semua route aplikasi
└── .env                     # Konfigurasi environment
```

---

## 🔑 Akun Default (setelah seeder)

```bash
php artisan db:seed
```

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@medix.test | password |
| User | user@medix.test | password |

---

## 📚 Referensi Medis

- **Kemenkes RI** — Pedoman Klinis & Panduan Kesehatan Nasional
- **WHO** — World Health Organization Guidelines
- **PERKENI** — Perkumpulan Endokrinologi Indonesia (Diabetes)
- **PERKI** — Perhimpunan Dokter Spesialis Kardiovaskular Indonesia
- **AHA** — American Heart Association (Tekanan Darah)

> ⚠️ Informasi dari aplikasi ini bersifat **edukatif**, bukan pengganti konsultasi dengan dokter.
