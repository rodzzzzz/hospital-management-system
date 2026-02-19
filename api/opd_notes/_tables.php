<?php
declare(strict_types=1);

require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../opd/_tables.php';
require_once __DIR__ . '/../users/_tables.php';

function ensure_opd_notes_tables(PDO $pdo): void
{
    ensure_opd_tables($pdo);
    ensure_users_tables($pdo);

    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS opd_consultation_notes (
            id INT AUTO_INCREMENT PRIMARY KEY,
            appointment_id INT NOT NULL,
            patient_id INT NOT NULL,
            doctor_user_id INT NULL,
            doctor_name VARCHAR(255) NULL,
            note_text TEXT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_opd_consult_notes_appt (appointment_id),
            INDEX idx_opd_consult_notes_patient (patient_id),
            INDEX idx_opd_consult_notes_doctor (doctor_user_id),
            CONSTRAINT fk_opd_consult_notes_appt FOREIGN KEY (appointment_id) REFERENCES opd_appointments(id) ON DELETE CASCADE,
            CONSTRAINT fk_opd_consult_notes_patient FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE RESTRICT,
            CONSTRAINT fk_opd_consult_notes_doctor FOREIGN KEY (doctor_user_id) REFERENCES users(id) ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
    );

    try {
        $pdo->exec('ALTER TABLE opd_consultation_notes ADD INDEX idx_opd_consult_notes_appt (appointment_id)');
    } catch (Throwable $e) {
    }

    try {
        $pdo->exec('ALTER TABLE opd_consultation_notes ADD INDEX idx_opd_consult_notes_patient (patient_id)');
    } catch (Throwable $e) {
    }

    try {
        $pdo->exec('ALTER TABLE opd_consultation_notes ADD INDEX idx_opd_consult_notes_doctor (doctor_user_id)');
    } catch (Throwable $e) {
    }

    try {
        $pdo->exec('ALTER TABLE opd_consultation_notes ADD CONSTRAINT fk_opd_consult_notes_appt FOREIGN KEY (appointment_id) REFERENCES opd_appointments(id) ON DELETE CASCADE');
    } catch (Throwable $e) {
    }

    try {
        $pdo->exec('ALTER TABLE opd_consultation_notes ADD CONSTRAINT fk_opd_consult_notes_patient FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE RESTRICT');
    } catch (Throwable $e) {
    }

    try {
        $pdo->exec('ALTER TABLE opd_consultation_notes ADD CONSTRAINT fk_opd_consult_notes_doctor FOREIGN KEY (doctor_user_id) REFERENCES users(id) ON DELETE SET NULL');
    } catch (Throwable $e) {
    }
}
