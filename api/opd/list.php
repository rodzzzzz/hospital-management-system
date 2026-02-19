<?php
declare(strict_types=1);

require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';
require_once __DIR__ . '/_tables.php';
require_once __DIR__ . '/../users/_tables.php';

require_method('GET');

try {
    $pdo = db();
    ensure_opd_tables($pdo);
    ensure_users_tables($pdo);

    $patientId = null;
    $patientIdRaw = $_GET['patient_id'] ?? null;
    if ($patientIdRaw !== null && $patientIdRaw !== '') {
        if (!ctype_digit((string)$patientIdRaw)) {
            json_response(['ok' => false, 'error' => 'Invalid patient_id'], 400);
        }
        $patientId = (int)$patientIdRaw;
        if ($patientId <= 0) {
            json_response(['ok' => false, 'error' => 'Invalid patient_id'], 400);
        }
    }

    $latest = isset($_GET['latest']) && (string)$_GET['latest'] === '1';

    $date = trim((string)($_GET['date'] ?? ''));
    if ($date === '') {
        $date = date('Y-m-d');
    }

    $dateFrom = trim((string)($_GET['date_from'] ?? ''));
    $dateTo = trim((string)($_GET['date_to'] ?? ''));
    if ($dateFrom !== '' && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateFrom)) {
        json_response(['ok' => false, 'error' => 'Invalid date_from'], 400);
    }
    if ($dateTo !== '' && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateTo)) {
        json_response(['ok' => false, 'error' => 'Invalid date_to'], 400);
    }

    $allRaw = strtolower(trim((string)($_GET['all'] ?? '')));
    $all = ($allRaw === '1' || $allRaw === 'true' || $allRaw === 'yes');

    $status = strtolower(trim((string)($_GET['status'] ?? '')));
    if ($status !== '' && !in_array($status, ['requested', 'scheduled', 'waiting', 'checked_in', 'in_consultation', 'completed', 'cancelled', 'no_show', 'rejected'], true)) {
        json_response(['ok' => false, 'error' => 'Invalid status'], 400);
    }

    $q = trim((string)($_GET['q'] ?? ''));
    $limit = ($patientId !== null && $latest) ? 1 : 200;

    $where = [];
    $params = [];

    if ($patientId !== null) {
        $where[] = 'a.patient_id = :patient_id';
        $params['patient_id'] = $patientId;
    } else {
        if ($status === 'requested' || $status === 'rejected') {
        } else {
            if ($all) {
                $where[] = 'a.appointment_at IS NOT NULL';
            } else if ($dateFrom !== '' || $dateTo !== '') {
                $from = $dateFrom !== '' ? $dateFrom : $date;
                $where[] = 'a.appointment_at IS NOT NULL AND DATE(a.appointment_at) >= :date_from';
                $params['date_from'] = $from;

                if ($dateTo !== '') {
                    $where[] = 'DATE(a.appointment_at) <= :date_to';
                    $params['date_to'] = $dateTo;
                }
            } else {
                $where[] = 'a.appointment_at IS NOT NULL AND DATE(a.appointment_at) = :date';
                $params['date'] = $date;
            }
        }
    }

    if ($status !== '') {
        $where[] = 'a.status = :status';
        $params['status'] = $status;
    }

    if ($q !== '') {
        $where[] = '(p.full_name LIKE :q OR p.patient_code LIKE :q OR a.doctor_name LIKE :q)';
        $params['q'] = '%' . $q . '%';
    }

    $doctorName = trim((string)($_GET['doctor_name'] ?? ''));
    if ($doctorName !== '') {
        $where[] = 'a.doctor_name = :doctor_name';
        $params['doctor_name'] = $doctorName;
    }

    $sqlWhere = count($where) ? ('WHERE ' . implode(' AND ', $where)) : '';

    $orderBy = ($patientId !== null)
        ? (' ORDER BY a.created_at DESC, a.id DESC')
        : (' ORDER BY a.appointment_at ASC');

    $sql =
        'SELECT
            a.id,
            a.patient_id,
            p.patient_code,
            p.full_name,
            a.doctor_name,
            a.appointment_at,
            a.status,
            a.notes,
            a.nursing_assessment_id,
            a.lab_tests_json,
            a.lab_note,
            a.approved_by_user_id,
            u.full_name AS approved_by_name,
            a.created_at,
            a.updated_at
        FROM opd_appointments a
        JOIN patients p ON p.id = a.patient_id
        LEFT JOIN users u ON u.id = a.approved_by_user_id
        ' . $sqlWhere .
        $orderBy .
        ' LIMIT ' . $limit;

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $rows = $stmt->fetchAll();

    json_response([
        'ok' => true,
        'appointments' => $rows,
        'date' => $date,
    ]);
} catch (Throwable $e) {
    json_response([
        'ok' => false,
        'error' => $e->getMessage(),
    ], 500);
}
