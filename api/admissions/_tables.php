<?php
declare(strict_types=1);

require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../encounters/_tables.php';
require_once __DIR__ . '/../users/_tables.php';

function ensure_admissions_tables(PDO $pdo): void
{
    ensure_encounter_tables($pdo);
    ensure_users_tables($pdo);

    // Pre-admission records
    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS pre_admissions (
            id INT AUTO_INCREMENT PRIMARY KEY,
            pre_admission_no VARCHAR(32) NULL UNIQUE,
            patient_id INT NOT NULL,
            scheduled_date DATE NOT NULL,
            scheduled_time TIME NULL,
            ward VARCHAR(64) NULL,
            procedure_name VARCHAR(255) NULL,
            admitting_physician VARCHAR(255) NULL,
            notes TEXT NULL,
            status ENUM('scheduled','admitted','cancelled') NOT NULL DEFAULT 'scheduled',
            created_by_user_id INT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            CONSTRAINT fk_pre_admissions_patient FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE RESTRICT,
            CONSTRAINT fk_pre_admissions_user FOREIGN KEY (created_by_user_id) REFERENCES users(id) ON DELETE SET NULL,
            INDEX idx_pre_admissions_patient (patient_id),
            INDEX idx_pre_admissions_status (status),
            INDEX idx_pre_admissions_scheduled (scheduled_date)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
    );

    // Admission records (IPD)
    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS admissions (
            id INT AUTO_INCREMENT PRIMARY KEY,
            admission_no VARCHAR(32) NULL UNIQUE,
            patient_id INT NOT NULL,
            encounter_id INT NULL,
            pre_admission_id INT NULL,
            admission_type ENUM('emergency','scheduled','transfer') NOT NULL DEFAULT 'scheduled',
            ward VARCHAR(64) NOT NULL,
            room_no VARCHAR(32) NULL,
            bed_id INT NULL,
            admitting_physician VARCHAR(255) NULL,
            admitting_diagnosis TEXT NULL,
            admission_date DATETIME NOT NULL,
            discharge_date DATETIME NULL,
            status ENUM('admitted','discharged','transferred','absconded') NOT NULL DEFAULT 'admitted',
            philhealth_pin VARCHAR(64) NULL,
            insurance_info VARCHAR(255) NULL,
            allergy_notes TEXT NULL,
            diet_notes TEXT NULL,
            special_instructions TEXT NULL,
            created_by_user_id INT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            CONSTRAINT fk_admissions_patient FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE RESTRICT,
            CONSTRAINT fk_admissions_encounter FOREIGN KEY (encounter_id) REFERENCES encounters(id) ON DELETE SET NULL,
            CONSTRAINT fk_admissions_pre FOREIGN KEY (pre_admission_id) REFERENCES pre_admissions(id) ON DELETE SET NULL,
            CONSTRAINT fk_admissions_user FOREIGN KEY (created_by_user_id) REFERENCES users(id) ON DELETE SET NULL,
            INDEX idx_admissions_patient (patient_id),
            INDEX idx_admissions_status (status),
            INDEX idx_admissions_ward (ward),
            INDEX idx_admissions_date (admission_date)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
    );

    // Admission nursing assessment (collected at bedside)
    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS admission_assessments (
            id INT AUTO_INCREMENT PRIMARY KEY,
            admission_id INT NOT NULL,
            patient_id INT NOT NULL,
            height_cm DECIMAL(5,2) NULL,
            weight_kg DECIMAL(5,2) NULL,
            temperature DECIMAL(4,1) NULL,
            blood_pressure VARCHAR(16) NULL,
            pulse_rate SMALLINT UNSIGNED NULL,
            respiratory_rate SMALLINT UNSIGNED NULL,
            oxygen_saturation TINYINT UNSIGNED NULL,
            pain_scale TINYINT UNSIGNED NULL,
            chief_complaint TEXT NULL,
            history_present_illness TEXT NULL,
            past_medical_history TEXT NULL,
            family_history TEXT NULL,
            social_history TEXT NULL,
            current_medications TEXT NULL,
            allergies TEXT NULL,
            immunization_history TEXT NULL,
            diet_restrictions TEXT NULL,
            mobility_status VARCHAR(64) NULL,
            fall_risk ENUM('low','medium','high') NULL DEFAULT 'low',
            code_status ENUM('full_code','dnr','comfort_care') NULL DEFAULT 'full_code',
            assessed_by_user_id INT NULL,
            assessed_by VARCHAR(255) NULL,
            assessed_at DATETIME NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            CONSTRAINT fk_adm_assess_admission FOREIGN KEY (admission_id) REFERENCES admissions(id) ON DELETE CASCADE,
            CONSTRAINT fk_adm_assess_patient FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE RESTRICT,
            CONSTRAINT fk_adm_assess_user FOREIGN KEY (assessed_by_user_id) REFERENCES users(id) ON DELETE SET NULL,
            UNIQUE KEY uniq_admission_assessment (admission_id),
            INDEX idx_adm_assess_patient (patient_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
    );

    // Migrate: add columns if missing
    foreach ([
        "ALTER TABLE admissions ADD COLUMN pre_admission_id INT NULL AFTER encounter_id",
        "ALTER TABLE admissions ADD COLUMN allergy_notes TEXT NULL AFTER insurance_info",
        "ALTER TABLE admissions ADD COLUMN diet_notes TEXT NULL AFTER allergy_notes",
        "ALTER TABLE admissions ADD COLUMN special_instructions TEXT NULL AFTER diet_notes",
    ] as $sql) {
        try { $pdo->exec($sql); } catch (Throwable $e) {}
    }
}
