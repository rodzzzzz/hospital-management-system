<?php
declare(strict_types=1);

require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';
require_once __DIR__ . '/_schema.php';

try {
    $pdo = db();
    dialysis_ensure_schema($pdo);
    $date = trim((string)($_GET['date'] ?? ''));
    if ($date === '') {
        $date = (new DateTimeImmutable('today'))->format('Y-m-d');
    }

    $labels = ['8am', '10am', '12pm', '2pm', '4pm', '6pm'];
    $bins = [
        ['08:00:00', '09:59:59'],
        ['10:00:00', '11:59:59'],
        ['12:00:00', '13:59:59'],
        ['14:00:00', '15:59:59'],
        ['16:00:00', '17:59:59'],
        ['18:00:00', '19:59:59'],
    ];

    $counts = [];
    $stmt = $pdo->prepare(
        'SELECT COUNT(*) AS c FROM dialysis_sessions WHERE start_time BETWEEN :start_dt AND :end_dt'
    );

    foreach ($bins as [$start, $end]) {
        $stmt->execute([
            'start_dt' => $date . ' ' . $start,
            'end_dt' => $date . ' ' . $end,
        ]);
        $counts[] = (int)$stmt->fetch()['c'];
    }

    $statusCounts = $pdo->query(
        "SELECT status, COUNT(*) AS c
         FROM dialysis_machines
         GROUP BY status"
    )->fetchAll();

    $utilization = [
        'in_use' => 0,
        'available' => 0,
        'maintenance' => 0,
    ];

    foreach ($statusCounts as $row) {
        $utilization[$row['status']] = (int)$row['c'];
    }

    json_response([
        'ok' => true,
        'date' => $date,
        'patient_load' => [
            'labels' => $labels,
            'data' => $counts,
        ],
        'machine_utilization' => [
            'labels' => ['In Use', 'Available', 'Maintenance'],
            'data' => [$utilization['in_use'], $utilization['available'], $utilization['maintenance']],
        ],
    ]);
} catch (Throwable $e) {
    json_response([
        'ok' => false,
        'error' => $e->getMessage(),
    ], 500);
}
