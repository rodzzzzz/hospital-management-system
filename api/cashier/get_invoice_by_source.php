<?php
declare(strict_types=1);

require_once __DIR__ . '/../_cors.php';
require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';
require_once __DIR__ . '/_tables.php';

cors_headers();
require_method('GET');

try {
    $sourceModule = trim((string)($_GET['source_module'] ?? ''));
    if ($sourceModule === '') {
        json_response(['ok' => false, 'error' => 'Missing source_module'], 400);
    }

    $sourceIdRaw = $_GET['source_id'] ?? null;
    if (!is_int($sourceIdRaw) && !(is_string($sourceIdRaw) && ctype_digit($sourceIdRaw))) {
        json_response(['ok' => false, 'error' => 'Missing or invalid source_id'], 400);
    }
    $sourceId = (int)$sourceIdRaw;

    $pdo = db();
    ensure_cashier_tables($pdo);

    $cStmt = $pdo->prepare('SELECT id FROM cashier_charges WHERE source_module = :m AND source_id = :sid LIMIT 1');
    $cStmt->execute(['m' => $sourceModule, 'sid' => $sourceId]);
    $charge = $cStmt->fetch();

    if (!$charge) {
        json_response(['ok' => true, 'charge_id' => null, 'invoice' => null]);
    }

    $chargeId = (int)($charge['id'] ?? 0);
    if ($chargeId <= 0) {
        json_response(['ok' => true, 'charge_id' => null, 'invoice' => null]);
    }

    $invIdStmt = $pdo->prepare('SELECT id FROM cashier_invoices WHERE charge_id = :cid ORDER BY id DESC LIMIT 1');
    $invIdStmt->execute(['cid' => $chargeId]);
    $invRow = $invIdStmt->fetch();

    if (!$invRow) {
        json_response(['ok' => true, 'charge_id' => $chargeId, 'invoice' => null]);
    }

    $invoiceId = (int)($invRow['id'] ?? 0);
    if ($invoiceId <= 0) {
        json_response(['ok' => true, 'charge_id' => $chargeId, 'invoice' => null]);
    }

    $invStmt = $pdo->prepare(
        'SELECT i.id, i.charge_id, i.patient_id, p.patient_code, p.full_name, i.status, i.total, i.created_at FROM cashier_invoices i JOIN patients p ON p.id = i.patient_id WHERE i.id = :id LIMIT 1'
    );
    $invStmt->execute(['id' => $invoiceId]);
    $inv = $invStmt->fetch();
    if (!$inv) {
        json_response(['ok' => true, 'charge_id' => $chargeId, 'invoice' => null]);
    }

    $itemsStmt = $pdo->prepare('SELECT id, medicine_id, description, qty, unit_price, subtotal FROM cashier_invoice_items WHERE invoice_id = :id ORDER BY id ASC');
    $itemsStmt->execute(['id' => $invoiceId]);
    $items = $itemsStmt->fetchAll();

    $payStmt = $pdo->prepare('SELECT COALESCE(SUM(amount - COALESCE(change_amount, 0)), 0) AS paid FROM cashier_payments WHERE invoice_id = :id');
    $payStmt->execute(['id' => $invoiceId]);
    $paidRow = $payStmt->fetch();

    $lastPayStmt = $pdo->prepare('SELECT COALESCE(change_amount, 0) AS change_amount FROM cashier_payments WHERE invoice_id = :id ORDER BY created_at DESC, id DESC LIMIT 1');
    $lastPayStmt->execute(['id' => $invoiceId]);
    $lastPay = $lastPayStmt->fetch();

    $total = (float)($inv['total'] ?? 0);
    $paid = (float)($paidRow['paid'] ?? 0);
    $balance = $total - $paid;
    if ($balance < 0) $balance = 0;

    $lastChange = (float)($lastPay['change_amount'] ?? 0);

    json_response([
        'ok' => true,
        'charge_id' => $chargeId,
        'invoice' => [
            'id' => (int)$inv['id'],
            'charge_id' => ($inv['charge_id'] === null ? null : (int)$inv['charge_id']),
            'patient_id' => (int)$inv['patient_id'],
            'patient_code' => (string)$inv['patient_code'],
            'full_name' => (string)$inv['full_name'],
            'status' => (string)$inv['status'],
            'total' => number_format($total, 2, '.', ''),
            'paid' => number_format($paid, 2, '.', ''),
            'balance' => number_format($balance, 2, '.', ''),
            'last_change' => number_format($lastChange, 2, '.', ''),
            'created_at' => (string)$inv['created_at'],
            'items' => array_map(function ($r) {
                return [
                    'id' => (int)($r['id'] ?? 0),
                    'medicine_id' => ($r['medicine_id'] === null ? null : (int)$r['medicine_id']),
                    'description' => (string)($r['description'] ?? ''),
                    'qty' => (int)($r['qty'] ?? 0),
                    'unit_price' => (string)($r['unit_price'] ?? ''),
                    'subtotal' => (string)($r['subtotal'] ?? ''),
                ];
            }, is_array($items) ? $items : []),
        ],
    ]);
} catch (Throwable $e) {
    json_response(['ok' => false, 'error' => $e->getMessage()], 500);
}
