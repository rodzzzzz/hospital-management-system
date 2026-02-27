<?php
declare(strict_types=1);

require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';
require_once __DIR__ . '/../_cors.php';
require_once __DIR__ . '/_tables.php';
require_once __DIR__ . '/../pharmacy/_tables.php';
require_once __DIR__ . '/../price_master/_tables.php';

cors_headers();
require_method('GET');

try {
    $pdo = db();
    ensure_cashier_tables($pdo);
    ensure_pharmacy_tables($pdo);
    ensure_price_master_tables($pdo);

    $status = isset($_GET['status']) ? trim((string)$_GET['status']) : 'pending_invoice';
    if ($status === '') $status = 'pending_invoice';

    $group = isset($_GET['group']) ? strtolower(trim((string)$_GET['group'])) : '';
    $groupByPatient = ($group === 'patient');

    $q = isset($_GET['q']) ? trim((string)$_GET['q']) : '';
    $limit = 100;

    $where = 'WHERE c.status = :status';
    $params = ['status' => $status];

    if ($status === 'pending_invoice') {
        $where .= " AND (inv.id IS NULL OR inv.status <> 'paid')";
    }

    if ($groupByPatient) {
        $where .= " AND c.source_module IN ('lab_request','pharmacy_resit')";
    }

    if ($q !== '') {
        $where .= ' AND (p.patient_code LIKE :q OR p.full_name LIKE :q)';
        $params['q'] = '%' . $q . '%';
    }

    $sql =
        "SELECT
            c.id,
            c.source_module,
            c.source_id,
            c.patient_id,
            p.patient_code,
            p.full_name,
            c.status,
            c.created_at,
            COUNT(i.id) AS items_count,
            li.invoice_id,
            CASE
                WHEN c.source_module IN ('opd_consultation','er_consultation')
                    THEN COALESCE(inv.total, (SELECT price FROM opd_fees WHERE fee_code = 'consultation' LIMIT 1), 0)
                ELSE COALESCE(inv.total, SUM(i.qty * COALESCE(m.price, 0)), 0)
            END AS total
        FROM cashier_charges c
        JOIN patients p ON p.id = c.patient_id
        LEFT JOIN cashier_charge_items i ON i.charge_id = c.id
        LEFT JOIN pharmacy_medicines m ON m.id = i.medicine_id
        LEFT JOIN (
            SELECT charge_id, MAX(id) AS invoice_id
            FROM cashier_invoices
            GROUP BY charge_id
        ) li ON li.charge_id = c.id
        LEFT JOIN cashier_invoices inv ON inv.id = li.invoice_id
        {$where}
        GROUP BY c.id
        ORDER BY c.created_at DESC
        LIMIT {$limit}";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $rows = $stmt->fetchAll();

    if (!$groupByPatient) {
        $charges = array_map(function ($r) {
            return [
                'id' => (int)($r['id'] ?? 0),
                'source_module' => (string)($r['source_module'] ?? ''),
                'source_id' => (int)($r['source_id'] ?? 0),
                'patient_id' => (int)($r['patient_id'] ?? 0),
                'patient_code' => (string)($r['patient_code'] ?? ''),
                'full_name' => (string)($r['full_name'] ?? ''),
                'status' => (string)($r['status'] ?? ''),
                'created_at' => (string)($r['created_at'] ?? ''),
                'items_count' => (int)($r['items_count'] ?? 0),
                'invoice_id' => ($r['invoice_id'] === null || $r['invoice_id'] === '' ? null : (int)$r['invoice_id']),
                'total' => (string)($r['total'] ?? '0'),
            ];
        }, is_array($rows) ? $rows : []);

        json_response(['ok' => true, 'charges' => $charges]);
        exit;
    }

    $byPatient = [];
    foreach (is_array($rows) ? $rows : [] as $r) {
        $patientId = (int)($r['patient_id'] ?? 0);
        if ($patientId <= 0) continue;

        if (!isset($byPatient[$patientId])) {
            $byPatient[$patientId] = [
                'patient_id' => $patientId,
                'patient_code' => (string)($r['patient_code'] ?? ''),
                'full_name' => (string)($r['full_name'] ?? ''),
                'status' => (string)($r['status'] ?? ''),
                'created_at' => (string)($r['created_at'] ?? ''),
                'lab_charge_id' => null,
                'lab_source_id' => null,
                'lab_invoice_id' => null,
                'lab_total' => null,
                'pharmacy_charge_id' => null,
                'pharmacy_source_id' => null,
                'pharmacy_invoice_id' => null,
                'pharmacy_total' => null,
                'total' => '0.00',
            ];
        }

        $src = (string)($r['source_module'] ?? '');
        $chargeId = (int)($r['id'] ?? 0);
        $sourceId = (int)($r['source_id'] ?? 0);
        $invoiceId = ($r['invoice_id'] === null || $r['invoice_id'] === '' ? null : (int)$r['invoice_id']);
        $totalStr = (string)($r['total'] ?? '0');

        if ($src === 'lab_request') {
            if ($byPatient[$patientId]['lab_charge_id'] === null) {
                $byPatient[$patientId]['lab_charge_id'] = $chargeId;
                $byPatient[$patientId]['lab_source_id'] = $sourceId;
                $byPatient[$patientId]['lab_invoice_id'] = $invoiceId;
                $byPatient[$patientId]['lab_total'] = $totalStr;
            }
        } elseif ($src === 'pharmacy_resit') {
            if ($byPatient[$patientId]['pharmacy_charge_id'] === null) {
                $byPatient[$patientId]['pharmacy_charge_id'] = $chargeId;
                $byPatient[$patientId]['pharmacy_source_id'] = $sourceId;
                $byPatient[$patientId]['pharmacy_invoice_id'] = $invoiceId;
                $byPatient[$patientId]['pharmacy_total'] = $totalStr;
            }
        }

        $createdAt = (string)($r['created_at'] ?? '');
        if ($createdAt !== '' && ($byPatient[$patientId]['created_at'] === '' || $createdAt > $byPatient[$patientId]['created_at'])) {
            $byPatient[$patientId]['created_at'] = $createdAt;
        }
    }

    $out = [];
    foreach ($byPatient as $g) {
        $sum = 0.0;
        if ($g['lab_total'] !== null) $sum += (float)$g['lab_total'];
        if ($g['pharmacy_total'] !== null) $sum += (float)$g['pharmacy_total'];
        $g['total'] = number_format($sum, 2, '.', '');
        $out[] = $g;
    }

    json_response(['ok' => true, 'charges' => $out]);
} catch (Throwable $e) {
    json_response(['ok' => false, 'error' => $e->getMessage()], 500);
}
