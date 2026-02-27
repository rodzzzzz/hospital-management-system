<?php
declare(strict_types=1);

require_once __DIR__ . '/../_cors.php';
require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';

cors_headers();

$pdo = db();

try {
    // Queue stations table
    $pdo->exec("CREATE TABLE IF NOT EXISTS queue_stations (
        id INT(11) NOT NULL AUTO_INCREMENT,
        station_name VARCHAR(50) NOT NULL,
        station_display_name VARCHAR(100) NOT NULL,
        station_order INT(11) NOT NULL DEFAULT 0,
        is_active TINYINT(1) NOT NULL DEFAULT 1,
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        UNIQUE KEY uq_station_name (station_name)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    // Queue settings table
    $pdo->exec("CREATE TABLE IF NOT EXISTS queue_settings (
        id INT(11) NOT NULL AUTO_INCREMENT,
        station_id INT(11) NOT NULL,
        setting_key VARCHAR(100) NOT NULL,
        setting_value VARCHAR(255) NOT NULL,
        PRIMARY KEY (id),
        UNIQUE KEY uq_station_setting (station_id, setting_key)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    // Patient queue table (the real working one used by _functions.php)
    $pdo->exec("CREATE TABLE IF NOT EXISTS patient_queue (
        id INT(11) NOT NULL AUTO_INCREMENT,
        patient_id INT(11) NOT NULL,
        station_id INT(11) NOT NULL,
        queue_number INT(11) NOT NULL,
        queue_position INT(11) NOT NULL DEFAULT 0,
        status ENUM('waiting','in_progress','completed','cancelled','unavailable') NOT NULL DEFAULT 'waiting',
        staff_user_id INT(11) DEFAULT NULL,
        arrived_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        started_at DATETIME DEFAULT NULL,
        completed_at DATETIME DEFAULT NULL,
        updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        KEY idx_station_status (station_id, status),
        KEY idx_patient_id (patient_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    // Queue transfers table
    $pdo->exec("CREATE TABLE IF NOT EXISTS queue_transfers (
        id INT(11) NOT NULL AUTO_INCREMENT,
        patient_id INT(11) NOT NULL,
        from_station_id INT(11) NOT NULL,
        to_station_id INT(11) NOT NULL,
        transferred_by INT(11) DEFAULT NULL,
        transfer_reason VARCHAR(100) DEFAULT 'manual',
        notes TEXT DEFAULT NULL,
        transferred_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        KEY idx_from_station (from_station_id),
        KEY idx_patient (patient_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    // Queue error log table
    $pdo->exec("CREATE TABLE IF NOT EXISTS queue_error_log (
        id INT(11) NOT NULL AUTO_INCREMENT,
        queue_id INT(11) NOT NULL,
        patient_id INT(11) NOT NULL,
        wrong_station_id INT(11) NOT NULL,
        correct_station_id INT(11) NOT NULL,
        reported_by INT(11) NOT NULL,
        confirmed_by INT(11) DEFAULT NULL,
        status ENUM('pending','confirmed','rejected') NOT NULL DEFAULT 'pending',
        notes TEXT DEFAULT NULL,
        reported_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        confirmed_at DATETIME DEFAULT NULL,
        PRIMARY KEY (id),
        KEY idx_wrong_station_status (wrong_station_id, status),
        KEY idx_queue_id (queue_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    // Seed default stations if none exist
    $stmt = $pdo->query("SELECT COUNT(*) as cnt FROM queue_stations");
    $count = (int)$stmt->fetch()['cnt'];
    if ($count === 0) {
        $stations = [
            ['opd', 'Out-Patient Department', 1],
            ['doctor', "Doctor's Office", 2],
            ['pharmacy', 'Pharmacy', 3],
            ['cashier', 'Cashier', 4],
            ['xray', 'X-Ray', 5],
            ['lab', 'Laboratory', 6],
        ];
        $ins = $pdo->prepare("INSERT INTO queue_stations (station_name, station_display_name, station_order) VALUES (?, ?, ?)");
        foreach ($stations as $s) {
            $ins->execute($s);
        }

        // Seed default average service times
        $settingIns = $pdo->prepare("INSERT INTO queue_settings (station_id, setting_key, setting_value) VALUES (?, 'average_service_time', ?)");
        $stationsResult = $pdo->query("SELECT id, station_name FROM queue_stations ORDER BY id");
        while ($row = $stationsResult->fetch()) {
            $avg = match($row['station_name']) {
                'opd' => '10',
                'doctor' => '15',
                'pharmacy' => '5',
                'cashier' => '5',
                'xray' => '20',
                'lab' => '15',
                default => '10',
            };
            $settingIns->execute([$row['id'], $avg]);
        }
    }

    json_response(['ok' => true, 'message' => 'Queue tables created and seeded successfully']);
} catch (Throwable $e) {
    json_response(['ok' => false, 'error' => $e->getMessage()], 500);
}
