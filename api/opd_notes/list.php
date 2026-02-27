<?php
declare(strict_types=1);

require_once __DIR__ . '/../_cors.php';
require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';
require_once __DIR__ . '/_tables.php';

cors_headers();
require_method('GET');

try {
    $appointmentId = null;
    $appointmentIdRaw = $_GET['appointment_id'] ?? null;
    if ($appointmentIdRaw !== null && $appointmentIdRaw !== '') {
        if (!ctype_digit((string)$appointmentIdRaw)) {
            json_response(['ok' => false, 'error' => 'Invalid appointment_id'], 400);
        }
        $appointmentId = (int)$appointmentIdRaw;
        if ($appointmentId <= 0) {
            json_response(['ok' => false, 'error' => 'Invalid appointment_id'], 400);
        }
    }

    $allRaw = strtolower(trim((string)($_GET['all'] ?? '')));
    $all = ($allRaw === '1' || $allRaw === 'true' || $allRaw === 'yes');

    $q = trim((string)($_GET['q'] ?? ''));

    if (!$all && $appointmentId === null) {
        json_response(['ok' => false, 'error' => 'Missing or invalid appointment_id'], 400);
    }

    $pdo = db();
    ensure_opd_notes_tables($pdo);

    $where = [];
    $params = [];

    if ($appointmentId !== null) {
        $where[] = 'n.appointment_id = :appointment_id';
        $params['appointment_id'] = $appointmentId;
    }

    if ($q !== '') {
        $where[] = '(p.full_name LIKE :q1 OR p.patient_code LIKE :q2 OR n.doctor_name LIKE :q3)';
        $like = '%' . $q . '%';
        $params['q1'] = $like;
        $params['q2'] = $like;
        $params['q3'] = $like;
    }

    $sqlWhere = count($where) ? ('WHERE ' . implode(' AND ', $where)) : '';
    $limit = $all ? 300 : 200;

    $stmt = $pdo->prepare(
        'SELECT n.*, p.patient_code, p.full_name
         FROM opd_consultation_notes n
         JOIN patients p ON p.id = n.patient_id
         ' . $sqlWhere . '
         ORDER BY n.created_at DESC, n.id DESC
         LIMIT ' . $limit
    );
    $stmt->execute($params);
    $rows = $stmt->fetchAll();

    json_response(['ok' => true, 'notes' => $rows]);
} catch (Throwable $e) {
    json_response(['ok' => false, 'error' => $e->getMessage()], 500);
}
