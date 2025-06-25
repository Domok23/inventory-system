-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 23, 2025 at 01:22 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.16

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
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
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
(15, 'final test cat', '2025-05-20 06:36:05', '2025-05-20 06:36:05', NULL),
(16, 'test3', '2025-06-04 06:32:31', '2025-06-04 06:32:31', NULL),
(17, 'Kabel', '2025-06-09 04:01:00', '2025-06-09 04:01:00', NULL),
(18, 'llkk', '2025-06-09 07:38:46', '2025-06-09 07:38:46', NULL),
(19, 'Benang', '2025-06-09 07:58:45', '2025-06-09 07:58:45', NULL),
(20, 'Benang2', '2025-06-09 07:58:45', '2025-06-09 07:58:45', NULL),
(21, 'Jarum', '2025-06-10 03:30:14', '2025-06-10 03:30:14', NULL),
(22, 'Kertas', '2025-06-17 04:55:05', '2025-06-17 04:55:05', NULL),
(23, 'Electronic', '2025-06-18 02:35:40', '2025-06-18 02:35:40', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `currencies`
--

CREATE TABLE `currencies` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exchange_rate` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `currencies`
--

INSERT INTO `currencies` (`id`, `name`, `exchange_rate`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'USD', '16286.50', '2025-05-07 20:10:36', '2025-06-11 06:42:27', NULL),
(2, 'IDR', '1', '2025-05-07 20:10:53', '2025-05-07 23:21:32', NULL),
(5, 'SGD', '12671.96', '2025-05-07 23:21:45', '2025-06-09 02:28:10', NULL),
(6, 'RMB', '2265.72', '2025-05-07 23:21:54', '2025-06-09 02:28:43', NULL),
(15, 'TBH', '498.472', '2025-05-19 07:59:18', '2025-06-09 02:29:49', NULL),
(16, 'Rial', '10000', '2025-05-19 08:50:07', '2025-06-09 02:30:45', '2025-06-09 02:30:45'),
(17, 'SAU', '12000', '2025-05-19 08:50:49', '2025-06-09 02:30:52', '2025-06-09 02:30:52'),
(18, 'test', '1233', '2025-05-19 09:01:00', '2025-06-09 02:30:49', '2025-06-09 02:30:49'),
(19, 'test222', '2222', '2025-05-19 09:02:01', '2025-05-24 03:14:56', '2025-05-24 03:14:56'),
(20, 'fft', '23', '2025-05-19 09:13:07', '2025-05-23 02:05:53', '2025-05-23 02:05:53'),
(21, 'tesyyyy', '334', '2025-05-19 09:19:44', '2025-06-09 02:30:55', '2025-06-09 02:30:55'),
(22, 'testsssttt', '455', '2025-05-19 09:20:02', '2025-06-09 02:30:58', '2025-06-09 02:30:58'),
(23, 'sdcvsd', '4545', '2025-05-19 09:21:16', '2025-06-09 02:31:01', '2025-06-09 02:31:01'),
(24, 'asxcas', '23', '2025-05-20 03:52:09', '2025-05-24 02:25:40', '2025-05-24 02:25:40'),
(25, 'final test cury', '20000', '2025-05-20 06:36:40', '2025-06-09 02:31:04', '2025-06-09 02:31:04'),
(26, 'bhn', '56660', '2025-06-09 02:21:56', '2025-06-09 02:31:08', '2025-06-09 02:31:08'),
(27, 'MYR', '3848.02', '2025-06-09 02:31:22', '2025-06-09 02:31:22', NULL),
(28, 'JPY', '112.92', '2025-06-09 02:32:12', '2025-06-09 02:32:12', NULL),
(29, 'SAR', '4339.13', '2025-06-09 02:33:35', '2025-06-09 02:33:35', NULL),
(30, 'PHP', '291.99', '2025-06-09 02:34:33', '2025-06-09 02:34:33', NULL),
(31, 'Test556609', '23234', '2025-06-10 09:44:07', '2025-06-10 09:45:21', '2025-06-10 09:45:21'),
(32, 'cvgghy', '12', '2025-06-10 09:45:46', '2025-06-10 09:45:56', '2025-06-10 09:45:56'),
(33, 'testsss556ss', '12', '2025-06-10 09:47:49', '2025-06-10 09:48:06', '2025-06-10 09:48:06'),
(34, 'fgggdz', '34', '2025-06-10 09:50:59', '2025-06-11 04:46:11', '2025-06-11 04:46:11');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `goods_in`
--

CREATE TABLE `goods_in` (
  `id` bigint UNSIGNED NOT NULL,
  `goods_out_id` bigint UNSIGNED DEFAULT NULL,
  `inventory_id` bigint UNSIGNED DEFAULT NULL,
  `project_id` bigint UNSIGNED DEFAULT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `returned_by` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `returned_at` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `remark` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `goods_in`
--

INSERT INTO `goods_in` (`id`, `goods_out_id`, `inventory_id`, `project_id`, `quantity`, `returned_by`, `returned_at`, `created_at`, `updated_at`, `remark`, `deleted_at`) VALUES
(27, 23, 64, 20, 10.00, 'admin', '2025-05-21 04:01:00', '2025-05-21 04:01:32', '2025-05-21 04:01:32', NULL, NULL),
(28, 27, 84, 25, 1.30, 'admin', '2025-05-22 03:38:00', '2025-05-22 03:39:07', '2025-05-22 03:39:07', NULL, NULL),
(29, 29, 85, 25, 4.00, 'admin', '2025-05-22 04:03:00', '2025-05-22 04:03:58', '2025-05-22 04:03:58', NULL, NULL),
(30, 31, 85, 25, 0.30, 'admin', '2025-05-22 04:08:00', '2025-05-22 04:08:52', '2025-05-22 04:08:52', NULL, NULL),
(31, 32, 85, 25, 0.70, 'admin', '2025-05-22 04:10:00', '2025-05-22 04:10:24', '2025-05-22 04:10:24', NULL, NULL),
(32, 33, 75, 14, 2.50, 'admin', '2025-05-22 04:13:00', '2025-05-22 04:13:16', '2025-05-22 04:13:16', NULL, NULL),
(33, NULL, 84, 40, 2.00, 'admin', '2025-05-21 17:00:00', '2025-05-22 08:54:05', '2025-06-09 06:07:46', 'hadehh', '2025-06-09 06:07:46'),
(34, NULL, 84, 14, 2.00, 'admin', '2025-05-22 17:00:00', '2025-05-22 09:09:45', '2025-05-22 09:09:45', 'sdcvsd', NULL),
(35, NULL, 72, 16, 3.00, 'admin', '2025-05-21 17:00:00', '2025-05-22 09:11:13', '2025-05-22 09:11:13', 'fdvbdf', NULL),
(36, 41, 59, 14, 0.60, 'admin', '2025-05-23 03:03:00', '2025-05-23 03:03:53', '2025-05-23 03:03:53', NULL, NULL),
(37, 40, 59, 14, 0.40, 'admin', '2025-05-23 03:04:00', '2025-05-23 03:04:24', '2025-05-23 03:04:24', NULL, NULL),
(38, 41, 59, 14, 2.40, 'admin', '2025-05-23 03:05:00', '2025-05-23 03:05:37', '2025-05-23 03:05:37', NULL, NULL),
(40, 42, 61, 17, 1.50, 'admin', '2025-05-23 08:04:00', '2025-05-23 08:04:32', '2025-05-23 08:04:32', NULL, NULL),
(41, 43, 61, 17, 1.00, 'admin', '2025-05-23 08:24:00', '2025-05-23 08:24:31', '2025-05-23 08:24:31', NULL, NULL),
(42, 42, 61, 17, 4.00, 'admin', '2025-05-27 08:34:47', '2025-05-27 08:34:47', '2025-05-27 08:34:47', 'Bulk Goods In', NULL),
(43, 43, 61, 17, 3.00, 'admin', '2025-05-27 08:34:47', '2025-05-27 08:34:47', '2025-05-27 08:34:47', 'Bulk Goods In', NULL),
(44, 44, 63, 31, 1.00, 'admin', '2025-05-27 08:34:47', '2025-05-27 08:34:47', '2025-05-27 08:34:47', 'Bulk Goods In', NULL),
(45, 45, 60, 23, 1.00, 'tari', '2025-06-03 01:56:33', '2025-06-03 01:56:33', '2025-06-03 01:56:33', 'Bulk Goods In', NULL),
(46, 46, 62, NULL, 0.50, 'tari', '2025-06-03 01:57:00', '2025-06-03 01:58:06', '2025-06-03 08:20:52', NULL, NULL),
(47, 48, 69, 24, 1.00, 'logitech', '2025-06-03 07:32:23', '2025-06-03 07:32:23', '2025-06-03 07:32:23', 'Bulk Goods In', NULL),
(48, NULL, 84, NULL, 3.00, 'tari', '2025-06-03 08:11:00', '2025-06-03 08:11:56', '2025-06-03 08:21:03', NULL, NULL),
(49, NULL, 88, NULL, 1.00, 'tari', '2025-06-04 17:00:00', '2025-06-03 08:43:51', '2025-06-03 08:43:51', '2', NULL),
(50, NULL, 89, NULL, 3.00, 'dyla', '2025-06-04 17:00:00', '2025-06-04 06:38:41', '2025-06-04 06:38:41', 'masuk baru', NULL),
(51, 61, 69, 19, 0.50, 'dyla', '2025-06-04 06:59:00', '2025-06-04 06:59:06', '2025-06-04 06:59:06', NULL, NULL),
(52, 62, 58, 18, 0.40, 'laura', '2025-06-04 09:16:00', '2025-06-04 09:16:11', '2025-06-04 09:16:11', NULL, NULL),
(53, 64, 119, 46, 1.00, 'logitech', '2025-06-09 04:05:00', '2025-06-09 04:05:22', '2025-06-09 04:05:22', NULL, NULL),
(54, NULL, 84, 25, 2.00, 'logitech', '2025-06-10 09:57:00', '2025-06-10 09:57:27', '2025-06-10 10:01:18', NULL, '2025-06-10 10:01:18'),
(55, 68, 153, 53, 1.00, 'logitech', '2025-06-17 06:13:00', '2025-06-17 06:13:30', '2025-06-17 06:13:30', NULL, NULL),
(56, 67, 60, 14, 1.00, 'logitech', '2025-06-17 06:49:21', '2025-06-17 06:49:21', '2025-06-17 06:49:21', 'Bulk Goods In', NULL),
(57, 67, 60, 14, 0.00, 'logitech', '2025-06-17 06:50:15', '2025-06-17 06:50:15', '2025-06-17 06:50:15', 'Bulk Goods In', NULL),
(58, 68, 153, 53, 2.00, 'logitech', '2025-06-17 06:50:00', '2025-06-17 06:50:48', '2025-06-17 06:50:48', NULL, NULL),
(59, 66, 69, 43, 1.00, 'logitech', '2025-06-17 07:01:25', '2025-06-17 07:01:25', '2025-06-17 07:01:25', 'Bulk Goods In', NULL),
(60, 67, 60, 14, 1.00, 'logitech', '2025-06-17 07:01:25', '2025-06-17 07:01:25', '2025-06-17 07:01:25', 'Bulk Goods In', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `goods_out`
--

CREATE TABLE `goods_out` (
  `id` bigint UNSIGNED NOT NULL,
  `material_request_id` bigint UNSIGNED DEFAULT NULL,
  `inventory_id` bigint UNSIGNED NOT NULL,
  `project_id` bigint UNSIGNED DEFAULT NULL,
  `requested_by` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `department` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `remark` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `goods_out`
--

INSERT INTO `goods_out` (`id`, `material_request_id`, `inventory_id`, `project_id`, `requested_by`, `department`, `quantity`, `created_at`, `updated_at`, `remark`, `deleted_at`) VALUES
(15, 37, 72, 26, 'admin', 'management', 5.00, '2025-05-19 08:30:59', '2025-05-19 08:30:59', 'dah 100%', NULL),
(16, 39, 64, 20, 'admin', 'management', 3.00, '2025-05-19 08:34:02', '2025-05-19 08:34:02', NULL, NULL),
(17, NULL, 69, 25, 'admin', 'management', 1.00, '2025-05-20 01:34:37', '2025-05-20 01:34:37', NULL, NULL),
(20, 73, 86, 39, 'admin', 'management', 4.60, '2025-05-20 06:42:21', '2025-05-20 06:42:21', NULL, NULL),
(21, 73, 86, 39, 'admin', 'management', 2.20, '2025-05-20 06:43:24', '2025-05-20 06:43:24', NULL, NULL),
(22, 73, 86, 39, 'admin', 'management', 0.10, '2025-05-20 09:08:53', '2025-05-20 09:27:52', NULL, NULL),
(23, 39, 64, 20, 'admin', 'management', 15.00, '2025-05-20 09:46:10', '2025-05-20 09:46:10', NULL, NULL),
(24, 39, 64, 20, 'admin', 'management', 2.00, '2025-05-20 09:48:45', '2025-05-22 03:38:03', 'ascasw', NULL),
(25, 39, 64, 20, 'admin', 'management', 0.00, '2025-05-20 09:49:24', '2025-05-21 03:04:23', NULL, NULL),
(27, 74, 84, 25, 'admin', 'management', 2.00, '2025-05-22 03:37:45', '2025-05-22 03:37:45', 'testing B', NULL),
(29, 75, 85, 25, 'admin', 'management', 6.00, '2025-05-22 04:02:39', '2025-05-22 04:02:39', NULL, NULL),
(30, 75, 85, 25, 'admin', 'management', 3.00, '2025-05-22 04:03:22', '2025-05-22 04:03:22', NULL, NULL),
(31, 75, 85, 25, 'admin', 'management', 1.30, '2025-05-22 04:08:03', '2025-05-22 04:08:03', NULL, NULL),
(32, 75, 85, 25, 'admin', 'management', 0.70, '2025-05-22 04:09:52', '2025-05-22 04:09:52', NULL, NULL),
(33, 76, 75, 14, 'admin', 'management', 3.00, '2025-05-22 04:12:23', '2025-05-22 04:12:23', 'ascas', NULL),
(34, 76, 75, 14, 'admin', 'management', 2.00, '2025-05-22 04:14:04', '2025-06-09 06:07:39', NULL, '2025-06-09 06:07:39'),
(35, 74, 84, 25, 'admin', 'management', 0.50, '2025-05-22 04:37:31', '2025-05-22 04:37:31', NULL, NULL),
(36, NULL, 84, 40, 'lesta', 'finance', 2.00, '2025-05-22 08:55:01', '2025-06-02 04:36:28', 'test again', NULL),
(37, NULL, 72, 15, 'admin', 'management', 4.00, '2025-05-22 09:12:23', '2025-05-30 04:09:42', 'test99', NULL),
(40, 77, 59, 14, 'admin', 'management', 1.50, '2025-05-23 03:03:03', '2025-05-23 03:03:03', 'test 1', NULL),
(41, 77, 59, 14, 'admin', 'management', 3.00, '2025-05-23 03:03:22', '2025-05-23 03:07:50', NULL, NULL),
(42, 78, 61, 17, 'admin', 'management', 4.00, '2025-05-23 08:02:39', '2025-05-23 08:02:39', NULL, NULL),
(43, 78, 61, 17, 'admin', 'management', 3.00, '2025-05-23 08:03:54', '2025-05-23 08:49:57', NULL, NULL),
(44, 72, 63, 31, 'logitech', 'management', 1.00, '2025-05-27 08:34:07', '2025-06-02 04:33:15', 'ever', NULL),
(45, 250, 60, 23, 'admin', 'management', 1.00, '2025-06-03 01:40:38', '2025-06-03 01:40:38', '1', NULL),
(46, 251, 62, 31, 'admin', 'management', 1.00, '2025-06-03 01:40:38', '2025-06-03 01:40:38', '1', NULL),
(47, 248, 72, 16, 'admin', 'management', 1.00, '2025-06-03 02:10:48', '2025-06-03 02:10:48', '1', NULL),
(48, 245, 69, 24, 'admin', 'management', 1.00, '2025-06-03 02:11:35', '2025-06-03 02:11:35', '2', NULL),
(49, 161, 69, 15, 'admin', 'management', 1.00, '2025-06-03 06:07:51', '2025-06-03 06:07:51', 'vvvvddd', NULL),
(50, 151, 60, 15, 'admin', 'management', 1.00, '2025-06-03 07:58:01', '2025-06-03 07:58:01', 'dddrr', NULL),
(51, 161, 69, 15, 'admin', 'management', 1.00, '2025-06-03 07:58:21', '2025-06-03 07:58:21', NULL, NULL),
(52, NULL, 89, 25, 'dyla', 'logistic', 1.00, '2025-06-04 06:38:05', '2025-06-04 06:38:05', 'buang', NULL),
(53, 298, 84, 14, 'tari', 'costume', 1.00, '2025-06-04 06:49:12', '2025-06-04 06:49:12', 'delivered by apri', NULL),
(54, 297, 69, 25, 'tari', 'costume', 1.00, '2025-06-04 06:52:42', '2025-06-04 06:52:42', 'kurang 1 kg lagi', NULL),
(55, 297, 69, 25, 'tari', 'costume', 1.00, '2025-06-04 06:54:10', '2025-06-04 06:54:10', NULL, NULL),
(56, 256, 62, 26, 'tari', 'costume', 1.50, '2025-06-04 06:54:51', '2025-06-04 06:54:51', 'kurang 1/2', NULL),
(57, 256, 62, 26, 'tari', 'costume', 0.50, '2025-06-04 06:56:21', '2025-06-04 06:56:21', NULL, NULL),
(58, 295, 84, 25, 'tari', 'costume', 1.00, '2025-06-04 06:57:55', '2025-06-04 06:57:55', 'ffv', NULL),
(59, 299, 69, 16, 'tari', 'costume', 1.00, '2025-06-04 06:57:55', '2025-06-04 06:57:55', '1', NULL),
(60, 300, 60, 39, 'tari', 'costume', 1.00, '2025-06-04 06:57:55', '2025-06-04 06:57:55', '1', NULL),
(61, 301, 69, 19, 'tari', 'costume', 1.00, '2025-06-04 06:57:55', '2025-06-04 06:57:55', '1', NULL),
(62, 305, 58, 18, 'laura', 'mascot', 1.00, '2025-06-04 09:15:14', '2025-06-04 09:15:14', NULL, NULL),
(63, 331, 119, 46, 'logitech', 'management', 2.00, '2025-06-09 04:05:01', '2025-06-09 04:05:01', NULL, NULL),
(64, 331, 119, 46, 'logitech', 'management', 3.00, '2025-06-09 04:05:09', '2025-06-09 04:05:09', NULL, NULL),
(65, 335, 146, 25, 'logitech', 'management', 4.00, '2025-06-17 04:50:28', '2025-06-17 04:50:28', 'Bulk Goods Out', NULL),
(66, 336, 69, 43, 'logitech', 'management', 3.00, '2025-06-17 04:50:28', '2025-06-17 04:50:28', 'Bulk Goods Out', NULL),
(67, 330, 60, 14, 'logitech', 'management', 3.00, '2025-06-17 04:52:50', '2025-06-17 04:52:50', NULL, NULL),
(68, 337, 153, 53, 'logitech', 'management', 3.00, '2025-06-17 06:11:26', '2025-06-17 06:11:26', NULL, NULL),
(69, 337, 153, 53, 'logitech', 'management', 1.00, '2025-06-17 06:52:01', '2025-06-17 06:52:01', NULL, NULL),
(70, 338, 90, 53, 'logitech', 'management', 3.00, '2025-06-17 07:45:40', '2025-06-17 07:45:40', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `inventories`
--

CREATE TABLE `inventories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` decimal(8,2) NOT NULL,
  `unit` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `supplier` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency_id` bigint UNSIGNED DEFAULT NULL,
  `location` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remark` text COLLATE utf8mb4_unicode_ci,
  `img` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `qrcode_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `qrcode` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `category_id` bigint UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inventories`
--

INSERT INTO `inventories` (`id`, `name`, `quantity`, `unit`, `price`, `supplier`, `currency_id`, `location`, `remark`, `img`, `qrcode_path`, `qrcode`, `created_at`, `updated_at`, `category_id`, `deleted_at`) VALUES
(58, 'Fabric 223', 339.40, 'mm', 2500.00, NULL, 6, 'Rack 3 Batam', NULL, 'images/CFqzHbQLMAUkIOsjx7iE7ag6XwtBl8oX7KmIxbFc.jpg', 'qrcodes/qr_683a8901cfbc6.svg', NULL, '2025-05-19 07:28:58', '2025-06-04 09:16:11', 1, NULL),
(59, 'Buttons 1', 317.40, 'pcs', 0.50, NULL, 5, 'Singapore', NULL, 'images/aYwSO3uyO1eF3eKfQvSqZ9fafM6vZXstGMGRDcgM.jpg', 'qrcodes/qr_682fdd1629683.svg', NULL, '2025-05-19 07:30:00', '2025-05-31 04:38:22', 9, '2025-05-31 04:38:22'),
(60, 'Buttons 2', 497.00, 'pack', 0.25, NULL, 1, 'Batam', NULL, 'images/kuBk9OHnTPF6YXQoUGsHTdIBUVityxZkTHdQX8ZZ.jpg', 'qrcodes/qr_682ae0a61b8fb.svg', NULL, '2025-05-19 07:31:00', '2025-06-17 07:01:25', 9, NULL),
(61, 'Foam 1', 50.00, 'm', 12000.00, NULL, 2, 'Rack 5', NULL, 'images/MyI4zCLVzse1nuHh2WBTaHtNmxmudSuO6DjrRsoj.jpg', 'qrcodes/qr_682ade57c7118.svg', NULL, '2025-05-19 07:31:35', '2025-05-27 08:34:47', 8, NULL),
(62, 'Foam 2', 20.50, 'kg', 1300.00, NULL, 6, 'Rack 5', NULL, 'images/TadRnNm3YJxNLKVTOC9YLpaosnELQRykx8Cum2fz.jpg', 'qrcodes/qr_682ade8e3152a.svg', NULL, '2025-05-19 07:32:30', '2025-06-04 06:56:21', 8, NULL),
(63, 'Zipper 1', 130.00, 'set', 2.00, NULL, 5, 'Batam 2', NULL, 'images/zG9Ng3mWP1sAMetg6Osmwix6SJREh8a7MGyCJniV.jpg', 'qrcodes/qr_682adee3d4810.svg', NULL, '2025-05-19 07:33:55', '2025-06-02 04:33:15', 10, NULL),
(64, 'Zipper 2', 490.00, 'pcs', 1.40, NULL, 1, 'Rack 4', NULL, 'images/LIL8BXOR6SR4TxGCG5rhllwaHdNukXziNsgA9x6Y.jpg', 'qrcodes/qr_682adf0ecccc5.svg', NULL, '2025-05-19 07:34:38', '2025-05-22 03:38:03', 10, NULL),
(65, 'Foam 3', 105.00, 'pcs', 20000.00, NULL, 2, 'rack 4', NULL, 'images/ltLYZMLCILT2PW0vwpWG1WK9qstwTsRCaGsfHXwS.jpg', 'qrcodes/qr_682ae01889494.svg', NULL, '2025-05-19 07:39:04', '2025-05-19 07:39:04', 8, NULL),
(66, 'Foam 4', 14.00, 'pack', 2.00, NULL, 5, 'rack 3', NULL, 'images/C46JybX6qaYsRr28kZrq9hfaK5oZcgTfYK2Y9D0Q.jpg', 'qrcodes/qr_682ae054366fe.svg', NULL, '2025-05-19 07:39:51', '2025-05-19 07:40:04', 8, NULL),
(67, 'Fabric 4', 234.00, 'cm', 3400.00, NULL, 6, 'rack 4', NULL, 'images/5biAk4B9XCpZzfyVRdht1uvxyrkJ4Qhli5fu7aya.jpg', 'qrcodes/qr_682ae07faf63e.svg', NULL, '2025-05-19 07:40:47', '2025-05-19 07:40:47', 1, NULL),
(68, 'Fabric 5', 378.00, 'kg', 4000.00, NULL, 6, 'rack 1', NULL, 'images/UGrKEVmZQdCRaxzaqOxb6vnxvtiQiD5XXvq2nzxt.jpg', 'qrcodes/qr_682ae0f4b4cbb.svg', NULL, '2025-05-19 07:42:44', '2025-05-19 07:42:44', 1, NULL),
(69, 'Button 4', 447.50, 'kg', 200.00, NULL, 2, 'rack 3', NULL, 'images/lqKjFQd3RO33i6QFaIbZdQOMsq89aG5btkL3t6AZ.jpg', 'qrcodes/qr_682ae1311f7f5.svg', NULL, '2025-05-19 07:43:45', '2025-06-17 07:01:25', 9, NULL),
(70, 'Fabric 7', 5.00, 'roll', 2700.00, NULL, 6, 'rack 1', NULL, 'images/oWL0AjWu2KW0P6jyGEcK9aQFI3DZFe5G4kvqU9jg.jpg', 'qrcodes/qr_682ae196514d0.svg', NULL, '2025-05-19 07:45:26', '2025-05-19 07:45:26', 1, NULL),
(71, 'Quick Mat', 200.00, 'dm', 20.00, NULL, 5, 'rack 3', NULL, 'inventory_images/uulwQ8atWvEz0KObNt9F9oT42BJJB5JxDdY38zkC.jpg', 'qrcodes/qr_682ae472d411e.svg', NULL, '2025-05-19 07:49:49', '2025-05-19 07:57:38', 11, NULL),
(72, 'Buttons 8', 993.00, 'pcs', 2000.00, NULL, 2, 'Rack 6', NULL, 'inventory_images/G3WOO8vGnYN5jkkEFhGUHH9nbRgTdcY3RRwA87PT.jpg', 'qrcodes/qr_682ae4426fee9.svg', NULL, '2025-05-19 07:52:22', '2025-06-03 02:10:48', 9, NULL),
(73, 'Metal 1', 232.00, 'kg', 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-19 08:03:56', '2025-06-10 01:52:29', NULL, '2025-06-10 01:52:29'),
(74, 'Test Material 2', 200.00, 'cm', 0.00, NULL, 15, NULL, NULL, NULL, 'qrcodes/qr_682ae90b1a45a.svg', NULL, '2025-05-19 08:05:03', '2025-05-19 08:17:15', 1, NULL),
(75, 'test fabric', 10.50, 'dm', NULL, NULL, NULL, NULL, NULL, NULL, 'qrcodes/qr_682ea3f97fc77.svg', NULL, '2025-05-19 08:26:00', '2025-06-09 06:07:39', 1, NULL),
(76, 'test5464', 34.00, 'cm', 2.00, NULL, 15, 'rack 1', NULL, 'inventory_images/idcPPU5ycCONrc3Mu4UR8twxke4vqPBnmG7o2YOP.jpg', 'qrcodes/qr_683d62b02b63c.svg', NULL, '2025-05-19 09:08:14', '2025-06-02 08:37:04', 9, NULL),
(77, 'dfbdfb', 45.00, 'cm', 0.00, NULL, NULL, NULL, NULL, NULL, 'qrcodes/qr_682fdd25f04f1.svg', NULL, '2025-05-19 09:26:40', '2025-05-23 02:27:50', 9, NULL),
(83, 'sdcfswd', 23.00, 'cm', NULL, NULL, NULL, NULL, NULL, NULL, 'qrcodes/qr_682bfbd413f63.svg', NULL, '2025-05-20 03:49:40', '2025-05-27 07:09:46', 9, NULL),
(84, 'ascasc', 36.00, 'dm', 200000.00, NULL, 15, 'rack 3', NULL, NULL, 'qrcodes/qr_68303ebaab0ed.svg', NULL, '2025-05-20 04:00:16', '2025-06-10 09:57:27', 9, NULL),
(85, 'rgbrt', 415.70, 'cm', 40000.00, NULL, 15, 'rack 1', NULL, 'inventory_images/8fcTzosKxgqt1R1Sz896hrm1rRYUFYXf4EXK3TWt.jpg', 'qrcodes/qr_68303e77874f6.svg', NULL, '2025-05-20 04:04:32', '2025-05-23 09:23:04', 9, NULL),
(86, 'final test mat', 293.10, 'final test unit', 3.00, NULL, 25, 'final test loc', NULL, 'inventory_images/pVP0NMrrE6UBLQIuBVqLZ97ybvP5TFxeAy8LtuKK.jpg', 'qrcodes/qr_682c2355f036d.svg', NULL, '2025-05-20 06:37:41', '2025-05-22 04:48:20', 15, '2025-05-22 04:48:20'),
(87, 'wefw', 34.78, 'cm', 45.04, NULL, 15, '2', NULL, NULL, 'qrcodes/qr_68303f7381817.svg', NULL, '2025-05-23 09:26:45', '2025-05-23 09:27:15', 14, NULL),
(88, 'testrrr', 2.00, 'cm', 23.00, 'kunmu', NULL, 'rack 3', NULL, NULL, 'qrcodes/qr_684107b8625ec.svg', NULL, '2025-06-02 08:52:56', '2025-06-05 02:58:00', 9, NULL),
(89, 'Lem biru', 32.00, 'Kantong', 3000.00, NULL, 15, NULL, NULL, NULL, 'qrcodes/qr_683fe8e94131c.svg', NULL, '2025-06-04 06:34:17', '2025-06-04 06:38:41', 16, NULL),
(90, 'Foam Biru', 47.00, 'm', 200.00, 'chengdu', 15, 'rack 3', NULL, 'inventory_images/Hf4gF0hYycJNIe5WfJZ1uEEwo35u5OUtxoZM4WDu.jpg', 'qrcodes/qr_68410701eee7f.svg', NULL, '2025-06-04 09:13:36', '2025-06-17 07:45:40', 9, NULL),
(91, 'ssr', 29.00, 'joule', 2.00, 'Dunia Benang', 1, 'Rack1', NULL, NULL, NULL, NULL, '2025-06-07 04:43:37', '2025-06-07 04:48:17', NULL, '2025-06-07 04:48:17'),
(92, 'ssr4', 30.00, 'joule', 3.00, 'Dunia Benang', 1, 'Rack2', NULL, NULL, NULL, NULL, '2025-06-07 04:43:37', '2025-06-07 04:48:19', NULL, '2025-06-07 04:48:19'),
(93, 'ssr6', 31.00, 'joule', 4.00, 'Dunia Benang', 1, 'Rack3', NULL, NULL, NULL, NULL, '2025-06-07 04:43:37', '2025-06-07 04:48:22', NULL, '2025-06-07 04:48:22'),
(94, 'ssr7', 32.00, 'joule', 5.00, 'Dunia Benang', 1, 'Rack4', NULL, NULL, NULL, NULL, '2025-06-07 04:43:37', '2025-06-07 04:48:25', NULL, '2025-06-07 04:48:25'),
(95, 'ssr2', 33.00, 'joule', 6.00, 'Dunia Benang', 1, 'Rack5', NULL, NULL, NULL, NULL, '2025-06-07 04:43:37', '2025-06-07 04:48:27', NULL, '2025-06-07 04:48:27'),
(96, 'ssr1', 34.00, 'joule', 7.00, 'Dunia Benang', 1, 'Rack6', NULL, NULL, NULL, NULL, '2025-06-07 04:43:37', '2025-06-07 04:48:30', NULL, '2025-06-07 04:48:30'),
(97, 'bbooy1', 29.00, 'joule', 2.00, 'Dunia Benang', 1, 'Rack1', NULL, NULL, 'qrcodes/qr_6843c47686f9c.svg', NULL, '2025-06-07 04:47:50', '2025-06-07 04:48:14', NULL, '2025-06-07 04:48:14'),
(98, 'bbooy2', 30.00, 'joule', 3.00, 'Dunia Benang', 1, 'Rack2', NULL, NULL, 'qrcodes/qr_6843c477c7652.svg', NULL, '2025-06-07 04:47:51', '2025-06-07 04:48:12', NULL, '2025-06-07 04:48:12'),
(99, 'bbooy3', 31.00, 'joule', 4.00, 'Dunia Benang', 1, 'Rack3', NULL, NULL, 'qrcodes/qr_6843c477cbed9.svg', NULL, '2025-06-07 04:47:51', '2025-06-07 04:48:10', NULL, '2025-06-07 04:48:10'),
(100, 'bbooy4', 32.00, 'joule', 5.00, 'Dunia Benang', 1, 'Rack4', NULL, NULL, 'qrcodes/qr_6843c477cfd8b.svg', NULL, '2025-06-07 04:47:51', '2025-06-07 04:48:07', NULL, '2025-06-07 04:48:07'),
(101, 'bbooy5', 33.00, 'joule', 6.00, 'Dunia Benang', 1, 'Rack5', NULL, NULL, 'qrcodes/qr_6843c477d3d53.svg', NULL, '2025-06-07 04:47:51', '2025-06-07 04:48:05', NULL, '2025-06-07 04:48:05'),
(102, 'bbooy6', 34.00, 'joule', 7.00, 'Dunia Benang', 1, 'Rack6', NULL, NULL, 'qrcodes/qr_6843c477d7596.svg', NULL, '2025-06-07 04:47:51', '2025-06-07 04:48:02', NULL, '2025-06-07 04:48:02'),
(103, 'Tali200mm8', 23.00, 'km', 35566.00, NULL, 2, NULL, NULL, NULL, NULL, NULL, '2025-06-07 05:22:30', '2025-06-07 05:22:30', NULL, NULL),
(104, 'Tali200mm88', 23.00, 'km', 35566.00, NULL, 2, NULL, NULL, NULL, NULL, NULL, '2025-06-07 05:28:50', '2025-06-07 05:28:50', NULL, NULL),
(105, 'Tali200mm66', 0.00, 'km', 2233.00, NULL, 2, NULL, NULL, NULL, NULL, NULL, '2025-06-07 05:35:58', '2025-06-07 05:35:58', NULL, NULL),
(106, 'Tali200mmbb', 0.00, '-', 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-07 05:48:09', '2025-06-07 05:48:09', NULL, NULL),
(107, 'Tali200cv', 2.50, 'gh', 0.00, NULL, NULL, NULL, NULL, 'inventory_images/wdiZLbHTdZNqr5rk3ctD6P64ikDVu8KMBx3pfHXS.jpg', 'qrcodes/qr_684ce1d86faeb.svg', NULL, '2025-06-07 05:50:39', '2025-06-14 02:43:37', 20, NULL),
(108, 'Tali200cv1', 11.57, 'gh', 0.00, NULL, 6, NULL, NULL, NULL, NULL, NULL, '2025-06-07 05:55:42', '2025-06-07 05:55:42', NULL, NULL),
(109, 'lvimagination1', 1.40, '-', 134.60, 'lv', 2, 'rack 2', NULL, NULL, 'qrcodes/qr_6843d669c2fc8.svg', NULL, '2025-06-07 06:03:47', '2025-06-07 06:04:25', 9, NULL),
(110, 'lvimagination2', 1.50, 'kl', 134.70, 'lv', NULL, 'rack 2', NULL, NULL, NULL, NULL, '2025-06-07 06:05:37', '2025-06-07 06:05:37', NULL, NULL),
(111, 'lvimagination3', 2.50, 'kl', 135.70, 'lv', NULL, 'rack 3', NULL, NULL, 'qrcodes/qr_6843d916aae22.svg', NULL, '2025-06-07 06:15:50', '2025-06-07 06:15:50', NULL, NULL),
(112, 'lvimagination4', 3.50, 'kl', 136.70, 'lv', NULL, 'rack 4', NULL, NULL, 'qrcodes/qr_6843d916b4d5a.svg', NULL, '2025-06-07 06:15:50', '2025-06-07 06:15:50', NULL, NULL),
(113, 'lvimagination2a', 1.50, 'kl', 134.70, 'lv', NULL, 'rack 2', NULL, NULL, 'qrcodes/qr_6843d9897be17.svg', NULL, '2025-06-07 06:17:45', '2025-06-07 06:17:45', NULL, NULL),
(114, 'lvimagination45', 3.50, 'kl', 136.70, 'lv', NULL, 'rack 4', NULL, NULL, 'qrcodes/qr_6843d98986668.svg', NULL, '2025-06-07 06:17:45', '2025-06-07 06:17:45', NULL, NULL),
(115, 'sstty', 23.00, 'km ', 2.00, 'indo', 1, 'Rack 1', NULL, NULL, 'qrcodes/qr_684652f3b0da9.svg', NULL, '2025-06-09 03:20:19', '2025-06-09 03:20:21', NULL, NULL),
(116, 'sstty2', 23.00, 'km', 2.00, 'indo', 1, 'Rack 1', NULL, 'inventory_images/PlC8t3PyFudBnwzEB5ZjEezxWjA5Y236862iY3Vx.png', 'qrcodes/qr_6848e938b13c1.svg', NULL, '2025-06-09 03:25:50', '2025-06-11 02:26:01', 20, NULL),
(117, 'ddrt', 0.00, '-', 0.00, NULL, NULL, NULL, NULL, NULL, 'qrcodes/qr_6846548141982.svg', NULL, '2025-06-09 03:26:57', '2025-06-09 06:06:56', NULL, '2025-06-09 06:06:56'),
(118, 'sstty3', 23.00, 'km ', 2.00, 'indo', 1, 'Rack 1', NULL, NULL, 'qrcodes/qr_684654814c85e.svg', NULL, '2025-06-09 03:26:57', '2025-06-09 03:26:57', NULL, NULL),
(119, 'Kabel 1', 16.00, 'm', 32.00, 'MalayCable', 27, 'rack 1', NULL, 'images/qcZ4QvOAYT6jR42NYeSwbEg0v743FOKOg9FIRHWR.jpg', 'qrcodes/qr_68465ce8af7e6.svg', NULL, '2025-06-09 04:02:48', '2025-06-09 04:05:22', 17, NULL),
(120, 'dfdfv', 2.00, 'cm', 220.00, 'dong chi', 1, 'rack 1', NULL, 'inventory_images/iT55LEivHK5bq7V22s33BHpx6oToHXdwUzlOV1pu.jpg', 'qrcodes/qr_6849459ba1cc8.svg', NULL, '2025-06-09 07:55:49', '2025-06-11 09:00:11', 18, NULL),
(121, 'dfdfv2', 3.00, 'cm', 221.00, 'dong chi', 1, 'rack 2', NULL, NULL, 'qrcodes/qr_6846938603adb.svg', NULL, '2025-06-09 07:55:50', '2025-06-09 07:55:50', 1, NULL),
(122, 'dfdfv3', 4.00, 'cm', 222.00, 'dong chi', 1, 'rack 3', NULL, NULL, 'qrcodes/qr_6846938607bbf.svg', NULL, '2025-06-09 07:55:50', '2025-06-09 07:55:50', 18, NULL),
(123, 'cfcf', 46.00, 'Gulung', 23.00, 'dunia', 6, 'Rack 2', NULL, NULL, 'qrcodes/qr_68469435e8196.svg', NULL, '2025-06-09 07:58:45', '2025-06-09 07:58:45', 19, NULL),
(124, 'cfcf2', 47.00, 'm', 24.00, 'dunia', 6, 'Rack 3', 'ssdd', 'inventory_images/0MeJsnXbAZ7rzDmfJNDgvnKgmleTctwosPynXGQQ.jpg', 'qrcodes/qr_6849406ee4ada.svg', NULL, '2025-06-09 07:59:27', '2025-06-11 08:38:06', 20, NULL),
(125, 'cfcf34', 48.00, 'Gulung', 25.00, 'dunia', 6, 'Rack 4', NULL, 'inventory_images/JbHv7ugrpbUFop5TrxorYXcLtwqjuxS4O0tYx5LD.jpg', 'qrcodes/qr_68493f879368f.svg', NULL, '2025-06-09 07:59:27', '2025-06-11 08:34:15', 19, NULL),
(126, 'testsss34', 177.00, 'cm', 200.00, 'chengdu', 28, 'rack 1', 'test remark', 'inventory_images/baDIlvXcDabdHGkbheNuhBzv41zsvvySjXLJk06P.jpg', 'qrcodes/qr_6849323383ce5.svg', NULL, '2025-06-09 08:47:57', '2025-06-11 07:37:23', 20, NULL),
(138, 'ctrl', 23.00, 'cm', 40000.00, 'ssd', 2, 'Rack 3', 'test', 'inventory_images/3LpsY62nhniCriDpPZ62i3rD2XqZj3BcxpQbqBXG.jpg', 'qrcodes/qr_684d05509cf9a.svg', NULL, '2025-06-10 09:22:30', '2025-06-14 05:14:56', 19, NULL),
(139, 'fftyuiop', 2.00, 'cm', 100.00, 'Dunia Jarum', 28, 'rack 1', 'test', NULL, 'qrcodes/qr_684d03472f8a5.svg', NULL, '2025-06-14 05:06:15', '2025-06-14 05:31:36', 19, '2025-06-14 05:31:36'),
(140, 'Fiber Optik', 5.00, 'm', 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-14 05:42:58', '2025-06-14 05:42:58', NULL, NULL),
(141, 'Lycra / New Double-Sided Healthy Fabric 079 Blue', 8.00, 'm', 25.00, 'Wei Li Ye 伟利业 - CCM', 6, '-', 'Becky Bunny Father', NULL, 'qrcodes/qr_684fcffea71dc.svg', NULL, '2025-06-16 08:04:14', '2025-06-16 08:04:17', 1, NULL),
(142, 'Fur / A material crystal super soft / #114 Brown dark orange', 19.50, 'm', 12.00, 'Hong Ye 新加坡群 鸿业', 6, '-', 'Becky Bunny Mother', NULL, 'qrcodes/qr_684fd001d9c1c.svg', NULL, '2025-06-16 08:04:17', '2025-06-16 08:04:17', 1, NULL),
(143, 'Fur / YII002A Material Crystal Super Soft-223# Peach', 2.00, 'm', 10.00, 'Yuan Hua 远华集团 - CCM', 6, '-', 'Becky Bunny Father', NULL, 'qrcodes/qr_684fd001ddaf8.svg', NULL, '2025-06-16 08:04:17', '2025-06-16 08:04:17', 1, NULL),
(144, 'Fur/ Thickened Crystal Super Soft WL-2022 / #152 Brownn', 20.00, 'm', 20.00, 'Li Tai Long 利泰隆 x CCM', 6, '-', 'Becky Bunny Mother', 'inventory_images/2qCbw8WC8i2THoWondkUra1NocimmGgL3KAEyaXY.jpg', 'qrcodes/qr_6852245b0cdb7.svg', NULL, '2025-06-16 08:04:17', '2025-06-18 02:28:43', 1, NULL),
(145, 'KUNMU 8002 #14 Yellow', 164.00, 'm', 14.00, 'Kun Mu 绍兴 坤木纺织 - CCM', 6, 'B1-4', 'Leftover FFFA Bear Plush Toys 2022, 23/07/2024 Dolphin 2024 minion land', NULL, 'qrcodes/qr_684fd001e5e97.svg', NULL, '2025-06-16 08:04:17', '2025-06-16 08:04:17', 1, NULL),
(146, 'AT004 Nylon Matte #78# Blue', 184.50, 'm', 20.00, 'An Tai 安泰 Antai Anruda X CCM 新加坡', 6, '-', 'NDP Soka headgear & Soka legging', NULL, 'qrcodes/qr_684fd001ea27b.svg', NULL, '2025-06-16 08:04:17', '2025-06-17 04:50:28', 1, NULL),
(147, 'Fur / A material crystal 1mm-172-blue', 2.00, 'm', 12.00, 'Hong Ye 新加坡群 鸿业', 6, '-', 'Comic con mascot', NULL, 'qrcodes/qr_684fd001ede31.svg', NULL, '2025-06-16 08:04:17', '2025-06-16 08:04:17', 1, NULL),
(148, 'Fur / S Crystal Ultra Soft / #149 Dark Blue', 2.00, 'm', 20.00, 'Li Tai Long 利泰隆 x CCM', 6, '-', 'Comic con mascot', NULL, 'qrcodes/qr_684fd001f1df8.svg', NULL, '2025-06-16 08:04:17', '2025-06-16 08:04:18', 1, NULL),
(149, 'Fur / S Crystal Ultra Soft / #025 skin colour', 1.00, 'm', 20.00, 'Li Tai Long 利泰隆 x CCM', 6, '-', 'Comic Con Mascot', NULL, 'qrcodes/qr_684fd00201ed7.svg', NULL, '2025-06-16 08:04:18', '2025-06-16 08:04:18', 1, NULL),
(150, 'testsss334', 5.00, 'cm', 20.00, 'Dunia Jarum', NULL, 'rack 1', 'NDP (From Quick Add)', NULL, 'qrcodes/qr_684fe118afe1b.svg', NULL, '2025-06-16 09:06:14', '2025-06-16 09:17:12', 19, NULL),
(151, 'Tapak Kuda', 2.00, 'cm', 210.00, 'chengdu', 27, 'rack 1', 'for NDP Blue', NULL, 'qrcodes/qr_684fdf485d136.svg', NULL, '2025-06-16 09:08:39', '2025-06-16 09:09:28', 20, NULL),
(152, 'Speaker', 3.00, 'Pasang', 2.00, 'sss', NULL, NULL, 'For Server (From Quick Add)', 'inventory_images/qz5fT960Nezf9J4dg14QsCGUMgi0ncAWeZ8IWTRm.jpg', 'qrcodes/qr_684fe1b18e6d8.svg', NULL, '2025-06-16 09:19:10', '2025-06-16 09:19:45', 20, NULL),
(153, 'Tisu', 24.00, 'pack', 13000.00, 'Indomaret', 2, 'Rack 4', 'tisu', 'inventory_images/vRzP0GEUxsk6iSP8hsm6qFplxjIN76OOAdqT5rmB.jpg', 'qrcodes/qr_68510686c7905.svg', NULL, '2025-06-17 06:09:10', '2025-06-17 06:52:01', 22, NULL),
(154, 'Battery', 23.00, 'final test unit', 200000.00, 'Dunia Jarum', 2, 'rack 1', 'ssdd', 'inventory_images/4sRttkFyr9BafkNiJW32vvJDaUJY2Bgghx2luQEb.jpg', 'qrcodes/qr_68522535ab3bb.svg', NULL, '2025-06-18 02:32:21', '2025-06-18 02:32:21', 13, NULL),
(155, 'UPS 9Ah', 5.00, 'pcs', 200000.00, 'HNS', 2, 'rack 1', '222334', 'inventory_images/duYfpBpwsFDWnPvM5BykaCtBDz34RxrwmelRrn46.jpg', NULL, NULL, '2025-06-18 02:36:13', '2025-06-18 02:36:13', 23, NULL),
(156, 'Botol Aki', 4.00, 'dm', 4500.00, 'Dunia Jarum', 2, 'rack 1', 'sdcvsd', 'inventory_images/LER2Z8Ox1xZZNaG1Qt5WCLRI7pPdXOCLvxRWNm2I.jpg', NULL, NULL, '2025-06-18 06:50:59', '2025-06-18 06:53:00', 19, NULL),
(157, 'jhmyh', 1.00, 'final test unit', 44.00, 'erfvedr', 28, 'dfvdf', '4532', 'inventory_images/9nhHV6Dm1sSr2Ax1r8zll2Z5dszd8B1smhVA44PL.jpg', NULL, NULL, '2025-06-18 10:05:39', '2025-06-18 10:05:39', 20, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `inventory_transactions`
--

CREATE TABLE `inventory_transactions` (
  `id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `material_requests`
--

CREATE TABLE `material_requests` (
  `id` bigint UNSIGNED NOT NULL,
  `inventory_id` bigint UNSIGNED NOT NULL,
  `project_id` bigint UNSIGNED NOT NULL,
  `qty` decimal(10,2) NOT NULL,
  `processed_qty` decimal(10,2) NOT NULL DEFAULT '0.00',
  `requested_by` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `department` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remark` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` enum('pending','approved','delivered','canceled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `material_requests`
--

INSERT INTO `material_requests` (`id`, `inventory_id`, `project_id`, `qty`, `processed_qty`, `requested_by`, `department`, `remark`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(29, 69, 14, 1.00, 0.00, 'admin', 'management', 'testttt', 'approved', '2025-05-19 07:49:10', '2025-05-19 08:06:38', NULL),
(31, 65, 21, 3.00, 0.00, 'admin', 'management', 'testtting', 'pending', '2025-05-19 07:49:10', '2025-05-19 07:49:10', NULL),
(32, 63, 24, 5.00, 0.00, 'admin', 'management', 'testingg4', 'approved', '2025-05-19 07:49:10', '2025-06-17 08:18:12', NULL),
(33, 71, 25, 1.00, 0.00, 'admin', 'management', 'testing5', 'pending', '2025-05-19 07:51:33', '2025-05-19 07:51:33', NULL),
(34, 69, 15, 2.00, 0.00, 'admin', 'management', NULL, 'approved', '2025-05-19 07:51:33', '2025-05-19 08:06:40', NULL),
(35, 70, 23, 1.00, 0.00, 'admin', 'management', NULL, 'pending', '2025-05-19 07:51:33', '2025-05-19 07:51:33', NULL),
(36, 68, 19, 0.20, 0.00, 'admin', 'management', NULL, 'pending', '2025-05-19 07:51:33', '2025-05-19 07:51:33', NULL),
(37, 72, 26, 0.00, 0.00, 'admin', 'management', NULL, 'delivered', '2025-05-19 07:52:58', '2025-05-19 08:30:59', NULL),
(38, 66, 16, 0.60, 0.00, 'admin', 'management', NULL, 'approved', '2025-05-19 07:53:23', '2025-05-19 08:06:43', NULL),
(39, 64, 20, 0.00, 0.00, 'admin', 'management', NULL, 'delivered', '2025-05-19 07:53:52', '2025-05-22 03:38:03', NULL),
(40, 61, 23, 34.00, 0.00, 'admin', 'management', NULL, 'pending', '2025-05-19 07:53:52', '2025-05-19 07:53:52', NULL),
(43, 75, 25, 2.00, 0.00, 'admin', 'management', NULL, 'pending', '2025-05-20 02:05:06', '2025-05-20 02:05:06', NULL),
(44, 69, 25, 1.00, 0.00, 'admin', 'management', 'sdvsd', 'pending', '2025-05-20 02:20:44', '2025-05-20 02:20:44', NULL),
(57, 77, 21, 1.00, 0.00, 'admin', 'management', '4sdvsd', 'approved', '2025-05-20 02:43:32', '2025-05-20 03:21:57', NULL),
(72, 63, 31, 1.00, 0.00, 'admin', 'management', 'ever', 'approved', '2025-05-20 02:58:11', '2025-06-02 04:33:15', NULL),
(73, 86, 39, 0.10, 0.00, 'admin', 'management', 'final test mat req', 'approved', '2025-05-20 06:41:39', '2025-05-20 09:27:52', NULL),
(74, 84, 25, 0.00, 0.00, 'admin', 'management', 'testiing A', 'delivered', '2025-05-22 03:37:23', '2025-05-22 04:37:31', NULL),
(75, 85, 25, 0.00, 0.00, 'admin', 'management', 'etst', 'delivered', '2025-05-22 04:02:04', '2025-05-22 04:09:52', NULL),
(76, 75, 14, 0.00, 0.00, 'admin', 'management', 'asd', 'delivered', '2025-05-22 04:12:03', '2025-05-22 04:14:04', NULL),
(77, 59, 14, 0.00, 0.00, 'admin', 'management', '23/5', 'delivered', '2025-05-23 03:02:12', '2025-05-23 03:03:22', NULL),
(78, 61, 17, 6.05, 0.00, 'admin', 'management', 'test', 'approved', '2025-05-23 08:00:53', '2025-06-17 08:17:48', NULL),
(79, 69, 14, 3.00, 0.00, 'admin', 'management', '4', 'pending', '2025-05-24 04:35:59', '2025-05-24 04:35:59', NULL),
(80, 69, 14, 5.00, 0.00, 'costume', 'costume', NULL, 'pending', '2025-05-24 04:36:43', '2025-05-24 04:36:43', NULL),
(81, 84, 15, 1.00, 0.00, 'costume', 'costume', '2', 'pending', '2025-05-24 04:37:22', '2025-05-24 04:37:22', NULL),
(82, 69, 14, 1.00, 0.00, 'costume', 'costume', 'eee', 'pending', '2025-05-24 04:40:23', '2025-05-24 04:40:23', NULL),
(83, 69, 14, 1.00, 0.00, 'costume', 'costume', 'rrrrr', 'approved', '2025-05-24 04:51:18', '2025-06-17 08:45:36', NULL),
(84, 59, 14, 1.00, 0.00, 'costume', 'costume', '1', 'approved', '2025-05-24 04:56:32', '2025-05-27 03:50:03', NULL),
(85, 84, 25, 4.50, 0.00, 'admin', 'management', 'testtti', 'pending', '2025-05-28 03:10:50', '2025-05-28 03:10:50', NULL),
(86, 84, 25, 4.50, 0.00, 'admin', 'management', 'testtti', 'pending', '2025-05-28 03:11:28', '2025-05-28 03:11:28', NULL),
(87, 76, 31, 2.00, 0.00, 'admin', 'management', 'testttting1', 'pending', '2025-05-28 03:16:15', '2025-05-28 03:16:15', NULL),
(88, 76, 31, 2.00, 0.00, 'admin', 'management', 'testttting1', 'pending', '2025-05-28 03:17:38', '2025-05-28 03:17:38', NULL),
(89, 76, 31, 2.00, 0.00, 'admin', 'management', 'testttting1', 'pending', '2025-05-28 03:17:47', '2025-05-28 03:17:47', NULL),
(90, 64, 40, 1.00, 0.00, 'admin', 'management', 'test ajax', 'pending', '2025-05-28 04:18:40', '2025-05-28 04:18:40', NULL),
(91, 69, 14, 5.00, 0.00, 'admin', 'management', 't', 'pending', '2025-05-28 04:31:41', '2025-05-28 04:31:46', '2025-05-28 04:31:46'),
(92, 59, 14, 1.00, 0.00, 'admin', 'management', '1', 'pending', '2025-05-28 06:12:12', '2025-05-28 06:12:12', NULL),
(93, 69, 14, 1.00, 0.00, 'admin', 'management', NULL, 'pending', '2025-05-28 06:26:40', '2025-05-28 06:26:40', NULL),
(94, 69, 15, 1.00, 0.00, 'admin', 'management', 'asc', 'pending', '2025-05-28 06:29:19', '2025-05-28 06:29:19', NULL),
(95, 69, 25, 1.00, 0.00, 'admin', 'management', 'www', 'pending', '2025-05-28 06:31:13', '2025-05-28 06:31:13', NULL),
(96, 69, 14, 1.00, 0.00, 'admin', 'management', 'ffff', 'pending', '2025-05-28 07:35:52', '2025-05-28 07:35:52', NULL),
(97, 84, 25, 1.00, 0.00, 'admin', 'management', 'tyyyy', 'pending', '2025-05-28 07:43:12', '2025-05-28 07:43:12', NULL),
(98, 84, 25, 1.00, 0.00, 'admin', 'management', 'tyyyy', 'pending', '2025-05-28 07:43:37', '2025-05-28 07:43:37', NULL),
(99, 69, 15, 2.00, 0.00, 'admin', 'management', 'bbnnnccc', 'pending', '2025-05-28 07:44:27', '2025-05-28 07:47:52', '2025-05-28 07:47:52'),
(100, 69, 14, 1.00, 0.00, 'admin', 'management', 'sds', 'pending', '2025-05-28 08:46:41', '2025-05-28 08:46:41', NULL),
(101, 69, 15, 1.00, 0.00, 'admin', 'management', '111114444', 'pending', '2025-05-28 08:47:39', '2025-05-28 08:47:39', NULL),
(102, 65, 23, 1.00, 0.00, 'admin', 'management', 'zzzzzzzzz', 'pending', '2025-05-28 08:49:28', '2025-05-28 08:49:28', NULL),
(103, 74, 26, 1.00, 0.00, 'admin', 'management', 'ffffffffffff', 'pending', '2025-05-28 08:49:49', '2025-05-28 08:49:49', NULL),
(104, 66, 15, 1.00, 0.00, 'admin', 'management', 'dcv dsv', 'pending', '2025-05-28 08:52:15', '2025-05-28 08:52:15', NULL),
(105, 65, 23, 3.00, 0.00, 'admin', 'management', '3333333333', 'pending', '2025-05-28 08:59:30', '2025-05-28 08:59:30', NULL),
(106, 58, 19, 1.00, 0.00, 'admin', 'management', '2222', 'pending', '2025-05-28 09:06:39', '2025-05-30 01:26:37', '2025-05-30 01:26:37'),
(107, 66, 23, 1.00, 0.00, 'admin', 'management', 'defwe', 'pending', '2025-05-28 09:10:17', '2025-05-30 01:26:34', '2025-05-30 01:26:34'),
(108, 61, 40, 1.00, 0.00, 'admin', 'management', 'ffffffggg', 'pending', '2025-05-28 09:15:40', '2025-05-30 01:26:26', '2025-05-30 01:26:26'),
(109, 61, 24, 1.00, 0.00, 'admin', 'management', 'bjkbjkjkjk', 'pending', '2025-05-28 09:18:37', '2025-05-30 01:26:20', '2025-05-30 01:26:20'),
(110, 72, 14, 1.00, 0.00, 'admin', 'management', '22', 'pending', '2025-05-28 09:43:50', '2025-05-30 01:26:13', '2025-05-30 01:26:13'),
(111, 60, 16, 1.00, 0.00, 'admin', 'management', 'nnnnnn', 'pending', '2025-05-28 09:54:58', '2025-06-09 07:03:09', NULL),
(112, 69, 14, 1.00, 0.00, 'admin', 'management', 'ggggggggg', 'pending', '2025-05-30 01:26:52', '2025-05-30 01:26:52', NULL),
(113, 72, 16, 1.00, 0.00, 'admin', 'management', 'ffffffff', 'pending', '2025-05-30 01:38:28', '2025-05-30 01:38:28', NULL),
(114, 69, 14, 1.00, 0.00, 'admin', 'management', 'qqqq33', 'pending', '2025-05-30 01:50:36', '2025-05-30 01:50:36', NULL),
(115, 60, 14, 1.00, 0.00, 'admin', 'management', 'rrrttt', 'pending', '2025-05-30 02:00:08', '2025-05-30 02:00:08', NULL),
(116, 76, 26, 1.00, 0.00, 'admin', 'management', 'rrrrrrrwwwww', 'pending', '2025-05-30 02:18:54', '2025-05-30 02:18:54', NULL),
(117, 84, 14, 1.00, 0.00, 'admin', 'management', 'vvvvvvvvv', 'pending', '2025-05-30 02:26:55', '2025-05-30 02:26:55', NULL),
(118, 66, 30, 1.00, 0.00, 'admin', 'management', 'bbbbvvvvv', 'pending', '2025-05-30 02:29:29', '2025-05-30 02:29:29', NULL),
(119, 70, 39, 1.00, 0.00, 'admin', 'management', 'aaaaabbbcc', 'pending', '2025-05-30 02:30:16', '2025-05-30 02:30:16', NULL),
(120, 60, 17, 1.00, 0.00, 'admin', 'management', 'ccccv', 'pending', '2025-05-30 02:31:26', '2025-05-30 02:31:26', NULL),
(121, 59, 16, 1.00, 0.00, 'admin', 'management', 'mmmmmmmm', 'pending', '2025-05-30 02:39:06', '2025-05-30 02:39:06', NULL),
(122, 60, 15, 1.00, 0.00, 'admin', 'management', 'ghghghgh', 'pending', '2025-05-30 02:43:30', '2025-05-30 02:43:30', NULL),
(123, 69, 14, 1.00, 0.00, 'admin', 'management', 'rowrow', 'pending', '2025-05-30 03:13:44', '2025-05-30 03:13:44', NULL),
(124, 69, 14, 1.00, 0.00, 'admin', 'management', 'cxcxcx', 'pending', '2025-05-30 03:21:13', '2025-05-30 03:21:13', NULL),
(125, 59, 14, 1.00, 0.00, 'admin', 'management', 'DDD', 'pending', '2025-05-30 03:24:51', '2025-05-30 03:24:51', NULL),
(126, 59, 14, 2.00, 0.00, 'admin', 'management', 'WKWK', 'pending', '2025-05-30 03:26:49', '2025-05-30 03:26:49', NULL),
(127, 69, 14, 1.00, 0.00, 'admin', 'management', '5555555', 'pending', '2025-05-30 03:36:08', '2025-05-30 03:36:08', NULL),
(128, 69, 14, 1.00, 0.00, 'admin', 'management', 'hhhhhh', 'pending', '2025-05-30 03:40:38', '2025-05-30 03:40:38', NULL),
(129, 59, 15, 1.00, 0.00, 'admin', 'management', 'jkjkjk', 'pending', '2025-05-30 03:45:43', '2025-05-30 03:45:43', NULL),
(130, 60, 15, 1.00, 0.00, 'admin', 'management', 'zxzxzxz', 'pending', '2025-05-30 03:49:01', '2025-05-30 03:49:01', NULL),
(131, 84, 14, 1.00, 0.00, 'admin', 'management', 'tttttt', 'pending', '2025-05-30 04:04:10', '2025-05-30 04:04:10', NULL),
(132, 69, 14, 1.00, 0.00, 'admin', 'management', 'ccccccttttttttt', 'pending', '2025-05-30 04:08:38', '2025-05-30 04:08:38', NULL),
(133, 59, 14, 1.00, 0.00, 'admin', 'management', 'ddff', 'pending', '2025-05-30 04:11:37', '2025-05-30 04:11:37', NULL),
(134, 59, 14, 1.00, 0.00, 'admin', 'management', 'ggggg', 'pending', '2025-05-30 04:13:22', '2025-05-30 04:13:22', NULL),
(135, 60, 15, 2.00, 0.00, 'admin', 'management', 'dfdfdf', 'pending', '2025-05-30 04:16:19', '2025-05-30 04:16:19', NULL),
(136, 69, 14, 1.00, 0.00, 'admin', 'management', 'cdcdc', 'pending', '2025-05-30 04:21:16', '2025-05-30 04:21:16', NULL),
(137, 59, 14, 1.00, 0.00, 'admin', 'management', 'klklkl', 'pending', '2025-05-30 04:29:03', '2025-05-30 04:29:03', NULL),
(138, 69, 25, 1.00, 0.00, 'admin', 'management', 'sdcsd', 'pending', '2025-05-30 04:55:27', '2025-05-30 04:55:27', NULL),
(139, 59, 14, 1.00, 0.00, 'admin', 'management', 'sdcsd', 'pending', '2025-05-30 04:57:59', '2025-05-30 04:57:59', NULL),
(140, 69, 14, 1.00, 0.00, 'admin', 'management', 'ee', 'pending', '2025-05-30 06:01:21', '2025-05-30 06:01:21', NULL),
(141, 69, 25, 1.00, 0.00, 'admin', 'management', 'sdvsdv', 'pending', '2025-05-30 06:09:38', '2025-05-30 06:09:38', NULL),
(142, 69, 14, 1.00, 0.00, 'admin', 'management', 'sdcsd', 'pending', '2025-05-30 06:14:45', '2025-05-30 06:14:45', NULL),
(143, 59, 15, 1.00, 0.00, 'admin', 'management', 'fg fg', 'pending', '2025-05-30 06:14:59', '2025-05-30 06:14:59', NULL),
(144, 59, 15, 1.00, 0.00, 'admin', 'management', 'bgbgb', 'pending', '2025-05-30 06:19:49', '2025-05-30 06:19:49', NULL),
(145, 59, 15, 1.00, 0.00, 'admin', 'management', 'ddcd', 'pending', '2025-05-30 06:20:10', '2025-05-30 06:20:10', NULL),
(146, 60, 15, 1.00, 0.00, 'admin', 'management', 'cvvv', 'pending', '2025-05-30 06:20:28', '2025-05-30 06:20:28', NULL),
(147, 69, 15, 1.00, 0.00, 'admin', 'management', 'sdvsd', 'pending', '2025-05-30 06:21:21', '2025-05-30 06:21:21', NULL),
(148, 69, 14, 1.00, 0.00, 'admin', 'management', 'fgfg', 'pending', '2025-05-30 06:23:21', '2025-05-30 06:23:21', NULL),
(149, 59, 14, 1.00, 0.00, 'admin', 'management', 'dfv', 'pending', '2025-05-30 06:23:40', '2025-05-30 06:23:40', NULL),
(150, 63, 18, 1.00, 0.00, 'admin', 'management', 'testinggg', 'pending', '2025-05-30 06:24:33', '2025-05-30 06:24:33', NULL),
(151, 60, 15, 0.00, 0.00, 'admin', 'management', 'dfvdf', 'delivered', '2025-05-30 06:25:56', '2025-06-03 07:58:01', NULL),
(152, 69, 14, 1.00, 0.00, 'admin', 'management', 'ddd', 'pending', '2025-05-30 06:28:42', '2025-05-30 06:28:42', NULL),
(153, 69, 15, 1.00, 0.00, 'admin', 'management', 'sdsd', 'pending', '2025-05-30 06:29:37', '2025-05-30 06:29:37', NULL),
(154, 59, 15, 2.00, 0.00, 'admin', 'management', 'dfvdf', 'pending', '2025-05-30 06:29:52', '2025-05-30 06:44:03', '2025-05-30 06:44:03'),
(155, 60, 17, 3.00, 0.00, 'admin', 'management', 'fver', 'pending', '2025-05-30 06:30:14', '2025-05-30 06:43:55', '2025-05-30 06:43:55'),
(156, 60, 14, 1.00, 0.00, 'admin', 'management', 'fgbf', 'pending', '2025-05-30 06:32:58', '2025-05-30 06:36:23', '2025-05-30 06:36:23'),
(157, 77, 15, 1.50, 0.00, 'admin', 'management', 'efved', 'pending', '2025-05-30 06:33:26', '2025-05-30 06:36:10', '2025-05-30 06:36:10'),
(158, 77, 39, 1.00, 0.00, 'admin', 'management', 'hhgg', 'pending', '2025-05-30 06:33:58', '2025-05-30 06:35:13', '2025-05-30 06:35:13'),
(159, 60, 14, 1.00, 0.00, 'admin', 'management', 'ssdd', 'pending', '2025-05-30 06:56:31', '2025-05-30 06:58:36', NULL),
(160, 59, 16, 2.00, 0.00, 'admin', 'management', 'ddd', 'approved', '2025-05-30 06:56:44', '2025-05-30 06:58:17', NULL),
(161, 69, 15, 0.00, 0.00, 'admin', 'management', 'vvvvddd', 'delivered', '2025-05-30 06:59:12', '2025-06-03 07:58:21', NULL),
(162, 69, 14, 1.00, 0.00, 'admin', 'management', 'mmnnmm', 'pending', '2025-05-30 07:00:59', '2025-05-30 07:00:59', NULL),
(163, 59, 14, 1.00, 0.00, 'admin', 'management', 'hyhyh', 'pending', '2025-05-30 07:13:17', '2025-05-30 07:13:17', NULL),
(164, 72, 14, 1.00, 0.00, 'admin', 'management', 'ddf', 'pending', '2025-05-30 07:13:45', '2025-05-30 07:13:45', NULL),
(165, 60, 16, 1.00, 0.00, 'admin', 'management', 'ffg', 'pending', '2025-05-30 07:13:58', '2025-05-30 07:13:58', NULL),
(166, 61, 26, 1.00, 0.00, 'admin', 'management', 'ffuiu', 'pending', '2025-05-30 07:15:03', '2025-05-30 07:15:03', NULL),
(167, 60, 14, 1.00, 0.00, 'admin', 'management', NULL, 'pending', '2025-05-30 07:18:18', '2025-05-30 07:18:18', NULL),
(168, 59, 15, 2.00, 0.00, 'admin', 'management', NULL, 'pending', '2025-05-30 07:18:18', '2025-05-30 07:18:18', NULL),
(169, 60, 39, 1.00, 0.00, 'admin', 'management', NULL, 'pending', '2025-05-30 07:18:18', '2025-05-30 07:18:18', NULL),
(170, 60, 20, 2.00, 0.00, 'admin', 'management', NULL, 'pending', '2025-05-30 07:18:18', '2025-05-30 07:18:18', NULL),
(171, 60, 15, 1.00, 0.00, 'admin', 'management', 'ggbbg', 'pending', '2025-05-30 07:31:47', '2025-05-30 07:31:47', NULL),
(172, 77, 15, 1.00, 0.00, 'admin', 'management', 'ff', 'pending', '2025-05-30 07:32:12', '2025-05-30 07:32:12', NULL),
(173, 59, 39, 1.00, 0.00, 'admin', 'management', 'gg', 'pending', '2025-05-30 07:32:12', '2025-05-30 07:32:12', NULL),
(174, 77, 15, 1.00, 0.00, 'admin', 'management', 'ff', 'pending', '2025-05-30 07:35:21', '2025-05-30 07:35:21', NULL),
(175, 59, 39, 1.00, 0.00, 'admin', 'management', 'gg', 'pending', '2025-05-30 07:35:22', '2025-05-30 07:35:22', NULL),
(176, 66, 23, 1.00, 0.00, 'admin', 'management', 'yy', 'pending', '2025-05-30 07:35:46', '2025-05-30 07:35:46', NULL),
(177, 73, 31, 1.00, 0.00, 'admin', 'management', 'uu', 'pending', '2025-05-30 07:35:46', '2025-05-30 07:35:46', NULL),
(178, 72, 23, 1.00, 0.00, 'admin', 'management', '00', 'pending', '2025-05-30 07:36:47', '2025-05-30 07:36:47', NULL),
(179, 70, 31, 1.00, 0.00, 'admin', 'management', '99', 'pending', '2025-05-30 07:36:48', '2025-05-30 07:36:48', NULL),
(180, 59, 40, 1.00, 0.00, 'admin', 'management', '88', 'pending', '2025-05-30 07:36:48', '2025-05-30 07:36:48', NULL),
(181, 65, 15, 1.00, 0.00, 'admin', 'management', '22', 'pending', '2025-05-30 07:39:05', '2025-05-30 07:39:05', NULL),
(182, 60, 39, 1.00, 0.00, 'admin', 'management', NULL, 'pending', '2025-05-30 07:39:06', '2025-05-30 07:39:06', NULL),
(183, 69, 24, 1.00, 0.00, 'admin', 'management', NULL, 'pending', '2025-05-30 07:39:06', '2025-05-30 07:39:06', NULL),
(184, 69, 30, 1.00, 0.00, 'admin', 'management', NULL, 'pending', '2025-05-30 07:39:06', '2025-05-30 07:39:06', NULL),
(185, 59, 26, 1.00, 0.00, 'admin', 'management', NULL, 'pending', '2025-05-30 07:39:06', '2025-05-30 07:39:06', NULL),
(186, 59, 40, 1.00, 0.00, 'admin', 'management', NULL, 'pending', '2025-05-30 07:39:06', '2025-05-30 07:39:06', NULL),
(187, 69, 21, 1.00, 0.00, 'admin', 'management', NULL, 'pending', '2025-05-30 07:39:06', '2025-05-30 07:39:06', NULL),
(188, 59, 23, 1.00, 0.00, 'admin', 'management', NULL, 'pending', '2025-05-30 07:39:06', '2025-05-30 07:39:06', NULL),
(189, 72, 31, 1.00, 0.00, 'admin', 'management', NULL, 'pending', '2025-05-30 07:39:06', '2025-05-30 07:39:06', NULL),
(190, 69, 26, 1.00, 0.00, 'admin', 'management', NULL, 'pending', '2025-05-30 07:39:06', '2025-05-30 07:39:06', NULL),
(191, 69, 23, 1.00, 0.00, 'admin', 'management', NULL, 'pending', '2025-05-30 07:39:06', '2025-05-30 07:39:06', NULL),
(192, 72, 31, 1.00, 0.00, 'admin', 'management', NULL, 'pending', '2025-05-30 07:39:06', '2025-05-30 07:39:06', NULL),
(193, 69, 17, 1.00, 0.00, 'admin', 'management', NULL, 'pending', '2025-05-30 07:39:06', '2025-05-30 07:39:06', NULL),
(194, 59, 40, 1.00, 0.00, 'admin', 'management', NULL, 'pending', '2025-05-30 07:39:06', '2025-05-30 07:39:06', NULL),
(195, 61, 31, 1.00, 0.00, 'admin', 'management', NULL, 'pending', '2025-05-30 07:39:06', '2025-05-30 07:39:06', NULL),
(196, 69, 14, 1.00, 0.00, 'admin', 'management', '1', 'pending', '2025-05-30 07:48:45', '2025-05-30 07:48:45', NULL),
(197, 59, 15, 1.00, 0.00, 'admin', 'management', '2', 'pending', '2025-05-30 07:48:46', '2025-05-30 07:48:46', NULL),
(198, 60, 16, 1.00, 0.00, 'admin', 'management', '3', 'pending', '2025-05-30 07:48:46', '2025-05-30 07:48:46', NULL),
(199, 60, 39, 1.00, 0.00, 'admin', 'management', '4', 'pending', '2025-05-30 07:48:46', '2025-05-30 07:48:46', NULL),
(200, 59, 17, 1.00, 0.00, 'admin', 'management', '5', 'pending', '2025-05-30 07:48:46', '2025-05-30 07:48:46', NULL),
(201, 67, 20, 1.00, 0.00, 'admin', 'management', '6', 'pending', '2025-05-30 07:48:47', '2025-05-30 07:48:47', NULL),
(202, 60, 21, 1.00, 0.00, 'admin', 'management', '7', 'pending', '2025-05-30 07:48:47', '2025-05-30 07:48:47', NULL),
(203, 60, 40, 1.00, 0.00, 'admin', 'management', '8', 'pending', '2025-05-30 07:48:47', '2025-05-30 07:48:47', NULL),
(204, 85, 31, 1.00, 0.00, 'admin', 'management', '9', 'pending', '2025-05-30 07:48:47', '2025-05-30 07:48:47', NULL),
(205, 72, 26, 1.00, 0.00, 'admin', 'management', '10', 'pending', '2025-05-30 07:48:47', '2025-05-30 07:48:47', NULL),
(206, 60, 15, 1.00, 0.00, 'admin', 'management', 'fff', 'pending', '2025-05-30 07:49:37', '2025-05-30 07:49:37', NULL),
(207, 60, 14, 1.00, 0.00, 'admin', 'management', '3', 'pending', '2025-05-30 07:50:08', '2025-05-30 07:50:08', NULL),
(208, 59, 16, 2.00, 0.00, 'admin', 'management', '4', 'pending', '2025-05-30 07:50:09', '2025-05-30 07:50:09', NULL),
(209, 60, 39, 3.00, 0.00, 'admin', 'management', '1', 'pending', '2025-05-30 07:50:09', '2025-05-30 07:50:09', NULL),
(210, 69, 24, 1.00, 0.00, 'admin', 'management', '2', 'pending', '2025-05-30 07:50:09', '2025-05-30 07:50:09', NULL),
(211, 59, 25, 1.00, 0.00, 'admin', 'management', 'fgbfg', 'pending', '2025-05-30 07:52:50', '2025-05-30 07:52:50', NULL),
(212, 69, 14, 1.00, 0.00, 'costume', 'costume', 'gg', 'pending', '2025-05-30 07:53:45', '2025-05-30 07:53:45', NULL),
(213, 60, 15, 1.00, 0.00, 'admin', 'management', '3', 'pending', '2025-05-30 08:06:00', '2025-05-30 08:06:00', NULL),
(214, 69, 39, 1.00, 0.00, 'admin', 'management', '1', 'pending', '2025-05-30 08:06:01', '2025-05-30 08:06:01', NULL),
(215, 59, 15, 1.00, 0.00, 'admin', 'management', '1', 'pending', '2025-05-30 08:06:01', '2025-05-30 08:06:01', NULL),
(216, 69, 27, 1.00, 0.00, 'admin', 'management', '1', 'pending', '2025-05-30 08:06:01', '2025-05-30 08:06:01', NULL),
(217, 69, 20, 1.00, 0.00, 'admin', 'management', '1', 'pending', '2025-05-30 08:06:01', '2025-05-30 08:06:01', NULL),
(218, 65, 26, 1.00, 0.00, 'admin', 'management', NULL, 'pending', '2025-05-30 08:06:01', '2025-05-30 08:06:01', NULL),
(219, 69, 14, 1.00, 0.00, 'admin', 'management', '2', 'pending', '2025-05-30 08:21:59', '2025-05-30 08:21:59', NULL),
(220, 59, 15, 1.00, 0.00, 'admin', 'management', '1', 'pending', '2025-05-30 08:22:00', '2025-05-30 08:22:00', NULL),
(221, 69, 14, 1.00, 0.00, 'admin', 'management', '1', 'pending', '2025-05-30 08:24:22', '2025-05-30 08:24:22', NULL),
(222, 59, 14, 1.00, 0.00, 'admin', 'management', '2', 'pending', '2025-05-30 08:25:17', '2025-05-30 08:25:17', NULL),
(223, 61, 16, 1.00, 0.00, 'admin', 'management', '3', 'pending', '2025-05-30 08:25:17', '2025-05-30 08:25:17', NULL),
(224, 69, 39, 1.00, 0.00, 'admin', 'management', NULL, 'pending', '2025-05-30 08:25:17', '2025-05-30 08:25:17', NULL),
(225, 69, 15, 1.00, 0.00, 'admin', 'management', '1', 'pending', '2025-05-30 08:30:21', '2025-05-30 08:30:21', NULL),
(226, 60, 16, 1.00, 0.00, 'admin', 'management', '2', 'pending', '2025-05-30 08:30:22', '2025-05-30 08:30:22', NULL),
(227, 69, 17, 1.00, 0.00, 'admin', 'management', '1', 'pending', '2025-05-30 08:30:22', '2025-05-30 08:30:22', NULL),
(228, 69, 15, 1.00, 0.00, 'admin', 'management', '22', 'pending', '2025-05-30 08:30:37', '2025-05-30 08:30:37', NULL),
(229, 69, 15, 1.00, 0.00, 'admin', 'management', '1', 'pending', '2025-05-30 08:30:49', '2025-05-30 08:30:49', NULL),
(230, 69, 14, 1.00, 0.00, 'admin', 'management', '111', 'pending', '2025-05-30 08:36:54', '2025-05-30 08:36:54', NULL),
(231, 59, 14, 1.00, 0.00, 'admin', 'management', '1', 'pending', '2025-05-30 08:38:15', '2025-05-30 08:38:15', NULL),
(232, 59, 20, 1.00, 0.00, 'admin', 'management', '2', 'pending', '2025-05-30 08:39:24', '2025-05-30 08:39:24', NULL),
(233, 59, 24, 1.00, 0.00, 'admin', 'management', '22', 'pending', '2025-05-30 08:39:25', '2025-05-30 08:39:25', NULL),
(234, 69, 23, 1.00, 0.00, 'admin', 'management', '111', 'pending', '2025-05-30 08:39:25', '2025-05-30 08:39:25', NULL),
(235, 69, 15, 1.00, 0.00, 'admin', 'management', '1', 'pending', '2025-05-30 08:44:08', '2025-05-30 08:44:08', NULL),
(236, 59, 39, 1.00, 0.00, 'admin', 'management', '1', 'pending', '2025-05-30 08:44:09', '2025-05-30 08:44:09', NULL),
(237, 59, 15, 1.00, 0.00, 'admin', 'management', '1', 'pending', '2025-05-30 08:44:09', '2025-05-30 08:44:09', NULL),
(238, 61, 16, 1.10, 0.00, 'admin', 'management', '1', 'pending', '2025-05-30 08:44:09', '2025-05-30 08:44:09', NULL),
(239, 69, 14, 1.00, 0.00, 'admin', 'management', '2', 'pending', '2025-05-30 08:52:34', '2025-05-30 08:52:34', NULL),
(240, 69, 14, 1.00, 0.00, 'admin', 'management', '2', 'pending', '2025-05-30 08:52:35', '2025-05-30 08:52:35', NULL),
(241, 69, 16, 1.00, 0.00, 'admin', 'management', '2', 'pending', '2025-05-30 08:52:35', '2025-05-30 08:52:35', NULL),
(242, 59, 15, 1.00, 0.00, 'admin', 'management', '1', 'pending', '2025-05-30 08:56:37', '2025-05-30 08:56:37', NULL),
(243, 59, 16, 1.00, 0.00, 'admin', 'management', '2', 'pending', '2025-05-30 08:56:38', '2025-05-30 08:56:38', NULL),
(244, 69, 39, 1.00, 0.00, 'admin', 'management', '2', 'pending', '2025-05-30 08:56:38', '2025-05-30 08:56:38', NULL),
(245, 69, 24, 1.00, 0.00, 'admin', 'management', '2', 'delivered', '2025-05-30 08:56:38', '2025-06-03 02:11:35', NULL),
(246, 69, 14, 1.00, 0.00, 'admin', 'management', '1', 'pending', '2025-05-30 08:57:15', '2025-05-30 08:57:15', NULL),
(247, 61, 16, 1.00, 0.00, 'admin', 'management', '1', 'pending', '2025-05-30 08:57:15', '2025-05-30 08:57:15', NULL),
(248, 72, 16, 1.00, 0.00, 'admin', 'management', '1', 'delivered', '2025-05-30 08:57:30', '2025-06-03 02:10:48', NULL),
(249, 59, 30, 1.00, 0.00, 'admin', 'management', '2', 'approved', '2025-05-30 08:57:58', '2025-05-30 09:04:17', NULL),
(250, 60, 23, 1.00, 0.00, 'admin', 'management', '1', 'delivered', '2025-05-30 08:57:58', '2025-06-03 01:40:38', NULL),
(251, 62, 31, 1.00, 0.00, 'admin', 'management', '1', 'delivered', '2025-05-30 08:57:58', '2025-06-03 01:40:38', NULL),
(252, 69, 14, 1.00, 0.00, 'admin', 'management', 'tytyty', 'approved', '2025-05-30 09:07:52', '2025-06-02 04:30:51', '2025-06-02 04:30:51'),
(253, 69, 14, 1.01, 0.00, 'admin', 'management', 'def', 'pending', '2025-05-30 09:14:05', '2025-06-02 09:44:03', NULL),
(254, 69, 14, 1.00, 0.00, 'logitech', 'management', 'sss', 'pending', '2025-06-03 02:13:25', '2025-06-03 02:13:25', NULL),
(255, 60, 25, 2.00, 0.00, 'tari', 'costume', 'kkkkn', 'pending', '2025-06-03 02:16:23', '2025-06-03 02:16:23', NULL),
(256, 62, 26, 0.00, 0.00, 'tari', 'costume', 'jjjjjjjjjj', 'delivered', '2025-06-03 02:22:25', '2025-06-04 06:56:21', NULL),
(257, 84, 14, 1.00, 0.00, 'tari', 'costume', 'lklklk', 'pending', '2025-06-03 02:34:41', '2025-06-03 02:34:41', NULL),
(258, 66, 23, 1.00, 0.00, 'tari', 'costume', 'ioio', 'pending', '2025-06-03 02:39:05', '2025-06-03 02:39:05', NULL),
(259, 69, 16, 1.00, 0.00, 'tari', 'costume', 'uyuyuy', 'pending', '2025-06-03 02:39:50', '2025-06-03 02:39:50', NULL),
(260, 60, 41, 1.00, 0.00, 'tari', 'costume', 'qwqw', 'pending', '2025-06-03 02:48:53', '2025-06-03 02:48:53', NULL),
(261, 69, 14, 1.00, 0.00, 'tari', 'costume', 'rrrt', 'pending', '2025-06-03 02:50:16', '2025-06-03 02:50:16', NULL),
(262, 77, 16, 1.00, 0.00, 'tari', 'costume', 'rwrw', 'pending', '2025-06-03 02:50:38', '2025-06-03 02:50:38', NULL),
(263, 69, 14, 1.00, 0.00, 'tari', 'costume', 'cxcx', 'pending', '2025-06-03 03:00:03', '2025-06-03 03:00:03', NULL),
(264, 72, 16, 1.00, 0.00, 'tari', 'costume', 'fdfd', 'pending', '2025-06-03 03:02:49', '2025-06-03 03:02:49', NULL),
(265, 60, 16, 1.00, 0.00, 'tari', 'costume', 'opop', 'pending', '2025-06-03 03:04:17', '2025-06-03 03:04:17', NULL),
(266, 69, 15, 1.00, 0.00, 'tari', 'costume', 'hoho', 'pending', '2025-06-03 03:15:30', '2025-06-03 03:15:30', NULL),
(267, 69, 14, 1.00, 0.00, 'tari', 'costume', 'hyhy', 'pending', '2025-06-03 04:31:35', '2025-06-03 04:31:35', NULL),
(268, 69, 14, 1.00, 0.00, 'tari', 'costume', 'jhjh', 'pending', '2025-06-03 04:41:39', '2025-06-03 04:41:39', NULL),
(269, 69, 15, 1.00, 0.00, 'tari', 'costume', 'cdcd', 'pending', '2025-06-03 04:43:20', '2025-06-03 04:43:20', NULL),
(270, 69, 15, 1.00, 0.00, 'tari', 'costume', 'tftf', 'pending', '2025-06-03 04:44:32', '2025-06-03 04:44:32', NULL),
(271, 69, 14, 1.00, 0.00, 'tari', 'costume', 'crcr', 'pending', '2025-06-03 04:46:41', '2025-06-03 04:46:41', NULL),
(272, 69, 14, 1.00, 0.00, 'tari', 'costume', 'gkgk', 'pending', '2025-06-03 04:48:38', '2025-06-03 04:48:38', NULL),
(273, 69, 14, 1.00, 0.00, 'tari', 'costume', 'zmzm', 'pending', '2025-06-03 04:51:22', '2025-06-03 04:51:22', NULL),
(274, 69, 14, 1.00, 0.00, 'tari', 'costume', 'plplp', 'pending', '2025-06-03 09:43:30', '2025-06-03 09:43:30', NULL),
(275, 69, 14, 1.00, 0.00, 'tari', 'costume', 'eeuu', 'pending', '2025-06-03 09:58:56', '2025-06-03 09:58:56', NULL),
(276, 69, 14, 1.00, 0.00, 'tari', 'costume', 'bangsut', 'pending', '2025-06-03 10:00:42', '2025-06-03 10:00:42', NULL),
(277, 69, 15, 1.00, 0.00, 'tari', 'costume', 'xixixi', 'pending', '2025-06-03 10:02:26', '2025-06-03 10:02:26', NULL),
(278, 69, 14, 1.00, 0.00, 'tari', 'costume', 'ezzzzz', 'pending', '2025-06-03 10:02:53', '2025-06-03 10:02:53', NULL),
(279, 69, 25, 1.00, 0.00, 'logitech', 'management', 'trtrt', 'pending', '2025-06-03 10:06:25', '2025-06-03 10:06:25', NULL),
(280, 69, 14, 1.00, 0.00, 'tari', 'costume', 'bgbg', 'pending', '2025-06-04 01:44:36', '2025-06-04 01:44:36', NULL),
(281, 69, 14, 1.00, 0.00, 'tari', 'costume', 'fmfm', 'pending', '2025-06-04 01:51:45', '2025-06-04 01:51:45', NULL),
(282, 69, 14, 1.00, 0.00, 'tari', 'costume', 'vtvt', 'pending', '2025-06-04 01:56:46', '2025-06-04 01:56:46', NULL),
(283, 69, 14, 1.00, 0.00, 'tari', 'costume', 'vovo', 'pending', '2025-06-04 02:05:16', '2025-06-04 02:05:16', NULL),
(284, 69, 14, 2.00, 0.00, 'tari', 'costume', 'gqgq', 'pending', '2025-06-04 02:13:15', '2025-06-17 09:38:07', NULL),
(285, 69, 14, 1.00, 0.00, 'tari', 'costume', 'mo', 'approved', '2025-06-04 02:41:49', '2025-06-17 08:45:10', NULL),
(286, 69, 15, 1.00, 0.00, 'tari', 'costume', 'jj', 'pending', '2025-06-04 02:42:08', '2025-06-04 02:42:08', NULL),
(287, 69, 14, 1.00, 0.00, 'tari', 'costume', 'gmgmn', 'approved', '2025-06-04 02:48:59', '2025-06-17 08:47:56', NULL),
(288, 69, 14, 1.00, 0.00, 'tari', 'costume', 'ft', 'pending', '2025-06-04 02:49:36', '2025-06-04 02:49:36', NULL),
(289, 60, 16, 1.00, 0.00, 'tari', 'costume', 'fy', 'pending', '2025-06-04 02:49:36', '2025-06-04 02:49:36', NULL),
(290, 69, 39, 1.00, 0.00, 'tari', 'costume', 'fu', 'approved', '2025-06-04 02:49:36', '2025-06-17 08:44:47', NULL),
(291, 84, 25, 1.00, 0.00, 'tari', 'costume', '2ee', 'approved', '2025-06-04 03:19:24', '2025-06-05 08:22:36', NULL),
(292, 69, 14, 1.00, 0.00, 'tari', 'costume', '222', 'approved', '2025-06-04 03:37:03', '2025-06-17 08:45:53', NULL),
(293, 69, 14, 1.00, 0.00, 'tari', 'costume', 'cbcb', 'approved', '2025-06-04 03:38:46', '2025-06-17 08:45:16', NULL),
(294, 69, 25, 1.00, 0.00, 'tari', 'costume', 'ccf', 'pending', '2025-06-04 04:10:56', '2025-06-09 06:07:29', '2025-06-09 06:07:29'),
(295, 84, 25, 1.00, 0.00, 'tari', 'costume', 'ffv', 'delivered', '2025-06-04 04:11:30', '2025-06-04 06:57:55', NULL),
(296, 69, 14, 1.00, 0.00, 'tari', 'costume', 'vt', 'approved', '2025-06-04 04:13:30', '2025-06-17 08:34:07', NULL),
(297, 69, 25, 0.00, 0.00, 'tari', 'costume', 'test request', 'delivered', '2025-06-04 06:44:24', '2025-06-04 06:54:10', NULL),
(298, 84, 14, 0.00, 0.00, 'tari', 'costume', '1', 'delivered', '2025-06-04 06:45:21', '2025-06-04 06:49:12', NULL),
(299, 69, 16, 1.00, 0.00, 'tari', 'costume', '1', 'canceled', '2025-06-04 06:45:21', '2025-06-05 06:23:33', NULL),
(300, 60, 39, 1.00, 0.00, 'tari', 'costume', '1', 'delivered', '2025-06-04 06:45:21', '2025-06-04 06:57:55', NULL),
(301, 69, 19, 1.00, 0.00, 'tari', 'costume', '1', 'delivered', '2025-06-04 06:45:21', '2025-06-04 06:57:55', NULL),
(302, 61, 42, 3.00, 0.00, 'laura', 'mascot', 'test', 'pending', '2025-06-04 09:10:31', '2025-06-05 06:23:19', NULL),
(303, 69, 14, 1.00, 0.00, 'laura', 'mascot', 'test', 'approved', '2025-06-04 09:11:15', '2025-06-17 08:44:23', NULL),
(304, 69, 17, 1.00, 0.00, 'laura', 'mascot', NULL, 'canceled', '2025-06-04 09:13:51', '2025-06-05 06:23:26', NULL),
(305, 58, 18, 0.00, 0.00, 'laura', 'mascot', NULL, 'delivered', '2025-06-04 09:13:52', '2025-06-04 09:15:14', NULL),
(306, 90, 43, 1.00, 0.00, 'laura', 'mascot', NULL, 'approved', '2025-06-04 09:13:52', '2025-06-05 04:13:18', NULL),
(307, 69, 43, 1.00, 0.00, 'logitech', 'management', 'csd', 'pending', '2025-06-05 06:12:10', '2025-06-05 06:12:10', NULL),
(308, 69, 43, 1.00, 0.00, 'logitech', 'management', 'jq', 'pending', '2025-06-05 06:13:00', '2025-06-05 06:13:00', NULL),
(309, 72, 41, 1.00, 0.00, 'logitech', 'management', 'tungtung', 'pending', '2025-06-05 06:25:55', '2025-06-05 06:25:55', NULL),
(310, 60, 25, 1.00, 0.00, 'logitech', 'management', 'tingting', 'pending', '2025-06-05 06:26:34', '2025-06-05 06:26:34', NULL),
(311, 69, 39, 3.00, 0.00, 'logitech', 'management', 'tangtang', 'pending', '2025-06-05 06:32:05', '2025-06-11 01:10:37', NULL),
(312, 84, 14, 1.00, 0.00, 'logitech', 'management', 'tongtong', 'pending', '2025-06-05 06:33:12', '2025-06-05 06:33:12', NULL),
(313, 77, 43, 1.00, 0.00, 'logitech', 'management', 'sahurr', 'pending', '2025-06-05 06:40:40', '2025-06-05 06:40:40', NULL),
(314, 69, 43, 1.00, 0.00, 'logitech', 'management', 'dmdmd', 'pending', '2025-06-05 06:43:56', '2025-06-05 06:45:00', NULL),
(315, 69, 43, 1.00, 0.00, 'logitech', 'management', 'vvg', 'pending', '2025-06-05 06:46:52', '2025-06-05 06:46:52', NULL),
(316, 72, 15, 1.00, 0.00, 'logitech', 'management', 'vzvz', 'pending', '2025-06-05 06:49:50', '2025-06-05 06:49:50', NULL),
(317, 72, 43, 1.00, 0.00, 'logitech', 'management', '1', 'pending', '2025-06-05 06:50:06', '2025-06-05 06:50:06', NULL),
(318, 60, 43, 1.00, 0.00, 'logitech', 'management', '2gg', 'approved', '2025-06-05 06:52:27', '2025-06-05 06:52:41', NULL),
(319, 69, 14, 1.00, 0.00, 'tari', 'costume', 'sss', 'pending', '2025-06-05 07:52:54', '2025-06-05 07:52:54', NULL),
(320, 60, 14, 1.00, 0.00, 'tari', 'costume', '1bbb', 'pending', '2025-06-05 07:54:10', '2025-06-05 07:54:10', NULL),
(321, 60, 14, 1.00, 0.00, 'tari', 'costume', 'ffyuu', 'pending', '2025-06-05 08:01:33', '2025-06-05 08:01:33', NULL),
(322, 69, 43, 1.00, 0.00, 'tari', 'costume', 'cdc', 'pending', '2025-06-05 08:05:18', '2025-06-05 08:05:18', NULL),
(323, 60, 43, 1.00, 0.00, 'logitech', 'management', 'fdfvbdf', 'pending', '2025-06-05 08:05:32', '2025-06-05 08:05:32', NULL),
(324, 60, 43, 1.00, 0.00, 'logitech', 'management', 'xc vd', 'pending', '2025-06-05 08:06:41', '2025-06-05 08:06:41', NULL),
(325, 69, 14, 1.00, 0.00, 'tari', 'costume', 'ffdc', 'pending', '2025-06-05 08:07:53', '2025-06-05 08:07:53', NULL),
(326, 69, 43, 1.00, 0.00, 'tari', 'costume', 'hfhdg', 'canceled', '2025-06-05 08:14:48', '2025-06-05 08:49:40', NULL),
(327, 72, 43, 1.00, 0.00, 'tari', 'costume', 'ssdd', 'delivered', '2025-06-05 08:16:27', '2025-06-05 08:20:48', NULL),
(328, 60, 15, 2.00, 0.00, 'tari', 'costume', 'sdcvsd', 'delivered', '2025-06-05 08:16:40', '2025-06-05 08:22:09', NULL),
(329, 69, 25, 1.00, 0.00, 'logitech', 'management', 'dsd', 'approved', '2025-06-05 08:39:51', '2025-06-05 08:49:36', NULL),
(330, 60, 14, 2.00, 0.00, 'logitech', 'management', 'deved', 'approved', '2025-06-05 08:39:52', '2025-06-17 04:52:50', NULL),
(331, 119, 46, 0.00, 0.00, 'logitech', 'management', 'etst', 'delivered', '2025-06-09 04:04:26', '2025-06-09 04:05:09', NULL),
(332, 69, 14, 1.00, 0.00, 'logitech', 'management', 'dds', 'approved', '2025-06-11 07:05:56', '2025-06-14 05:43:59', NULL),
(333, 60, 43, 1.00, 0.00, 'tari', 'costume', 'dds', 'pending', '2025-06-11 07:09:22', '2025-06-11 07:09:59', '2025-06-11 07:09:59'),
(334, 140, 50, 1.00, 0.00, 'dyla', 'logistic', 'Request By IT', 'pending', '2025-06-14 05:43:23', '2025-06-14 05:43:23', NULL),
(335, 146, 25, 4.00, 0.00, 'logitech', 'management', '2', 'delivered', '2025-06-16 09:36:16', '2025-06-17 04:50:28', NULL),
(336, 69, 43, 3.00, 0.00, 'logitech', 'management', 'ddff', 'delivered', '2025-06-16 09:37:57', '2025-06-17 04:50:28', NULL),
(337, 153, 53, 0.00, 0.00, 'logitech', 'management', NULL, 'delivered', '2025-06-17 06:10:48', '2025-06-17 06:52:01', NULL),
(338, 90, 53, 0.00, 0.00, 'logitech', 'management', 'aass', 'delivered', '2025-06-17 07:45:25', '2025-06-17 07:45:40', NULL),
(339, 146, 43, 1.00, 0.00, 'logitech', 'management', 'sxcsd', 'pending', '2025-06-17 08:07:28', '2025-06-17 08:07:28', NULL),
(340, 145, 53, 1.00, 0.00, 'logitech', 'management', '2', 'pending', '2025-06-17 08:11:45', '2025-06-17 08:11:45', NULL),
(341, 147, 45, 1.00, 0.00, 'logitech', 'management', 'rrt', 'approved', '2025-06-17 08:15:14', '2025-06-17 08:31:19', NULL),
(342, 69, 15, 1.00, 0.00, 'logitech', 'management', 'df', 'approved', '2025-06-17 08:15:43', '2025-06-17 08:31:27', NULL),
(343, 146, 43, 5.00, 0.00, 'logitech', 'management', '1', 'pending', '2025-06-17 09:45:05', '2025-06-17 09:45:05', NULL),
(344, 148, 50, 1.00, 0.00, 'logitech', 'management', 'sdd', 'pending', '2025-06-17 09:46:25', '2025-06-17 09:46:25', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `material_usages`
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
-- Dumping data for table `material_usages`
--

INSERT INTO `material_usages` (`id`, `inventory_id`, `project_id`, `used_quantity`, `created_at`, `updated_at`, `deleted_at`) VALUES
(17, 69, 25, 3.00, '2025-05-20 01:34:37', '2025-06-04 06:54:10', NULL),
(24, 84, 40, 2.00, '2025-05-22 08:54:05', '2025-06-09 06:07:46', NULL),
(26, 72, 16, -3.00, '2025-05-22 09:11:13', '2025-06-09 06:08:13', '2025-06-09 06:08:13'),
(27, 72, 15, 4.00, '2025-05-22 09:12:23', '2025-05-30 04:09:42', NULL),
(28, 72, 26, 5.00, '2025-05-22 09:38:05', '2025-05-22 09:38:05', NULL),
(29, 84, 25, 2.20, '2025-05-23 02:15:36', '2025-06-10 10:01:18', NULL),
(31, 59, 15, 6.50, '2025-05-23 02:58:57', '2025-06-11 10:04:57', '2025-06-11 10:04:57'),
(32, 59, 14, 0.00, '2025-05-23 03:03:03', '2025-06-11 10:04:52', '2025-06-11 10:04:52'),
(33, 61, 17, 1.50, '2025-05-23 08:02:39', '2025-05-23 08:49:29', NULL),
(34, 63, 31, 0.00, '2025-06-02 04:33:15', '2025-06-02 04:33:15', NULL),
(35, 62, 31, 0.50, '2025-06-03 01:58:06', '2025-06-03 01:58:06', NULL),
(36, 60, 15, 1.00, '2025-06-03 07:58:01', '2025-06-03 07:58:01', NULL),
(37, 69, 15, 2.00, '2025-06-03 07:58:21', '2025-06-03 07:58:21', NULL),
(38, 89, 25, 1.00, '2025-06-04 06:38:05', '2025-06-04 06:38:05', NULL),
(39, 84, 14, -1.00, '2025-06-04 06:49:12', '2025-06-09 06:08:07', '2025-06-09 06:08:07'),
(40, 62, 26, 2.00, '2025-06-04 06:54:51', '2025-06-04 06:56:21', NULL),
(41, 69, 19, 0.50, '2025-06-04 06:59:06', '2025-06-04 06:59:06', NULL),
(42, 58, 18, 0.60, '2025-06-04 09:15:14', '2025-06-04 09:16:11', NULL),
(43, 119, 46, 4.00, '2025-06-09 04:05:01', '2025-06-09 04:05:22', NULL),
(44, 75, 14, 0.50, '2025-06-09 06:07:39', '2025-06-09 06:07:39', NULL),
(45, 60, 14, 3.00, '2025-06-17 04:52:50', '2025-06-17 04:52:50', NULL),
(46, 153, 53, 1.00, '2025-06-17 06:11:26', '2025-06-17 06:52:01', NULL),
(47, 90, 53, 3.00, '2025-06-17 07:45:40', '2025-06-17 07:45:40', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
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
(31, '2025_05_19_091706_add_soft_deletes_to_all_tables', 22),
(32, '2025_05_24_110751_create_notifications_table', 23),
(33, '2025_05_28_090444_create_notifications_table', 24),
(34, '2025_05_28_114754_create_notifications_table', 25),
(35, '2025_06_04_095402_create_notifications_table', 26),
(36, '2025_06_05_093344_add_supplier_to_inventories_table', 27),
(37, '2025_06_05_164030_add_start_date_to_projects_table', 28),
(38, '2025_06_09_153310_add_remark_to_inventories_table', 29),
(39, '2025_06_14_124912_add_created_by_to_projects_table', 30),
(40, '2025_06_17_144806_add_processed_qty_to_material_requests_table', 31);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` bigint UNSIGNED NOT NULL,
  `created_by` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `qty` int NOT NULL,
  `img` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `deadline` date DEFAULT NULL,
  `department` enum('mascot','costume','mascot&costume','animatronic','plustoys','it','facility','bag') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `created_by`, `name`, `qty`, `img`, `start_date`, `deadline`, `department`, `created_at`, `updated_at`, `deleted_at`) VALUES
(14, '', 'Costume 1', 2, 'projects/zvqZPckVQpBA9twdjkDNl1YFj8V653lvmULjEXCP.png', NULL, '2025-05-31', 'costume', '2025-05-19 07:35:12', '2025-05-19 07:35:12', NULL),
(15, '', 'Costume 2', 5, 'projects/nco7izn5X5Jl26ldDwilHYVNmsyUhuRgQ1FzrFYl.png', NULL, '2025-06-07', 'costume', '2025-05-19 07:35:35', '2025-05-19 07:35:35', NULL),
(16, '', 'Costume 3', 3, 'projects/xyFSpqbI8iO76qg3BSDv0Bz76IIpnRiLyB9J9nnY.png', NULL, '2025-06-04', 'costume', '2025-05-19 07:36:07', '2025-05-19 07:36:07', NULL),
(17, '', 'Mascot 1', 1, 'projects/WwxLYysaGwcbB5vWnrmz35GdAy4nlY1aDlxCOkS2.png', NULL, '2025-06-27', 'mascot', '2025-05-19 07:36:29', '2025-05-19 07:36:29', NULL),
(18, '', 'Mascot 2', 1, 'projects/iC6qedTR8cRVPXPlYAOubatgBstx8gXuTTeIc2eo.png', NULL, '2025-07-05', 'mascot', '2025-05-19 07:36:41', '2025-05-19 07:38:18', NULL),
(19, '', 'Project 1', 1, 'projects/1wA9TSzcWaZzQc8ErdysqOJ5QxMo4zvPt8EcNHkY.png', NULL, '2025-07-25', 'mascot&costume', '2025-05-19 07:37:03', '2025-05-19 07:37:03', NULL),
(20, '', 'Project 2', 1, 'projects/0sT6jN7Jn7379mQVftEC7HK1d1B1eMFeLWuGHCXt.png', NULL, '2025-05-23', 'mascot&costume', '2025-05-19 07:37:22', '2025-05-19 07:37:22', NULL),
(21, '', 'Test', 5, 'projects/MzmRuUIHFFQVV1HRgM77EbYwWK3BCftJ7HMdoRm1.png', NULL, '2025-05-24', 'mascot', '2025-05-19 07:45:58', '2025-05-19 07:45:58', NULL),
(23, '', 'Test 3', 2, 'projects/iJdBsy9olbY2oyMq4lJnZskulz3RrdnM725oWiDY.png', NULL, '2025-05-24', 'mascot', '2025-05-19 07:46:43', '2025-05-19 07:46:43', NULL),
(24, '', 'Project 3', 2, 'projects/ejZvq2QTLy8z5bM6y7UEUeAIPMTWeEuUNshlhA63.jpg', NULL, '2025-05-23', 'costume', '2025-05-19 07:47:19', '2025-05-19 07:47:19', NULL),
(25, '', 'Bobo', 1, NULL, NULL, NULL, 'costume', '2025-05-19 07:50:24', '2025-05-19 07:50:24', NULL),
(26, '', 'Qucking', 12, 'projects/h2aE72Y5oPCkEnDCqAqhiMOriqWt0Ulh6uyXhO2X.png', NULL, '2025-05-31', 'costume', '2025-05-19 07:51:55', '2025-05-19 07:58:17', NULL),
(27, '', 'Mascot 7', 1, NULL, NULL, NULL, 'mascot', '2025-05-19 08:02:52', '2025-05-19 08:02:52', NULL),
(30, '', 'Project 90', 1, NULL, NULL, NULL, 'costume', '2025-05-19 08:48:39', '2025-06-09 06:09:17', '2025-06-09 06:09:17'),
(31, '', 'test99', 9, NULL, NULL, NULL, 'costume', '2025-05-19 09:07:05', '2025-05-19 09:07:05', NULL),
(39, '', 'final test pro', 2, 'projects/XQjjQvOXyF87BpwPi7CO0qwTaYcLb7NlwieKYIny.png', NULL, '2025-06-13', 'mascot&costume', '2025-05-20 06:41:02', '2025-05-20 06:41:02', NULL),
(40, '', 'test quick add', 4, NULL, NULL, NULL, 'mascot', '2025-05-22 08:25:43', '2025-05-23 09:32:19', NULL),
(41, '', 'testssspro', 1, 'projects/pYshVyIsaPeGBS7GuNmARVodUzrk9Th9slHcDZ7q.png', NULL, '2025-06-03', 'animatronic', '2025-06-02 02:36:03', '2025-06-02 02:36:03', NULL),
(42, '', 'Panda', 1, 'projects/tCOdO1QFaV0v53rMlQhswEAjQq6dtSwam7HxujRe.png', NULL, '2025-06-14', 'mascot', '2025-06-04 09:09:22', '2025-06-04 09:09:22', NULL),
(43, '', 'buaya', 1, NULL, '2025-06-05', '2025-06-07', 'mascot', '2025-06-04 09:12:54', '2025-06-05 09:54:46', NULL),
(44, '', 'ffty', 1, 'projects/iAefyFCaIJwEv8lvtX5MmgwhzfrU4DLqCXqIoRdz.png', NULL, '2025-06-07', 'mascot&costume', '2025-06-05 09:59:08', '2025-06-05 10:04:55', NULL),
(45, '', 'power ranger', 1, 'projects/b9WXhh2IS6geSZ1qOKqJKV0JcGoIFUDpoGqzztOf.png', '2025-06-01', '2025-06-05', 'mascot', '2025-06-05 10:09:45', '2025-06-05 10:09:45', NULL),
(46, '', 'Server', 23, 'projects/9xfqRx4eMrOvQ7oD1Wc0tMy27LQfNj58YFt8oBus.jpg', '2025-06-01', '2025-06-28', 'it', '2025-06-09 04:03:27', '2025-06-09 04:03:27', NULL),
(48, '', 'test1black', 2, 'projects/SXp2Bf8vuB6PlAouJhxsRuBSSeCngyDy02XVwHww.png', '2025-06-11', '2025-06-13', 'bag', '2025-06-10 10:10:20', '2025-06-10 10:10:49', '2025-06-10 10:10:49'),
(49, '', 'Wifi', 23, 'projects/A6KLjrLfGTh81LxC8Nplf74srabxgdhwXZIBSR6W.png', '2025-06-08', '2025-06-14', 'facility', '2025-06-14 05:42:01', '2025-06-14 05:42:01', NULL),
(50, '', 'Server Bawah', 2, NULL, NULL, NULL, 'facility', '2025-06-14 05:42:38', '2025-06-14 05:42:38', NULL),
(51, 'tari', 'sdsd', 4, NULL, NULL, NULL, 'costume', '2025-06-14 05:58:35', '2025-06-14 06:00:03', NULL),
(52, 'tari', 'gtyo', 2, NULL, NULL, NULL, 'costume', '2025-06-14 06:02:20', '2025-06-14 06:02:20', NULL),
(53, 'logitech', 'Joni', 1, 'projects/RxOGo4bPLrFoJZuqsR9iEmVfLjwlTsYzecuVvEbo.jpg', '2025-06-17', '2025-06-24', 'mascot', '2025-06-17 06:10:25', '2025-06-17 06:10:25', NULL),
(54, 'dyla', 'ssss', 3, NULL, NULL, NULL, 'animatronic', '2025-06-17 08:07:18', '2025-06-17 08:07:18', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `units`
--

CREATE TABLE `units` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `units`
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
(30, 'final test unit', '2025-05-20 06:37:41', '2025-05-20 06:37:41'),
(31, 'Kantong', '2025-06-04 06:34:17', '2025-06-04 06:34:17'),
(32, '-', '2025-06-07 05:48:09', '2025-06-07 05:48:09'),
(33, 'gh', '2025-06-07 05:50:39', '2025-06-07 05:50:39'),
(34, 'kl', '2025-06-07 06:03:47', '2025-06-07 06:03:47'),
(35, 'km ', '2025-06-09 03:20:19', '2025-06-09 03:20:19'),
(36, 'Gulung', '2025-06-09 07:58:45', '2025-06-09 07:58:45'),
(37, 'Pasang', '2025-06-16 09:19:10', '2025-06-16 09:19:10');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('super_admin','admin_logistic','admin_mascot','admin_costume','admin_finance','admin_animatronic','general') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `remember_token`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'logitech', '$2y$10$d92eVtlqhO1zea7Ofb/jKuR6s.LKe7GYqVQkAMTEDzm.sk34iBC5C', 'super_admin', '1OQDDlI8BGatZbPEiVocnwbVkNmc8kXc7e7Vv3qCp0uz6FExCZiJ1wZNMjTh', '2025-05-05 23:37:44', '2025-06-02 04:32:44', NULL),
(2, 'laura', '$2y$10$agrAQMX7Zu08uOYtHcQQYeWTO1kge9xJWT.mIFMBX/XhItIyToAIy', 'admin_mascot', NULL, '2025-05-06 00:07:01', '2025-06-02 04:31:49', NULL),
(3, 'tari', '$2y$10$Q2V3kGCkazSCAtjXQ3No4./RayNoZAMEVfNdmFr//vXHBqP87xrs6', 'admin_costume', NULL, '2025-05-06 00:07:27', '2025-06-02 04:31:57', NULL),
(4, 'dyla', '$2y$10$IbH1.hfTsurL05wNwT6VTepZnmQn1cyxoa.UwIjIN739.0hV11h.a', 'admin_logistic', NULL, '2025-05-06 00:07:43', '2025-06-02 04:32:05', NULL),
(5, 'lesta', '$2y$10$LijMxv8aRqT7/e0sX5me/.b/BTvBoKmpZdPEfw/OmGzQfavpUxYxu', 'admin_finance', NULL, '2025-05-06 00:08:01', '2025-06-02 04:32:12', NULL),
(6, 'test', '$2y$10$cWkKf3CZcradOc.3Y.gYb.5b5FMrlbzvDxeSbjROPjstBd8Tq/aiu', 'general', NULL, '2025-06-09 06:53:37', '2025-06-09 07:10:40', '2025-06-09 07:10:40'),
(7, 'test2', '$2y$10$EIGn8Hj1p8kK8iuKBhC7u.6tYMgI69vY0DSsjNWPwA9KbEEb.4X7i', 'admin_animatronic', NULL, '2025-06-09 06:53:56', '2025-06-09 06:54:35', '2025-06-09 06:54:35'),
(8, 'test112', '$2y$10$yHJX7UNjaUBgoWVBxeHQEOGPmwbUqIykoAAuJOczfxhkgETHMcBV6', 'admin_mascot', NULL, '2025-06-09 07:11:01', '2025-06-09 10:01:52', '2025-06-09 10:01:52'),
(10, 'test1123', '$2y$10$FMLL25z9ZauBaLIRKoUNFelmxED4oTh5326SaG7EIwzn7WcGmw/QC', 'admin_logistic', NULL, '2025-06-09 10:01:28', '2025-06-09 10:01:50', '2025-06-09 10:01:50');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_name_unique` (`name`);

--
-- Indexes for table `currencies`
--
ALTER TABLE `currencies`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `currencies_name_unique` (`name`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `goods_in`
--
ALTER TABLE `goods_in`
  ADD PRIMARY KEY (`id`),
  ADD KEY `goods_in_goods_out_id_foreign` (`goods_out_id`),
  ADD KEY `goods_in_inventory_id_foreign` (`inventory_id`),
  ADD KEY `goods_in_project_id_foreign` (`project_id`);

--
-- Indexes for table `goods_out`
--
ALTER TABLE `goods_out`
  ADD PRIMARY KEY (`id`),
  ADD KEY `goods_out_material_request_id_foreign` (`material_request_id`),
  ADD KEY `goods_out_inventory_id_foreign` (`inventory_id`),
  ADD KEY `goods_out_project_id_foreign` (`project_id`);

--
-- Indexes for table `inventories`
--
ALTER TABLE `inventories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inventories_currency_id_foreign` (`currency_id`),
  ADD KEY `inventories_category_id_foreign` (`category_id`);

--
-- Indexes for table `inventory_transactions`
--
ALTER TABLE `inventory_transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `material_requests`
--
ALTER TABLE `material_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `material_requests_inventory_id_foreign` (`inventory_id`),
  ADD KEY `material_requests_project_id_foreign` (`project_id`);

--
-- Indexes for table `material_usages`
--
ALTER TABLE `material_usages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `material_usages_inventory_id_foreign` (`inventory_id`),
  ADD KEY `material_usages_project_id_foreign` (`project_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `units`
--
ALTER TABLE `units`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `units_name_unique` (`name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_username_unique` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `currencies`
--
ALTER TABLE `currencies`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `goods_in`
--
ALTER TABLE `goods_in`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `goods_out`
--
ALTER TABLE `goods_out`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT for table `inventories`
--
ALTER TABLE `inventories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=158;

--
-- AUTO_INCREMENT for table `inventory_transactions`
--
ALTER TABLE `inventory_transactions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `material_requests`
--
ALTER TABLE `material_requests`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=345;

--
-- AUTO_INCREMENT for table `material_usages`
--
ALTER TABLE `material_usages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `units`
--
ALTER TABLE `units`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `goods_in`
--
ALTER TABLE `goods_in`
  ADD CONSTRAINT `goods_in_goods_out_id_foreign` FOREIGN KEY (`goods_out_id`) REFERENCES `goods_out` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `goods_in_inventory_id_foreign` FOREIGN KEY (`inventory_id`) REFERENCES `inventories` (`id`),
  ADD CONSTRAINT `goods_in_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`);

--
-- Constraints for table `goods_out`
--
ALTER TABLE `goods_out`
  ADD CONSTRAINT `goods_out_inventory_id_foreign` FOREIGN KEY (`inventory_id`) REFERENCES `inventories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `goods_out_material_request_id_foreign` FOREIGN KEY (`material_request_id`) REFERENCES `material_requests` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `goods_out_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `inventories`
--
ALTER TABLE `inventories`
  ADD CONSTRAINT `inventories_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `inventories_currency_id_foreign` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `material_requests`
--
ALTER TABLE `material_requests`
  ADD CONSTRAINT `material_requests_inventory_id_foreign` FOREIGN KEY (`inventory_id`) REFERENCES `inventories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `material_requests_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `material_usages`
--
ALTER TABLE `material_usages`
  ADD CONSTRAINT `material_usages_inventory_id_foreign` FOREIGN KEY (`inventory_id`) REFERENCES `inventories` (`id`),
  ADD CONSTRAINT `material_usages_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
