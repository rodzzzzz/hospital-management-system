<?php
declare(strict_types=1);

require_once __DIR__ . '/../_db.php';

function ensure_users_tables(PDO $pdo): void
{
    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(64) NOT NULL UNIQUE,
            full_name VARCHAR(255) NOT NULL,
            password_hash VARCHAR(255) NULL,
            status ENUM('active','inactive') NOT NULL DEFAULT 'active',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_users_status (status),
            INDEX idx_users_name (full_name)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
    );

    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS user_roles (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            module VARCHAR(32) NOT NULL,
            role VARCHAR(64) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            UNIQUE KEY uniq_user_roles (user_id, module, role),
            INDEX idx_user_roles_user (user_id),
            INDEX idx_user_roles_module (module),
            CONSTRAINT fk_user_roles_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
    );

    try {
        $pdo->exec("ALTER TABLE users ADD COLUMN full_name VARCHAR(255) NOT NULL");
    } catch (Throwable $e) {
    }

    try {
        $pdo->exec("ALTER TABLE users ADD COLUMN password_hash VARCHAR(255) NULL");
    } catch (Throwable $e) {
    }

    try {
        $pdo->exec("ALTER TABLE users ADD COLUMN status ENUM('active','inactive') NOT NULL DEFAULT 'active'");
    } catch (Throwable $e) {
    }

    try {
        $pdo->exec("ALTER TABLE users ADD INDEX idx_users_status (status)");
    } catch (Throwable $e) {
    }

    try {
        $pdo->exec("ALTER TABLE user_roles ADD UNIQUE KEY uniq_user_roles (user_id, module, role)");
    } catch (Throwable $e) {
    }

    try {
        $pdo->exec("ALTER TABLE user_roles ADD INDEX idx_user_roles_module (module)");
    } catch (Throwable $e) {
    }

    try {
        $pdo->exec("ALTER TABLE user_roles ADD CONSTRAINT fk_user_roles_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE");
    } catch (Throwable $e) {
    }
}
