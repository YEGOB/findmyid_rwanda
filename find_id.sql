-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 29, 2025 at 04:29 PM
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
-- Database: `find_id`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `image`) VALUES
(6, 'umuhire manzi delphin', '1745775959_i.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `conversations`
--

CREATE TABLE `conversations` (
  `id` int(11) NOT NULL,
  `user_one_id` int(11) NOT NULL,
  `user_two_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `found_ids`
--

CREATE TABLE `found_ids` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `id_number` varchar(50) DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `location_found` varchar(100) DEFAULT NULL,
  `date_found` date DEFAULT NULL,
  `posted_by` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `phone_number` int(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `is_taken` tinyint(1) DEFAULT 0,
  `taken_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `found_ids`
--

INSERT INTO `found_ids` (`id`, `full_name`, `id_number`, `category`, `image`, `location_found`, `date_found`, `posted_by`, `created_at`, `phone_number`, `description`, `is_taken`, `taken_at`) VALUES
(20, 'manzi bazzy', '20073', 'student id', 'card.jpg', 'Kirehe', '2025-04-28', 'kalisa', '2025-04-28 18:43:50', 736322662, 'ibisobanuro', 1, '2025-04-28 19:37:39'),
(28, 'umuhire', '111', 'national id', '1745683769_i.jpg', 'Nyabihu', '2025-04-29', 'manzi bazzy', '2025-04-29 12:24:40', 736322662, 'kumuhanda', 0, '2025-04-29 12:24:40'),
(29, 'manzi bazzy', '1234', '', 'cre.jpg', 'Ngoma', '2025-04-29', ' bazzy', '2025-04-29 12:33:56', 736322662, 'mku776', 0, '2025-04-29 12:33:56');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `conversation_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `seeki`
--

CREATE TABLE `seeki` (
  `id` varchar(100) NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone_number` int(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `found_status` tinyint(1) NOT NULL,
  `id_type` varchar(100) DEFAULT NULL,
  `id_number` int(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `notification_sent` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `seeki`
--

INSERT INTO `seeki` (`id`, `name`, `phone_number`, `description`, `date`, `created_at`, `found_status`, `id_type`, `id_number`, `category`, `notification_sent`) VALUES
('123', 'manzi bazzy', 796403913, 'knknkk', '2025-04-29', '2025-04-29 12:34:21', 0, 'National ID', 0, '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `rule` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `created_at`, `rule`) VALUES
(28, 'manzi bazzy', 'manzi@gmail.com', '$2y$10$ENmwaP19HdHTOg8H2444keK7Ey6LnWCQne8sJM/PjbkTZkD5zn/Em', '2025-04-28 12:16:00', 'User'),
(29, 'umuhire manzi delphin', 'bazzy@gmail.com', '$2y$10$l72m8a./mLGZGPVCwbUWbuVYZE57FA8JlbGd.maMTKn4q5eNZPHxm', '2025-04-28 12:17:02', 'User'),
(30, 'bazzy', 'manzidelphin18@gmail.com', '$2y$10$oaGH/TEGECcBx.OkypWCLOOcsqTdQ9LoBqtnA.wKU6MmIH9pZ6OXu', '2025-04-28 14:11:33', 'Admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `conversations`
--
ALTER TABLE `conversations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_one_id` (`user_one_id`),
  ADD KEY `user_two_id` (`user_two_id`);

--
-- Indexes for table `found_ids`
--
ALTER TABLE `found_ids`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `conversation_id` (`conversation_id`),
  ADD KEY `sender_id` (`sender_id`);

--
-- Indexes for table `seeki`
--
ALTER TABLE `seeki`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `conversations`
--
ALTER TABLE `conversations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `found_ids`
--
ALTER TABLE `found_ids`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `conversations`
--
ALTER TABLE `conversations`
  ADD CONSTRAINT `conversations_ibfk_1` FOREIGN KEY (`user_one_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `conversations_ibfk_2` FOREIGN KEY (`user_two_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`conversation_id`) REFERENCES `conversations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
