<?php
declare(strict_types=1);

require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';
require_once __DIR__ . '/../_cors.php';
require_once __DIR__ . '/_tables.php';

cors_headers();
require_method('GET');

try {
    $pdo = db();
    ensure_ward_management_tables($pdo);

    $admissionId = !empty($_GET['admission_id']) ? (int)$_GET['admission_id'] : null;
    $patientId   = !empty($_GET['patient_id'])   ? (int)$_GET['patient_id']   : null;
    $status      = $_GET['status'] ?? '';

    $where  = ['1=1'];
    $params = [];

    if ($admissionId !== null) {
        $where[] = 'm.admission_id = :admission_id';
        $params['admission_id'] = $admissionId;
    }
    if ($patientId !== null) {
        $where[] = 'm.patient_id = :patient_id';
        $params['patient_id'] = $patientId;
    }
    if ($status !== '') {
        $where[] = 'm.status = :status';
        $params['status'] = $status;
    }

    $stmt = $pdo->prepare(
        "SELECT m.id, m.admission_id, m.patient_id, m.ward,
                m.medication_name, m.dose, m.route, m.frequency, m.scheduled_time,
                m.given_at, m.given_by, m.status, m.remarks,
                m.created_at, m.updated_at,
                p.full_name, p.patient_code
         FROM ward_mar m
         JOIN patients p ON p.id = m.patient_id
         WHERE " . implode(' AND ', $where) . "
         ORDER BY m.scheduled_time ASC, m.created_at DESC
         LIMIT 300"
    );
    $stmt->execute($params);
    $rows = $stmt->fetchAll();

    json_response(['ok' => true, 'mar' => $rows]);
} catch (Throwable $e) {
    json_response(['ok' => false, 'error' => $e->getMessage()], 500);
}
