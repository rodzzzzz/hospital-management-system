<?php
declare(strict_types=1);

require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';
require_once __DIR__ . '/_tables.php';
require_once __DIR__ . '/../pharmacy/_tables.php';
require_once __DIR__ . '/../price_master/_tables.php';

require_method('GET');

try {
    $chargeId = require_int('charge_id');

    $pdo = db();
    ensure_cashier_tables($pdo);
    ensure_pharmacy_tables($pdo);
    ensure_price_master_tables($pdo);

    $cStmt = $pdo->prepare(
        'SELECT c.id, c.source_module, c.source_id, c.patient_id, c.status, c.created_at, p.patient_code, p.full_name FROM cashier_charges c JOIN patients p ON p.id = c.patient_id WHERE c.id = :id LIMIT 1'
    );
    $cStmt->execute(['id' => $chargeId]);
    $charge = $cStmt->fetch();
    if (!$charge) {
        json_response(['ok' => false, 'error' => 'Charge not found'], 404);
    }

    $invIdStmt = $pdo->prepare('SELECT id, total FROM cashier_invoices WHERE charge_id = :cid ORDER BY id DESC LIMIT 1');
    $invIdStmt->execute(['cid' => $chargeId]);
    $invRow = $invIdStmt->fetch();
    $invoiceId = $invRow ? (int)($invRow['id'] ?? 0) : 0;

    $items = [];
    $total = 0.0;
    $hasMissingPrice = false;

    $sourceModule = (string)($charge['source_module'] ?? '');

    if (in_array($sourceModule, ['lab_request', 'opd_consultation'], true) && $invoiceId > 0) {
        $itStmt = $pdo->prepare('SELECT id, description, qty, unit_price, subtotal FROM cashier_invoice_items WHERE invoice_id = :iid ORDER BY id ASC');
        $itStmt->execute(['iid' => $invoiceId]);
        $invItems = $itStmt->fetchAll();
        foreach ($invItems as $r) {
            $qty = (int)($r['qty'] ?? 0);
            if ($qty < 1) $qty = 1;
            $unit = (float)($r['unit_price'] ?? 0);
            $sub = (float)($r['subtotal'] ?? ($qty * $unit));
            $total += $sub;
            $items[] = [
                'id' => (int)($r['id'] ?? 0),
                'medicine_id' => null,
                'medicine_name' => (string)($r['description'] ?? ''),
                'qty' => $qty,
                'instructions' => '',
                'unit_price' => number_format($unit, 2, '.', ''),
                'subtotal' => number_format($sub, 2, '.', ''),
            ];
        }
        $total = $invRow && isset($invRow['total']) ? (float)$invRow['total'] : $total;
        $hasMissingPrice = false;
    } else if (in_array($sourceModule, ['opd_consultation', 'er_consultation'], true) && $invoiceId <= 0) {
        $feeStmt = $pdo->prepare('SELECT fee_name, price FROM opd_fees WHERE fee_code = :code LIMIT 1');
        $feeStmt->execute(['code' => 'consultation']);
        $fee = $feeStmt->fetch();
        if ($fee) {
            $feeName = trim((string)($fee['fee_name'] ?? 'OPD Consultation'));
            if ($feeName === '') $feeName = 'OPD Consultation';
            $price = (float)($fee['price'] ?? 0);
            if ($price < 0) $price = 0;

            $total = $price;
            $items[] = [
                'id' => 0,
                'medicine_id' => null,
                'medicine_name' => $feeName,
                'qty' => 1,
                'instructions' => '',
                'unit_price' => number_format($price, 2, '.', ''),
                'subtotal' => number_format($price, 2, '.', ''),
            ];
            $hasMissingPrice = false;
        } else {
            $hasMissingPrice = true;
        }
    } else {
        $byNameStmt = $pdo->prepare(
            'SELECT id, price FROM pharmacy_medicines WHERE LOWER(name) = LOWER(:name) ORDER BY id DESC LIMIT 1'
        );

        $itemsStmt = $pdo->prepare(
            'SELECT i.id, i.medicine_id, i.medicine_name, i.qty, i.instructions, m.price FROM cashier_charge_items i LEFT JOIN pharmacy_medicines m ON m.id = i.medicine_id WHERE i.charge_id = :id ORDER BY i.id ASC'
        );
        $itemsStmt->execute(['id' => $chargeId]);
        $rows = $itemsStmt->fetchAll();

        foreach ($rows as $r) {
            $qty = (int)($r['qty'] ?? 0);
            if ($qty < 1) $qty = 1;

            $resolvedMedicineId = ($r['medicine_id'] === null ? null : (int)$r['medicine_id']);
            $priceRaw = $r['price'] ?? null;
            if (($priceRaw === null || $priceRaw === '') && isset($r['medicine_name'])) {
                $name = trim((string)($r['medicine_name'] ?? ''));
                if ($name !== '') {
                    $byNameStmt->execute(['name' => $name]);
                    $p = $byNameStmt->fetch();
                    if ($p) {
                        if ($resolvedMedicineId === null && isset($p['id'])) {
                            $resolvedMedicineId = (int)$p['id'];
                        }
                        $priceRaw = $p['price'] ?? null;
                    }
                }
            }
            $price = ($priceRaw === null || $priceRaw === '') ? null : (float)$priceRaw;
            if ($price === null) {
                $hasMissingPrice = true;
            }
            $subtotal = ($price !== null) ? ($qty * $price) : 0.0;
            $total += $subtotal;

            $items[] = [
                'id' => (int)($r['id'] ?? 0),
                'medicine_id' => $resolvedMedicineId,
                'medicine_name' => (string)($r['medicine_name'] ?? ''),
                'qty' => $qty,
                'instructions' => (string)($r['instructions'] ?? ''),
                'unit_price' => ($price !== null) ? number_format($price, 2, '.', '') : null,
                'subtotal' => number_format($subtotal, 2, '.', ''),
            ];
        }
    }

    json_response([
        'ok' => true,
        'charge' => [
            'id' => (int)($charge['id'] ?? 0),
            'source_module' => (string)($charge['source_module'] ?? ''),
            'source_id' => (int)($charge['source_id'] ?? 0),
            'patient_id' => (int)($charge['patient_id'] ?? 0),
            'patient_code' => (string)($charge['patient_code'] ?? ''),
            'full_name' => (string)($charge['full_name'] ?? ''),
            'status' => (string)($charge['status'] ?? ''),
            'created_at' => (string)($charge['created_at'] ?? ''),
            'invoice_id' => ($invoiceId > 0 ? $invoiceId : null),
            'items' => $items,
            'total' => number_format($total, 2, '.', ''),
            'has_missing_price' => $hasMissingPrice,
        ],
    ]);
} catch (Throwable $e) {
    json_response(['ok' => false, 'error' => $e->getMessage()], 500);
}
