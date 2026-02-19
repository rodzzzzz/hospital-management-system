<?php
declare(strict_types=1);

function xray_ensure_schema(PDO $pdo): void
{
    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS xray_orders (
            id INT AUTO_INCREMENT PRIMARY KEY,
            patient_name VARCHAR(255) NOT NULL,
            exam_type VARCHAR(128) NOT NULL,
            priority ENUM('routine','urgent','stat') NOT NULL DEFAULT 'routine',
            status ENUM('requested','scheduled','in_progress','completed','reported','cancelled') NOT NULL DEFAULT 'requested',
            ordered_at DATETIME NOT NULL,
            scheduled_at DATETIME NULL,
            completed_at DATETIME NULL,
            technologist_name VARCHAR(255) NULL,
            notes TEXT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_xray_orders_ordered_at (ordered_at),
            INDEX idx_xray_orders_status (status),
            INDEX idx_xray_orders_exam_type (exam_type)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
    );
}
