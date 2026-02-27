-- Queue Return Requests Table (receiver-initiated corrections)
-- This is the "Reverse QEC" — receiving station flags a wrongly received patient
-- and notifies the origin station, which can confirm or reject.

CREATE TABLE IF NOT EXISTS `queue_return_requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `queue_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `requesting_station_id` int(11) NOT NULL COMMENT 'Station B — where the patient currently is',
  `origin_station_id` int(11) NOT NULL COMMENT 'Station A — who sent the patient here',
  `suggested_station_id` int(11) NOT NULL COMMENT 'Where Station B thinks the patient should go',
  `requested_by` int(11) NOT NULL COMMENT 'Station B staff who filed the request',
  `responded_by` int(11) DEFAULT NULL COMMENT 'Station A staff who confirmed/rejected',
  `status` enum('pending','confirmed','rejected') NOT NULL DEFAULT 'pending',
  `request_notes` text DEFAULT NULL COMMENT 'Reason from Station B',
  `rejection_reason` text DEFAULT NULL COMMENT 'Reason from Station A if rejected',
  `requested_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `responded_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_queue_id` (`queue_id`),
  KEY `idx_patient_id` (`patient_id`),
  KEY `idx_requesting_station_id` (`requesting_station_id`),
  KEY `idx_origin_station_id` (`origin_station_id`),
  KEY `idx_suggested_station_id` (`suggested_station_id`),
  KEY `idx_status` (`status`),
  KEY `idx_requested_at` (`requested_at`),
  CONSTRAINT `fk_qrr_queue` FOREIGN KEY (`queue_id`) REFERENCES `patient_queue` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_qrr_patient` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_qrr_requesting_station` FOREIGN KEY (`requesting_station_id`) REFERENCES `queue_stations` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_qrr_origin_station` FOREIGN KEY (`origin_station_id`) REFERENCES `queue_stations` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_qrr_suggested_station` FOREIGN KEY (`suggested_station_id`) REFERENCES `queue_stations` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_qrr_requested_by` FOREIGN KEY (`requested_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_qrr_responded_by` FOREIGN KEY (`responded_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
