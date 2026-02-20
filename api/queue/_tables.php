<?php
declare(strict_types=1);

function ensure_patient_queue_table(PDO $pdo): void
{
    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS patient_queue (
            id INT(11) NOT NULL AUTO_INCREMENT,
            payload_json LONGTEXT NOT NULL,
            status ENUM('queued','confirmed','cancelled') NOT NULL DEFAULT 'queued',
            confirmed_patient_id INT(11) DEFAULT NULL,
            confirmed_by VARCHAR(255) DEFAULT NULL,
            confirmed_at DATETIME DEFAULT NULL,
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY idx_status_created (status, created_at),
            KEY idx_confirmed_patient_id (confirmed_patient_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci"
    );
}
