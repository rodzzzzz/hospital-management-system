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
    ensure_discharge_tables($pdo);

    $patientId   = !empty($_GET['patient_id'])   ? (int)$_GET['patient_id']   : null;
    $admissionId = !empty($_GET['admission_id'])  ? (int)$_GET['admission_id'] : null;
    $status      = $_GET['status'] ?? '';

    $where  = ['1=1'];
    $params = [];

    if ($patientId !== null) {
        $where[]              = 'dp.patient_id = :patient_id';
        $params['patient_id'] = $patientId;
    }
    if ($admissionId !== null) {
        $where[]               = 'dp.admission_id = :admission_id';
        $params['admission_id'] = $admissionId;
    }
    if ($status !== '') {
        $where[]          = 'dp.status = :status';
        $params['status'] = $status;
    }

    $stmt = $pdo->prepare(
        "SELECT dp.id, dp.plan_no, dp.admission_id, dp.patient_id,
                dp.expected_discharge_date, dp.actual_discharge_date,
                dp.discharge_destination, dp.discharge_diagnosis,
                dp.discharge_condition, dp.discharge_notes,
                dp.medications_on_discharge, dp.diet_instructions,
                dp.activity_restrictions, dp.wound_care_instructions,
                dp.return_precautions, dp.status,
                dp.planned_by, dp.cleared_by, dp.cleared_at,
                dp.created_at, dp.updated_at,
                p.full_name, p.patient_code,
                a.admission_no, a.ward, a.room_no
         FROM discharge_plans dp
         JOIN patients p  ON p.id  = dp.patient_id
         JOIN admissions a ON a.id = dp.admission_id
         WHERE " . implode(' AND ', $where) . "
         ORDER BY dp.created_at DESC
         LIMIT 200"
    );
    $stmt->execute($params);
    $rows = $stmt->fetchAll();

    json_response(['ok' => true, 'plans' => $rows]);
} catch (Throwable $e) {
    json_response(['ok' => false, 'error' => $e->getMessage()], 500);
}
