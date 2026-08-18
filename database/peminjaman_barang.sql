-- ========================================================
-- DOKUMEN BASIS DATA UJI KOMPETENSI KEAHLIAN (UKK) RPL 2026
-- Proyek: Aplikasi Peminjaman Alat / Barang Sarana Sekolah
-- Database: peminjaman_barang
-- Dibuat secara otomatis untuk Lampiran Berkas UKK RPL
-- ========================================================

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
START TRANSACTION;
SET time_zone = '+07:00';

-- --------------------------------------------------------
-- Struktur tabel untuk `alats`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `alats`;
CREATE TABLE `alats` (
  `id_alat` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `kode_alat` varchar(50) NOT NULL,
  `nama_alat` varchar(150) NOT NULL,
  `id_kategori` bigint(20) unsigned NOT NULL,
  `stok` int(11) NOT NULL DEFAULT 0,
  `kondisi` enum('Baik','Perlu Perbaikan','Rusak') NOT NULL DEFAULT 'Baik',
  `foto` varchar(255) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_alat`),
  UNIQUE KEY `alats_kode_alat_unique` (`kode_alat`),
  KEY `alats_id_kategori_foreign` (`id_kategori`),
  CONSTRAINT `alats_id_kategori_foreign` FOREIGN KEY (`id_kategori`) REFERENCES `kategoris` (`id_kategori`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data untuk tabel `alats`
INSERT INTO `alats` VALUES
('1', 'ALT-ELK-001', 'Proyektor Epson EB-X400 HDMI', '1', '5', 'Baik', NULL, 'Proyektor 3300 Lumens resolusi XGA lengkap kabel HDMI dan tas.', '2026-08-16 17:30:46', '2026-08-16 17:30:46'),
('2', 'ALT-ELK-002', 'Pointer Wireless Presenter Laser', '1', '8', 'Baik', NULL, 'Laser pointer presentasi wireless USB receiver 2.4GHz.', '2026-08-16 17:30:46', '2026-08-16 17:30:46'),
('3', 'ALT-LAB-001', 'LAN Cable Tester RJ45 & RJ11', '2', '12', 'Baik', NULL, 'Tester kabel jaringan RJ45 dengan indikator LED 1-8.', '2026-08-16 17:30:46', '2026-08-16 17:30:46'),
('4', 'ALT-LAB-002', 'Tang Crimping RJ45 RJ11 Cat5/Cat6', '2', '10', 'Baik', NULL, 'Crimping tool modular 3-in-1 pemotong dan pengupas kabel UTP.', '2026-08-16 17:30:46', '2026-08-16 17:30:46'),
('5', 'ALT-BGK-001', 'Digital Multimeter Sanwa CD800a', '3', '6', 'Baik', NULL, 'Multitester digital pengukur tegangan AC/DC, resistansi, kontinuitas.', '2026-08-16 17:30:46', '2026-08-16 17:30:46'),
('6', 'ALT-OLR-001', 'Bola Basket Molten GG7X Original', '4', '4', 'Baik', NULL, 'Bola basket standar FIBA official match ball size 7.', '2026-08-16 17:30:46', '2026-08-16 17:30:46');

-- --------------------------------------------------------
-- Struktur tabel untuk `cache`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Struktur tabel untuk `cache_locks`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Struktur tabel untuk `failed_jobs`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Struktur tabel untuk `job_batches`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Struktur tabel untuk `jobs`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Struktur tabel untuk `kategoris`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `kategoris`;
CREATE TABLE `kategoris` (
  `id_kategori` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nama_kategori` varchar(100) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_kategori`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data untuk tabel `kategoris`
INSERT INTO `kategoris` VALUES
('1', 'Elektronik & Multimedia', 'Peralatan proyektor, kabel VGA/HDMI, kamera, speaker audio, mic.', '2026-08-16 17:30:46', '2026-08-16 17:30:46'),
('2', 'Laboratorium Komputer & Jaringan', 'Crimping tool, tester LAN kabel, switch hub, router mikrotik, converter.', '2026-08-16 17:30:46', '2026-08-16 17:30:46'),
('3', 'Peralatan Bengkel & Praktik', 'Solder listrik, multimeter digital, toolkit set, bor tangan, tang kombinasi.', '2026-08-16 17:30:46', '2026-08-16 17:30:46'),
('4', 'Sarana Olahraga & Seni', 'Bola basket, bola voli, raket badminton, matras senam, gitar akustik.', '2026-08-16 17:30:46', '2026-08-16 17:30:46');

-- --------------------------------------------------------
-- Struktur tabel untuk `log_aktivitas`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `log_aktivitas`;
CREATE TABLE `log_aktivitas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `aktivitas` varchar(255) NOT NULL,
  `ip_address` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `log_aktivitas_user_id_foreign` (`user_id`),
  CONSTRAINT `log_aktivitas_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data untuk tabel `log_aktivitas`
INSERT INTO `log_aktivitas` VALUES
('1', '1', 'Inisialisasi sistem aplikasi peminjaman barang dan inventarisasi master data.', '127.0.0.1', '2026-08-16 17:30:46', '2026-08-16 17:30:46');

-- --------------------------------------------------------
-- Struktur tabel untuk `migrations`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data untuk tabel `migrations`
INSERT INTO `migrations` VALUES
('1', '0001_01_01_000000_create_users_table', '1'),
('2', '0001_01_01_000001_create_cache_table', '1'),
('3', '0001_01_01_000002_create_jobs_table', '1'),
('4', '2026_01_01_000001_create_kategoris_table', '1'),
('5', '2026_01_01_000002_create_alats_table', '1'),
('6', '2026_01_01_000003_create_peminjamans_table', '1'),
('7', '2026_01_01_000004_create_log_aktivitas_table', '1');

-- --------------------------------------------------------
-- Struktur tabel untuk `password_reset_tokens`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Struktur tabel untuk `peminjamans`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `peminjamans`;
CREATE TABLE `peminjamans` (
  `id_peminjaman` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `kode_peminjaman` varchar(50) NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `id_alat` bigint(20) unsigned NOT NULL,
  `jumlah_pinjam` int(11) NOT NULL DEFAULT 1,
  `tgl_pinjam` date NOT NULL,
  `tgl_kembali_rencana` date NOT NULL,
  `tgl_kembali_aktual` date DEFAULT NULL,
  `status` enum('Menunggu Konfirmasi','Disetujui','Ditolak','Sedang Dipinjam','Dikembalikan') NOT NULL DEFAULT 'Menunggu Konfirmasi',
  `catatan_peminjam` text DEFAULT NULL,
  `catatan_petugas` text DEFAULT NULL,
  `denda` decimal(12,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_peminjaman`),
  UNIQUE KEY `peminjamans_kode_peminjaman_unique` (`kode_peminjaman`),
  KEY `peminjamans_user_id_foreign` (`user_id`),
  KEY `peminjamans_id_alat_foreign` (`id_alat`),
  CONSTRAINT `peminjamans_id_alat_foreign` FOREIGN KEY (`id_alat`) REFERENCES `alats` (`id_alat`) ON UPDATE CASCADE,
  CONSTRAINT `peminjamans_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data untuk tabel `peminjamans`
INSERT INTO `peminjamans` VALUES
('1', 'PINJAM-20260216-001', '3', '1', '1', '2026-08-14', '2026-08-17', NULL, 'Sedang Dipinjam', 'Untuk presentasi tugas akhir sidang UKK di Aula Lt. 2', 'Kelengkapan kabel power dan HDMI lengkap.', '0.00', '2026-08-16 17:30:46', '2026-08-16 17:30:46'),
('2', 'PINJAM-20260216-002', '4', '3', '2', '2026-08-16', '2026-08-18', NULL, 'Menunggu Konfirmasi', 'Praktik instalasi jaringan komputer kelas XII RPL 2', NULL, '0.00', '2026-08-16 17:30:46', '2026-08-16 17:30:46'),
('3', 'PINJAM-20260210-003', '3', '5', '1', '2026-08-10', '2026-08-12', '2026-08-12', 'Dikembalikan', 'Pengujian modul power supply komputer', 'Dikembalikan tepat waktu dalam kondisi baik.', '0.00', '2026-08-16 17:30:46', '2026-08-16 17:30:46');

-- --------------------------------------------------------
-- Struktur tabel untuk `sessions`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Struktur tabel untuk `users`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','petugas','peminjam') NOT NULL DEFAULT 'peminjam',
  `no_telp` varchar(20) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_username_unique` (`username`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data untuk tabel `users`
INSERT INTO `users` VALUES
('1', 'Administrator Sarpras', 'admin', 'admin@sekolah.sch.id', NULL, '$2y$12$nVTJTgNRQGjK6QSLQaiCXOcGqP0RU9PBAYAI3FJFu.mwpqdnL0aFC', 'admin', '081234567890', 'Ruang Sarpras Lantai 1', NULL, '2026-08-16 17:30:45', '2026-08-16 17:30:45'),
('2', 'Budi Santoso (Petugas Lab)', 'petugas', 'petugas@sekolah.sch.id', NULL, '$2y$12$26gp8vWNMW20.Co0dRZvFO/AQwnGwvNQvT7qe9p6GSSIDSMESDm26', 'petugas', '081234567891', 'Ruang Pengelola Alat', NULL, '2026-08-16 17:30:46', '2026-08-16 17:30:46'),
('3', 'Ahmad Fauzi (XII RPL 1)', 'siswa1', 'ahmad@siswa.sch.id', NULL, '$2y$12$wNGOkulfsm9yq69djUFhGuoC5bLOBOka4iHD/vI7LYLbTypHXmQH2', 'peminjam', '081298765432', 'Jl. Merdeka No. 12', NULL, '2026-08-16 17:30:46', '2026-08-16 17:30:46'),
('4', 'Siti Nurhaliza (XII RPL 2)', 'siswa2', 'siti@siswa.sch.id', NULL, '$2y$12$iGosndBb557ORVT8AIDmvuFlMCjKJe5YHdZtLtaKr3MGk/nHfcHPO', 'peminjam', '081287654321', 'Jl. Pemuda No. 45', NULL, '2026-08-16 17:30:46', '2026-08-16 17:30:46');

SET FOREIGN_KEY_CHECKS=1;
COMMIT;
