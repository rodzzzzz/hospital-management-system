<?php
declare(strict_types=1);

require_once __DIR__ . '/../_db.php';

function ensure_hr_tables(PDO $pdo): void
{
    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS hr_departments (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL UNIQUE,
            status ENUM('active','inactive') NOT NULL DEFAULT 'active',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_hr_departments_status (status)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
    );

    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS hr_positions (
            id INT AUTO_INCREMENT PRIMARY KEY,
            department_id INT NULL,
            name VARCHAR(255) NOT NULL,
            status ENUM('active','inactive') NOT NULL DEFAULT 'active',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            UNIQUE KEY uniq_hr_positions (department_id, name),
            INDEX idx_hr_positions_dept (department_id),
            INDEX idx_hr_positions_status (status),
            CONSTRAINT fk_hr_positions_department FOREIGN KEY (department_id) REFERENCES hr_departments(id) ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
    );

    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS hr_employees (
            id INT AUTO_INCREMENT PRIMARY KEY,
            employee_code VARCHAR(64) NULL UNIQUE,
            full_name VARCHAR(255) NOT NULL,
            phone VARCHAR(64) NULL,
            email VARCHAR(255) NULL,
            department_id INT NULL,
            position_id INT NULL,
            status ENUM('active','inactive') NOT NULL DEFAULT 'active',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_hr_employees_name (full_name),
            INDEX idx_hr_employees_status (status),
            INDEX idx_hr_employees_dept (department_id),
            INDEX idx_hr_employees_pos (position_id),
            CONSTRAINT fk_hr_employees_department FOREIGN KEY (department_id) REFERENCES hr_departments(id) ON DELETE SET NULL,
            CONSTRAINT fk_hr_employees_position FOREIGN KEY (position_id) REFERENCES hr_positions(id) ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
    );

    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS hr_schedules (
            id INT AUTO_INCREMENT PRIMARY KEY,
            employee_id INT NOT NULL,
            shift_date DATE NOT NULL,
            start_time TIME NOT NULL,
            end_time TIME NOT NULL,
            notes VARCHAR(255) NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_hr_schedules_emp_date (employee_id, shift_date),
            INDEX idx_hr_schedules_date (shift_date),
            CONSTRAINT fk_hr_schedules_employee FOREIGN KEY (employee_id) REFERENCES hr_employees(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
    );
}
