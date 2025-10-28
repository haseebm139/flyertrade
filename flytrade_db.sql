-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 22, 2025 at 05:11 PM
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
-- Database: `flytrade_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `attachments`
--

CREATE TABLE `attachments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `message_id` bigint(20) UNSIGNED NOT NULL,
  `url` varchar(2048) NOT NULL,
  `mime` varchar(191) DEFAULT NULL,
  `size` bigint(20) UNSIGNED DEFAULT NULL,
  `width` int(10) UNSIGNED DEFAULT NULL,
  `height` int(10) UNSIGNED DEFAULT NULL,
  `duration_ms` int(10) UNSIGNED DEFAULT NULL,
  `meta` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`meta`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `booking_ref` varchar(255) NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `provider_id` bigint(20) UNSIGNED NOT NULL,
  `service_id` bigint(20) UNSIGNED NOT NULL,
  `provider_service_id` bigint(20) UNSIGNED NOT NULL,
  `booking_address` varchar(255) NOT NULL,
  `booking_description` text DEFAULT NULL,
  `status` enum('awaiting_provider','confirmed','in_progress','rejected','completed','cancelled','refunded','reschedule_pending_provider','reschedule_pending_customer') NOT NULL DEFAULT 'awaiting_provider',
  `booking_type` enum('custom','hourly') NOT NULL DEFAULT 'hourly',
  `booking_working_minutes` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `total_price` decimal(10,2) NOT NULL,
  `service_charges` decimal(10,2) NOT NULL DEFAULT 0.00,
  `stripe_payment_intent_id` varchar(255) DEFAULT NULL,
  `stripe_payment_method_id` varchar(255) DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `confirmed_at` timestamp NULL DEFAULT NULL,
  `rejected_at` timestamp NULL DEFAULT NULL,
  `started_at` timestamp NULL DEFAULT NULL,
  `cancelled_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `refunded_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `booking_days`
--

CREATE TABLE `booking_days` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `booking_id` bigint(20) UNSIGNED NOT NULL,
  `booking_date` date NOT NULL,
  `booking_start_time` time NOT NULL,
  `booking_end_time` time NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `booking_messages`
--

CREATE TABLE `booking_messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `booking_reschedules`
--

CREATE TABLE `booking_reschedules` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `booking_id` bigint(20) UNSIGNED NOT NULL,
  `requested_by` bigint(20) UNSIGNED NOT NULL,
  `old_slots` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`old_slots`)),
  `new_slots` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`new_slots`)),
  `status` enum('pending','accepted','rejected') NOT NULL DEFAULT 'pending',
  `responded_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `booking_slots`
--

CREATE TABLE `booking_slots` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `booking_id` bigint(20) UNSIGNED NOT NULL,
  `service_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `duration_minutes` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bookmarks`
--

CREATE TABLE `bookmarks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `provider_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `conversations`
--

CREATE TABLE `conversations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_by_id` bigint(20) UNSIGNED NOT NULL,
  `type` enum('direct','admin') NOT NULL DEFAULT 'direct',
  `last_message_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `conversation_participants`
--

CREATE TABLE `conversation_participants` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `conversation_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `role` enum('customer','provider','admin') NOT NULL,
  `joined_at` timestamp NULL DEFAULT NULL,
  `muted_until` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `device_tokens`
--

CREATE TABLE `device_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `disputes`
--

CREATE TABLE `disputes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

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
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `conversation_id` bigint(20) UNSIGNED NOT NULL,
  `sender_id` bigint(20) UNSIGNED NOT NULL,
  `kind` enum('text','attachment','system') NOT NULL DEFAULT 'text',
  `body` text DEFAULT NULL,
  `meta` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`meta`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `message_reads`
--

CREATE TABLE `message_reads` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `message_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `read_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(47, '0001_01_01_000000_create_users_table', 1),
(48, '0001_01_01_000001_create_cache_table', 1),
(49, '0001_01_01_000002_create_jobs_table', 1),
(50, '2025_08_11_205721_create_personal_access_tokens_table', 1),
(51, '2025_08_11_230409_create_permission_tables', 1),
(52, '2025_08_11_234249_create_services_table', 1),
(53, '2025_08_13_235317_create_booking_messages_table', 1),
(54, '2025_08_13_235324_create_reviews_table', 1),
(55, '2025_08_13_235330_create_payouts_table', 1),
(56, '2025_08_13_235335_create_disputes_table', 1),
(57, '2025_08_13_235339_create_provider_profiles_table', 1),
(58, '2025_08_13_235343_create_categories_table', 1),
(59, '2025_08_13_235358_create_transactions_table', 1),
(60, '2025_08_13_235401_create_device_tokens_table', 1),
(61, '2025_08_15_180748_create_provider_services_table', 1),
(62, '2025_08_15_180817_create_provider_certificates_table', 1),
(63, '2025_08_15_181326_create_provider_service_media_table', 1),
(64, '2025_08_21_191624_create_provider_working_hours_table', 1),
(65, '2025_08_29_194915_create_bookmarks_table', 1),
(66, '2025_09_03_230328_create_bookings_table', 1),
(67, '2025_09_03_230330_create_booking_days_table', 1),
(68, '2025_09_04_185150_create_booking_slots_table', 1),
(69, '2025_09_04_235503_create_booking_reschedules_table', 1),
(70, '2025_09_23_100001_create_conversations_table', 2),
(71, '2025_09_23_100002_create_conversation_participants_table', 2),
(72, '2025_09_23_100003_create_messages_table', 2),
(73, '2025_09_23_100004_create_attachments_table', 2),
(74, '2025_09_23_100005_create_message_reads_table', 2),
(75, '2025_09_23_100006_create_offers_table', 2),
(76, '2025_09_23_100007_create_offer_revisions_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 2),
(1, 'App\\Models\\User', 24),
(1, 'App\\Models\\User', 25),
(2, 'App\\Models\\User', 42),
(3, 'App\\Models\\User', 41);

-- --------------------------------------------------------

--
-- Table structure for table `offers`
--

CREATE TABLE `offers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `conversation_id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `provider_id` bigint(20) UNSIGNED NOT NULL,
  `service_type` varchar(191) NOT NULL,
  `time_from` timestamp NULL DEFAULT NULL,
  `time_to` timestamp NULL DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` enum('pending','countered','bargained','accepted','declined','finalized') NOT NULL DEFAULT 'pending',
  `current_revision_id` bigint(20) UNSIGNED DEFAULT NULL,
  `responded_at` timestamp NULL DEFAULT NULL,
  `finalized_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `offer_revisions`
--

CREATE TABLE `offer_revisions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `offer_id` bigint(20) UNSIGNED NOT NULL,
  `by_user_id` bigint(20) UNSIGNED NOT NULL,
  `cost_items` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`cost_items`)),
  `materials` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`materials`)),
  `flat_fee` decimal(10,2) NOT NULL DEFAULT 0.00,
  `currency` varchar(8) NOT NULL DEFAULT 'USD',
  `subtotal` decimal(10,2) NOT NULL DEFAULT 0.00,
  `tax` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payouts`
--

CREATE TABLE `payouts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'read user management', 'web', '2025-09-11 16:43:05', '2025-09-11 16:43:05'),
(2, 'write user management', 'web', '2025-09-11 16:43:05', '2025-09-11 16:43:05'),
(3, 'create user management', 'web', '2025-09-11 16:43:05', '2025-09-11 16:43:05'),
(4, 'delete user management', 'web', '2025-09-11 16:43:05', '2025-09-11 16:43:05'),
(5, 'read content management', 'web', '2025-09-11 16:43:05', '2025-09-11 16:43:05'),
(6, 'write content management', 'web', '2025-09-11 16:43:05', '2025-09-11 16:43:05'),
(7, 'create content management', 'web', '2025-09-11 16:43:05', '2025-09-11 16:43:05'),
(8, 'delete content management', 'web', '2025-09-11 16:43:05', '2025-09-11 16:43:05'),
(9, 'read financial management', 'web', '2025-09-11 16:43:05', '2025-09-11 16:43:05'),
(10, 'write financial management', 'web', '2025-09-11 16:43:05', '2025-09-11 16:43:05'),
(11, 'create financial management', 'web', '2025-09-11 16:43:05', '2025-09-11 16:43:05'),
(12, 'delete financial management', 'web', '2025-09-11 16:43:05', '2025-09-11 16:43:05'),
(13, 'read reporting', 'web', '2025-09-11 16:43:05', '2025-09-11 16:43:05'),
(14, 'write reporting', 'web', '2025-09-11 16:43:05', '2025-09-11 16:43:05'),
(15, 'create reporting', 'web', '2025-09-11 16:43:05', '2025-09-11 16:43:05'),
(16, 'delete reporting', 'web', '2025-09-11 16:43:05', '2025-09-11 16:43:05'),
(17, 'read view products', 'web', '2025-09-11 16:43:05', '2025-09-11 16:43:05'),
(18, 'write view products', 'web', '2025-09-11 16:43:05', '2025-09-11 16:43:05'),
(19, 'create view products', 'web', '2025-09-11 16:43:05', '2025-09-11 16:43:05'),
(20, 'delete view products', 'web', '2025-09-11 16:43:05', '2025-09-11 16:43:05'),
(21, 'read place orders', 'web', '2025-09-11 16:43:05', '2025-09-11 16:43:05'),
(22, 'write place orders', 'web', '2025-09-11 16:43:05', '2025-09-11 16:43:05'),
(23, 'create place orders', 'web', '2025-09-11 16:43:05', '2025-09-11 16:43:05'),
(24, 'delete place orders', 'web', '2025-09-11 16:43:05', '2025-09-11 16:43:05'),
(25, 'read manage profile', 'web', '2025-09-11 16:43:05', '2025-09-11 16:43:05'),
(26, 'write manage profile', 'web', '2025-09-11 16:43:05', '2025-09-11 16:43:05'),
(27, 'create manage profile', 'web', '2025-09-11 16:43:05', '2025-09-11 16:43:05'),
(28, 'delete manage profile', 'web', '2025-09-11 16:43:05', '2025-09-11 16:43:05'),
(29, 'read manage services', 'web', '2025-09-11 16:43:05', '2025-09-11 16:43:05'),
(30, 'write manage services', 'web', '2025-09-11 16:43:05', '2025-09-11 16:43:05'),
(31, 'create manage services', 'web', '2025-09-11 16:43:05', '2025-09-11 16:43:05'),
(32, 'delete manage services', 'web', '2025-09-11 16:43:05', '2025-09-11 16:43:05'),
(33, 'read view bookings', 'web', '2025-09-11 16:43:05', '2025-09-11 16:43:05'),
(34, 'write view bookings', 'web', '2025-09-11 16:43:05', '2025-09-11 16:43:05'),
(35, 'create view bookings', 'web', '2025-09-11 16:43:05', '2025-09-11 16:43:05'),
(36, 'delete view bookings', 'web', '2025-09-11 16:43:05', '2025-09-11 16:43:05'),
(37, 'view_dashboard', 'web', '2025-10-12 08:21:35', '2025-10-12 08:21:35'),
(38, 'manage_dashboard', 'web', '2025-10-12 08:21:35', '2025-10-12 08:21:35'),
(39, 'view_users', 'web', '2025-10-12 08:21:35', '2025-10-12 08:21:35'),
(40, 'create_users', 'web', '2025-10-12 08:21:35', '2025-10-12 08:21:35'),
(41, 'edit_users', 'web', '2025-10-12 08:21:35', '2025-10-12 08:21:35'),
(42, 'delete_users', 'web', '2025-10-12 08:21:35', '2025-10-12 08:21:35'),
(43, 'manage_user_roles', 'web', '2025-10-12 08:21:35', '2025-10-12 08:21:35'),
(44, 'view_bookings', 'web', '2025-10-12 08:21:35', '2025-10-12 08:21:35'),
(45, 'create_bookings', 'web', '2025-10-12 08:21:35', '2025-10-12 08:21:35'),
(46, 'edit_bookings', 'web', '2025-10-12 08:21:35', '2025-10-12 08:21:35'),
(47, 'delete_bookings', 'web', '2025-10-12 08:21:35', '2025-10-12 08:21:35'),
(48, 'manage_booking_status', 'web', '2025-10-12 08:21:35', '2025-10-12 08:21:35'),
(49, 'view_transactions', 'web', '2025-10-12 08:21:35', '2025-10-12 08:21:35'),
(50, 'create_transactions', 'web', '2025-10-12 08:21:35', '2025-10-12 08:21:35'),
(51, 'edit_transactions', 'web', '2025-10-12 08:21:35', '2025-10-12 08:21:35'),
(52, 'delete_transactions', 'web', '2025-10-12 08:21:35', '2025-10-12 08:21:35'),
(53, 'manage_payments', 'web', '2025-10-12 08:21:35', '2025-10-12 08:21:35'),
(54, 'view_reports', 'web', '2025-10-12 08:21:35', '2025-10-12 08:21:35'),
(55, 'generate_reports', 'web', '2025-10-12 08:21:35', '2025-10-12 08:21:35'),
(56, 'export_reports', 'web', '2025-10-12 08:21:35', '2025-10-12 08:21:35'),
(57, 'view_analytics', 'web', '2025-10-12 08:21:35', '2025-10-12 08:21:35'),
(58, 'view_roles', 'web', '2025-10-12 08:21:35', '2025-10-12 08:21:35'),
(59, 'create_roles', 'web', '2025-10-12 08:21:35', '2025-10-12 08:21:35'),
(60, 'edit_roles', 'web', '2025-10-12 08:21:35', '2025-10-12 08:21:35'),
(61, 'delete_roles', 'web', '2025-10-12 08:21:35', '2025-10-12 08:21:35'),
(62, 'assign_permissions', 'web', '2025-10-12 08:21:35', '2025-10-12 08:21:35'),
(63, 'manage_permissions', 'web', '2025-10-12 08:21:35', '2025-10-12 08:21:35');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` text NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\User', 3, 'auth_token', 'd04d50a83aa316c90ae3807bcbfc9721369da375a512ed7133bc052506739c3c', '[\"*\"]', '2025-09-13 23:30:42', NULL, '2025-09-13 23:29:21', '2025-09-13 23:30:42'),
(2, 'App\\Models\\User', 4, 'auth_token', 'f0dfdc4bf4958e2a8001cc818ffa1f8ebe139bf53b9e469085397314eed8fec9', '[\"*\"]', '2025-09-13 23:42:06', NULL, '2025-09-13 23:40:39', '2025-09-13 23:42:06');

-- --------------------------------------------------------

--
-- Table structure for table `provider_certificates`
--

CREATE TABLE `provider_certificates` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `provider_profile_id` bigint(20) UNSIGNED NOT NULL,
  `provider_service_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `provider_profiles`
--

CREATE TABLE `provider_profiles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `about_me` text DEFAULT NULL,
  `profile_photo` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `zip` varchar(255) DEFAULT NULL,
  `office_address` varchar(255) DEFAULT NULL,
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL,
  `id_photo` varchar(255) DEFAULT NULL,
  `passport` varchar(255) DEFAULT NULL,
  `work_permit` varchar(255) DEFAULT NULL,
  `id_photo_status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `passport_status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `work_permit_status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `availability_status` enum('fully_booked','available','not_available') NOT NULL DEFAULT 'available',
  `is_completed` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `provider_services`
--

CREATE TABLE `provider_services` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `service_id` bigint(20) UNSIGNED NOT NULL,
  `provider_profile_id` bigint(20) UNSIGNED NOT NULL,
  `is_primary` tinyint(1) NOT NULL DEFAULT 0,
  `show_certificate` tinyint(1) NOT NULL DEFAULT 1,
  `title` varchar(255) DEFAULT NULL,
  `about` longtext DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `staff_count` int(10) UNSIGNED DEFAULT NULL,
  `rate_min` decimal(10,2) DEFAULT NULL,
  `rate_max` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `provider_service_media`
--

CREATE TABLE `provider_service_media` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `provider_profile_id` bigint(20) UNSIGNED NOT NULL,
  `provider_service_id` bigint(20) UNSIGNED NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `type` enum('photo','video') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `provider_working_hours`
--

CREATE TABLE `provider_working_hours` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `provider_profile_id` bigint(20) UNSIGNED NOT NULL,
  `day` enum('sunday','monday','tuesday','wednesday','thursday','friday','saturday') NOT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'web', '2025-09-11 16:43:05', '2025-09-11 16:43:05'),
(2, 'customer', 'web', '2025-09-11 16:43:05', '2025-09-11 16:43:05'),
(3, 'provider', 'web', '2025-09-11 16:43:05', '2025-09-11 16:43:05'),
(14, 'asss', 'web', '2025-10-18 23:04:55', '2025-10-18 23:04:55');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(1, 2),
(2, 1),
(2, 2),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(11, 1),
(12, 1),
(13, 1),
(13, 14),
(14, 1),
(15, 1),
(16, 1),
(17, 2),
(18, 2),
(19, 2),
(20, 2),
(21, 2),
(22, 2),
(23, 2),
(24, 2),
(25, 2),
(25, 3),
(26, 2),
(26, 3),
(27, 2),
(27, 3),
(28, 2),
(28, 3),
(29, 3),
(30, 3),
(31, 3),
(32, 3),
(33, 3),
(34, 3),
(35, 3),
(36, 3),
(37, 1),
(38, 1),
(38, 2),
(39, 1),
(40, 1),
(41, 1),
(43, 1),
(58, 1),
(59, 1);

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `name`, `slug`, `icon`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Cleaning', 'cleaning', 'assets/images/services/cleaning.png', 'General residential cleaning services, including deep cleaning, bathroom cleaning, kitchen cleaning, and post-renovation cleanups.', 1, NULL, NULL),
(2, 'Carpenter', 'carpenter', 'assets/images/services/carpenter.png', 'Woodwork services including furniture repairs, cabinet making, door and window installations, and custom carpentry projects.', 1, NULL, NULL),
(3, 'Laundry', 'laundry', 'assets/images/services/laundry.png', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim a', 1, NULL, NULL),
(4, 'Painting', 'painting', 'assets/images/services/painting.png', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim a', 1, NULL, NULL),
(5, 'Logistics', 'logistics', 'assets/images/services/logistics.png', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim a', 1, NULL, NULL),
(6, 'Cooking', 'cooking', 'assets/images/services/cooking.png', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim a', 1, NULL, NULL),
(7, 'Electric Work', 'electric-work', 'assets/images/services/electric_work.png', 'Electrical installation, repairs, wiring, lighting fixtures, appliance setup, and troubleshooting electrical faults', 1, NULL, NULL),
(8, 'Plumbing', 'plumbing', 'assets/images/services/plumbing.png', 'Installation, maintenance, and repair of plumbing systems, including leak repairs, pipe fitting, unclogging drains, and bathroom/kitc..', 1, NULL, NULL),
(9, 'Beauty', 'beauty', 'assets/images/services/beauty.png', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim a', 1, NULL, NULL),
(10, 'Technician', 'technician', 'assets/images/services/technician.png', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim a', 1, NULL, NULL),
(11, 'AC repair', 'ac-repair', 'assets/images/services/ac_repair.png', 'Air conditioning unit installation, repairs, cleaning, gas refilling, and regular servicing to improve cooling efficiency.', 1, NULL, NULL),
(12, 'Baking', 'baking', 'assets/images/services/baking.png', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim a', 1, NULL, NULL),
(13, 'Gardener', 'gardener', 'assets/images/services/gardener.png', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim a', 1, NULL, NULL),
(14, 'Man\'s saloon', 'mans-saloon', 'assets/images/services/man_saloon.png', 'Electrical installation, repairs, wiring, lighting fixtures, appliance setup, and troubleshooting electrical faults', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT 'assets/images/avatar/default.png',
  `phone` varchar(255) DEFAULT NULL,
  `role_id` enum('customer','provider','admin','multi') NOT NULL DEFAULT 'customer',
  `user_type` enum('customer','provider','admin','multi') NOT NULL DEFAULT 'customer',
  `is_verified` enum('pending','verified','declined') NOT NULL DEFAULT 'pending',
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `country` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `zip` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL,
  `google_id` varchar(255) DEFAULT NULL,
  `facebook_id` varchar(255) DEFAULT NULL,
  `apple_id` varchar(255) DEFAULT NULL,
  `otp` int(11) DEFAULT NULL,
  `is_guest` tinyint(1) NOT NULL DEFAULT 0,
  `fcm_token` varchar(255) DEFAULT NULL,
  `is_booking_notification` tinyint(1) NOT NULL DEFAULT 1,
  `is_promo_option_notification` tinyint(1) NOT NULL DEFAULT 0,
  `referral_code` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `avatar`, `phone`, `role_id`, `user_type`, `is_verified`, `status`, `country`, `city`, `state`, `zip`, `address`, `latitude`, `longitude`, `google_id`, `facebook_id`, `apple_id`, `otp`, `is_guest`, `fcm_token`, `is_booking_notification`, `is_promo_option_notification`, `referral_code`, `remember_token`, `created_at`, `updated_at`) VALUES
(2, 'Super Admin', 'demo@demo.com', '2025-09-11 16:43:06', '$2y$12$WhXECyraLH3Cv/b32oa7YOgRZcnurDiqTs4CvHfndM1qulscG9.S2', 'assets/images/avatar/default.png', '1-682-309-9895', 'admin', 'admin', 'pending', 'active', 'Brazil', 'Keelinghaven', 'Michigan', '32071', '4763 Kassandra Overpass', 51.2338650, 139.9455630, NULL, NULL, NULL, NULL, 0, NULL, 1, 0, '0Y8C2R', 'pzAE9NBuvK', '2025-09-11 16:43:06', '2025-09-11 16:43:06'),
(24, 'Super Admin', 'admin@admin.com', '2025-10-14 14:56:47', '$2y$12$Cju5X6NaaYkfEMHwQIV85eCzMCLQD05T0LILIi72NC7Tc.q9L9Z3y', 'assets/images/avatar/default.png', '+1-820-489-2749', 'admin', 'admin', 'pending', 'active', 'Kuwait', 'Port Sarina', 'New Hampshire', '95889-6600', '8581 Torp Place', -7.8053170, 178.1088490, NULL, NULL, NULL, NULL, 0, NULL, 1, 0, 'TCUED1', '0yz9Z9mLh5up27KysN9rqO2nsl3aqA3qgsDjXpODgO8K3rxTCNSNnqFbFdWg', '2025-10-14 14:56:48', '2025-10-14 14:56:48'),
(25, 'Super Admin', 'demo@demo.com', '2025-10-14 14:56:48', '$2y$12$rGD6dT8bjFpFTeraRiYV9uD/NX7IeYXvKUp3TMB73lHYs/Dr0nrgy', 'assets/images/avatar/default.png', '+1-831-276-1689', 'admin', 'admin', 'pending', 'active', 'Cayman Islands', 'Kolbyton', 'Washington', '94369-7700', '18660 Kuhlman Stravenue Suite 134', -2.5431690, 125.7188600, NULL, NULL, NULL, NULL, 0, NULL, 1, 0, 'GSYM90', 'htN7accSFz', '2025-10-14 14:56:48', '2025-10-14 14:56:48'),
(41, 'Haseeb Memon Memon', 'haseebm139@gmail.com', NULL, NULL, 'assets/images/avatar/default.png', '03322671412', 'provider', 'provider', 'pending', 'active', NULL, NULL, NULL, NULL, 'Street 12, Q A-15, Sindh University Colony Jamshoro', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 1, 0, 'PSZ80H', NULL, '2025-10-18 16:14:35', '2025-10-18 16:14:35'),
(42, 'sairjam', 'admin@gmail.com', NULL, NULL, 'assets/images/avatar/default.png', '03163431018', 'customer', 'customer', 'pending', 'active', NULL, NULL, NULL, NULL, 'Street 12, Q A-15, Sindh University Colony Jamshoro', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 1, 0, 'XACLDP', NULL, '2025-10-18 16:17:01', '2025-10-18 16:17:01');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attachments`
--
ALTER TABLE `attachments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `attachments_message_id_index` (`message_id`),
  ADD KEY `attachments_mime_index` (`mime`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `bookings_booking_ref_unique` (`booking_ref`),
  ADD KEY `bookings_customer_id_foreign` (`customer_id`),
  ADD KEY `bookings_service_id_foreign` (`service_id`),
  ADD KEY `bookings_provider_service_id_foreign` (`provider_service_id`),
  ADD KEY `bookings_provider_id_status_index` (`provider_id`,`status`),
  ADD KEY `bookings_stripe_payment_intent_id_index` (`stripe_payment_intent_id`);

--
-- Indexes for table `booking_days`
--
ALTER TABLE `booking_days`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_days_booking_id_foreign` (`booking_id`);

--
-- Indexes for table `booking_messages`
--
ALTER TABLE `booking_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `booking_reschedules`
--
ALTER TABLE `booking_reschedules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_reschedules_booking_id_foreign` (`booking_id`),
  ADD KEY `booking_reschedules_requested_by_foreign` (`requested_by`);

--
-- Indexes for table `booking_slots`
--
ALTER TABLE `booking_slots`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_slots_booking_id_foreign` (`booking_id`),
  ADD KEY `booking_slots_service_date_start_time_end_time_index` (`service_date`,`start_time`,`end_time`);

--
-- Indexes for table `bookmarks`
--
ALTER TABLE `bookmarks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `bookmarks_user_id_provider_id_unique` (`user_id`,`provider_id`),
  ADD KEY `bookmarks_provider_id_foreign` (`provider_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `conversations`
--
ALTER TABLE `conversations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `conversations_created_by_id_index` (`created_by_id`),
  ADD KEY `conversations_type_index` (`type`),
  ADD KEY `conversations_last_message_at_index` (`last_message_at`);

--
-- Indexes for table `conversation_participants`
--
ALTER TABLE `conversation_participants`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `conversation_participants_conversation_id_user_id_unique` (`conversation_id`,`user_id`),
  ADD KEY `conversation_participants_conversation_id_index` (`conversation_id`),
  ADD KEY `conversation_participants_user_id_index` (`user_id`),
  ADD KEY `conversation_participants_role_index` (`role`);

--
-- Indexes for table `device_tokens`
--
ALTER TABLE `device_tokens`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `disputes`
--
ALTER TABLE `disputes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

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
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `messages_conversation_id_created_at_index` (`conversation_id`,`created_at`),
  ADD KEY `messages_conversation_id_index` (`conversation_id`),
  ADD KEY `messages_sender_id_index` (`sender_id`),
  ADD KEY `messages_kind_index` (`kind`);

--
-- Indexes for table `message_reads`
--
ALTER TABLE `message_reads`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `message_reads_message_id_user_id_unique` (`message_id`,`user_id`),
  ADD KEY `message_reads_message_id_index` (`message_id`),
  ADD KEY `message_reads_user_id_index` (`user_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `offers`
--
ALTER TABLE `offers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `offers_conversation_id_index` (`conversation_id`),
  ADD KEY `offers_customer_id_index` (`customer_id`),
  ADD KEY `offers_provider_id_index` (`provider_id`),
  ADD KEY `offers_service_type_index` (`service_type`),
  ADD KEY `offers_time_from_index` (`time_from`),
  ADD KEY `offers_time_to_index` (`time_to`),
  ADD KEY `offers_status_index` (`status`),
  ADD KEY `offers_current_revision_id_index` (`current_revision_id`);

--
-- Indexes for table `offer_revisions`
--
ALTER TABLE `offer_revisions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `offer_revisions_offer_id_index` (`offer_id`),
  ADD KEY `offer_revisions_by_user_id_index` (`by_user_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `payouts`
--
ALTER TABLE `payouts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  ADD KEY `personal_access_tokens_expires_at_index` (`expires_at`);

--
-- Indexes for table `provider_certificates`
--
ALTER TABLE `provider_certificates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `provider_certificates_user_id_foreign` (`user_id`),
  ADD KEY `provider_certificates_provider_profile_id_foreign` (`provider_profile_id`),
  ADD KEY `provider_certificates_provider_service_id_foreign` (`provider_service_id`);

--
-- Indexes for table `provider_profiles`
--
ALTER TABLE `provider_profiles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `provider_profiles_user_id_foreign` (`user_id`);

--
-- Indexes for table `provider_services`
--
ALTER TABLE `provider_services`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `provider_services_user_id_service_id_unique` (`user_id`,`service_id`),
  ADD KEY `provider_services_service_id_foreign` (`service_id`),
  ADD KEY `provider_services_provider_profile_id_foreign` (`provider_profile_id`);

--
-- Indexes for table `provider_service_media`
--
ALTER TABLE `provider_service_media`
  ADD PRIMARY KEY (`id`),
  ADD KEY `provider_service_media_user_id_foreign` (`user_id`),
  ADD KEY `provider_service_media_provider_profile_id_foreign` (`provider_profile_id`),
  ADD KEY `provider_service_media_provider_service_id_foreign` (`provider_service_id`);

--
-- Indexes for table `provider_working_hours`
--
ALTER TABLE `provider_working_hours`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `provider_working_hours_user_id_day_unique` (`user_id`,`day`),
  ADD KEY `provider_working_hours_provider_profile_id_foreign` (`provider_profile_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `services_name_unique` (`name`),
  ADD UNIQUE KEY `services_slug_unique` (`slug`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_referral_code_unique` (`referral_code`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attachments`
--
ALTER TABLE `attachments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `booking_days`
--
ALTER TABLE `booking_days`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `booking_messages`
--
ALTER TABLE `booking_messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `booking_reschedules`
--
ALTER TABLE `booking_reschedules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `booking_slots`
--
ALTER TABLE `booking_slots`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bookmarks`
--
ALTER TABLE `bookmarks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `conversations`
--
ALTER TABLE `conversations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `conversation_participants`
--
ALTER TABLE `conversation_participants`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `device_tokens`
--
ALTER TABLE `device_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `disputes`
--
ALTER TABLE `disputes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `message_reads`
--
ALTER TABLE `message_reads`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT for table `offers`
--
ALTER TABLE `offers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `offer_revisions`
--
ALTER TABLE `offer_revisions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payouts`
--
ALTER TABLE `payouts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `provider_certificates`
--
ALTER TABLE `provider_certificates`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `provider_profiles`
--
ALTER TABLE `provider_profiles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `provider_services`
--
ALTER TABLE `provider_services`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `provider_service_media`
--
ALTER TABLE `provider_service_media`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `provider_working_hours`
--
ALTER TABLE `provider_working_hours`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attachments`
--
ALTER TABLE `attachments`
  ADD CONSTRAINT `attachments_message_id_foreign` FOREIGN KEY (`message_id`) REFERENCES `messages` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_provider_id_foreign` FOREIGN KEY (`provider_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_provider_service_id_foreign` FOREIGN KEY (`provider_service_id`) REFERENCES `provider_services` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `booking_days`
--
ALTER TABLE `booking_days`
  ADD CONSTRAINT `booking_days_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `booking_reschedules`
--
ALTER TABLE `booking_reschedules`
  ADD CONSTRAINT `booking_reschedules_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `booking_reschedules_requested_by_foreign` FOREIGN KEY (`requested_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `booking_slots`
--
ALTER TABLE `booking_slots`
  ADD CONSTRAINT `booking_slots_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `bookmarks`
--
ALTER TABLE `bookmarks`
  ADD CONSTRAINT `bookmarks_provider_id_foreign` FOREIGN KEY (`provider_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookmarks_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `conversations`
--
ALTER TABLE `conversations`
  ADD CONSTRAINT `conversations_created_by_id_foreign` FOREIGN KEY (`created_by_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `conversation_participants`
--
ALTER TABLE `conversation_participants`
  ADD CONSTRAINT `conversation_participants_conversation_id_foreign` FOREIGN KEY (`conversation_id`) REFERENCES `conversations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `conversation_participants_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_conversation_id_foreign` FOREIGN KEY (`conversation_id`) REFERENCES `conversations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_sender_id_foreign` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `message_reads`
--
ALTER TABLE `message_reads`
  ADD CONSTRAINT `message_reads_message_id_foreign` FOREIGN KEY (`message_id`) REFERENCES `messages` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `message_reads_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `offers`
--
ALTER TABLE `offers`
  ADD CONSTRAINT `offers_conversation_id_foreign` FOREIGN KEY (`conversation_id`) REFERENCES `conversations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `offers_current_revision_id_foreign` FOREIGN KEY (`current_revision_id`) REFERENCES `offer_revisions` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `offers_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `offers_provider_id_foreign` FOREIGN KEY (`provider_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `offer_revisions`
--
ALTER TABLE `offer_revisions`
  ADD CONSTRAINT `offer_revisions_by_user_id_foreign` FOREIGN KEY (`by_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `offer_revisions_offer_id_foreign` FOREIGN KEY (`offer_id`) REFERENCES `offers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `provider_certificates`
--
ALTER TABLE `provider_certificates`
  ADD CONSTRAINT `provider_certificates_provider_profile_id_foreign` FOREIGN KEY (`provider_profile_id`) REFERENCES `provider_profiles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `provider_certificates_provider_service_id_foreign` FOREIGN KEY (`provider_service_id`) REFERENCES `provider_services` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `provider_certificates_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `provider_profiles`
--
ALTER TABLE `provider_profiles`
  ADD CONSTRAINT `provider_profiles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `provider_services`
--
ALTER TABLE `provider_services`
  ADD CONSTRAINT `provider_services_provider_profile_id_foreign` FOREIGN KEY (`provider_profile_id`) REFERENCES `provider_profiles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `provider_services_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `provider_services_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `provider_service_media`
--
ALTER TABLE `provider_service_media`
  ADD CONSTRAINT `provider_service_media_provider_profile_id_foreign` FOREIGN KEY (`provider_profile_id`) REFERENCES `provider_profiles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `provider_service_media_provider_service_id_foreign` FOREIGN KEY (`provider_service_id`) REFERENCES `provider_services` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `provider_service_media_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `provider_working_hours`
--
ALTER TABLE `provider_working_hours`
  ADD CONSTRAINT `provider_working_hours_provider_profile_id_foreign` FOREIGN KEY (`provider_profile_id`) REFERENCES `provider_profiles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `provider_working_hours_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
