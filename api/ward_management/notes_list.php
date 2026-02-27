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
    $patientId   = !empty($_GET['patient_id']) ? (int)$_GET['patient_id'] : null;
    $ward        = $_GET['ward'] ?? '';
    $noteType    = $_GET['note_type'] ?? '';

    $where = ['1=1'];
    $params = [];

    if ($admissionId !== null) {
        $where[] = 'n.admission_id = :admission_id';
        $params['admission_id'] = $admissionId;
    }
    if ($patientId !== null) {
        $where[] = 'n.patient_id = :patient_id';
        $params['patient_id'] = $patientId;
    }
    if ($ward !== '') {
        $where[] = 'n.ward = :ward';
        $params['ward'] = $ward;
    }
    if ($noteType !== '') {
        $where[] = 'n.note_type = :note_type';
        $params['note_type'] = $noteType;
    }

    $stmt = $pdo->prepare(
        "SELECT n.id, n.admission_id, n.patient_id, n.ward, n.note_type,
                n.note_text, n.vitals_json, n.author_name, n.created_at,
                p.full_name, p.patient_code
         FROM ward_notes n
         JOIN patients p ON p.id = n.patient_id
         WHERE " . implode(' AND ', $where) . "
         ORDER BY n.created_at DESC
         LIMIT 200"
    );
    $stmt->execute($params);
    $rows = $stmt->fetchAll();

    json_response(['ok' => true, 'notes' => $rows]);
} catch (Throwable $e) {
    json_response(['ok' => false, 'error' => $e->getMessage()], 500);
}
