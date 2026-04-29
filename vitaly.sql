-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 29, 2026 at 10:09 AM
-- Server version: 8.0.30
-- PHP Version: 8.3.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `vitaly`
--

-- --------------------------------------------------------

--
-- Table structure for table `ai_analyses`
--

CREATE TABLE `ai_analyses` (
  `id` bigint UNSIGNED NOT NULL,
  `patient_id` bigint UNSIGNED DEFAULT NULL,
  `prompt` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `result` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `records_analyzed` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ai_analyses`
--

INSERT INTO `ai_analyses` (`id`, `patient_id`, `prompt`, `result`, `records_analyzed`, `created_at`, `updated_at`) VALUES
(1, 2, '{\"user\":{\"name\":\"Fina Julianti\",\"gender\":\"female\",\"age\":20},\"records_count\":1}', '**1. Ringkasan Kesehatan**\nBerdasarkan data terakhir Fina, sistem menghasilkan **Skor Risiko: Baik (ada beberapa hal yang perlu dijaga)**.\n\n**2. Analisis Detail Parameter**\n- **IMT (15.4):** Berat badan kurang (Underweight).\n\n**3. Rekomendasi Gaya Hidup**\n1. Tingkatkan asupan kalori berkualitas: protein hewani (telur, ayam, ikan) dan lemak baik (alpukat, kacang). Target kenaikan 0.5 kg/minggu.\n\n**4. Kesimpulan**\nSecara keseluruhan kondisi Fina terpantau dalam batas yang dapat dikelola. Tetap pertahankan gaya hidup sehat dan pantau secara berkala.\n\n---\n*Analisis ini dihasilkan oleh sistem cadangan lokal berbasis aturan klinis. Tidak menggantikan diagnosis dokter.*', 1, '2026-04-28 21:34:31', '2026-04-28 21:34:31'),
(2, 2, '{\"user\":{\"name\":\"Fina Julianti\",\"gender\":\"female\",\"age\":20},\"records_count\":1}', '**1. Ringkasan Kesehatan**\nBerdasarkan data terakhir Fina, sistem menghasilkan **Skor Risiko: Baik (ada beberapa hal yang perlu dijaga)**.\n\n**2. Analisis Detail Parameter**\n- **IMT (15.4):** Berat badan kurang (Underweight).\n\n**3. Rekomendasi Gaya Hidup**\n1. Tingkatkan asupan kalori berkualitas: protein hewani (telur, ayam, ikan) dan lemak baik (alpukat, kacang). Target kenaikan 0.5 kg/minggu.\n\n**4. Kesimpulan**\nSecara keseluruhan kondisi Fina terpantau dalam batas yang dapat dikelola. Tetap pertahankan gaya hidup sehat dan pantau secara berkala.\n\n---\n*Analisis ini dihasilkan oleh sistem cadangan lokal berbasis aturan klinis. Tidak menggantikan diagnosis dokter.*', 1, '2026-04-28 22:10:14', '2026-04-28 22:10:14'),
(3, 1, '{\"user\":{\"name\":\"Budi Santoso\",\"gender\":\"male\",\"age\":30},\"records_count\":1}', '**1. Ringkasan Kesehatan**\nBerdasarkan data terakhir Budi, sistem menghasilkan **Skor Risiko: Baik (ada beberapa hal yang perlu dijaga)**.\n\n**2. Analisis Detail Parameter**\n- **Tekanan Darah (145/95 mmHg):** Hipertensi Derajat 1. Berisiko tinggi.\n- **Detak Jantung (88 bpm):** Normal dan stabil.\n- **Suhu Tubuh (36.8°C):** Normal.\n\n**3. Rekomendasi Gaya Hidup**\n1. Segera konsultasi ke dokter untuk penanganan medis. Kurangi asupan garam harian maksimal 1 sendok teh, dan hindari stres.\n\n**4. Kesimpulan**\nSecara keseluruhan kondisi Budi terpantau dalam batas yang dapat dikelola. Tetap pertahankan gaya hidup sehat dan pantau secara berkala.\n\n---\n*Analisis ini dihasilkan oleh sistem cadangan lokal berbasis aturan klinis. Tidak menggantikan diagnosis dokter.*', 1, '2026-04-29 01:52:32', '2026-04-29 01:52:32'),
(4, 1, '{\"user\":{\"name\":\"Budi Santoso\",\"gender\":\"male\",\"age\":30},\"records_count\":1}', '**1. Ringkasan Kesehatan**\nBerdasarkan data terakhir Budi, sistem menghasilkan **Skor Risiko: Baik (ada beberapa hal yang perlu dijaga)**.\n\n**2. Analisis Detail Parameter**\n- **Tekanan Darah (145/95 mmHg):** Hipertensi Derajat 1. Berisiko tinggi.\n- **Detak Jantung (88 bpm):** Normal dan stabil.\n- **Suhu Tubuh (36.8°C):** Normal.\n\n**3. Rekomendasi Gaya Hidup**\n1. Segera konsultasi ke dokter untuk penanganan medis. Kurangi asupan garam harian maksimal 1 sendok teh, dan hindari stres.\n\n**4. Kesimpulan**\nSecara keseluruhan kondisi Budi terpantau dalam batas yang dapat dikelola. Tetap pertahankan gaya hidup sehat dan pantau secara berkala.\n\n---\n*Analisis ini dihasilkan oleh sistem cadangan lokal berbasis aturan klinis. Tidak menggantikan diagnosis dokter.*', 1, '2026-04-29 01:59:05', '2026-04-29 01:59:05'),
(5, 3, '{\"user\":{\"name\":\"Ahmad Fauzi\",\"gender\":\"male\",\"age\":58},\"records_count\":1}', '**1. Ringkasan Kesehatan**\nBerdasarkan data terakhir Ahmad, sistem menghasilkan **Skor Risiko: Berisiko Tinggi**.\n\nUsia 40 tahun ke atas dengan hipertensi: risiko stroke dan serangan jantung meningkat 2-3x lipat. Pemeriksaan EKG dan profil lipid tahunan sangat direkomendasikan.\n\n**2. Analisis Detail Parameter**\n- **Tekanan Darah (185/115 mmHg):** Hipertensi Derajat 3. Berisiko tinggi.\n- **Detak Jantung (112 bpm):** Di atas normal (Takikardia). Bisa dipicu stres, dehidrasi, atau kafein.\n- **Suhu Tubuh (37.2°C):** Normal.\n\n**3. Rekomendasi Gaya Hidup**\n1. Segera konsultasi ke dokter untuk penanganan medis. Kurangi asupan garam harian maksimal 1 sendok teh, dan hindari stres.\n2. Cukupi cairan (2 liter air/hari), kurangi kafein, dan lakukan teknik relaksasi pernapasan dalam.\n\n**4. Kesimpulan**\n🚨 **PERINGATAN DARURAT (KRITIS):** Kondisi Ahmad saat ini sangat berbahaya dan berisiko fatal jika diabaikan. Segera bawa pasien ke IGD RS Terdekat atau Puskesmas sekarang juga!\n\n---\n*Analisis ini dihasilkan oleh sistem cadangan lokal berbasis aturan klinis. Tidak menggantikan diagnosis dokter.*', 1, '2026-04-29 02:29:26', '2026-04-29 02:29:26');

-- --------------------------------------------------------

--
-- Table structure for table `ai_knowledge`
--

CREATE TABLE `ai_knowledge` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'umum',
  `keywords` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('vitaly-cache-ai_analysis:share:analysis:1', 's:12:\"dkldfvbchnty\";', 1780044661),
('vitaly-cache-ai_analysis:share:analysis:2', 's:12:\"6fpbbbugrg7w\";', 1780044661),
('vitaly-cache-ai_analysis:share:analysis:3', 's:12:\"pjvqc14j3gcs\";', 1780046564),
('vitaly-cache-ai_analysis:share:analysis:4', 's:12:\"oj2tbp2uujo8\";', 1780046564),
('vitaly-cache-ai_analysis:share:analysis:5', 's:12:\"fxfen7gxjwun\";', 1780046967),
('vitaly-cache-ai_analysis:share:token:6fpbbbugrg7w', 'i:2;', 1780044661),
('vitaly-cache-ai_analysis:share:token:dkldfvbchnty', 'i:1;', 1780044661),
('vitaly-cache-ai_analysis:share:token:fxfen7gxjwun', 'i:5;', 1780046967),
('vitaly-cache-ai_analysis:share:token:oj2tbp2uujo8', 'i:4;', 1780046564),
('vitaly-cache-ai_analysis:share:token:pjvqc14j3gcs', 'i:3;', 1780046564),
('vitaly-cache-edukasi_videos_all', 'a:30:{i:0;a:9:{s:2:\"id\";i:1;s:9:\"youtubeId\";s:11:\"Fn6j-rCnghw\";s:8:\"category\";s:5:\"heart\";s:5:\"title\";s:47:\"Press Briefing Hari Jantung Sedunia-Kemenkes RI\";s:4:\"desc\";s:34:\"Sumber Youtube Kemenkes RI.â€¦\";s:7:\"channel\";s:38:\"Penyakit Tidak Menular Indonesia (ncd)\";s:6:\"source\";s:38:\"Penyakit Tidak Menular Indonesia (ncd)\";s:8:\"duration\";N;s:11:\"publishedAt\";s:10:\"2022-10-05\";}i:1;a:9:{s:2:\"id\";i:2;s:9:\"youtubeId\";s:11:\"DF1UbiDo_cI\";s:8:\"category\";s:5:\"heart\";s:5:\"title\";s:54:\"CEGAH PENYAKIT JANTUNG KORONER DENGAN CERDIK DAN PATUH\";s:4:\"desc\";s:127:\"Mengetahui bagaimana cara mencegah penyakit jantung sangatlah penting, apalagi penyakit kardiovaskuler, seperti penyakitâ€¦\";s:7:\"channel\";s:15:\"PROMKESJATIM TV\";s:6:\"source\";s:15:\"PROMKESJATIM TV\";s:8:\"duration\";N;s:11:\"publishedAt\";s:10:\"2022-10-17\";}i:2;a:9:{s:2:\"id\";i:3;s:9:\"youtubeId\";s:11:\"c1VfSipim2Q\";s:8:\"category\";s:5:\"heart\";s:5:\"title\";s:54:\"Kelas Edukasi Jantung Koroner Bikin Geger? Ini Tipsnya\";s:4:\"desc\";s:7:\"â€¦\";s:7:\"channel\";s:33:\"Unit Pelayanan Kesehatan Kemenkes\";s:6:\"source\";s:33:\"Unit Pelayanan Kesehatan Kemenkes\";s:8:\"duration\";N;s:11:\"publishedAt\";s:10:\"2023-10-03\";}i:3;a:9:{s:2:\"id\";i:4;s:9:\"youtubeId\";s:11:\"q6DPLoLfRl8\";s:8:\"category\";s:5:\"heart\";s:5:\"title\";s:63:\"Ayo Bersama Cegah Hipertensi | Video Promkes Dit.P2PTM Kemenkes\";s:4:\"desc\";s:125:\"PTM #videopromkes #hipertensi #cegahhipertensi Selamat Datang di Forum EpidemiologMuda Media berbagi informasi dan ...â€¦\";s:7:\"channel\";s:16:\"Epidemiolog Muda\";s:6:\"source\";s:16:\"Epidemiolog Muda\";s:8:\"duration\";N;s:11:\"publishedAt\";s:10:\"2022-12-05\";}i:4;a:9:{s:2:\"id\";i:5;s:9:\"youtubeId\";s:11:\"M2kRJlSufqo\";s:8:\"category\";s:5:\"heart\";s:5:\"title\";s:31:\"Diet sehat untuk pasien jantung\";s:4:\"desc\";s:127:\"Eduhasna TV adalah channel edukasi kesehatan, dikemas ringkas menyapa anda disela rutinitas. Tak butuh banyak waktu untuâ€¦\";s:7:\"channel\";s:11:\"Eduhasna TV\";s:6:\"source\";s:11:\"Eduhasna TV\";s:8:\"duration\";N;s:11:\"publishedAt\";s:10:\"2022-08-26\";}i:5;a:9:{s:2:\"id\";i:6;s:9:\"youtubeId\";s:11:\"Z3zZBSAsKs4\";s:8:\"category\";s:5:\"heart\";s:5:\"title\";s:54:\"Hubungan Antara Stroke, Tekanan Darah, Kinerja Jantung\";s:4:\"desc\";s:121:\"Poltekkes Kemenkes Surakarta Kelompok 07 Kelas/Prodi : 1A/D3 Akupunktur Dosen Pengampu : Heru Edi Kurniawan, S.Pd.â€¦\";s:7:\"channel\";s:9:\"Zian Zian\";s:6:\"source\";s:9:\"Zian Zian\";s:8:\"duration\";N;s:11:\"publishedAt\";s:10:\"2021-11-22\";}i:6;a:9:{s:2:\"id\";i:7;s:9:\"youtubeId\";s:11:\"2xdVIW9VAL8\";s:8:\"category\";s:8:\"diabetes\";s:5:\"title\";s:44:\"ILM PTM Ayo Cegah Diabetes Mellitus 60 detik\";s:4:\"desc\";s:127:\"Website Resmi Direktorat Promkes dan PM Kemenkes RI: http://promkes.kemkes.go.id Jangan lupa follow akun Social Media ..â€¦\";s:7:\"channel\";s:34:\"Ayo Sehat Kementerian Kesehatan RI\";s:6:\"source\";s:34:\"Ayo Sehat Kementerian Kesehatan RI\";s:8:\"duration\";N;s:11:\"publishedAt\";s:10:\"2020-01-24\";}i:7;a:9:{s:2:\"id\";i:8;s:9:\"youtubeId\";s:11:\"ypmoHwmLPF0\";s:8:\"category\";s:8:\"diabetes\";s:5:\"title\";s:33:\"Edukasi Mengenal Diabetes Melitus\";s:4:\"desc\";s:116:\"Video Edukasi Mengenal Diabetes Melitus Hak cipta dilindungi undang-undang dengan nomor EC00202105470 Tim ...â€¦\";s:7:\"channel\";s:22:\"HDSS Sleman FK-KMK UGM\";s:6:\"source\";s:22:\"HDSS Sleman FK-KMK UGM\";s:8:\"duration\";N;s:11:\"publishedAt\";s:10:\"2021-02-03\";}i:8;a:9:{s:2:\"id\";i:9;s:9:\"youtubeId\";s:11:\"BlO24liTpQ0\";s:8:\"category\";s:8:\"diabetes\";s:5:\"title\";s:47:\"Penyebab &amp; Jenis Penyakit Diabetes Mellitus\";s:4:\"desc\";s:120:\"Penyebab & Jenis Penyakit Diabetes Mellitus (Kencing Manis) Apa saja Gejala Diabetes, bagaimana menanggulanginya?â€¦\";s:7:\"channel\";s:10:\"SB30Health\";s:6:\"source\";s:10:\"SB30Health\";s:8:\"duration\";N;s:11:\"publishedAt\";s:10:\"2018-01-03\";}i:9;a:9:{s:2:\"id\";i:10;s:9:\"youtubeId\";s:11:\"qs2c7P-15jY\";s:8:\"category\";s:8:\"diabetes\";s:5:\"title\";s:93:\"Bagaimana Cara Cegah Diabetes dan Hipertensi Sejak Dini? (Video Edukasi PTM #TanyaKaderPRIMA)\";s:4:\"desc\";s:7:\"â€¦\";s:7:\"channel\";s:13:\"CISDI CHANNEL\";s:6:\"source\";s:13:\"CISDI CHANNEL\";s:8:\"duration\";N;s:11:\"publishedAt\";s:10:\"2022-09-21\";}i:10;a:9:{s:2:\"id\";i:11;s:9:\"youtubeId\";s:11:\"3zFeWwoN5PM\";s:8:\"category\";s:8:\"diabetes\";s:5:\"title\";s:87:\"Hari Diabetes Sedunia Tahun 2022 “Cegah dan Kendalikan Diabetes untuk Masa Depanmu”\";s:4:\"desc\";s:7:\"â€¦\";s:7:\"channel\";s:24:\"Kementerian Kesehatan RI\";s:6:\"source\";s:24:\"Kementerian Kesehatan RI\";s:8:\"duration\";N;s:11:\"publishedAt\";s:10:\"2022-11-14\";}i:11;a:9:{s:2:\"id\";i:12;s:9:\"youtubeId\";s:11:\"HucThdXBdBc\";s:8:\"category\";s:8:\"diabetes\";s:5:\"title\";s:51:\"Kapan Dikatakan Positif Menderita Diabetes Melitus?\";s:4:\"desc\";s:127:\"Hai Sahabat Graha Hermine, Salam Sehat. Komplek Ruko Asih Raya No. 06 - 15, Batu Aji, Batam. Telp. 0778 - 363 318 / 363 â€¦\";s:7:\"channel\";s:25:\"Rumah Sakit Graha Hermine\";s:6:\"source\";s:25:\"Rumah Sakit Graha Hermine\";s:8:\"duration\";N;s:11:\"publishedAt\";s:10:\"2023-05-01\";}i:12;a:9:{s:2:\"id\";i:13;s:9:\"youtubeId\";s:11:\"yhkvnohoD0I\";s:8:\"category\";s:9:\"nutrition\";s:5:\"title\";s:89:\"Cara Menghitung IMT (Indeks Massa Tubuh) untuk Menilai Status Gizi | Edukasi Gizi Praktis\";s:4:\"desc\";s:127:\"IMT atau Indeks Massa Tubuh adalah salah satu cara sederhana untuk menilai status gizi seseorang, apakah termasuk kurus,â€¦\";s:7:\"channel\";s:49:\"Dr. dr. Dwi Monik Purnamasari, M.Kes(Gizi), CBCFF\";s:6:\"source\";s:49:\"Dr. dr. Dwi Monik Purnamasari, M.Kes(Gizi), CBCFF\";s:8:\"duration\";N;s:11:\"publishedAt\";s:10:\"2025-04-08\";}i:13;a:9:{s:2:\"id\";i:14;s:9:\"youtubeId\";s:11:\"toh1kbw13Yo\";s:8:\"category\";s:9:\"nutrition\";s:5:\"title\";s:92:\"Gizi Seimbang, Menghitung Berat Badan Ideal, Indek Massa Tubuh, dan Angka Metabolisme Basal.\";s:4:\"desc\";s:127:\"Gizi Seimbang, Menghitung Berat Badan Ideal, Indek Massa Tubuh, dan Angka Metabolisme Basal. Halo teman progress, Kita .â€¦\";s:7:\"channel\";s:11:\"BIOPROGRESS\";s:6:\"source\";s:11:\"BIOPROGRESS\";s:8:\"duration\";N;s:11:\"publishedAt\";s:10:\"2022-01-11\";}i:14;a:9:{s:2:\"id\";i:15;s:9:\"youtubeId\";s:11:\"YueyBBP8Wxo\";s:8:\"category\";s:9:\"nutrition\";s:5:\"title\";s:60:\"Cara Menghitung IMT (Indeks Massa Tubuh) 💖 Kesehatan 💖\";s:4:\"desc\";s:127:\"Cara menghitung IMT atau Indeks Massa Tubuh atau Body Massa Indeks, yaitu berat badan dalam kilogram dibagi kuadrat tingâ€¦\";s:7:\"channel\";s:6:\"PERKES\";s:6:\"source\";s:6:\"PERKES\";s:8:\"duration\";N;s:11:\"publishedAt\";s:10:\"2016-12-11\";}i:15;a:9:{s:2:\"id\";i:16;s:9:\"youtubeId\";s:11:\"4iia6eXPt3w\";s:8:\"category\";s:9:\"nutrition\";s:5:\"title\";s:73:\"CARA MENGHITUNG INDEKS MASSA TUBUH (IMT) BADAN IDEAL || DUNIA KEPERAWATAN\";s:4:\"desc\";s:120:\"CARA MENGHITUNG INDEKS MASSA TUBUH (IMT) BADAN IDEAL: https://youtu.be/_f8gqjMlFAI?si=9sWppNJ3opT-pe1P Indeks ...â€¦\";s:7:\"channel\";s:17:\"DUNIA KEPERAWATAN\";s:6:\"source\";s:17:\"DUNIA KEPERAWATAN\";s:8:\"duration\";N;s:11:\"publishedAt\";s:10:\"2024-05-27\";}i:16;a:9:{s:2:\"id\";i:17;s:9:\"youtubeId\";s:11:\"PJLu8F2sW6g\";s:8:\"category\";s:9:\"nutrition\";s:5:\"title\";s:40:\"Edukasi Gizi Obesitas_Promkes Gizi UNDIP\";s:4:\"desc\";s:127:\"Obesitas merupakan salah satu masalah gizi utama yang ditemukan di Indonesia dan bahkan dunia. Belakangan ini masalah giâ€¦\";s:7:\"channel\";s:15:\"Ilmu Gizi Undip\";s:6:\"source\";s:15:\"Ilmu Gizi Undip\";s:8:\"duration\";N;s:11:\"publishedAt\";s:10:\"2021-12-11\";}i:17;a:9:{s:2:\"id\";i:18;s:9:\"youtubeId\";s:11:\"j8SIiQYCMEU\";s:8:\"category\";s:9:\"nutrition\";s:5:\"title\";s:77:\"🍎 Rekomendasi Makanan Sehat untuk Overweight: Nikmati Tanpa Khawatir! 🥗\";s:4:\"desc\";s:127:\"Selamat datang di Pusat Edukasi Gizi Poltekkes Kemenkes Surabaya! Mengelola berat badan bukan berarti harus meninggalkanâ€¦\";s:7:\"channel\";s:46:\"Pusat Edukasi Gizi Poltekkes Kemenkes Surabaya\";s:6:\"source\";s:46:\"Pusat Edukasi Gizi Poltekkes Kemenkes Surabaya\";s:8:\"duration\";N;s:11:\"publishedAt\";s:10:\"2024-12-23\";}i:18;a:9:{s:2:\"id\";i:19;s:9:\"youtubeId\";s:11:\"9ukD8CkQv2s\";s:8:\"category\";s:9:\"lifestyle\";s:5:\"title\";s:88:\"AKIBAT KELEBIHAN BERAT BADAN #olahraga #dietbodyfit #senamaerobik #senambodyfit #wagroup\";s:4:\"desc\";s:90:\"GABUNG DI GROUP WA UNTUK PENURUNAN BERAT BADAN Kirim pesan ke Body Fit di WhatsApp.â€¦\";s:7:\"channel\";s:16:\"BodyFit By Bagus\";s:6:\"source\";s:16:\"BodyFit By Bagus\";s:8:\"duration\";N;s:11:\"publishedAt\";s:10:\"2024-09-13\";}i:19;a:9:{s:2:\"id\";i:20;s:9:\"youtubeId\";s:11:\"2qWR_b1HE18\";s:8:\"category\";s:9:\"lifestyle\";s:5:\"title\";s:74:\"#suaratirta TOPIK KALI INI: MITOS DAN FAKTA SEPUTAR KESEHATAN BEROLAHRAGA!\";s:4:\"desc\";s:101:\"Subscribe kalau mau subscribe, like kalau kontennya bermanfaat, share kalau layak di share ...â€¦\";s:7:\"channel\";s:18:\"Tirta PengPengPeng\";s:6:\"source\";s:18:\"Tirta PengPengPeng\";s:8:\"duration\";N;s:11:\"publishedAt\";s:10:\"2024-12-11\";}i:20;a:9:{s:2:\"id\";i:21;s:9:\"youtubeId\";s:11:\"pxZ5-DNo38E\";s:8:\"category\";s:9:\"lifestyle\";s:5:\"title\";s:31:\"Yang Merasa Sehat, Nonton Ini!\";s:4:\"desc\";s:127:\"Senang banget akhirnya bisa ngobrol langsung sama Ade Rai! Dari awal sampai akhir gue dapat banyak insight soal gaya hidâ€¦\";s:7:\"channel\";s:12:\"Raditya Dika\";s:6:\"source\";s:12:\"Raditya Dika\";s:8:\"duration\";N;s:11:\"publishedAt\";s:10:\"2025-08-05\";}i:21;a:9:{s:2:\"id\";i:22;s:9:\"youtubeId\";s:11:\"50P19BRlEhg\";s:8:\"category\";s:9:\"lifestyle\";s:5:\"title\";s:88:\"Cara Paling Gampang Hidup Sehat - dr. Zaidul Akbar Official #jsr #zaidulakbar #kesehatan\";s:4:\"desc\";s:36:\"dr. Zaidul Akbar Official ...â€¦\";s:7:\"channel\";s:25:\"dr. Zaidul Akbar Official\";s:6:\"source\";s:25:\"dr. Zaidul Akbar Official\";s:8:\"duration\";N;s:11:\"publishedAt\";s:10:\"2024-02-02\";}i:22;a:9:{s:2:\"id\";i:23;s:9:\"youtubeId\";s:11:\"78slupxVcog\";s:8:\"category\";s:9:\"lifestyle\";s:5:\"title\";s:91:\"#momscorner 68 dr. Diana F. Suganda, M.Kes, SpGK | Olahraga di Malam Hari, Aman atau Tidak?\";s:4:\"desc\";s:127:\"Hi everyone! Podcast kali ini pembahasannya seru banget, kita akan diskusi mengenai gizi, diet, dan olahraga yang sesuaiâ€¦\";s:7:\"channel\";s:21:\"Nikita Willy Official\";s:6:\"source\";s:21:\"Nikita Willy Official\";s:8:\"duration\";N;s:11:\"publishedAt\";s:10:\"2025-10-04\";}i:23;a:9:{s:2:\"id\";i:24;s:9:\"youtubeId\";s:11:\"Dh-4rLyJUhA\";s:8:\"category\";s:9:\"lifestyle\";s:5:\"title\";s:90:\"Gerakan yang bikin sirkulasi darah lancar dan anti lemes lemes #DrXie #bloodpressure #tips\";s:4:\"desc\";s:7:\"â€¦\";s:7:\"channel\";s:6:\"DR XIE\";s:6:\"source\";s:6:\"DR XIE\";s:8:\"duration\";N;s:11:\"publishedAt\";s:10:\"2023-07-19\";}i:24;a:9:{s:2:\"id\";i:25;s:9:\"youtubeId\";s:11:\"bGpIhiciB8M\";s:8:\"category\";s:6:\"mental\";s:5:\"title\";s:79:\"Gangguan Kesehatan Mental: Penyebab, Gejala, Pengobatan, dan Pencegahan #shorts\";s:4:\"desc\";s:90:\"Temukan video lengkapnya di sini: https://youtu.be/3lWja7H9_Zw Artikel terkait: ...â€¦\";s:7:\"channel\";s:7:\"Halodoc\";s:6:\"source\";s:7:\"Halodoc\";s:8:\"duration\";N;s:11:\"publishedAt\";s:10:\"2023-02-25\";}i:25;a:9:{s:2:\"id\";i:26;s:9:\"youtubeId\";s:11:\"q5x1SNjRQwY\";s:8:\"category\";s:6:\"mental\";s:5:\"title\";s:23:\"Buat yang Lagi Stress..\";s:4:\"desc\";s:127:\"Baru! Stand Up Comedy Raditya Dika selama tur Cerita Sebelku, beli di sini: https://radityadika.com/video/ceritasebelku2â€¦\";s:7:\"channel\";s:12:\"Raditya Dika\";s:6:\"source\";s:12:\"Raditya Dika\";s:8:\"duration\";N;s:11:\"publishedAt\";s:10:\"2024-07-22\";}i:26;a:9:{s:2:\"id\";i:27;s:9:\"youtubeId\";s:11:\"5SULCVCshmg\";s:8:\"category\";s:6:\"mental\";s:5:\"title\";s:32:\"On Marissa&#39;s Mind: Kecemasan\";s:4:\"desc\";s:125:\"Kecemasan adalah bagian dari hidup manusia, karena nenek moyang kita adalah manusia purba yang dulu harus khawatir ...â€¦\";s:7:\"channel\";s:9:\"Greatmind\";s:6:\"source\";s:9:\"Greatmind\";s:8:\"duration\";N;s:11:\"publishedAt\";s:10:\"2022-02-12\";}i:27;a:9:{s:2:\"id\";i:28;s:9:\"youtubeId\";s:11:\"x6U7aLW93r8\";s:8:\"category\";s:6:\"mental\";s:5:\"title\";s:108:\"WASPADA⚠️ DOKTER SPESIALIS JIWA INI BONGKAR AWAL MULA TERJADINYA DEPRESI &amp; CARA ATASINYA - dr. Andri\";s:4:\"desc\";s:127:\"dr. Andri, Sp.KJ, FAPM Beliau merupakan psikiater di RS EMC Alam Sutera yang akan membongkar alasan kenapa depresi bisa â€¦\";s:7:\"channel\";s:10:\"kasisolusi\";s:6:\"source\";s:10:\"kasisolusi\";s:8:\"duration\";N;s:11:\"publishedAt\";s:10:\"2023-06-29\";}i:28;a:9:{s:2:\"id\";i:29;s:9:\"youtubeId\";s:11:\"BwivvpCyVAA\";s:8:\"category\";s:6:\"mental\";s:5:\"title\";s:74:\"Anxiety Disorder dan Cara untuk Menghadapinya | PAB #61 Psikolog Joe Irene\";s:4:\"desc\";s:127:\"Anxiety Disorder adalah gangguan kesehatan mental ditandai dengan munculnya perasaan cemas, khawatir, takut berlebihan .â€¦\";s:7:\"channel\";s:12:\"Bicarakan ID\";s:6:\"source\";s:12:\"Bicarakan ID\";s:8:\"duration\";N;s:11:\"publishedAt\";s:10:\"2024-05-12\";}i:29;a:9:{s:2:\"id\";i:30;s:9:\"youtubeId\";s:11:\"A3uIr2F2Ono\";s:8:\"category\";s:6:\"mental\";s:5:\"title\";s:55:\"KENALI BERAGAM GANGGUAN KECEMASAN DAN CARA MENGATASINYA\";s:4:\"desc\";s:126:\"Narasumber : dr. E. Mudjaddid, Sp.PD-KPsi, FINASIM Bergabung dengan channel ini untuk mendapatkan akses ke berbagai ...â€¦\";s:7:\"channel\";s:18:\"Bethsaida Hospital\";s:6:\"source\";s:18:\"Bethsaida Hospital\";s:8:\"duration\";N;s:11:\"publishedAt\";s:10:\"2024-02-15\";}}', 1777458882);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `health_records`
--

CREATE TABLE `health_records` (
  `id` bigint UNSIGNED NOT NULL,
  `patient_id` bigint UNSIGNED DEFAULT NULL,
  `recorded_by` bigint UNSIGNED DEFAULT NULL,
  `systolic` int DEFAULT NULL COMMENT 'Tekanan darah sistolik (mmHg)',
  `diastolic` int DEFAULT NULL COMMENT 'Tekanan darah diastolik (mmHg)',
  `heart_rate` int DEFAULT NULL COMMENT 'Detak jantung (bpm)',
  `blood_sugar` decimal(6,2) DEFAULT NULL COMMENT 'Gula darah (mg/dL)',
  `weight` decimal(5,2) DEFAULT NULL COMMENT 'Berat badan (kg)',
  `height` decimal(5,2) DEFAULT NULL COMMENT 'Tinggi badan (cm)',
  `temperature` decimal(4,2) DEFAULT NULL COMMENT 'Suhu tubuh (°C)',
  `respiratory_rate` int DEFAULT NULL COMMENT 'Laju nafas / Respiratory Rate (x/menit)',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `recorded_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `health_records`
--

INSERT INTO `health_records` (`id`, `patient_id`, `recorded_by`, `systolic`, `diastolic`, `heart_rate`, `blood_sugar`, `weight`, `height`, `temperature`, `respiratory_rate`, `notes`, `recorded_at`, `created_at`, `updated_at`) VALUES
(1, 2, 2, NULL, NULL, NULL, NULL, '54.00', '187.00', NULL, NULL, NULL, '2026-04-28 21:34:26', '2026-04-28 21:34:26', '2026-04-28 21:34:26'),
(2, 2, 2, 145, 95, 88, NULL, NULL, NULL, '36.80', NULL, 'Data otomatis ditarik via Bluetooth (Simulasi IoMT)', '2026-04-29 01:50:38', '2026-04-29 01:50:38', '2026-04-29 01:50:38'),
(3, 1, 2, 145, 95, 88, NULL, NULL, NULL, '36.80', NULL, 'Data otomatis ditarik via Bluetooth (Simulasi IoMT)', '2026-04-29 08:52:00', '2026-04-29 01:52:22', '2026-04-29 01:52:22'),
(4, 3, 2, 185, 115, 112, NULL, NULL, NULL, '37.20', NULL, 'Data otomatis ditarik via Bluetooth (Simulasi IoMT)', '2026-04-29 02:29:16', '2026-04-29 02:29:16', '2026-04-29 02:29:16');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_01_01_000003_add_role_to_users_table', 1),
(5, '2025_01_01_000004_create_health_records_table', 1),
(6, '2025_01_01_000005_create_ai_analyses_table', 1),
(7, '2026_03_07_223120_add_google_id_to_users_table', 1),
(8, '2026_03_07_234913_add_profile_fields_to_users_table', 1),
(9, '2026_03_08_161438_create_ai_knowledge_table', 1),
(10, '2026_03_10_000001_create_patients_table', 1),
(11, '2026_03_10_000002_update_health_records_for_patients', 1),
(12, '2026_03_10_000003_update_ai_analyses_for_patients', 1),
(13, '2026_03_10_000004_update_users_role_to_kader', 1),
(14, '2026_03_12_000001_replace_oxygen_saturation_with_respiratory_rate_in_health_records', 1),
(15, '2026_04_29_090845_add_device_id_to_patients_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

CREATE TABLE `patients` (
  `id` bigint UNSIGNED NOT NULL,
  `nik` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_of_birth` date NOT NULL,
  `gender` enum('male','female') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `device_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `patients`
--

INSERT INTO `patients` (`id`, `nik`, `name`, `date_of_birth`, `gender`, `phone`, `device_id`, `address`, `created_at`, `updated_at`) VALUES
(1, '3201012505950001', 'Budi Santoso', '1995-05-25', 'male', '081234567890', NULL, 'Jl. Contoh No. 1, Jakarta', '2026-04-28 20:45:26', '2026-04-28 20:45:26'),
(2, '3111111111111111', 'Fina Julianti', '2005-06-28', 'female', '085869236023', NULL, 'Jalan Klapasawit', '2026-04-28 21:34:03', '2026-04-28 21:34:03'),
(3, '3201015508741140', 'Ahmad Fauzi', '1968-03-22', 'male', '085635653264', 'VTL-XK6CN3', 'Jl. Kenanga No. 12, Depok, Jawa Barat', '2026-04-29 02:29:01', '2026-04-29 02:29:01');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('AQKRd7qv1UPJVstseCMkTr4WCFhPqzhubryTIbov', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiNFF6OFo0M0RGZTJKQmpTdkpJZzZ3V0dpOGJBY2pUbzc0SFJyMU4wdCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9hdXRoL2dvb2dsZS90YW11IjtzOjU6InJvdXRlIjtzOjE2OiJhdXRoLmdvb2dsZS50YW11Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1777436668),
('ij4sR6ByIlQz5srffuWgjHbGE7M0XKK8AfBTWN7y', NULL, '192.168.100.13', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiWkdPblI0VXU1N0k3Q0M1UG02RElabjE0TDZITXl4S3J1YXdXcVBKaSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly8xOTIuMTY4LjEwMC4xMDo4MDAwL21hc3VrIjtzOjU6InJvdXRlIjtzOjE0OiJwYXRpZW50Lmxvb2t1cCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1777438263),
('JdtI6jtircyrU0K21AyTGfBKCXllP5T2ne2uours', NULL, '192.168.100.13', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiNklpNjZWRnlyRUpDTk44UHVZRDRwMzczZHBiNGtDVndjU3dxN0RneSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly8xOTIuMTY4LjEwMC4xMDo4MDAwL21hc3VrIjtzOjU6InJvdXRlIjtzOjE0OiJwYXRpZW50Lmxvb2t1cCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1777438350),
('lY5c99IGiSiWIZhIt7NhaLwxff8beU6TIo4W1ytl', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiTVd4YkNoa05ueHB2OEM3SkhrMUdxRUd3emNIRzdZaDhHYVJzVjc5TSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9kYXNoYm9hcmQiO3M6NToicm91dGUiO3M6OToiZGFzaGJvYXJkIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1777437800),
('UhuvqdqSerStnPh3fCcfdZ2L5ThAGsKnwYtmOaPD', NULL, '192.168.100.10', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiM21veGhyaEdOUWlrMTB3ZHJuRWhvVVFkT01hT0Nlb244czVHT1RKMyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDM6Imh0dHA6Ly8xOTIuMTY4LjEwMC4xMDo4MDAwL2F1dGgvZ29vZ2xlL3RhbXUiO3M6NToicm91dGUiO3M6MTY6ImF1dGguZ29vZ2xlLnRhbXUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1777436402),
('vMDTuOu2Kcp02iHZEtfwxnp3JhGM0Hvx5Mho0fgn', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiU2lEelN5ZXg4NFhuM3oxWGVRZ01nQTRMVFh6RHBTVmpGVTQ2RFhzQyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMCI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1777457325),
('wzdgoKdAXhvkDeYxrEbESa2MfCfnT6oXbAVcPKx3', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiQlNEQ0lBMzVGazhCWU5iNE53R0pGeDZuUXN5NDNXd0VTT2tndFQ4dSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hdXRoL2dvb2dsZS90YW11IjtzOjU6InJvdXRlIjtzOjE2OiJhdXRoLmdvb2dsZS50YW11Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1777436472),
('ywiSBze5E4644xupdDFIsxyKbvlwd1mdbNHgsGMv', NULL, '192.168.100.11', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiTEJaTmpDekZRZWpCY1hCbTlhMUhFcWdNWldaWUtGZVp0b01HN0xiMCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjY6Imh0dHA6Ly8xOTIuMTY4LjEwMC4xMDo4MDAwIjtzOjU6InJvdXRlIjtOO319', 1777456475),
('Zxv4kjtiSHUFH9dTIGJ6wsWGjKEv9KAD140iZD07', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiWllGVHNQdDg1eERRUHRuaTRvSEZ2MEJYeG5ySTBuNm1mTExwd2tTSyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9tYXN1ayI7czo1OiJyb3V0ZSI7czoxNDoicGF0aWVudC5sb29rdXAiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjIwOiJ0YW11X2dvb2dsZV92ZXJpZmllZCI7YjoxO3M6MTY6InRhbXVfZ29vZ2xlX25hbWUiO3M6OToiQWxmYWVuIDIzIjt9', 1777436774);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `google_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` enum('admin','kader') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'kader',
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` enum('male','female') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avatar` text COLLATE utf8mb4_unicode_ci,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `google_id`, `role`, `phone`, `date_of_birth`, `gender`, `avatar`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin VITALY', 'admin@VITALY.com', NULL, 'admin', NULL, NULL, NULL, NULL, NULL, '$2y$12$l5l1lDZacqlemX8sng2PMuhcWsnamfAvEyNf1RL/9I7jnQ5pvkqoe', 'E8wg70xLCGKij1EKw6Br1yAo1U7TWCp0uyzlxgis0u8K7pv3L92mHWBJR9cZ', '2026-04-28 20:45:26', '2026-04-28 20:45:26'),
(2, 'Health Agent VITALY', 'kader@VITALY.com', NULL, 'kader', NULL, NULL, NULL, NULL, NULL, '$2y$12$n4w/F9XvMYnRigZLnhIR8uQ3cDBpdWwA1V7g23eAjuvyMOArDPy.G', NULL, '2026-04-28 20:45:26', '2026-04-29 02:00:44');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ai_analyses`
--
ALTER TABLE `ai_analyses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ai_analyses_patient_id_foreign` (`patient_id`);

--
-- Indexes for table `ai_knowledge`
--
ALTER TABLE `ai_knowledge`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `health_records`
--
ALTER TABLE `health_records`
  ADD PRIMARY KEY (`id`),
  ADD KEY `health_records_patient_id_foreign` (`patient_id`),
  ADD KEY `health_records_recorded_by_foreign` (`recorded_by`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `patients_nik_unique` (`nik`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_google_id_unique` (`google_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ai_analyses`
--
ALTER TABLE `ai_analyses`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `ai_knowledge`
--
ALTER TABLE `ai_knowledge`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `health_records`
--
ALTER TABLE `health_records`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `patients`
--
ALTER TABLE `patients`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `ai_analyses`
--
ALTER TABLE `ai_analyses`
  ADD CONSTRAINT `ai_analyses_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `health_records`
--
ALTER TABLE `health_records`
  ADD CONSTRAINT `health_records_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `health_records_recorded_by_foreign` FOREIGN KEY (`recorded_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
