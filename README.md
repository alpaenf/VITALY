# VITALY — Platform Monitoring Kesehatan Berbasis IoMT

> **Versi:** 2.0 (IoMT Edition)
> **Stack:** Laravel 11 · Vue 3 (Inertia.js) · Vite · TailwindCSS · Gemini AI
> **Konteks:** Karya Tulis Ilmiah (KTI) — Sistem Kesehatan Digital Berbasis Internet of Medical Things

---

## 🧠 Ringkasan Sistem

VITALY adalah platform monitoring kesehatan masyarakat yang mengintegrasikan **IoMT (Internet of Medical Things)** berbasis smartwatch (Mi Band 8) dengan **analisis kecerdasan buatan (Google Gemini AI)**. Sistem ini dirancang untuk memberdayakan masyarakat agar bisa memantau kondisi kesehatannya secara mandiri, sekaligus membantu tenaga kesehatan (Health Agent) dalam mencatat dan menganalisis data vital pasien.

---

## 👥 Peran Pengguna (Roles)

| Role | Sebutan di UI | Akses |
|------|--------------|-------|
| `user` (Pasien) | Pasien | Dashboard pribadi, riwayat, AI chat, input mandiri |
| `kader` *(internal DB)* | **Health Agent** | Kelola pasien, input data, lihat analisis AI |
| `admin` | Admin | Kelola Health Agent, kelola pasien, knowledge base AI |

### Alur Login per Role:
```
Pasien / Tamu  → /login → "Saya Pasien" → Google OAuth → /masuk (input NIK)
Health Agent   → /login → "Saya Health Agent" → Email + Password / Google OAuth
Admin          → /login → "Saya Health Agent / Admin" → Email + Password
```

---

## 🗂️ Arsitektur Aplikasi

```
VITALY/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/AuthController.php       ← Google OAuth + Login biasa
│   │   │   ├── PatientController.php         ← Lookup NIK + Daftar Mandiri
│   │   │   ├── SelfInputController.php       ← Input data mandiri oleh pasien
│   │   │   ├── DashboardController.php       ← Dashboard pasien
│   │   │   ├── HealthRecordController.php    ← Riwayat data kesehatan
│   │   │   ├── AiAnalysisController.php      ← Analisis AI Gemini
│   │   │   ├── AiChatController.php          ← Chatbot VITALY Smart Assistant
│   │   │   ├── EdukasiController.php         ← Konten edukasi + YouTube API
│   │   │   ├── StandarNormalController.php   ← Referensi nilai normal
│   │   │   ├── Kader/
│   │   │   │   ├── KaderDashboardController.php
│   │   │   │   └── KaderPatientController.php  ← CRUD pasien + input data
│   │   │   └── Admin/
│   │   │       ├── AdminDashboardController.php
│   │   │       ├── AdminUserController.php     ← Kelola pasien (admin)
│   │   │       ├── AdminKaderController.php    ← Kelola Health Agent
│   │   │       └── AiKnowledgeController.php   ← Knowledge base AI
│   ├── Models/
│   │   ├── User.php          ← Health Agent & Admin (role: kader/admin)
│   │   ├── Patient.php       ← Data pasien (NIK, nama, dll + self_registered)
│   │   ├── HealthRecord.php  ← Record vital: TD, BPM, SpO2, gula darah, dll
│   │   └── AiAnalysis.php    ← Hasil analisis AI
│   └── Services/
│       └── GeminiService.php ← Integrasi Google Gemini API (analisis + chat)
│
├── resources/js/
│   ├── Pages/
│   │   ├── Landing.vue              ← Halaman utama publik
│   │   ├── Auth/Login.vue           ← Halaman login (multi-role)
│   │   ├── PatientLookup.vue        ← Masuk via NIK (pasien)
│   │   ├── SelfRegister.vue         ← Daftar mandiri pasien baru (3 langkah)
│   │   ├── SelfInput.vue            ← Input data mandiri pasien
│   │   ├── Dashboard.vue            ← Dashboard pasien (IoMT-aware)
│   │   ├── AiAnalysis.vue           ← Halaman analisis AI
│   │   ├── AiChat.vue               ← Chatbot VITALY Smart Assistant
│   │   ├── Edukasi.vue              ← Video edukasi kesehatan
│   │   ├── StandarNormal.vue        ← Referensi nilai normal
│   │   ├── Kader/
│   │   │   ├── Dashboard.vue        ← Dashboard Health Agent
│   │   │   ├── Patients.vue         ← Daftar pasien + pairing smartwatch
│   │   │   ├── PatientDetail.vue    ← Detail + riwayat pasien
│   │   │   └── InputData.vue        ← Input data pasien (manual + sync BLE)
│   │   └── Admin/
│   │       ├── Dashboard.vue
│   │       ├── Kaders.vue           ← Kelola Health Agent (UI: "Health Agent")
│   │       └── UserDetail.vue
│   ├── Layouts/
│   │   ├── AppLayout.vue            ← Layout pasien (sidebar + bottom nav mobile)
│   │   ├── KaderLayout.vue          ← Layout Health Agent
│   │   ├── AdminLayout.vue          ← Layout Admin
│   │   └── AuthLayout.vue           ← Layout halaman auth (split-screen)
│   └── Services/
│       └── BluetoothService.js      ← 🔑 Layanan BLE / IoMT (lihat bawah)
│
├── routes/web.php                   ← Semua route (pasien, Health Agent, admin, daftar)
└── database/migrations/             ← Schema DB
```

---

## 📍 Konteks & Studi Kasus

### Skenario Utama: "Kegiatan Pemeriksaan Kesehatan Massal"

VITALY dirancang sebagai **Platform Skrining Kesehatan Mobile** yang dapat digunakan di berbagai kegiatan pemeriksaan kesehatan, bukan hanya terbatas pada posyandu. Contoh skenario nyata:

| Konteks | Peran Health Agent | Peran Peserta |
|---------|-------------------|---------------|
| 🎓 **Bakti Sosial Kampus** | Mahasiswa Keperawatan / Kesehatan Masyarakat | Peserta event / civitas akademika |
| 🏢 **Medical Check-Up Perusahaan** | Petugas kesehatan perusahaan | Karyawan |
| 🏘️ **Posyandu / Puskesmas Keliling** | Kader/Nakes desa | Warga setempat |
| 🏟️ **Pameran / Expo Kesehatan** | Relawan medis | Pengunjung umum |

### Mengapa Konteks Event/Kampus Lebih Kuat?

Kegiatan skrining kesehatan massal (baksos, medical check-up, dll) memiliki dua masalah klasik:

1. **Antrian & Penumpukan Data** — Banyak peserta, data dicatat manual di kertas, rentan hilang atau salah ketik.
2. **Hasil Tidak Berkelanjutan** — Peserta cek tensi hari ini, pulang, lupa hasilnya minggu depan. Tidak ada monitoring lanjutan.

VITALY menyelesaikan keduanya sekaligus:

```
Peserta scan QR / buka link VITALY
        ↓
Daftar Mandiri (30 detik via Google)
        ↓
Pairing Mi Band 8 di booth → data vital tersinkron otomatis
        ↓
Gemini AI analisis langsung → hasil tampil di HP peserta
        ↓
Peserta pulang, dashboard tetap aktif → monitoring berlanjut
        ↓
Health Agent bisa pantau perkembangan peserta dari portal
```

---

## 🤔 Mengapa VITALY? (Inovasi vs Sistem Konvensional)

VITALY lahir dari evaluasi kritis terhadap sistem monitoring kesehatan tradisional yang masih bersifat **reaktif, terpusat, dan tidak berkelanjutan**. VITALY melakukan tiga lompatan fundamental:

### 1. Dari "Buku Catatan Digital" → Sistem Monitoring IoMT Aktif
Sistem sebelumnya hanya memindahkan kertas ke layar — data tetap diinput manual setelah kejadian. VITALY menghilangkan proses itu. Data mengalir **real-time langsung dari tubuh peserta** via Mi Band 8 ke database, tanpa perantara manual yang rawan *human error*. Ini adalah perbedaan antara *mencatat kesehatan* vs *merasakan kesehatan*.

### 2. Dari Sentralisasi Petugas → Otonomi Digital Peserta
Dulu, data kesehatan seseorang "terkunci" di tangan petugas. Peserta tidak bisa mengakses, memperbarui, atau memahami datanya sendiri. VITALY menghancurkan tembok ini dengan fitur **Self-Registration & Self-Input**. Peserta bukan lagi objek yang "diperiksa", melainkan subjek yang aktif mengelola kesehatannya secara digital — bahkan setelah event selesai.

### 3. Dari Angka → Interpretasi AI yang Kontekstual
Cek tensi 140/90? Sistem lama hanya simpan angka. VITALY menggunakan **Gemini AI** sebagai "dokter virtual" yang memberikan interpretasi kontekstual: apa artinya, apa risikonya, apa yang harus dilakukan, dan video edukasi apa yang relevan — semua otomatis, berbahasa Indonesia, berbasis standar Kemenkes RI.

---

## 👥 Dua Jalur Pendaftaran (Inklusivitas Digital)

VITALY dirancang agar tidak meninggalkan siapa pun — dari peserta event yang melek teknologi hingga lansia yang butuh bantuan petugas.

| Jalur | Pendekatan | Alur |
|-------|------------|------|
| **Independent Flow** *(Mandiri)* | Peserta daftar & input sendiri | Scan QR → Daftar via Google → Sync Mi Band → Lihat hasil AI di HP sendiri |
| **Assisted Flow** *(Dibantu Petugas)* | Health Agent catat untuk peserta | Petugas input data di portal → Sistem simpan → Peserta bisa akses via NIK |

---


## 🔑 Fitur Utama & Alur Kerja

### 1. Pendaftaran Pasien Mandiri (Self-Registration)

Pasien baru bisa mendaftar sendiri tanpa perlu ke posyandu:

```
/login → "Saya Pasien" → "Daftar Mandiri (Pasien Baru)"
    ↓
[Langkah 1] Verifikasi Google OAuth
    ↓
[Langkah 2] Isi form: NIK, Nama, TTL, Gender, HP, Alamat
    ↓  POST /daftar → PatientController@register
    ↓  Patient dibuat dengan flag: self_registered = true
[Langkah 3] Pairing Smartwatch (opsional via BluetoothService)
    ↓
Auto-login sebagai pasien → /masuk (gunakan NIK yang sudah didaftarkan)
```

**File terkait:**
- `SelfRegister.vue` — Form 3 langkah
- `PatientController::showRegister()` + `register()` + `saveDevice()`
- `AuthController::redirectToGoogleRegister()` + callback `register_` flow
- Route: `GET/POST /daftar`, `GET /auth/google/register`

### 2. Login Pasien (Returning User)

```
/masuk → Input NIK 16 digit → session('patient_id') disimpan → /dashboard
```

> **Pasien mandiri** login dengan NIK yang sama saat mendaftar.
> Tidak perlu Google lagi setelah terdaftar.

### 3. Input Data Mandiri Pasien

Pasien bisa mengisi data vital sendiri atau sync dari smartwatch:

```
/dashboard (belum ada data) → [Sync Smartwatch] atau [Input Manual]
    ↓                                ↓
syncVitalData() via BLE     GET /input-mandiri (SelfInput.vue)
    ↓ POST /input-mandiri           ↓ POST /input-mandiri
    SelfInputController@store → HealthRecord disimpan → reload dashboard
```

**Data yang bisa diinput:**
- Tekanan Darah (Sistolik/Diastolik) — bisa dari BLE
- Detak Jantung (BPM) — bisa dari BLE
- SpO2 / Oksigen Saturasi — bisa dari BLE
- Gula Darah — manual
- Suhu Tubuh — manual
- Berat & Tinggi Badan — manual

### 4. Health Agent Input Data Pasien

```
/kader/pasien → pilih pasien → /kader/pasien/{id}/input
    ↓
InputData.vue → Form vital + tombol "Sync dari Smartwatch"
    ↓ Sync via syncVitalData() di BluetoothService.js
    ↓ Data di-autofill, badge "Data Terverifikasi Otomatis" muncul
    ↓ Jika full sync → konfirmasi manual disembunyikan
    ↓ Jika manual → checkbox konfirmasi wajib dicentang
    ↓ POST /kader/pasien/{id}/input
    KaderPatientController@storeInput → HealthRecord
```

> **Catatan:** URL `/kader/...` adalah nama teknis di backend (tidak diubah agar tidak merusak sistem). Tampilan UI tetap menampilkan **"Health Agent"**.

### 5. Analisis AI (Gemini)

```
Pasien/Health Agent klik "Analisis AI"
    ↓ POST /ai-analysis
    AiAnalysisController → GeminiService::analyze($patient, $record)
    ↓
GeminiService membangun prompt dengan:
  - Data identitas pasien (usia, gender)
  - Semua nilai vital terbaru
  - Label sumber data: [SUMBER: IoMT/Smartwatch] atau [SUMBER: Manual]
  - Knowledge base dari tabel ai_knowledge
  - Instruksi persona: dokter IoMT Indonesia
    ↓
Gemini API mengembalikan:
  - Interpretasi kondisi kesehatan
  - Rekomendasi tindakan
  - Link video YouTube edukatif yang relevan
  - Saran waktu kontrol ulang
```

### 6. VITALY Smart Assistant (Chatbot)

```
/ai-chat → AiChat.vue
    ↓ POST /ai-chat/message
    AiChatController → GeminiService::chat($messages)
    ↓
Chatbot berkepribadian "VITALY Smart Assistant":
  - Menjawab pertanyaan kesehatan berbasis Kemenkes RI
  - Menjelaskan cara koneksi Mi Band 8 ke VITALY
  - Memberikan link video edukasi yang relevan
  - Melabeli sumber data IoMT vs manual
```

**Quick Questions di UI:**
- "Tensi 130/85 itu bahaya?"
- "Cara cepat turunin gula darah?"
- "Cara hubungkan Mi Band 8 ke VITALY?"
- "Manfaat pantau kesehatan pakai smartwatch?"

### 7. Konten Edukasi

```
/edukasi → EdukasiController@index
    ↓
Jika YOUTUBE_API_KEY tersedia di .env:
    → Fetch video live dari YouTube per kategori
Jika tidak ada API Key:
    → Tampilkan video statis (fallback)
```

**Kategori video:**
| ID | Label |
|----|-------|
| `heart` | Jantung & TD |
| `diabetes` | Gula Darah |
| `nutrition` | Gizi & IMT |
| `lifestyle` | Gaya Hidup |
| `mental` | Kesehatan Mental |
| `device` | IoMT & Teknologi ← *kategori baru* |

---

## ⚡ BluetoothService.js — Layanan IoMT BLE

File: `resources/js/Services/BluetoothService.js`

### Export yang tersedia:

| Fungsi | Kegunaan |
|--------|---------|
| `isBluetoothSupported()` | Cek apakah browser support Web Bluetooth |
| `isHttps()` | Cek apakah halaman berjalan di HTTPS |
| `getBluetoothStatus()` | Return `{ canUse, reason, message }` — cek lengkap |
| `pairDevice(onStatus)` | Buka dialog pairing, return `{ deviceId, deviceName }` |
| `syncVitalData(onStatus)` | Baca data vital dari BLE, return objek data |
| `formatDeviceId(id)` | Format device ID untuk tampilan |

### Logika Fallback:

```
syncVitalData() dipanggil
    ↓
getBluetoothStatus()
    ├── HTTP (bukan HTTPS)? → pesan error + simulasi
    ├── Browser tidak support? → pesan error + simulasi
    ├── Bluetooth hardware off? → pesan error + simulasi
    └── Semua OK → requestDevice() → GATT connect → baca data real
                        ↓ gagal?
                    simulateVitalData() ← data demo realistis
```

### Syarat Agar BLE Asli Bekerja:

1. **HTTPS wajib** — Web Bluetooth API diblokir di HTTP
2. **Chrome atau Edge** — Firefox & Safari tidak support
3. **Bluetooth aktif** di perangkat user
4. **Smartwatch dalam mode discoverable**

### Protokol Mi Band 8:

Mi Band 8 menggunakan protokol **Zepp OS proprietary** + GATT standard:
- Heart Rate dibaca via GATT standard (`0x180D` + `0x2A37`) ✅
- Blood Pressure via GATT notifications ✅
- Data lain (langkah, tidur) butuh auth Zepp token ⚠️

---

## 🗃️ Database Schema (Tabel Utama)

### `patients`
```sql
id, nik (unique 16 digit), name, date_of_birth, gender,
phone, address, device_id (smartwatch ID),
google_email, self_registered (bool), timestamps
```

### `health_records`
```sql
id, patient_id (FK), recorded_by (FK users),
systolic, diastolic,          -- Tekanan Darah (mmHg)
heart_rate,                   -- Detak Jantung (bpm)
oxygen_saturation,            -- SpO2 (%)
blood_sugar,                  -- Gula Darah (mg/dL)
temperature,                  -- Suhu (°C)
weight, height,               -- BB (kg), TB (cm)
respiratory_rate,             -- Laju napas (x/menit)
notes,                        -- [SUMBER: IoMT] / [SUMBER: Manual]
recorded_at, timestamps
```

### `users` (Health Agent & Admin)
```sql
id, name, email, password,
role ENUM('kader','admin'),   -- 'kader' = Health Agent di backend (nama teknis)
google_id, avatar, gender, date_of_birth, phone, timestamps
```

### `ai_analyses`
```sql
id, patient_id, health_record_id, result (text), timestamps
```

---

## 🌐 Route Map Penting

```
GET  /                      → Landing page
GET  /login                 → Halaman login multi-role
GET  /auth/google           → Google OAuth (Health Agent)
GET  /auth/google/tamu      → Google OAuth (Pasien/Tamu)
GET  /auth/google/register  → Google OAuth (Daftar Mandiri)
GET  /auth/google/callback  → Callback handler semua flow

GET  /masuk                 → PatientLookup (input NIK)
POST /masuk                 → Proses lookup NIK → session patient_id
GET  /daftar                → Form daftar mandiri (SelfRegister)
POST /daftar                → Simpan pasien baru (self_registered=true)
POST /daftar/device         → Simpan device_id smartwatch

-- PATIENT SESSION (middleware: patient.session) --
GET  /dashboard             → Dashboard pasien
GET  /history               → Riwayat data
GET  /ai-analysis           → Analisis AI
POST /ai-analysis           → Jalankan analisis
GET  /ai-chat               → Chatbot
POST /ai-chat/message       → Kirim pesan chatbot
GET  /input-mandiri         → Form input mandiri
POST /input-mandiri         → Simpan data mandiri

-- HEALTH AGENT (middleware: auth + role:kader — tampil sebagai "Health Agent" di UI) --
GET  /kader/dashboard
GET  /kader/pasien                    → Daftar pasien
POST /kader/pasien                    → Tambah pasien
GET  /kader/pasien/{id}               → Detail pasien
GET  /kader/pasien/{id}/input         → Form input data
POST /kader/pasien/{id}/input         → Simpan data
POST /kader/pasien/{id}/analyze       → Analisis AI

-- ADMIN (middleware: auth + role:admin) --
GET  /admin/dashboard
GET  /admin/patients
GET  /admin/kaders                    → Kelola Health Agent (UI: "Health Agent")
GET  /admin/knowledge                 → Knowledge base AI

-- PUBLIC --
GET  /edukasi               → Konten edukasi
GET  /standar-normal        → Referensi nilai normal
```

---

## ⚙️ Konfigurasi `.env` Penting

```env
# Database
DB_CONNECTION=mysql
DB_DATABASE=vitaly

# Google OAuth (wajib untuk login)
GOOGLE_CLIENT_ID=...
GOOGLE_CLIENT_SECRET=...
GOOGLE_REDIRECT_URI=https://your-domain.com/auth/google/callback

# Google Gemini AI (wajib untuk analisis & chatbot)
GEMINI_API_KEY=...

# YouTube Data API v3 (opsional — untuk video edukasi live)
YOUTUBE_API_KEY=...

# App
APP_URL=https://your-domain.com
APP_ENV=production
```

---

## 📱 Responsivitas Mobile

Semua halaman dirancang **mobile-first** menggunakan Tailwind CSS:
- Bottom navigation bar (capsule style) untuk pasien di mobile
- Sidebar collapse di Health Agent mobile
- Form grid adaptif: `grid-cols-2` → `grid-cols-1` di layar kecil
- Hero section dengan animasi wave divider

---

## 🤖 GeminiService.php — Prompt AI

File: `app/Services/GeminiService.php`

Prompt sistem yang dikirim ke Gemini mencakup:
1. **Persona:** Dokter IoMT Indonesia berbasis Kemenkes RI
2. **Data Pasien:** Usia, gender, semua nilai vital terbaru
3. **Label Sumber:** `[SUMBER: IoMT/Smartwatch]` atau `[SUMBER: Manual]`
4. **Knowledge Base:** Dari tabel `ai_knowledge` (bisa dikelola admin)
5. **Instruksi Output:** Format terstruktur — interpretasi, rekomendasi, video YouTube

---

## 🚀 Cara Deploy ke Production

```bash
# 1. Clone & install
git clone ... && cd Vitaly
composer install --no-dev
npm install && npm run build

# 2. Setup environment
cp .env.example .env
php artisan key:generate
# Isi semua nilai di .env (DB, Google OAuth, Gemini API)

# 3. Migrate database
php artisan migrate --force

# 4. Optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 5. Pastikan HTTPS aktif (wajib untuk Web Bluetooth API)
# Gunakan SSL dari Let's Encrypt atau layanan hosting yang menyediakan SSL
```

> ⚠️ **Penting:** Web Bluetooth API **hanya bekerja di HTTPS**. Tanpa SSL, fitur sync smartwatch otomatis menggunakan mode simulasi.

---

## 📊 Nilai Referensi Kesehatan (Standar Kemenkes RI)

| Parameter | Normal | Perlu Perhatian | Berbahaya |
|-----------|--------|-----------------|-----------|
| Tekanan Darah Sistolik | < 120 mmHg | 120–139 | ≥ 140 |
| Tekanan Darah Diastolik | < 80 mmHg | 80–89 | ≥ 90 |
| Detak Jantung | 60–100 bpm | — | < 60 atau > 100 |
| SpO2 | ≥ 95% | 90–94% | < 90% |
| Gula Darah Puasa | < 100 mg/dL | 100–125 | ≥ 126 |
| Suhu Tubuh | 36.0–37.5°C | — | > 37.5 (demam) |
| IMT (Asia Pasifik) | 18.5–22.9 | 23.0–24.9 | ≥ 25 (obes) |

---

*VITALY © 2026 — Sistem Monitor Kesehatan IoMT · KTI Competition Edition*
