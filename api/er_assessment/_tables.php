<?php
declare(strict_types=1);

require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../encounters/_tables.php';

function ensure_er_assessment_tables(PDO $pdo): void
{
    ensure_encounter_tables($pdo);

    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS er_nursing_assessments (
            id INT AUTO_INCREMENT PRIMARY KEY,
            encounter_id INT NOT NULL,
            patient_id INT NOT NULL,
            nurse_name VARCHAR(255) NULL,
            triage_level INT NULL,
            vitals_json TEXT NULL,
            assessment_json TEXT NULL,
            notes TEXT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_er_nurse_assess_encounter (encounter_id),
            INDEX idx_er_nurse_assess_patient (patient_id),
            CONSTRAINT fk_er_nurse_assess_encounter FOREIGN KEY (encounter_id) REFERENCES encounters(id) ON DELETE CASCADE,
            CONSTRAINT fk_er_nurse_assess_patient FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE RESTRICT
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
    );

    try {
        $pdo->exec('ALTER TABLE er_nursing_assessments ADD COLUMN nurse_name VARCHAR(255) NULL');
    } catch (Throwable $e) {
    }

    try {
        $pdo->exec('ALTER TABLE er_nursing_assessments ADD COLUMN triage_level INT NULL');
    } catch (Throwable $e) {
    }

    try {
        $pdo->exec('ALTER TABLE er_nursing_assessments ADD COLUMN vitals_json TEXT NULL');
    } catch (Throwable $e) {
    }

    try {
        $pdo->exec('ALTER TABLE er_nursing_assessments ADD COLUMN assessment_json TEXT NULL');
    } catch (Throwable $e) {
    }

    try {
        $pdo->exec('ALTER TABLE er_nursing_assessments ADD COLUMN notes TEXT NULL');
    } catch (Throwable $e) {
    }

    try {
        $pdo->exec('ALTER TABLE er_nursing_assessments ADD INDEX idx_er_nurse_assess_encounter (encounter_id)');
    } catch (Throwable $e) {
    }

    try {
        $pdo->exec('ALTER TABLE er_nursing_assessments ADD INDEX idx_er_nurse_assess_patient (patient_id)');
    } catch (Throwable $e) {
    }

    try {
        $pdo->exec('ALTER TABLE er_nursing_assessments ADD CONSTRAINT fk_er_nurse_assess_encounter FOREIGN KEY (encounter_id) REFERENCES encounters(id) ON DELETE CASCADE');
    } catch (Throwable $e) {
    }

    try {
        $pdo->exec('ALTER TABLE er_nursing_assessments ADD CONSTRAINT fk_er_nurse_assess_patient FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE RESTRICT');
    } catch (Throwable $e) {
    }

    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS er_doctor_feedback (
            id INT AUTO_INCREMENT PRIMARY KEY,
            encounter_id INT NOT NULL,
            patient_id INT NOT NULL,
            er_assessment_id INT NULL,
            doctor_name VARCHAR(255) NULL,
            lab_tests_json TEXT NULL,
            lab_note TEXT NULL,
            status VARCHAR(30) NOT NULL DEFAULT 'pending',
            feedback_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_er_doc_feedback_encounter (encounter_id),
            INDEX idx_er_doc_feedback_patient (patient_id),
            INDEX idx_er_doc_feedback_assess (er_assessment_id),
            CONSTRAINT fk_er_doc_feedback_encounter FOREIGN KEY (encounter_id) REFERENCES encounters(id) ON DELETE CASCADE,
            CONSTRAINT fk_er_doc_feedback_patient FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE RESTRICT,
            CONSTRAINT fk_er_doc_feedback_assess FOREIGN KEY (er_assessment_id) REFERENCES er_nursing_assessments(id) ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
    );

    try {
        $pdo->exec('ALTER TABLE er_doctor_feedback ADD COLUMN doctor_name VARCHAR(255) NULL');
    } catch (Throwable $e) {
    }

    try {
        $pdo->exec('ALTER TABLE er_doctor_feedback ADD COLUMN lab_tests_json TEXT NULL');
    } catch (Throwable $e) {
    }

    try {
        $pdo->exec('ALTER TABLE er_doctor_feedback ADD COLUMN lab_note TEXT NULL');
    } catch (Throwable $e) {
    }

    try {
        $pdo->exec("ALTER TABLE er_doctor_feedback ADD COLUMN status VARCHAR(30) NOT NULL DEFAULT 'pending'");
    } catch (Throwable $e) {
    }

    try {
        $pdo->exec('ALTER TABLE er_doctor_feedback ADD COLUMN feedback_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP');
    } catch (Throwable $e) {
    }

    try {
        $pdo->exec('ALTER TABLE er_doctor_feedback ADD INDEX idx_er_doc_feedback_encounter (encounter_id)');
    } catch (Throwable $e) {
    }

    try {
        $pdo->exec('ALTER TABLE er_doctor_feedback ADD INDEX idx_er_doc_feedback_patient (patient_id)');
    } catch (Throwable $e) {
    }

    try {
        $pdo->exec('ALTER TABLE er_doctor_feedback ADD INDEX idx_er_doc_feedback_assess (er_assessment_id)');
    } catch (Throwable $e) {
    }

    try {
        $pdo->exec('ALTER TABLE er_doctor_feedback ADD CONSTRAINT fk_er_doc_feedback_encounter FOREIGN KEY (encounter_id) REFERENCES encounters(id) ON DELETE CASCADE');
    } catch (Throwable $e) {
    }

    try {
        $pdo->exec('ALTER TABLE er_doctor_feedback ADD CONSTRAINT fk_er_doc_feedback_patient FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE RESTRICT');
    } catch (Throwable $e) {
    }

    try {
        $pdo->exec('ALTER TABLE er_doctor_feedback ADD CONSTRAINT fk_er_doc_feedback_assess FOREIGN KEY (er_assessment_id) REFERENCES er_nursing_assessments(id) ON DELETE SET NULL');
    } catch (Throwable $e) {
    }

    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS er_assessment_submissions (
            id INT AUTO_INCREMENT PRIMARY KEY,
            encounter_id INT NOT NULL,
            patient_id INT NOT NULL,
            er_assessment_id INT NOT NULL,
            submitted_by VARCHAR(255) NULL,
            doctor_id INT NULL,
            doctor_name VARCHAR(255) NULL,
            status VARCHAR(30) NOT NULL DEFAULT 'submitted',
            submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            responded_at TIMESTAMP NULL,
            INDEX idx_er_assess_sub_doc (doctor_id),
            INDEX idx_er_assess_sub_docname (doctor_name),
            INDEX idx_er_assess_sub_patient (patient_id),
            INDEX idx_er_assess_sub_assess (er_assessment_id),
            INDEX idx_er_assess_sub_encounter (encounter_id),
            CONSTRAINT fk_er_assess_sub_encounter FOREIGN KEY (encounter_id) REFERENCES encounters(id) ON DELETE CASCADE,
            CONSTRAINT fk_er_assess_sub_patient FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE RESTRICT,
            CONSTRAINT fk_er_assess_sub_assess FOREIGN KEY (er_assessment_id) REFERENCES er_nursing_assessments(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
    );

    try {
        $pdo->exec("ALTER TABLE er_assessment_submissions ADD COLUMN submitted_by VARCHAR(255) NULL");
    } catch (Throwable $e) {
    }

    try {
        $pdo->exec("ALTER TABLE er_assessment_submissions ADD COLUMN doctor_id INT NULL");
    } catch (Throwable $e) {
    }

    try {
        $pdo->exec("ALTER TABLE er_assessment_submissions ADD COLUMN doctor_name VARCHAR(255) NULL");
    } catch (Throwable $e) {
    }

    try {
        $pdo->exec("ALTER TABLE er_assessment_submissions ADD COLUMN status VARCHAR(30) NOT NULL DEFAULT 'submitted'");
    } catch (Throwable $e) {
    }

    try {
        $pdo->exec("ALTER TABLE er_assessment_submissions ADD COLUMN submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP");
    } catch (Throwable $e) {
    }

    try {
        $pdo->exec("ALTER TABLE er_assessment_submissions ADD COLUMN responded_at TIMESTAMP NULL");
    } catch (Throwable $e) {
    }

    try {
        $pdo->exec('ALTER TABLE er_assessment_submissions ADD INDEX idx_er_assess_sub_doc (doctor_id)');
    } catch (Throwable $e) {
    }

    try {
        $pdo->exec('ALTER TABLE er_assessment_submissions ADD INDEX idx_er_assess_sub_docname (doctor_name)');
    } catch (Throwable $e) {
    }

    try {
        $pdo->exec('ALTER TABLE er_assessment_submissions ADD INDEX idx_er_assess_sub_patient (patient_id)');
    } catch (Throwable $e) {
    }

    try {
        $pdo->exec('ALTER TABLE er_assessment_submissions ADD INDEX idx_er_assess_sub_assess (er_assessment_id)');
    } catch (Throwable $e) {
    }

    try {
        $pdo->exec('ALTER TABLE er_assessment_submissions ADD INDEX idx_er_assess_sub_encounter (encounter_id)');
    } catch (Throwable $e) {
    }

    try {
        $pdo->exec('ALTER TABLE er_assessment_submissions ADD CONSTRAINT fk_er_assess_sub_encounter FOREIGN KEY (encounter_id) REFERENCES encounters(id) ON DELETE CASCADE');
    } catch (Throwable $e) {
    }

    try {
        $pdo->exec('ALTER TABLE er_assessment_submissions ADD CONSTRAINT fk_er_assess_sub_patient FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE RESTRICT');
    } catch (Throwable $e) {
    }

    try {
        $pdo->exec('ALTER TABLE er_assessment_submissions ADD CONSTRAINT fk_er_assess_sub_assess FOREIGN KEY (er_assessment_id) REFERENCES er_nursing_assessments(id) ON DELETE CASCADE');
    } catch (Throwable $e) {
    }
}
