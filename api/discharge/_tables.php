<?php
declare(strict_types=1);

require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../admissions/_tables.php';
require_once __DIR__ . '/../users/_tables.php';

function ensure_discharge_tables(PDO $pdo): void
{
    ensure_admissions_tables($pdo);
    ensure_users_tables($pdo);

    // Discharge plans
    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS discharge_plans (
            id INT AUTO_INCREMENT PRIMARY KEY,
            plan_no VARCHAR(32) NULL UNIQUE,
            admission_id INT NOT NULL,
            patient_id INT NOT NULL,
            expected_discharge_date DATE NULL,
            actual_discharge_date DATETIME NULL,
            discharge_destination ENUM('home','rehab','nursing_home','transfer','expired','absconded') NOT NULL DEFAULT 'home',
            discharge_diagnosis TEXT NULL,
            discharge_condition ENUM('improved','stable','deteriorated','expired') NULL,
            discharge_notes TEXT NULL,
            medications_on_discharge TEXT NULL,
            diet_instructions TEXT NULL,
            activity_restrictions TEXT NULL,
            wound_care_instructions TEXT NULL,
            return_precautions TEXT NULL,
            status ENUM('planning','pending_orders','pending_clearance','cleared','discharged','cancelled') NOT NULL DEFAULT 'planning',
            planned_by_user_id INT NULL,
            planned_by VARCHAR(255) NULL,
            cleared_by_user_id INT NULL,
            cleared_by VARCHAR(255) NULL,
            cleared_at DATETIME NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            CONSTRAINT fk_discharge_plans_admission FOREIGN KEY (admission_id) REFERENCES admissions(id) ON DELETE RESTRICT,
            CONSTRAINT fk_discharge_plans_patient FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE RESTRICT,
            CONSTRAINT fk_discharge_plans_planner FOREIGN KEY (planned_by_user_id) REFERENCES users(id) ON DELETE SET NULL,
            CONSTRAINT fk_discharge_plans_clearer FOREIGN KEY (cleared_by_user_id) REFERENCES users(id) ON DELETE SET NULL,
            UNIQUE KEY uniq_discharge_plan_admission (admission_id),
            INDEX idx_discharge_plans_patient (patient_id),
            INDEX idx_discharge_plans_status (status),
            INDEX idx_discharge_plans_expected (expected_discharge_date)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
    );

    // Discharge clearance checklist per department
    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS discharge_clearances (
            id INT AUTO_INCREMENT PRIMARY KEY,
            discharge_plan_id INT NOT NULL,
            admission_id INT NOT NULL,
            patient_id INT NOT NULL,
            department ENUM('nursing','physician','pharmacy','cashier','laboratory','radiology','social_work') NOT NULL,
            status ENUM('pending','cleared','not_required') NOT NULL DEFAULT 'pending',
            cleared_by VARCHAR(255) NULL,
            cleared_at DATETIME NULL,
            remarks TEXT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            CONSTRAINT fk_disc_clear_plan FOREIGN KEY (discharge_plan_id) REFERENCES discharge_plans(id) ON DELETE CASCADE,
            CONSTRAINT fk_disc_clear_admission FOREIGN KEY (admission_id) REFERENCES admissions(id) ON DELETE CASCADE,
            CONSTRAINT fk_disc_clear_patient FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE RESTRICT,
            UNIQUE KEY uniq_clearance_dept (discharge_plan_id, department),
            INDEX idx_disc_clear_plan (discharge_plan_id),
            INDEX idx_disc_clear_patient (patient_id),
            INDEX idx_disc_clear_status (status)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
    );

    // Follow-up appointments post-discharge
    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS discharge_followups (
            id INT AUTO_INCREMENT PRIMARY KEY,
            discharge_plan_id INT NOT NULL,
            patient_id INT NOT NULL,
            followup_date DATE NOT NULL,
            followup_time TIME NULL,
            department VARCHAR(64) NULL,
            physician VARCHAR(255) NULL,
            reason TEXT NULL,
            notes TEXT NULL,
            status ENUM('scheduled','completed','missed','cancelled') NOT NULL DEFAULT 'scheduled',
            created_by_user_id INT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            CONSTRAINT fk_disc_followup_plan FOREIGN KEY (discharge_plan_id) REFERENCES discharge_plans(id) ON DELETE CASCADE,
            CONSTRAINT fk_disc_followup_patient FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE RESTRICT,
            CONSTRAINT fk_disc_followup_user FOREIGN KEY (created_by_user_id) REFERENCES users(id) ON DELETE SET NULL,
            INDEX idx_disc_followup_plan (discharge_plan_id),
            INDEX idx_disc_followup_patient (patient_id),
            INDEX idx_disc_followup_date (followup_date)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
    );
}
