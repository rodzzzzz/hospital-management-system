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
    $status      = $_GET['status']     ?? '';
    $orderType   = $_GET['order_type'] ?? '';

    $where  = ['1=1'];
    $params = [];

    if ($admissionId !== null) {
        $where[] = 'o.admission_id = :admission_id';
        $params['admission_id'] = $admissionId;
    }
    if ($patientId !== null) {
        $where[] = 'o.patient_id = :patient_id';
        $params['patient_id'] = $patientId;
    }
    if ($status !== '') {
        $where[] = 'o.status = :status';
        $params['status'] = $status;
    }
    if ($orderType !== '') {
        $where[] = 'o.order_type = :order_type';
        $params['order_type'] = $orderType;
    }

    $stmt = $pdo->prepare(
        "SELECT o.id, o.admission_id, o.patient_id, o.ward, o.order_type, o.order_text,
                o.ordered_by, o.ordered_at, o.status, o.noted_by, o.noted_at,
                o.created_at, o.updated_at,
                p.full_name, p.patient_code
         FROM ward_physician_orders o
         JOIN patients p ON p.id = o.patient_id
         WHERE " . implode(' AND ', $where) . "
         ORDER BY o.ordered_at DESC
         LIMIT 200"
    );
    $stmt->execute($params);
    $rows = $stmt->fetchAll();

    json_response(['ok' => true, 'orders' => $rows]);
} catch (Throwable $e) {
    json_response(['ok' => false, 'error' => $e->getMessage()], 500);
}
