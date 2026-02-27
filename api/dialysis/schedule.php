<?php
declare(strict_types=1);

require_once __DIR__ . '/../_cors.php';
require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';
require_once __DIR__ . '/_schema.php';

cors_headers();
require_method('POST');

try {
    $pdo = db();
    dialysis_ensure_schema($pdo);

    $patientId = require_int('patient_id');
    $machineId = require_int('machine_id');
    $date = require_string('date');
    $slot = require_string('time_slot');
    $notes = trim((string)($_POST['notes'] ?? ''));

    $startEndBySlot = [
        'morning' => ['08:00:00', '12:00:00'],
        'afternoon' => ['13:00:00', '17:00:00'],
        'evening' => ['18:00:00', '22:00:00'],
    ];

    if (!isset($startEndBySlot[$slot])) {
        json_response([
            'ok' => false,
            'error' => 'Invalid time_slot',
        ], 400);
    }

    $startTime = $date . ' ' . $startEndBySlot[$slot][0];
    $endTime = $date . ' ' . $startEndBySlot[$slot][1];

    $stmt = $pdo->prepare(
        'INSERT INTO dialysis_sessions (patient_id, machine_id, start_time, end_time, status, notes) VALUES (:patient_id, :machine_id, :start_time, :end_time, :status, :notes)'
    );
    $stmt->execute([
        'patient_id' => $patientId,
        'machine_id' => $machineId,
        'start_time' => $startTime,
        'end_time' => $endTime,
        'status' => 'scheduled',
        'notes' => $notes === '' ? null : $notes,
    ]);

    json_response([
        'ok' => true,
        'id' => (int)$pdo->lastInsertId(),
    ]);
} catch (Throwable $e) {
    json_response([
        'ok' => false,
        'error' => $e->getMessage(),
    ], 500);
}
