-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 26, 2025 at 06:10 PM
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
  `user_type` enum('Visitor','Admin','Vendor','Inspector') DEFAULT 'Visitor',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `otp_code` varchar(6) DEFAULT NULL,
  `otp_expiry` datetime DEFAULT NULL,
  `is_verified` tinyint(1) DEFAULT 0,
  `otp_sent_count` int(11) DEFAULT 0,
  `last_otp_sent` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `email`, `password`, `user_type`, `created_at`, `otp_code`, `otp_expiry`, `is_verified`, `otp_sent_count`, `last_otp_sent`) VALUES
(15, 'admin@yahoo.com', '$2y$10$0DaDJodpdYQo1YCbdAHCPuBXtWifxBmd.eFZ7i2qvvoXSoLRzKp6m', 'Admin', '2025-04-03 09:31:21', NULL, NULL, 1, 0, NULL),
(16, 'vendor@yahoo.com', '$2y$10$krM2waP4zCEpJCjsiqvEP.eYblsyBUQM7/LpuA1htsaBTUjP.L1i2', 'Vendor', '2025-04-03 09:32:32', NULL, NULL, 1, 0, NULL),
(17, 'inspector@yahoo.com', '$2y$10$PRxFJ5jqM11RHymSeBNpXODI8EpWe1kBgOaSiA0vy4lmnLhYDFSC.', 'Inspector', '2025-04-03 09:33:13', '344192', '2025-04-17 05:28:48', 1, 1, '2025-04-17 05:23:48'),
(79, 'nreyesmine69@gmail.com', '$2y$10$F97aH2lZObTpmMIHfM5/xOeLyldJB51/GiY0CiFINvPX5.6E8I9Im', 'Visitor', '2025-04-13 11:45:30', NULL, NULL, 1, 1, '2025-04-14 08:01:40'),
(80, 'reyes.nelson.panong@gmail.com', '$2y$10$pmXDj8Rjiga0vdueskFn6.04hCA9p5Stm271DW2o5QdCtEonOWfna', 'Visitor', '2025-04-13 12:55:15', '778498', '2025-04-13 15:00:15', 0, 1, '2025-04-13 14:55:15'),
(81, 'test@yahoo.com', '$2y$10$DZuaCAp8hvhFy9TVIPL/eeZsS6aa83bZRWYAtt5sXqeQBh5ZIzG3O', 'Vendor', '2025-04-17 01:12:58', NULL, NULL, 1, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `admin_logs`
--

CREATE TABLE `admin_logs` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `action` varchar(255) NOT NULL,
  `details` text DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_logs`
--

INSERT INTO `admin_logs` (`id`, `admin_id`, `action`, `details`, `timestamp`) VALUES
(313, 15, 'Logged In', 'IP: ::1', '2025-04-04 00:33:51'),
(314, 15, 'Start Review', 'Started review for application ID: 425', '2025-04-04 07:23:32'),
(315, 15, 'Approved Application', 'Approved application ID: 425', '2025-04-04 07:39:55'),
(316, 17, 'Logged In', 'IP: ::1', '2025-04-04 07:58:44'),
(317, 17, 'Issued Violation', 'Issued Violation ID: 13', '2025-04-04 07:59:50'),
(318, 17, 'Logged In', 'IP: ::1', '2025-04-04 08:43:32'),
(319, 17, 'Issued Violation', 'Issued Violation ID: 14', '2025-04-04 08:46:36'),
(320, 17, 'Logged In', 'IP: ::1', '2025-04-04 13:16:03'),
(321, 17, 'Issued Violation', 'Issued Violation ID: 15', '2025-04-04 13:46:39'),
(322, 17, 'Issued Violation', 'Issued Violation ID: 16', '2025-04-04 14:06:50'),
(323, 15, 'Logged In', 'IP: ::1', '2025-04-13 05:35:07'),
(324, 15, 'Logged In', 'IP: ::1', '2025-04-13 05:41:51'),
(325, 15, 'Logged In', 'IP: ::1', '2025-04-14 10:20:14'),
(326, 15, 'Logged In', 'IP: ::1', '2025-04-14 12:57:59'),
(327, 15, 'Logged In', 'IP: ::1', '2025-04-14 13:05:12'),
(328, 15, 'Logged In', 'IP: ::1', '2025-04-15 00:29:48'),
(329, 15, 'Logged In', 'IP: ::1', '2025-04-17 01:50:27'),
(330, 15, 'Start Review', 'Started review for application ID: 427', '2025-04-17 03:14:43'),
(331, 17, 'Logged In', 'IP: ::1', '2025-04-17 03:24:25'),
(332, 17, 'Logged In', 'IP: ::1', '2025-04-17 03:55:34'),
(333, 15, 'Start Review', 'Started review for application ID: 427', '2025-04-17 04:20:07'),
(334, 15, 'Start Review', 'Started review for application ID: 427', '2025-04-17 04:40:15'),
(335, 15, 'Logged In', 'IP: ::1', '2025-04-17 13:25:42'),
(336, 15, 'Logged In', 'IP: ::1', '2025-04-17 23:19:22'),
(337, 15, 'Start Review', 'Started review for application ID: 427', '2025-04-17 23:19:38'),
(338, 15, 'Start Review', 'Started review for application ID: 432', '2025-04-18 00:44:49'),
(339, 15, 'Start Review', 'Started review for application ID: 432', '2025-04-18 00:45:05'),
(340, 15, 'Start Review', 'Started review for application ID: 432', '2025-04-18 00:45:12'),
(341, 15, 'Start Review', 'Started review for application ID: 432', '2025-04-18 00:52:40'),
(342, 15, 'Approved Application', 'Approved application ID: 432', '2025-04-18 01:03:22'),
(343, 15, 'Start Review', 'Started review for application ID: 433', '2025-04-18 01:05:56'),
(344, 15, 'Start Review', 'Started review for application ID: 427', '2025-04-18 01:08:07'),
(345, 15, 'Start Review', 'Started review for application ID: 433', '2025-04-18 01:08:27'),
(346, 15, 'Start Review', 'Started review for application ID: 427', '2025-04-18 01:11:18'),
(347, 15, 'Start Review', 'Started review for application ID: 433', '2025-04-18 01:14:58'),
(348, 15, 'Start Review', 'Started review for application ID: 433', '2025-04-18 01:26:55'),
(349, 15, 'Start Review', 'Started review for application ID: 435', '2025-04-18 01:57:01'),
(350, 15, 'Start Review', 'Started review for application ID: 435', '2025-04-18 02:26:16'),
(351, 15, 'Approved Application', 'Approved application ID: 435', '2025-04-18 02:28:14'),
(352, 15, 'Logged In', 'IP: ::1', '2025-04-19 01:59:19'),
(353, 15, 'Logged In', 'IP: ::1', '2025-04-19 10:28:36'),
(354, 15, 'Logged In', 'IP: ::1', '2025-04-19 12:23:58'),
(355, 17, 'Logged In', 'IP: ::1', '2025-04-19 12:24:29'),
(356, 17, 'Logged In', 'IP: ::1', '2025-04-19 23:48:09'),
(357, 17, 'Logged In', 'IP: ::1', '2025-04-20 07:10:56'),
(358, 17, 'Issued Violation', 'Issued Violation ID: 17', '2025-04-20 07:27:15'),
(359, 15, 'Logged In', 'IP: ::1', '2025-04-20 08:58:33'),
(360, 15, 'Logged In', 'IP: ::1', '2025-04-20 23:57:54'),
(361, 17, 'Logged In', 'IP: ::1', '2025-04-20 23:58:06'),
(362, 15, 'Logged In', 'IP: ::1', '2025-04-21 02:42:18'),
(363, 17, 'Issued Violation', 'Issued Violation ID: 18', '2025-04-21 03:55:47'),
(364, 17, 'Issued Violation', 'Issued Violation ID: 19', '2025-04-21 03:59:48'),
(365, 17, 'Issued Violation', 'Issued Violation ID: 20', '2025-04-21 04:03:41'),
(366, 17, 'Issued Violation', 'Issued Violation ID: 21', '2025-04-21 04:25:24'),
(367, 17, 'Issued Violation', 'Issued Violation ID: 22', '2025-04-21 04:26:51'),
(368, 15, 'Logged In', 'IP: ::1', '2025-04-21 14:41:43'),
(369, 15, 'Logged In', 'IP: ::1', '2025-04-22 23:27:49'),
(370, 15, 'Logged In', 'IP: ::1', '2025-04-22 23:30:02'),
(371, 15, 'Logged In', 'IP: ::1', '2025-04-25 04:43:47'),
(372, 15, 'Logged In', 'IP: ::1', '2025-04-25 13:51:55'),
(373, 15, 'Logged In', 'IP: ::1', '2025-04-26 08:11:43');

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `start_date` datetime DEFAULT NULL,
  `expiry_date` datetime DEFAULT NULL,
  `audience` enum('all','vendors','admins') DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` enum('Active','Archived') DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `applicants`
--

CREATE TABLE `applicants` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `application_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `applicants`
--

INSERT INTO `applicants` (`id`, `user_id`, `application_id`, `created_at`) VALUES
(196, 12, 425, '2025-04-04 07:23:14'),
(198, 15, 427, '2025-04-17 03:07:06'),
(199, 12, 432, '2025-04-18 00:25:30'),
(202, 12, 435, '2025-04-18 01:55:06');

-- --------------------------------------------------------

--
-- Table structure for table `applications`
--

CREATE TABLE `applications` (
  `id` int(11) NOT NULL,
  `application_number` varchar(255) DEFAULT NULL,
  `account_id` int(11) NOT NULL,
  `stall_id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `market_id` int(11) NOT NULL,
  `application_type` enum('stall','stall transfer','stall extension','helper','stall succession') NOT NULL,
  `products` varchar(255) DEFAULT NULL,
  `helper_id` int(10) DEFAULT NULL,
  `extension_id` int(11) DEFAULT NULL,
  `status` enum('Submitted','Under Review','Approved','Rejected','Withdrawn') NOT NULL DEFAULT 'Submitted',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `rejection_reason` varchar(255) DEFAULT NULL,
  `reviewing_admin_id` int(11) DEFAULT NULL,
  `reviewed_by` int(11) DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `inspector_id` int(10) DEFAULT NULL,
  `inspection_date` date DEFAULT NULL,
  `inspection_status` enum('Approved','Rejected','Pending','Scheduled') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `applications`
--

INSERT INTO `applications` (`id`, `application_number`, `account_id`, `stall_id`, `section_id`, `market_id`, `application_type`, `products`, `helper_id`, `extension_id`, `status`, `created_at`, `rejection_reason`, `reviewing_admin_id`, `reviewed_by`, `reviewed_at`, `inspector_id`, `inspection_date`, `inspection_status`) VALUES
(425, 'APP-20250404-000001', 16, 184, 5, 5, 'stall', NULL, NULL, NULL, 'Withdrawn', '2025-04-04 07:23:14', NULL, NULL, 15, '2025-04-04 07:23:32', 17, '2025-04-05', 'Approved'),
(427, 'APP-20250417-000426', 81, 134, 5, 5, 'stall', 'Ulam', NULL, NULL, 'Under Review', '2025-04-17 03:07:06', NULL, 15, NULL, '2025-04-17 03:14:43', 17, '2025-04-18', 'Scheduled'),
(432, 'APP-20250418-000428', 16, 184, 5, 5, 'stall extension', NULL, NULL, 35, 'Approved', '2025-04-18 00:25:30', NULL, NULL, 15, '2025-04-18 00:44:49', 17, '2025-04-19', 'Approved'),
(435, 'APP-20250418-000435', 16, 184, 5, 5, 'helper', NULL, 58, NULL, 'Approved', '2025-04-18 01:55:06', NULL, NULL, 15, '2025-04-18 01:57:01', 17, '2025-04-19', 'Approved');

-- --------------------------------------------------------

--
-- Table structure for table `documents`
--

CREATE TABLE `documents` (
  `id` int(11) NOT NULL,
  `application_id` int(11) NOT NULL,
  `document_name` varchar(255) DEFAULT NULL,
  `document_type` varchar(255) NOT NULL,
  `document_path` varchar(255) NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('Valid','Rejected','Pending','') DEFAULT 'Pending',
  `rejection_reason` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `documents`
--

INSERT INTO `documents` (`id`, `application_id`, `document_name`, `document_type`, `document_path`, `uploaded_at`, `status`, `rejection_reason`) VALUES
(620, 425, 'Proof_of_Residency_1743751394_a79ad20d83.png', 'Proof of Residency', 'uploads/Proof_of_Residency_1743751394_a79ad20d83.png', '2025-04-04 07:23:14', 'Valid', NULL),
(621, 425, 'SSS_1743751394_9acde2eaa5.png', 'SSS', 'uploads/SSS_1743751394_9acde2eaa5.png', '2025-04-04 07:23:14', 'Valid', NULL),
(624, 427, 'Proof_of_Residency_1744859226_69dd3e7670.png', 'Proof of Residency', 'uploads/Proof_of_Residency_1744859226_69dd3e7670.png', '2025-04-17 03:07:06', 'Valid', NULL),
(625, 427, 'Drivers_license_1744859226_39e260f95b.png', 'Drivers_license', 'uploads/Drivers_license_1744859226_39e260f95b.png', '2025-04-17 03:07:06', 'Valid', NULL),
(626, 432, 'Current_Id_Photo_1744935930_9c32871d36.png', 'Current Id Photo', 'uploads/Current_Id_Photo_1744935930_9c32871d36.png', '2025-04-18 00:25:30', 'Valid', NULL),
(627, 432, 'Proof_of_Payment_1744935930_bb0418945c.png', 'Proof of Payment', 'uploads/Proof_of_Payment_1744935930_bb0418945c.png', '2025-04-18 00:25:30', 'Valid', NULL),
(636, 435, 'Letter_of_Authorization_1744941306_69579de8a2.png', 'Letter of Authorization', 'uploads/Letter_of_Authorization_1744941306_69579de8a2.png', '2025-04-18 01:55:06', 'Valid', NULL),
(637, 435, 'Passport_1744941306_6f829069bf.png', 'Passport', 'uploads/Passport_1744941306_6f829069bf.png', '2025-04-18 01:55:06', 'Valid', NULL),
(638, 435, 'Barangay_Clearance_1744941306_949a242eb5.png', 'Barangay Clearance', 'uploads/Barangay_Clearance_1744941306_949a242eb5.png', '2025-04-18 01:55:06', 'Valid', NULL),
(639, 435, 'Proof_of_Residency_1744941306_df14cb2a96.png', 'Proof of Residency', 'uploads/Proof_of_Residency_1744941306_df14cb2a96.png', '2025-04-18 01:55:06', 'Valid', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `expiration_dates`
--

CREATE TABLE `expiration_dates` (
  `id` int(11) NOT NULL,
  `application_id` int(11) DEFAULT NULL,
  `reference_id` int(11) NOT NULL,
  `type` enum('stall','extension','helper','violation') NOT NULL,
  `expiration_date` date NOT NULL,
  `status` enum('active','expired','payment_period','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `expiration_dates`
--

INSERT INTO `expiration_dates` (`id`, `application_id`, `reference_id`, `type`, `expiration_date`, `status`, `created_at`) VALUES
(13, 425, 184, 'stall', '2025-05-01', 'payment_period', '2025-04-04 07:39:55'),
(17, 432, 35, 'extension', '2026-04-18', 'active', '2025-04-18 01:03:22'),
(18, 435, 58, 'helper', '2025-04-21', 'expired', '2025-04-18 02:28:14'),
(22, NULL, 20, 'violation', '2025-04-26', 'inactive', '2025-04-21 04:03:41'),
(24, NULL, 22, 'violation', '2025-04-20', 'expired', '2025-04-21 04:26:51');

-- --------------------------------------------------------

--
-- Table structure for table `extensions`
--

CREATE TABLE `extensions` (
  `id` int(11) NOT NULL,
  `stall_id` int(11) NOT NULL,
  `application_id` int(11) NOT NULL,
  `duration` enum('3 months','6 months','12 months') NOT NULL,
  `extension_cost` decimal(10,2) NOT NULL,
  `payment_status` enum('Paid','Unpaid','Overdue','Payment_period') DEFAULT 'Unpaid',
  `status` enum('active','inactive') NOT NULL DEFAULT 'inactive',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `extensions`
--

INSERT INTO `extensions` (`id`, `stall_id`, `application_id`, `duration`, `extension_cost`, `payment_status`, `status`, `created_at`, `updated_at`) VALUES
(35, 184, 432, '3 months', 312.00, 'Paid', 'active', '2025-04-18 00:25:30', '2025-04-19 03:47:09');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `account_id` int(11) DEFAULT NULL,
  `message` text NOT NULL,
  `sentiment` enum('Positive','Negative','Neutral','undefined') DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`id`, `account_id`, `message`, `sentiment`, `created_at`) VALUES
(131, 15, 'this was awsome', 'undefined', '2025-04-03 23:45:28'),
(132, 15, 'it was good', 'Positive', '2025-04-03 23:46:02'),
(133, 15, 'this is fire (test)', 'undefined', '2025-04-13 05:35:34'),
(134, 15, 'sentiment test', 'Neutral', '2025-04-13 05:36:03'),
(135, 15, 'this is the test im doing', 'Neutral', '2025-04-13 05:42:07'),
(136, 15, 'the test was doing good so far, and i like it', 'Positive', '2025-04-13 05:42:35');

-- --------------------------------------------------------

--
-- Table structure for table `garbage_requests`
--

CREATE TABLE `garbage_requests` (
  `id` int(11) NOT NULL,
  `market_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `request_count` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `request_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `garbage_requests`
--

INSERT INTO `garbage_requests` (`id`, `market_id`, `user_id`, `request_count`, `created_at`, `request_date`) VALUES
(3, 5, 13, 0, '2025-04-17 05:53:19', '2025-04-17 13:53:19'),
(4, 1, 12, 1, '2025-04-17 06:18:08', '2025-04-17 14:18:08'),
(5, 4, 12, 1, '2025-04-17 06:49:52', '2025-04-17 14:49:52'),
(6, 3, 12, 1, '2025-04-17 06:50:31', '2025-04-17 14:50:31');

-- --------------------------------------------------------

--
-- Table structure for table `helpers`
--

CREATE TABLE `helpers` (
  `id` int(100) NOT NULL,
  `stall_id` int(10) DEFAULT NULL,
  `so_user_id` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(255) NOT NULL,
  `sex` enum('Male','Female','Other') DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `alt_email` varchar(255) DEFAULT NULL,
  `phone_number` varchar(15) NOT NULL,
  `civil_status` varchar(50) NOT NULL,
  `nationality` varchar(100) NOT NULL,
  `address` text NOT NULL,
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Inactive',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `helpers`
--

INSERT INTO `helpers` (`id`, `stall_id`, `so_user_id`, `first_name`, `middle_name`, `last_name`, `sex`, `email`, `alt_email`, `phone_number`, `civil_status`, `nationality`, `address`, `status`, `created_at`) VALUES
(58, 184, 12, 'Michael', 'N/A', 'Santos', 'Male', 'helper@yahoo.com', 'helperalt@yahoo.com', '09918827651', 'Single', 'Filipino', '98, Ambuklao St., Pasong Tamo, Quezon City, NCR, 1107', 'Active', '2025-04-18 01:55:06');

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
  `market_address` varchar(255) NOT NULL,
  `google_maps_links` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `market_locations`
--

INSERT INTO `market_locations` (`id`, `market_name`, `market_address`, `google_maps_links`) VALUES
(1, 'Central Market', '123 Main St.', ''),
(2, 'Eastside Market', '456 East St.', ''),
(3, 'West End Market', '789 West St.', ''),
(4, 'Roxas Market', '123 Market Road, Sunrise City', ''),
(5, 'San Jose City-Owned Market', 'Mayon St., Barangay N.S. Amoranto (Gintong Silahis), District 1, Quezon City', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3860.4196756691617!2d120.9924109757739!3d14.632102576303287!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397b673a0fbac99%3A0x439b5209c194c294!2sSan%20Jose%20Public%20Market!5e0!3m2!1sen!2sph!4v1741762688427!5m2!1sen!2sph');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `account_id` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `icon` varchar(50) DEFAULT NULL,
  `status` enum('unread','read') DEFAULT 'unread',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `account_id`, `title`, `message`, `type`, `icon`, `status`, `created_at`) VALUES
(386, 15, NULL, 'Your feedback has been received. Thank you for your input!', 'Feedback Submitted', NULL, 'unread', '2025-04-03 23:45:28'),
(387, 15, NULL, 'Your feedback has been received. Thank you for your input!', 'Feedback Submitted', NULL, 'unread', '2025-04-03 23:46:02'),
(388, 15, NULL, 'Your request has been received. Thank you for your input!', 'Support Request Submitted', NULL, 'unread', '2025-04-03 23:48:42'),
(389, 16, NULL, 'Your Vendor Application has been successfully approved. Your Application Form Number is: .', 'Vendor Application', NULL, 'read', '2025-04-04 06:32:58'),
(390, 16, NULL, 'Your Vendor Application has been successfully approved. Your Application Form Number is: .', 'Vendor Application', NULL, 'read', '2025-04-04 06:34:37'),
(391, 16, NULL, 'Your application for Stall Application has been successfully submitted. Your Application Form Number is: APP-20250404-000001.', 'Stall Application', NULL, 'read', '2025-04-04 07:23:14'),
(392, 15, NULL, 'Congratulations! Your application for stall (Application Number: APP-20250404-000001) has been approved.', 'Approved Application', NULL, 'unread', '2025-04-04 07:39:55'),
(393, 16, NULL, 'A violation has been recorded under your stall. Please check your account for details.', 'Violation Issued', NULL, 'read', '2025-04-04 07:59:50'),
(394, 16, NULL, 'A violation has been recorded under your stall. Please check your account for details.', 'Violation Issued', NULL, 'read', '2025-04-04 08:46:36'),
(395, 16, NULL, 'A violation has been recorded under your stall. Please check your account for details.', 'Violation Issued', NULL, 'read', '2025-04-04 13:46:39'),
(396, 16, NULL, 'A violation has been recorded under your stall. Please check your account for details.', 'Violation Issued', NULL, 'read', '2025-04-04 14:06:50'),
(397, 15, NULL, 'Your feedback has been received. Thank you for your input!', 'Feedback Submitted', NULL, 'unread', '2025-04-13 05:35:34'),
(398, 15, NULL, 'Your feedback has been received. Thank you for your input!', 'Feedback Submitted', NULL, 'unread', '2025-04-13 05:36:03'),
(399, 15, NULL, 'Your feedback has been received. Thank you for your input!', 'Feedback Submitted', NULL, 'unread', '2025-04-13 05:42:07'),
(400, 15, NULL, 'Your feedback has been received. Thank you for your input!', 'Feedback Submitted', NULL, 'unread', '2025-04-13 05:42:35'),
(401, 81, NULL, 'Your Vendor Application has been successfully approved. Please login again to reflect changes.', 'Vendor Application', NULL, 'read', '2025-04-17 01:52:25'),
(402, 81, NULL, 'Your application for Stall Application has been successfully submitted. Your Application Form Number is: APP-20250417-000426.', 'Stall Application', NULL, 'unread', '2025-04-17 03:05:44'),
(403, 81, NULL, 'Your application for Stall Application has been successfully submitted. Your Application Form Number is: APP-20250417-000426.', 'Stall Application', NULL, 'unread', '2025-04-17 03:07:06'),
(404, 16, NULL, 'Your application for Stall Extension has been successfully submitted. Your Application Form Number is: APP-20250418-000428.', 'Stall Extension', NULL, 'read', '2025-04-18 00:25:30'),
(405, 16, NULL, 'Your application for Helper Application has been successfully submitted. Your Application Form Number is: APP-20250418-000433.', 'Helper Application', NULL, 'read', '2025-04-18 00:43:44'),
(406, 15, NULL, 'Congratulations! Your application for stall extension (Application Number: APP-20250418-000428) has been approved.', 'Approved Application', NULL, 'unread', '2025-04-18 01:03:22'),
(407, 16, NULL, 'Your application for Helper Application has been successfully submitted. Your Application Form Number is: APP-20250418-000434.', 'Helper Application', NULL, 'read', '2025-04-18 01:49:35'),
(408, 16, NULL, 'Your application for Helper Application has been successfully submitted. Your Application Form Number is: APP-20250418-000435.', 'Helper Application', NULL, 'read', '2025-04-18 01:55:06'),
(409, 15, NULL, 'Congratulations! Your application for helper (Application Number: APP-20250418-000435) has been approved.', 'Approved Application', NULL, 'unread', '2025-04-18 02:28:14'),
(410, 16, NULL, 'A violation has been recorded under your stall. Please check your account for details.', 'Violation Issued', NULL, 'read', '2025-04-20 07:27:15'),
(411, 16, NULL, 'A violation has been recorded under your stall. Please check your account for details.', 'Violation Issued', NULL, 'read', '2025-04-21 03:55:47'),
(412, 16, NULL, 'A violation has been recorded under your stall. Please check your account for details.', 'Violation Issued', NULL, 'read', '2025-04-21 03:59:48'),
(413, 16, NULL, 'A violation has been recorded under your stall. Please check your account for details.', 'Violation Issued', NULL, 'read', '2025-04-21 04:03:41'),
(414, 16, NULL, 'A violation has been recorded under your stall. Please check your account for details.', 'Violation Issued', NULL, 'read', '2025-04-21 04:25:24'),
(415, 16, NULL, 'A violation has been recorded under your stall. Please check your account for details.', 'Violation Issued', NULL, 'read', '2025-04-21 04:26:51');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `stall_id` int(11) DEFAULT NULL,
  `extension_id` int(11) DEFAULT NULL,
  `violation_id` int(11) DEFAULT NULL,
  `source_type` enum('stall','extension','violation','helper') NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_status` enum('Paid','Unpaid','Pending') DEFAULT 'Pending',
  `payment_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `receipt_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `user_id`, `stall_id`, `extension_id`, `violation_id`, `source_type`, `amount`, `payment_status`, `payment_date`, `receipt_path`, `created_at`) VALUES
(3, 12, 184, NULL, NULL, 'stall', 800.00, 'Paid', '2025-04-04 08:20:35', '../../uploads/receipts/receipt_67ef9653a62361.43813979.png', '2025-04-04 08:20:35'),
(4, 12, 184, NULL, NULL, 'stall', 800.00, 'Paid', '2025-04-04 14:43:48', '../../uploads/receipts/receipt_67eff0249a91c2.77658429.png', '2025-04-04 14:43:48'),
(5, 12, 184, NULL, NULL, 'stall', 1000.00, 'Paid', '2025-04-14 12:25:55', '../../uploads/receipts/receipt_67fcfed3eeed13.38320616.png', '2025-04-14 12:25:55'),
(9, 12, 184, 35, NULL, 'extension', 312.00, 'Paid', '2025-04-19 01:47:22', '../../uploads/receipts/receipt_680300aa11a7b2.52906238.png', '2025-04-19 01:47:22'),
(10, 12, NULL, NULL, 20, 'violation', 1200.00, 'Paid', '2025-04-25 13:49:46', '../../uploads/receipts/receipt_680b92fa3f19e1.89267550.png', '2025-04-25 13:49:46');

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
(4, 'Dry Goods'),
(5, 'Carinderia'),
(6, 'Grocery');

-- --------------------------------------------------------

--
-- Table structure for table `stalls`
--

CREATE TABLE `stalls` (
  `id` int(11) NOT NULL,
  `market_id` int(11) DEFAULT NULL,
  `section_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `stall_number` varchar(11) NOT NULL,
  `rental_fee` decimal(10,2) NOT NULL,
  `stall_size` varchar(50) NOT NULL,
  `payment_status` enum('Paid','Unpaid','Pending','Overdue','Payment_Period') DEFAULT 'Unpaid',
  `status` enum('available','occupied','maintenance','pending','expired','terminated','suspended') DEFAULT 'available',
  `product` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stalls`
--

INSERT INTO `stalls` (`id`, `market_id`, `section_id`, `user_id`, `stall_number`, `rental_fee`, `stall_size`, `payment_status`, `status`, `product`) VALUES
(6, 3, 2, NULL, '301', 85.00, '8x8 sqft', 'Unpaid', 'available', NULL),
(7, 3, 3, NULL, '302', 130.00, '12x12 sqft', 'Unpaid', 'available', NULL),
(18, 4, 1, NULL, '1', 1500.00, '12x12 sqft', 'Unpaid', 'available', NULL),
(20, 4, 4, NULL, '3', 1800.00, '15x10 sqft', 'Unpaid', 'available', NULL),
(22, 4, 4, NULL, '5', 1200.00, '8x6 sqft', 'Unpaid', 'available', NULL),
(23, 4, 1, NULL, '6', 1500.00, '12x12 sqft', 'Unpaid', 'available', NULL),
(25, 4, 4, NULL, '8', 1900.00, '15x10 sqft', 'Unpaid', 'available', NULL),
(26, 4, 4, NULL, '9', 1800.00, '20x15 sqft', 'Unpaid', 'available', NULL),
(134, 5, 5, NULL, 'A3', 1012.00, '4.4sqm', 'Unpaid', 'available', NULL),
(135, 5, 4, NULL, 'A4', 524.98, '9.13sqm', 'Unpaid', 'available', NULL),
(136, 5, 5, NULL, 'B1', 240.00, '4sqm', 'Unpaid', 'available', NULL),
(137, 5, 5, NULL, 'B2', 920.00, '4sqm', 'Unpaid', 'available', NULL),
(140, 5, 5, NULL, 'B5', 230.00, '4sqm', 'Unpaid', 'available', NULL),
(141, 5, 5, NULL, 'B6', 230.00, '4sqm', 'Unpaid', 'available', NULL),
(142, 5, 5, NULL, 'B7', 230.00, '4sqm', 'Unpaid', 'available', NULL),
(143, 5, 5, NULL, 'B8', 230.00, '4sqm', 'Unpaid', 'available', NULL),
(144, 5, 5, NULL, 'B9', 230.00, '4sqm', 'Unpaid', 'available', NULL),
(145, 5, 5, NULL, 'B10', 230.00, '4sqm', 'Unpaid', 'available', NULL),
(146, 5, 5, NULL, 'B11', 230.00, '4sqm', 'Unpaid', 'available', NULL),
(147, 5, 5, NULL, 'B12', 230.00, '4sqm', 'Unpaid', 'available', NULL),
(148, 5, 5, NULL, 'B13', 220.00, '4sqm', 'Unpaid', 'available', NULL),
(149, 5, 5, NULL, 'C1', 336.00, '5.6sqm', 'Unpaid', 'available', NULL),
(150, 5, 3, NULL, 'C2', 400.00, '4sqm', 'Unpaid', 'available', NULL),
(151, 5, 3, NULL, 'C3', 400.00, '4sqm', 'Unpaid', 'available', NULL),
(152, 5, 3, NULL, 'C4', 400.00, '4sqm', 'Unpaid', 'available', NULL),
(153, 5, 3, NULL, 'C5', 1600.00, '4sqm', 'Unpaid', 'available', NULL),
(154, 5, 3, NULL, 'C6', 1600.00, '4sqm', 'Unpaid', 'available', NULL),
(155, 5, 3, NULL, 'C7', 1600.00, '4sqm', 'Unpaid', 'available', NULL),
(156, 5, 1, NULL, 'C8', 400.00, '4sqm', 'Unpaid', 'available', NULL),
(157, 5, 1, NULL, 'C9', 400.00, '4sqm', 'Unpaid', 'available', NULL),
(158, 5, 1, NULL, 'C10', 400.00, '4sqm', 'Unpaid', 'available', NULL),
(159, 5, 1, NULL, 'C11', 400.00, '4sqm', 'Unpaid', 'available', NULL),
(160, 5, 6, NULL, 'D1', 336.00, '5.6sqm', 'Unpaid', 'available', NULL),
(161, 5, 4, NULL, 'D2', 201.25, '3.5sqm', 'Unpaid', 'available', NULL),
(162, 5, 2, NULL, 'D3', 240.00, '4sqm', 'Unpaid', 'available', NULL),
(163, 5, 2, NULL, 'D4', 240.00, '4sqm', 'Unpaid', 'available', NULL),
(164, 5, 2, NULL, 'D5', 240.00, '4sqm', 'Unpaid', 'available', NULL),
(165, 5, 2, NULL, 'D6', 240.00, '4sqm', 'Unpaid', 'available', NULL),
(166, 5, 2, NULL, 'D7', 960.00, '4sqm', 'Unpaid', 'available', NULL),
(167, 5, 2, NULL, 'D8', 240.00, '4sqm', 'Unpaid', 'available', NULL),
(168, 5, 2, NULL, 'D9', 240.00, '4sqm', 'Unpaid', 'available', NULL),
(169, 5, 2, NULL, 'D10', 240.00, '4sqm', 'Unpaid', 'available', NULL),
(170, 5, 2, NULL, 'D11', 240.00, '4sqm', 'Unpaid', 'available', NULL),
(171, 5, 2, NULL, 'D12', 240.00, '4sqm', 'Unpaid', 'available', NULL),
(172, 5, 2, NULL, 'D13', 240.00, '4sqm', 'Unpaid', 'available', NULL),
(173, 5, 2, NULL, 'D14', 1056.00, '4sqm', 'Unpaid', 'available', NULL),
(174, 5, 4, NULL, 'E1', 200.00, '4sqm', 'Unpaid', 'available', NULL),
(175, 5, 4, NULL, 'E2', 200.00, '4sqm', 'Unpaid', 'available', NULL),
(176, 5, 4, NULL, 'E3', 800.00, '4sqm', 'Unpaid', 'available', NULL),
(177, 5, 4, NULL, 'E4', 400.00, '4sqm', 'Unpaid', 'available', NULL),
(178, 5, 4, NULL, 'E5', 400.00, '4sqm', 'Unpaid', 'available', NULL),
(179, 5, 6, NULL, 'E6', 400.00, '4sqm', 'Unpaid', 'available', NULL),
(180, 5, 6, NULL, 'E7', 400.00, '4sqm', 'Unpaid', 'available', NULL),
(181, 5, 6, NULL, 'E8', 200.00, '4sqm', 'Unpaid', 'available', NULL),
(182, 5, 6, NULL, 'E9', 200.00, '4sqm', 'Unpaid', 'available', NULL),
(183, 5, 6, NULL, 'E10', 240.00, '4sqm', 'Unpaid', 'available', NULL),
(184, 5, 5, 12, 'A1', 800.00, '4sqm', 'Payment_Period', 'suspended', NULL),
(185, 5, 5, NULL, 'A2', 1000.00, '4sqm', 'Unpaid', 'available', NULL),
(186, 5, 5, NULL, 'B3', 1100.00, '5sqm', 'Unpaid', 'available', NULL),
(187, 5, 5, NULL, 'B4', 1000.00, '4sqm', 'Unpaid', 'available', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `stall_reviews`
--

CREATE TABLE `stall_reviews` (
  `id` int(11) NOT NULL,
  `stall_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` tinyint(4) NOT NULL CHECK (`rating` between 1 and 5),
  `comment` text DEFAULT NULL,
  `comment_sentiment` enum('Neutral','Positive','Negative','undefined') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stall_reviews`
--

INSERT INTO `stall_reviews` (`id`, `stall_id`, `user_id`, `rating`, `comment`, `comment_sentiment`, `created_at`) VALUES
(42, 184, 14, 4, 'Safety & Security', 'undefined', '2025-04-04 07:49:31');

-- --------------------------------------------------------

--
-- Table structure for table `stall_transfers`
--

CREATE TABLE `stall_transfers` (
  `id` int(11) NOT NULL,
  `current_owner_id` int(11) DEFAULT NULL,
  `deceased_owner_id` int(11) DEFAULT NULL,
  `application_id` int(11) NOT NULL,
  `transfer_type` enum('Transfer','Succession') NOT NULL,
  `transfer_reason` text DEFAULT NULL,
  `recipient_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `transfer_confirmation_status` enum('Pending','Approved','Rejected') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
-- Dumping data for table `support_tickets`
--

INSERT INTO `support_tickets` (`id`, `account_id`, `message`, `status`, `created_at`) VALUES
(47, 15, 'the comfort room were smelly', 'open', '2025-04-03 23:48:42');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `alt_email` varchar(100) DEFAULT NULL,
  `contact_no` varchar(15) NOT NULL,
  `sex` enum('Male','Female','Other') NOT NULL,
  `civil_status` enum('Single','Married','Widowed','Divorced','Separated') NOT NULL,
  `nationality` varchar(50) NOT NULL DEFAULT 'Filipino',
  `address` text NOT NULL,
  `user_type` enum('Admin','Visitor','Vendor','Inspector') NOT NULL DEFAULT 'Visitor',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('active','terminated','blacklisted','suspended') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `account_id`, `first_name`, `middle_name`, `last_name`, `email`, `alt_email`, `contact_no`, `sex`, `civil_status`, `nationality`, `address`, `user_type`, `created_at`, `updated_at`, `status`) VALUES
(12, 16, 'Nelson', 'Panong', 'Reyes', 'vendor@yahoo.com', 'altVendorEmail@yahoo.com', '09929440796', 'Male', 'Single', 'Filipino', '98, Ambuklao St., Napocor Village, Pasong Tamo, Quezon City, NCR, 1700', 'Vendor', '2025-04-04 06:34:37', '2025-04-25 02:05:36', 'suspended'),
(13, 17, 'Andrei', 'DeGuzman', 'Santos', 'inspector@yahoo.com', 'altInspector@yahoo.com', '9827162533', 'Male', 'Married', 'Filipino', '98, Ambuklao St., Napocor Village, Pasong Tamo, Quezon City, NCR, 1700', 'Inspector', '0000-00-00 00:00:00', '2025-04-23 10:01:49', 'active'),
(14, 15, 'John', 'Doe', 'Smith', 'admin@yahoo.com', 'altAdmin@yahoo.com', '09263772162', 'Male', 'Single', 'Filipino', '123 Main St, Some City', 'Admin', '2025-04-03 16:00:00', '2025-04-23 10:01:49', 'active'),
(15, 81, 'Cynthia', 'N/A', 'Reyes', 'test@yahoo.com', 'testalt@yahoo.com', '09382771625', 'Male', 'Single', 'Filipino', 'test, test, test, test, test, test, 1234', 'Vendor', '2025-04-17 01:52:25', '2025-04-23 10:01:49', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `vendors_application`
--

CREATE TABLE `vendors_application` (
  `id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `middle_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `alt_email` varchar(255) DEFAULT NULL,
  `contact_no` varchar(20) NOT NULL,
  `sex` enum('Male','Female','Other') NOT NULL,
  `civil_status` enum('Single','Married','Divorced','Widowed') NOT NULL,
  `nationality` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `application_status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `application_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `rejection_reason` text DEFAULT NULL,
  `status_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vendors_application`
--

INSERT INTO `vendors_application` (`id`, `account_id`, `first_name`, `middle_name`, `last_name`, `email`, `alt_email`, `contact_no`, `sex`, `civil_status`, `nationality`, `address`, `application_status`, `application_date`, `rejection_reason`, `status_date`) VALUES
(7, 81, 'Test', 'Test', 'Test', 'test@yahoo.com', 'testalt@yahoo.com', '09382771625', 'Male', 'Single', 'Filipino', 'test, test, test, test, test, test, 1234', 'Approved', '2025-04-17 01:52:06', NULL, '2025-04-17 09:52:25');

-- --------------------------------------------------------

--
-- Table structure for table `violations`
--

CREATE TABLE `violations` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `stall_id` int(11) NOT NULL,
  `violation_type_id` int(11) NOT NULL,
  `violation_description` text NOT NULL,
  `evidence_image_path` varchar(255) NOT NULL,
  `violation_date` date NOT NULL,
  `status` enum('Pending','Resolved','Dismissed','Deleted','Escalated') NOT NULL DEFAULT 'Pending',
  `suspension_started` datetime DEFAULT NULL,
  `suspension_end` datetime DEFAULT NULL,
  `payment_status` enum('Paid','Unpaid','Pending','Overdue','Payment_Period') NOT NULL DEFAULT 'Unpaid',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `appeal_text` text DEFAULT NULL,
  `appeal_document_path` text DEFAULT NULL,
  `appeal_submitted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `violations`
--

INSERT INTO `violations` (`id`, `user_id`, `stall_id`, `violation_type_id`, `violation_description`, `evidence_image_path`, `violation_date`, `status`, `suspension_started`, `suspension_end`, `payment_status`, `created_at`, `updated_at`, `appeal_text`, `appeal_document_path`, `appeal_submitted_at`) VALUES
(20, 12, 184, 6, 'test', 'uploads/Evidence_1745208221_6805c39d6b817.png', '2025-04-22', 'Escalated', '2025-04-26 20:19:55', '2025-05-26 20:19:55', 'Overdue', '2025-04-21 04:03:41', '2025-04-26 12:19:55', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `violation_types`
--

CREATE TABLE `violation_types` (
  `id` int(11) NOT NULL,
  `violation_name` varchar(255) NOT NULL,
  `fine_amount` decimal(10,2) NOT NULL,
  `escalation_fee` decimal(10,2) DEFAULT 0.00,
  `criticality` enum('Critical','Not Critical') NOT NULL,
  `escalation_status` enum('None','Warning','Suspended','Terminated') DEFAULT 'None'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `violation_types`
--

INSERT INTO `violation_types` (`id`, `violation_name`, `fine_amount`, `escalation_fee`, `criticality`, `escalation_status`) VALUES
(1, 'Harassment or Misconduct', 5000.00, 1000.00, 'Critical', 'Terminated'),
(2, 'Overpricing or Price Manipulation', 4000.00, 800.00, 'Critical', 'Suspended'),
(3, 'Obstructing Pathways or Exits', 3000.00, 600.00, 'Critical', 'Suspended'),
(4, 'Failure to Follow Food Safety Standards', 4500.00, 900.00, 'Critical', 'Suspended'),
(5, 'Tampering with Scales or Measurements', 3500.00, 700.00, 'Critical', 'Suspended'),
(6, 'Causing Disturbances', 1000.00, 200.00, 'Critical', 'Suspended'),
(7, 'Poor Stall Hygiene', 1500.00, 300.00, 'Critical', 'Suspended'),
(8, 'Improper Waste Disposal', 1200.00, 240.00, 'Critical', 'Suspended'),
(10, 'Late Payment', 500.00, 100.00, 'Not Critical', 'None'),
(11, 'Unauthorized Selling', 2500.00, 500.00, 'Not Critical', 'Warning');

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
-- Indexes for table `admin_logs`
--
ALTER TABLE `admin_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `applicants`
--
ALTER TABLE `applicants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `application_id` (`application_id`);

--
-- Indexes for table `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stall_id` (`stall_id`),
  ADD KEY `fk_section_id` (`section_id`),
  ADD KEY `fk_market_location` (`market_id`),
  ADD KEY `applications_ibfk_1` (`account_id`),
  ADD KEY `helper_id` (`helper_id`),
  ADD KEY `fk_extension_id` (`extension_id`),
  ADD KEY `reviewed_by` (`reviewed_by`),
  ADD KEY `reviewing_admin_id` (`reviewing_admin_id`),
  ADD KEY `inspector_id` (`inspector_id`);

--
-- Indexes for table `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `documents_ibfk_1` (`application_id`);

--
-- Indexes for table `expiration_dates`
--
ALTER TABLE `expiration_dates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `application_id` (`application_id`),
  ADD KEY `reference_id` (`reference_id`);

--
-- Indexes for table `extensions`
--
ALTER TABLE `extensions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `application_id` (`application_id`),
  ADD KEY `fk_stall` (`stall_id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`),
  ADD KEY `feedback_ibfk_1` (`account_id`);

--
-- Indexes for table `garbage_requests`
--
ALTER TABLE `garbage_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `market_id` (`market_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `helpers`
--
ALTER TABLE `helpers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_stall_id` (`stall_id`),
  ADD KEY `so_user_id` (`so_user_id`);

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
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `account_id` (`account_id`) USING BTREE;

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `stall_id` (`stall_id`),
  ADD KEY `extension_id` (`extension_id`),
  ADD KEY `violation_id` (`violation_id`);

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
  ADD KEY `fk_vendor` (`user_id`);

--
-- Indexes for table `stall_reviews`
--
ALTER TABLE `stall_reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stall_id` (`stall_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `stall_transfers`
--
ALTER TABLE `stall_transfers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `application_id` (`application_id`),
  ADD KEY `current_owner_id` (`current_owner_id`) USING BTREE,
  ADD KEY `deceased_owner_id` (`deceased_owner_id`) USING BTREE,
  ADD KEY `fk_recipient` (`recipient_id`);

--
-- Indexes for table `support_tickets`
--
ALTER TABLE `support_tickets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `support_tickets_ibfk_1` (`account_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `account_id` (`account_id`);

--
-- Indexes for table `vendors_application`
--
ALTER TABLE `vendors_application`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_account_id` (`account_id`);

--
-- Indexes for table `violations`
--
ALTER TABLE `violations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `stall_id` (`stall_id`),
  ADD KEY `violation_type_id` (`violation_type_id`);

--
-- Indexes for table `violation_types`
--
ALTER TABLE `violation_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `violation_name` (`violation_name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- AUTO_INCREMENT for table `admin_logs`
--
ALTER TABLE `admin_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=374;

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `applicants`
--
ALTER TABLE `applicants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=203;

--
-- AUTO_INCREMENT for table `applications`
--
ALTER TABLE `applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=436;

--
-- AUTO_INCREMENT for table `documents`
--
ALTER TABLE `documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=640;

--
-- AUTO_INCREMENT for table `expiration_dates`
--
ALTER TABLE `expiration_dates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `extensions`
--
ALTER TABLE `extensions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=137;

--
-- AUTO_INCREMENT for table `garbage_requests`
--
ALTER TABLE `garbage_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `helpers`
--
ALTER TABLE `helpers`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `login_attempts`
--
ALTER TABLE `login_attempts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `market_locations`
--
ALTER TABLE `market_locations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=416;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `sections`
--
ALTER TABLE `sections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `stalls`
--
ALTER TABLE `stalls`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=188;

--
-- AUTO_INCREMENT for table `stall_reviews`
--
ALTER TABLE `stall_reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `stall_transfers`
--
ALTER TABLE `stall_transfers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `support_tickets`
--
ALTER TABLE `support_tickets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `vendors_application`
--
ALTER TABLE `vendors_application`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `violations`
--
ALTER TABLE `violations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `violation_types`
--
ALTER TABLE `violation_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin_logs`
--
ALTER TABLE `admin_logs`
  ADD CONSTRAINT `admin_logs_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `applicants`
--
ALTER TABLE `applicants`
  ADD CONSTRAINT `applicants_ibfk_application_id` FOREIGN KEY (`application_id`) REFERENCES `applications` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `applicants_ibfk_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `applications`
--
ALTER TABLE `applications`
  ADD CONSTRAINT `applications_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `applications_ibfk_2` FOREIGN KEY (`stall_id`) REFERENCES `stalls` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_extension_id` FOREIGN KEY (`extension_id`) REFERENCES `extensions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_helper_id` FOREIGN KEY (`helper_id`) REFERENCES `helpers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_inspector_id` FOREIGN KEY (`inspector_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_market_location` FOREIGN KEY (`market_id`) REFERENCES `market_locations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_reviewed_by` FOREIGN KEY (`reviewed_by`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_reviewing_admin_id` FOREIGN KEY (`reviewing_admin_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_section_id` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `documents`
--
ALTER TABLE `documents`
  ADD CONSTRAINT `documents_ibfk_1` FOREIGN KEY (`application_id`) REFERENCES `applications` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `expiration_dates`
--
ALTER TABLE `expiration_dates`
  ADD CONSTRAINT `fk_application_id` FOREIGN KEY (`application_id`) REFERENCES `applications` (`id`);

--
-- Constraints for table `extensions`
--
ALTER TABLE `extensions`
  ADD CONSTRAINT `extensions_ibfk_1` FOREIGN KEY (`application_id`) REFERENCES `applications` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_stall` FOREIGN KEY (`stall_id`) REFERENCES `stalls` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `garbage_requests`
--
ALTER TABLE `garbage_requests`
  ADD CONSTRAINT `garbage_requests_ibfk_1` FOREIGN KEY (`market_id`) REFERENCES `market_locations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `garbage_requests_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `helpers`
--
ALTER TABLE `helpers`
  ADD CONSTRAINT `fk_so_user_id` FOREIGN KEY (`so_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_stall_id` FOREIGN KEY (`stall_id`) REFERENCES `stalls` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `login_attempts`
--
ALTER TABLE `login_attempts`
  ADD CONSTRAINT `login_attempts_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payments_ibfk_2` FOREIGN KEY (`stall_id`) REFERENCES `stalls` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payments_ibfk_3` FOREIGN KEY (`extension_id`) REFERENCES `extensions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payments_ibfk_4` FOREIGN KEY (`violation_id`) REFERENCES `violations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stalls`
--
ALTER TABLE `stalls`
  ADD CONSTRAINT `fk_market` FOREIGN KEY (`market_id`) REFERENCES `market_locations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stalls_ibfk_1` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stall_reviews`
--
ALTER TABLE `stall_reviews`
  ADD CONSTRAINT `stall_reviews_ibfk_1` FOREIGN KEY (`stall_id`) REFERENCES `stalls` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stall_reviews_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stall_transfers`
--
ALTER TABLE `stall_transfers`
  ADD CONSTRAINT `fk_recipient` FOREIGN KEY (`recipient_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `stall_transfers_ibfk_2` FOREIGN KEY (`application_id`) REFERENCES `applications` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stall_transfers_ibfk_current_owner` FOREIGN KEY (`current_owner_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `stall_transfers_ibfk_deceased_owner` FOREIGN KEY (`deceased_owner_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `support_tickets`
--
ALTER TABLE `support_tickets`
  ADD CONSTRAINT `support_tickets_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vendors_application`
--
ALTER TABLE `vendors_application`
  ADD CONSTRAINT `idx_account_id	` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`);

--
-- Constraints for table `violations`
--
ALTER TABLE `violations`
  ADD CONSTRAINT `violations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `violations_ibfk_2` FOREIGN KEY (`stall_id`) REFERENCES `stalls` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `violations_ibfk_3` FOREIGN KEY (`violation_type_id`) REFERENCES `violation_types` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
