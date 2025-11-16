-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 16, 2025 at 11:28 AM
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
-- Database: `aces_flutter_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `profile_image` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `committee_members`
--

CREATE TABLE `committee_members` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `role` varchar(100) NOT NULL,
  `year` varchar(20) DEFAULT NULL,
  `branch` varchar(100) DEFAULT NULL,
  `skills` text DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `academic_year` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `committee_members`
--

INSERT INTO `committee_members` (`id`, `name`, `role`, `year`, `branch`, `skills`, `photo`, `academic_year`, `created_at`) VALUES
(1, 'Aditya Nanaware', 'General Secretary', 'TE', 'Computer', 'Communication,Coding', 'members_6822ec3cb34e2_scaled_gs.jpg', '2024-25', '2025-05-12 10:57:55'),
(2, 'Roman Shaikh', 'President', 'TE', 'Computer', 'Leadership, Communication', 'members1747047823_scaled_1000298747.jpg', '2024-25', '2025-05-12 11:03:43');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `event_date` date NOT NULL,
  `location` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `title`, `description`, `event_date`, `location`, `image`, `created_at`) VALUES
(2, 'Label Technology Show 2025', 'The Label Technology Show showcases innovations in labeling, featuring technologies like Label Printing Machinery, RFID Labels, and Anti-counterfeiting, while providing networking opportunities for industry leaders and experts.', '2025-05-29', 'Pune International Exhibition and Convention CenterMR6R+PQM Sector No. 5 Moshi Pimpri-Chinchwad Maharashtra 412105 IndiaIndia', '1747202407_1000299680.jpg', '2025-05-13 21:14:03');

-- --------------------------------------------------------

--
-- Table structure for table `event_budgets`
--

CREATE TABLE `event_budgets` (
  `id` int(11) NOT NULL,
  `event_id` int(11) DEFAULT NULL,
  `budget_amount` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `event_budgets`
--

INSERT INTO `event_budgets` (`id`, `event_id`, `budget_amount`, `created_at`) VALUES
(1, 1, 2500.00, '2025-05-14 06:41:43'),
(2, 2, 15000.00, '2025-05-16 10:49:36');

-- --------------------------------------------------------

--
-- Table structure for table `event_expenses`
--

CREATE TABLE `event_expenses` (
  `id` int(11) NOT NULL,
  `event_id` int(11) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `expense_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `event_expenses`
--

INSERT INTO `event_expenses` (`id`, `event_id`, `description`, `amount`, `expense_date`, `created_at`) VALUES
(1, 1, 'Decoration', 850.00, '2025-05-14', '2025-05-15 11:52:36'),
(2, 1, 'Snack', 1250.00, '2025-05-13', '2025-05-16 05:24:11'),
(3, 2, 'Decoration', 2500.00, '2025-05-15', '2025-05-16 10:56:30'),
(4, 2, 'Snacks', 6000.00, '2025-05-16', '2025-05-16 10:58:51');

-- --------------------------------------------------------

--
-- Table structure for table `event_photos`
--

CREATE TABLE `event_photos` (
  `id` int(11) NOT NULL,
  `event_id` int(11) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `event_registrations`
--

CREATE TABLE `event_registrations` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `event_id` int(11) DEFAULT NULL,
  `registered_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('student','admin') DEFAULT 'student',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `profile_photo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`, `profile_photo`) VALUES
(1, 'Aditya Balasaheb Nanaware', 'aditya1234@gmail.com', '$2b$10$kDZ/wzZf1c1uyoqWwLa2sOl5/AvAh9RJPGtTZzB3oCuqHkznCNZsq', 'admin', '2025-04-08 14:34:55', 'admin_68272926f1f8a0.81489148.jpg'),
(2, 'Digvijay Shamkant Shinde', 'digvijay1234@gmail.com', '$2y$10$DKEbmeJ0.s/R0WuICQSb3uHfTUGrL2/sCanLvUY8HE1zFGOfOVcvC', 'student', '2025-04-08 15:56:33', '1744159146_Nitro_Wallpaper_06_3840x2400.jpg'),
(3, 'Aditya', 'aditya8308@gmail.com', '$2y$10$lH7MRFUxkMsBTg9XlAbj9eCZahrPt0L70Cpu4s40k8Sc2Tp.Lm.dC', 'student', '2025-05-11 17:46:52', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `committee_members`
--
ALTER TABLE `committee_members`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `event_budgets`
--
ALTER TABLE `event_budgets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `event_expenses`
--
ALTER TABLE `event_expenses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `event_photos`
--
ALTER TABLE `event_photos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `event_registrations`
--
ALTER TABLE `event_registrations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `event_id` (`event_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `committee_members`
--
ALTER TABLE `committee_members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `event_budgets`
--
ALTER TABLE `event_budgets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `event_expenses`
--
ALTER TABLE `event_expenses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `event_photos`
--
ALTER TABLE `event_photos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `event_registrations`
--
ALTER TABLE `event_registrations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `event_registrations`
--
ALTER TABLE `event_registrations`
  ADD CONSTRAINT `event_registrations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `event_registrations_ibfk_2` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
