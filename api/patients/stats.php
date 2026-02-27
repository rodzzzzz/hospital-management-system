<?php
declare(strict_types=1);

require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';
require_once __DIR__ . '/../_cors.php';

cors_headers();
require_method('GET');

try {
    $pdo = db();

    $total = (int)$pdo->query('SELECT COUNT(*) FROM patients')->fetchColumn();

    $inTreatmentStmt = $pdo->query("SELECT COUNT(*) FROM patients WHERE patient_type IN ('inpatient','dialysis')");
    $inTreatment = (int)$inTreatmentStmt->fetchColumn();

    $waitingStmt = $pdo->query("SELECT COUNT(*) FROM patients WHERE patient_type = 'er'");
    $waiting = (int)$waitingStmt->fetchColumn();

    $surgeriesStmt = $pdo->query("SELECT COUNT(*) FROM patients WHERE department = 'surgery'");
    $surgeries = (int)$surgeriesStmt->fetchColumn();

    $discharged = 0;

    $deptRows = $pdo->query('SELECT department, COUNT(*) AS c FROM patients GROUP BY department')->fetchAll();
    $dept = [];
    foreach ($deptRows as $r) {
        $k = trim((string)($r['department'] ?? ''));
        if ($k === '') {
            $k = 'other';
        }
        $dept[$k] = (int)$r['c'];
    }

    $locRows = $pdo->query(
        "SELECT DATE_FORMAT(created_at, '%Y-%m-%d %H:00:00') AS hour_bucket, initial_location, COUNT(*) AS c
         FROM patients
         WHERE created_at >= DATE_SUB(NOW(), INTERVAL 8 HOUR)
         GROUP BY hour_bucket, initial_location
         ORDER BY hour_bucket ASC"
    )->fetchAll();

    $labels = [];
    for ($i = 7; $i >= 0; $i--) {
        $labels[] = date('g A', strtotime('-' . $i . ' hour'));
    }

    $series = [
        'emergency' => array_fill(0, count($labels), 0),
        'or' => array_fill(0, count($labels), 0),
        'pharmacy' => array_fill(0, count($labels), 0),
    ];

    $labelIndex = [];
    foreach ($labels as $idx => $lbl) {
        $labelIndex[$lbl] = $idx;
    }

    foreach ($locRows as $r) {
        $bucket = (string)($r['hour_bucket'] ?? '');
        if ($bucket === '') {
            continue;
        }
        $lbl = date('g A', strtotime($bucket));
        if (!array_key_exists($lbl, $labelIndex)) {
            continue;
        }
        $idx = $labelIndex[$lbl];

        $loc = trim((string)($r['initial_location'] ?? ''));
        $count = (int)$r['c'];

        if ($loc === 'emergency') {
            $series['emergency'][$idx] += $count;
        } elseif ($loc === 'or') {
            $series['or'][$idx] += $count;
        } elseif ($loc === 'pharmacy') {
            $series['pharmacy'][$idx] += $count;
        }
    }

    json_response([
        'ok' => true,
        'cards' => [
            'total_patients' => $total,
            'in_treatment' => $inTreatment,
            'waiting' => $waiting,
            'surgeries' => $surgeries,
            'discharged' => $discharged,
        ],
        'charts' => [
            'department' => $dept,
            'flow' => [
                'labels' => $labels,
                'series' => $series,
            ],
        ],
    ]);
} catch (Throwable $e) {
    json_response([
        'ok' => false,
        'error' => $e->getMessage(),
    ], 500);
}
