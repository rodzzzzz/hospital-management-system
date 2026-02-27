<?php
declare(strict_types=1);

require_once __DIR__ . '/../_cors.php';
require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';
require_once __DIR__ . '/../auth/_session.php';
require_once __DIR__ . '/_tables.php';

cors_headers();
require_method('POST');

try {
    $raw = file_get_contents('php://input');
    $data = json_decode($raw ?: 'null', true);
    if (!is_array($data)) {
        json_response(['ok' => false, 'error' => 'Invalid JSON body'], 400);
    }

    $invoiceId = $data['invoice_id'] ?? null;
    if (!is_int($invoiceId) && !(is_string($invoiceId) && ctype_digit($invoiceId))) {
        json_response(['ok' => false, 'error' => 'Missing or invalid invoice_id'], 400);
    }
    $invoiceId = (int)$invoiceId;

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

    $actor = auth_current_user_optional_token($pdo);
    if ($receivedBy === '' && $actor) {
        $receivedBy = trim((string)($actor['username'] ?? ''));
        if ($receivedBy === '') {
            $receivedBy = trim((string)($actor['full_name'] ?? ''));
        }
    }

    $pdo->beginTransaction();

    $invStmt = $pdo->prepare('SELECT id, charge_id, patient_id, encounter_id, total FROM cashier_invoices WHERE id = :id LIMIT 1');
    $invStmt->execute(['id' => $invoiceId]);
    $inv = $invStmt->fetch();
    if (!$inv) {
        $pdo->rollBack();
        json_response(['ok' => false, 'error' => 'Invoice not found'], 404);
    }

    $chargeId = null;
    if (isset($inv['charge_id']) && $inv['charge_id'] !== null && $inv['charge_id'] !== '') {
        $chargeId = (int)$inv['charge_id'];
        if ($chargeId <= 0) $chargeId = null;
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
        if ($chargeId !== null) {
            $pdo->prepare("UPDATE cashier_charges SET status = 'paid' WHERE id = :id")
                ->execute(['id' => $chargeId]);
        }
        $pdo->commit();
        json_response([
            'ok' => true,
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

    if ($newStatus === 'paid' && $chargeId !== null) {
        $pdo->prepare("UPDATE cashier_charges SET status = 'paid' WHERE id = :id")
            ->execute(['id' => $chargeId]);
    }

    $pdo->commit();

    json_response([
        'ok' => true,
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
