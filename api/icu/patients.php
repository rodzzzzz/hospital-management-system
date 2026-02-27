<?php
declare(strict_types=1);

require_once __DIR__ . '/../_cors.php';
require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';
require_once __DIR__ . '/_schema.php';

cors_headers();
require_method('GET');

try {
    $pdo = db();
    icu_ensure_schema($pdo);

    $rows = $pdo->query(
        "SELECT a.id,
                a.patient_name,
                b.bed_code,
                a.admitted_at,
                a.diagnosis
           FROM icu_admissions a
           JOIN icu_beds b ON b.id = a.bed_id
          WHERE a.status = 'admitted'
          ORDER BY a.admitted_at DESC
          LIMIT 50"
    )->fetchAll();

    $attendings = [
        'Dr. Reyes',
        'Dr. Santos',
        'Dr. Garcia',
        'Dr. Cruz',
        'Dr. Tan',
    ];

    $out = [];
    foreach (is_array($rows) ? $rows : [] as $i => $r) {
        $out[] = [
            'id' => (int)($r['id'] ?? 0),
            'patient_name' => (string)($r['patient_name'] ?? ''),
            'bed_code' => (string)($r['bed_code'] ?? ''),
            'attending_physician' => $attendings[$i % count($attendings)],
            'admitted_at' => (string)($r['admitted_at'] ?? ''),
            'diagnosis' => (string)($r['diagnosis'] ?? ''),
        ];
    }

    json_response([
        'ok' => true,
        'patients' => $out,
    ]);
} catch (Throwable $e) {
    json_response([
        'ok' => false,
        'error' => $e->getMessage(),
    ], 500);
}
