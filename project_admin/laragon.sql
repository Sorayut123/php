-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 03, 2025 at 05:50 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `laragon`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `category_name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `category_name`, `created_at`) VALUES
(7, 'เว็บไซต์', '2025-06-27 08:22:48'),
(8, 'ระบบจัดการ', '2025-06-27 08:23:26'),
(9, 'แอปพลิเคชัน', '2025-06-27 08:23:36');

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE `members` (
  `id` int(11) NOT NULL,
  `MemberID` varchar(20) NOT NULL,
  `FullName` varchar(100) NOT NULL,
  `ProfileImage` varchar(255) DEFAULT NULL,
  `Username` varchar(50) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `Telephone` varchar(20) DEFAULT NULL,
  `BirthDate` date DEFAULT NULL,
  `Occupation` varchar(100) DEFAULT NULL,
  `Address` text DEFAULT NULL,
  `District` varchar(100) DEFAULT NULL,
  `SubDistrict` varchar(100) DEFAULT NULL,
  `Province` varchar(100) DEFAULT NULL,
  `ZipCode` varchar(10) DEFAULT NULL,
  `role_id` int(11) DEFAULT NULL,
  `Gender` varchar(10) NOT NULL DEFAULT 'อื่น ๆ'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`id`, `MemberID`, `FullName`, `ProfileImage`, `Username`, `Password`, `Email`, `Telephone`, `BirthDate`, `Occupation`, `Address`, `District`, `SubDistrict`, `Province`, `ZipCode`, `role_id`, `Gender`) VALUES
(1, '', 'admin som', 'profile_685faf7a2a4ee8.38664821.png', 'admin', '$2y$10$PW/fOhl3kas6O5aRMaGXau.AocTC54bW/KUmO3CTRhRZ.da1PKoVO', 'admin@gmail.com', '0123456789', '2000-05-11', NULL, NULL, NULL, NULL, NULL, NULL, 1, 'ชาย'),
(6, 'MB17508427247246', 'some', 'profile_6860cbcad5b925.71562050.png', 'ss', '$2y$10$jmh0XwOvWDQAQAiT9Pqe/O8gPPD/1RysKMT8/Zd.fmMnnGfIufLge', 's@gmail.com', '0123456789', '2025-06-01', 'นักดาบ', '123/45 หมู่บ้านพฤกษา', 'บางบัวทอง', 'ลำโพ', 'นนทบุรี', '11100', 2, 'ชาย'),
(9, 'MB17509559066944', 'som01', NULL, 'gg', '$2y$10$1R7i8BgXonwQSALNCYd/5.Dhi7vRkl3Gto7z0pMfiVDicBFpxwq3O', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, 'อื่น ๆ');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `role_id` int(11) NOT NULL,
  `role_name` varchar(50) NOT NULL,
  `role_description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`role_id`, `role_name`, `role_description`) VALUES
(1, 'admin', 'ผู้ดูแลระบบ'),
(2, 'expert', 'ผู้เชี่ยวชาญ'),
(3, 'member', 'สมาชิกทั่วไป');

-- --------------------------------------------------------

--
-- Table structure for table `training_projects`
--

CREATE TABLE `training_projects` (
  `id` int(10) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `date` date DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `display_order` int(11) DEFAULT NULL,
  `status` enum('open','closed','in_progress','completed') DEFAULT 'open',
  `project_status` enum('open','closed','in_progress') DEFAULT 'open'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `training_projects`
--

INSERT INTO `training_projects` (`id`, `title`, `description`, `date`, `location`, `image`, `display_order`, `status`, `project_status`) VALUES
(12, 'ggg', 'ef', '2025-07-05', 'acsa', 'profile_68669a9f6cddf8.92337938.png', 2, 'open', 'open'),
(15, 'พเ', 'พะ', '2025-07-11', 'พเพ', 'profile_68669a98d42166.57980050.png', 4, 'closed', 'open'),
(19, 'ssss', 'ss', '2025-07-04', 'ss', 'profile_68669aafa66839.50224373.png', 3, 'open', 'open'),
(22, 'อบรม HTML/CSS', 'เรียนรู้การสร้างหน้าเว็บด้วย HTML และตกแต่งด้วย CSS สำหรับผู้เริ่มต้น', '2025-07-03', 'ห้องปฏิบัติการคอมพิวเตอร์ ', 'profile_6866a306a39e65.18186361.png', 1, 'open', 'open'),
(23, 'ไกฟไ', 'กไก', '2025-07-18', 'ไกก', 'profile_6866a697f3d0c6.61980641.png', NULL, 'open', 'open');

-- --------------------------------------------------------

--
-- Table structure for table `videos`
--

CREATE TABLE `videos` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `video_type` enum('upload','youtube') NOT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `youtube_url` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `videos`
--

INSERT INTO `videos` (`id`, `title`, `description`, `video_type`, `file_path`, `youtube_url`, `created_at`, `updated_at`) VALUES
(2, 'PHP Admin Panel - Create users', '...', 'youtube', NULL, 'https://youtu.be/N4XTDhLwbys?si=yRAh_bOyn9jyRgM_', '2025-06-28 18:31:42', '2025-06-28 23:31:42'),
(3, 'การพัฒนาเว็บเบื้องต้น', '..', 'youtube', NULL, 'https://youtu.be/8JJ101D3knE?si=5rm2u6t7nUbvhcQ3', '2025-06-28 18:35:57', '2025-06-28 23:35:57'),
(5, 'การพัฒนาเว็บเบื้องต้น มังนะ', '...', 'youtube', NULL, 'https://youtu.be/n_T-9K1SSq4?si=eFQhfOV4wdOskhTw', '2025-06-28 18:58:23', '2025-06-29 00:00:14');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `category_name` (`category_name`);

--
-- Indexes for table `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `MemberID` (`MemberID`),
  ADD UNIQUE KEY `Username` (`Username`),
  ADD KEY `role_id` (`role_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`role_id`),
  ADD UNIQUE KEY `role_name` (`role_name`);

--
-- Indexes for table `training_projects`
--
ALTER TABLE `training_projects`
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
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `training_projects`
--
ALTER TABLE `training_projects`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `videos`
--
ALTER TABLE `videos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `members`
--
ALTER TABLE `members`
  ADD CONSTRAINT `members_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
