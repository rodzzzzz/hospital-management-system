<?php
declare(strict_types=1);

require_once __DIR__ . '/../_db.php';

function ensure_price_master_tables(PDO $pdo): void
{
    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS lab_test_fees (
            test_code VARCHAR(64) NOT NULL PRIMARY KEY,
            test_name VARCHAR(255) NOT NULL,
            price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
    );

    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS opd_fees (
            fee_code VARCHAR(64) NOT NULL PRIMARY KEY,
            fee_name VARCHAR(255) NOT NULL,
            price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
    );

    try {
        $col = $pdo->query("SHOW COLUMNS FROM lab_test_fees LIKE 'test_name'")->fetch();
        if (!$col) {
            $pdo->exec("ALTER TABLE lab_test_fees ADD COLUMN test_name VARCHAR(255) NOT NULL AFTER test_code");
        }
    } catch (Throwable $e) {
    }

    try {
        $col = $pdo->query("SHOW COLUMNS FROM lab_test_fees LIKE 'price'")->fetch();
        if (!$col) {
            $pdo->exec("ALTER TABLE lab_test_fees ADD COLUMN price DECIMAL(10,2) NOT NULL DEFAULT 0.00 AFTER test_name");
        }
    } catch (Throwable $e) {
    }

    try {
        $col = $pdo->query("SHOW COLUMNS FROM opd_fees LIKE 'fee_name'")->fetch();
        if (!$col) {
            $pdo->exec("ALTER TABLE opd_fees ADD COLUMN fee_name VARCHAR(255) NOT NULL AFTER fee_code");
        }
    } catch (Throwable $e) {
    }

    try {
        $col = $pdo->query("SHOW COLUMNS FROM opd_fees LIKE 'price'")->fetch();
        if (!$col) {
            $pdo->exec("ALTER TABLE opd_fees ADD COLUMN price DECIMAL(10,2) NOT NULL DEFAULT 0.00 AFTER fee_name");
        }
    } catch (Throwable $e) {
    }
}
