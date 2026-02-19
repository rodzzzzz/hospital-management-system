<?php
declare(strict_types=1);

require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';
require_once __DIR__ . '/_schema.php';

require_method('GET');

function xray_seeded_int(string $seed, int $min, int $max): int
{
    $h = crc32($seed);
    $range = max(1, $max - $min + 1);
    return $min + (int)($h % $range);
}

try {
    $pdo = db();
    xray_ensure_schema($pdo);

    $rows = $pdo->query(
        "SELECT id, patient_name, exam_type, priority, status, ordered_at, completed_at
           FROM xray_orders
          WHERE status IN ('completed','reported')
          ORDER BY COALESCE(completed_at, ordered_at) DESC
          LIMIT 50"
    )->fetchAll();

    $out = [];
    foreach (is_array($rows) ? $rows : [] as $r) {
        $id = (int)($r['id'] ?? 0);
        $prio = strtolower((string)($r['priority'] ?? ''));
        $exam = (string)($r['exam_type'] ?? '');
        $seed = 'rel|' . $id;

        $critical = ($prio === 'stat') || (stripos($exam, 'Chest') !== false && $prio === 'urgent' && xray_seeded_int($seed, 0, 100) < 35);
        $released = ((string)($r['status'] ?? '') === 'reported') && (xray_seeded_int($seed . '|r', 0, 100) < 70);

        $releasedTo = $released ? 'Attending Physician' : null;
        $releasedAt = null;
        $ca = (string)($r['completed_at'] ?? '');
        if ($released && $ca !== '') {
            $dt = new DateTimeImmutable(str_replace(' ', 'T', $ca));
            $mins = xray_seeded_int($seed . '|mins', 10, 90);
            $releasedAt = $dt->add(new DateInterval('PT' . $mins . 'M'))->format('Y-m-d H:i:s');
        }

        $out[] = [
            'id' => $id,
            'patient_name' => (string)($r['patient_name'] ?? ''),
            'exam_type' => $exam,
            'priority' => (string)($r['priority'] ?? ''),
            'status' => (string)($r['status'] ?? ''),
            'completed_at' => (string)($r['completed_at'] ?? ''),
            'released' => $released,
            'released_to' => $releasedTo,
            'released_at' => $releasedAt,
            'critical_findings' => $critical,
        ];
    }

    $releasedCount = 0;
    $criticalCount = 0;
    foreach ($out as $o) {
        if (!empty($o['released'])) $releasedCount++;
        if (!empty($o['critical_findings'])) $criticalCount++;
    }

    json_response([
        'ok' => true,
        'summary' => [
            'ready_for_release' => count($out),
            'released' => $releasedCount,
            'critical_findings' => $criticalCount,
        ],
        'results' => $out,
    ]);
} catch (Throwable $e) {
    json_response([
        'ok' => false,
        'error' => $e->getMessage(),
    ], 500);
}
