<?php
declare(strict_types=1);

require_once __DIR__ . '/../_cors.php';
require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';
require_once __DIR__ . '/_schema.php';

cors_headers();

try {
    $pdo = db();
    dialysis_ensure_schema($pdo);
    $id = require_int('id');

    $stmt = $pdo->prepare(
        "SELECT s.id,
                p.patient_code,
                p.full_name,
                m.machine_code,
                s.start_time,
                s.end_time,
                s.status,
                s.notes
         FROM dialysis_sessions s
         JOIN dialysis_patients p ON p.id = s.patient_id
         JOIN dialysis_machines m ON m.id = s.machine_id
         WHERE s.id = :id"
    );
    $stmt->execute(['id' => $id]);

    $session = $stmt->fetch();
    if (!$session) {
        json_response([
            'ok' => false,
            'error' => 'Session not found',
        ], 404);
    }

    json_response([
        'ok' => true,
        'session' => $session,
    ]);
} catch (Throwable $e) {
    json_response([
        'ok' => false,
        'error' => $e->getMessage(),
    ], 500);
}
