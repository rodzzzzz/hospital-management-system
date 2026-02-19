<?php
declare(strict_types=1);

require_once __DIR__ . '/../_db.php';

function ensure_opd_tables(PDO $pdo): void
{
    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS opd_appointments (
            id INT AUTO_INCREMENT PRIMARY KEY,
            patient_id INT NOT NULL,
            doctor_name VARCHAR(255) NOT NULL,
            appointment_at DATETIME NULL,
            status ENUM('requested','scheduled','waiting','checked_in','in_consultation','completed','cancelled','no_show','rejected') NOT NULL DEFAULT 'requested',
            notes TEXT NULL,
            nursing_assessment_id INT NULL,
            lab_tests_json TEXT NULL,
            lab_note TEXT NULL,
            responded_at TIMESTAMP NULL DEFAULT NULL,
            approved_by_user_id INT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            CONSTRAINT fk_opd_appt_patient FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE RESTRICT,
            INDEX idx_opd_appt_at (appointment_at),
            INDEX idx_opd_appt_status (status),
            INDEX idx_opd_appt_patient (patient_id),
            INDEX idx_opd_appt_approved_by (approved_by_user_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
    );

    try {
        $pdo->exec("ALTER TABLE opd_appointments MODIFY appointment_at DATETIME NULL");
    } catch (Throwable $e) {
    }

    try {
        $pdo->exec("ALTER TABLE opd_appointments MODIFY status ENUM('requested','scheduled','waiting','checked_in','in_consultation','completed','cancelled','no_show','rejected') NOT NULL DEFAULT 'requested'");
    } catch (Throwable $e) {
    }

    try {
        $pdo->exec("ALTER TABLE opd_appointments ADD COLUMN responded_at TIMESTAMP NULL DEFAULT NULL");
    } catch (Throwable $e) {
    }

    try {
        $pdo->exec("ALTER TABLE opd_appointments ADD COLUMN nursing_assessment_id INT NULL");
    } catch (Throwable $e) {
    }

    try {
        $pdo->exec("ALTER TABLE opd_appointments ADD COLUMN lab_tests_json TEXT NULL");
    } catch (Throwable $e) {
    }

    try {
        $pdo->exec("ALTER TABLE opd_appointments ADD COLUMN lab_note TEXT NULL");
    } catch (Throwable $e) {
    }

    try {
        $pdo->exec("ALTER TABLE opd_appointments ADD COLUMN approved_by_user_id INT NULL");
    } catch (Throwable $e) {
    }

    try {
        $pdo->exec("ALTER TABLE opd_appointments ADD INDEX idx_opd_appt_at (appointment_at)");
    } catch (Throwable $e) {
    }

    try {
        $pdo->exec("ALTER TABLE opd_appointments ADD INDEX idx_opd_appt_status (status)");
    } catch (Throwable $e) {
    }

    try {
        $pdo->exec("ALTER TABLE opd_appointments ADD INDEX idx_opd_appt_approved_by (approved_by_user_id)");
    } catch (Throwable $e) {
    }

    try {
        $pdo->exec("ALTER TABLE opd_appointments ADD CONSTRAINT fk_opd_appt_patient FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE RESTRICT");
    } catch (Throwable $e) {
    }
}
