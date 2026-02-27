<?php
declare(strict_types=1);

require_once __DIR__ . '/../_cors.php';
require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';
require_once __DIR__ . '/_schema.php';

cors_headers();

try {
    $pdo = db();
    icu_ensure_schema($pdo);

    $labels = [];
    $occupancyPct = [];

    $totalBeds = (int)$pdo->query('SELECT COUNT(*) AS c FROM icu_beds')->fetch()['c'];
    if ($totalBeds <= 0) {
        $totalBeds = 1;
    }

    $stmtOcc = $pdo->prepare(
        "SELECT COUNT(*) AS c
         FROM icu_admissions
         WHERE admitted_at <= :end_dt
           AND (discharged_at IS NULL OR discharged_at > :start_dt)"
    );

    $today = new DateTimeImmutable('today');
    for ($i = 6; $i >= 0; $i--) {
        $day = $today->sub(new DateInterval('P' . $i . 'D'));
        $start = $day->format('Y-m-d 00:00:00');
        $end = $day->format('Y-m-d 23:59:59');
        $stmtOcc->execute(['start_dt' => $start, 'end_dt' => $end]);
        $occ = (int)$stmtOcc->fetch()['c'];
        $labels[] = $day->format('D');
        $occupancyPct[] = (int)round(($occ / $totalBeds) * 100);
    }

    $bins = [
        ['00:00:00', '07:59:59', 'Night'],
        ['08:00:00', '15:59:59', 'Day'],
        ['16:00:00', '23:59:59', 'Evening'],
    ];

    $admitLabels = [];
    $admitCounts = [];
    $stmtAdmits = $pdo->prepare(
        'SELECT COUNT(*) AS c FROM icu_admissions WHERE admitted_at BETWEEN :start_dt AND :end_dt'
    );

    $d = $today->format('Y-m-d');
    foreach ($bins as [$from, $to, $label]) {
        $stmtAdmits->execute([
            'start_dt' => $d . ' ' . $from,
            'end_dt' => $d . ' ' . $to,
        ]);
        $admitLabels[] = $label;
        $admitCounts[] = (int)$stmtAdmits->fetch()['c'];
    }

    json_response([
        'ok' => true,
        'occupancy_trend' => [
            'labels' => $labels,
            'data' => $occupancyPct,
        ],
        'admissions_by_shift' => [
            'labels' => $admitLabels,
            'data' => $admitCounts,
        ],
    ]);
} catch (Throwable $e) {
    json_response([
        'ok' => false,
        'error' => $e->getMessage(),
    ], 500);
}
