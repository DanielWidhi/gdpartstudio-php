-- phpMyAdmin SQL Dump
-- Version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 12, 2026 at 10:00 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

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
CREATE DATABASE IF NOT EXISTS `gdpartstudio` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `gdpartstudio`;

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

DROP TABLE IF EXISTS `admins`;
CREATE TABLE `admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
-- Password default: admin123
--

INSERT INTO `admins` (`id`, `name`, `email`, `password`) VALUES
(1, 'Super Admin', 'admin@gdpartstudio.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

DROP TABLE IF EXISTS `projects`;
CREATE TABLE `projects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `category_display` varchar(255) NOT NULL COMMENT 'Contoh: Wedding Photography',
  `filter_tag` varchar(50) NOT NULL COMMENT 'Contoh: weddings, religious, events',
  `image_url` text NOT NULL,
  `description` text DEFAULT NULL,
  `client_name` varchar(100) DEFAULT NULL,
  `event_date` varchar(50) DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `services` varchar(255) DEFAULT 'Photography, Art Direction',
  `venue` varchar(255) DEFAULT 'Private Location',
  `concept_text` text DEFAULT NULL,
  `testimonial_quote` text DEFAULT NULL,
  `testimonial_author` varchar(100) DEFAULT NULL,
  `testimonial_role` varchar(100) DEFAULT 'Client',
  `video_thumbnail_url` text DEFAULT NULL,
  `status` enum('Published','Draft','Archived') DEFAULT 'Published',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `title`, `slug`, `category_display`, `filter_tag`, `image_url`, `description`, `client_name`, `event_date`, `location`, `services`, `venue`, `concept_text`, `testimonial_quote`, `testimonial_author`, `testimonial_role`, `video_thumbnail_url`, `status`) VALUES
(1, 'Intimate Vows', 'intimate-vows', 'Wedding Photography', 'weddings', 'https://lh3.googleusercontent.com/aida-public/AB6AXuCtk0wGbbjfAOGWZnzUxuPGqfBwo3M7Ev2bZx6AayTNoDYQgrFEYNrQEXP74pvzJxAv4tEYF7PzkfdpsK20b5z8YeXMjPxtkilXNrqBqntQbP9ib60TceUj2EhBu55m318jwNgq0kV-ofAgWKwApSug47Q92sAUCCVWknekYDooFx3U-U0RBp69ZqxGFgo6JZ3PSpAmKEUIhXt0bMOEM3_5KZspWlLhfolly3LhWG-Eig2oyQzf-v6jRCay2i1-bMD7jdoDfZkcVC0', 'A beautiful, intimate wedding ceremony capturing the raw emotions and sacred promises between the couple.', 'Sarah & James', 'Nov 14, 2023', 'Jakarta, Indonesia', 'Photography, Art Direction', 'Private Garden Estate', 'For Sarah and James, the focus was entirely on authenticity. They wanted a documentation style that felt less like a production and more like a memory unfolding in real-time.\r\n\r\nWe utilized natural light throughout the ceremony to maintain the soft, romantic atmosphere of the garden setting.', 'GDPARTSTUDIO was the best investment we made for our wedding. The team was professional, unobtrusive, and the final gallery brought tears to our eyes.', 'Sarah Jenkins', 'The Bride', 'https://lh3.googleusercontent.com/aida-public/AB6AXuCWDtUfImgY9i2ixmssQoPUZV2DzPAcQbmu82KaTJie7EmwRK_pR7OLyNVcQpV1MvCvqUmOtBtvvrVu3J6Aogg6y8JCN1hoov3OAIQ67wEEZNkP_ZGW9trpuNC22FYW1tIBeTVSJtkf-DUvnCKcSYoA5wyzQJoCGcC8COdClKc0r20tuO2SOroS2VUaQfjwK9AyQfxyaFoPHehZjjWqym032OJKWSXojg6RvvPyX7yjTTBeiB67R_U0zPT34t27_LOILCb62ufv8qk', 'Published'),
(2, 'Balinese Ceremony', 'balinese-ceremony', 'Religious Documentation', 'religious', 'https://lh3.googleusercontent.com/aida-public/AB6AXuCINcFoh412PyqaBa9ygkfrVO8NI56thVzFLMabsPLSd-CsfrJECG_sG-pBxIQrLWur2Rx1aElVsWiY0cMYWzF9VLIlpZv1WVDPV-36YX8oukuBJ5P3Aji9_ukZbcqD_yr7glQbgAvKhdEAZDp8s3jHN02qqJvh_ALl5UuZINk3arfQ1U74J_Tg-9tGIHu4Cc-CDHQtM0X4OO9MLwkXadVadxSbplmoagduMBJ_NdCQzRMqwHYEGVKthAbixbvjTktmVz9n_k3oJiA', 'Documentation of a traditional Balinese religious ceremony capturing vibrant colors and spiritual atmosphere.', 'The Aditya Family', 'Oct 2023', 'Ubud, Bali', 'Event Documentation', 'Pura Taman Saraswati', 'A vibrant and spiritual journey documenting the sacred traditions of the Aditya family in Ubud.', NULL, NULL, 'Client', NULL, 'Published'),
(3, 'Annual Gala Night', 'annual-gala-night', 'Corporate Event', 'events', 'https://lh3.googleusercontent.com/aida-public/AB6AXuDv8R20lApZ6N-8qgtyGjoeLnF4GeJAtkhnU_O76ZLthxUjOSPlxJ91Jk-CkCt0ahUjRHkCRxWziDLDuOnxvfqOTn83yuwphuMHsQwWMqua2KaUmHkm-hxJy2btXl8GsClMTux62G9_ywEeLkKZaCglLJaWpbw4RO8OSJ_OYI66EIAVYf-8OUXHPeOfVeIvwpDfvtIfusQEwLwdC3Fk6TjJRy1sJQCB1jpEzO0EEBcqxaAUydojyM05b0jznMpupF7waHnApIkZJo8', 'Comprehensive coverage of the annual corporate gala from red carpet to keynote speeches.', 'TechCorp Inc.', 'Sep 2023', 'Surabaya', 'Event Photography', 'Grand City Hall', 'Capturing the elegance and networking moments of the annual tech summit gala dinner.', NULL, NULL, 'Client', NULL, 'Published'),
(4, 'Forever Details', 'forever-details', 'Wedding Photography', 'weddings', 'https://lh3.googleusercontent.com/aida-public/AB6AXuAmRBbm-9TGCiuGB9Tb_12c2EBw6BNBoFlyWg4nQMHjgKjae1SzeXaJmDbg6rtgJyTsbKfYDMdtj2h6YZdmsPZaRIViiiY2OQtlyFTEhqqDqudsm9UqHX-nencwyWNc3INeCyBi897x17_YxT38HP4jkI7kGX7jzuqaZhQgsKyz14rshcoJeBWB23OYps2tb2-heUv10u2pISiuCHCrWxtXwVZgREGBHGJe5kfjbZHOiFXEmMrHtoJ1F4dDCvf8xgXAj-37LF87PzE', 'Intricate details of the wedding day, from rings to floral arrangements.', 'Emily & Tom', 'Dec 2023', 'Bandung', 'Macro Photography', 'The Trans Luxury Hotel', 'Focusing on the small details that make up the big picture: rings, flowers, and textures.', NULL, NULL, 'Client', NULL, 'Published'),
(5, 'Summer Beats', 'summer-beats', 'Music Festival', 'events', 'https://lh3.googleusercontent.com/aida-public/AB6AXuAVcXl3FxTmQyjRgXvTafvkvzko16phEZrcSoUwCoqzmD2_ciC_-uDJuQC01QvHwtsPK8L-eQT0AShPsHWw4LfDR-i9ofHYvSsBe3Z4u1hlyy8W5ScDyUvcAJhLKKb-N5A_7B-Z71nNYH4lI76ZTqRhZB02cTz9j1ffDgMKAJ3beaDqlYangEUNUYqTSbOH1M_8QOKBxHnDfGxjkdF3508KYrMIS0gmHTENUA6BqJhX0Xqlk7x-AzlmWWMjaDccvIr2xZ8EQFB0GzQ', 'Capturing the energy and excitement of the Summer Beats music festival.', 'Summer Fest Org', 'July 2023', 'Bali', 'Festival Coverage', 'GWK Cultural Park', 'High energy documentation of the biggest summer festival in Bali, focusing on crowd interaction and stage performance.', NULL, NULL, 'Client', NULL, 'Published'),
(6, 'Sacred Blessings', 'sacred-blessings', 'Religious Ceremony', 'religious', 'https://lh3.googleusercontent.com/aida-public/AB6AXuDRlOJ542LCkneAV31azyn0Ht8FZz4YqKJDIEuxPp4kX7VP_6V5ZYsVDwPxnoQUTh25imtICZGM00OP8IK08UUXUC5t5qDCznGoXH-_lHZS851euL2-mBT_RrOLuTq-TPPRgkl1AboJDOzmZnm387BQ7BSI2Dx2xUkn5wQCLaccg9SuTK_CkvKqh7rSN8vq9BZwR0m9qlSPiPPT7YciN-yrdecQSRr5hqWyNsLvZXzMioksqBix5NAZcy0tOoDJ69BOpM79_KamMNc', 'A quiet moment of prayer and reflection captured during a religious procession.', 'Temple Community', 'May 2023', 'Yogyakarta', 'Documentary', 'Prambanan Temple', 'A solemn documentation of the Vesak day procession.', NULL, NULL, 'Client', NULL, 'Published');

-- --------------------------------------------------------

--
-- Table structure for table `project_gallery`
--

DROP TABLE IF EXISTS `project_gallery`;
CREATE TABLE `project_gallery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL,
  `image_url` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `project_id` (`project_id`),
  CONSTRAINT `project_gallery_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE
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
-- Table structure for table `videos`
--

DROP TABLE IF EXISTS `videos`;
CREATE TABLE `videos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `thumbnail_url` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `videos`
--

INSERT INTO `videos` (`id`, `title`, `category`, `thumbnail_url`) VALUES
(1, 'Sarah & Mike\'s Wedding', 'Wedding Film', 'https://lh3.googleusercontent.com/aida-public/AB6AXuCWDtUfImgY9i2ixmssQoPUZV2DzPAcQbmu82KaTJie7EmwRK_pR7OLyNVcQpV1MvCvqUmOtBtvvrVu3J6Aogg6y8JCN1hoov3OAIQ67wEEZNkP_ZGW9trpuNC22FYW1tIBeTVSJtkf-DUvnCKcSYoA5wyzQJoCGcC8COdClKc0r20tuO2SOroS2VUaQfjwK9AyQfxyaFoPHehZjjWqym032OJKWSXojg6RvvPyX7yjTTBeiB67R_U0zPT34t27_LOILCb62ufv8qk'),
(2, 'Tech Summit 2023', 'Event Highlight', 'https://lh3.googleusercontent.com/aida-public/AB6AXuBkg76gmDkLYpIs5mQDT0HgOoKisifrsnFywqqMNJlfoFnYEQiyuR4jPQ9B1NOnh07-8cMIUOzVAFkvPxPW_qHjFg817TqKw00U7WCd43RdYQU-lp9OnRQpO2J8NwnGMypZZS56Fk-Rk_WCSvl1oKr1Eey5UZhMb6d0F9hLiL6lqTXt9tI-hCqSFiLEQfklh_-b-DMf90BW8JiQ5tj4KO2AfgtjK3RjJKWyvsRRyA4M5iRiQN3AiJxtrAI8ds5MGyyiACLBw4F2rSs'),
(3, 'Temple Rituals', 'Documentary', 'https://lh3.googleusercontent.com/aida-public/AB6AXuBFGPCLp1_M1esjy_gdLQpbTMbAz4R0fndfoD4vd3NujyyfpszVFTsC9iLzUNLhd3lEp7C8ZejFAIX84JBpsDnlAYFRgIBRCF0DAh7I97wOumWdlebjSdBD8r3bisL-9OyhKp1QriK4UqcPl0H3pH8NEeVNNKQlN8hdvCSHSw5UxFJq_2OzsUnHa3qckHwFT8x6X3DypPxWCATq3MLz-08MpEUY9cmYD9zQzG80NMzesgZrN_meuA1gXYDC6QmFFFQC1GFZ9wlpOFs');

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;