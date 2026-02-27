<?php
declare(strict_types=1);

require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';
require_once __DIR__ . '/../_cors.php';
require_once __DIR__ . '/_schema.php';

cors_headers();

try {
    $pdo = db();
    icu_ensure_schema($pdo);

    $activePatients = (int)$pdo->query("SELECT COUNT(*) AS c FROM icu_admissions WHERE status = 'admitted'")->fetch()['c'];
    $availableBeds = (int)$pdo->query("SELECT COUNT(*) AS c FROM icu_beds WHERE status = 'available'")->fetch()['c'];
    $occupiedBeds = (int)$pdo->query("SELECT COUNT(*) AS c FROM icu_beds WHERE status = 'occupied'")->fetch()['c'];
    $totalBeds = (int)$pdo->query("SELECT COUNT(*) AS c FROM icu_beds")->fetch()['c'];
    $otherBeds = max(0, $totalBeds - $availableBeds - $occupiedBeds);

    $avgLosDays = $pdo->query(
        "SELECT AVG(TIMESTAMPDIFF(HOUR, admitted_at, discharged_at)) / 24.0 AS avg_days
         FROM icu_admissions
         WHERE status = 'discharged' AND discharged_at IS NOT NULL AND admitted_at >= (NOW() - INTERVAL 30 DAY)"
    )->fetch()['avg_days'];

    $avgLosDaysOut = null;
    if ($avgLosDays !== null) {
        $avgLosDaysOut = round((float)$avgLosDays, 1);
    }

    json_response([
        'ok' => true,
        'stats' => [
            'active_patients' => $activePatients,
            'available_beds' => $availableBeds,
            'occupied_beds' => $occupiedBeds,
            'total_beds' => $totalBeds,
            'other_beds' => $otherBeds,
            'avg_los_days' => $avgLosDaysOut,
        ],
    ]);
} catch (Throwable $e) {
    json_response([
        'ok' => false,
        'error' => $e->getMessage(),
    ], 500);
}
