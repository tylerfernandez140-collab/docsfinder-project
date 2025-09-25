-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Aug 31, 2025 at 01:39 PM
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
-- Database: `db_docsfinder`
--

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
-- Table structure for table `file_user_downloads`
--

CREATE TABLE `file_user_downloads` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `file_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE `groups` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_by_role` tinyint(3) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`id`, `name`, `created_by_role`, `created_at`, `updated_at`) VALUES
(3, 'Documents Finder', NULL, '2025-07-13 07:13:04', '2025-07-13 07:13:04'),
(5, 'Anonymous', NULL, '2025-07-13 08:42:59', '2025-07-13 08:42:59'),
(6, 'marites gc', NULL, '2025-08-30 20:41:36', '2025-08-30 20:41:36');

-- --------------------------------------------------------

--
-- Table structure for table `group_user`
--

CREATE TABLE `group_user` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `group_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `group_user`
--

INSERT INTO `group_user` (`id`, `group_id`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 3, 3, NULL, NULL),
(2, 3, 2, NULL, NULL),
(4, 5, 2, '2025-07-13 08:43:12', '2025-07-13 08:43:12'),
(5, 5, 3, '2025-07-13 08:45:04', '2025-07-13 08:45:04'),
(6, 3, 4, '2025-07-13 09:09:22', '2025-07-13 09:09:22'),
(7, 3, 5, '2025-07-13 09:09:28', '2025-07-13 09:09:28'),
(8, 6, 4, '2025-08-30 20:43:16', '2025-08-30 20:43:16'),
(9, 6, 2, '2025-08-30 20:43:28', '2025-08-30 20:43:28');

-- --------------------------------------------------------

--
-- Table structure for table `history`
--

CREATE TABLE `history` (
  `history_id` bigint(20) UNSIGNED NOT NULL,
  `user_activity` text NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `history`
--

INSERT INTO `history` (`history_id`, `user_activity`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 'Viewing Documents', 5, '2025-07-11 12:24:46', '2025-07-11 12:24:46'),
(2, 'Viewing Documents', 5, '2025-07-11 12:24:59', '2025-07-11 12:24:59'),
(3, 'Requesting Edit Documents', 5, '2025-07-11 12:25:01', '2025-07-11 12:25:01'),
(4, 'Viewing Documents', 5, '2025-07-11 12:25:02', '2025-07-11 12:25:02'),
(5, 'Requesting Document is Reviewing', 4, '2025-07-11 12:26:17', '2025-07-11 12:26:17'),
(6, 'Viewing Documents', 5, '2025-07-11 12:29:25', '2025-07-11 12:29:25'),
(7, 'Requesting Edit Documents', 5, '2025-07-11 12:29:27', '2025-07-11 12:29:27'),
(8, 'Viewing Documents', 5, '2025-07-11 12:29:27', '2025-07-11 12:29:27'),
(9, 'Requesting Document is Reviewing', 4, '2025-07-11 12:29:44', '2025-07-11 12:29:44'),
(10, 'File Downloaded :LNDba4wUkGTzOT9rKh86Ds4WlZx7csciWqz93XLG.xlsx', 4, '2025-07-11 12:31:29', '2025-07-11 12:31:29'),
(11, 'File Downloaded :LNDba4wUkGTzOT9rKh86Ds4WlZx7csciWqz93XLG.xlsx', 4, '2025-07-11 23:46:11', '2025-07-11 23:46:11'),
(12, 'Viewing Documents', 5, '2025-07-11 23:47:01', '2025-07-11 23:47:01'),
(13, 'File Downloaded :LNDba4wUkGTzOT9rKh86Ds4WlZx7csciWqz93XLG.xlsx', 5, '2025-07-11 23:47:03', '2025-07-11 23:47:03'),
(14, 'Viewing Documents', 5, '2025-07-11 23:47:06', '2025-07-11 23:47:06'),
(15, 'Viewing Documents', 5, '2025-07-11 23:47:11', '2025-07-11 23:47:11'),
(16, 'Requesting Edit Documents', 5, '2025-07-11 23:47:13', '2025-07-11 23:47:13'),
(17, 'Viewing Documents', 5, '2025-07-11 23:47:13', '2025-07-11 23:47:13'),
(18, 'File Downloaded :LNDba4wUkGTzOT9rKh86Ds4WlZx7csciWqz93XLG.xlsx', 5, '2025-07-11 23:47:32', '2025-07-11 23:47:32'),
(19, 'File Downloaded :LNDba4wUkGTzOT9rKh86Ds4WlZx7csciWqz93XLG.xlsx', 5, '2025-07-11 23:51:00', '2025-07-11 23:51:00'),
(20, 'File Downloaded :LNDba4wUkGTzOT9rKh86Ds4WlZx7csciWqz93XLG.xlsx', 5, '2025-07-11 23:51:01', '2025-07-11 23:51:01'),
(21, 'File Downloaded :LNDba4wUkGTzOT9rKh86Ds4WlZx7csciWqz93XLG.xlsx', 5, '2025-07-11 23:51:02', '2025-07-11 23:51:02'),
(22, 'File Downloaded :LNDba4wUkGTzOT9rKh86Ds4WlZx7csciWqz93XLG.xlsx', 5, '2025-07-11 23:51:03', '2025-07-11 23:51:03'),
(23, 'File Downloaded :LNDba4wUkGTzOT9rKh86Ds4WlZx7csciWqz93XLG.xlsx', 5, '2025-07-11 23:51:24', '2025-07-11 23:51:24'),
(24, 'File Downloaded :LNDba4wUkGTzOT9rKh86Ds4WlZx7csciWqz93XLG.xlsx', 5, '2025-07-11 23:51:29', '2025-07-11 23:51:29'),
(25, 'File Downloaded :LNDba4wUkGTzOT9rKh86Ds4WlZx7csciWqz93XLG.xlsx', 5, '2025-07-11 23:51:37', '2025-07-11 23:51:37'),
(26, 'File Downloaded :LNDba4wUkGTzOT9rKh86Ds4WlZx7csciWqz93XLG.xlsx', 3, '2025-07-11 23:59:25', '2025-07-11 23:59:25'),
(27, 'File Downloaded :LNDba4wUkGTzOT9rKh86Ds4WlZx7csciWqz93XLG.xlsx', 3, '2025-08-31 03:17:05', '2025-08-31 03:17:05');

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
  `group_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `content` text NOT NULL,
  `parent_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `group_id`, `user_id`, `content`, `parent_id`, `created_at`, `updated_at`) VALUES
(1, 3, 2, 'hi guys', NULL, '2025-07-13 07:13:13', '2025-07-13 07:13:13'),
(2, 3, 2, 'asd', 1, '2025-07-13 07:17:02', '2025-07-13 07:17:02'),
(3, 3, 3, 'hello buddy!', NULL, '2025-07-13 07:20:45', '2025-07-13 07:20:45'),
(4, 3, 2, 'hi guys', 3, '2025-07-13 07:21:06', '2025-07-13 07:21:06'),
(5, 5, 2, 'dasdasdasd', NULL, '2025-07-13 08:45:16', '2025-07-13 08:45:16'),
(6, 3, 3, 'hyiygyggiyg', NULL, '2025-07-13 09:01:32', '2025-07-13 09:01:32'),
(7, 3, 3, 'kutftuf', 6, '2025-07-13 09:01:37', '2025-07-13 09:01:37'),
(8, 3, 5, 'hi', NULL, '2025-07-13 09:09:50', '2025-07-13 09:09:50');

-- --------------------------------------------------------

--
-- Table structure for table `message_reads`
--

CREATE TABLE `message_reads` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `message_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `read_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `message_reads`
--

INSERT INTO `message_reads` (`id`, `message_id`, `user_id`, `read_at`) VALUES
(1, 1, 2, '2025-07-13 07:13:13'),
(2, 2, 2, '2025-07-13 07:17:02'),
(3, 3, 3, '2025-07-13 07:20:45'),
(4, 4, 2, '2025-07-13 07:21:06'),
(5, 5, 2, '2025-07-13 08:45:16'),
(6, 6, 3, '2025-07-13 09:01:32'),
(7, 7, 3, '2025-07-13 09:01:38'),
(8, 8, 5, '2025-07-13 09:09:50');

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
(5, '0001_01_01_000000_create_users_table', 1),
(6, '0001_01_01_000001_create_cache_table', 1),
(7, '0001_01_01_000002_create_jobs_table', 1),
(8, '2025_07_06_155830_create_uploads_table', 1),
(9, '2025_07_06_182017_create_request_table', 2),
(10, '2025_07_06_182017_create_requesting_table', 3),
(11, '2025_07_10_181436_create_history_table', 4),
(12, '2025_07_13_144318_create_groups_table', 5),
(13, '2025_07_13_144350_create_group_user_table', 5),
(14, '2025_07_13_144427_create_messages_table', 5),
(15, '2025_07_13_144456_create_message_reads_table', 5);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `password_reset_tokens`
--

INSERT INTO `password_reset_tokens` (`email`, `token`, `created_at`) VALUES
('xanewa9005@cavoyar.com', '$2y$12$PvYLIYfu38zS1Gpc9d8GHOtVlAlQMdhj8c1JkF809rN2RS6IoEKlm', '2025-08-31 03:18:55');

-- --------------------------------------------------------

--
-- Table structure for table `requesting`
--

CREATE TABLE `requesting` (
  `request_id` bigint(20) UNSIGNED NOT NULL,
  `upload_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `request_status` int(11) NOT NULL,
  `status_remarks` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `requesting`
--

INSERT INTO `requesting` (`request_id`, `upload_id`, `user_id`, `request_status`, `status_remarks`, `created_at`, `updated_at`) VALUES
(21, 16, 5, 1, 'adasdasd', '2025-07-11 12:29:27', '2025-07-11 12:29:44'),
(22, 17, 5, 1, NULL, '2025-07-11 23:47:13', '2025-07-11 23:47:13');

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
('gYa7JaD7mSxwgqYZphXd2GFiGzK1okdmf71dkjOt', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiak5MM012TEpWb1lUTkpwWmpiRzVzTUgweEJlbzFJQ2NLY0V2WlhjUyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9wYXNzd29yZC9yZXNldCI7fX0=', 1756639145),
('zMsmdPnUsT4wSiiGGb32cuBfmoV08toBhE0qJuUi', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiZ2hrUVJxUTlzem0yU2w2UGY1T3VJMTMzZHVTcmdLUElGNkRTUE5ZVCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zZWFyY2giO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToyO3M6NDoiYXV0aCI7YToxOntzOjIxOiJwYXNzd29yZF9jb25maXJtZWRfYXQiO2k6MTc1NjYxNTIxODt9fQ==', 1756619196);

-- --------------------------------------------------------

--
-- Table structure for table `uploads`
--

CREATE TABLE `uploads` (
  `upload_id` bigint(20) UNSIGNED NOT NULL,
  `filename` varchar(255) NOT NULL,
  `size` varchar(255) NOT NULL,
  `file_type` text NOT NULL,
  `path` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status_upload` int(11) NOT NULL,
  `numdl` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `uploads`
--

INSERT INTO `uploads` (`upload_id`, `filename`, `size`, `file_type`, `path`, `user_id`, `status_upload`, `numdl`, `created_at`, `updated_at`) VALUES
(16, '1_SDK -EN.xlsx', '24773', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'uploads/LNDba4wUkGTzOT9rKh86Ds4WlZx7csciWqz93XLG.xlsx', 5, 0, 5, '2025-07-11 12:24:17', '2025-08-31 03:17:05'),
(17, '1_SDK -EN.xlsx', '24773', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'uploads/LNDba4wUkGTzOT9rKh86Ds4WlZx7csciWqz93XLG.xlsx', 5, 0, 4, '2025-07-11 12:24:17', '2025-07-11 23:59:25');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `employee_id` text NOT NULL,
  `dob` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `role`, `name`, `employee_id`, `dob`, `email`, `address`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(2, 0, '045825', 'Super Admin (UDCC)', '2025-08-13', 'SuperAdmin@gmail.com', 'Canada', NULL, '$2y$12$/.Tbx65Dbm/ARQzdKBNjp.c4sLHE9Iso841F50aFgVebTFko01Bpe', NULL, '2025-07-06 08:09:19', '2025-08-30 21:22:24'),
(3, 1, 'Admin (OVPQA)', '120000', '', 'xanewa9005@cavoyar.com', '', NULL, '$2y$12$KAQKimCfxcFqUgYp9NVhOeaPsYh3dHmoDjmXAzj0pCHDrvI06mEza', NULL, '2025-07-10 08:14:31', '2025-07-10 08:14:31'),
(4, 2, 'Campus DCC', '12300', '', 'Campus@gmail.com', '', NULL, '$2y$12$4xFkIy9h73zhvsudJkj6jeu9AEjpbAT5SocABboaER5nLEyBXDX0u', NULL, '2025-07-10 08:15:26', '2025-07-10 08:15:26'),
(5, 3, 'Process Owners (Faculty)', '12340', '', 'ProcessOwners@gmail.com', '', NULL, '$2y$12$TDXAdWM2RvFlLtp5t7JukezIhcoisVDfjfESfBKC.b0vWKNqYsuja', 'CiUvQVMl1TOs75YcVLXR58YA0coyBCV7LkaLFxlzfi7IRe8TEZ46KhuqAhPN', '2025-07-10 08:15:54', '2025-07-10 08:15:54');

--
-- Indexes for dumped tables
--

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
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `file_user_downloads`
--
ALTER TABLE `file_user_downloads`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_file` (`user_id`,`file_id`);

--
-- Indexes for table `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `group_user`
--
ALTER TABLE `group_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `group_user_group_id_foreign` (`group_id`),
  ADD KEY `group_user_user_id_foreign` (`user_id`);

--
-- Indexes for table `history`
--
ALTER TABLE `history`
  ADD PRIMARY KEY (`history_id`);

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
  ADD KEY `messages_group_id_foreign` (`group_id`),
  ADD KEY `messages_user_id_foreign` (`user_id`),
  ADD KEY `messages_parent_id_foreign` (`parent_id`);

--
-- Indexes for table `message_reads`
--
ALTER TABLE `message_reads`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `message_reads_message_id_user_id_unique` (`message_id`,`user_id`),
  ADD KEY `message_reads_user_id_foreign` (`user_id`);

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
-- Indexes for table `requesting`
--
ALTER TABLE `requesting`
  ADD PRIMARY KEY (`request_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `uploads`
--
ALTER TABLE `uploads`
  ADD PRIMARY KEY (`upload_id`);

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
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `file_user_downloads`
--
ALTER TABLE `file_user_downloads`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `group_user`
--
ALTER TABLE `group_user`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `history`
--
ALTER TABLE `history`
  MODIFY `history_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `message_reads`
--
ALTER TABLE `message_reads`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `requesting`
--
ALTER TABLE `requesting`
  MODIFY `request_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `uploads`
--
ALTER TABLE `uploads`
  MODIFY `upload_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `group_user`
--
ALTER TABLE `group_user`
  ADD CONSTRAINT `group_user_group_id_foreign` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `group_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_group_id_foreign` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `messages` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `message_reads`
--
ALTER TABLE `message_reads`
  ADD CONSTRAINT `message_reads_message_id_foreign` FOREIGN KEY (`message_id`) REFERENCES `messages` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `message_reads_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
