<?php
declare(strict_types=1);

require_once __DIR__ . '/../_cors.php';
require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';
require_once __DIR__ . '/../auth/_session.php';
require_once __DIR__ . '/_tables.php';
require_once __DIR__ . '/../pharmacy/_tables.php';
require_once __DIR__ . '/../price_master/_tables.php';

cors_headers();
require_method('POST');

try {
    $raw = file_get_contents('php://input');
    $data = json_decode($raw ?: 'null', true);
    if (!is_array($data)) {
        json_response(['ok' => false, 'error' => 'Invalid JSON body'], 400);
    }

    $chargeId = $data['charge_id'] ?? null;
    if (!is_int($chargeId) && !(is_string($chargeId) && ctype_digit($chargeId))) {
        json_response(['ok' => false, 'error' => 'Missing or invalid charge_id'], 400);
    }
    $chargeId = (int)$chargeId;

    $amountRaw = $data['amount'] ?? null;
    if ($amountRaw === null || $amountRaw === '' || !is_numeric((string)$amountRaw)) {
        json_response(['ok' => false, 'error' => 'Missing or invalid amount'], 400);
    }
    $amount = (float)$amountRaw;
    if ($amount <= 0) {
        json_response(['ok' => false, 'error' => 'Amount must be greater than 0'], 400);
    }

    $method = trim((string)($data['method'] ?? 'cash'));
    if ($method === '') $method = 'cash';

    $receivedBy = trim((string)($data['received_by'] ?? ''));

    $pdo = db();
    ensure_cashier_tables($pdo);
    ensure_pharmacy_tables($pdo);
    ensure_price_master_tables($pdo);

    $actor = auth_current_user_optional_token($pdo);
    if ($receivedBy === '' && $actor) {
        $receivedBy = trim((string)($actor['username'] ?? ''));
        if ($receivedBy === '') {
            $receivedBy = trim((string)($actor['full_name'] ?? ''));
        }
    }

    $pdo->beginTransaction();

    $cStmt = $pdo->prepare('SELECT id, source_module, patient_id, encounter_id, status FROM cashier_charges WHERE id = :id LIMIT 1 FOR UPDATE');
    $cStmt->execute(['id' => $chargeId]);
    $charge = $cStmt->fetch();
    if (!$charge) {
        $pdo->rollBack();
        json_response(['ok' => false, 'error' => 'Charge not found'], 404);
    }

    if ((string)($charge['status'] ?? '') !== 'pending_invoice') {
        $pdo->rollBack();
        json_response(['ok' => false, 'error' => 'Charge is not pending charge'], 400);
    }

    $invStmt = $pdo->prepare('SELECT id, status, patient_id, encounter_id, total FROM cashier_invoices WHERE charge_id = :cid ORDER BY id DESC LIMIT 1');
    $invStmt->execute(['cid' => $chargeId]);
    $inv = $invStmt->fetch();

    $invoiceId = $inv ? (int)($inv['id'] ?? 0) : 0;

    $invStatus = strtolower(trim((string)($inv['status'] ?? '')));
    if ($invoiceId > 0 && $invStatus === 'paid') {
        $pdo->prepare("UPDATE cashier_charges SET status = 'paid' WHERE id = :id")
            ->execute(['id' => $chargeId]);
        $pdo->commit();
        json_response([
            'ok' => true,
            'charge_id' => $chargeId,
            'invoice_id' => $invoiceId,
            'status' => 'paid',
            'applied' => number_format(0, 2, '.', ''),
            'change' => number_format(0, 2, '.', ''),
        ]);
    }

    if ($invoiceId <= 0) {
        $sourceModule = strtolower(trim((string)($charge['source_module'] ?? '')));
        $isConsult = in_array($sourceModule, ['opd_consultation', 'er_consultation'], true);

        $billingEncounterId = null;
        if (isset($charge['encounter_id']) && $charge['encounter_id'] !== '' && $charge['encounter_id'] !== null) {
            $billingEncounterId = (int)$charge['encounter_id'];
            if ($billingEncounterId <= 0) $billingEncounterId = null;
        }
        if ($billingEncounterId === null) {
            $billingEncounterId = create_encounter($pdo, (int)($charge['patient_id'] ?? 0), 'OPD');
            $pdo->prepare('UPDATE cashier_charges SET encounter_id = :encounter_id WHERE id = :id')
                ->execute(['encounter_id' => $billingEncounterId, 'id' => $chargeId]);
        }

        $invoiceItems = [];
        $total = 0.0;

        if ($isConsult) {
            $feeStmt = $pdo->prepare('SELECT fee_name, price FROM opd_fees WHERE fee_code = :code LIMIT 1');
            $feeStmt->execute(['code' => 'consultation']);
            $fee = $feeStmt->fetch();
            if (!$fee) {
                $pdo->rollBack();
                json_response(['ok' => false, 'error' => 'Missing OPD fee: consultation. Please set it in Price Master → OPD Fees.'], 400);
            }

            $feeName = trim((string)($fee['fee_name'] ?? 'OPD Consultation'));
            if ($feeName === '') $feeName = 'OPD Consultation';

            $price = (float)($fee['price'] ?? 0);
            if ($price < 0) $price = 0;

            $total = $price;
            $invoiceItems[] = [
                'medicine_id' => null,
                'description' => $feeName,
                'qty' => 1,
                'unit_price' => $price,
                'subtotal' => $price,
            ];
        } else {
            $itemsStmt = $pdo->prepare(
                'SELECT i.medicine_id, i.medicine_name, i.qty, m.price FROM cashier_charge_items i LEFT JOIN pharmacy_medicines m ON m.id = i.medicine_id WHERE i.charge_id = :id ORDER BY i.id ASC'
            );
            $itemsStmt->execute(['id' => $chargeId]);
            $items = $itemsStmt->fetchAll();
            if (!is_array($items) || count($items) === 0) {
                $pdo->rollBack();
                json_response(['ok' => false, 'error' => 'Charge has no items'], 400);
            }

            $resolveByNameStmt = $pdo->prepare(
                'SELECT id, price FROM pharmacy_medicines WHERE LOWER(name) = LOWER(:name) ORDER BY id DESC LIMIT 1'
            );

            foreach ($items as $it) {
                $mid = $it['medicine_id'] ?? null;
                $priceRaw = $it['price'] ?? null;
                if (($mid === null || $mid === '' || !ctype_digit((string)$mid)) || $priceRaw === null || $priceRaw === '') {
                    $name = trim((string)($it['medicine_name'] ?? ''));
                    if ($name !== '') {
                        $resolveByNameStmt->execute(['name' => $name]);
                        $m = $resolveByNameStmt->fetch();
                        if ($m) {
                            if ($mid === null || $mid === '' || !ctype_digit((string)$mid)) {
                                $mid = $m['id'] ?? null;
                            }
                            if ($priceRaw === null || $priceRaw === '') {
                                $priceRaw = $m['price'] ?? null;
                            }
                        }
                    }
                }

                if ($mid === null || $mid === '' || !ctype_digit((string)$mid)) {
                    $pdo->rollBack();
                    json_response(['ok' => false, 'error' => 'Charge item is missing medicine_id. Please re-create the resit using autocomplete selection.'], 400);
                }

                if ($priceRaw === null || $priceRaw === '') {
                    $pdo->rollBack();
                    json_response(['ok' => false, 'error' => 'Medicine price missing. Please set medicine price in Pharmacy → Medicines.'], 400);
                }

                $qty = (int)($it['qty'] ?? 0);
                if ($qty < 1) $qty = 1;

                $price = (float)$priceRaw;
                $subtotal = $qty * $price;
                $total += $subtotal;

                $invoiceItems[] = [
                    'medicine_id' => (int)$mid,
                    'description' => (string)($it['medicine_name'] ?? ''),
                    'qty' => $qty,
                    'unit_price' => $price,
                    'subtotal' => $subtotal,
                ];
            }
        }

        $insInvoice = $pdo->prepare(
            'INSERT INTO cashier_invoices (charge_id, patient_id, encounter_id, status, total) VALUES (:charge_id, :patient_id, :encounter_id, :status, :total)'
        );
        $insInvoice->execute([
            'charge_id' => $chargeId,
            'patient_id' => (int)($charge['patient_id'] ?? 0),
            'encounter_id' => $billingEncounterId,
            'status' => 'unpaid',
            'total' => number_format($total, 2, '.', ''),
        ]);
        $invoiceId = (int)$pdo->lastInsertId();

        $insItem = $pdo->prepare(
            'INSERT INTO cashier_invoice_items (invoice_id, medicine_id, description, qty, unit_price, subtotal) VALUES (:invoice_id, :medicine_id, :description, :qty, :unit_price, :subtotal)'
        );
        foreach ($invoiceItems as $it) {
            $insItem->execute([
                'invoice_id' => $invoiceId,
                'medicine_id' => $it['medicine_id'],
                'description' => $it['description'],
                'qty' => $it['qty'],
                'unit_price' => number_format((float)$it['unit_price'], 2, '.', ''),
                'subtotal' => number_format((float)$it['subtotal'], 2, '.', ''),
            ]);
        }

        $inv = [
            'id' => $invoiceId,
            'patient_id' => (int)($charge['patient_id'] ?? 0),
            'encounter_id' => $billingEncounterId,
            'total' => number_format($total, 2, '.', ''),
        ];
    }

    if (!is_array($inv) || (int)($inv['id'] ?? 0) !== $invoiceId) {
        $invStmt2 = $pdo->prepare('SELECT id, patient_id, encounter_id, total FROM cashier_invoices WHERE id = :id LIMIT 1');
        $invStmt2->execute(['id' => $invoiceId]);
        $inv = $invStmt2->fetch();
    }

    if (!$inv) {
        $pdo->rollBack();
        json_response(['ok' => false, 'error' => 'Invoice not found'], 404);
    }

    $invEncounterId = null;
    if (isset($inv['encounter_id']) && $inv['encounter_id'] !== '' && $inv['encounter_id'] !== null) {
        $invEncounterId = (int)$inv['encounter_id'];
        if ($invEncounterId <= 0) $invEncounterId = null;
    }
    if ($invEncounterId === null) {
        $invEncounterId = create_encounter($pdo, (int)($inv['patient_id'] ?? 0), 'OPD');
        $pdo->prepare('UPDATE cashier_invoices SET encounter_id = :encounter_id WHERE id = :id')
            ->execute(['encounter_id' => $invEncounterId, 'id' => $invoiceId]);
    }

    $sumStmt = $pdo->prepare('SELECT COALESCE(SUM(amount - COALESCE(change_amount, 0)), 0) AS paid FROM cashier_payments WHERE invoice_id = :id');
    $sumStmt->execute(['id' => $invoiceId]);
    $paidRow = $sumStmt->fetch();

    $total = (float)($inv['total'] ?? 0);
    $paidBefore = (float)($paidRow['paid'] ?? 0);
    $balanceBefore = $total - $paidBefore;
    if ($balanceBefore < 0) $balanceBefore = 0;
    if ($balanceBefore <= 0) {
        $pdo->prepare("UPDATE cashier_charges SET status = 'paid' WHERE id = :id")
            ->execute(['id' => $chargeId]);
        $pdo->commit();
        json_response([
            'ok' => true,
            'charge_id' => $chargeId,
            'invoice_id' => $invoiceId,
            'status' => 'paid',
            'applied' => number_format(0, 2, '.', ''),
            'change' => number_format(0, 2, '.', ''),
        ]);
    }

    $applied = $amount;
    if ($applied > $balanceBefore) {
        $applied = $balanceBefore;
    }
    $change = $amount - $applied;
    if ($change < 0) $change = 0;

    $pdo->prepare('INSERT INTO cashier_payments (invoice_id, amount, change_amount, method, received_by) VALUES (:invoice_id, :amount, :change_amount, :method, :received_by)')
        ->execute([
            'invoice_id' => $invoiceId,
            'amount' => number_format($amount, 2, '.', ''),
            'change_amount' => number_format($change, 2, '.', ''),
            'method' => $method,
            'received_by' => ($receivedBy !== '' ? $receivedBy : null),
        ]);

    $paid = $paidBefore + $applied;

    $newStatus = 'unpaid';
    if ($paid >= $total && $total > 0) {
        $newStatus = 'paid';
    } elseif ($paid > 0) {
        $newStatus = 'partial';
    }

    $pdo->prepare('UPDATE cashier_invoices SET status = :status WHERE id = :id')
        ->execute(['status' => $newStatus, 'id' => $invoiceId]);

    if ($newStatus === 'paid') {
        $pdo->prepare("UPDATE cashier_charges SET status = 'paid' WHERE id = :id")
            ->execute(['id' => $chargeId]);
    }

    $pdo->commit();

    json_response([
        'ok' => true,
        'charge_id' => $chargeId,
        'invoice_id' => $invoiceId,
        'status' => $newStatus,
        'applied' => number_format($applied, 2, '.', ''),
        'change' => number_format($change, 2, '.', ''),
    ]);
} catch (Throwable $e) {
    if (isset($pdo) && $pdo instanceof PDO && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    json_response(['ok' => false, 'error' => $e->getMessage()], 500);
}
