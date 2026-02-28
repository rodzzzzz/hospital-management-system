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
    $limit       = min((int)($_GET['limit'] ?? 50), 200);

    $where  = ['1=1'];
    $params = [];

    if ($admissionId !== null) {
        $where[] = 'v.admission_id = :admission_id';
        $params['admission_id'] = $admissionId;
    }
    if ($patientId !== null) {
        $where[] = 'v.patient_id = :patient_id';
        $params['patient_id'] = $patientId;
    }

    $stmt = $pdo->prepare(
        "SELECT v.id, v.admission_id, v.patient_id, v.ward,
                v.temperature, v.blood_pressure, v.pulse_rate, v.respiratory_rate,
                v.oxygen_saturation, v.pain_scale, v.weight_kg, v.blood_glucose,
                v.recorded_by, v.recorded_at, v.created_at,
                p.full_name, p.patient_code
         FROM ward_vitals v
         JOIN patients p ON p.id = v.patient_id
         WHERE " . implode(' AND ', $where) . "
         ORDER BY v.recorded_at DESC
         LIMIT " . $limit
    );
    $stmt->execute($params);
    $rows = $stmt->fetchAll();

    json_response(['ok' => true, 'vitals' => $rows]);
} catch (Throwable $e) {
    json_response(['ok' => false, 'error' => $e->getMessage()], 500);
}
