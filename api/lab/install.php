<?php
declare(strict_types=1);

require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';
require_once __DIR__ . '/_tables.php';

try {
    $pdo = db();
    ensure_lab_tables($pdo);

    try {
        $pdo->exec("ALTER TABLE queue_stations MODIFY COLUMN station_name ENUM('opd','doctor','pharmacy','cashier','xray','lab') NOT NULL");
    } catch (Throwable $ignored) {
        // queue tables may not be installed yet in some environments
    }

    try {
        $stationStmt = $pdo->prepare('SELECT id FROM queue_stations WHERE station_name = :station_name LIMIT 1');
        $stationStmt->execute(['station_name' => 'lab']);
        $labStationId = (int)($stationStmt->fetchColumn() ?: 0);

        if ($labStationId <= 0) {
            $insertStation = $pdo->prepare(
                'INSERT INTO queue_stations (station_name, station_display_name, station_order, is_active) VALUES (:station_name, :display_name, :station_order, 1)'
            );
            $insertStation->execute([
                'station_name' => 'lab',
                'display_name' => 'Laboratory',
                'station_order' => 6,
            ]);
            $labStationId = (int)$pdo->lastInsertId();
        }

        if ($labStationId > 0) {
            $defaultSettings = [
                'average_service_time' => '15',
                'queue_prefix' => 'LAB',
                'display_refresh_interval' => '10',
                'sound_enabled' => '1',
            ];

            $upsertSetting = $pdo->prepare(
                'INSERT INTO queue_settings (station_id, setting_key, setting_value)
                 VALUES (:station_id, :setting_key, :setting_value)
                 ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)'
            );

            foreach ($defaultSettings as $key => $value) {
                $upsertSetting->execute([
                    'station_id' => $labStationId,
                    'setting_key' => $key,
                    'setting_value' => $value,
                ]);
            }
        }
    } catch (Throwable $ignored) {
        // queue tables may not be installed yet in some environments
    }

    json_response([
        'ok' => true,
        'message' => 'Lab tables are ready.',
    ]);
} catch (Throwable $e) {
    json_response([
        'ok' => false,
        'error' => $e->getMessage(),
    ], 500);
}
