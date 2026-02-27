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
    ensure_cashier_tables($pdo);

    $status = isset($_GET['status']) ? trim((string)$_GET['status']) : '';
    $q = isset($_GET['q']) ? trim((string)$_GET['q']) : '';

    $patientId = isset($_GET['patient_id']) ? trim((string)$_GET['patient_id']) : '';
    $encounterId = isset($_GET['encounter_id']) ? trim((string)$_GET['encounter_id']) : '';

    $limit = 100;

    $where = 'WHERE 1=1';
    $params = [];

    if ($status !== '') {
        $where .= ' AND i.status = :status';
        $params['status'] = $status;
    }

    if ($q !== '') {
        $where .= ' AND (p.patient_code LIKE :q_code OR p.full_name LIKE :q_name OR i.id = :q_exact)';
        $params['q_code'] = '%' . $q . '%';
        $params['q_name'] = '%' . $q . '%';
        $params['q_exact'] = ctype_digit($q) ? (int)$q : 0;
    }

    if ($patientId !== '' && ctype_digit($patientId)) {
        $where .= ' AND i.patient_id = :patient_id';
        $params['patient_id'] = (int)$patientId;
    }

    if ($encounterId !== '' && ctype_digit($encounterId)) {
        $where .= ' AND i.encounter_id = :encounter_id';
        $params['encounter_id'] = (int)$encounterId;
    }

    $sql =
        "SELECT
            i.id,
            i.charge_id,
            i.patient_id,
            i.encounter_id,
            p.patient_code,
            p.full_name,
            i.status,
            i.total,
            i.created_at,
            COALESCE(SUM(pay.amount - COALESCE(pay.change_amount, 0)), 0) AS paid,
            (SELECT COALESCE(cp.change_amount, 0) FROM cashier_payments cp WHERE cp.invoice_id = i.id ORDER BY cp.created_at DESC, cp.id DESC LIMIT 1) AS last_change,
            (SELECT cp.created_at FROM cashier_payments cp WHERE cp.invoice_id = i.id ORDER BY cp.created_at DESC, cp.id DESC LIMIT 1) AS last_paid_at
        FROM cashier_invoices i
        JOIN patients p ON p.id = i.patient_id
        LEFT JOIN cashier_payments pay ON pay.invoice_id = i.id
        {$where}
        GROUP BY i.id
        ORDER BY COALESCE((SELECT cp.created_at FROM cashier_payments cp WHERE cp.invoice_id = i.id ORDER BY cp.created_at DESC, cp.id DESC LIMIT 1), i.created_at) DESC
        LIMIT {$limit}";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $rows = $stmt->fetchAll();

    $invoices = array_map(function ($r) {
        $total = (float)($r['total'] ?? 0);
        $paid = (float)($r['paid'] ?? 0);
        $balance = $total - $paid;
        if ($balance < 0) $balance = 0;
        $lastChange = (float)($r['last_change'] ?? 0);
        $lastPaidAt = (string)($r['last_paid_at'] ?? '');
        return [
            'id' => (int)($r['id'] ?? 0),
            'charge_id' => ($r['charge_id'] === null ? null : (int)$r['charge_id']),
            'patient_id' => (int)($r['patient_id'] ?? 0),
            'encounter_id' => ($r['encounter_id'] === null ? null : (int)$r['encounter_id']),
            'patient_code' => (string)($r['patient_code'] ?? ''),
            'full_name' => (string)($r['full_name'] ?? ''),
            'status' => (string)($r['status'] ?? ''),
            'total' => number_format($total, 2, '.', ''),
            'paid' => number_format($paid, 2, '.', ''),
            'balance' => number_format($balance, 2, '.', ''),
            'last_change' => number_format($lastChange, 2, '.', ''),
            'last_paid_at' => $lastPaidAt,
            'created_at' => (string)($r['created_at'] ?? ''),
        ];
    }, is_array($rows) ? $rows : []);

    json_response(['ok' => true, 'invoices' => $invoices]);
} catch (Throwable $e) {
    json_response(['ok' => false, 'error' => $e->getMessage()], 500);
}
