<?php
declare(strict_types=1);

require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../encounters/_tables.php';
require_once __DIR__ . '/../users/_tables.php';
require_once __DIR__ . '/../admissions/_tables.php';

function ensure_ward_management_tables(PDO $pdo): void
{
    ensure_encounter_tables($pdo);
    ensure_users_tables($pdo);
    ensure_admissions_tables($pdo);

    // Nurse's notes per ward patient
    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS ward_notes (
            id INT AUTO_INCREMENT PRIMARY KEY,
            admission_id INT NOT NULL,
            patient_id INT NOT NULL,
            ward VARCHAR(64) NOT NULL,
            note_type ENUM('assessment','medication','vital_signs','general','physician_order','incident') NOT NULL DEFAULT 'general',
            note_text TEXT NOT NULL,
            vitals_json JSON NULL,
            author_user_id INT NULL,
            author_name VARCHAR(255) NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            CONSTRAINT fk_ward_notes_admission FOREIGN KEY (admission_id) REFERENCES admissions(id) ON DELETE CASCADE,
            CONSTRAINT fk_ward_notes_patient FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE RESTRICT,
            CONSTRAINT fk_ward_notes_user FOREIGN KEY (author_user_id) REFERENCES users(id) ON DELETE SET NULL,
            INDEX idx_ward_notes_admission (admission_id),
            INDEX idx_ward_notes_patient (patient_id),
            INDEX idx_ward_notes_ward (ward),
            INDEX idx_ward_notes_type (note_type),
            INDEX idx_ward_notes_created (created_at)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
    );

    // Vital signs records (separate from notes for charting/trending)
    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS ward_vitals (
            id INT AUTO_INCREMENT PRIMARY KEY,
            admission_id INT NOT NULL,
            patient_id INT NOT NULL,
            ward VARCHAR(64) NOT NULL,
            temperature DECIMAL(4,1) NULL,
            blood_pressure VARCHAR(16) NULL,
            pulse_rate SMALLINT UNSIGNED NULL,
            respiratory_rate SMALLINT UNSIGNED NULL,
            oxygen_saturation TINYINT UNSIGNED NULL,
            pain_scale TINYINT UNSIGNED NULL,
            weight_kg DECIMAL(5,2) NULL,
            blood_glucose DECIMAL(6,2) NULL,
            recorded_by_user_id INT NULL,
            recorded_by VARCHAR(255) NULL,
            recorded_at DATETIME NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            CONSTRAINT fk_ward_vitals_admission FOREIGN KEY (admission_id) REFERENCES admissions(id) ON DELETE CASCADE,
            CONSTRAINT fk_ward_vitals_patient FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE RESTRICT,
            CONSTRAINT fk_ward_vitals_user FOREIGN KEY (recorded_by_user_id) REFERENCES users(id) ON DELETE SET NULL,
            INDEX idx_ward_vitals_admission (admission_id),
            INDEX idx_ward_vitals_patient (patient_id),
            INDEX idx_ward_vitals_recorded (recorded_at)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
    );

    // Physician orders per ward patient
    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS ward_physician_orders (
            id INT AUTO_INCREMENT PRIMARY KEY,
            admission_id INT NOT NULL,
            patient_id INT NOT NULL,
            ward VARCHAR(64) NOT NULL,
            order_type ENUM('medication','diet','activity','procedure','lab','imaging','other') NOT NULL DEFAULT 'other',
            order_text TEXT NOT NULL,
            ordered_by VARCHAR(255) NULL,
            ordered_at DATETIME NOT NULL,
            status ENUM('active','completed','cancelled','on_hold') NOT NULL DEFAULT 'active',
            noted_by VARCHAR(255) NULL,
            noted_at DATETIME NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            CONSTRAINT fk_ward_orders_admission FOREIGN KEY (admission_id) REFERENCES admissions(id) ON DELETE CASCADE,
            CONSTRAINT fk_ward_orders_patient FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE RESTRICT,
            INDEX idx_ward_orders_admission (admission_id),
            INDEX idx_ward_orders_patient (patient_id),
            INDEX idx_ward_orders_status (status)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
    );
}
