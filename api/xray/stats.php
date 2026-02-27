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

    $ordersToday = (int)$pdo->query('SELECT COUNT(*) AS c FROM xray_orders WHERE DATE(ordered_at) = CURDATE()')->fetch()['c'];
    $pending = (int)$pdo->query("SELECT COUNT(*) AS c FROM xray_orders WHERE status IN ('requested','scheduled','in_progress')")->fetch()['c'];
    $reportedToday = (int)$pdo->query("SELECT COUNT(*) AS c FROM xray_orders WHERE status IN ('reported','completed') AND DATE(ordered_at) = CURDATE()")->fetch()['c'];

    $avgMins = $pdo->query(
        "SELECT AVG(TIMESTAMPDIFF(MINUTE, ordered_at, completed_at)) AS avg_minutes
         FROM xray_orders
         WHERE status IN ('completed','reported') AND completed_at IS NOT NULL AND ordered_at >= (NOW() - INTERVAL 30 DAY)"
    )->fetch()['avg_minutes'];

    $avgOut = null;
    if ($avgMins !== null) {
        $avgOut = (int)round((float)$avgMins);
    }

    json_response([
        'ok' => true,
        'stats' => [
            'orders_today' => $ordersToday,
            'pending_orders' => $pending,
            'reported_today' => $reportedToday,
            'avg_turnaround_mins' => $avgOut,
        ],
    ]);
} catch (Throwable $e) {
    json_response([
        'ok' => false,
        'error' => $e->getMessage(),
    ], 500);
}
