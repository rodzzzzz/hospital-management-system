<?php
declare(strict_types=1);

require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';
require_once __DIR__ . '/_tables.php';

require_method('GET');

try {
    $pdo = db();
    ensure_lab_tables($pdo);

    $mode = strtolower(trim((string)($_GET['mode'] ?? '')));
    $status = strtolower(trim((string)($_GET['status'] ?? '')));
    $limit = 200;

    $q = trim((string)($_GET['q'] ?? ''));

    $where = [];
    $params = [];

    if ($mode === 'doctor') {
        $where[] = "r.requester_role = 'nurse'";
    }

    if ($mode === 'er') {
        $where[] = "r.source_unit = 'ER'";
    }

    if ($mode === 'opd') {
        $where[] = "r.source_unit = 'OPD'";
    }

    if ($status !== '') {
        $where[] = 'r.status = :status';
        $params['status'] = $status;
    } else {
        if ($mode === 'doctor') {
            $where[] = "r.status IN ('pending_approval','approved','rejected','collected','in_progress','completed','cancelled')";
        } elseif ($mode === 'lab') {
            $where[] = "r.status IN ('approved','collected','in_progress','completed')";
        } elseif ($mode === 'lab_pending') {
            $where[] = "r.status IN ('pending_approval','approved','in_progress')";
        }
    }

    $patientId = $_GET['patient_id'] ?? null;
    if ($patientId !== null && $patientId !== '' && ctype_digit((string)$patientId)) {
        $where[] = 'r.patient_id = :patient_id';
        $params['patient_id'] = (int)$patientId;
    }

    $encounterId = $_GET['encounter_id'] ?? null;
    if ($encounterId !== null && $encounterId !== '' && ctype_digit((string)$encounterId)) {
        $where[] = 'r.encounter_id = :encounter_id';
        $params['encounter_id'] = (int)$encounterId;
    }

    if ($q !== '') {
        $where[] = '(
            r.request_no LIKE :q_request_no
            OR p.full_name LIKE :q_full_name
            OR p.patient_code LIKE :q_patient_code
            OR r.chief_complaint LIKE :q_chief_complaint
            OR i.test_name LIKE :q_test_name
        )';
        $like = '%' . $q . '%';
        $params['q_request_no'] = $like;
        $params['q_full_name'] = $like;
        $params['q_patient_code'] = $like;
        $params['q_chief_complaint'] = $like;
        $params['q_test_name'] = $like;
    }

    $sqlWhere = count($where) ? ('WHERE ' . implode(' AND ', $where)) : '';

    $orderBy = 'ORDER BY r.created_at DESC';
    if ($mode === 'lab_pending' || $mode === 'lab') {
        $orderBy = "ORDER BY FIELD(r.priority,'stat','urgent','routine') ASC, r.created_at DESC";
    }

    $sql =
        "SELECT
            r.id,
            r.request_no,
            r.patient_id,
            r.encounter_id,
            p.patient_code,
            p.full_name,
            r.source_unit,
            r.triage_level,
            r.chief_complaint,
            r.priority,
            r.status,
            r.cashier_status,
            r.requested_by,
            r.requester_role,
            r.approved_by,
            r.approved_at,
            r.rejection_reason,
            r.created_at,
            MAX(res.released_by) AS released_by,
            MAX(res.released_at) AS released_at,
            GROUP_CONCAT(i.test_name ORDER BY i.id SEPARATOR ', ') AS tests
        FROM lab_requests r
        JOIN patients p ON p.id = r.patient_id
        LEFT JOIN lab_request_items i ON i.request_id = r.id
        LEFT JOIN lab_results res ON res.request_item_id = i.id
        {$sqlWhere}
        GROUP BY r.id
        {$orderBy}
        LIMIT {$limit}";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $rows = $stmt->fetchAll();

    json_response([
        'ok' => true,
        'requests' => $rows,
    ]);
} catch (Throwable $e) {
    json_response([
        'ok' => false,
        'error' => $e->getMessage(),
    ], 500);
}
