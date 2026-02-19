<?php
declare(strict_types=1);

require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../encounters/_tables.php';
require_once __DIR__ . '/../users/_tables.php';

function ensure_er_notes_tables(PDO $pdo): void
{
    ensure_encounter_tables($pdo);
    ensure_users_tables($pdo);

    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS er_consultation_notes (
            id INT AUTO_INCREMENT PRIMARY KEY,
            encounter_id INT NOT NULL,
            patient_id INT NOT NULL,
            author_user_id INT NULL,
            author_name VARCHAR(255) NULL,
            note_text TEXT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_er_consult_notes_encounter (encounter_id),
            INDEX idx_er_consult_notes_patient (patient_id),
            INDEX idx_er_consult_notes_author (author_user_id),
            CONSTRAINT fk_er_consult_notes_encounter FOREIGN KEY (encounter_id) REFERENCES encounters(id) ON DELETE CASCADE,
            CONSTRAINT fk_er_consult_notes_patient FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE RESTRICT,
            CONSTRAINT fk_er_consult_notes_author FOREIGN KEY (author_user_id) REFERENCES users(id) ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
    );

    try {
        $pdo->exec('ALTER TABLE er_consultation_notes ADD INDEX idx_er_consult_notes_encounter (encounter_id)');
    } catch (Throwable $e) {
    }

    try {
        $pdo->exec('ALTER TABLE er_consultation_notes ADD INDEX idx_er_consult_notes_patient (patient_id)');
    } catch (Throwable $e) {
    }

    try {
        $pdo->exec('ALTER TABLE er_consultation_notes ADD INDEX idx_er_consult_notes_author (author_user_id)');
    } catch (Throwable $e) {
    }

    try {
        $pdo->exec('ALTER TABLE er_consultation_notes ADD CONSTRAINT fk_er_consult_notes_encounter FOREIGN KEY (encounter_id) REFERENCES encounters(id) ON DELETE CASCADE');
    } catch (Throwable $e) {
    }

    try {
        $pdo->exec('ALTER TABLE er_consultation_notes ADD CONSTRAINT fk_er_consult_notes_patient FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE RESTRICT');
    } catch (Throwable $e) {
    }

    try {
        $pdo->exec('ALTER TABLE er_consultation_notes ADD CONSTRAINT fk_er_consult_notes_author FOREIGN KEY (author_user_id) REFERENCES users(id) ON DELETE SET NULL');
    } catch (Throwable $e) {
    }
}
