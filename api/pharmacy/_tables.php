<?php
declare(strict_types=1);

require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../encounters/_tables.php';

function ensure_pharmacy_tables(PDO $pdo): void
{
    ensure_encounter_tables($pdo);
    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS pharmacy_medicines (
            id INT AUTO_INCREMENT PRIMARY KEY,
            medicine_code VARCHAR(64) NULL,
            name VARCHAR(255) NOT NULL,
            category VARCHAR(100) NULL,
            quantity INT NOT NULL DEFAULT 0,
            min_quantity INT NOT NULL DEFAULT 0,
            price DECIMAL(10,2) NULL,
            expiry_date DATE NULL,
            manufacturer VARCHAR(255) NULL,
            description TEXT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            UNIQUE KEY uniq_pharmacy_medicines_code (medicine_code),
            INDEX idx_pharmacy_medicines_name (name),
            INDEX idx_pharmacy_medicines_category (category)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
    );

    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS pharmacy_resits (
            id INT AUTO_INCREMENT PRIMARY KEY,
            patient_id INT NOT NULL,
            encounter_id INT NULL,
            prescribed_by VARCHAR(255) NULL,
            notes TEXT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            CONSTRAINT fk_pharmacy_resits_patient FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE RESTRICT,
            CONSTRAINT fk_pharmacy_resits_encounter FOREIGN KEY (encounter_id) REFERENCES encounters(id) ON DELETE SET NULL,
            INDEX idx_pharmacy_resits_patient (patient_id),
            INDEX idx_pharmacy_resits_encounter (encounter_id),
            INDEX idx_pharmacy_resits_created (created_at)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
    );

    try {
        $col = $pdo->query("SHOW COLUMNS FROM pharmacy_resits LIKE 'encounter_id'")->fetch();
        if (!$col) {
            $pdo->exec("ALTER TABLE pharmacy_resits ADD COLUMN encounter_id INT NULL AFTER patient_id");
        }
    } catch (Throwable $e) {
    }

    try {
        $pdo->exec("ALTER TABLE pharmacy_resits ADD INDEX idx_pharmacy_resits_encounter (encounter_id)");
    } catch (Throwable $e) {
    }

    try {
        $pdo->exec("ALTER TABLE pharmacy_resits ADD CONSTRAINT fk_pharmacy_resits_encounter FOREIGN KEY (encounter_id) REFERENCES encounters(id) ON DELETE SET NULL");
    } catch (Throwable $e) {
    }

    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS pharmacy_resit_items (
            id INT AUTO_INCREMENT PRIMARY KEY,
            resit_id INT NOT NULL,
            medicine_name VARCHAR(255) NOT NULL,
            qty VARCHAR(64) NULL,
            instructions VARCHAR(255) NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            CONSTRAINT fk_pharmacy_resit_items_resit FOREIGN KEY (resit_id) REFERENCES pharmacy_resits(id) ON DELETE CASCADE,
            INDEX idx_pharmacy_resit_items_resit (resit_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
    );

    try {
        $pdo->exec('ALTER TABLE pharmacy_resit_items ADD COLUMN medicine_id INT NULL AFTER resit_id');
    } catch (Throwable $e) {
    }

    try {
        $pdo->exec('ALTER TABLE pharmacy_resit_items ADD INDEX idx_pharmacy_resit_items_medicine_id (medicine_id)');
    } catch (Throwable $e) {
    }

    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS pharmacy_consultation_notes (
            id INT AUTO_INCREMENT PRIMARY KEY,
            source_module VARCHAR(8) NOT NULL,
            source_note_id INT NOT NULL,
            patient_id INT NOT NULL,
            appointment_id INT NULL,
            encounter_id INT NULL,
            provider_name VARCHAR(255) NULL,
            note_text MEDIUMTEXT NOT NULL,
            note_created_at DATETIME NULL,
            submitted_by_user_id INT NULL,
            submitted_by_name VARCHAR(255) NULL,
            submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            CONSTRAINT fk_pharmacy_consult_notes_patient FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE RESTRICT,
            UNIQUE KEY uniq_pharmacy_consult_source (source_module, source_note_id),
            INDEX idx_pharmacy_consult_patient (patient_id),
            INDEX idx_pharmacy_consult_submitted (submitted_at)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
    );
}
