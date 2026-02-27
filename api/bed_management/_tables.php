<?php
declare(strict_types=1);

require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../admissions/_tables.php';

function ensure_bed_management_tables(PDO $pdo): void
{
    ensure_admissions_tables($pdo);

    // Rooms per ward
    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS ward_rooms (
            id INT AUTO_INCREMENT PRIMARY KEY,
            ward VARCHAR(64) NOT NULL,
            room_no VARCHAR(32) NOT NULL,
            room_type ENUM('ward','semi-private','private','icu') NOT NULL DEFAULT 'ward',
            floor VARCHAR(16) NULL,
            notes TEXT NULL,
            is_active TINYINT(1) NOT NULL DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            UNIQUE KEY uniq_ward_room (ward, room_no),
            INDEX idx_ward_rooms_ward (ward),
            INDEX idx_ward_rooms_active (is_active)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
    );

    // Beds per room
    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS ward_beds (
            id INT AUTO_INCREMENT PRIMARY KEY,
            room_id INT NOT NULL,
            ward VARCHAR(64) NOT NULL,
            bed_code VARCHAR(32) NOT NULL UNIQUE,
            status ENUM('available','occupied','cleaning','maintenance') NOT NULL DEFAULT 'available',
            current_admission_id INT NULL,
            current_patient_id INT NULL,
            notes TEXT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            CONSTRAINT fk_ward_beds_room FOREIGN KEY (room_id) REFERENCES ward_rooms(id) ON DELETE RESTRICT,
            CONSTRAINT fk_ward_beds_admission FOREIGN KEY (current_admission_id) REFERENCES admissions(id) ON DELETE SET NULL,
            CONSTRAINT fk_ward_beds_patient FOREIGN KEY (current_patient_id) REFERENCES patients(id) ON DELETE SET NULL,
            INDEX idx_ward_beds_room (room_id),
            INDEX idx_ward_beds_ward (ward),
            INDEX idx_ward_beds_status (status)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
    );

    // Room transfer history
    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS room_transfers (
            id INT AUTO_INCREMENT PRIMARY KEY,
            transfer_no VARCHAR(32) NULL UNIQUE,
            admission_id INT NOT NULL,
            patient_id INT NOT NULL,
            from_bed_id INT NULL,
            from_bed_code VARCHAR(32) NULL,
            from_room_no VARCHAR(32) NULL,
            from_ward VARCHAR(64) NULL,
            to_bed_id INT NOT NULL,
            to_bed_code VARCHAR(32) NOT NULL,
            to_room_no VARCHAR(32) NULL,
            to_ward VARCHAR(64) NOT NULL,
            reason VARCHAR(255) NULL,
            transferred_by_user_id INT NULL,
            transferred_by VARCHAR(255) NULL,
            transferred_at DATETIME NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            CONSTRAINT fk_room_transfers_admission FOREIGN KEY (admission_id) REFERENCES admissions(id) ON DELETE RESTRICT,
            CONSTRAINT fk_room_transfers_patient FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE RESTRICT,
            CONSTRAINT fk_room_transfers_to_bed FOREIGN KEY (to_bed_id) REFERENCES ward_beds(id) ON DELETE RESTRICT,
            INDEX idx_room_transfers_admission (admission_id),
            INDEX idx_room_transfers_patient (patient_id),
            INDEX idx_room_transfers_ward (to_ward),
            INDEX idx_room_transfers_date (transferred_at)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
    );

    // Housekeeping log
    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS bed_housekeeping (
            id INT AUTO_INCREMENT PRIMARY KEY,
            bed_id INT NOT NULL,
            bed_code VARCHAR(32) NOT NULL,
            ward VARCHAR(64) NOT NULL,
            action ENUM('cleaning_started','cleaning_done','maintenance_started','maintenance_done','inspected') NOT NULL,
            performed_by VARCHAR(255) NULL,
            performed_at DATETIME NOT NULL,
            notes TEXT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            CONSTRAINT fk_housekeeping_bed FOREIGN KEY (bed_id) REFERENCES ward_beds(id) ON DELETE CASCADE,
            INDEX idx_housekeeping_bed (bed_id),
            INDEX idx_housekeeping_ward (ward),
            INDEX idx_housekeeping_date (performed_at)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
    );
}
