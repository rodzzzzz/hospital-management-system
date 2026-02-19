<?php
declare(strict_types=1);

require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../opd/_tables.php';
require_once __DIR__ . '/../users/_tables.php';

function ensure_opd_billing_tables(PDO $pdo): void
{
    ensure_opd_tables($pdo);
    ensure_users_tables($pdo);

    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS opd_billing_items (
            id INT AUTO_INCREMENT PRIMARY KEY,
            appointment_id INT NOT NULL,
            patient_id INT NOT NULL,
            created_by_user_id INT NULL,
            item_type VARCHAR(32) NOT NULL DEFAULT 'misc',
            description VARCHAR(255) NOT NULL,
            qty INT NOT NULL DEFAULT 1,
            unit_price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_opd_billing_items_appt (appointment_id),
            INDEX idx_opd_billing_items_patient (patient_id),
            CONSTRAINT fk_opd_billing_items_appt FOREIGN KEY (appointment_id) REFERENCES opd_appointments(id) ON DELETE CASCADE,
            CONSTRAINT fk_opd_billing_items_patient FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE RESTRICT,
            CONSTRAINT fk_opd_billing_items_created_by FOREIGN KEY (created_by_user_id) REFERENCES users(id) ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
    );

    try {
        $pdo->exec('ALTER TABLE opd_billing_items ADD INDEX idx_opd_billing_items_appt (appointment_id)');
    } catch (Throwable $e) {
    }

    try {
        $pdo->exec('ALTER TABLE opd_billing_items ADD INDEX idx_opd_billing_items_patient (patient_id)');
    } catch (Throwable $e) {
    }

    try {
        $pdo->exec('ALTER TABLE opd_billing_items ADD CONSTRAINT fk_opd_billing_items_appt FOREIGN KEY (appointment_id) REFERENCES opd_appointments(id) ON DELETE CASCADE');
    } catch (Throwable $e) {
    }

    try {
        $pdo->exec('ALTER TABLE opd_billing_items ADD CONSTRAINT fk_opd_billing_items_patient FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE RESTRICT');
    } catch (Throwable $e) {
    }

    try {
        $pdo->exec('ALTER TABLE opd_billing_items ADD CONSTRAINT fk_opd_billing_items_created_by FOREIGN KEY (created_by_user_id) REFERENCES users(id) ON DELETE SET NULL');
    } catch (Throwable $e) {
    }
}
