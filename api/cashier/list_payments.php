<?php
declare(strict_types=1);

require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';
require_once __DIR__ . '/_tables.php';

require_method('GET');

try {
    $pdo = db();
    ensure_cashier_tables($pdo);

    $q = isset($_GET['q']) ? trim((string)$_GET['q']) : '';
    $patientId = isset($_GET['patient_id']) ? trim((string)$_GET['patient_id']) : '';
    $encounterId = isset($_GET['encounter_id']) ? trim((string)$_GET['encounter_id']) : '';
    $limit = 100;

    $where = 'WHERE 1=1';
    $params = [];

    if ($q !== '') {
        $where .= ' AND (p.patient_code LIKE :q OR p.full_name LIKE :q OR pay.method LIKE :q)';
        $params['q'] = '%' . $q . '%';
    }

    if ($patientId !== '' && ctype_digit($patientId)) {
        $where .= ' AND inv.patient_id = :patient_id';
        $params['patient_id'] = (int)$patientId;
    }

    if ($encounterId !== '' && ctype_digit($encounterId)) {
        $where .= ' AND inv.encounter_id = :encounter_id';
        $params['encounter_id'] = (int)$encounterId;
    }

    $sql =
        "SELECT
            pay.id,
            pay.invoice_id,
            pay.amount,
            pay.change_amount,
            pay.method,
            pay.received_by,
            pay.created_at,
            inv.patient_id,
            inv.encounter_id,
            p.patient_code,
            p.full_name
        FROM cashier_payments pay
        JOIN cashier_invoices inv ON inv.id = pay.invoice_id
        JOIN patients p ON p.id = inv.patient_id
        {$where}
        ORDER BY pay.created_at DESC
        LIMIT {$limit}";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $rows = $stmt->fetchAll();

    $payments = array_map(function ($r) {
        return [
            'id' => (int)($r['id'] ?? 0),
            'invoice_id' => (int)($r['invoice_id'] ?? 0),
            'patient_id' => (int)($r['patient_id'] ?? 0),
            'encounter_id' => ($r['encounter_id'] === null ? null : (int)$r['encounter_id']),
            'patient_code' => (string)($r['patient_code'] ?? ''),
            'full_name' => (string)($r['full_name'] ?? ''),
            'amount' => (string)($r['amount'] ?? ''),
            'change_amount' => (string)($r['change_amount'] ?? '0.00'),
            'method' => (string)($r['method'] ?? ''),
            'received_by' => (string)($r['received_by'] ?? ''),
            'created_at' => (string)($r['created_at'] ?? ''),
        ];
    }, is_array($rows) ? $rows : []);

    json_response(['ok' => true, 'payments' => $payments]);
} catch (Throwable $e) {
    json_response(['ok' => false, 'error' => $e->getMessage()], 500);
}
