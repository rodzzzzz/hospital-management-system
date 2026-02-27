<?php
declare(strict_types=1);

require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';
require_once __DIR__ . '/../_cors.php';
require_once __DIR__ . '/_tables.php';

cors_headers();
require_method('POST');

try {
    $pdo = db();
    ensure_admissions_tables($pdo);

    $body = json_decode(file_get_contents('php://input'), true) ?? [];

    $patientId          = (int)($body['patient_id'] ?? 0);
    $scheduledDate      = trim($body['scheduled_date'] ?? '');
    $scheduledTime      = trim($body['scheduled_time'] ?? '');
    $ward               = trim($body['ward'] ?? '');
    $procedureName      = trim($body['procedure_name'] ?? '');
    $admittingPhysician = trim($body['admitting_physician'] ?? '');
    $notes              = trim($body['notes'] ?? '');

    if ($patientId <= 0) {
        json_response(['ok' => false, 'error' => 'patient_id is required'], 422);
    }
    if ($scheduledDate === '') {
        json_response(['ok' => false, 'error' => 'scheduled_date is required'], 422);
    }

    $p = $pdo->prepare('SELECT id FROM patients WHERE id = :id LIMIT 1');
    $p->execute(['id' => $patientId]);
    if (!$p->fetch()) {
        json_response(['ok' => false, 'error' => 'Patient not found'], 404);
    }

    $ins = $pdo->prepare(
        "INSERT INTO pre_admissions
            (patient_id, scheduled_date, scheduled_time, ward, procedure_name, admitting_physician, notes, status)
         VALUES
            (:patient_id, :scheduled_date, :scheduled_time, :ward, :procedure_name, :admitting_physician, :notes, 'scheduled')"
    );
    $ins->execute([
        'patient_id'          => $patientId,
        'scheduled_date'      => $scheduledDate,
        'scheduled_time'      => $scheduledTime ?: null,
        'ward'                => $ward,
        'procedure_name'      => $procedureName,
        'admitting_physician' => $admittingPhysician,
        'notes'               => $notes,
    ]);

    $id = (int)$pdo->lastInsertId();
    $no = 'PRE-' . date('Ymd') . '-' . str_pad((string)$id, 6, '0', STR_PAD_LEFT);
    $pdo->prepare('UPDATE pre_admissions SET pre_admission_no = :no WHERE id = :id')
        ->execute(['no' => $no, 'id' => $id]);

    json_response(['ok' => true, 'pre_admission_id' => $id, 'pre_admission_no' => $no]);
} catch (Throwable $e) {
    json_response(['ok' => false, 'error' => $e->getMessage()], 500);
}
