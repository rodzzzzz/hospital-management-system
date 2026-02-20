-- Queue Management System Database Schema
-- Created for Hospital Queue Management System

-- 1. Queue Stations Table
CREATE TABLE IF NOT EXISTS `queue_stations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `station_name` enum('opd','doctor','pharmacy','cashier') NOT NULL,
  `station_display_name` varchar(100) NOT NULL,
  `station_order` int(11) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_station_name` (`station_name`),
  KEY `idx_station_order` (`station_order`),
  KEY `idx_is_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 2. Patient Queue Table
CREATE TABLE IF NOT EXISTS `patient_queue` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `patient_id` int(11) NOT NULL,
  `station_id` int(11) NOT NULL,
  `queue_number` int(11) NOT NULL,
  `queue_position` int(11) NOT NULL,
  `status` enum('waiting','in_progress','completed','cancelled','skipped') NOT NULL DEFAULT 'waiting',
  `arrived_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `started_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `staff_user_id` int(11) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `estimated_wait_minutes` int(11) DEFAULT NULL,
  `service_duration_minutes` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_patient_id` (`patient_id`),
  KEY `idx_station_id` (`station_id`),
  KEY `idx_queue_number` (`queue_number`),
  KEY `idx_queue_position` (`queue_position`),
  KEY `idx_status` (`status`),
  KEY `idx_arrived_at` (`arrived_at`),
  KEY `idx_station_status_position` (`station_id`, `status`, `queue_position`),
  CONSTRAINT `fk_patient_queue_patient` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_patient_queue_station` FOREIGN KEY (`station_id`) REFERENCES `queue_stations` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_patient_queue_staff` FOREIGN KEY (`staff_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 3. Queue Transfers Table (Audit Log)
CREATE TABLE IF NOT EXISTS `queue_transfers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `patient_id` int(11) NOT NULL,
  `from_station_id` int(11) DEFAULT NULL,
  `to_station_id` int(11) NOT NULL,
  `transferred_by` int(11) NOT NULL,
  `transfer_reason` enum('automatic','manual','completed','cancelled') NOT NULL DEFAULT 'automatic',
  `notes` text DEFAULT NULL,
  `transferred_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_patient_id` (`patient_id`),
  KEY `idx_from_station_id` (`from_station_id`),
  KEY `idx_to_station_id` (`to_station_id`),
  KEY `idx_transferred_by` (`transferred_by`),
  KEY `idx_transferred_at` (`transferred_at`),
  CONSTRAINT `fk_queue_transfers_patient` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_queue_transfers_from_station` FOREIGN KEY (`from_station_id`) REFERENCES `queue_stations` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_queue_transfers_to_station` FOREIGN KEY (`to_station_id`) REFERENCES `queue_stations` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_queue_transfers_user` FOREIGN KEY (`transferred_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 4. Queue Settings Table
CREATE TABLE IF NOT EXISTS `queue_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `station_id` int(11) NOT NULL,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_station_setting` (`station_id`, `setting_key`),
  KEY `idx_setting_key` (`setting_key`),
  CONSTRAINT `fk_queue_settings_station` FOREIGN KEY (`station_id`) REFERENCES `queue_stations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert default stations
INSERT INTO `queue_stations` (`station_name`, `station_display_name`, `station_order`) VALUES
('opd', 'Out-Patient Department', 1),
('doctor', 'Doctor\'s Office', 2),
('pharmacy', 'Pharmacy', 3),
('cashier', 'Cashier', 4);

-- Insert default settings
INSERT INTO `queue_settings` (`station_id`, `setting_key`, `setting_value`) VALUES
(1, 'average_service_time', '15'),
(1, 'queue_prefix', 'OPD'),
(2, 'average_service_time', '20'),
(2, 'queue_prefix', 'DOC'),
(3, 'average_service_time', '10'),
(3, 'queue_prefix', 'PHR'),
(4, 'average_service_time', '5'),
(4, 'queue_prefix', 'CSH'),
(1, 'display_refresh_interval', '10'),
(2, 'display_refresh_interval', '10'),
(3, 'display_refresh_interval', '10'),
(4, 'display_refresh_interval', '10'),
(1, 'sound_enabled', '1'),
(2, 'sound_enabled', '1'),
(3, 'sound_enabled', '1'),
(4, 'sound_enabled', '1');
