<?php
declare(strict_types=1);

require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../encounters/_tables.php';

function ensure_lab_tables(PDO $pdo): void
{
    ensure_encounter_tables($pdo);
    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS lab_requests (
            id INT AUTO_INCREMENT PRIMARY KEY,
            request_no VARCHAR(32) NULL UNIQUE,
            patient_id INT NOT NULL,
            encounter_id INT NULL,
            source_unit VARCHAR(32) NOT NULL DEFAULT 'ER',
            triage_level TINYINT UNSIGNED NULL,
            chief_complaint VARCHAR(255) NULL,
            priority ENUM('routine','urgent','stat') NOT NULL DEFAULT 'routine',
            vitals_json JSON NULL,
            notes TEXT NULL,
            status ENUM('pending_approval','approved','rejected','collected','in_progress','completed','cancelled') NOT NULL DEFAULT 'pending_approval',
            cashier_status VARCHAR(32) NULL,
            requested_by VARCHAR(255) NULL,
            doctor_id INT NULL,
            approved_by VARCHAR(255) NULL,
            approved_at DATETIME NULL,
            rejection_reason VARCHAR(255) NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            CONSTRAINT fk_lab_requests_patient FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE RESTRICT,
            CONSTRAINT fk_lab_requests_encounter FOREIGN KEY (encounter_id) REFERENCES encounters(id) ON DELETE SET NULL,
            INDEX idx_lab_requests_status (status),
            INDEX idx_lab_requests_patient (patient_id),
            INDEX idx_lab_requests_encounter (encounter_id),
            INDEX idx_lab_requests_created (created_at)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
    );

    try {
        $col = $pdo->query("SHOW COLUMNS FROM lab_requests LIKE 'encounter_id'")->fetch();
        if (!$col) {
            $pdo->exec("ALTER TABLE lab_requests ADD COLUMN encounter_id INT NULL AFTER patient_id");
        }
    } catch (Throwable $e) {
    }

    try {
        $pdo->exec("ALTER TABLE lab_requests ADD INDEX idx_lab_requests_encounter (encounter_id)");
    } catch (Throwable $e) {
    }

    try {
        $pdo->exec("ALTER TABLE lab_requests ADD CONSTRAINT fk_lab_requests_encounter FOREIGN KEY (encounter_id) REFERENCES encounters(id) ON DELETE SET NULL");
    } catch (Throwable $e) {
    }

    try {
        $col = $pdo->query("SHOW COLUMNS FROM lab_requests LIKE 'requester_role'")->fetch();
        if (!$col) {
            $pdo->exec("ALTER TABLE lab_requests ADD COLUMN requester_role VARCHAR(32) NULL AFTER requested_by");
        }
    } catch (Throwable $e) {
    }

    try {
        $col = $pdo->query("SHOW COLUMNS FROM lab_requests LIKE 'doctor_id'")->fetch();
        if (!$col) {
            $pdo->exec("ALTER TABLE lab_requests ADD COLUMN doctor_id INT NULL AFTER requested_by");
        }
    } catch (Throwable $e) {
    }

    try {
        $col = $pdo->query("SHOW COLUMNS FROM lab_requests LIKE 'cashier_status'")->fetch();
        if (!$col) {
            $pdo->exec("ALTER TABLE lab_requests ADD COLUMN cashier_status VARCHAR(32) NULL AFTER status");
        }
    } catch (Throwable $e) {
    }

    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS lab_request_items (
            id INT AUTO_INCREMENT PRIMARY KEY,
            request_id INT NOT NULL,
            test_code VARCHAR(64) NOT NULL,
            test_name VARCHAR(255) NOT NULL,
            specimen VARCHAR(64) NULL,
            status ENUM('pending','in_progress','completed','cancelled') NOT NULL DEFAULT 'pending',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            CONSTRAINT fk_lab_items_request FOREIGN KEY (request_id) REFERENCES lab_requests(id) ON DELETE CASCADE,
            INDEX idx_lab_items_request (request_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
    );

    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS lab_results (
            id INT AUTO_INCREMENT PRIMARY KEY,
            request_item_id INT NOT NULL,
            result_text TEXT NULL,
            released_by VARCHAR(255) NULL,
            released_at DATETIME NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            CONSTRAINT fk_lab_results_item FOREIGN KEY (request_item_id) REFERENCES lab_request_items(id) ON DELETE CASCADE,
            UNIQUE KEY uniq_lab_results_item (request_item_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
    );
}

function lab_test_catalog(): array
{
    return [
        'cbc' => ['name' => 'Complete Blood Count (CBC)', 'specimen' => 'Blood'],
        'urinalysis' => ['name' => 'Urinalysis', 'specimen' => 'Urine'],
        'rbs' => ['name' => 'Random Blood Sugar (RBS)', 'specimen' => 'Blood'],
        'fbs' => ['name' => 'Fasting Blood Sugar (FBS)', 'specimen' => 'Blood'],
        'bun' => ['name' => 'BUN', 'specimen' => 'Blood'],
        'creatinine' => ['name' => 'Creatinine', 'specimen' => 'Blood'],
        'electrolytes' => ['name' => 'Electrolytes (Na/K/Cl)', 'specimen' => 'Blood'],
        'pregnancy' => ['name' => 'Pregnancy Test', 'specimen' => 'Urine'],
        'ecg' => ['name' => 'Electrocardiogram (ECG)', 'specimen' => null],
        'xray' => ['name' => 'X-Ray', 'specimen' => null],
    ];
}

function lab_standing_order_test_codes(): array
{
    return [
        'cbc',
        'urinalysis',
        'pregnancy',
        'rbs',
        'fbs',
        'electrolytes',
    ];
}
