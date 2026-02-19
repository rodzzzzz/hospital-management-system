-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 05, 2026 at 06:14 AM
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
-- Database: `ttsi_auto`
--

-- --------------------------------------------------------

--
-- Table structure for table `cashier_charges`
--

CREATE TABLE `cashier_charges` (
  `id` int(11) NOT NULL,
  `source_module` varchar(50) NOT NULL,
  `source_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `encounter_id` int(11) DEFAULT NULL,
  `status` varchar(32) NOT NULL DEFAULT 'pending_invoice',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cashier_charge_items`
--

CREATE TABLE `cashier_charge_items` (
  `id` int(11) NOT NULL,
  `charge_id` int(11) NOT NULL,
  `medicine_id` int(11) DEFAULT NULL,
  `medicine_name` varchar(255) NOT NULL,
  `qty` int(11) NOT NULL DEFAULT 1,
  `instructions` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cashier_invoices`
--

CREATE TABLE `cashier_invoices` (
  `id` int(11) NOT NULL,
  `charge_id` int(11) DEFAULT NULL,
  `patient_id` int(11) NOT NULL,
  `encounter_id` int(11) DEFAULT NULL,
  `status` varchar(32) NOT NULL DEFAULT 'unpaid',
  `total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cashier_invoice_items`
--

CREATE TABLE `cashier_invoice_items` (
  `id` int(11) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `medicine_id` int(11) DEFAULT NULL,
  `description` varchar(255) NOT NULL,
  `qty` int(11) NOT NULL DEFAULT 1,
  `unit_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `subtotal` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cashier_payments`
--

CREATE TABLE `cashier_payments` (
  `id` int(11) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `method` varchar(50) NOT NULL DEFAULT 'cash',
  `received_by` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `change_amount` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chat_files`
--

CREATE TABLE `chat_files` (
  `id` int(11) NOT NULL,
  `thread_id` int(11) NOT NULL,
  `message_id` int(11) DEFAULT NULL,
  `uploader_id` int(11) DEFAULT NULL,
  `original_name` varchar(255) NOT NULL,
  `stored_name` varchar(255) NOT NULL,
  `mime_type` varchar(127) DEFAULT NULL,
  `size_bytes` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chat_messages`
--

CREATE TABLE `chat_messages` (
  `id` int(11) NOT NULL,
  `thread_id` int(11) NOT NULL,
  `sender_id` int(11) DEFAULT NULL,
  `body` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `sender_module` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chat_messages`
--

INSERT INTO `chat_messages` (`id`, `thread_id`, `sender_id`, `body`, `created_at`, `sender_module`) VALUES
(26, 4144, 10, 'asd', '2026-02-02 06:53:15', ''),
(27, 4144, 10, 'asd', '2026-02-02 06:54:47', ''),
(28, 7616, 8, 'ho', '2026-02-02 07:22:42', '');

-- --------------------------------------------------------

--
-- Table structure for table `chat_threads`
--

CREATE TABLE `chat_threads` (
  `id` int(11) NOT NULL,
  `type` enum('module','direct') NOT NULL,
  `module_a` varchar(32) DEFAULT NULL,
  `module_b` varchar(32) DEFAULT NULL,
  `module` varchar(32) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chat_threads`
--

INSERT INTO `chat_threads` (`id`, `type`, `module_a`, `module_b`, `module`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'module', NULL, NULL, 'ER', 3, '2026-02-02 02:18:12', '2026-02-02 05:50:39'),
(2, 'module', NULL, NULL, 'ANNOUNCEMENTS', NULL, '2026-02-02 02:35:24', '2026-02-02 02:35:24'),
(75, 'module', NULL, NULL, 'OPD', 3, '2026-02-02 03:54:35', '2026-02-02 04:04:47'),
(109, 'module', NULL, NULL, 'PHARMACY', 3, '2026-02-02 04:05:13', '2026-02-02 05:49:38'),
(132, 'module', NULL, NULL, 'LAB', 3, '2026-02-02 04:06:03', '2026-02-02 04:06:03'),
(4144, 'direct', NULL, NULL, 'ER|PHARMACY', 3, '2026-02-02 06:15:12', '2026-02-02 06:54:47'),
(7616, 'direct', NULL, NULL, 'CASHIER|ER', 8, '2026-02-02 07:22:42', '2026-02-02 07:22:42'),
(14259, 'module', NULL, NULL, 'CASHIER', NULL, '2026-02-02 14:40:12', '2026-02-02 14:40:12'),
(14260, 'module', NULL, NULL, 'HR', NULL, '2026-02-02 14:40:12', '2026-02-02 14:40:12'),
(14261, '', NULL, NULL, NULL, NULL, '2026-02-02 17:27:36', '2026-02-02 17:27:36'),
(14262, '', NULL, NULL, NULL, NULL, '2026-02-02 17:27:44', '2026-02-02 17:27:44'),
(14263, '', NULL, NULL, NULL, NULL, '2026-02-02 17:32:54', '2026-02-02 17:32:54'),
(14264, '', NULL, NULL, NULL, NULL, '2026-02-02 17:33:01', '2026-02-02 17:33:01'),
(14265, '', NULL, NULL, NULL, NULL, '2026-02-02 17:34:52', '2026-02-02 17:34:52'),
(14266, '', 'CASHIER', 'LAB', NULL, NULL, '2026-02-02 17:34:54', '2026-02-02 17:34:54'),
(14267, '', 'CASHIER', 'PHARMACY', NULL, NULL, '2026-02-02 17:34:56', '2026-02-02 17:34:56'),
(14273, '', 'CASHIER', 'OPD', NULL, NULL, '2026-02-02 17:35:02', '2026-02-02 17:35:02'),
(14274, '', 'CASHIER', 'ER', NULL, NULL, '2026-02-02 17:35:03', '2026-02-02 17:35:03'),
(14280, '', NULL, NULL, NULL, NULL, '2026-02-02 17:35:05', '2026-02-02 17:35:05'),
(14282, '', NULL, NULL, NULL, NULL, '2026-02-02 17:55:13', '2026-02-02 17:55:13'),
(14284, '', NULL, NULL, NULL, NULL, '2026-02-02 17:55:16', '2026-02-02 17:55:16'),
(14286, '', NULL, NULL, NULL, NULL, '2026-02-02 17:55:32', '2026-02-02 17:55:32'),
(14289, '', NULL, NULL, NULL, NULL, '2026-02-02 17:56:59', '2026-02-02 17:56:59'),
(14290, '', NULL, NULL, NULL, NULL, '2026-02-02 17:56:59', '2026-02-02 17:56:59'),
(14296, '', NULL, NULL, NULL, NULL, '2026-02-02 17:59:45', '2026-02-02 17:59:45'),
(14302, '', NULL, NULL, NULL, NULL, '2026-02-02 18:02:49', '2026-02-02 18:02:49'),
(14307, '', NULL, NULL, NULL, NULL, '2026-02-02 18:04:18', '2026-02-02 18:04:18'),
(14311, '', NULL, NULL, NULL, NULL, '2026-02-02 18:04:50', '2026-02-02 18:04:50'),
(14312, '', 'ADMIN', 'OPD', NULL, NULL, '2026-02-02 18:04:56', '2026-02-02 18:04:56'),
(14313, '', 'ADMIN', 'ER', NULL, NULL, '2026-02-02 18:05:02', '2026-02-02 18:05:02'),
(14314, '', 'ADMIN', 'LAB', NULL, NULL, '2026-02-02 18:05:04', '2026-02-02 18:05:04'),
(14315, '', 'ADMIN', 'PHARMACY', NULL, NULL, '2026-02-02 18:05:07', '2026-02-02 18:05:07'),
(14316, '', 'ADMIN', 'CASHIER', NULL, NULL, '2026-02-02 18:05:08', '2026-02-02 18:05:08'),
(14318, '', NULL, NULL, NULL, NULL, '2026-02-02 18:06:30', '2026-02-02 18:06:30'),
(14320, '', NULL, NULL, NULL, NULL, '2026-02-02 18:06:40', '2026-02-02 18:06:40'),
(14321, '', NULL, NULL, NULL, NULL, '2026-02-02 18:06:40', '2026-02-02 18:06:40'),
(14326, '', NULL, NULL, NULL, NULL, '2026-02-02 18:06:44', '2026-02-02 18:06:44'),
(14328, '', NULL, NULL, NULL, NULL, '2026-02-02 18:06:45', '2026-02-02 18:06:45'),
(14334, '', NULL, NULL, NULL, NULL, '2026-02-02 18:06:50', '2026-02-02 18:06:50'),
(14335, '', NULL, NULL, NULL, NULL, '2026-02-02 18:06:50', '2026-02-02 18:06:50'),
(14338, '', NULL, NULL, NULL, NULL, '2026-02-02 18:10:18', '2026-02-02 18:10:18'),
(14341, '', NULL, NULL, NULL, NULL, '2026-02-02 18:14:42', '2026-02-02 18:14:42'),
(14352, '', NULL, NULL, NULL, NULL, '2026-02-02 18:15:11', '2026-02-02 18:15:11'),
(14353, '', NULL, NULL, NULL, NULL, '2026-02-02 18:15:15', '2026-02-02 18:15:15'),
(14361, '', NULL, NULL, NULL, NULL, '2026-02-02 18:15:22', '2026-02-02 18:15:22'),
(14362, '', NULL, NULL, NULL, NULL, '2026-02-02 18:15:23', '2026-02-02 18:15:23'),
(14363, '', NULL, NULL, NULL, NULL, '2026-02-02 18:15:24', '2026-02-02 18:15:24'),
(14365, '', NULL, NULL, NULL, NULL, '2026-02-02 18:15:26', '2026-02-02 18:15:26'),
(14366, '', NULL, NULL, NULL, NULL, '2026-02-02 18:23:20', '2026-02-02 18:23:20'),
(14373, '', NULL, NULL, NULL, NULL, '2026-02-02 18:23:31', '2026-02-02 18:23:31'),
(14374, '', NULL, NULL, NULL, NULL, '2026-02-02 18:23:31', '2026-02-02 18:23:31'),
(14381, '', NULL, NULL, NULL, NULL, '2026-02-02 18:28:14', '2026-02-02 18:28:14'),
(14386, '', NULL, NULL, NULL, NULL, '2026-02-02 18:28:17', '2026-02-02 18:28:17'),
(14387, '', NULL, NULL, NULL, NULL, '2026-02-02 18:28:17', '2026-02-02 18:28:17'),
(14388, '', NULL, NULL, NULL, NULL, '2026-02-02 18:28:18', '2026-02-02 18:28:18'),
(14390, '', NULL, NULL, NULL, NULL, '2026-02-02 18:28:19', '2026-02-02 18:28:19'),
(14391, '', NULL, NULL, NULL, NULL, '2026-02-02 18:28:19', '2026-02-02 18:28:19'),
(14394, '', NULL, NULL, NULL, NULL, '2026-02-02 18:28:20', '2026-02-02 18:28:20'),
(14395, '', NULL, NULL, NULL, NULL, '2026-02-02 18:57:00', '2026-02-02 18:57:00'),
(14396, '', NULL, NULL, NULL, NULL, '2026-02-02 23:52:52', '2026-02-02 23:52:52'),
(14398, '', NULL, NULL, NULL, NULL, '2026-02-02 23:53:08', '2026-02-02 23:53:08'),
(14399, '', NULL, NULL, NULL, NULL, '2026-02-02 23:53:09', '2026-02-02 23:53:09'),
(14400, '', NULL, NULL, NULL, NULL, '2026-02-02 23:53:10', '2026-02-02 23:53:10'),
(14402, '', NULL, NULL, NULL, NULL, '2026-02-02 23:53:11', '2026-02-02 23:53:11'),
(14404, '', NULL, NULL, NULL, NULL, '2026-02-02 23:53:12', '2026-02-02 23:53:12'),
(14405, '', NULL, NULL, NULL, NULL, '2026-02-02 23:53:14', '2026-02-02 23:53:14'),
(14406, '', NULL, NULL, NULL, NULL, '2026-02-02 23:53:20', '2026-02-02 23:53:20'),
(14407, '', 'ER', 'OPD', NULL, NULL, '2026-02-02 23:53:22', '2026-02-02 23:53:22'),
(14408, '', NULL, NULL, NULL, NULL, '2026-02-03 00:57:07', '2026-02-03 00:57:07'),
(14409, '', NULL, NULL, NULL, NULL, '2026-02-03 01:25:25', '2026-02-03 01:25:25'),
(14410, '', NULL, NULL, NULL, NULL, '2026-02-04 17:56:46', '2026-02-04 17:56:46'),
(14412, '', NULL, NULL, NULL, NULL, '2026-02-04 17:56:49', '2026-02-04 17:56:49'),
(14413, '', NULL, NULL, NULL, NULL, '2026-02-04 17:56:50', '2026-02-04 17:56:50'),
(14419, '', NULL, NULL, NULL, NULL, '2026-02-04 17:56:52', '2026-02-04 17:56:52'),
(14420, '', NULL, NULL, NULL, NULL, '2026-02-04 17:56:52', '2026-02-04 17:56:52'),
(14425, '', NULL, NULL, NULL, NULL, '2026-02-04 18:01:12', '2026-02-04 18:01:12'),
(14426, '', NULL, NULL, NULL, NULL, '2026-02-04 18:01:13', '2026-02-04 18:01:13'),
(14427, '', NULL, NULL, NULL, NULL, '2026-02-04 18:01:14', '2026-02-04 18:01:14'),
(14429, '', NULL, NULL, NULL, NULL, '2026-02-04 18:01:15', '2026-02-04 18:01:15'),
(14431, '', NULL, NULL, NULL, NULL, '2026-02-04 18:01:15', '2026-02-04 18:01:15'),
(14444, '', NULL, NULL, NULL, NULL, '2026-02-05 04:32:09', '2026-02-05 04:32:09');

-- --------------------------------------------------------

--
-- Table structure for table `chat_thread_members`
--

CREATE TABLE `chat_thread_members` (
  `id` int(11) NOT NULL,
  `thread_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chat_thread_members`
--

INSERT INTO `chat_thread_members` (`id`, `thread_id`, `user_id`, `created_at`) VALUES
(1, 1, 3, '2026-02-02 02:18:12'),
(2, 75, 12, '2026-02-02 03:54:35'),
(3, 75, 13, '2026-02-02 03:54:36'),
(4, 75, 3, '2026-02-02 03:54:36'),
(8, 109, 10, '2026-02-02 04:05:13'),
(9, 109, 11, '2026-02-02 04:05:13'),
(10, 109, 3, '2026-02-02 04:05:13'),
(11, 132, 6, '2026-02-02 04:06:03'),
(12, 132, 7, '2026-02-02 04:06:03'),
(13, 132, 3, '2026-02-02 04:06:03'),
(18, 1, 4, '2026-02-02 04:11:09'),
(19, 1, 5, '2026-02-02 04:11:09'),
(32, 1, 10, '2026-02-02 04:29:45'),
(78, 4144, 3, '2026-02-02 06:15:12'),
(79, 4144, 4, '2026-02-02 06:15:12'),
(80, 4144, 5, '2026-02-02 06:15:12'),
(81, 4144, 10, '2026-02-02 06:15:12'),
(82, 4144, 11, '2026-02-02 06:15:12'),
(103, 7616, 3, '2026-02-02 07:22:42'),
(104, 7616, 4, '2026-02-02 07:22:42'),
(105, 7616, 5, '2026-02-02 07:22:42'),
(106, 7616, 8, '2026-02-02 07:22:42'),
(107, 7616, 9, '2026-02-02 07:22:42'),
(108, 2, 1, '2026-02-02 14:40:12'),
(109, 2, 2, '2026-02-02 14:40:12'),
(110, 2, 3, '2026-02-02 14:40:12'),
(111, 2, 4, '2026-02-02 14:40:12'),
(112, 2, 5, '2026-02-02 14:40:12'),
(113, 2, 6, '2026-02-02 14:40:12'),
(114, 2, 7, '2026-02-02 14:40:12'),
(115, 2, 8, '2026-02-02 14:40:12'),
(116, 2, 9, '2026-02-02 14:40:12'),
(117, 2, 10, '2026-02-02 14:40:12'),
(118, 2, 11, '2026-02-02 14:40:12'),
(119, 2, 12, '2026-02-02 14:40:12'),
(120, 2, 13, '2026-02-02 14:40:12'),
(121, 2, 14, '2026-02-02 14:40:12'),
(122, 2, 15, '2026-02-02 14:40:12'),
(123, 2, 16, '2026-02-02 14:40:12'),
(133, 14259, 8, '2026-02-02 14:40:12'),
(134, 14259, 9, '2026-02-02 14:40:12'),
(135, 14260, 14, '2026-02-02 14:40:12'),
(136, 14260, 15, '2026-02-02 14:40:12');

-- --------------------------------------------------------

--
-- Table structure for table `chat_thread_reads`
--

CREATE TABLE `chat_thread_reads` (
  `id` int(11) NOT NULL,
  `thread_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `last_read_message_id` int(11) NOT NULL DEFAULT 0,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chat_thread_reads`
--

INSERT INTO `chat_thread_reads` (`id`, `thread_id`, `user_id`, `last_read_message_id`, `updated_at`) VALUES
(1, 75, 3, 2, '2026-02-02 04:04:47'),
(5, 109, 3, 19, '2026-02-02 05:49:39'),
(9, 132, 3, 4, '2026-02-02 04:06:04'),
(45, 1, 3, 15, '2026-02-02 05:41:15'),
(110, 1, 10, 22, '2026-02-02 05:50:39'),
(693, 4144, 3, 27, '2026-02-02 06:54:57'),
(732, 4144, 10, 27, '2026-02-02 06:54:47'),
(975, 7616, 8, 28, '2026-02-02 07:22:42');

-- --------------------------------------------------------

--
-- Table structure for table `dialysis_machines`
--

CREATE TABLE `dialysis_machines` (
  `id` int(11) NOT NULL,
  `machine_code` varchar(32) NOT NULL,
  `status` enum('available','in_use','maintenance') NOT NULL DEFAULT 'available',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dialysis_patients`
--

CREATE TABLE `dialysis_patients` (
  `id` int(11) NOT NULL,
  `patient_code` varchar(32) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dialysis_sessions`
--

CREATE TABLE `dialysis_sessions` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `machine_id` int(11) NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `status` enum('scheduled','in_progress','completed') NOT NULL DEFAULT 'scheduled',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `doctor_availability`
--

CREATE TABLE `doctor_availability` (
  `user_id` int(11) NOT NULL,
  `status` enum('available','busy','on_leave') NOT NULL DEFAULT 'available',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctor_availability`
--

INSERT INTO `doctor_availability` (`user_id`, `status`, `updated_at`) VALUES
(1, 'available', '2026-02-02 10:10:41'),
(16, 'available', '2026-02-01 11:45:52'),
(21, 'available', '2026-02-04 15:44:38');

-- --------------------------------------------------------

--
-- Table structure for table `encounters`
--

CREATE TABLE `encounters` (
  `id` int(11) NOT NULL,
  `encounter_no` varchar(32) DEFAULT NULL,
  `patient_id` int(11) NOT NULL,
  `type` enum('ER','OPD','IPD','PHARMACY') NOT NULL DEFAULT 'OPD',
  `status` enum('open','closed','cancelled') NOT NULL DEFAULT 'open',
  `started_at` datetime NOT NULL,
  `ended_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `er_assessment_submissions`
--

CREATE TABLE `er_assessment_submissions` (
  `id` int(11) NOT NULL,
  `encounter_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `er_assessment_id` int(11) NOT NULL,
  `submitted_by` varchar(255) DEFAULT NULL,
  `doctor_id` int(11) DEFAULT NULL,
  `doctor_name` varchar(255) DEFAULT NULL,
  `status` varchar(30) NOT NULL DEFAULT 'submitted',
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `responded_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `er_clearance_requests`
--

CREATE TABLE `er_clearance_requests` (
  `id` int(11) NOT NULL,
  `encounter_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `clearance_type` enum('discharge','psych','admission','transfer') NOT NULL DEFAULT 'discharge',
  `status` enum('pending','cleared','needs_workup','rejected') NOT NULL DEFAULT 'pending',
  `checklist_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`checklist_json`)),
  `notes` text DEFAULT NULL,
  `requested_by` varchar(255) DEFAULT NULL,
  `requested_at` datetime NOT NULL,
  `cleared_by` varchar(255) DEFAULT NULL,
  `cleared_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `er_consultation_notes`
--

CREATE TABLE `er_consultation_notes` (
  `id` int(11) NOT NULL,
  `encounter_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `author_user_id` int(11) DEFAULT NULL,
  `author_name` varchar(255) DEFAULT NULL,
  `note_text` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `er_doctor_feedback`
--

CREATE TABLE `er_doctor_feedback` (
  `id` int(11) NOT NULL,
  `encounter_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `er_assessment_id` int(11) DEFAULT NULL,
  `doctor_name` varchar(255) DEFAULT NULL,
  `lab_tests_json` text DEFAULT NULL,
  `lab_note` text DEFAULT NULL,
  `feedback_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(30) NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `er_nursing_assessments`
--

CREATE TABLE `er_nursing_assessments` (
  `id` int(11) NOT NULL,
  `encounter_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `nurse_name` varchar(255) DEFAULT NULL,
  `triage_level` int(11) DEFAULT NULL,
  `vitals_json` text DEFAULT NULL,
  `assessment_json` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `er_ward_assignments`
--

CREATE TABLE `er_ward_assignments` (
  `id` int(11) NOT NULL,
  `bed_id` int(11) NOT NULL,
  `encounter_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `track` enum('common','senior','teen_pdea') NOT NULL DEFAULT 'common',
  `isolation_flag` tinyint(1) NOT NULL DEFAULT 0,
  `fall_risk_flag` tinyint(1) NOT NULL DEFAULT 0,
  `extra_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`extra_json`)),
  `assigned_at` datetime NOT NULL,
  `discharged_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `er_ward_beds`
--

CREATE TABLE `er_ward_beds` (
  `id` int(11) NOT NULL,
  `bed_code` varchar(32) NOT NULL,
  `room` varchar(64) DEFAULT NULL,
  `station` varchar(64) DEFAULT NULL,
  `status` enum('available','occupied','cleaning','maintenance') NOT NULL DEFAULT 'available',
  `isolation_capable` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `er_ward_discharge`
--

CREATE TABLE `er_ward_discharge` (
  `id` int(11) NOT NULL,
  `assignment_id` int(11) NOT NULL,
  `checklist_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`checklist_json`)),
  `transfer_facility` varchar(255) DEFAULT NULL,
  `transfer_details` text DEFAULT NULL,
  `ready` tinyint(1) NOT NULL DEFAULT 0,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `er_ward_notes`
--

CREATE TABLE `er_ward_notes` (
  `id` int(11) NOT NULL,
  `assignment_id` int(11) NOT NULL,
  `encounter_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `note_type` enum('daily','sbar') NOT NULL DEFAULT 'daily',
  `note_text` text NOT NULL,
  `author_user_id` int(11) DEFAULT NULL,
  `author_name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hr_departments`
--

CREATE TABLE `hr_departments` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hr_departments`
--

INSERT INTO `hr_departments` (`id`, `name`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Surgery Department', 'active', '2026-02-02 09:36:34', '2026-02-02 09:36:34'),
(2, 'Human Resources', 'active', '2026-02-02 22:40:56', '2026-02-02 22:40:56'),
(3, 'Nursing', 'active', '2026-02-02 22:40:56', '2026-02-02 22:40:56'),
(4, 'Laboratory', 'active', '2026-02-02 22:40:56', '2026-02-02 22:40:56');

-- --------------------------------------------------------

--
-- Table structure for table `hr_employees`
--

CREATE TABLE `hr_employees` (
  `id` int(11) NOT NULL,
  `employee_code` varchar(64) DEFAULT NULL,
  `full_name` varchar(255) NOT NULL,
  `phone` varchar(64) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `position_id` int(11) DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hr_employees`
--

INSERT INTO `hr_employees` (`id`, `employee_code`, `full_name`, `phone`, `email`, `department_id`, `position_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 'EMP-0001', 'Maria Santos', '0917-000-0001', 'maria.santos@example.com', 2, 1, 'active', '2026-02-02 22:40:56', '2026-02-02 22:40:56'),
(2, 'EMP-0002', 'John Dela Cruz', '0917-000-0002', 'john.delacruz@example.com', 3, 2, 'active', '2026-02-02 22:40:56', '2026-02-02 22:40:56'),
(3, 'EMP-0003', 'Anne Reyes', '0917-000-0003', 'anne.reyes@example.com', 4, 3, 'active', '2026-02-02 22:40:56', '2026-02-02 22:40:56');

-- --------------------------------------------------------

--
-- Table structure for table `hr_positions`
--

CREATE TABLE `hr_positions` (
  `id` int(11) NOT NULL,
  `department_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hr_positions`
--

INSERT INTO `hr_positions` (`id`, `department_id`, `name`, `status`, `created_at`, `updated_at`) VALUES
(1, 2, 'HR Assistant', 'active', '2026-02-02 22:40:56', '2026-02-02 22:40:56'),
(2, 3, 'Staff Nurse', 'active', '2026-02-02 22:40:56', '2026-02-02 22:40:56'),
(3, 4, 'Med Tech', 'active', '2026-02-02 22:40:56', '2026-02-02 22:40:56');

-- --------------------------------------------------------

--
-- Table structure for table `hr_schedules`
--

CREATE TABLE `hr_schedules` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `shift_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `notes` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hr_schedules`
--

INSERT INTO `hr_schedules` (`id`, `employee_id`, `shift_date`, `start_time`, `end_time`, `notes`, `created_at`) VALUES
(1, 1, '2026-02-02', '08:00:00', '17:00:00', 'On-site', '2026-02-02 22:40:56'),
(2, 2, '2026-02-02', '07:00:00', '19:00:00', 'Overtime', '2026-02-02 22:40:56'),
(3, 3, '2026-02-02', '09:00:00', '18:00:00', NULL, '2026-02-02 22:40:56'),
(4, 1, '2026-02-01', '08:00:00', '17:00:00', NULL, '2026-02-02 22:40:56'),
(5, 1, '2026-01-31', '08:00:00', '17:00:00', NULL, '2026-02-02 22:40:56'),
(6, 2, '2026-01-31', '07:00:00', '19:00:00', NULL, '2026-02-02 22:40:56'),
(7, 1, '2026-01-30', '08:00:00', '17:00:00', NULL, '2026-02-02 22:40:56'),
(8, 3, '2026-01-30', '09:00:00', '18:00:00', NULL, '2026-02-02 22:40:56'),
(9, 1, '2026-01-29', '08:00:00', '17:00:00', 'On-site', '2026-02-02 22:40:56'),
(10, 2, '2026-01-29', '07:00:00', '19:00:00', NULL, '2026-02-02 22:40:56'),
(11, 1, '2026-01-28', '08:00:00', '17:00:00', NULL, '2026-02-02 22:40:56'),
(12, 1, '2026-01-27', '08:00:00', '17:00:00', NULL, '2026-02-02 22:40:56'),
(13, 2, '2026-01-27', '07:00:00', '19:00:00', 'Overtime', '2026-02-02 22:40:56'),
(14, 3, '2026-01-27', '09:00:00', '18:00:00', NULL, '2026-02-02 22:40:56'),
(15, 1, '2026-01-26', '08:00:00', '17:00:00', NULL, '2026-02-02 22:40:56'),
(16, 1, '2026-01-25', '08:00:00', '17:00:00', 'On-site', '2026-02-02 22:40:56'),
(17, 2, '2026-01-25', '07:00:00', '19:00:00', NULL, '2026-02-02 22:40:56'),
(18, 1, '2026-01-24', '08:00:00', '17:00:00', NULL, '2026-02-02 22:40:56'),
(19, 3, '2026-01-24', '09:00:00', '18:00:00', NULL, '2026-02-02 22:40:56');

-- --------------------------------------------------------

--
-- Table structure for table `icu_admissions`
--

CREATE TABLE `icu_admissions` (
  `id` int(11) NOT NULL,
  `patient_name` varchar(255) NOT NULL,
  `bed_id` int(11) NOT NULL,
  `admitted_at` datetime NOT NULL,
  `discharged_at` datetime DEFAULT NULL,
  `status` enum('admitted','discharged') NOT NULL DEFAULT 'admitted',
  `diagnosis` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `icu_admissions`
--

INSERT INTO `icu_admissions` (`id`, `patient_name`, `bed_id`, `admitted_at`, `discharged_at`, `status`, `diagnosis`, `created_at`) VALUES
(1, 'Juan Dela Cruz', 1, '2026-02-02 01:57:41', NULL, 'admitted', 'Sepsis', '2026-02-02 18:57:41'),
(2, 'Maria Santos', 2, '2026-02-02 13:57:41', NULL, 'admitted', 'Post-op monitoring', '2026-02-02 18:57:41'),
(3, 'Emily White', 3, '2026-02-01 13:57:41', NULL, 'admitted', 'Respiratory failure', '2026-02-02 18:57:41'),
(4, 'Michael Brown', 4, '2026-02-02 09:57:41', NULL, 'admitted', 'Severe pneumonia', '2026-02-02 18:57:41'),
(5, 'David Chen', 5, '2026-02-01 01:57:41', NULL, 'admitted', 'Cardiac arrest (recovery)', '2026-02-02 18:57:41'),
(6, 'Sarah Lee', 7, '2026-01-29 19:57:41', '2026-01-31 19:57:41', 'discharged', 'DKA', '2026-02-02 18:57:41');

-- --------------------------------------------------------

--
-- Table structure for table `icu_beds`
--

CREATE TABLE `icu_beds` (
  `id` int(11) NOT NULL,
  `bed_code` varchar(32) NOT NULL,
  `status` enum('available','occupied','cleaning','maintenance') NOT NULL DEFAULT 'available',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `icu_beds`
--

INSERT INTO `icu_beds` (`id`, `bed_code`, `status`, `created_at`) VALUES
(1, 'ICU-01', 'occupied', '2026-02-02 18:57:41'),
(2, 'ICU-02', 'occupied', '2026-02-02 18:57:41'),
(3, 'ICU-03', 'occupied', '2026-02-02 18:57:41'),
(4, 'ICU-04', 'occupied', '2026-02-02 18:57:41'),
(5, 'ICU-05', 'occupied', '2026-02-02 18:57:41'),
(6, 'ICU-06', 'available', '2026-02-02 18:57:41'),
(7, 'ICU-07', 'available', '2026-02-02 18:57:41'),
(8, 'ICU-08', 'available', '2026-02-02 18:57:41'),
(9, 'ICU-09', 'available', '2026-02-02 18:57:41'),
(10, 'ICU-10', 'available', '2026-02-02 18:57:41');

-- --------------------------------------------------------

--
-- Table structure for table `lab_requests`
--

CREATE TABLE `lab_requests` (
  `id` int(11) NOT NULL,
  `request_no` varchar(32) DEFAULT NULL,
  `patient_id` int(11) NOT NULL,
  `encounter_id` int(11) DEFAULT NULL,
  `source_unit` varchar(32) NOT NULL DEFAULT 'ER',
  `triage_level` tinyint(3) UNSIGNED DEFAULT NULL,
  `chief_complaint` varchar(255) DEFAULT NULL,
  `priority` enum('routine','urgent','stat') NOT NULL DEFAULT 'routine',
  `vitals_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`vitals_json`)),
  `notes` text DEFAULT NULL,
  `status` enum('pending_approval','approved','rejected','collected','in_progress','completed','cancelled') NOT NULL DEFAULT 'pending_approval',
  `cashier_status` varchar(32) DEFAULT NULL,
  `requested_by` varchar(255) DEFAULT NULL,
  `doctor_id` int(11) DEFAULT NULL,
  `requester_role` varchar(32) DEFAULT NULL,
  `approved_by` varchar(255) DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL,
  `rejection_reason` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lab_request_items`
--

CREATE TABLE `lab_request_items` (
  `id` int(11) NOT NULL,
  `request_id` int(11) NOT NULL,
  `test_code` varchar(64) NOT NULL,
  `test_name` varchar(255) NOT NULL,
  `specimen` varchar(64) DEFAULT NULL,
  `status` enum('pending','in_progress','completed','cancelled') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lab_results`
--

CREATE TABLE `lab_results` (
  `id` int(11) NOT NULL,
  `request_item_id` int(11) NOT NULL,
  `result_text` text DEFAULT NULL,
  `released_by` varchar(255) DEFAULT NULL,
  `released_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lab_test_fees`
--

CREATE TABLE `lab_test_fees` (
  `test_code` varchar(64) NOT NULL,
  `test_name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lab_test_fees`
--

INSERT INTO `lab_test_fees` (`test_code`, `test_name`, `price`, `created_at`, `updated_at`) VALUES
('bun', 'BUN', 500.00, '2026-02-05 04:27:43', '2026-02-05 04:27:43'),
('cbc', 'Complete Blood Count (CBC)', 600.00, '2026-02-05 04:27:51', '2026-02-05 04:27:51'),
('creatinine', 'Creatinine', 300.00, '2026-02-05 04:27:58', '2026-02-05 04:27:58'),
('ecg', 'Electrocardiogram (ECG)', 300.00, '2026-02-05 04:28:03', '2026-02-05 04:28:14'),
('electrolytes', 'Electrolytes (Na/K/Cl)', 1200.00, '2026-02-05 04:28:23', '2026-02-05 04:28:23'),
('fbs', 'Fasting Blood Sugar (FBS)', 500.00, '2026-02-05 04:28:33', '2026-02-05 04:28:33'),
('pregnancy', 'Pregnancy Test', 300.00, '2026-02-05 04:28:48', '2026-02-05 04:28:48'),
('rbs', 'Random Blood Sugar (RBS)', 200.00, '2026-02-05 04:28:53', '2026-02-05 04:28:53'),
('urinalysis', 'Urinalysis', 200.00, '2026-02-05 04:28:58', '2026-02-05 04:28:58'),
('xray', 'X-Ray', 1000.00, '2026-02-05 04:29:05', '2026-02-05 04:29:05');

-- --------------------------------------------------------

--
-- Table structure for table `opd_appointments`
--

CREATE TABLE `opd_appointments` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `doctor_name` varchar(255) NOT NULL,
  `appointment_at` datetime DEFAULT NULL,
  `status` enum('requested','scheduled','waiting','checked_in','in_consultation','completed','cancelled','no_show','rejected') NOT NULL DEFAULT 'requested',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `responded_at` timestamp NULL DEFAULT NULL,
  `approved_by_user_id` int(11) DEFAULT NULL,
  `nursing_assessment_id` int(11) DEFAULT NULL,
  `lab_tests_json` text DEFAULT NULL,
  `lab_note` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `opd_billing_items`
--

CREATE TABLE `opd_billing_items` (
  `id` int(11) NOT NULL,
  `appointment_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `created_by_user_id` int(11) DEFAULT NULL,
  `item_type` varchar(32) NOT NULL DEFAULT 'misc',
  `description` varchar(255) NOT NULL,
  `qty` int(11) NOT NULL DEFAULT 1,
  `unit_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `opd_consultation_notes`
--

CREATE TABLE `opd_consultation_notes` (
  `id` int(11) NOT NULL,
  `appointment_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `doctor_user_id` int(11) DEFAULT NULL,
  `doctor_name` varchar(255) DEFAULT NULL,
  `note_text` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `opd_fees`
--

CREATE TABLE `opd_fees` (
  `fee_code` varchar(64) NOT NULL,
  `fee_name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `opd_fees`
--

INSERT INTO `opd_fees` (`fee_code`, `fee_name`, `price`, `created_at`, `updated_at`) VALUES
('consultation', 'OPD Consultation', 500.00, '2026-01-31 19:35:33', '2026-01-31 19:35:33');

-- --------------------------------------------------------

--
-- Table structure for table `opd_nursing_assessments`
--

CREATE TABLE `opd_nursing_assessments` (
  `id` int(11) NOT NULL,
  `appointment_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `nurse_name` varchar(255) DEFAULT NULL,
  `triage_level` int(11) DEFAULT NULL,
  `vitals_json` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `assessment_json` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

CREATE TABLE `patients` (
  `id` int(11) NOT NULL,
  `patient_code` varchar(32) DEFAULT NULL,
  `philhealth_pin` varchar(32) DEFAULT NULL,
  `full_name` varchar(255) NOT NULL,
  `dob` date DEFAULT NULL,
  `sex` varchar(16) DEFAULT NULL,
  `contact` varchar(64) DEFAULT NULL,
  `civil_status` varchar(32) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `street_address` varchar(255) DEFAULT NULL,
  `barangay` varchar(128) DEFAULT NULL,
  `city` varchar(128) DEFAULT NULL,
  `province` varchar(128) DEFAULT NULL,
  `zip_code` varchar(16) DEFAULT NULL,
  `employer_name` varchar(255) DEFAULT NULL,
  `employer_address` varchar(255) DEFAULT NULL,
  `patient_type` varchar(32) DEFAULT NULL,
  `initial_location` varchar(64) DEFAULT NULL,
  `department` varchar(64) DEFAULT NULL,
  `diagnosis` varchar(255) DEFAULT NULL,
  `emergency_contact_name` varchar(255) DEFAULT NULL,
  `emergency_contact_relationship` varchar(64) DEFAULT NULL,
  `emergency_contact_phone` varchar(64) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `blood_type` varchar(16) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pharmacy_consultation_notes`
--

CREATE TABLE `pharmacy_consultation_notes` (
  `id` int(11) NOT NULL,
  `source_module` varchar(8) NOT NULL,
  `source_note_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `appointment_id` int(11) DEFAULT NULL,
  `encounter_id` int(11) DEFAULT NULL,
  `provider_name` varchar(255) DEFAULT NULL,
  `note_text` mediumtext NOT NULL,
  `note_created_at` datetime DEFAULT NULL,
  `submitted_by_user_id` int(11) DEFAULT NULL,
  `submitted_by_name` varchar(255) DEFAULT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pharmacy_medicines`
--

CREATE TABLE `pharmacy_medicines` (
  `id` int(11) NOT NULL,
  `medicine_code` varchar(64) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `min_quantity` int(11) NOT NULL DEFAULT 0,
  `price` decimal(10,2) DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `manufacturer` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pharmacy_medicines`
--

INSERT INTO `pharmacy_medicines` (`id`, `medicine_code`, `name`, `category`, `quantity`, `min_quantity`, `price`, `expiry_date`, `manufacturer`, `description`, `created_at`, `updated_at`) VALUES
(2, NULL, 'Amoxicillin 500mg', 'antibiotics', 300, 0, 20.00, '2026-02-22', 'Medline Pharma Inc.', NULL, '2026-02-05 04:40:13', '2026-02-05 04:40:13'),
(3, NULL, 'Azithromycin 250mg Tablets', 'painRelievers', 10, 0, 15.00, '2027-07-14', 'PharmaOne', NULL, '2026-02-05 04:40:40', '2026-02-05 04:40:40'),
(4, NULL, 'Amoxicillin 500mg Capsule', 'antibiotics', 60, 0, 14.00, '2026-02-17', 'HealthPharma Inc.', NULL, '2026-02-05 04:41:07', '2026-02-05 04:41:07'),
(5, NULL, 'Ascorbic Acid', 'vitamins', 60, 0, 20.00, '2026-02-25', 'Medline Pharma Inc.', NULL, '2026-02-05 04:41:35', '2026-02-05 04:41:35');

-- --------------------------------------------------------

--
-- Table structure for table `pharmacy_resits`
--

CREATE TABLE `pharmacy_resits` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `encounter_id` int(11) DEFAULT NULL,
  `prescribed_by` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pharmacy_resits`
--

INSERT INTO `pharmacy_resits` (`id`, `patient_id`, `encounter_id`, `prescribed_by`, `notes`, `created_at`, `updated_at`) VALUES
(1, 3, NULL, NULL, NULL, '2026-02-05 04:45:30', '2026-02-05 04:45:30');

-- --------------------------------------------------------

--
-- Table structure for table `pharmacy_resit_items`
--

CREATE TABLE `pharmacy_resit_items` (
  `id` int(11) NOT NULL,
  `resit_id` int(11) NOT NULL,
  `medicine_id` int(11) DEFAULT NULL,
  `medicine_name` varchar(255) NOT NULL,
  `qty` varchar(64) DEFAULT NULL,
  `instructions` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pharmacy_resit_items`
--

INSERT INTO `pharmacy_resit_items` (`id`, `resit_id`, `medicine_id`, `medicine_name`, `qty`, `instructions`, `created_at`) VALUES
(1, 1, 4, 'Amoxicillin 500mg Capsule', '10', 'asdwdawd', '2026-02-05 04:45:30');

-- --------------------------------------------------------

--
-- Table structure for table `philhealth_claims`
--

CREATE TABLE `philhealth_claims` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `status` varchar(32) NOT NULL DEFAULT 'draft',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `philhealth_claims_draft`
--

CREATE TABLE `philhealth_claims_draft` (
  `id` int(11) NOT NULL,
  `philhealth_pin` varchar(32) DEFAULT NULL,
  `status` varchar(32) NOT NULL DEFAULT 'draft',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `last_form_code` varchar(8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `philhealth_forms`
--

CREATE TABLE `philhealth_forms` (
  `claim_id` int(11) NOT NULL,
  `philhealth_pin` varchar(32) NOT NULL,
  `form_code` varchar(8) NOT NULL,
  `data_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`data_json`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `philhealth_forms_draft`
--

CREATE TABLE `philhealth_forms_draft` (
  `draft_id` int(11) NOT NULL,
  `form_code` varchar(8) NOT NULL,
  `data_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`data_json`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `philhealth_members`
--

CREATE TABLE `philhealth_members` (
  `patient_id` int(11) NOT NULL,
  `philhealth_pin` varchar(32) NOT NULL,
  `employer_name` varchar(255) DEFAULT NULL,
  `employer_address` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(64) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `password_hash` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `full_name`, `password_hash`, `status`, `created_at`, `updated_at`) VALUES
(1, 'doctor2@gmail.com', 'Doctor2', '$2y$10$YolUNeDgAqncEnYGVnjikuzEgfJX69a.14BXhc5HYxXdNggOOSbXm', 'active', '2026-01-31 17:14:00', '2026-01-31 17:14:00'),
(2, 'AdminHos@gmail.com', 'Admin', '$2y$10$VaLh1WFRWg.7Igm4oIcEjen98MGxZPAFZDqJHomlPoFiFDITfXqJi', 'active', '2026-02-01 09:05:40', '2026-02-03 10:14:50'),
(3, 'ernurse@gmail.com', 'ER Nurse', '$2y$10$ql9odIuR0JrN.F.9hTIAPedBSh/p42Aj7l2z7vvS/QZa.mDTe9USO', 'active', '2026-02-01 11:16:47', '2026-02-03 00:15:12'),
(4, 'np@gmail.com', 'NP User', '$2y$10$qHC/XnClsZ7ETWBSZcwnfO0NV878PJOZHfIBXkHRg0N.dyB1sLkZO', 'active', '2026-02-01 11:16:47', '2026-02-03 00:15:12'),
(5, 'pa@gmail.com', 'PA User', '$2y$10$azTMagIGh5VLyjYiIjfuv.A3fNKFEJYg6SkNiO3UKM5.69tOmBxZq', 'active', '2026-02-01 11:16:47', '2026-02-03 00:15:12'),
(6, 'medtech@gmail.com', 'MedTech', '$2y$10$VgcR3/Z3fWknPhNTie3fuORayCAlO.2q4pgK0Cgsn/kCyL8cPaHjW', 'active', '2026-02-01 11:16:47', '2026-02-03 00:15:12'),
(7, 'labsupervisor@gmail.com', 'Lab Supervisor', '$2y$10$/HCkUNCIvC9E8MerUynuoOQZfYZQhqOgkDb9HSoZTee2Hg7EQYrf2', 'active', '2026-02-01 11:16:47', '2026-02-03 00:15:12'),
(8, 'cashier@gmail.com', 'Cashier', '$2y$10$X1v5BwESpGtGDCOnhH2wROmJ5DsyCHXn4DSGLeCNF8oBYIjIRWgKG', 'active', '2026-02-01 11:16:47', '2026-02-03 00:15:13'),
(9, 'billing@gmail.com', 'Billing', '$2y$10$tgkVIE7Ma.K4AOA7tbWACus.pYMQ4bL5q5IHxRpLgZiEVC.1wNnhu', 'active', '2026-02-01 11:16:47', '2026-02-03 00:15:13'),
(10, 'pharmacist@gmail.com', 'Pharmacist', '$2y$10$cGMaOnUPrCDxclz8Vo0EhO3sk2GzF1HnsR6F/CGUTXyQjXsHqhDDm', 'active', '2026-02-01 11:16:47', '2026-02-03 00:15:13'),
(11, 'pharmacyassistant@gmail.com', 'Pharmacy Assistant', '$2y$10$BdRrogOLQ37TR2ubvDjoQei1OXg0n/Cyxa6mq5Ow7WK9a9JVuZjla', 'active', '2026-02-01 11:16:48', '2026-02-03 00:15:13'),
(12, 'opdnurse@gmail.com', 'OPD Nurse', '$2y$10$nAhk1mg2YpMcpEzzrwpyWOw2oUKq5i5fku.mMp4lyA9VTSp51Jwqy', 'active', '2026-02-01 11:16:48', '2026-02-03 00:15:13'),
(13, 'opdclerk@gmail.com', 'OPD Clerk', '$2y$10$5FHlFpEnMx2y4ItrWUoQr.o2dG7HFem7itaafgbgihnu//Oxps4QO', 'active', '2026-02-01 11:16:48', '2026-02-03 00:15:13'),
(14, 'hrstaff@gmail.com', 'HR Staff', '$2y$10$Sx7trRByxUTGmvBQL26vrejEcu4Xqb95/ajmQiVbcgfIznLwp3yFa', 'active', '2026-02-01 11:16:48', '2026-02-03 00:15:14'),
(15, 'hradmin@gmail.com', 'HR Admin', '$2y$10$FznEdmtDZhQeV2uCoOuIHOl.6V0gXIR/xLV9Ybbcf9Jd10vmThCte', 'active', '2026-02-01 11:16:48', '2026-02-03 00:15:14'),
(16, 'doctor3@gmail.com', 'Doctor3', '$2y$10$wqJtFNLmiWqECVBZDHGNSOqXroN5f0iZY9LKOn3VyRIIECYRI57TW', 'active', '2026-02-01 11:44:57', '2026-02-01 11:44:57'),
(17, 'icunurse@gmail.com', 'ICU Nurse', '$2y$10$B9weeJKPbyecKrzKZwXC9OWe4UroD/GAqpDcykxKYpxREoFpOvgaG', 'active', '2026-02-03 00:15:14', '2026-02-03 00:15:14'),
(18, 'icustaff@gmail.com', 'ICU Staff', '$2y$10$MFcojFAWYxNa5YGb5906i.b96y2sDxXh9iRr.zm7glIjMKcrE9VNm', 'active', '2026-02-03 00:15:14', '2026-02-03 00:15:14'),
(19, 'xraytech@gmail.com', 'Xray Tech', '$2y$10$GESigz.ElF.QUr1EkwtpfOXnc2iXM5OkqIiK.A9Rf5WEzaJmJkz5e', 'active', '2026-02-03 00:15:14', '2026-02-03 00:15:14'),
(20, 'radiologist@gmail.com', 'Radiologist', '$2y$10$JMnear3Piy5DvXZdQHPUQuZnxG4hoMnusO8jNVU.rXW0H9iQZhqI6', 'active', '2026-02-03 00:15:14', '2026-02-03 00:15:14'),
(21, 'doctor@gmail.com', 'Doctor', '$2y$10$7ctscMsvLQPlLMuJw7mHcuytg/JAao.SkobnZdGWa6dLDOL.JB7nW', 'active', '2026-02-03 00:15:14', '2026-02-03 00:15:14'),
(22, 'orstaff@gmail.com', 'Operating Room Staff', '$2y$10$4jIIiokflKogykog1p92pukTgYccmGy9lAoCBm/ucjd6U.KOXVs5q', 'active', '2026-02-03 00:15:15', '2026-02-03 00:15:15'),
(23, 'drstaff@gmail.com', 'Delivery Room Staff', '$2y$10$.eJwmAK.4UjQThOGCsKxJO5TecOV9lrXK.Ly.koYgJu7kAouKuNJO', 'active', '2026-02-03 00:15:15', '2026-02-03 00:15:15');

-- --------------------------------------------------------

--
-- Table structure for table `user_roles`
--

CREATE TABLE `user_roles` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `module` varchar(32) NOT NULL,
  `role` varchar(64) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_roles`
--

INSERT INTO `user_roles` (`id`, `user_id`, `module`, `role`, `created_at`) VALUES
(1, 1, 'DOCTOR', 'Doctor', '2026-01-31 17:14:00'),
(2, 2, 'ADMIN', 'Administrator', '2026-02-01 09:05:40'),
(16, 16, 'DOCTOR', 'Doctor', '2026-02-01 11:44:57'),
(17, 3, 'ER', 'ER Nurse', '2026-02-03 00:15:12'),
(18, 4, 'ER', 'NP/PA', '2026-02-03 00:15:12'),
(19, 5, 'ER', 'NP/PA', '2026-02-03 00:15:12'),
(20, 6, 'LAB', 'MedTech', '2026-02-03 00:15:12'),
(21, 7, 'LAB', 'Lab Supervisor', '2026-02-03 00:15:12'),
(22, 8, 'CASHIER', 'Cashier', '2026-02-03 00:15:13'),
(23, 9, 'CASHIER', 'Billing', '2026-02-03 00:15:13'),
(24, 10, 'PHARMACY', 'Pharmacist', '2026-02-03 00:15:13'),
(25, 11, 'PHARMACY', 'Pharmacy Assistant', '2026-02-03 00:15:13'),
(26, 12, 'OPD', 'OPD Nurse', '2026-02-03 00:15:13'),
(27, 13, 'OPD', 'OPD Clerk', '2026-02-03 00:15:13'),
(28, 14, 'HR', 'HR Staff', '2026-02-03 00:15:14'),
(29, 15, 'HR', 'HR Admin', '2026-02-03 00:15:14'),
(30, 17, 'ICU', 'ICU Nurse', '2026-02-03 00:15:14'),
(31, 18, 'ICU', 'ICU Staff', '2026-02-03 00:15:14'),
(32, 19, 'XRAY', 'Xray Tech', '2026-02-03 00:15:14'),
(33, 20, 'XRAY', 'Radiologist', '2026-02-03 00:15:14'),
(34, 21, 'DOCTOR', 'Doctor', '2026-02-03 00:15:14'),
(35, 22, 'DOCTOR', 'Operating Room Staff', '2026-02-03 00:15:15'),
(36, 23, 'DOCTOR', 'Delivery Room Staff', '2026-02-03 00:15:15');

-- --------------------------------------------------------

--
-- Table structure for table `xray_orders`
--

CREATE TABLE `xray_orders` (
  `id` int(11) NOT NULL,
  `patient_name` varchar(255) NOT NULL,
  `exam_type` varchar(128) NOT NULL,
  `priority` enum('routine','urgent','stat') NOT NULL DEFAULT 'routine',
  `status` enum('requested','scheduled','in_progress','completed','reported','cancelled') NOT NULL DEFAULT 'requested',
  `ordered_at` datetime NOT NULL,
  `scheduled_at` datetime DEFAULT NULL,
  `completed_at` datetime DEFAULT NULL,
  `technologist_name` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `xray_orders`
--

INSERT INTO `xray_orders` (`id`, `patient_name`, `exam_type`, `priority`, `status`, `ordered_at`, `scheduled_at`, `completed_at`, `technologist_name`, `notes`, `created_at`) VALUES
(1, 'Juan Dela Cruz', 'Chest X-ray', 'urgent', 'reported', '2026-02-02 16:58:07', '2026-02-02 17:58:07', '2026-02-02 18:58:07', 'Tech Rivera', 'Rule out pneumonia', '2026-02-02 18:58:07'),
(2, 'Maria Santos', 'Extremity X-ray', 'routine', 'completed', '2026-02-02 14:58:07', '2026-02-02 15:58:07', '2026-02-02 16:58:07', 'Tech Santos', 'Post-fall assessment', '2026-02-02 18:58:07'),
(3, 'Emily White', 'Chest X-ray', 'stat', 'in_progress', '2026-02-02 18:58:07', '2026-02-02 19:58:07', NULL, 'Tech Lee', 'Shortness of breath', '2026-02-02 18:58:07'),
(4, 'Michael Brown', 'Abdomen X-ray', 'routine', 'scheduled', '2026-02-02 17:58:07', '2026-02-02 18:58:07', NULL, 'Tech Rivera', 'Abdominal pain', '2026-02-02 18:58:07'),
(5, 'David Chen', 'Spine X-ray', 'urgent', 'requested', '2026-02-02 19:58:07', NULL, NULL, 'Tech Santos', 'Back pain', '2026-02-02 18:58:07'),
(6, 'Patient D3D94', 'Abdomen X-ray', 'urgent', 'reported', '2026-02-01 10:00:00', '2026-02-01 10:10:00', '2026-02-01 10:20:00', 'Tech Santos', NULL, '2026-02-02 18:58:07'),
(7, 'Patient 6512B', 'Skull X-ray', 'routine', 'reported', '2026-02-01 10:45:00', '2026-02-01 10:55:00', '2026-02-01 11:13:00', 'Tech Lee', NULL, '2026-02-02 18:58:07'),
(8, 'Patient C20AD', 'Extremity X-ray', 'routine', 'reported', '2026-02-01 11:30:00', '2026-02-01 11:40:00', '2026-02-01 12:06:00', 'Tech Rivera', NULL, '2026-02-02 18:58:07'),
(9, 'Patient C51CE', 'Spine X-ray', 'routine', 'reported', '2026-02-01 12:15:00', '2026-02-01 12:25:00', '2026-02-01 12:59:00', 'Tech Santos', NULL, '2026-02-02 18:58:07'),
(10, 'Patient 98F13', 'Skull X-ray', 'urgent', 'reported', '2026-01-31 11:00:00', '2026-01-31 11:10:00', '2026-01-31 11:20:00', 'Tech Lee', NULL, '2026-02-02 18:58:07'),
(11, 'Patient 3C59D', 'Extremity X-ray', 'routine', 'reported', '2026-01-31 11:45:00', '2026-01-31 11:55:00', '2026-01-31 12:13:00', 'Tech Rivera', NULL, '2026-02-02 18:58:07'),
(12, 'Patient B6D76', 'Spine X-ray', 'routine', 'reported', '2026-01-31 12:30:00', '2026-01-31 12:40:00', '2026-01-31 13:06:00', 'Tech Santos', NULL, '2026-02-02 18:58:07'),
(13, 'Patient 37693', 'Chest X-ray', 'routine', 'reported', '2026-01-31 13:15:00', '2026-01-31 13:25:00', '2026-01-31 13:59:00', 'Tech Lee', NULL, '2026-02-02 18:58:07'),
(14, 'Patient 1FF1D', 'Abdomen X-ray', 'routine', 'reported', '2026-01-31 14:00:00', '2026-01-31 14:10:00', '2026-01-31 14:52:00', 'Tech Rivera', NULL, '2026-02-02 18:58:07'),
(15, 'Patient 34173', 'Extremity X-ray', 'urgent', 'reported', '2026-01-30 12:00:00', '2026-01-30 12:10:00', '2026-01-30 12:20:00', 'Tech Rivera', NULL, '2026-02-02 18:58:07'),
(16, 'Patient C16A5', 'Spine X-ray', 'routine', 'reported', '2026-01-30 12:45:00', '2026-01-30 12:55:00', '2026-01-30 13:13:00', 'Tech Santos', NULL, '2026-02-02 18:58:07'),
(17, 'Patient 6364D', 'Chest X-ray', 'routine', 'reported', '2026-01-30 13:30:00', '2026-01-30 13:40:00', '2026-01-30 14:06:00', 'Tech Lee', NULL, '2026-02-02 18:58:07'),
(18, 'Patient 182BE', 'Abdomen X-ray', 'routine', 'reported', '2026-01-30 14:15:00', '2026-01-30 14:25:00', '2026-01-30 14:59:00', 'Tech Rivera', NULL, '2026-02-02 18:58:07'),
(19, 'Patient E3698', 'Skull X-ray', 'routine', 'reported', '2026-01-30 15:00:00', '2026-01-30 15:10:00', '2026-01-30 15:52:00', 'Tech Santos', NULL, '2026-02-02 18:58:07'),
(20, 'Patient 1C383', 'Extremity X-ray', 'urgent', 'reported', '2026-01-30 15:45:00', '2026-01-30 15:55:00', '2026-01-30 16:45:00', 'Tech Lee', NULL, '2026-02-02 18:58:07'),
(21, 'Patient D6459', 'Spine X-ray', 'urgent', 'reported', '2026-01-29 13:00:00', '2026-01-29 13:10:00', '2026-01-29 13:20:00', 'Tech Santos', NULL, '2026-02-02 18:58:07'),
(22, 'Patient 3416A', 'Chest X-ray', 'routine', 'reported', '2026-01-29 13:45:00', '2026-01-29 13:55:00', '2026-01-29 14:13:00', 'Tech Lee', NULL, '2026-02-02 18:58:07'),
(23, 'Patient A1D0C', 'Abdomen X-ray', 'routine', 'reported', '2026-01-29 14:30:00', '2026-01-29 14:40:00', '2026-01-29 15:06:00', 'Tech Rivera', NULL, '2026-02-02 18:58:07'),
(24, 'Patient C0C7C', 'Chest X-ray', 'urgent', 'reported', '2026-01-28 14:00:00', '2026-01-28 14:10:00', '2026-01-28 14:20:00', 'Tech Lee', NULL, '2026-02-02 18:58:07'),
(25, 'Patient 28380', 'Abdomen X-ray', 'routine', 'reported', '2026-01-28 14:45:00', '2026-01-28 14:55:00', '2026-01-28 15:13:00', 'Tech Rivera', NULL, '2026-02-02 18:58:07'),
(26, 'Patient 9A115', 'Skull X-ray', 'routine', 'reported', '2026-01-28 15:30:00', '2026-01-28 15:40:00', '2026-01-28 16:06:00', 'Tech Santos', NULL, '2026-02-02 18:58:07'),
(27, 'Patient D82C8', 'Extremity X-ray', 'routine', 'reported', '2026-01-28 16:15:00', '2026-01-28 16:25:00', '2026-01-28 16:59:00', 'Tech Lee', NULL, '2026-02-02 18:58:07'),
(28, 'Patient 072B0', 'Abdomen X-ray', 'urgent', 'reported', '2026-01-27 09:00:00', '2026-01-27 09:10:00', '2026-01-27 09:20:00', 'Tech Rivera', NULL, '2026-02-02 18:58:07'),
(29, 'Patient 7F39F', 'Skull X-ray', 'routine', 'reported', '2026-01-27 09:45:00', '2026-01-27 09:55:00', '2026-01-27 10:13:00', 'Tech Santos', NULL, '2026-02-02 18:58:07'),
(30, 'Patient 44F68', 'Extremity X-ray', 'routine', 'reported', '2026-01-27 10:30:00', '2026-01-27 10:40:00', '2026-01-27 11:06:00', 'Tech Lee', NULL, '2026-02-02 18:58:07'),
(31, 'Patient 03AFD', 'Spine X-ray', 'routine', 'reported', '2026-01-27 11:15:00', '2026-01-27 11:25:00', '2026-01-27 11:59:00', 'Tech Rivera', NULL, '2026-02-02 18:58:07'),
(32, 'Patient EA5D2', 'Chest X-ray', 'routine', 'reported', '2026-01-27 12:00:00', '2026-01-27 12:10:00', '2026-01-27 12:52:00', 'Tech Santos', NULL, '2026-02-02 18:58:07'),
(33, 'Patient 7CBBC', 'Skull X-ray', 'urgent', 'reported', '2026-01-26 10:00:00', '2026-01-26 10:10:00', '2026-01-26 10:20:00', 'Tech Santos', NULL, '2026-02-02 18:58:07'),
(34, 'Patient E2C42', 'Extremity X-ray', 'routine', 'reported', '2026-01-26 10:45:00', '2026-01-26 10:55:00', '2026-01-26 11:13:00', 'Tech Lee', NULL, '2026-02-02 18:58:07'),
(35, 'Patient 32BB9', 'Spine X-ray', 'routine', 'reported', '2026-01-26 11:30:00', '2026-01-26 11:40:00', '2026-01-26 12:06:00', 'Tech Rivera', NULL, '2026-02-02 18:58:07'),
(36, 'Patient D2DDE', 'Chest X-ray', 'routine', 'reported', '2026-01-26 12:15:00', '2026-01-26 12:25:00', '2026-01-26 12:59:00', 'Tech Santos', NULL, '2026-02-02 18:58:07'),
(37, 'Patient AD61A', 'Abdomen X-ray', 'routine', 'reported', '2026-01-26 13:00:00', '2026-01-26 13:10:00', '2026-01-26 13:52:00', 'Tech Lee', NULL, '2026-02-02 18:58:07'),
(38, 'Patient D09BF', 'Skull X-ray', 'urgent', 'reported', '2026-01-26 13:45:00', '2026-01-26 13:55:00', '2026-01-26 14:45:00', 'Tech Rivera', NULL, '2026-02-02 18:58:07'),
(39, 'Patient F033A', 'Extremity X-ray', 'urgent', 'reported', '2026-01-25 11:00:00', '2026-01-25 11:10:00', '2026-01-25 11:20:00', 'Tech Lee', NULL, '2026-02-02 18:58:07'),
(40, 'Patient 43EC5', 'Spine X-ray', 'routine', 'reported', '2026-01-25 11:45:00', '2026-01-25 11:55:00', '2026-01-25 12:13:00', 'Tech Rivera', NULL, '2026-02-02 18:58:07'),
(41, 'Patient 9778D', 'Chest X-ray', 'routine', 'reported', '2026-01-25 12:30:00', '2026-01-25 12:40:00', '2026-01-25 13:06:00', 'Tech Santos', NULL, '2026-02-02 18:58:07'),
(42, 'Patient 86139', 'Spine X-ray', 'urgent', 'reported', '2026-01-24 12:00:00', '2026-01-24 12:10:00', '2026-01-24 12:20:00', 'Tech Rivera', NULL, '2026-02-02 18:58:07'),
(43, 'Patient 54229', 'Chest X-ray', 'routine', 'reported', '2026-01-24 12:45:00', '2026-01-24 12:55:00', '2026-01-24 13:13:00', 'Tech Santos', NULL, '2026-02-02 18:58:07'),
(44, 'Patient 92CC2', 'Abdomen X-ray', 'routine', 'reported', '2026-01-24 13:30:00', '2026-01-24 13:40:00', '2026-01-24 14:06:00', 'Tech Lee', NULL, '2026-02-02 18:58:07'),
(45, 'Patient 98DCE', 'Skull X-ray', 'routine', 'reported', '2026-01-24 14:15:00', '2026-01-24 14:25:00', '2026-01-24 14:59:00', 'Tech Rivera', NULL, '2026-02-02 18:58:07'),
(46, 'Patient F8991', 'Chest X-ray', 'urgent', 'reported', '2026-01-23 13:00:00', '2026-01-23 13:10:00', '2026-01-23 13:20:00', 'Tech Santos', NULL, '2026-02-02 18:58:07'),
(47, 'Patient 38B3E', 'Abdomen X-ray', 'routine', 'reported', '2026-01-23 13:45:00', '2026-01-23 13:55:00', '2026-01-23 14:13:00', 'Tech Lee', NULL, '2026-02-02 18:58:08'),
(48, 'Patient EC895', 'Skull X-ray', 'routine', 'reported', '2026-01-23 14:30:00', '2026-01-23 14:40:00', '2026-01-23 15:06:00', 'Tech Rivera', NULL, '2026-02-02 18:58:08'),
(49, 'Patient 6974C', 'Extremity X-ray', 'routine', 'reported', '2026-01-23 15:15:00', '2026-01-23 15:25:00', '2026-01-23 15:59:00', 'Tech Santos', NULL, '2026-02-02 18:58:08'),
(50, 'Patient C9E10', 'Spine X-ray', 'routine', 'reported', '2026-01-23 16:00:00', '2026-01-23 16:10:00', '2026-01-23 16:52:00', 'Tech Lee', NULL, '2026-02-02 18:58:08'),
(51, 'Anthony', 'Chest X-ray', 'urgent', 'reported', '2026-02-04 04:38:45', '2026-02-04 05:38:45', '2026-02-04 06:38:45', 'Tech Santos', 'Cough and fever; rule out pneumonia.', '2026-02-04 09:38:45');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cashier_charges`
--
ALTER TABLE `cashier_charges`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_cashier_charges_patient` (`patient_id`),
  ADD KEY `idx_cashier_charges_status` (`status`),
  ADD KEY `idx_cashier_charges_source` (`source_module`,`source_id`),
  ADD KEY `idx_cashier_charges_encounter` (`encounter_id`);

--
-- Indexes for table `cashier_charge_items`
--
ALTER TABLE `cashier_charge_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_cashier_charge_items_charge` (`charge_id`),
  ADD KEY `idx_cashier_charge_items_med_id` (`medicine_id`);

--
-- Indexes for table `cashier_invoices`
--
ALTER TABLE `cashier_invoices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_cashier_invoices_patient` (`patient_id`),
  ADD KEY `idx_cashier_invoices_status` (`status`),
  ADD KEY `idx_cashier_invoices_charge` (`charge_id`),
  ADD KEY `idx_cashier_invoices_encounter` (`encounter_id`);

--
-- Indexes for table `cashier_invoice_items`
--
ALTER TABLE `cashier_invoice_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_cashier_invoice_items_invoice` (`invoice_id`);

--
-- Indexes for table `cashier_payments`
--
ALTER TABLE `cashier_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_cashier_payments_invoice` (`invoice_id`);

--
-- Indexes for table `chat_files`
--
ALTER TABLE `chat_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_chat_files_thread` (`thread_id`,`id`),
  ADD KEY `idx_chat_files_message` (`message_id`),
  ADD KEY `fk_chat_files_uploader` (`uploader_id`);

--
-- Indexes for table `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_chat_messages_thread` (`thread_id`,`id`),
  ADD KEY `fk_chat_messages_sender` (`sender_id`);

--
-- Indexes for table `chat_threads`
--
ALTER TABLE `chat_threads`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_chat_threads_type_module` (`type`,`module`),
  ADD UNIQUE KEY `uniq_chat_thread` (`type`,`module_a`,`module_b`),
  ADD KEY `idx_chat_threads_updated` (`updated_at`),
  ADD KEY `fk_chat_threads_created_by` (`created_by`),
  ADD KEY `idx_chat_threads_type` (`type`),
  ADD KEY `idx_chat_threads_modules` (`module_a`,`module_b`);

--
-- Indexes for table `chat_thread_members`
--
ALTER TABLE `chat_thread_members`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_chat_thread_members` (`thread_id`,`user_id`),
  ADD KEY `idx_chat_thread_members_user` (`user_id`);

--
-- Indexes for table `chat_thread_reads`
--
ALTER TABLE `chat_thread_reads`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_chat_thread_reads` (`thread_id`,`user_id`),
  ADD KEY `idx_chat_thread_reads_user` (`user_id`);

--
-- Indexes for table `dialysis_machines`
--
ALTER TABLE `dialysis_machines`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `machine_code` (`machine_code`);

--
-- Indexes for table `dialysis_patients`
--
ALTER TABLE `dialysis_patients`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `patient_code` (`patient_code`);

--
-- Indexes for table `dialysis_sessions`
--
ALTER TABLE `dialysis_sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_dialysis_sessions_patient` (`patient_id`),
  ADD KEY `fk_dialysis_sessions_machine` (`machine_id`),
  ADD KEY `idx_dialysis_sessions_start` (`start_time`),
  ADD KEY `idx_dialysis_sessions_status` (`status`);

--
-- Indexes for table `doctor_availability`
--
ALTER TABLE `doctor_availability`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `idx_doctor_availability_status` (`status`);

--
-- Indexes for table `encounters`
--
ALTER TABLE `encounters`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `encounter_no` (`encounter_no`),
  ADD KEY `idx_encounters_patient` (`patient_id`),
  ADD KEY `idx_encounters_status` (`status`),
  ADD KEY `idx_encounters_started` (`started_at`);

--
-- Indexes for table `er_assessment_submissions`
--
ALTER TABLE `er_assessment_submissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_er_assess_sub_doc` (`doctor_id`),
  ADD KEY `idx_er_assess_sub_docname` (`doctor_name`),
  ADD KEY `idx_er_assess_sub_patient` (`patient_id`),
  ADD KEY `idx_er_assess_sub_assess` (`er_assessment_id`),
  ADD KEY `idx_er_assess_sub_encounter` (`encounter_id`);

--
-- Indexes for table `er_clearance_requests`
--
ALTER TABLE `er_clearance_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_er_clearance_status` (`status`),
  ADD KEY `idx_er_clearance_type` (`clearance_type`),
  ADD KEY `idx_er_clearance_patient` (`patient_id`),
  ADD KEY `idx_er_clearance_encounter` (`encounter_id`),
  ADD KEY `idx_er_clearance_requested_at` (`requested_at`);

--
-- Indexes for table `er_consultation_notes`
--
ALTER TABLE `er_consultation_notes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_er_consult_notes_encounter` (`encounter_id`),
  ADD KEY `idx_er_consult_notes_patient` (`patient_id`),
  ADD KEY `idx_er_consult_notes_author` (`author_user_id`);

--
-- Indexes for table `er_doctor_feedback`
--
ALTER TABLE `er_doctor_feedback`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_er_doc_feedback_encounter` (`encounter_id`),
  ADD KEY `idx_er_doc_feedback_patient` (`patient_id`),
  ADD KEY `idx_er_doc_feedback_assess` (`er_assessment_id`);

--
-- Indexes for table `er_nursing_assessments`
--
ALTER TABLE `er_nursing_assessments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_er_nurse_assess_encounter` (`encounter_id`),
  ADD KEY `idx_er_nurse_assess_patient` (`patient_id`);

--
-- Indexes for table `er_ward_assignments`
--
ALTER TABLE `er_ward_assignments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_er_ward_assignments_bed` (`bed_id`),
  ADD KEY `idx_er_ward_assignments_patient` (`patient_id`),
  ADD KEY `idx_er_ward_assignments_encounter` (`encounter_id`),
  ADD KEY `idx_er_ward_assignments_track` (`track`),
  ADD KEY `idx_er_ward_assignments_discharged` (`discharged_at`),
  ADD KEY `idx_er_ward_assignments_assigned` (`assigned_at`);

--
-- Indexes for table `er_ward_beds`
--
ALTER TABLE `er_ward_beds`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `bed_code` (`bed_code`),
  ADD KEY `idx_er_ward_beds_station` (`station`),
  ADD KEY `idx_er_ward_beds_status` (`status`);

--
-- Indexes for table `er_ward_discharge`
--
ALTER TABLE `er_ward_discharge`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `assignment_id` (`assignment_id`),
  ADD KEY `idx_er_ward_discharge_ready` (`ready`);

--
-- Indexes for table `er_ward_notes`
--
ALTER TABLE `er_ward_notes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_er_ward_notes_assignment` (`assignment_id`),
  ADD KEY `idx_er_ward_notes_patient` (`patient_id`),
  ADD KEY `idx_er_ward_notes_encounter` (`encounter_id`),
  ADD KEY `fk_er_ward_notes_author` (`author_user_id`);

--
-- Indexes for table `hr_departments`
--
ALTER TABLE `hr_departments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `idx_hr_departments_status` (`status`);

--
-- Indexes for table `hr_employees`
--
ALTER TABLE `hr_employees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `employee_code` (`employee_code`),
  ADD KEY `idx_hr_employees_name` (`full_name`),
  ADD KEY `idx_hr_employees_status` (`status`),
  ADD KEY `idx_hr_employees_dept` (`department_id`),
  ADD KEY `idx_hr_employees_pos` (`position_id`);

--
-- Indexes for table `hr_positions`
--
ALTER TABLE `hr_positions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_hr_positions` (`department_id`,`name`),
  ADD KEY `idx_hr_positions_dept` (`department_id`),
  ADD KEY `idx_hr_positions_status` (`status`);

--
-- Indexes for table `hr_schedules`
--
ALTER TABLE `hr_schedules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_hr_schedules_emp_date` (`employee_id`,`shift_date`),
  ADD KEY `idx_hr_schedules_date` (`shift_date`);

--
-- Indexes for table `icu_admissions`
--
ALTER TABLE `icu_admissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_icu_admissions_bed` (`bed_id`),
  ADD KEY `idx_icu_admissions_admitted_at` (`admitted_at`),
  ADD KEY `idx_icu_admissions_status` (`status`);

--
-- Indexes for table `icu_beds`
--
ALTER TABLE `icu_beds`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `bed_code` (`bed_code`);

--
-- Indexes for table `lab_requests`
--
ALTER TABLE `lab_requests`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `request_no` (`request_no`),
  ADD KEY `idx_lab_requests_status` (`status`),
  ADD KEY `idx_lab_requests_patient` (`patient_id`),
  ADD KEY `idx_lab_requests_created` (`created_at`),
  ADD KEY `idx_lab_requests_encounter` (`encounter_id`);

--
-- Indexes for table `lab_request_items`
--
ALTER TABLE `lab_request_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_lab_items_request` (`request_id`);

--
-- Indexes for table `lab_results`
--
ALTER TABLE `lab_results`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_lab_results_item` (`request_item_id`);

--
-- Indexes for table `lab_test_fees`
--
ALTER TABLE `lab_test_fees`
  ADD PRIMARY KEY (`test_code`);

--
-- Indexes for table `opd_appointments`
--
ALTER TABLE `opd_appointments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_opd_appt_at` (`appointment_at`),
  ADD KEY `idx_opd_appt_status` (`status`),
  ADD KEY `idx_opd_appt_patient` (`patient_id`),
  ADD KEY `idx_opd_appt_approved_by` (`approved_by_user_id`);

--
-- Indexes for table `opd_billing_items`
--
ALTER TABLE `opd_billing_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_opd_billing_items_appt` (`appointment_id`),
  ADD KEY `idx_opd_billing_items_patient` (`patient_id`),
  ADD KEY `fk_opd_billing_items_created_by` (`created_by_user_id`);

--
-- Indexes for table `opd_consultation_notes`
--
ALTER TABLE `opd_consultation_notes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_opd_consult_notes_appt` (`appointment_id`),
  ADD KEY `idx_opd_consult_notes_patient` (`patient_id`),
  ADD KEY `idx_opd_consult_notes_doctor` (`doctor_user_id`);

--
-- Indexes for table `opd_fees`
--
ALTER TABLE `opd_fees`
  ADD PRIMARY KEY (`fee_code`);

--
-- Indexes for table `opd_nursing_assessments`
--
ALTER TABLE `opd_nursing_assessments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_opd_nurse_assess_appt` (`appointment_id`),
  ADD KEY `idx_opd_nurse_assess_patient` (`patient_id`);

--
-- Indexes for table `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `philhealth_pin` (`philhealth_pin`),
  ADD UNIQUE KEY `patient_code` (`patient_code`);

--
-- Indexes for table `pharmacy_consultation_notes`
--
ALTER TABLE `pharmacy_consultation_notes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_pharmacy_consult_source` (`source_module`,`source_note_id`),
  ADD KEY `idx_pharmacy_consult_patient` (`patient_id`),
  ADD KEY `idx_pharmacy_consult_submitted` (`submitted_at`);

--
-- Indexes for table `pharmacy_medicines`
--
ALTER TABLE `pharmacy_medicines`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_pharmacy_medicines_code` (`medicine_code`),
  ADD KEY `idx_pharmacy_medicines_name` (`name`),
  ADD KEY `idx_pharmacy_medicines_category` (`category`);

--
-- Indexes for table `pharmacy_resits`
--
ALTER TABLE `pharmacy_resits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_pharmacy_resits_patient` (`patient_id`),
  ADD KEY `idx_pharmacy_resits_created` (`created_at`),
  ADD KEY `idx_pharmacy_resits_encounter` (`encounter_id`);

--
-- Indexes for table `pharmacy_resit_items`
--
ALTER TABLE `pharmacy_resit_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_pharmacy_resit_items_resit` (`resit_id`),
  ADD KEY `idx_pharmacy_resit_items_medicine_id` (`medicine_id`);

--
-- Indexes for table `philhealth_claims`
--
ALTER TABLE `philhealth_claims`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `patient_id` (`patient_id`);

--
-- Indexes for table `philhealth_claims_draft`
--
ALTER TABLE `philhealth_claims_draft`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_philhealth_claims_draft_pin` (`philhealth_pin`);

--
-- Indexes for table `philhealth_forms`
--
ALTER TABLE `philhealth_forms`
  ADD PRIMARY KEY (`claim_id`,`form_code`),
  ADD KEY `idx_philhealth_forms_pin` (`philhealth_pin`);

--
-- Indexes for table `philhealth_forms_draft`
--
ALTER TABLE `philhealth_forms_draft`
  ADD PRIMARY KEY (`draft_id`,`form_code`);

--
-- Indexes for table `philhealth_members`
--
ALTER TABLE `philhealth_members`
  ADD PRIMARY KEY (`patient_id`),
  ADD UNIQUE KEY `philhealth_pin` (`philhealth_pin`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `idx_users_status` (`status`),
  ADD KEY `idx_users_name` (`full_name`);

--
-- Indexes for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_user_roles` (`user_id`,`module`,`role`),
  ADD KEY `idx_user_roles_user` (`user_id`),
  ADD KEY `idx_user_roles_module` (`module`);

--
-- Indexes for table `xray_orders`
--
ALTER TABLE `xray_orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_xray_orders_ordered_at` (`ordered_at`),
  ADD KEY `idx_xray_orders_status` (`status`),
  ADD KEY `idx_xray_orders_exam_type` (`exam_type`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cashier_charges`
--
ALTER TABLE `cashier_charges`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `cashier_charge_items`
--
ALTER TABLE `cashier_charge_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `cashier_invoices`
--
ALTER TABLE `cashier_invoices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `cashier_invoice_items`
--
ALTER TABLE `cashier_invoice_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `cashier_payments`
--
ALTER TABLE `cashier_payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `chat_files`
--
ALTER TABLE `chat_files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chat_messages`
--
ALTER TABLE `chat_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `chat_threads`
--
ALTER TABLE `chat_threads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14445;

--
-- AUTO_INCREMENT for table `chat_thread_members`
--
ALTER TABLE `chat_thread_members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=485;

--
-- AUTO_INCREMENT for table `chat_thread_reads`
--
ALTER TABLE `chat_thread_reads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=990;

--
-- AUTO_INCREMENT for table `dialysis_machines`
--
ALTER TABLE `dialysis_machines`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dialysis_patients`
--
ALTER TABLE `dialysis_patients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dialysis_sessions`
--
ALTER TABLE `dialysis_sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `encounters`
--
ALTER TABLE `encounters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `er_assessment_submissions`
--
ALTER TABLE `er_assessment_submissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `er_clearance_requests`
--
ALTER TABLE `er_clearance_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `er_consultation_notes`
--
ALTER TABLE `er_consultation_notes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `er_doctor_feedback`
--
ALTER TABLE `er_doctor_feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `er_nursing_assessments`
--
ALTER TABLE `er_nursing_assessments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `er_ward_assignments`
--
ALTER TABLE `er_ward_assignments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `er_ward_beds`
--
ALTER TABLE `er_ward_beds`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `er_ward_discharge`
--
ALTER TABLE `er_ward_discharge`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `er_ward_notes`
--
ALTER TABLE `er_ward_notes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hr_departments`
--
ALTER TABLE `hr_departments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `hr_employees`
--
ALTER TABLE `hr_employees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `hr_positions`
--
ALTER TABLE `hr_positions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `hr_schedules`
--
ALTER TABLE `hr_schedules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `icu_admissions`
--
ALTER TABLE `icu_admissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `icu_beds`
--
ALTER TABLE `icu_beds`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `lab_requests`
--
ALTER TABLE `lab_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `lab_request_items`
--
ALTER TABLE `lab_request_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT for table `lab_results`
--
ALTER TABLE `lab_results`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `opd_appointments`
--
ALTER TABLE `opd_appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `opd_billing_items`
--
ALTER TABLE `opd_billing_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `opd_consultation_notes`
--
ALTER TABLE `opd_consultation_notes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `opd_nursing_assessments`
--
ALTER TABLE `opd_nursing_assessments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `patients`
--
ALTER TABLE `patients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pharmacy_consultation_notes`
--
ALTER TABLE `pharmacy_consultation_notes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `pharmacy_medicines`
--
ALTER TABLE `pharmacy_medicines`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `pharmacy_resits`
--
ALTER TABLE `pharmacy_resits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pharmacy_resit_items`
--
ALTER TABLE `pharmacy_resit_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `philhealth_claims`
--
ALTER TABLE `philhealth_claims`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT for table `philhealth_claims_draft`
--
ALTER TABLE `philhealth_claims_draft`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `user_roles`
--
ALTER TABLE `user_roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `xray_orders`
--
ALTER TABLE `xray_orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cashier_charges`
--
ALTER TABLE `cashier_charges`
  ADD CONSTRAINT `fk_cashier_charges_encounter` FOREIGN KEY (`encounter_id`) REFERENCES `encounters` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_cashier_charges_patient` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`);

--
-- Constraints for table `cashier_charge_items`
--
ALTER TABLE `cashier_charge_items`
  ADD CONSTRAINT `fk_cashier_charge_items_charge` FOREIGN KEY (`charge_id`) REFERENCES `cashier_charges` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cashier_invoices`
--
ALTER TABLE `cashier_invoices`
  ADD CONSTRAINT `fk_cashier_invoices_encounter` FOREIGN KEY (`encounter_id`) REFERENCES `encounters` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_cashier_invoices_patient` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`);

--
-- Constraints for table `cashier_invoice_items`
--
ALTER TABLE `cashier_invoice_items`
  ADD CONSTRAINT `fk_cashier_invoice_items_invoice` FOREIGN KEY (`invoice_id`) REFERENCES `cashier_invoices` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cashier_payments`
--
ALTER TABLE `cashier_payments`
  ADD CONSTRAINT `fk_cashier_payments_invoice` FOREIGN KEY (`invoice_id`) REFERENCES `cashier_invoices` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `chat_files`
--
ALTER TABLE `chat_files`
  ADD CONSTRAINT `fk_chat_files_message` FOREIGN KEY (`message_id`) REFERENCES `chat_messages` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_chat_files_thread` FOREIGN KEY (`thread_id`) REFERENCES `chat_threads` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_chat_files_uploader` FOREIGN KEY (`uploader_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD CONSTRAINT `fk_chat_messages_sender` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_chat_messages_thread` FOREIGN KEY (`thread_id`) REFERENCES `chat_threads` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `chat_threads`
--
ALTER TABLE `chat_threads`
  ADD CONSTRAINT `fk_chat_threads_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `chat_thread_members`
--
ALTER TABLE `chat_thread_members`
  ADD CONSTRAINT `fk_chat_thread_members_thread` FOREIGN KEY (`thread_id`) REFERENCES `chat_threads` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_chat_thread_members_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `chat_thread_reads`
--
ALTER TABLE `chat_thread_reads`
  ADD CONSTRAINT `fk_chat_thread_reads_thread` FOREIGN KEY (`thread_id`) REFERENCES `chat_threads` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_chat_thread_reads_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `dialysis_sessions`
--
ALTER TABLE `dialysis_sessions`
  ADD CONSTRAINT `fk_dialysis_sessions_machine` FOREIGN KEY (`machine_id`) REFERENCES `dialysis_machines` (`id`),
  ADD CONSTRAINT `fk_dialysis_sessions_patient` FOREIGN KEY (`patient_id`) REFERENCES `dialysis_patients` (`id`);

--
-- Constraints for table `doctor_availability`
--
ALTER TABLE `doctor_availability`
  ADD CONSTRAINT `fk_doctor_availability_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `er_assessment_submissions`
--
ALTER TABLE `er_assessment_submissions`
  ADD CONSTRAINT `fk_er_assess_sub_assess` FOREIGN KEY (`er_assessment_id`) REFERENCES `er_nursing_assessments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_er_assess_sub_encounter` FOREIGN KEY (`encounter_id`) REFERENCES `encounters` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_er_assess_sub_patient` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`);

--
-- Constraints for table `er_consultation_notes`
--
ALTER TABLE `er_consultation_notes`
  ADD CONSTRAINT `fk_er_consult_notes_author` FOREIGN KEY (`author_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_er_consult_notes_encounter` FOREIGN KEY (`encounter_id`) REFERENCES `encounters` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_er_consult_notes_patient` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`);

--
-- Constraints for table `er_doctor_feedback`
--
ALTER TABLE `er_doctor_feedback`
  ADD CONSTRAINT `fk_er_doc_feedback_assess` FOREIGN KEY (`er_assessment_id`) REFERENCES `er_nursing_assessments` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_er_doc_feedback_encounter` FOREIGN KEY (`encounter_id`) REFERENCES `encounters` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_er_doc_feedback_patient` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`);

--
-- Constraints for table `er_nursing_assessments`
--
ALTER TABLE `er_nursing_assessments`
  ADD CONSTRAINT `fk_er_nurse_assess_encounter` FOREIGN KEY (`encounter_id`) REFERENCES `encounters` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_er_nurse_assess_patient` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`);

--
-- Constraints for table `lab_requests`
--
ALTER TABLE `lab_requests`
  ADD CONSTRAINT `fk_lab_requests_encounter` FOREIGN KEY (`encounter_id`) REFERENCES `encounters` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `opd_appointments`
--
ALTER TABLE `opd_appointments`
  ADD CONSTRAINT `fk_opd_appt_patient` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`);

--
-- Constraints for table `opd_consultation_notes`
--
ALTER TABLE `opd_consultation_notes`
  ADD CONSTRAINT `fk_opd_consult_notes_appt` FOREIGN KEY (`appointment_id`) REFERENCES `opd_appointments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_opd_consult_notes_doctor` FOREIGN KEY (`doctor_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_opd_consult_notes_patient` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`);

--
-- Constraints for table `opd_nursing_assessments`
--
ALTER TABLE `opd_nursing_assessments`
  ADD CONSTRAINT `fk_opd_nurse_assess_appt` FOREIGN KEY (`appointment_id`) REFERENCES `opd_appointments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_opd_nurse_assess_patient` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`);

--
-- Constraints for table `pharmacy_resits`
--
ALTER TABLE `pharmacy_resits`
  ADD CONSTRAINT `fk_pharmacy_resits_encounter` FOREIGN KEY (`encounter_id`) REFERENCES `encounters` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD CONSTRAINT `fk_user_roles_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
