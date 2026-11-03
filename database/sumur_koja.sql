-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 03, 2026 at 05:03 AM
-- Server version: 8.4.3
-- PHP Version: 8.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sumur_koja`
--

-- --------------------------------------------------------

--
-- Table structure for table `akun_keluarga`
--

CREATE TABLE `akun_keluarga` (
  `id_keluarga` int UNSIGNED NOT NULL,
  `nama_keluarga` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat_domisili` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Jalan, nomor rumah, RT/RW saja. Daerah di kolom kelurahan, kecamatan, kota_kabupaten',
  `kelurahan` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kecamatan` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kota_kabupaten` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dibuat_pada` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `akun_keluarga`
--

INSERT INTO `akun_keluarga` (`id_keluarga`, `nama_keluarga`, `alamat_domisili`, `kelurahan`, `kecamatan`, `kota_kabupaten`, `dibuat_pada`) VALUES
(1, 'Keluarga Budi', 'Jl. Contoh No. 1, RT 02/RW 05', 'Menteng', 'Menteng', 'Jakarta Pusat', '2026-10-31 20:13:48');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jadwal`
--

CREATE TABLE `jadwal` (
  `id` bigint UNSIGNED NOT NULL,
  `kelas` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lokasi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tutor` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `waktu_mulai` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `waktu_selesai` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Terjadwal',
  `tanggal` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jadwal`
--

INSERT INTO `jadwal` (`id`, `kelas`, `lokasi`, `tutor`, `waktu_mulai`, `waktu_selesai`, `status`, `tanggal`, `created_at`, `updated_at`) VALUES
(1, 'Junior Robotics', 'Lab STEM 1', 'Kak Dimas', '09.00', '10.00 WIB', 'Selesai', NULL, '2026-11-01 22:16:03', '2026-11-01 22:16:03'),
(2, 'Python for Kids', 'Lab Komputer A', 'Kak Sarah', '10.30', '11.30 WIB', 'Selesai', NULL, '2026-11-01 22:16:03', '2026-11-01 22:16:03'),
(3, 'Arduino Explorer', 'Lab Maker', 'Kak Reza', '13.00', '14.00 WIB', 'Berlangsung', NULL, '2026-11-01 22:16:03', '2026-11-01 22:16:03'),
(4, 'Robotic Scratch', 'Ruang Teori 2', 'Kak Budi', '14.30', '15.30 WIB', 'Terjadwal', NULL, '2026-11-01 22:16:03', '2026-11-01 22:16:03'),
(5, 'AI Builder', 'Lab Robotika', 'Kak Nita', '16.00', '17.00 WIB', 'Terjadwal', NULL, '2026-11-01 22:16:03', '2026-11-01 22:16:03'),
(6, 'IoT Smart Home', 'Lab Inovasi', 'Kak Fajar', '17.00', '18.00 WIB', 'Terjadwal', NULL, '2026-11-01 22:16:03', '2026-11-01 22:16:03');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` smallint UNSIGNED NOT NULL,
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
(4, '2026_10_30_211500_add_username_to_users_table', 1),
(5, '2026_11_02_050105_create_arta_tabel_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `orang_tua`
--

CREATE TABLE `orang_tua` (
  `id_orang_tua` int UNSIGNED NOT NULL,
  `id_keluarga` int UNSIGNED NOT NULL,
  `id_pengguna` int UNSIGNED NOT NULL,
  `jenis_wali` enum('Primary_Parent','Secondary_Parent') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_lengkap` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `hubungan` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nomor_whatsapp` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orang_tua`
--

INSERT INTO `orang_tua` (`id_orang_tua`, `id_keluarga`, `id_pengguna`, `jenis_wali`, `nama_lengkap`, `hubungan`, `nomor_whatsapp`) VALUES
(1, 1, 5, 'Primary_Parent', 'Budi Santoso', 'Ayah', '081234567890'),
(2, 1, 9, 'Secondary_Parent', 'Siti Aminah', 'Ibu', '081234567892');

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
-- Table structure for table `pengguna`
--

CREATE TABLE `pengguna` (
  `id_pengguna` int UNSIGNED NOT NULL,
  `username` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_hash` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('Admin','Tutor','Orang_Tua','Siswa') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_aktif` tinyint(1) NOT NULL DEFAULT '1',
  `jumlah_gagal_login` int UNSIGNED NOT NULL DEFAULT '0',
  `terkunci_sampai` datetime DEFAULT NULL,
  `dibuat_pada` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pengguna`
--

INSERT INTO `pengguna` (`id_pengguna`, `username`, `password_hash`, `role`, `status_aktif`, `jumlah_gagal_login`, `terkunci_sampai`, `dibuat_pada`) VALUES
(1, 'admin', '$2y$10$U6580BdNQNHp2FO9pJQFu.FKo73we0kre5esH.P32AJfsuAV2k6I2', 'Admin', 1, 0, NULL, '2026-10-29 14:11:20'),
(5, 'wali.budi', '$2y$10$b5nWw/9LunGx8.ZH9B0TaOxVBBpZsEE9g4MKXusNLhz8KWTxSk8Tq', 'Orang_Tua', 1, 0, NULL, '2026-10-31 20:13:48'),
(7, 'tutor.rina', '$2y$10$zf4gDz.DNX7Ngn/SzFD5MuBO0P6YxnHyqsaAjUyMiQs.CHoRSj.Si', 'Tutor', 1, 0, NULL, '2026-11-01 13:54:09'),
(8, 'siswa.andi', '$2y$10$/Dp4loNV.ZFiPnHeieZ.i.yDrkf8B0V9iizjAvjVaiM6bVnB4I8D6', 'Siswa', 1, 0, NULL, '2026-11-01 14:17:23'),
(9, 'wali.siti', '$2y$10$deVhWz6Den5WWFgfBG9f7uJfZuUYLwERxcO1uhTPomlE.D2m.4unu', 'Orang_Tua', 1, 0, NULL, '2026-11-01 14:22:59');

-- --------------------------------------------------------

--
-- Table structure for table `pengumuman`
--

CREATE TABLE `pengumuman` (
  `id` bigint UNSIGNED NOT NULL,
  `judul` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `konten` text COLLATE utf8mb4_unicode_ci,
  `kategori` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Umum',
  `is_penting` tinyint(1) NOT NULL DEFAULT '0',
  `tanggal` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pengumuman`
--

INSERT INTO `pengumuman` (`id`, `judul`, `konten`, `kategori`, `is_penting`, `tanggal`, `created_at`, `updated_at`) VALUES
(1, 'Pengumuman Penting', 'Jadwal ujian sertifikasi robotika semester ganjil dimulai pekan depan.', 'PENTING', 1, '26 Okt 2026', '2026-11-01 22:16:03', '2026-11-01 22:16:03'),
(2, 'Pengumuman Kelas', 'Penambahan modul baru AI Builder di lab robotika.', 'Akademik', 0, '24 Okt 2026', '2026-11-01 22:16:03', '2026-11-01 22:16:03');

-- --------------------------------------------------------

--
-- Table structure for table `reschedule`
--

CREATE TABLE `reschedule` (
  `id` bigint UNSIGNED NOT NULL,
  `nama_siswa` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `inisial` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kelas` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jadwal_lama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jadwal_baru` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Menunggu',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reschedule`
--

INSERT INTO `reschedule` (`id`, `nama_siswa`, `inisial`, `kelas`, `jadwal_lama`, `jadwal_baru`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Alya Putri', 'AP', 'Junior Robotics', '28 Okt', '30 Okt', 'Menunggu', '2026-11-01 22:16:03', '2026-11-01 22:16:03'),
(2, 'Raka Kurniawan', 'RK', 'Python for Kids', '29 Okt', '31 Okt', 'Menunggu', '2026-11-01 22:16:03', '2026-11-01 22:16:03');

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
('xpEtf9e6CIoUOAlzq5VjExkBwOHWKDBDNpFYdrna', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36 Edg/156.0.0.0', 'eyJfdG9rZW4iOiJJQ3Jhc1dnNHZHRGxIQndtREltVnN6c1luTGx2Y25zNDlpRlJibmVZIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL3JhY2Nlci50ZXN0XC9sb2dpbiIsInJvdXRlIjoibG9naW4ifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==', 1793597677);

-- --------------------------------------------------------

--
-- Table structure for table `siswa`
--

CREATE TABLE `siswa` (
  `id_siswa` int UNSIGNED NOT NULL,
  `id_keluarga` int UNSIGNED NOT NULL,
  `id_pengguna` int UNSIGNED NOT NULL,
  `nama_lengkap` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_panggilan` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenjang` enum('TK','SD') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `kelas` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `status_siswa` enum('Aktif','Non_Aktif_Alumni') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Aktif',
  `dibuat_pada` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `siswa`
--

INSERT INTO `siswa` (`id_siswa`, `id_keluarga`, `id_pengguna`, `nama_lengkap`, `nama_panggilan`, `jenjang`, `kelas`, `tanggal_lahir`, `status_siswa`, `dibuat_pada`) VALUES
(1, 1, 8, 'Andi Santoso', 'Andi', 'SD', '3', '2017-05-12', 'Aktif', '2026-11-01 14:17:24');

-- --------------------------------------------------------

--
-- Table structure for table `tutor`
--

CREATE TABLE `tutor` (
  `id_tutor` int UNSIGNED NOT NULL,
  `id_pengguna` int UNSIGNED NOT NULL,
  `nama_lengkap` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_panggilan` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nomor_whatsapp` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `spesialisasi` enum('TK','SD') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `dibuat_pada` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tutor`
--

INSERT INTO `tutor` (`id_tutor`, `id_pengguna`, `nama_lengkap`, `nama_panggilan`, `nomor_whatsapp`, `spesialisasi`, `dibuat_pada`) VALUES
(1, 7, 'Rina Maharani', 'Kak Rina', '081234567891', 'SD', '2026-11-01 13:54:09');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin', 'admin@raccer.com', NULL, '$2y$12$hYPR4aLjBuyXD7stA2qhQ.I.oJiba56MRL0MzkLPq4WeennMDYP2q', NULL, '2026-11-01 22:15:48', '2026-11-01 22:15:48');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `akun_keluarga`
--
ALTER TABLE `akun_keluarga`
  ADD PRIMARY KEY (`id_keluarga`),
  ADD KEY `idx_keluarga_kota` (`kota_kabupaten`),
  ADD KEY `idx_keluarga_kecamatan` (`kecamatan`);

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
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  ADD KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`);

--
-- Indexes for table `jadwal`
--
ALTER TABLE `jadwal`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `orang_tua`
--
ALTER TABLE `orang_tua`
  ADD PRIMARY KEY (`id_orang_tua`),
  ADD UNIQUE KEY `uq_orang_tua_pengguna` (`id_pengguna`),
  ADD UNIQUE KEY `uq_keluarga_jenis_wali` (`id_keluarga`,`jenis_wali`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `pengguna`
--
ALTER TABLE `pengguna`
  ADD PRIMARY KEY (`id_pengguna`),
  ADD UNIQUE KEY `uq_pengguna_username` (`username`);

--
-- Indexes for table `pengumuman`
--
ALTER TABLE `pengumuman`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reschedule`
--
ALTER TABLE `reschedule`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `siswa`
--
ALTER TABLE `siswa`
  ADD PRIMARY KEY (`id_siswa`),
  ADD UNIQUE KEY `uq_siswa_pengguna` (`id_pengguna`),
  ADD KEY `idx_siswa_keluarga` (`id_keluarga`);

--
-- Indexes for table `tutor`
--
ALTER TABLE `tutor`
  ADD PRIMARY KEY (`id_tutor`),
  ADD UNIQUE KEY `uq_tutor_pengguna` (`id_pengguna`);

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
-- AUTO_INCREMENT for table `akun_keluarga`
--
ALTER TABLE `akun_keluarga`
  MODIFY `id_keluarga` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jadwal`
--
ALTER TABLE `jadwal`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `orang_tua`
--
ALTER TABLE `orang_tua`
  MODIFY `id_orang_tua` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `pengguna`
--
ALTER TABLE `pengguna`
  MODIFY `id_pengguna` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `pengumuman`
--
ALTER TABLE `pengumuman`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `reschedule`
--
ALTER TABLE `reschedule`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `siswa`
--
ALTER TABLE `siswa`
  MODIFY `id_siswa` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tutor`
--
ALTER TABLE `tutor`
  MODIFY `id_tutor` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orang_tua`
--
ALTER TABLE `orang_tua`
  ADD CONSTRAINT `fk_orang_tua_keluarga` FOREIGN KEY (`id_keluarga`) REFERENCES `akun_keluarga` (`id_keluarga`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_orang_tua_pengguna` FOREIGN KEY (`id_pengguna`) REFERENCES `pengguna` (`id_pengguna`) ON DELETE CASCADE;

--
-- Constraints for table `siswa`
--
ALTER TABLE `siswa`
  ADD CONSTRAINT `fk_siswa_keluarga` FOREIGN KEY (`id_keluarga`) REFERENCES `akun_keluarga` (`id_keluarga`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_siswa_pengguna` FOREIGN KEY (`id_pengguna`) REFERENCES `pengguna` (`id_pengguna`) ON DELETE CASCADE;

--
-- Constraints for table `tutor`
--
ALTER TABLE `tutor`
  ADD CONSTRAINT `fk_tutor_pengguna` FOREIGN KEY (`id_pengguna`) REFERENCES `pengguna` (`id_pengguna`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
