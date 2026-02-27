<?php
declare(strict_types=1);

require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';
require_once __DIR__ . '/../_cors.php';
require_once __DIR__ . '/_schema.php';

cors_headers();

try {
    $pdo = db();
    xray_ensure_schema($pdo);

    try {
        $pdo->exec("ALTER TABLE queue_stations MODIFY COLUMN station_name ENUM('opd','doctor','pharmacy','cashier','xray','lab') NOT NULL");
    } catch (Throwable $ignored) {
        // queue tables may not be installed yet in some environments
    }

    try {
        $stationStmt = $pdo->prepare('SELECT id FROM queue_stations WHERE station_name = :station_name LIMIT 1');
        $stationStmt->execute(['station_name' => 'xray']);
        $xrayStationId = (int)($stationStmt->fetchColumn() ?: 0);

        if ($xrayStationId <= 0) {
            $insertStation = $pdo->prepare(
                'INSERT INTO queue_stations (station_name, station_display_name, station_order, is_active) VALUES (:station_name, :display_name, :station_order, 1)'
            );
            $insertStation->execute([
                'station_name' => 'xray',
                'display_name' => 'X-Ray',
                'station_order' => 5,
            ]);
            $xrayStationId = (int)$pdo->lastInsertId();
        }

        if ($xrayStationId > 0) {
            $defaultSettings = [
                'average_service_time' => '12',
                'queue_prefix' => 'XRY',
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
                    'station_id' => $xrayStationId,
                    'setting_key' => $key,
                    'setting_value' => $value,
                ]);
            }
        }
    } catch (Throwable $ignored) {
        // queue tables may not be installed yet in some environments
    }

    $anthonyCountStmt = $pdo->prepare("SELECT COUNT(*) AS c FROM xray_orders WHERE patient_name = :name");
    $anthonyCountStmt->execute(['name' => 'Anthony']);
    $anthonyCount = (int)($anthonyCountStmt->fetch()['c'] ?? 0);
    if ($anthonyCount === 0) {
        $insertAnthony = $pdo->prepare(
            'INSERT INTO xray_orders (patient_name, exam_type, priority, status, ordered_at, scheduled_at, completed_at, technologist_name, notes)
             VALUES (:patient_name, :exam_type, :priority, :status, :ordered_at, :scheduled_at, :completed_at, :technologist_name, :notes)'
        );

        $nowAnthony = new DateTimeImmutable();
        $orderedAtAnthony = $nowAnthony->sub(new DateInterval('PT6H'))->format('Y-m-d H:i:s');
        $scheduledAtAnthony = $nowAnthony->sub(new DateInterval('PT5H'))->format('Y-m-d H:i:s');
        $completedAtAnthony = $nowAnthony->sub(new DateInterval('PT4H'))->format('Y-m-d H:i:s');

        $insertAnthony->execute([
            'patient_name' => 'Anthony',
            'exam_type' => 'Chest X-ray',
            'priority' => 'urgent',
            'status' => 'reported',
            'ordered_at' => $orderedAtAnthony,
            'scheduled_at' => $scheduledAtAnthony,
            'completed_at' => $completedAtAnthony,
            'technologist_name' => 'Tech Santos',
            'notes' => 'Cough and fever; rule out pneumonia.',
        ]);
    }

    $count = (int)$pdo->query('SELECT COUNT(*) AS c FROM xray_orders')->fetch()['c'];
    if ($count === 0) {
        $pdo->beginTransaction();

        $insert = $pdo->prepare(
            'INSERT INTO xray_orders (patient_name, exam_type, priority, status, ordered_at, scheduled_at, completed_at, technologist_name, notes)
             VALUES (:patient_name, :exam_type, :priority, :status, :ordered_at, :scheduled_at, :completed_at, :technologist_name, :notes)'
        );

        $now = new DateTimeImmutable();
        $examTypes = ['Chest X-ray', 'Abdomen X-ray', 'Skull X-ray', 'Extremity X-ray', 'Spine X-ray'];
        $techs = ['Tech Rivera', 'Tech Santos', 'Tech Lee'];

        $rows = [
            ['Juan Dela Cruz', 'Chest X-ray', 'urgent', 'reported', 3, 2, 1, 'Rule out pneumonia'],
            ['Maria Santos', 'Extremity X-ray', 'routine', 'completed', 5, 4, 3, 'Post-fall assessment'],
            ['Emily White', 'Chest X-ray', 'stat', 'in_progress', 1, 0, null, 'Shortness of breath'],
            ['Michael Brown', 'Abdomen X-ray', 'routine', 'scheduled', 2, 1, null, 'Abdominal pain'],
            ['David Chen', 'Spine X-ray', 'urgent', 'requested', 0, null, null, 'Back pain'],
        ];

        foreach ($rows as $i => $r) {
            [$name, $exam, $prio, $status, $orderedH, $schedH, $compH, $note] = $r;
            $orderedAt = $now->sub(new DateInterval('PT' . (int)$orderedH . 'H'))->format('Y-m-d H:i:s');
            $scheduledAt = ($schedH === null) ? null : $now->sub(new DateInterval('PT' . (int)$schedH . 'H'))->format('Y-m-d H:i:s');
            $completedAt = ($compH === null) ? null : $now->sub(new DateInterval('PT' . (int)$compH . 'H'))->format('Y-m-d H:i:s');
            $insert->execute([
                'patient_name' => $name,
                'exam_type' => $exam,
                'priority' => $prio,
                'status' => $status,
                'ordered_at' => $orderedAt,
                'scheduled_at' => $scheduledAt,
                'completed_at' => $completedAt,
                'technologist_name' => $techs[$i % count($techs)],
                'notes' => $note,
            ]);
        }

        for ($d = 1; $d <= 10; $d++) {
            $day = $now->sub(new DateInterval('P' . $d . 'D'));
            $base = $day->setTime(9 + ($d % 6), 0);
            $n = 3 + ($d % 4);
            for ($j = 0; $j < $n; $j++) {
                $exam = $examTypes[($d + $j) % count($examTypes)];
                $prio = ($j % 5 === 0) ? 'urgent' : 'routine';
                $orderedAtDt = $base->add(new DateInterval('PT' . (int)($j * 45) . 'M'));
                $completedAtDt = $orderedAtDt->add(new DateInterval('PT' . (int)(20 + ($j * 8)) . 'M'));

                $insert->execute([
                    'patient_name' => 'Patient ' . strtoupper(substr(md5((string)($d * 10 + $j)), 0, 5)),
                    'exam_type' => $exam,
                    'priority' => $prio,
                    'status' => 'reported',
                    'ordered_at' => $orderedAtDt->format('Y-m-d H:i:s'),
                    'scheduled_at' => $orderedAtDt->add(new DateInterval('PT10M'))->format('Y-m-d H:i:s'),
                    'completed_at' => $completedAtDt->format('Y-m-d H:i:s'),
                    'technologist_name' => $techs[($j + $d) % count($techs)],
                    'notes' => null,
                ]);
            }
        }

        $pdo->commit();
    }

    json_response([
        'ok' => true,
        'message' => 'Xray tables are ready.',
    ]);
} catch (Throwable $e) {
    if (isset($pdo) && $pdo instanceof PDO && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    json_response([
        'ok' => false,
        'error' => $e->getMessage(),
    ], 500);
}
