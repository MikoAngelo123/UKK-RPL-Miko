-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 18, 2026 at 11:25 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `peminjaman_barang`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
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
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `email`, `email_verified_at`, `password`, `role`, `no_telp`, `alamat`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Administrator Sarpras', 'admin', 'admin@sekolah.sch.id', NULL, '$2y$12$oYnKkE2V2Qxeb0mSJRWm/.QgsdKSyZ8BkKR4aAylpTIjHiqvVG6OK', 'admin', '081234567890', 'Ruang Sarpras Lantai 1', NULL, '2026-08-18 09:12:00', '2026-08-18 09:12:00'),
(2, 'Budi Santoso (Petugas Lab)', 'petugas', 'petugas@sekolah.sch.id', NULL, '$2y$12$XylZn/ybD4Q1x28zUl9IX.XsDkm/p/k/7SO4T6jBlUk33D6o8LEF.', 'petugas', '081234567891', 'Ruang Pengelola Alat', NULL, '2026-08-18 09:12:01', '2026-08-18 09:12:01'),
(3, 'Ahmad Fauzi (XII RPL 1)', 'siswa1', 'ahmad@siswa.sch.id', NULL, '$2y$12$e.nMXU.Ar1qcFYMGozNgAetBctGzWcckppUdQSJaQ3SMhKSuTV9jS', 'peminjam', '081298765432', 'Jl. Merdeka No. 12', NULL, '2026-08-18 09:12:01', '2026-08-18 09:12:01'),
(4, 'Siti Nurhaliza (XII RPL 2)', 'siswa2', 'siti@siswa.sch.id', NULL, '$2y$12$qGQheV03/7cl/esAqzKyQe.yN5BMYodrYirC7gTVPqkEWnosYxnj2', 'peminjam', '081287654321', 'Jl. Pemuda No. 45', NULL, '2026-08-18 09:12:02', '2026-08-18 09:12:02'),
(5, 'Miko Angelo Dharma', 'Miko', NULL, NULL, '$2y$12$CHsSIyvYi0xJL7OiI03bYuPBWTq2pxjaXIiPJJw3y5joapSsejS6e', 'peminjam', '0895326154409', 'XII RPL 1', NULL, '2026-08-18 09:17:14', '2026-08-18 09:17:14');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
