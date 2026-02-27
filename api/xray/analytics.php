<?php
declare(strict_types=1);

require_once __DIR__ . '/../_cors.php';
require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';
require_once __DIR__ . '/_schema.php';

cors_headers();

try {
    $pdo = db();
    xray_ensure_schema($pdo);

    $examTypes = ['Chest X-ray', 'Abdomen X-ray', 'Skull X-ray', 'Extremity X-ray', 'Spine X-ray'];

    $stmtType = $pdo->prepare('SELECT COUNT(*) AS c FROM xray_orders WHERE exam_type = :t AND DATE(ordered_at) = CURDATE()');
    $typeCounts = [];
    foreach ($examTypes as $t) {
        $stmtType->execute(['t' => $t]);
        $typeCounts[] = (int)$stmtType->fetch()['c'];
    }

    $labels = ['8-10', '10-12', '12-2', '2-4', '4-6'];
    $bins = [
        ['08:00:00', '09:59:59'],
        ['10:00:00', '11:59:59'],
        ['12:00:00', '13:59:59'],
        ['14:00:00', '15:59:59'],
        ['16:00:00', '17:59:59'],
    ];

    $d = (new DateTimeImmutable('today'))->format('Y-m-d');

    $stmtBin = $pdo->prepare('SELECT COUNT(*) AS c FROM xray_orders WHERE ordered_at BETWEEN :start_dt AND :end_dt');
    $binCounts = [];
    foreach ($bins as [$from, $to]) {
        $stmtBin->execute([
            'start_dt' => $d . ' ' . $from,
            'end_dt' => $d . ' ' . $to,
        ]);
        $binCounts[] = (int)$stmtBin->fetch()['c'];
    }

    $ttLabels = [];
    $ttMins = [];
    $stmtAvgDay = $pdo->prepare(
        "SELECT AVG(TIMESTAMPDIFF(MINUTE, ordered_at, completed_at)) AS avg_minutes
         FROM xray_orders
         WHERE status IN ('completed','reported') AND completed_at IS NOT NULL AND DATE(ordered_at) = :d"
    );

    $today = new DateTimeImmutable('today');
    for ($i = 6; $i >= 0; $i--) {
        $day = $today->sub(new DateInterval('P' . $i . 'D'));
        $dayStr = $day->format('Y-m-d');
        $stmtAvgDay->execute(['d' => $dayStr]);
        $avg = $stmtAvgDay->fetch()['avg_minutes'];
        $ttLabels[] = $day->format('D');
        $ttMins[] = $avg === null ? 0 : (int)round((float)$avg);
    }

    json_response([
        'ok' => true,
        'exams_by_type' => [
            'labels' => $examTypes,
            'data' => $typeCounts,
        ],
        'orders_by_time' => [
            'labels' => $labels,
            'data' => $binCounts,
        ],
        'turnaround_trend' => [
            'labels' => $ttLabels,
            'data' => $ttMins,
        ],
    ]);
} catch (Throwable $e) {
    json_response([
        'ok' => false,
        'error' => $e->getMessage(),
    ], 500);
}
