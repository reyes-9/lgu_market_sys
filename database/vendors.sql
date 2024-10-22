-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 22, 2024 at 04:03 PM
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
-- Database: `vendors`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_type` enum('User','Admin') DEFAULT 'User',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `email`, `password`, `user_type`, `created_at`) VALUES
(1, 'user1@example.com', 'hashed_password_1', 'User', '2024-09-23 00:18:15'),
(2, 'user2@example.com', 'hashed_password_2', 'User', '2024-09-23 00:18:15'),
(3, 'admin@example.com', 'hashed_password_admin', 'Admin', '2024-09-23 00:18:15'),
(4, 'reyes@gmail.com', '$2y$10$.Ouf1nFd3JyEDGxR1kGSturOgPiKIJRNsMMzRCzfkQHX7AbovA5qy', 'User', '2024-09-23 01:52:11'),
(5, 'bn@gmail.com', '$2y$10$dS4FMoqs6HlCS.i.kH9uv.RDlbkEp2NOIC2UVX3TXs2P1sWrqMMPu', 'User', '2024-09-25 12:56:15');

-- --------------------------------------------------------

--
-- Table structure for table `applications`
--

CREATE TABLE `applications` (
  `id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `stall_id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `market_id` int(11) NOT NULL,
  `application_type` enum('stall','stall transfer','stall extension','add helper') NOT NULL,
  `status` enum('Pending','Approved','Denied','') NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `documents`
--

CREATE TABLE `documents` (
  `id` int(11) NOT NULL,
  `application_id` int(11) NOT NULL,
  `document_type` varchar(255) NOT NULL,
  `document_name` varchar(255) NOT NULL,
  `document_path` varchar(255) NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `account_id` int(11) DEFAULT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `login_attempts`
--

CREATE TABLE `login_attempts` (
  `id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `login_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('success','failed') DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `market_locations`
--

CREATE TABLE `market_locations` (
  `id` int(11) NOT NULL,
  `market_name` varchar(255) NOT NULL,
  `market_address` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `market_locations`
--

INSERT INTO `market_locations` (`id`, `market_name`, `market_address`) VALUES
(1, 'Central Market', '123 Main St.'),
(2, 'Eastside Market', '456 East St.'),
(3, 'West End Market', '789 West St.');

-- --------------------------------------------------------

--
-- Table structure for table `profiles`
--

CREATE TABLE `profiles` (
  `id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `stall_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `bio` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `birthdate` date DEFAULT NULL,
  `address` varchar(255) NOT NULL,
  `contact` varchar(11) NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `profiles`
--

INSERT INTO `profiles` (`id`, `account_id`, `stall_id`, `name`, `bio`, `email`, `birthdate`, `address`, `contact`, `profile_picture`, `created_at`) VALUES
(1, 1, NULL, 'User One', '', '', NULL, '', '0', 'path/to/profile_picture_1.jpg', '2024-09-23 00:18:15'),
(2, 2, NULL, 'User Two', '', '', NULL, '', '0', 'path/to/profile_picture_2.jpg', '2024-09-23 00:18:15'),
(3, 3, NULL, 'Admin User', '', '', NULL, '', '0', 'path/to/profile_picture_admin.jpg', '2024-09-23 00:18:15'),
(39, 4, 6, 'Nelson Reyes', 'I.T. Student at BCP Major in Information Management', 'reyes@gmail.com', '2024-10-30', '98 Ambuklao St. Pasong Tamo, Tandang Sora, QC.', '09087527541', NULL, '2024-09-28 13:26:25');

-- --------------------------------------------------------

--
-- Table structure for table `sections`
--

CREATE TABLE `sections` (
  `id` int(11) NOT NULL,
  `section_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sections`
--

INSERT INTO `sections` (`id`, `section_name`) VALUES
(1, 'Meat'),
(2, 'Vegetables'),
(3, 'Fish'),
(4, 'Dry Goods');

-- --------------------------------------------------------

--
-- Table structure for table `stalls`
--

CREATE TABLE `stalls` (
  `id` int(11) NOT NULL,
  `market_id` int(11) DEFAULT NULL,
  `section_id` int(11) NOT NULL,
  `account_id` int(11) DEFAULT NULL,
  `stall_number` int(11) NOT NULL,
  `rental_fee` decimal(10,2) NOT NULL,
  `stall_size` varchar(50) NOT NULL,
  `status` enum('available','occupied','maintenance','pending') DEFAULT 'available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stalls`
--

INSERT INTO `stalls` (`id`, `market_id`, `section_id`, `account_id`, `stall_number`, `rental_fee`, `stall_size`, `status`) VALUES
(1, 1, 1, NULL, 101, 100.00, '10x10 sqft', 'available'),
(2, 1, 2, 4, 102, 80.00, '8x8 sqft', 'available'),
(3, 1, 3, NULL, 103, 120.00, '12x12 sqft', 'available'),
(4, 2, 1, NULL, 201, 110.00, '10x10 sqft', 'available'),
(5, 2, 4, NULL, 202, 90.00, '9x9 sqft', 'available'),
(6, 3, 2, NULL, 301, 85.00, '8x8 sqft', 'available'),
(7, 3, 3, NULL, 302, 130.00, '12x12 sqft', 'available');

-- --------------------------------------------------------

--
-- Table structure for table `support_tickets`
--

CREATE TABLE `support_tickets` (
  `id` int(11) NOT NULL,
  `account_id` int(11) DEFAULT NULL,
  `message` text NOT NULL,
  `status` enum('open','closed','in_progress') DEFAULT 'open',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stall_id` (`stall_id`),
  ADD KEY `fk_section_id` (`section_id`),
  ADD KEY `fk_market_location` (`market_id`),
  ADD KEY `applications_ibfk_1` (`account_id`);

--
-- Indexes for table `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `documents_ibfk_1` (`application_id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`),
  ADD KEY `feedback_ibfk_1` (`account_id`);

--
-- Indexes for table `login_attempts`
--
ALTER TABLE `login_attempts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `account_id` (`account_id`);

--
-- Indexes for table `market_locations`
--
ALTER TABLE `market_locations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `profiles`
--
ALTER TABLE `profiles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `account_id` (`account_id`),
  ADD KEY `stall_id` (`stall_id`);

--
-- Indexes for table `sections`
--
ALTER TABLE `sections`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stalls`
--
ALTER TABLE `stalls`
  ADD PRIMARY KEY (`id`),
  ADD KEY `section_id` (`section_id`),
  ADD KEY `fk_market` (`market_id`),
  ADD KEY `fk_vendor` (`account_id`);

--
-- Indexes for table `support_tickets`
--
ALTER TABLE `support_tickets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `support_tickets_ibfk_1` (`account_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `applications`
--
ALTER TABLE `applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `documents`
--
ALTER TABLE `documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `login_attempts`
--
ALTER TABLE `login_attempts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `market_locations`
--
ALTER TABLE `market_locations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `profiles`
--
ALTER TABLE `profiles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `sections`
--
ALTER TABLE `sections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `stalls`
--
ALTER TABLE `stalls`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `support_tickets`
--
ALTER TABLE `support_tickets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `applications`
--
ALTER TABLE `applications`
  ADD CONSTRAINT `applications_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `applications_ibfk_2` FOREIGN KEY (`stall_id`) REFERENCES `stalls` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_market_location` FOREIGN KEY (`market_id`) REFERENCES `market_locations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_section_id` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `documents`
--
ALTER TABLE `documents`
  ADD CONSTRAINT `documents_ibfk_1` FOREIGN KEY (`application_id`) REFERENCES `applications` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `login_attempts`
--
ALTER TABLE `login_attempts`
  ADD CONSTRAINT `login_attempts_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `profiles`
--
ALTER TABLE `profiles`
  ADD CONSTRAINT `fk_stall` FOREIGN KEY (`stall_id`) REFERENCES `stalls` (`id`),
  ADD CONSTRAINT `profiles_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stalls`
--
ALTER TABLE `stalls`
  ADD CONSTRAINT `fk_market` FOREIGN KEY (`market_id`) REFERENCES `market_locations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_vendor` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `stalls_ibfk_1` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `support_tickets`
--
ALTER TABLE `support_tickets`
  ADD CONSTRAINT `support_tickets_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
