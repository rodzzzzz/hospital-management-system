<?php
declare(strict_types=1);

function dialysis_ensure_schema(PDO $pdo): void
{
    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS dialysis_patients (
            id INT AUTO_INCREMENT PRIMARY KEY,
            patient_code VARCHAR(32) NOT NULL UNIQUE,
            full_name VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
    );

    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS dialysis_machines (
            id INT AUTO_INCREMENT PRIMARY KEY,
            machine_code VARCHAR(32) NOT NULL UNIQUE,
            status ENUM('available','in_use','maintenance') NOT NULL DEFAULT 'available',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
    );

    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS dialysis_sessions (
            id INT AUTO_INCREMENT PRIMARY KEY,
            patient_id INT NOT NULL,
            machine_id INT NOT NULL,
            start_time DATETIME NOT NULL,
            end_time DATETIME NOT NULL,
            status ENUM('scheduled','in_progress','completed') NOT NULL DEFAULT 'scheduled',
            notes TEXT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            CONSTRAINT fk_dialysis_sessions_patient FOREIGN KEY (patient_id) REFERENCES dialysis_patients(id) ON DELETE RESTRICT,
            CONSTRAINT fk_dialysis_sessions_machine FOREIGN KEY (machine_id) REFERENCES dialysis_machines(id) ON DELETE RESTRICT,
            INDEX idx_dialysis_sessions_start (start_time),
            INDEX idx_dialysis_sessions_status (status)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
    );
}
