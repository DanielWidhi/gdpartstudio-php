-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 02, 2026 at 07:54 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gdpartstudio`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` int NOT NULL,
  `admin_id` int DEFAULT NULL,
  `action_type` varchar(50) NOT NULL,
  `target_name` varchar(255) NOT NULL,
  `detail` varchar(255) DEFAULT NULL,
  `device` varchar(50) DEFAULT NULL,
  `browser` varchar(50) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `admin_id`, `action_type`, `target_name`, `detail`, `device`, `browser`, `ip_address`, `created_at`) VALUES
(5, 1, 'Create', 'INV-26011310', 'Klien: anak anjing | Total: Rp 12', 'Windows', 'Chrome', '127.0.0.1', '2026-01-13 18:38:28'),
(6, 2, 'Create', 'jirlah', 'Menambah portfolio baru', 'Windows', 'Chrome', '127.0.0.1', '2026-01-13 18:51:14'),
(7, 2, 'Update', 'memek', 'Memperbarui data portfolio', 'Windows', 'Chrome', '127.0.0.1', '2026-01-13 18:53:47'),
(8, 2, 'Delete', 'memek', 'Menghapus portfolio permanen', 'Windows', 'Chrome', '127.0.0.1', '2026-01-13 18:54:03'),
(9, 2, 'Delete', 'Testing Wedding', 'Menghapus portfolio permanen', 'Windows', 'Chrome', '127.0.0.1', '2026-01-13 18:54:31'),
(10, 2, 'Update', 'The Night', 'Memperbarui data portfolio', 'Windows', 'Chrome', '127.0.0.1', '2026-01-13 18:54:50'),
(11, 2, 'Delete', 'zzz', 'Menghapus akun admin', 'Windows', 'Chrome', '127.0.0.1', '2026-01-13 19:01:05'),
(12, 4, 'Update', 'Profil Sendiri', 'Memperbarui informasi akun', 'Windows', 'Chrome', '127.0.0.1', '2026-01-13 19:03:25'),
(13, 4, 'Delete', 'zzz', 'Menghapus akun admin', 'Windows', 'Chrome', '127.0.0.1', '2026-01-13 19:07:56'),
(14, 4, 'Create', 'zzz', 'Menambahkan admin baru', 'Windows', 'Chrome', '127.0.0.1', '2026-01-13 19:08:02'),
(15, 4, 'Create', 'INV-26011307', 'Klien: amgus | Total: Rp 2.110.000.000', 'Windows', 'Chrome', '127.0.0.1', '2026-01-13 19:09:00'),
(16, 4, 'Update', 'INV-26011307', 'Status diubah menjadi: Lunas', 'Windows', 'Chrome', '127.0.0.1', '2026-01-13 19:13:12'),
(17, 4, 'Create', 'INV-26011307', 'Klien: amgus | Total: Rp 1.333.200.000', 'Windows', 'Chrome', '127.0.0.1', '2026-01-13 19:16:10'),
(18, 4, 'Update', 'INV-26011307', 'Status diubah menjadi: Lunas', 'Windows', 'Chrome', '127.0.0.1', '2026-01-13 19:16:22'),
(19, 4, 'Delete', 'INV-26011307', 'Menghapus data invoice', 'Windows', 'Chrome', '127.0.0.1', '2026-01-13 19:16:31'),
(20, 6, 'Login', 'System', 'Gagal Login: Password Salah', 'Windows', 'Chrome', '127.0.0.1', '2026-01-13 19:23:11'),
(21, 6, 'Login', 'System', 'Login Berhasil', 'Windows', 'Chrome', '127.0.0.1', '2026-01-13 19:23:15'),
(22, 6, 'Login', 'System', 'Login Berhasil', 'Windows', 'Chrome', '127.0.0.1', '2026-01-13 19:35:13'),
(23, NULL, 'Login Failed', 'hacker@gmail.com', 'Percobaan login: Email tidak terdaftar', 'Windows', 'Chrome', '127.0.0.1', '2026-01-14 04:18:56'),
(24, 4, 'Login', 'System', 'Login Berhasil', 'Windows', 'Chrome', '127.0.0.1', '2026-01-14 04:19:02'),
(25, 4, 'Login', 'System', 'Login Berhasil', 'Windows', 'Chrome', '127.0.0.1', '2026-01-14 04:22:43'),
(26, 4, 'Create', 'INV-26011407', 'Klien: amgus | Total: Rp 22.200.000.000', 'Windows', 'Chrome', '127.0.0.1', '2026-01-14 04:26:00'),
(27, 4, 'Update', 'INV-26011407', 'Status diubah menjadi: Lunas', 'Windows', 'Chrome', '127.0.0.1', '2026-01-14 04:26:11'),
(28, 4, 'Login', 'System', 'Login Berhasil', 'Windows', 'Chrome', '127.0.0.1', '2026-01-14 07:00:35'),
(29, 4, 'Create', 'INV-26011414', 'Klien: jierss | Total: Rp 4.773.000', 'Windows', 'Chrome', '127.0.0.1', '2026-01-14 07:03:07'),
(30, 4, 'Update', 'INV-26011306', 'Status diubah menjadi: Lunas', 'Windows', 'Chrome', '127.0.0.1', '2026-01-14 07:03:18'),
(31, 4, 'Login', 'System', 'Login Berhasil', 'Windows', 'Chrome', '127.0.0.1', '2026-01-14 07:07:00'),
(32, 4, 'Login', 'System', 'Login Berhasil', 'Windows', 'Chrome', '127.0.0.1', '2026-01-14 08:26:16'),
(33, 4, 'Create', 'Daniel Widhi', 'Menambahkan admin baru', 'Windows', 'Chrome', '127.0.0.1', '2026-01-14 08:27:15'),
(34, 4, 'Update', 'Profil Sendiri', 'Update profil/foto', 'Windows', 'Chrome', '127.0.0.1', '2026-01-14 08:27:58'),
(35, 4, 'Login', 'System', 'Login Berhasil', 'Windows', 'Chrome', '127.0.0.1', '2026-01-14 12:48:20'),
(36, 4, 'Login', 'System', 'Login Berhasil', 'Windows', 'Chrome', '127.0.0.1', '2026-01-14 13:06:38'),
(37, 4, 'Login', 'System', 'Login Berhasil', 'Windows', 'Chrome', '127.0.0.1', '2026-01-18 07:27:22'),
(38, 1, 'Login', 'System', 'Login Berhasil', 'Windows', 'Chrome', '127.0.0.1', '2026-01-18 07:27:33'),
(39, 4, 'Login', 'System', 'Login Berhasil', 'Windows', 'Chrome', '127.0.0.1', '2026-01-18 07:27:53'),
(40, 4, 'Login', 'System', 'Login Berhasil', 'Windows', 'Chrome', '127.0.0.1', '2026-01-19 11:41:03'),
(41, 1, 'Login', 'System', 'Login Berhasil', 'Windows', 'Chrome', '127.0.0.1', '2026-01-19 11:41:14'),
(42, 4, 'Login', 'System', 'Login Berhasil', 'Windows', 'Chrome', '127.0.0.1', '2026-01-21 05:09:34'),
(43, 4, 'Update', 'Aputure 600d Pro', 'Ubah status ke: Di Studio', 'Windows', 'Chrome', '127.0.0.1', '2026-01-21 05:37:20'),
(44, 1, 'Login', 'System', 'Login Berhasil', 'Windows', 'Chrome', '127.0.0.1', '2026-01-21 05:50:49'),
(45, 4, 'Login', 'System', 'Login Berhasil', 'Windows', 'Chrome', '127.0.0.1', '2026-01-21 05:53:24'),
(46, 4, 'Update', 'Aputure 600d Pro', 'Ubah status ke: Di Lapangan', 'Windows', 'Chrome', '127.0.0.1', '2026-01-21 06:43:40'),
(47, 4, 'Update', 'Aputure 600d Pro', 'Ubah status ke: Di Lapangan', 'Windows', 'Chrome', '127.0.0.1', '2026-01-21 06:45:27'),
(48, 4, 'Login', 'System', 'Login Berhasil', 'Windows', 'Chrome', '127.0.0.1', '2026-01-27 04:37:49');

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `avatar` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `email`, `password`, `created_at`, `avatar`) VALUES
(1, 'Super Admin', 'admin@gdpartstudio.com', '$2y$10$L50UhgzY/PfUgj0KpJCDR.hILLHLjDPwyZFb9D7Vb0uwUwBc/6RLG', '2026-01-12 08:02:09', NULL),
(2, 'Dontol', 'dontol@gdpartstudio.com', '$2y$10$jxmv2gW.MGukBACtXbqD8OVawSynwxYlUuXzim46jV90Ykq5TIc1S', '2026-01-13 12:31:19', NULL),
(4, 'DW', 'zzz@gdpartstudio.com', '$2y$10$WW8M18pvm5cgRoA2fz9SIulHxob1.R/UvEyZzqMmBNWbdM8M.ARza', '2026-01-13 19:01:37', 'assets/images/admin/1768379278_6967538e8fac6.jpg'),
(6, 'zzz', 'dw@gdpartstudio.com', '$2y$10$iKQM0/jkJCn2Lh0Ifacxj.P9sdpXLxaeVbZF.m1uN8IyChyh/AOrS', '2026-01-13 19:08:02', NULL),
(7, 'Daniel Widhi', 'gededaniel14@gmail.com', '$2y$10$4PkChRrsUzF9NAa.9TCd0OknZymGDEOAlcesblnCcyIqBMSUVQFlG', '2026-01-14 08:27:15', '');

-- --------------------------------------------------------

--
-- Table structure for table `equipments`
--

CREATE TABLE `equipments` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `category` enum('Kamera','Lensa','Drone','Lighting','Audio','Aksesoris') NOT NULL,
  `serial_number` varchar(100) DEFAULT NULL,
  `status` enum('Di Studio','Di Lapangan','Maintenance','Rusak') DEFAULT 'Di Studio',
  `assigned_to` varchar(100) DEFAULT NULL,
  `rack_location` varchar(50) DEFAULT NULL,
  `condition_status` enum('Excellent','Good','Fair','Poor') DEFAULT 'Excellent',
  `last_service_date` date DEFAULT NULL,
  `next_service_date` date DEFAULT NULL,
  `image_url` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `equipments`
--

INSERT INTO `equipments` (`id`, `name`, `category`, `serial_number`, `status`, `assigned_to`, `rack_location`, `condition_status`, `last_service_date`, `next_service_date`, `image_url`, `created_at`) VALUES
(1, 'Sony Alpha A7S III', 'Kamera', 'SN-A7S3-882190', 'Di Lapangan', 'Budi (Kru)', NULL, 'Excellent', '2024-01-12', '2024-06-12', NULL, '2026-01-21 05:19:33'),
(2, 'FE 24-70mm f/2.8 GM II', 'Lensa', 'SN-LENS-GM2470', 'Di Studio', NULL, NULL, 'Excellent', '2024-02-05', '2024-08-05', NULL, '2026-01-21 05:19:33'),
(3, 'DJI Mavic 3 Pro', 'Drone', 'SN-DJI-M3P-1122', 'Maintenance', 'Teknisi: Agus', NULL, 'Good', '2023-09-15', '2024-03-15', NULL, '2026-01-21 05:19:33'),
(4, 'Aputure 600d Pro', 'Lighting', 'SN-APTR-600D-01', 'Di Lapangan', 'Rizler', 'Pecatu', 'Excellent', '2023-12-20', '2024-06-20', NULL, '2026-01-21 05:19:33');

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` int NOT NULL,
  `invoice_number` varchar(20) NOT NULL,
  `client_name` varchar(100) NOT NULL,
  `client_email` varchar(100) DEFAULT NULL,
  `invoice_date` date NOT NULL,
  `total_amount` decimal(15,2) NOT NULL,
  `status` enum('Lunas','Pending','Dibatalkan') DEFAULT 'Pending',
  `file_pdf` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `items_json` text,
  `client_phone` varchar(20) DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `notes` text,
  `discount_amount` decimal(15,2) DEFAULT '0.00',
  `tax_percent` decimal(5,2) DEFAULT '0.00',
  `tax_amount` decimal(15,2) DEFAULT '0.00',
  `grand_total` decimal(15,2) DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`id`, `invoice_number`, `client_name`, `client_email`, `invoice_date`, `total_amount`, `status`, `file_pdf`, `created_at`, `items_json`, `client_phone`, `due_date`, `notes`, `discount_amount`, `tax_percent`, `tax_amount`, `grand_total`) VALUES
(2, 'INV-23102002', 'Siti Aminah', 'siti.a@email.com', '2023-10-20', '3200000.00', 'Pending', NULL, '2026-01-13 13:35:14', '[{\"desc\":\"Layanan Utama\",\"price\":\"3200000.00\",\"qty\":\"1\"}]', '', NULL, '', '0.00', '0.00', '0.00', '3200000.00'),
(3, 'INV-23101503', 'PT. Maju Bersama', 'admin@majubersama.com', '2023-10-15', '12500000.00', 'Lunas', NULL, '2026-01-13 13:35:14', '[{\"desc\":\"Layanan Utama\",\"price\":\"12500000.00\",\"qty\":\"1\"}]', '', NULL, '', '0.00', '0.00', '0.00', '12500000.00'),
(4, 'INV-23101204', 'Agus Setiawan', 'agus.set@email.com', '2023-10-12', '1500000.00', 'Dibatalkan', NULL, '2026-01-13 13:35:14', '[{\"desc\":\"Layanan Utama\",\"price\":\"1500000.00\",\"qty\":\"1\"}]', '', NULL, '', '0.00', '0.00', '0.00', '1500000.00'),
(5, 'INV-26011305', 'donntol', 'test@gdpartstudio.com', '2026-01-13', '1050000.00', 'Pending', NULL, '2026-01-13 16:34:06', '[{\"desc\":\"Layanan Utama\",\"price\":\"1050000.00\",\"qty\":\"1\"}]', '', NULL, '', '0.00', '0.00', '0.00', '1050000.00'),
(6, 'INV-26011306', 'jierss', 'test1@gdpartstudio.com', '2026-01-14', '2400000.00', 'Lunas', 'Invoice-INV-26011306.pdf', '2026-01-13 16:57:30', '[{\"desc\":\"weding package\",\"price\":\"2400000\",\"qty\":\"1\"}]', '0812121212112', NULL, '', '244000.00', '11.00', '237160.00', '2393160.00'),
(13, 'INV-26011407', 'amgus', 'test1@gdpartstudio.com', '2026-01-14', '20000000000.00', 'Lunas', NULL, '2026-01-14 04:26:00', '[{\"desc\":\"weding package\",\"price\":\"20000000\",\"qty\":\"1000\"}]', '0812121212112', NULL, '', '0.00', '11.00', '2200000000.00', '22200000000.00'),
(14, 'INV-26011414', 'jierss', 'test1@gdpartstudio.com', '2026-01-14', '4800000.00', 'Pending', NULL, '2026-01-14 07:03:07', '[{\"desc\":\"weding package\",\"price\":\"2400000\",\"qty\":\"2\"}]', '1294128931823', NULL, '', '500000.00', '11.00', '473000.00', '4773000.00');

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `category_display` varchar(255) COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Contoh: Wedding Photography',
  `filter_tag` varchar(50) COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Contoh: weddings, religious, events',
  `image_url` text COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `client_name` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `event_date` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `location` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `services` varchar(255) COLLATE utf8mb4_general_ci DEFAULT 'Photography, Art Direction',
  `venue` varchar(255) COLLATE utf8mb4_general_ci DEFAULT 'Private Location',
  `concept_text` text COLLATE utf8mb4_general_ci,
  `testimonial_quote` text COLLATE utf8mb4_general_ci,
  `testimonial_author` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `testimonial_role` varchar(100) COLLATE utf8mb4_general_ci DEFAULT 'Client',
  `video_thumbnail_url` text COLLATE utf8mb4_general_ci,
  `status` enum('Published','Draft','Archived') COLLATE utf8mb4_general_ci DEFAULT 'Published',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `title`, `slug`, `category_display`, `filter_tag`, `image_url`, `description`, `client_name`, `event_date`, `location`, `services`, `venue`, `concept_text`, `testimonial_quote`, `testimonial_author`, `testimonial_role`, `video_thumbnail_url`, `status`, `created_at`) VALUES
(1, 'Intimate Vows', 'intimate-vows', 'Wedding Photography', 'weddings', 'https://lh3.googleusercontent.com/aida-public/AB6AXuCtk0wGbbjfAOGWZnzUxuPGqfBwo3M7Ev2bZx6AayTNoDYQgrFEYNrQEXP74pvzJxAv4tEYF7PzkfdpsK20b5z8YeXMjPxtkilXNrqBqntQbP9ib60TceUj2EhBu55m318jwNgq0kV-ofAgWKwApSug47Q92sAUCCVWknekYDooFx3U-U0RBp69ZqxGFgo6JZ3PSpAmKEUIhXt0bMOEM3_5KZspWlLhfolly3LhWG-Eig2oyQzf-v6jRCay2i1-bMD7jdoDfZkcVC0', 'A beautiful, intimate wedding ceremony capturing the raw emotions and sacred promises between the couple.', 'Sarah & James', 'Nov 14, 2023', 'Jakarta, Indonesia', 'Photography, Art Direction', 'Private Garden Estate', 'For Sarah and James, the focus was entirely on authenticity. They wanted a documentation style that felt less like a production and more like a memory unfolding in real-time.\r\n\r\nWe utilized natural light throughout the ceremony to maintain the soft, romantic atmosphere of the garden setting.', 'GDPARTSTUDIO was the best investment we made for our wedding. The team was professional, unobtrusive, and the final gallery brought tears to our eyes.', 'Sarah Jenkins', 'The Bride', 'https://lh3.googleusercontent.com/aida-public/AB6AXuCWDtUfImgY9i2ixmssQoPUZV2DzPAcQbmu82KaTJie7EmwRK_pR7OLyNVcQpV1MvCvqUmOtBtvvrVu3J6Aogg6y8JCN1hoov3OAIQ67wEEZNkP_ZGW9trpuNC22FYW1tIBeTVSJtkf-DUvnCKcSYoA5wyzQJoCGcC8COdClKc0r20tuO2SOroS2VUaQfjwK9AyQfxyaFoPHehZjjWqym032OJKWSXojg6RvvPyX7yjTTBeiB67R_U0zPT34t27_LOILCb62ufv8qk', 'Published', '2026-01-12 08:02:09'),
(2, 'Balinese Ceremony', 'balinese-ceremony', 'Religious Documentation', 'religious', 'https://lh3.googleusercontent.com/aida-public/AB6AXuCINcFoh412PyqaBa9ygkfrVO8NI56thVzFLMabsPLSd-CsfrJECG_sG-pBxIQrLWur2Rx1aElVsWiY0cMYWzF9VLIlpZv1WVDPV-36YX8oukuBJ5P3Aji9_ukZbcqD_yr7glQbgAvKhdEAZDp8s3jHN02qqJvh_ALl5UuZINk3arfQ1U74J_Tg-9tGIHu4Cc-CDHQtM0X4OO9MLwkXadVadxSbplmoagduMBJ_NdCQzRMqwHYEGVKthAbixbvjTktmVz9n_k3oJiA', 'Documentation of a traditional Balinese religious ceremony capturing vibrant colors and spiritual atmosphere.', 'The Aditya Family', 'Oct 2023', 'Ubud, Bali', 'Event Documentation', 'Pura Taman Saraswati', 'A vibrant and spiritual journey documenting the sacred traditions of the Aditya family in Ubud.', NULL, NULL, 'Client', NULL, 'Published', '2026-01-12 08:02:09'),
(3, 'Annual Gala Night', 'annual-gala-night', 'Corporate Event', 'events', 'https://lh3.googleusercontent.com/aida-public/AB6AXuDv8R20lApZ6N-8qgtyGjoeLnF4GeJAtkhnU_O76ZLthxUjOSPlxJ91Jk-CkCt0ahUjRHkCRxWziDLDuOnxvfqOTn83yuwphuMHsQwWMqua2KaUmHkm-hxJy2btXl8GsClMTux62G9_ywEeLkKZaCglLJaWpbw4RO8OSJ_OYI66EIAVYf-8OUXHPeOfVeIvwpDfvtIfusQEwLwdC3Fk6TjJRy1sJQCB1jpEzO0EEBcqxaAUydojyM05b0jznMpupF7waHnApIkZJo8', 'Comprehensive coverage of the annual corporate gala from red carpet to keynote speeches.', 'TechCorp Inc.', 'Sep 2023', 'Surabaya', 'Event Photography', 'Grand City Hall', 'Capturing the elegance and networking moments of the annual tech summit gala dinner.', NULL, NULL, 'Client', NULL, 'Published', '2026-01-12 08:02:09'),
(4, 'Forever Details', 'forever-details', 'Wedding Photography', 'weddings', 'https://lh3.googleusercontent.com/aida-public/AB6AXuAmRBbm-9TGCiuGB9Tb_12c2EBw6BNBoFlyWg4nQMHjgKjae1SzeXaJmDbg6rtgJyTsbKfYDMdtj2h6YZdmsPZaRIViiiY2OQtlyFTEhqqDqudsm9UqHX-nencwyWNc3INeCyBi897x17_YxT38HP4jkI7kGX7jzuqaZhQgsKyz14rshcoJeBWB23OYps2tb2-heUv10u2pISiuCHCrWxtXwVZgREGBHGJe5kfjbZHOiFXEmMrHtoJ1F4dDCvf8xgXAj-37LF87PzE', 'Intricate details of the wedding day, from rings to floral arrangements.', 'Emily & Tom', 'Dec 2023', 'Bandung', 'Macro Photography', 'The Trans Luxury Hotel', 'Focusing on the small details that make up the big picture: rings, flowers, and textures.', NULL, NULL, 'Client', NULL, 'Published', '2026-01-12 08:02:09'),
(5, 'Summer Beats', 'summer-beats', 'Music Festival', 'events', 'https://lh3.googleusercontent.com/aida-public/AB6AXuAVcXl3FxTmQyjRgXvTafvkvzko16phEZrcSoUwCoqzmD2_ciC_-uDJuQC01QvHwtsPK8L-eQT0AShPsHWw4LfDR-i9ofHYvSsBe3Z4u1hlyy8W5ScDyUvcAJhLKKb-N5A_7B-Z71nNYH4lI76ZTqRhZB02cTz9j1ffDgMKAJ3beaDqlYangEUNUYqTSbOH1M_8QOKBxHnDfGxjkdF3508KYrMIS0gmHTENUA6BqJhX0Xqlk7x-AzlmWWMjaDccvIr2xZ8EQFB0GzQ', 'Capturing the energy and excitement of the Summer Beats music festival.', 'Summer Fest Org', 'July 2023', 'Bali', 'Festival Coverage', 'GWK Cultural Park', 'High energy documentation of the biggest summer festival in Bali, focusing on crowd interaction and stage performance.', NULL, NULL, 'Client', NULL, 'Published', '2026-01-12 08:02:09'),
(6, 'Sacred Blessings', 'sacred-blessings', 'Religious Ceremony', 'religious', 'https://lh3.googleusercontent.com/aida-public/AB6AXuDRlOJ542LCkneAV31azyn0Ht8FZz4YqKJDIEuxPp4kX7VP_6V5ZYsVDwPxnoQUTh25imtICZGM00OP8IK08UUXUC5t5qDCznGoXH-_lHZS851euL2-mBT_RrOLuTq-TPPRgkl1AboJDOzmZnm387BQ7BSI2Dx2xUkn5wQCLaccg9SuTK_CkvKqh7rSN8vq9BZwR0m9qlSPiPPT7YciN-yrdecQSRr5hqWyNsLvZXzMioksqBix5NAZcy0tOoDJ69BOpM79_KamMNc', 'A quiet moment of prayer and reflection captured during a religious procession.', 'Temple Community', 'May 2023', 'Yogyakarta', 'Documentary', 'Prambanan Temple', 'A solemn documentation of the Vesak day procession.', NULL, NULL, 'Client', NULL, 'Published', '2026-01-12 08:02:09'),
(7, 'The Night', 'the-night', 'Wedding Photography', 'weddings', 'assets/uploads/1768206146_Frame 14.jpg', 'Anjay banget kak', 'jiers', '2026-01-12', 'dontol', 'Photography, Art Direction', 'Private Location', NULL, NULL, NULL, 'Client', NULL, 'Published', '2026-01-12 08:22:26');

-- --------------------------------------------------------

--
-- Table structure for table `project_gallery`
--

CREATE TABLE `project_gallery` (
  `id` int NOT NULL,
  `project_id` int NOT NULL,
  `image_url` text COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `project_gallery`
--

INSERT INTO `project_gallery` (`id`, `project_id`, `image_url`) VALUES
(1, 1, 'https://lh3.googleusercontent.com/aida-public/AB6AXuCtk0wGbbjfAOGWZnzUxuPGqfBwo3M7Ev2bZx6AayTNoDYQgrFEYNrQEXP74pvzJxAv4tEYF7PzkfdpsK20b5z8YeXMjPxtkilXNrqBqntQbP9ib60TceUj2EhBu55m318jwNgq0kV-ofAgWKwApSug47Q92sAUCCVWknekYDooFx3U-U0RBp69ZqxGFgo6JZ3PSpAmKEUIhXt0bMOEM3_5KZspWlLhfolly3LhWG-Eig2oyQzf-v6jRCay2i1-bMD7jdoDfZkcVC0'),
(2, 1, 'https://lh3.googleusercontent.com/aida-public/AB6AXuAmRBbm-9TGCiuGB9Tb_12c2EBw6BNBoFlyWg4nQMHjgKjae1SzeXaJmDbg6rtgJyTsbKfYDMdtj2h6YZdmsPZaRIViiiY2OQtlyFTEhqqDqudsm9UqHX-nencwyWNc3INeCyBi897x17_YxT38HP4jkI7kGX7jzuqaZhQgsKyz14rshcoJeBWB23OYps2tb2-heUv10u2pISiuCHCrWxtXwVZgREGBHGJe5kfjbZHOiFXEmMrHtoJ1F4dDCvf8xgXAj-37LF87PzE'),
(3, 1, 'https://lh3.googleusercontent.com/aida-public/AB6AXuAmRBbm-9TGCiuGB9Tb_12c2EBw6BNBoFlyWg4nQMHjgKjae1SzeXaJmDbg6rtgJyTsbKfYDMdtj2h6YZdmsPZaRIViiiY2OQtlyFTEhqqDqudsm9UqHX-nencwyWNc3INeCyBi897x17_YxT38HP4jkI7kGX7jzuqaZhQgsKyz14rshcoJeBWB23OYps2tb2-heUv10u2pISiuCHCrWxtXwVZgREGBHGJe5kfjbZHOiFXEmMrHtoJ1F4dDCvf8xgXAj-37LF87PzE');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `sku` varchar(50) DEFAULT NULL,
  `type` enum('Photography','Videography','Documentary') NOT NULL,
  `price` decimal(15,2) NOT NULL,
  `price_unit` varchar(50) DEFAULT 'Per Event',
  `status` enum('Active','Hidden') DEFAULT 'Active',
  `image_url` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `name`, `sku`, `type`, `price`, `price_unit`, `status`, `image_url`, `created_at`) VALUES
(1, 'Wedding Photography Full Day', '#SRV-WED-001', 'Photography', '8500000.00', 'Per Day', 'Active', 'https://lh3.googleusercontent.com/aida-public/AB6AXuBP15uT8-LRfsnEHGMBzUrQdWnZo70Yqr22XC69HrpasGc9Go74iWF4hUkHB7hTqPvpzRLLrpvJ3QIfy8Pl4Ll4ZrPQUzNseRI-V30NXM2arYHSZJtlr92FIiB3rT2qlyFIyLgxNulcoz9l8yIUjmHUYbiXrvGiYAnUHEJaIxOBcPkV9VAgFlmWRw25hydbYB9nfTaEdNYybprmyOdAu11wK4RQ1VC6ck7cKLCkE3Fi6SEZaRlnLo3TIjpZgcvxQQV5itL-IMlRFY0', '2026-01-13 13:25:09'),
(2, 'Religious Ceremony Package', '#SRV-CER-002', 'Documentary', '4000000.00', 'Per Event', 'Hidden', 'https://lh3.googleusercontent.com/aida-public/AB6AXuCvx9793rPEm77KZZbwDP566EGzn8O6Pq3IW_51pd4ywiK4VmEN84_HUqYS9abyA3VupdKlalbWEv_TkOkwhRrLIVh4QXIlqhv4jh2_OZUvnu9bZKEmguLsLMKwLGhscd82JB6_pSZxe82lvsTpfVmhovTzGUcWaGjo4yjcB7Bb69mFv7llPAxvhpduqwocTC0i1Uo4idK_NBURglv6xOKS0NEnJ8JwCyeF2okeHLZcqLnTejfDvE5x7kcLyVKrpHE1O7HiuJf-XCc', '2026-01-13 13:25:09'),
(3, 'Corporate Event Video Highlight', '#SRV-EVT-003', 'Videography', '6500000.00', 'Per Project', 'Active', 'https://lh3.googleusercontent.com/aida-public/AB6AXuDNrc1Nc6riI37_tTb2fFiCZ7WbjrXo4qRC62l0mPp4dzhY7_3vz5YniBQTay6uIL3sKeRumc0bNoPctJzd-AAmVs7cHOW2XC87kKYv2idLbOdhCDRAmlMobC5My_f7-t7wNxL9UQa-JiTR-qNDgf5nvpi52uxeG1ue6bczIGSYafsUjRH4seW3TXsqn5VmxqNTPVKCuqDqUDjwnF73jrFht1AZwwXTpNwN9EW5KmwlixPhZSRlvkYdpBXUWzFTuWylI93BpsNZ5J0', '2026-01-13 13:25:09'),
(4, 'Pre-wedding / Engagement Session', '#SRV-ENG-004', 'Photography', '3500000.00', '4 Hours', 'Active', 'https://lh3.googleusercontent.com/aida-public/AB6AXuDc_CmTt6gNreVor97Yh3XS3h6tlvB1tx5DQt1aehFURaBOkvei9HOxaPICpdNJ7LcV5bFAU2BkT8JesBwNMTixepJxLy-ycbXjWEHcytbGjttyuvWB8TvlIZ0KI78uViDwpQ6Lt1ygtmHlI-7PfMsQ1j1mft8-tnSwFaG1t9LLz7c2eLkJFQ4iLnX2Fg-S83XMH41KAXT9LkDbexCVM7CUq8COGacHDLmVNHWIzfbjs9V0T5gxrlDHcIlowAdHwhPZLgEhnLnnftE', '2026-01-13 13:25:09');

-- --------------------------------------------------------

--
-- Table structure for table `videos`
--

CREATE TABLE `videos` (
  `id` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `category` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `thumbnail_url` text COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `videos`
--

INSERT INTO `videos` (`id`, `title`, `category`, `thumbnail_url`) VALUES
(1, 'Sarah & Mike\'s Wedding', 'Wedding Film', 'https://lh3.googleusercontent.com/aida-public/AB6AXuCWDtUfImgY9i2ixmssQoPUZV2DzPAcQbmu82KaTJie7EmwRK_pR7OLyNVcQpV1MvCvqUmOtBtvvrVu3J6Aogg6y8JCN1hoov3OAIQ67wEEZNkP_ZGW9trpuNC22FYW1tIBeTVSJtkf-DUvnCKcSYoA5wyzQJoCGcC8COdClKc0r20tuO2SOroS2VUaQfjwK9AyQfxyaFoPHehZjjWqym032OJKWSXojg6RvvPyX7yjTTBeiB67R_U0zPT34t27_LOILCb62ufv8qk'),
(2, 'Tech Summit 2023', 'Event Highlight', 'https://lh3.googleusercontent.com/aida-public/AB6AXuBkg76gmDkLYpIs5mQDT0HgOoKisifrsnFywqqMNJlfoFnYEQiyuR4jPQ9B1NOnh07-8cMIUOzVAFkvPxPW_qHjFg817TqKw00U7WCd43RdYQU-lp9OnRQpO2J8NwnGMypZZS56Fk-Rk_WCSvl1oKr1Eey5UZhMb6d0F9hLiL6lqTXt9tI-hCqSFiLEQfklh_-b-DMf90BW8JiQ5tj4KO2AfgtjK3RjJKWyvsRRyA4M5iRiQN3AiJxtrAI8ds5MGyyiACLBw4F2rSs'),
(3, 'Temple Rituals', 'Documentary', 'https://lh3.googleusercontent.com/aida-public/AB6AXuBFGPCLp1_M1esjy_gdLQpbTMbAz4R0fndfoD4vd3NujyyfpszVFTsC9iLzUNLhd3lEp7C8ZejFAIX84JBpsDnlAYFRgIBRCF0DAh7I97wOumWdlebjSdBD8r3bisL-9OyhKp1QriK4UqcPl0H3pH8NEeVNNKQlN8hdvCSHSw5UxFJq_2OzsUnHa3qckHwFT8x6X3DypPxWCATq3MLz-08MpEUY9cmYD9zQzG80NMzesgZrN_meuA1gXYDC6QmFFFQC1GFZ9wlpOFs');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `equipments`
--
ALTER TABLE `equipments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `serial_number` (`serial_number`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invoice_number` (`invoice_number`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `project_gallery`
--
ALTER TABLE `project_gallery`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_id` (`project_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `videos`
--
ALTER TABLE `videos`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `equipments`
--
ALTER TABLE `equipments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `project_gallery`
--
ALTER TABLE `project_gallery`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `videos`
--
ALTER TABLE `videos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `project_gallery`
--
ALTER TABLE `project_gallery`
  ADD CONSTRAINT `project_gallery_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
