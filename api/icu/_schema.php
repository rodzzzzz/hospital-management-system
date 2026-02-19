<?php
declare(strict_types=1);

function icu_ensure_schema(PDO $pdo): void
{
    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS icu_beds (
            id INT AUTO_INCREMENT PRIMARY KEY,
            bed_code VARCHAR(32) NOT NULL UNIQUE,
            status ENUM('available','occupied','cleaning','maintenance') NOT NULL DEFAULT 'available',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
    );

    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS icu_admissions (
            id INT AUTO_INCREMENT PRIMARY KEY,
            patient_name VARCHAR(255) NOT NULL,
            bed_id INT NOT NULL,
            admitted_at DATETIME NOT NULL,
            discharged_at DATETIME NULL,
            status ENUM('admitted','discharged') NOT NULL DEFAULT 'admitted',
            diagnosis TEXT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            CONSTRAINT fk_icu_admissions_bed FOREIGN KEY (bed_id) REFERENCES icu_beds(id) ON DELETE RESTRICT,
            INDEX idx_icu_admissions_admitted_at (admitted_at),
            INDEX idx_icu_admissions_status (status)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
    );
}
