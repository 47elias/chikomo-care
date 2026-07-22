-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 22, 2026 at 03:21 PM
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
-- Database: `chikomo_care`
--

-- --------------------------------------------------------

--
-- Table structure for table `anyms-users`
--

CREATE TABLE `anyms-users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
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
-- Table structure for table `conversations`
--

CREATE TABLE `conversations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `counselor_id` bigint(20) UNSIGNED DEFAULT NULL,
  `token` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `is_flagged` tinyint(1) NOT NULL DEFAULT 0,
  `risk_level` enum('low','medium','high') NOT NULL DEFAULT 'low',
  `is_human_request` tinyint(1) NOT NULL DEFAULT 1,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `conversations`
--

INSERT INTO `conversations` (`id`, `counselor_id`, `token`, `alias`, `is_flagged`, `risk_level`, `is_human_request`, `status`, `created_at`, `updated_at`) VALUES
(122, 5, 'qR9QShNJ9Mp3DJ7iYPKieBm6CJoQmSKdAFACqhQl', 'Bright Mountain 832', 0, 'low', 1, 'active', '2026-07-22 07:11:33', '2026-07-22 07:12:40'),
(123, 5, 'DGw7pN4WCpA6ksJZvrCiAmCuiIYB13TuYqSsIMP2', 'Quiet Anchor 328', 0, 'low', 1, 'active', '2026-07-22 07:11:33', '2026-07-22 07:13:21'),
(124, NULL, 'tzJVLaARB4ndIuAUbvllU1lUtLxvd1GPawrntWPd', 'Resilient Guardian 319', 0, 'low', 1, 'pending', '2026-07-22 07:36:33', '2026-07-22 07:36:33'),
(125, NULL, 'dwvUdKjq51THmBCeYt3d1HRoqjKr0SkTk1VPKZqT', 'Kind Ocean 132', 0, 'low', 1, 'pending', '2026-07-22 07:36:33', '2026-07-22 07:36:33');

-- --------------------------------------------------------

--
-- Table structure for table `counselors`
--

CREATE TABLE `counselors` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `specialization` varchar(255) NOT NULL,
  `license_number` varchar(255) NOT NULL,
  `bio` text DEFAULT NULL,
  `phone_number` varchar(255) DEFAULT NULL,
  `status` enum('available','busy','offline') NOT NULL DEFAULT 'offline',
  `experience_years` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `counselor_assignments`
--

CREATE TABLE `counselor_assignments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `counselor_id` bigint(20) UNSIGNED NOT NULL,
  `conversation_id` bigint(20) UNSIGNED NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `counselor_logs`
--

CREATE TABLE `counselor_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `conversation_id` bigint(20) UNSIGNED NOT NULL,
  `counselor_id` bigint(20) UNSIGNED NOT NULL,
  `session_started_at` datetime NOT NULL,
  `session_ended_at` datetime DEFAULT NULL,
  `summary_notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `counselor_logs`
--

INSERT INTO `counselor_logs` (`id`, `conversation_id`, `counselor_id`, `session_started_at`, `session_ended_at`, `summary_notes`, `created_at`, `updated_at`) VALUES
(8, 122, 5, '2026-07-22 09:12:40', NULL, NULL, '2026-07-22 07:12:40', '2026-07-22 07:12:40'),
(9, 123, 5, '2026-07-22 09:13:12', NULL, NULL, '2026-07-22 07:13:12', '2026-07-22 07:13:12');

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
-- Table structure for table `groceries`
--

CREATE TABLE `groceries` (
  `id` int(11) NOT NULL,
  `item_name` varchar(100) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `category` varchar(50) DEFAULT NULL,
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `human_conversations`
--

CREATE TABLE `human_conversations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `counselor_id` bigint(20) UNSIGNED DEFAULT NULL,
  `token` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL DEFAULT 'Anonymous Guest',
  `is_flagged` tinyint(1) NOT NULL DEFAULT 0,
  `risk_level` enum('low','medium','high') NOT NULL DEFAULT 'low',
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `human_counselor_logs`
--

CREATE TABLE `human_counselor_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `human_conversation_id` bigint(20) UNSIGNED NOT NULL,
  `counselor_id` bigint(20) UNSIGNED NOT NULL,
  `session_started_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `session_ended_at` timestamp NULL DEFAULT NULL,
  `summary_notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `human_messages`
--

CREATE TABLE `human_messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `human_conversation_id` bigint(20) UNSIGNED NOT NULL,
  `content` text NOT NULL,
  `sender_type` enum('user','counselor','moderator') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
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
  `content` text NOT NULL,
  `sender_type` enum('user','ai','moderator') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `conversation_id`, `content`, `sender_type`, `created_at`, `updated_at`) VALUES
(255, 123, 'hi counsillor', 'user', '2026-07-22 07:13:21', '2026-07-22 07:13:21'),
(256, 123, 'How are you doing today? Is there something on your mind that you\'d like to talk about or perhaps share with me?', 'ai', '2026-07-22 07:13:21', '2026-07-22 07:13:21');

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
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_04_17_235144_create_chats_tables', 2),
(5, '2026_04_18_003214_create_stories_table', 3),
(6, '2026_04_18_012904_create_messages_table', 4),
(7, '2026_04_18_013257_create_messages_table', 5),
(8, '2026_04_20_133140_anyms-users', 6),
(9, '2026_04_22_131353_create_personal_access_tokens_table', 7),
(10, '2026_04_22_133959_create_chikomo_tables', 8),
(11, '2026_04_30_222344_counselors', 9),
(12, '2026_05_02_145532_create_counselor_assignments_table', 10),
(13, '2026_05_28_112117_add_role_to_users_table', 11),
(14, '2026_05_28_113436_add_status_to_users_table', 12),
(15, '2026_05_29_193842_create_stress_modules_table', 13),
(16, '2026_05_29_195156_create_peer_stories_table', 14),
(17, '2026_05_29_200142_update_conversations_for_counselor_matching', 15),
(18, '2026_05_29_200211_create_counselor_logs_table', 15),
(19, '2026_05_29_225924_human_conversations', 16),
(20, '2026_05_29_230047_human_messages', 17),
(21, '2026_05_29_230150_human_counselor_logs', 18),
(22, '2026_05_29_233142_add_is_human_request_to_conversations_table', 19),
(23, '2026_07_21_205143_request_to_add_human_column', 20);

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
-- Table structure for table `peer_stories`
--

CREATE TABLE `peer_stories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `author_alias` varchar(255) NOT NULL DEFAULT 'Anonymous Peer',
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `is_approved` tinyint(1) NOT NULL DEFAULT 0,
  `rating_average` decimal(3,2) NOT NULL DEFAULT 0.00,
  `total_ratings_count` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('Uwbpbk8fCh4l5pK0XjE87zE3JtISd6W41adZV5fj', 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiRHF0TlBudU1NZkZjbDE2SHVBSzVpMjJhQktKTzJYdEV4cTZqbUNaRyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9jb3Vuc2Vsb3ItcG9ydGFsL2NoYXQvMTIzIjtzOjU6InJvdXRlIjtzOjE0OiJjb3Vuc2Vsb3IuY2hhdCI7fXM6MzoidXJsIjthOjA6e31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTo1O30=', 1784726336);

-- --------------------------------------------------------

--
-- Table structure for table `stress_modules`
--

CREATE TABLE `stress_modules` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `file_path` varchar(255) NOT NULL,
  `download_count` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stress_modules`
--

INSERT INTO `stress_modules` (`id`, `title`, `description`, `file_path`, `download_count`, `created_at`, `updated_at`) VALUES
(4, 'cv', 'cv', 'uploads/stress_modules/1784713490_chihambakwecv.pdf', 0, '2026-07-22 07:44:50', '2026-07-22 07:44:50');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'admin',
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `role`, `status`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Chikomo Care', 'default@chikomo.ac.zw', 'admin', 1, '2026-04-30 10:50:06', '$2y$12$5Unyr3avs4Vd/BCqpT8IS.Xt/u20otrt8dZdBA1IPGLXMxqHrdVm.', 'k7rjhhS3OM', '2026-04-30 10:50:07', '2026-06-01 05:01:49'),
(2, 'Musa Elias Mukahlera', 'b240336a@students.co.zw', 'admin', 1, '2026-04-30 10:50:35', '$2y$12$PyubPEhJjm43WIKdTa12mu0AczVhIU4flh2BR3CMnbFAQJf195AxC', 'PlUg3J5txs8mjOMS4kufzphzILfh4DIkM1IzZM8cnICYrKrpFr4EIt1jcQ4E', '2026-04-30 10:50:35', '2026-07-21 18:28:10'),
(5, 'Hamamunashe Tirekerwi', 'elon@chikomo.co.zw', 'counselor', 1, NULL, '$2y$12$dYUOhv/HsljcyKGoNUJ8DuIbQReKAd5ODBYQGz.nv9NoQ94cEzSRe', NULL, '2026-05-28 09:38:19', '2026-07-21 18:28:38'),
(6, 'Mrs D Murasi', 'tete@chikomo.ac.zw', 'counselor', 1, NULL, '$2y$12$9DlxGYl5cWzDkhCPPvsshO2F7aj5jQv2anh6IUfwY8XIv6/Iz7O.G', NULL, '2026-05-28 09:49:17', '2026-05-29 18:31:08'),
(7, 'Portia Chihwai', 'pchihwai@chikomo.ac.zw', 'counselor', 1, NULL, '$2y$12$bTP/FrSgP4b2WmRPzkkQquv8ogqUD4O.e23AWnsp4YSTz1Xcpnxku', NULL, '2026-05-29 17:28:48', '2026-05-29 17:28:48'),
(8, 'Lynetty Mutsakama', 'lynetty@chikomo.co.zw', 'counselor', 1, NULL, '$2y$12$3oUt2G8hYRNmJmEW3kh/VunZ8.ggO/e4ptfyNCcRuOSM4olJPNisW', NULL, '2026-06-01 05:03:24', '2026-06-01 05:03:48'),
(9, 'Tadiwanashe Tamawenyu', 'tadiwa@chikomo.co.zw', 'admin', 1, NULL, '$2y$12$RSXQQuvGEwBY/MXo.JxZ1e.Xr/UBx1H.TuWz2B7FgJ71JIy51gCoi', NULL, '2026-06-01 20:41:45', '2026-06-01 20:41:45'),
(11, 'Neville Mupasa', 'neville@chikomo.co.zw', 'counselor', 1, NULL, '$2y$12$RsFbjHZUcSN3F3LmpfvGgekIQ1/om0EsbjtJZ80Sk/SQx5WkeY.L2', NULL, '2026-06-01 20:43:14', '2026-06-01 20:43:14'),
(12, 'Sean Mutandi', 'sean@chikomocare.co.zw', 'counselor', 1, NULL, '$2y$12$YwQg5IU3BVPRQMDjx19DtulKj8nZ086pcInT.BRCd19W5hmInrQ.a', NULL, '2026-07-21 19:14:59', '2026-07-21 19:14:59');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `anyms-users`
--
ALTER TABLE `anyms-users`
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
-- Indexes for table `conversations`
--
ALTER TABLE `conversations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `conversations_token_unique` (`token`);

--
-- Indexes for table `counselors`
--
ALTER TABLE `counselors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `counselors_license_number_unique` (`license_number`),
  ADD KEY `counselors_user_id_foreign` (`user_id`);

--
-- Indexes for table `counselor_assignments`
--
ALTER TABLE `counselor_assignments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `counselor_assignments_counselor_id_foreign` (`counselor_id`),
  ADD KEY `counselor_assignments_conversation_id_foreign` (`conversation_id`);

--
-- Indexes for table `counselor_logs`
--
ALTER TABLE `counselor_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `counselor_logs_conversation_id_foreign` (`conversation_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `groceries`
--
ALTER TABLE `groceries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `human_conversations`
--
ALTER TABLE `human_conversations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `human_conversations_token_unique` (`token`),
  ADD KEY `human_conversations_counselor_id_foreign` (`counselor_id`),
  ADD KEY `human_conversations_status_created_at_index` (`status`,`created_at`);

--
-- Indexes for table `human_counselor_logs`
--
ALTER TABLE `human_counselor_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `human_counselor_logs_human_conversation_id_foreign` (`human_conversation_id`),
  ADD KEY `human_counselor_logs_counselor_id_session_ended_at_index` (`counselor_id`,`session_ended_at`);

--
-- Indexes for table `human_messages`
--
ALTER TABLE `human_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `human_messages_human_conversation_id_foreign` (`human_conversation_id`);

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
  ADD KEY `messages_conversation_id_foreign` (`conversation_id`);

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
-- Indexes for table `peer_stories`
--
ALTER TABLE `peer_stories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  ADD KEY `personal_access_tokens_expires_at_index` (`expires_at`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `stress_modules`
--
ALTER TABLE `stress_modules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `anyms-users`
--
ALTER TABLE `anyms-users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `conversations`
--
ALTER TABLE `conversations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=126;

--
-- AUTO_INCREMENT for table `counselors`
--
ALTER TABLE `counselors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `counselor_assignments`
--
ALTER TABLE `counselor_assignments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `counselor_logs`
--
ALTER TABLE `counselor_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `groceries`
--
ALTER TABLE `groceries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `human_conversations`
--
ALTER TABLE `human_conversations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `human_counselor_logs`
--
ALTER TABLE `human_counselor_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `human_messages`
--
ALTER TABLE `human_messages`
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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=257;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `peer_stories`
--
ALTER TABLE `peer_stories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stress_modules`
--
ALTER TABLE `stress_modules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `counselors`
--
ALTER TABLE `counselors`
  ADD CONSTRAINT `counselors_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `counselor_assignments`
--
ALTER TABLE `counselor_assignments`
  ADD CONSTRAINT `counselor_assignments_conversation_id_foreign` FOREIGN KEY (`conversation_id`) REFERENCES `conversations` (`id`),
  ADD CONSTRAINT `counselor_assignments_counselor_id_foreign` FOREIGN KEY (`counselor_id`) REFERENCES `counselors` (`id`);

--
-- Constraints for table `counselor_logs`
--
ALTER TABLE `counselor_logs`
  ADD CONSTRAINT `counselor_logs_conversation_id_foreign` FOREIGN KEY (`conversation_id`) REFERENCES `conversations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `human_conversations`
--
ALTER TABLE `human_conversations`
  ADD CONSTRAINT `human_conversations_counselor_id_foreign` FOREIGN KEY (`counselor_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `human_counselor_logs`
--
ALTER TABLE `human_counselor_logs`
  ADD CONSTRAINT `human_counselor_logs_counselor_id_foreign` FOREIGN KEY (`counselor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `human_counselor_logs_human_conversation_id_foreign` FOREIGN KEY (`human_conversation_id`) REFERENCES `human_conversations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `human_messages`
--
ALTER TABLE `human_messages`
  ADD CONSTRAINT `human_messages_human_conversation_id_foreign` FOREIGN KEY (`human_conversation_id`) REFERENCES `human_conversations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_conversation_id_foreign` FOREIGN KEY (`conversation_id`) REFERENCES `conversations` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
