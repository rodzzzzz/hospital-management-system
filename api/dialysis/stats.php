<?php
declare(strict_types=1);

require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';
require_once __DIR__ . '/../_cors.php';
require_once __DIR__ . '/_schema.php';

cors_headers();

try {
    $pdo = db();
    dialysis_ensure_schema($pdo);

    $sessionsToday = (int)$pdo->query('SELECT COUNT(*) AS c FROM dialysis_sessions WHERE DATE(start_time) = CURDATE()')->fetch()['c'];
    $available = (int)$pdo->query("SELECT COUNT(*) AS c FROM dialysis_machines WHERE status = 'available'")->fetch()['c'];
    $total = (int)$pdo->query('SELECT COUNT(*) AS c FROM dialysis_machines')->fetch()['c'];

    $avgMinutes = $pdo->query(
        "SELECT AVG(TIMESTAMPDIFF(MINUTE, start_time, end_time)) AS avg_minutes
         FROM dialysis_sessions
         WHERE status = 'completed' AND start_time >= (NOW() - INTERVAL 30 DAY)"
    )->fetch()['avg_minutes'];

    $avgHours = null;
    if ($avgMinutes !== null) {
        $avgHours = round(((float)$avgMinutes) / 60.0, 1);
    }

    json_response([
        'ok' => true,
        'stats' => [
            'sessions_today' => $sessionsToday,
            'available_machines' => $available,
            'total_machines' => $total,
            'avg_treatment_hours' => $avgHours,
        ],
    ]);
} catch (Throwable $e) {
    json_response([
        'ok' => false,
        'error' => $e->getMessage(),
    ], 500);
}
