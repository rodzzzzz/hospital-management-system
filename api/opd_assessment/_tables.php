<?php
declare(strict_types=1);

require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../opd/_tables.php';

function ensure_opd_assessment_tables(PDO $pdo): void
{
    ensure_opd_tables($pdo);

    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS opd_nursing_assessments (
            id INT AUTO_INCREMENT PRIMARY KEY,
            appointment_id INT NOT NULL,
            patient_id INT NOT NULL,
            nurse_name VARCHAR(255) NULL,
            triage_level INT NULL,
            vitals_json TEXT NULL,
            assessment_json TEXT NULL,
            notes TEXT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_opd_nurse_assess_appt (appointment_id),
            INDEX idx_opd_nurse_assess_patient (patient_id),
            CONSTRAINT fk_opd_nurse_assess_appt FOREIGN KEY (appointment_id) REFERENCES opd_appointments(id) ON DELETE CASCADE,
            CONSTRAINT fk_opd_nurse_assess_patient FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE RESTRICT
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
    );

    try {
        $pdo->exec('ALTER TABLE opd_nursing_assessments ADD COLUMN nurse_name VARCHAR(255) NULL');
    } catch (Throwable $e) {
    }

    try {
        $pdo->exec('ALTER TABLE opd_nursing_assessments ADD COLUMN triage_level INT NULL');
    } catch (Throwable $e) {
    }

    try {
        $pdo->exec('ALTER TABLE opd_nursing_assessments ADD COLUMN vitals_json TEXT NULL');
    } catch (Throwable $e) {
    }

    try {
        $pdo->exec('ALTER TABLE opd_nursing_assessments ADD COLUMN assessment_json TEXT NULL');
    } catch (Throwable $e) {
    }

    try {
        $pdo->exec('ALTER TABLE opd_nursing_assessments ADD COLUMN notes TEXT NULL');
    } catch (Throwable $e) {
    }

    try {
        $pdo->exec('ALTER TABLE opd_nursing_assessments ADD INDEX idx_opd_nurse_assess_appt (appointment_id)');
    } catch (Throwable $e) {
    }

    try {
        $pdo->exec('ALTER TABLE opd_nursing_assessments ADD INDEX idx_opd_nurse_assess_patient (patient_id)');
    } catch (Throwable $e) {
    }

    try {
        $pdo->exec('ALTER TABLE opd_nursing_assessments ADD CONSTRAINT fk_opd_nurse_assess_appt FOREIGN KEY (appointment_id) REFERENCES opd_appointments(id) ON DELETE CASCADE');
    } catch (Throwable $e) {
    }

    try {
        $pdo->exec('ALTER TABLE opd_nursing_assessments ADD CONSTRAINT fk_opd_nurse_assess_patient FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE RESTRICT');
    } catch (Throwable $e) {
    }
}
