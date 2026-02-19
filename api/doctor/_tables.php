<?php
declare(strict_types=1);

require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../users/_tables.php';

function ensure_doctor_tables(PDO $pdo): void
{
    ensure_users_tables($pdo);
    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS doctor_availability (
            user_id INT PRIMARY KEY,
            status ENUM('available','busy','on_leave') NOT NULL DEFAULT 'available',
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_doctor_availability_status (status),
            CONSTRAINT fk_doctor_availability_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
    );
}
