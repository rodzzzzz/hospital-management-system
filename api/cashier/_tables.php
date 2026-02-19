<?php
declare(strict_types=1);

require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../encounters/_tables.php';

function ensure_cashier_tables(PDO $pdo): void
{
    ensure_encounter_tables($pdo);
    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS cashier_charges (
            id INT AUTO_INCREMENT PRIMARY KEY,
            source_module VARCHAR(50) NOT NULL,
            source_id INT NOT NULL,
            patient_id INT NOT NULL,
            encounter_id INT NULL,
            status VARCHAR(32) NOT NULL DEFAULT 'pending_invoice',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_cashier_charges_patient (patient_id),
            INDEX idx_cashier_charges_encounter (encounter_id),
            INDEX idx_cashier_charges_status (status),
            INDEX idx_cashier_charges_source (source_module, source_id),
            CONSTRAINT fk_cashier_charges_patient FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE RESTRICT,
            CONSTRAINT fk_cashier_charges_encounter FOREIGN KEY (encounter_id) REFERENCES encounters(id) ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
    );

    try {
        $col = $pdo->query("SHOW COLUMNS FROM cashier_charges LIKE 'encounter_id'")->fetch();
        if (!$col) {
            $pdo->exec("ALTER TABLE cashier_charges ADD COLUMN encounter_id INT NULL AFTER patient_id");
        }
    } catch (Throwable $e) {
    }

    try {
        $pdo->exec("ALTER TABLE cashier_charges ADD INDEX idx_cashier_charges_encounter (encounter_id)");
    } catch (Throwable $e) {
    }

    try {
        $pdo->exec("ALTER TABLE cashier_charges ADD CONSTRAINT fk_cashier_charges_encounter FOREIGN KEY (encounter_id) REFERENCES encounters(id) ON DELETE SET NULL");
    } catch (Throwable $e) {
    }

    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS cashier_charge_items (
            id INT AUTO_INCREMENT PRIMARY KEY,
            charge_id INT NOT NULL,
            medicine_id INT NULL,
            medicine_name VARCHAR(255) NOT NULL,
            qty INT NOT NULL DEFAULT 1,
            instructions VARCHAR(255) NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_cashier_charge_items_charge (charge_id),
            INDEX idx_cashier_charge_items_med_id (medicine_id),
            CONSTRAINT fk_cashier_charge_items_charge FOREIGN KEY (charge_id) REFERENCES cashier_charges(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
    );

    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS cashier_invoices (
            id INT AUTO_INCREMENT PRIMARY KEY,
            charge_id INT NULL,
            patient_id INT NOT NULL,
            encounter_id INT NULL,
            status VARCHAR(32) NOT NULL DEFAULT 'unpaid',
            total DECIMAL(10,2) NOT NULL DEFAULT 0.00,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_cashier_invoices_patient (patient_id),
            INDEX idx_cashier_invoices_encounter (encounter_id),
            INDEX idx_cashier_invoices_status (status),
            INDEX idx_cashier_invoices_charge (charge_id),
            CONSTRAINT fk_cashier_invoices_patient FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE RESTRICT,
            CONSTRAINT fk_cashier_invoices_encounter FOREIGN KEY (encounter_id) REFERENCES encounters(id) ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
    );

    try {
        $col = $pdo->query("SHOW COLUMNS FROM cashier_invoices LIKE 'encounter_id'")->fetch();
        if (!$col) {
            $pdo->exec("ALTER TABLE cashier_invoices ADD COLUMN encounter_id INT NULL AFTER patient_id");
        }
    } catch (Throwable $e) {
    }

    try {
        $pdo->exec("ALTER TABLE cashier_invoices ADD INDEX idx_cashier_invoices_encounter (encounter_id)");
    } catch (Throwable $e) {
    }

    try {
        $pdo->exec("ALTER TABLE cashier_invoices ADD CONSTRAINT fk_cashier_invoices_encounter FOREIGN KEY (encounter_id) REFERENCES encounters(id) ON DELETE SET NULL");
    } catch (Throwable $e) {
    }

    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS cashier_invoice_items (
            id INT AUTO_INCREMENT PRIMARY KEY,
            invoice_id INT NOT NULL,
            medicine_id INT NULL,
            description VARCHAR(255) NOT NULL,
            qty INT NOT NULL DEFAULT 1,
            unit_price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
            subtotal DECIMAL(10,2) NOT NULL DEFAULT 0.00,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_cashier_invoice_items_invoice (invoice_id),
            CONSTRAINT fk_cashier_invoice_items_invoice FOREIGN KEY (invoice_id) REFERENCES cashier_invoices(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
    );

    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS cashier_payments (
            id INT AUTO_INCREMENT PRIMARY KEY,
            invoice_id INT NOT NULL,
            amount DECIMAL(10,2) NOT NULL,
            change_amount DECIMAL(10,2) NOT NULL DEFAULT 0.00,
            method VARCHAR(50) NOT NULL DEFAULT 'cash',
            received_by VARCHAR(100) NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_cashier_payments_invoice (invoice_id),
            CONSTRAINT fk_cashier_payments_invoice FOREIGN KEY (invoice_id) REFERENCES cashier_invoices(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
    );

    try {
        $pdo->exec("ALTER TABLE cashier_payments ADD COLUMN change_amount DECIMAL(10,2) NOT NULL DEFAULT 0.00");
    } catch (Throwable $e) {
    }
}
