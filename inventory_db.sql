-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 09 Bulan Mei 2025 pada 01.26
-- Versi server: 8.0.39
-- Versi PHP: 8.3.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `inventory_db`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `currencies`
--

CREATE TABLE `currencies` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `exchange_rate` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `currencies`
--

INSERT INTO `currencies` (`id`, `name`, `exchange_rate`, `created_at`, `updated_at`) VALUES
(1, 'USD', '16500', '2025-05-07 20:10:36', '2025-05-07 23:48:00'),
(2, 'IDR', '1', '2025-05-07 20:10:53', '2025-05-07 23:21:32'),
(5, 'SGD', '12689', '2025-05-07 23:21:45', '2025-05-07 23:34:15'),
(6, 'RMB', '2275', '2025-05-07 23:21:54', '2025-05-07 23:34:47');

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
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
-- Struktur dari tabel `inventories`
--

CREATE TABLE `inventories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` decimal(8,2) NOT NULL,
  `unit` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `currency_id` bigint UNSIGNED DEFAULT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `img` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `qrcode_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `qrcode` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `inventories`
--

INSERT INTO `inventories` (`id`, `name`, `quantity`, `unit`, `price`, `currency_id`, `location`, `img`, `qrcode_path`, `qrcode`, `created_at`, `updated_at`) VALUES
(1, 'test1', 2.00, 'cm', 3.00, 1, 'rack 1', 'images/wcaMc34z6ds7oom0pGO4S8LTKNn2SYiA4erhhNCu.png', 'qrcodes/qr_681c51bd40cab.svg', NULL, '2025-05-06 01:08:10', '2025-05-07 23:39:57'),
(5, 'testsss', 3.00, 'cm', 36000.00, 2, 'rack 1', 'images/s09uYxaVyOVAiaHhPL8qkZ5evfOPz20CSIvc2i37.png', 'qrcodes/qr_681c51cd30a58.svg', NULL, '2025-05-06 02:02:39', '2025-05-07 23:40:13'),
(6, 'testsss', 3.00, 'cm', 4.70, 5, 'rack 1', 'images/EJawuEryO0S8LcXMEyESr9GFAjky80u7UT242gND.png', 'qrcodes/qr_681c543806247.svg', NULL, '2025-05-06 02:13:32', '2025-05-07 23:50:32'),
(7, 'ascwwww', 4.00, 'frbr', 190000.00, 2, 'rack 1', 'images/Q7G4xYb8OaBh1Eg3hEHZOoo8WESH3ziVBmEN8bux.png', 'qrcodes/qr_681c5442645a9.svg', NULL, '2025-05-06 02:14:44', '2025-05-07 23:50:42'),
(8, 'test kain1', 1.00, 'cm', 20000.00, NULL, 'rack 3', NULL, NULL, NULL, '2025-05-06 02:47:30', '2025-05-06 02:47:30'),
(9, 'test kain1111', 1.00, 'cm', 20000.00, NULL, 'rack 4', 'inventory_images/OzGUFnyq4cF9aeS1laZxPIAaZCJULFcP0AcVlvRT.png', 'qrcodes/qr_6819db322204a.svg', NULL, '2025-05-06 02:48:36', '2025-05-06 02:49:38'),
(10, 'test kain2', 2.00, 'cm', 20001.00, NULL, 'rack 4', 'inventory_images/ipHDglDAK6YCIK85AzuR5vnz8zEBDzf6GRCvjjoz.jpg', 'qrcodes/qr_6819db3ca9cac.svg', NULL, '2025-05-06 02:48:36', '2025-05-06 02:49:48'),
(11, 'test kain3', 3.00, 'cm', 20002.00, NULL, 'rack 5', NULL, 'qrcodes/qr_6819daf479902.svg', NULL, '2025-05-06 02:48:36', '2025-05-06 02:48:36'),
(12, 'test kain4', 4.00, 'cm', 20003.00, NULL, 'rack 6', NULL, 'qrcodes/qr_6819daf47c1d3.svg', NULL, '2025-05-06 02:48:36', '2025-05-06 02:48:36'),
(13, 'test kain5', 5.00, 'cm', 20004.00, NULL, 'rack 7', NULL, 'qrcodes/qr_6819daf47f2d7.svg', NULL, '2025-05-06 02:48:36', '2025-05-06 02:48:36'),
(14, 'test kain133', 2.00, 'cm', 34567.00, NULL, 'rack 5', NULL, 'qrcodes/qr_6819db9c282fe.svg', NULL, '2025-05-06 02:51:24', '2025-05-06 02:51:24'),
(15, 'aseee', 1.00, 'g', 3434.00, NULL, 'erbgererg', NULL, 'qrcodes/qr_681b0feae9a9b.svg', NULL, '2025-05-07 00:02:27', '2025-05-07 00:46:50'),
(16, 'foam2', 5.00, 'mm', 2000.00, NULL, NULL, NULL, 'qrcodes/qr_681b32398ecd5.svg', NULL, '2025-05-07 01:44:51', '2025-05-07 03:13:13'),
(17, 'test lem1', 2.00, 'kaleng', 34567.00, NULL, 'rack 5', NULL, 'qrcodes/qr_681c17545e814.svg', NULL, '2025-05-07 19:30:44', '2025-05-07 19:30:45'),
(18, 'test lem2', 3.00, 'kaleng', 34568.00, NULL, 'rack 6', NULL, 'qrcodes/qr_681c17557ef68.svg', NULL, '2025-05-07 19:30:45', '2025-05-07 19:30:45'),
(19, 'test lem3', 4.00, 'kaleng', 34569.00, NULL, 'rack 7', NULL, 'qrcodes/qr_681c1755816ca.svg', NULL, '2025-05-07 19:30:45', '2025-05-07 19:30:45'),
(20, 'test lem1', 2.00, 'kaleng', 34567.00, NULL, 'rack 5', NULL, 'qrcodes/qr_681c548731904.svg', NULL, '2025-05-07 23:51:51', '2025-05-07 23:51:51'),
(21, 'test lem2', 3.00, 'kaleng', 34568.00, NULL, 'rack 6', NULL, 'qrcodes/qr_681c548739d23.svg', NULL, '2025-05-07 23:51:51', '2025-05-07 23:51:51'),
(23, 'test foam2', 2.00, 'm', 34567.00, NULL, 'rack 5', NULL, 'qrcodes/qr_681c559c5047a.svg', NULL, '2025-05-07 23:56:28', '2025-05-07 23:56:28'),
(24, 'test foam3', 3.00, 'm', 34568.00, NULL, 'rack 6', NULL, 'qrcodes/qr_681c559c58b30.svg', NULL, '2025-05-07 23:56:28', '2025-05-07 23:56:28'),
(25, 'test foam4', 4.00, 'm', 34569.00, 2, 'rack 7', 'inventory_images/SYDrmC8IvkutJIWKAg70VzHR29Fx7gHp2RzUIw40.png', 'qrcodes/qr_681c55d741891.svg', NULL, '2025-05-07 23:56:28', '2025-05-07 23:57:27');

-- --------------------------------------------------------

--
-- Struktur dari tabel `inventory_transactions`
--

CREATE TABLE `inventory_transactions` (
  `id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `material_requests`
--

CREATE TABLE `material_requests` (
  `id` bigint UNSIGNED NOT NULL,
  `inventory_id` bigint UNSIGNED NOT NULL,
  `project_id` bigint UNSIGNED NOT NULL,
  `qty` decimal(10,2) NOT NULL,
  `requested_by` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `department` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remark` text COLLATE utf8mb4_unicode_ci,
  `status` enum('pending','approved','delivered') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `material_requests`
--

INSERT INTO `material_requests` (`id`, `inventory_id`, `project_id`, `qty`, `requested_by`, `department`, `remark`, `status`, `created_at`, `updated_at`) VALUES
(6, 15, 5, 5.80, 'mascot', 'mascot', 'test', 'approved', '2025-05-07 00:30:22', '2025-05-07 23:53:24'),
(7, 6, 4, 2.00, 'mascot', 'mascot', NULL, 'pending', '2025-05-07 02:05:40', '2025-05-07 02:05:40'),
(8, 9, 7, 6.00, 'mascot', 'mascot', NULL, 'pending', '2025-05-07 02:05:53', '2025-05-07 02:05:53'),
(9, 15, 4, 5.00, 'mascot', 'mascot', NULL, 'pending', '2025-05-07 02:06:52', '2025-05-07 02:06:52'),
(10, 7, 3, 6.00, 'mascot', 'mascot', NULL, 'pending', '2025-05-07 02:06:52', '2025-05-07 02:06:52'),
(11, 14, 2, 5.00, 'mascot', 'mascot', NULL, 'pending', '2025-05-07 02:06:52', '2025-05-07 02:06:52'),
(12, 16, 3, 2.00, 'mascot', 'mascot', NULL, 'pending', '2025-05-07 02:06:52', '2025-05-07 02:06:52'),
(13, 13, 4, 4.00, 'mascot', 'mascot', NULL, 'pending', '2025-05-07 02:06:52', '2025-05-07 02:06:52'),
(14, 6, 5, 3.00, 'mascot', 'mascot', NULL, 'pending', '2025-05-07 02:07:56', '2025-05-07 02:07:56'),
(15, 14, 6, 5.00, 'mascot', 'mascot', NULL, 'pending', '2025-05-07 02:07:56', '2025-05-07 02:07:56'),
(16, 14, 5, 2.00, 'mascot', 'mascot', NULL, 'pending', '2025-05-07 02:07:56', '2025-05-07 02:07:56'),
(17, 12, 5, 4.00, 'mascot', 'mascot', NULL, 'pending', '2025-05-07 02:07:56', '2025-05-07 02:07:56'),
(18, 16, 6, 4.00, 'mascot', 'mascot', NULL, 'pending', '2025-05-07 02:07:56', '2025-05-07 02:07:56'),
(19, 5, 2, 4.00, 'mascot', 'mascot', NULL, 'pending', '2025-05-07 02:38:48', '2025-05-07 02:38:48'),
(20, 14, 5, 1.00, 'admin', 'management', NULL, 'pending', '2025-05-07 02:39:22', '2025-05-07 02:39:22'),
(21, 6, 4, 1.00, 'admin', 'management', NULL, 'pending', '2025-05-07 02:41:16', '2025-05-07 02:41:16'),
(22, 7, 3, 2.00, 'admin', 'management', NULL, 'pending', '2025-05-07 02:41:16', '2025-05-07 02:41:16'),
(23, 15, 4, 1.00, 'admin', 'management', NULL, 'pending', '2025-05-07 02:41:16', '2025-05-07 02:41:16'),
(24, 13, 2, 3.50, 'admin', 'management', NULL, 'pending', '2025-05-07 02:41:16', '2025-05-07 02:41:16'),
(25, 14, 6, 1.07, 'admin', 'management', NULL, 'pending', '2025-05-07 02:41:16', '2025-05-07 02:41:16'),
(26, 6, 2, 2.60, 'admin', 'management', 'sds', 'pending', '2025-05-08 00:16:52', '2025-05-08 00:16:52'),
(27, 1, 1, 2.90, 'admin', 'management', NULL, 'pending', '2025-05-08 00:17:05', '2025-05-08 00:17:05');

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2025_05_06_061656_create_materials_table', 2),
(6, '2025_05_06_061716_create_projects_table', 2),
(7, '2025_05_06_061729_create_inventory_transactions_table', 2),
(8, '2025_05_06_061741_create_material_requests_table', 2),
(9, '2025_05_06_062109_add_role_to_users_table', 2),
(10, '2025_05_06_062420_create_users_table', 3),
(11, '2025_05_06_074612_create_inventories_table', 4),
(12, '2025_05_06_085036_add_qrcode_to_inventories_table', 5),
(13, '2025_05_06_085916_add_qrcode_path_to_inventories_table', 6),
(14, '2025_05_07_034921_create_projects_table', 7),
(15, '2025_05_07_041628_create_material_requests', 8),
(16, '2025_05_07_042921_create_material_requests_table', 9),
(17, '2025_05_07_045301_create_material_requests', 10),
(18, '2025_05_07_095349_add_remark_to_material_requests_table', 11),
(19, '2025_05_08_023751_create_currencies_table', 12),
(20, '2025_05_08_023823_add_currency_id_to_inventories_table', 12);

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `projects`
--

CREATE TABLE `projects` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `qty` int NOT NULL,
  `img` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deadline` date DEFAULT NULL,
  `department` enum('mascot','costume','mascot&costume') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `projects`
--

INSERT INTO `projects` (`id`, `name`, `qty`, `img`, `deadline`, `department`, `created_at`, `updated_at`) VALUES
(1, 'Bobo', 5, NULL, '2025-05-08', 'costume', '2025-05-06 21:01:01', '2025-05-06 21:01:01'),
(2, 'bobobo23', 7, 'projects/2WrpOf288zSCCadIILTxCwdBrj9DBBPanF6VD1hM.png', '2025-05-17', 'mascot&costume', '2025-05-06 21:01:24', '2025-05-06 21:01:52'),
(3, 'test', 2, NULL, NULL, 'costume', '2025-05-06 23:54:45', '2025-05-06 23:54:45'),
(4, 'asa', 5, NULL, NULL, 'costume', '2025-05-06 23:56:30', '2025-05-06 23:56:30'),
(5, 'aba', 4, NULL, NULL, 'costume', '2025-05-06 23:56:42', '2025-05-06 23:56:42'),
(6, 'Standart Costume', 2, NULL, NULL, 'costume', '2025-05-07 00:15:22', '2025-05-07 00:15:22'),
(7, 'upin', 5, NULL, NULL, 'costume', '2025-05-07 00:45:03', '2025-05-07 00:45:03');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('super_admin','admin_logistic','admin_mascot','admin_costume','admin_finance') COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'admin', '$2y$10$d92eVtlqhO1zea7Ofb/jKuR6s.LKe7GYqVQkAMTEDzm.sk34iBC5C', 'super_admin', NULL, '2025-05-05 23:37:44', '2025-05-06 20:24:34'),
(2, 'mascot', '$2y$10$agrAQMX7Zu08uOYtHcQQYeWTO1kge9xJWT.mIFMBX/XhItIyToAIy', 'admin_mascot', NULL, '2025-05-06 00:07:01', '2025-05-06 00:07:01'),
(3, 'costume', '$2y$10$Q2V3kGCkazSCAtjXQ3No4./RayNoZAMEVfNdmFr//vXHBqP87xrs6', 'admin_costume', NULL, '2025-05-06 00:07:27', '2025-05-06 00:07:27'),
(4, 'store', '$2y$10$IbH1.hfTsurL05wNwT6VTepZnmQn1cyxoa.UwIjIN739.0hV11h.a', 'admin_logistic', NULL, '2025-05-06 00:07:43', '2025-05-06 00:07:43'),
(5, 'finance', '$2y$10$LijMxv8aRqT7/e0sX5me/.b/BTvBoKmpZdPEfw/OmGzQfavpUxYxu', 'admin_finance', NULL, '2025-05-06 00:08:01', '2025-05-06 00:08:01'),
(7, 'test2', '$2y$10$BqyG8JxeoatjpMYMlp0TUeeF92NR92rEewFq2n2YJE7OS7/Gp9Jq6', 'admin_logistic', NULL, '2025-05-06 00:42:32', '2025-05-06 00:42:42'),
(8, 'admin2', '$2y$10$8C3CJ5gXSQzUa4tqhsuPDOa12unXtOciT3dPkPuNUdgJtg1BXxp22', 'super_admin', NULL, '2025-05-06 20:24:17', '2025-05-06 20:24:17');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `currencies`
--
ALTER TABLE `currencies`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `currencies_name_unique` (`name`);

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indeks untuk tabel `inventories`
--
ALTER TABLE `inventories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inventories_currency_id_foreign` (`currency_id`);

--
-- Indeks untuk tabel `inventory_transactions`
--
ALTER TABLE `inventory_transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `material_requests`
--
ALTER TABLE `material_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `material_requests_inventory_id_foreign` (`inventory_id`),
  ADD KEY `material_requests_project_id_foreign` (`project_id`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indeks untuk tabel `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indeks untuk tabel `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_username_unique` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `currencies`
--
ALTER TABLE `currencies`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `inventories`
--
ALTER TABLE `inventories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT untuk tabel `inventory_transactions`
--
ALTER TABLE `inventory_transactions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `material_requests`
--
ALTER TABLE `material_requests`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT untuk tabel `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `projects`
--
ALTER TABLE `projects`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `inventories`
--
ALTER TABLE `inventories`
  ADD CONSTRAINT `inventories_currency_id_foreign` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `material_requests`
--
ALTER TABLE `material_requests`
  ADD CONSTRAINT `material_requests_inventory_id_foreign` FOREIGN KEY (`inventory_id`) REFERENCES `inventories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `material_requests_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
