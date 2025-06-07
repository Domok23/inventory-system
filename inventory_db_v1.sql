-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 21 Bulan Mei 2025 pada 01.11
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
-- Struktur dari tabel `categories`
--

CREATE TABLE `categories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `categories`
--

INSERT INTO `categories` (`id`, `name`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Fabric', '2025-05-16 05:01:14', '2025-05-16 05:01:14', NULL),
(8, 'Foam', '2025-05-19 07:01:43', '2025-05-19 07:01:43', NULL),
(9, 'Button', '2025-05-19 07:01:58', '2025-05-19 07:01:58', NULL),
(10, 'Zipper', '2025-05-19 07:02:52', '2025-05-19 07:02:52', NULL),
(11, 'Metal', '2025-05-19 07:03:05', '2025-05-19 07:03:05', NULL),
(12, 'Test Cat', '2025-05-19 07:58:52', '2025-05-19 07:58:52', NULL),
(13, 'test1', '2025-05-19 09:01:48', '2025-05-19 09:01:48', NULL),
(14, 'Busa', '2025-05-20 01:25:20', '2025-05-20 01:25:20', NULL),
(15, 'final test cat', '2025-05-20 06:36:05', '2025-05-20 06:36:05', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `currencies`
--

CREATE TABLE `currencies` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `exchange_rate` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `currencies`
--

INSERT INTO `currencies` (`id`, `name`, `exchange_rate`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'USD', '16500', '2025-05-07 20:10:36', '2025-05-07 23:48:00', NULL),
(2, 'IDR', '1', '2025-05-07 20:10:53', '2025-05-07 23:21:32', NULL),
(5, 'SGD', '12689', '2025-05-07 23:21:45', '2025-05-07 23:34:15', NULL),
(6, 'RMB', '2275', '2025-05-07 23:21:54', '2025-05-07 23:34:47', NULL),
(15, 'Bath', '6500', '2025-05-19 07:59:18', '2025-05-19 07:59:18', NULL),
(16, 'Rial', '10000', '2025-05-19 08:50:07', '2025-05-19 08:50:07', NULL),
(17, 'SAU', '12000', '2025-05-19 08:50:49', '2025-05-19 08:50:49', NULL),
(18, 'test', '1233', '2025-05-19 09:01:00', '2025-05-19 09:01:00', NULL),
(19, 'test222', '2222', '2025-05-19 09:02:01', '2025-05-19 09:02:01', NULL),
(20, 'fft', '23', '2025-05-19 09:13:07', '2025-05-19 09:13:07', NULL),
(21, 'tesyyyy', '334', '2025-05-19 09:19:44', '2025-05-19 09:19:44', NULL),
(22, 'testsssttt', '455', '2025-05-19 09:20:02', '2025-05-19 09:20:02', NULL),
(23, 'sdcvsd', '4545', '2025-05-19 09:21:16', '2025-05-19 09:21:16', NULL),
(24, 'asxcas', '23', '2025-05-20 03:52:09', '2025-05-20 03:52:09', NULL),
(25, 'final test cur', '10000', '2025-05-20 06:36:40', '2025-05-20 06:36:40', NULL);

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
-- Struktur dari tabel `goods_in`
--

CREATE TABLE `goods_in` (
  `id` bigint UNSIGNED NOT NULL,
  `goods_out_id` bigint UNSIGNED DEFAULT NULL,
  `inventory_id` bigint UNSIGNED DEFAULT NULL,
  `project_id` bigint UNSIGNED DEFAULT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `returned_by` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `returned_at` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `remark` text COLLATE utf8mb4_unicode_ci,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `goods_out`
--

CREATE TABLE `goods_out` (
  `id` bigint UNSIGNED NOT NULL,
  `material_request_id` bigint UNSIGNED DEFAULT NULL,
  `inventory_id` bigint UNSIGNED NOT NULL,
  `project_id` bigint UNSIGNED DEFAULT NULL,
  `requested_by` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `department` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `remark` text COLLATE utf8mb4_unicode_ci,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `goods_out`
--

INSERT INTO `goods_out` (`id`, `material_request_id`, `inventory_id`, `project_id`, `requested_by`, `department`, `quantity`, `created_at`, `updated_at`, `remark`, `deleted_at`) VALUES
(14, 37, 72, 26, 'admin', 'management', 5.00, '2025-05-19 08:30:28', '2025-05-19 08:30:28', 'baru 50%', NULL),
(15, 37, 72, 26, 'admin', 'management', 5.00, '2025-05-19 08:30:59', '2025-05-19 08:30:59', 'dah 100%', NULL),
(16, 39, 64, 20, 'admin', 'management', 3.00, '2025-05-19 08:34:02', '2025-05-19 08:34:02', NULL, NULL),
(17, NULL, 69, 25, 'admin', 'management', 1.00, '2025-05-20 01:34:37', '2025-05-20 01:34:37', NULL, NULL),
(18, 50, 59, 15, 'admin', 'management', 5.00, '2025-05-20 04:32:34', '2025-05-20 04:32:34', 'goods out 1', NULL),
(19, 50, 59, 15, 'admin', 'management', 1.50, '2025-05-20 04:34:39', '2025-05-20 04:34:39', NULL, NULL),
(20, 73, 86, 39, 'admin', 'management', 4.60, '2025-05-20 06:42:21', '2025-05-20 06:42:21', NULL, NULL),
(21, 73, 86, 39, 'admin', 'management', 2.20, '2025-05-20 06:43:24', '2025-05-20 06:43:24', NULL, NULL),
(22, 73, 86, 39, 'admin', 'management', 0.10, '2025-05-20 09:08:53', '2025-05-20 09:27:52', NULL, NULL),
(23, 39, 64, 20, 'admin', 'management', 15.00, '2025-05-20 09:46:10', '2025-05-20 09:46:10', NULL, NULL),
(24, 39, 64, 20, 'admin', 'management', 2.00, '2025-05-20 09:48:45', '2025-05-20 09:48:45', NULL, NULL),
(25, 39, 64, 20, 'admin', 'management', 3.00, '2025-05-20 09:49:24', '2025-05-20 09:49:24', NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `inventories`
--

CREATE TABLE `inventories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` decimal(8,2) NOT NULL,
  `unit` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `currency_id` bigint UNSIGNED DEFAULT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `img` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `qrcode_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `qrcode` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `category_id` bigint UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `inventories`
--

INSERT INTO `inventories` (`id`, `name`, `quantity`, `unit`, `price`, `currency_id`, `location`, `img`, `qrcode_path`, `qrcode`, `created_at`, `updated_at`, `category_id`, `deleted_at`) VALUES
(57, 'Fabric 1', 200.00, 'cm', 20000.00, 2, 'Rack 1 Batam', 'images/RQixGWyIQznJG3q0qmorDMdL6Assm4fJaNuHwxWv.jpg', 'qrcodes/qr_682add8dc31aa.svg', NULL, '2025-05-19 07:28:13', '2025-05-19 07:28:14', 1, NULL),
(58, 'Fabric 2', 340.00, 'mm', 2500.00, 6, 'Rack 3 Batam', 'images/CFqzHbQLMAUkIOsjx7iE7ag6XwtBl8oX7KmIxbFc.jpg', 'qrcodes/qr_682addba06061.svg', NULL, '2025-05-19 07:28:58', '2025-05-19 07:28:58', 1, NULL),
(59, 'Buttons 1', 3993.50, 'pcs', 0.50, 5, 'Singapore', 'images/aYwSO3uyO1eF3eKfQvSqZ9fafM6vZXstGMGRDcgM.jpg', 'qrcodes/qr_682addf823578.svg', NULL, '2025-05-19 07:30:00', '2025-05-20 04:34:39', 9, NULL),
(60, 'Buttons 2', 500.00, 'pack', 0.25, 1, 'Batam', 'images/kuBk9OHnTPF6YXQoUGsHTdIBUVityxZkTHdQX8ZZ.jpg', 'qrcodes/qr_682ae0a61b8fb.svg', NULL, '2025-05-19 07:31:00', '2025-05-19 07:41:26', 9, NULL),
(61, 'Foam 1', 30.00, 'm', 12000.00, 2, 'Rack 5', 'images/MyI4zCLVzse1nuHh2WBTaHtNmxmudSuO6DjrRsoj.jpg', 'qrcodes/qr_682ade57c7118.svg', NULL, '2025-05-19 07:31:35', '2025-05-19 07:31:35', 8, NULL),
(62, 'Foam 2', 23.00, 'kg', 1300.00, 6, 'Rack 5', 'images/TadRnNm3YJxNLKVTOC9YLpaosnELQRykx8Cum2fz.jpg', 'qrcodes/qr_682ade8e3152a.svg', NULL, '2025-05-19 07:32:30', '2025-05-19 07:32:30', 8, NULL),
(63, 'Zipper 1', 130.00, 'set', 2.00, 5, 'Batam 2', 'images/zG9Ng3mWP1sAMetg6Osmwix6SJREh8a7MGyCJniV.jpg', 'qrcodes/qr_682adee3d4810.svg', NULL, '2025-05-19 07:33:55', '2025-05-19 07:33:55', 10, NULL),
(64, 'Zipper 2', 477.00, 'pcs', 1.40, 1, 'Rack 4', 'images/LIL8BXOR6SR4TxGCG5rhllwaHdNukXziNsgA9x6Y.jpg', 'qrcodes/qr_682adf0ecccc5.svg', NULL, '2025-05-19 07:34:38', '2025-05-20 09:49:24', 10, NULL),
(65, 'Foam 3', 105.00, 'pcs', 20000.00, 2, 'rack 4', 'images/ltLYZMLCILT2PW0vwpWG1WK9qstwTsRCaGsfHXwS.jpg', 'qrcodes/qr_682ae01889494.svg', NULL, '2025-05-19 07:39:04', '2025-05-19 07:39:04', 8, NULL),
(66, 'Foam 4', 14.00, 'pack', 2.00, 5, 'rack 3', 'images/C46JybX6qaYsRr28kZrq9hfaK5oZcgTfYK2Y9D0Q.jpg', 'qrcodes/qr_682ae054366fe.svg', NULL, '2025-05-19 07:39:51', '2025-05-19 07:40:04', 8, NULL),
(67, 'Fabric 4', 234.00, 'cm', 3400.00, 6, 'rack 4', 'images/5biAk4B9XCpZzfyVRdht1uvxyrkJ4Qhli5fu7aya.jpg', 'qrcodes/qr_682ae07faf63e.svg', NULL, '2025-05-19 07:40:47', '2025-05-19 07:40:47', 1, NULL),
(68, 'Fabric 5', 378.00, 'kg', 4000.00, 6, 'rack 1', 'images/UGrKEVmZQdCRaxzaqOxb6vnxvtiQiD5XXvq2nzxt.jpg', 'qrcodes/qr_682ae0f4b4cbb.svg', NULL, '2025-05-19 07:42:44', '2025-05-19 07:42:44', 1, NULL),
(69, 'Button 4', 455.00, 'kg', 200.00, 2, 'rack 3', 'images/lqKjFQd3RO33i6QFaIbZdQOMsq89aG5btkL3t6AZ.jpg', 'qrcodes/qr_682ae1311f7f5.svg', NULL, '2025-05-19 07:43:45', '2025-05-20 01:34:37', 9, NULL),
(70, 'Fabric 7', 5.00, 'roll', 2700.00, 6, 'rack 1', 'images/oWL0AjWu2KW0P6jyGEcK9aQFI3DZFe5G4kvqU9jg.jpg', 'qrcodes/qr_682ae196514d0.svg', NULL, '2025-05-19 07:45:26', '2025-05-19 07:45:26', 1, NULL),
(71, 'Quick Mat', 200.00, 'dm', 20.00, 5, 'rack 3', 'inventory_images/uulwQ8atWvEz0KObNt9F9oT42BJJB5JxDdY38zkC.jpg', 'qrcodes/qr_682ae472d411e.svg', NULL, '2025-05-19 07:49:49', '2025-05-19 07:57:38', 11, NULL),
(72, 'Buttons 8', 990.00, 'pcs', 2000.00, 2, 'Rack 6', 'inventory_images/G3WOO8vGnYN5jkkEFhGUHH9nbRgTdcY3RRwA87PT.jpg', 'qrcodes/qr_682ae4426fee9.svg', NULL, '2025-05-19 07:52:22', '2025-05-19 08:30:59', 9, NULL),
(73, 'Metal 1', 232.00, 'kg', 0.00, NULL, NULL, NULL, NULL, NULL, '2025-05-19 08:03:56', '2025-05-19 08:03:56', NULL, NULL),
(74, 'Test Material 2', 200.00, 'cm', 0.00, 15, NULL, NULL, 'qrcodes/qr_682ae90b1a45a.svg', NULL, '2025-05-19 08:05:03', '2025-05-19 08:17:15', 1, NULL),
(75, 'test fabric', 2.00, 'dm', NULL, NULL, NULL, NULL, 'qrcodes/qr_682aeb186ba2d.svg', NULL, '2025-05-19 08:26:00', '2025-05-19 08:26:00', 1, NULL),
(76, 'test5464', 34.00, 'cm', 0.00, NULL, NULL, NULL, NULL, NULL, '2025-05-19 09:08:14', '2025-05-19 09:08:14', NULL, NULL),
(77, 'dfbdfb', 45.00, 'cm', 0.00, NULL, NULL, NULL, NULL, NULL, '2025-05-19 09:26:40', '2025-05-20 04:00:01', NULL, '2025-05-20 04:00:01'),
(83, 'sdcfswd', 23.00, 'cm', NULL, NULL, NULL, NULL, 'qrcodes/qr_682bfbd413f63.svg', NULL, '2025-05-20 03:49:40', '2025-05-20 03:59:58', 9, '2025-05-20 03:59:58'),
(84, 'ascasc', 34.00, 'cm', NULL, NULL, NULL, NULL, 'qrcodes/qr_682bfe50528c5.svg', NULL, '2025-05-20 04:00:16', '2025-05-20 04:00:16', 9, NULL),
(85, 'rgbrt', 400.00, 'cm', NULL, NULL, NULL, NULL, 'qrcodes/qr_682bfff523d59.svg', NULL, '2025-05-20 04:04:32', '2025-05-20 04:07:17', 9, NULL),
(86, 'final test mat', 293.10, 'final test unit', 3.00, 25, 'final test loc', 'inventory_images/pVP0NMrrE6UBLQIuBVqLZ97ybvP5TFxeAy8LtuKK.jpg', 'qrcodes/qr_682c2355f036d.svg', NULL, '2025-05-20 06:37:41', '2025-05-20 09:27:52', 15, NULL);

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
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `material_requests`
--

INSERT INTO `material_requests` (`id`, `inventory_id`, `project_id`, `qty`, `requested_by`, `department`, `remark`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(29, 69, 14, 1.00, 'admin', 'management', 'testttt', 'approved', '2025-05-19 07:49:10', '2025-05-19 08:06:38', NULL),
(30, 57, 17, 2.00, 'admin', 'management', 'testt', 'pending', '2025-05-19 07:49:10', '2025-05-19 07:49:10', NULL),
(31, 65, 21, 3.00, 'admin', 'management', 'testtting', 'pending', '2025-05-19 07:49:10', '2025-05-19 07:49:10', NULL),
(32, 63, 24, 5.00, 'admin', 'management', 'testingg4', 'pending', '2025-05-19 07:49:10', '2025-05-19 07:49:10', NULL),
(33, 71, 25, 1.00, 'admin', 'management', 'testing5', 'pending', '2025-05-19 07:51:33', '2025-05-19 07:51:33', NULL),
(34, 69, 15, 2.00, 'admin', 'management', NULL, 'approved', '2025-05-19 07:51:33', '2025-05-19 08:06:40', NULL),
(35, 70, 23, 1.00, 'admin', 'management', NULL, 'pending', '2025-05-19 07:51:33', '2025-05-19 07:51:33', NULL),
(36, 68, 19, 0.20, 'admin', 'management', NULL, 'pending', '2025-05-19 07:51:33', '2025-05-19 07:51:33', NULL),
(37, 72, 26, 0.00, 'admin', 'management', NULL, 'delivered', '2025-05-19 07:52:58', '2025-05-19 08:30:59', NULL),
(38, 66, 16, 0.60, 'admin', 'management', NULL, 'approved', '2025-05-19 07:53:23', '2025-05-19 08:06:43', NULL),
(39, 64, 20, 0.00, 'admin', 'management', NULL, 'delivered', '2025-05-19 07:53:52', '2025-05-20 09:49:24', NULL),
(40, 61, 23, 34.00, 'admin', 'management', NULL, 'pending', '2025-05-19 07:53:52', '2025-05-19 07:53:52', NULL),
(43, 75, 25, 2.00, 'admin', 'management', NULL, 'pending', '2025-05-20 02:05:06', '2025-05-20 02:05:06', NULL),
(44, 69, 25, 1.00, 'admin', 'management', 'sdvsd', 'pending', '2025-05-20 02:20:44', '2025-05-20 02:20:44', NULL),
(45, 59, 25, 2.00, 'admin', 'management', 'sdsd', 'pending', '2025-05-20 02:20:44', '2025-05-20 02:20:44', NULL),
(46, 60, 14, 2.00, 'admin', 'management', 'erdfv', 'pending', '2025-05-20 02:21:36', '2025-05-20 02:21:36', NULL),
(47, 59, 15, 1.00, 'admin', 'management', 'dfvdf', 'pending', '2025-05-20 02:21:36', '2025-05-20 02:21:36', NULL),
(48, 60, 14, 2.00, 'admin', 'management', 'erdfv', 'approved', '2025-05-20 02:23:57', '2025-05-20 04:21:09', NULL),
(49, 59, 15, 1.00, 'admin', 'management', 'dfvdf', 'pending', '2025-05-20 02:23:57', '2025-05-20 02:23:57', NULL),
(50, 59, 15, 0.00, 'admin', 'management', 'test IT 22', 'delivered', '2025-05-20 02:24:28', '2025-05-20 04:34:39', NULL),
(57, 77, 21, 1.00, 'admin', 'management', '4sdvsd', 'approved', '2025-05-20 02:43:32', '2025-05-20 03:21:57', NULL),
(72, 63, 31, 1.00, 'admin', 'management', 'ever', 'approved', '2025-05-20 02:58:11', '2025-05-20 03:05:31', NULL),
(73, 86, 39, 0.10, 'admin', 'management', 'final test mat req', 'approved', '2025-05-20 06:41:39', '2025-05-20 09:27:52', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `material_usages`
--

CREATE TABLE `material_usages` (
  `id` bigint UNSIGNED NOT NULL,
  `inventory_id` bigint UNSIGNED NOT NULL,
  `project_id` bigint UNSIGNED NOT NULL,
  `used_quantity` decimal(12,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `material_usages`
--

INSERT INTO `material_usages` (`id`, `inventory_id`, `project_id`, `used_quantity`, `created_at`, `updated_at`, `deleted_at`) VALUES
(14, 59, 16, 0.00, '2025-05-19 08:07:18', '2025-05-19 08:29:36', NULL),
(15, 72, 26, 10.00, '2025-05-19 08:30:28', '2025-05-19 08:30:59', NULL),
(16, 64, 20, 23.00, '2025-05-19 08:34:02', '2025-05-20 09:49:24', NULL),
(17, 69, 25, 1.00, '2025-05-20 01:34:37', '2025-05-20 01:34:37', NULL),
(18, 59, 15, 6.50, '2025-05-20 04:32:34', '2025-05-20 04:34:39', NULL),
(19, 86, 39, 6.90, '2025-05-20 06:42:21', '2025-05-20 09:27:52', NULL);

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
(20, '2025_05_08_023823_add_currency_id_to_inventories_table', 12),
(21, '2025_05_09_042038_create_units_table', 13),
(22, '2025_05_14_063224_create_goods_out_table', 14),
(23, '2025_05_15_060717_add_department_to_users_table', 15),
(24, '2025_05_15_070514_create_goods_in_table', 16),
(25, '2025_05_15_085008_add_inventory_and_project_to_goods_in_table', 17),
(26, '2025_05_15_085058_create_material_usages_table', 18),
(27, '2025_05_16_040419_alter_goods_in_goods_out_id_nullable', 19),
(28, '2025_05_16_114925_create_categories_table', 20),
(29, '2025_05_16_115000_add_category_id_to_inventories_table', 20),
(30, '2025_05_19_081152_add_remark_to_goods_in_and_goods_out_tables', 21),
(31, '2025_05_19_091706_add_soft_deletes_to_all_tables', 22);

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
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `projects`
--

INSERT INTO `projects` (`id`, `name`, `qty`, `img`, `deadline`, `department`, `created_at`, `updated_at`, `deleted_at`) VALUES
(14, 'Costume 1', 2, 'projects/zvqZPckVQpBA9twdjkDNl1YFj8V653lvmULjEXCP.png', '2025-05-31', 'costume', '2025-05-19 07:35:12', '2025-05-19 07:35:12', NULL),
(15, 'Costume 2', 5, 'projects/nco7izn5X5Jl26ldDwilHYVNmsyUhuRgQ1FzrFYl.png', '2025-06-07', 'costume', '2025-05-19 07:35:35', '2025-05-19 07:35:35', NULL),
(16, 'Costume 3', 3, 'projects/xyFSpqbI8iO76qg3BSDv0Bz76IIpnRiLyB9J9nnY.png', '2025-06-04', 'costume', '2025-05-19 07:36:07', '2025-05-19 07:36:07', NULL),
(17, 'Mascot 1', 1, 'projects/WwxLYysaGwcbB5vWnrmz35GdAy4nlY1aDlxCOkS2.png', '2025-06-27', 'mascot', '2025-05-19 07:36:29', '2025-05-19 07:36:29', NULL),
(18, 'Mascot 2', 1, 'projects/iC6qedTR8cRVPXPlYAOubatgBstx8gXuTTeIc2eo.png', '2025-07-05', 'mascot', '2025-05-19 07:36:41', '2025-05-19 07:38:18', NULL),
(19, 'Project 1', 1, 'projects/1wA9TSzcWaZzQc8ErdysqOJ5QxMo4zvPt8EcNHkY.png', '2025-07-25', 'mascot&costume', '2025-05-19 07:37:03', '2025-05-19 07:37:03', NULL),
(20, 'Project 2', 1, 'projects/0sT6jN7Jn7379mQVftEC7HK1d1B1eMFeLWuGHCXt.png', '2025-05-23', 'mascot&costume', '2025-05-19 07:37:22', '2025-05-19 07:37:22', NULL),
(21, 'Test', 5, 'projects/MzmRuUIHFFQVV1HRgM77EbYwWK3BCftJ7HMdoRm1.png', '2025-05-24', 'mascot', '2025-05-19 07:45:58', '2025-05-19 07:45:58', NULL),
(22, 'Test 2', 5, 'projects/Ncxocae1mkW0refit8058OhkpKuurqmndq7Xd2Za.png', '2025-05-24', 'mascot&costume', '2025-05-19 07:46:18', '2025-05-19 07:46:18', NULL),
(23, 'Test 3', 2, 'projects/iJdBsy9olbY2oyMq4lJnZskulz3RrdnM725oWiDY.png', '2025-05-24', 'mascot', '2025-05-19 07:46:43', '2025-05-19 07:46:43', NULL),
(24, 'Project 3', 2, 'projects/ejZvq2QTLy8z5bM6y7UEUeAIPMTWeEuUNshlhA63.jpg', '2025-05-23', 'costume', '2025-05-19 07:47:19', '2025-05-19 07:47:19', NULL),
(25, 'Bobo', 1, NULL, NULL, 'costume', '2025-05-19 07:50:24', '2025-05-19 07:50:24', NULL),
(26, 'Qucking', 12, 'projects/h2aE72Y5oPCkEnDCqAqhiMOriqWt0Ulh6uyXhO2X.png', '2025-05-31', 'costume', '2025-05-19 07:51:55', '2025-05-19 07:58:17', NULL),
(27, 'Mascot 7', 1, NULL, NULL, 'mascot', '2025-05-19 08:02:52', '2025-05-19 08:02:52', NULL),
(30, 'Project 90', 1, NULL, NULL, 'costume', '2025-05-19 08:48:39', '2025-05-19 08:48:39', NULL),
(31, 'test99', 9, NULL, NULL, 'costume', '2025-05-19 09:07:05', '2025-05-19 09:07:05', NULL),
(39, 'final test pro', 2, 'projects/XQjjQvOXyF87BpwPi7CO0qwTaYcLb7NlwieKYIny.png', '2025-06-13', 'mascot&costume', '2025-05-20 06:41:02', '2025-05-20 06:41:02', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `units`
--

CREATE TABLE `units` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `units`
--

INSERT INTO `units` (`id`, `name`, `created_at`, `updated_at`) VALUES
(13, 'cm', '2025-05-19 07:28:13', '2025-05-19 07:28:13'),
(14, 'mm', '2025-05-19 07:28:58', '2025-05-19 07:28:58'),
(15, 'pcs', '2025-05-19 07:30:00', '2025-05-19 07:30:00'),
(16, 'pack', '2025-05-19 07:31:00', '2025-05-19 07:31:00'),
(17, 'm', '2025-05-19 07:31:35', '2025-05-19 07:31:35'),
(18, 'kg', '2025-05-19 07:32:30', '2025-05-19 07:32:30'),
(19, 'set', '2025-05-19 07:33:55', '2025-05-19 07:33:55'),
(20, 'roll', '2025-05-19 07:45:26', '2025-05-19 07:45:26'),
(21, 'dm', '2025-05-19 07:49:49', '2025-05-19 07:49:49'),
(30, 'final test unit', '2025-05-20 06:37:41', '2025-05-20 06:37:41');

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
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `remember_token`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'admin', '$2y$10$d92eVtlqhO1zea7Ofb/jKuR6s.LKe7GYqVQkAMTEDzm.sk34iBC5C', 'super_admin', 'uDM6jrLeKQSK9iyyDhWJk3zaG0upKKmkaTShHXeHGDnr2Tjg8jSvxecDY1fR', '2025-05-05 23:37:44', '2025-05-06 20:24:34', NULL),
(2, 'mascot', '$2y$10$agrAQMX7Zu08uOYtHcQQYeWTO1kge9xJWT.mIFMBX/XhItIyToAIy', 'admin_mascot', NULL, '2025-05-06 00:07:01', '2025-05-06 00:07:01', NULL),
(3, 'costume', '$2y$10$Q2V3kGCkazSCAtjXQ3No4./RayNoZAMEVfNdmFr//vXHBqP87xrs6', 'admin_costume', NULL, '2025-05-06 00:07:27', '2025-05-06 00:07:27', NULL),
(4, 'store', '$2y$10$IbH1.hfTsurL05wNwT6VTepZnmQn1cyxoa.UwIjIN739.0hV11h.a', 'admin_logistic', NULL, '2025-05-06 00:07:43', '2025-05-06 00:07:43', NULL),
(5, 'finance', '$2y$10$LijMxv8aRqT7/e0sX5me/.b/BTvBoKmpZdPEfw/OmGzQfavpUxYxu', 'admin_finance', NULL, '2025-05-06 00:08:01', '2025-05-06 00:08:01', NULL);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_name_unique` (`name`);

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
-- Indeks untuk tabel `goods_in`
--
ALTER TABLE `goods_in`
  ADD PRIMARY KEY (`id`),
  ADD KEY `goods_in_goods_out_id_foreign` (`goods_out_id`),
  ADD KEY `goods_in_inventory_id_foreign` (`inventory_id`),
  ADD KEY `goods_in_project_id_foreign` (`project_id`);

--
-- Indeks untuk tabel `goods_out`
--
ALTER TABLE `goods_out`
  ADD PRIMARY KEY (`id`),
  ADD KEY `goods_out_material_request_id_foreign` (`material_request_id`),
  ADD KEY `goods_out_inventory_id_foreign` (`inventory_id`),
  ADD KEY `goods_out_project_id_foreign` (`project_id`);

--
-- Indeks untuk tabel `inventories`
--
ALTER TABLE `inventories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inventories_currency_id_foreign` (`currency_id`),
  ADD KEY `inventories_category_id_foreign` (`category_id`);

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
-- Indeks untuk tabel `material_usages`
--
ALTER TABLE `material_usages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `material_usages_inventory_id_foreign` (`inventory_id`),
  ADD KEY `material_usages_project_id_foreign` (`project_id`);

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
-- Indeks untuk tabel `units`
--
ALTER TABLE `units`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `units_name_unique` (`name`);

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
-- AUTO_INCREMENT untuk tabel `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT untuk tabel `currencies`
--
ALTER TABLE `currencies`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `goods_in`
--
ALTER TABLE `goods_in`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT untuk tabel `goods_out`
--
ALTER TABLE `goods_out`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT untuk tabel `inventories`
--
ALTER TABLE `inventories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;

--
-- AUTO_INCREMENT untuk tabel `inventory_transactions`
--
ALTER TABLE `inventory_transactions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `material_requests`
--
ALTER TABLE `material_requests`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT untuk tabel `material_usages`
--
ALTER TABLE `material_usages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT untuk tabel `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `projects`
--
ALTER TABLE `projects`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT untuk tabel `units`
--
ALTER TABLE `units`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8000000;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `goods_in`
--
ALTER TABLE `goods_in`
  ADD CONSTRAINT `goods_in_goods_out_id_foreign` FOREIGN KEY (`goods_out_id`) REFERENCES `goods_out` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `goods_in_inventory_id_foreign` FOREIGN KEY (`inventory_id`) REFERENCES `inventories` (`id`),
  ADD CONSTRAINT `goods_in_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`);

--
-- Ketidakleluasaan untuk tabel `goods_out`
--
ALTER TABLE `goods_out`
  ADD CONSTRAINT `goods_out_inventory_id_foreign` FOREIGN KEY (`inventory_id`) REFERENCES `inventories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `goods_out_material_request_id_foreign` FOREIGN KEY (`material_request_id`) REFERENCES `material_requests` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `goods_out_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `inventories`
--
ALTER TABLE `inventories`
  ADD CONSTRAINT `inventories_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `inventories_currency_id_foreign` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `material_requests`
--
ALTER TABLE `material_requests`
  ADD CONSTRAINT `material_requests_inventory_id_foreign` FOREIGN KEY (`inventory_id`) REFERENCES `inventories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `material_requests_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `material_usages`
--
ALTER TABLE `material_usages`
  ADD CONSTRAINT `material_usages_inventory_id_foreign` FOREIGN KEY (`inventory_id`) REFERENCES `inventories` (`id`),
  ADD CONSTRAINT `material_usages_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
