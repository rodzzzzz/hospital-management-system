<?php
declare(strict_types=1);

require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';
require_once __DIR__ . '/_tables.php';

require_method('GET');

try {
    $invoiceId = require_int('invoice_id');

    $pdo = db();
    ensure_cashier_tables($pdo);

    $invStmt = $pdo->prepare(
        'SELECT i.id, i.charge_id, i.patient_id, p.patient_code, p.full_name, i.status, i.total, i.created_at FROM cashier_invoices i JOIN patients p ON p.id = i.patient_id WHERE i.id = :id LIMIT 1'
    );
    $invStmt->execute(['id' => $invoiceId]);
    $inv = $invStmt->fetch();
    if (!$inv) {
        json_response(['ok' => false, 'error' => 'Invoice not found'], 404);
    }

    $itemsStmt = $pdo->prepare('SELECT id, medicine_id, description, qty, unit_price, subtotal FROM cashier_invoice_items WHERE invoice_id = :id ORDER BY id ASC');
    $itemsStmt->execute(['id' => $invoiceId]);
    $items = $itemsStmt->fetchAll();

    $payStmt = $pdo->prepare('SELECT COALESCE(SUM(amount - COALESCE(change_amount, 0)), 0) AS paid FROM cashier_payments WHERE invoice_id = :id');
    $payStmt->execute(['id' => $invoiceId]);
    $paidRow = $payStmt->fetch();

    $lastPayStmt = $pdo->prepare('SELECT COALESCE(change_amount, 0) AS change_amount, COALESCE(received_by, \'\') AS received_by FROM cashier_payments WHERE invoice_id = :id ORDER BY created_at DESC, id DESC LIMIT 1');
    $lastPayStmt->execute(['id' => $invoiceId]);
    $lastPay = $lastPayStmt->fetch();

    $total = (float)($inv['total'] ?? 0);
    $paid = (float)($paidRow['paid'] ?? 0);
    $balance = $total - $paid;
    if ($balance < 0) $balance = 0;

    $lastChange = (float)($lastPay['change_amount'] ?? 0);
    $lastReceivedBy = trim((string)($lastPay['received_by'] ?? ''));

    json_response([
        'ok' => true,
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
            'last_received_by' => $lastReceivedBy,
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
