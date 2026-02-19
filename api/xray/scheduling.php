<?php
declare(strict_types=1);

require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';
require_once __DIR__ . '/_schema.php';

require_method('GET');

try {
    $pdo = db();
    xray_ensure_schema($pdo);

    $rows = $pdo->query(
        "SELECT id, patient_name, exam_type, priority, status, ordered_at, scheduled_at, technologist_name
           FROM xray_orders
          WHERE status IN ('requested','scheduled','in_progress')
          ORDER BY CASE status
                    WHEN 'in_progress' THEN 1
                    WHEN 'scheduled' THEN 2
                    WHEN 'requested' THEN 3
                    ELSE 4
                   END,
                   COALESCE(scheduled_at, ordered_at) ASC
          LIMIT 50"
    )->fetchAll();

    $queueTotal = (int)$pdo->query("SELECT COUNT(*) AS c FROM xray_orders WHERE status IN ('requested','scheduled','in_progress')")->fetch()['c'];
    $inProgress = (int)$pdo->query("SELECT COUNT(*) AS c FROM xray_orders WHERE status = 'in_progress'")->fetch()['c'];
    $scheduled = (int)$pdo->query("SELECT COUNT(*) AS c FROM xray_orders WHERE status = 'scheduled'")->fetch()['c'];

    $nextSlotMins = max(5, 10 + ($queueTotal * 6));

    $availability = [
        [
            'modality' => 'Xray Room 1',
            'status' => ($inProgress > 0) ? 'busy' : 'available',
            'next_slot_mins' => ($inProgress > 0) ? $nextSlotMins : 0,
            'queue' => $queueTotal,
        ],
        [
            'modality' => 'Portable Xray',
            'status' => ($queueTotal > 6) ? 'busy' : 'available',
            'next_slot_mins' => ($queueTotal > 6) ? (15 + ($queueTotal * 3)) : 0,
            'queue' => max(0, $queueTotal - 2),
        ],
    ];

    json_response([
        'ok' => true,
        'summary' => [
            'queue_total' => $queueTotal,
            'in_progress' => $inProgress,
            'scheduled' => $scheduled,
        ],
        'availability' => $availability,
        'queue' => $rows,
    ]);
} catch (Throwable $e) {
    json_response([
        'ok' => false,
        'error' => $e->getMessage(),
    ], 500);
}
