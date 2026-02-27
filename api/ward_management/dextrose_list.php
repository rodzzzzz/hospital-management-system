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
        $where[] = 'd.admission_id = :admission_id';
        $params['admission_id'] = $admissionId;
    }
    if ($patientId !== null) {
        $where[] = 'd.patient_id = :patient_id';
        $params['patient_id'] = $patientId;
    }
    if ($status !== '') {
        $where[] = 'd.status = :status';
        $params['status'] = $status;
    }

    $stmt = $pdo->prepare(
        "SELECT d.id, d.admission_id, d.patient_id, d.ward, d.bottle_no, d.solution,
                d.volume_ml, d.rate_ml_hr, d.iv_site, d.started_at, d.completed_at,
                d.status, d.recorded_by, d.notes, d.created_at,
                p.full_name, p.patient_code
         FROM ward_dextrose d
         JOIN patients p ON p.id = d.patient_id
         WHERE " . implode(' AND ', $where) . "
         ORDER BY d.started_at DESC
         LIMIT 100"
    );
    $stmt->execute($params);
    $rows = $stmt->fetchAll();

    json_response(['ok' => true, 'dextrose' => $rows]);
} catch (Throwable $e) {
    json_response(['ok' => false, 'error' => $e->getMessage()], 500);
}
