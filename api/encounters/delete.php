<?php
declare(strict_types=1);

require_once __DIR__ . '/../_cors.php';
require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';
require_once __DIR__ . '/_tables.php';
require_once __DIR__ . '/../lab/_tables.php';
require_once __DIR__ . '/../pharmacy/_tables.php';
require_once __DIR__ . '/../cashier/_tables.php';

cors_headers();
require_method('POST');

try {
    $raw = file_get_contents('php://input');
    $data = json_decode($raw ?: 'null', true);
    if (!is_array($data)) {
        json_response(['ok' => false, 'error' => 'Invalid JSON body'], 400);
    }

    $encIdRaw = $data['encounter_id'] ?? null;
    if (!is_int($encIdRaw) && !(is_string($encIdRaw) && ctype_digit($encIdRaw))) {
        json_response(['ok' => false, 'error' => 'Missing or invalid encounter_id'], 400);
    }
    $encounterId = (int)$encIdRaw;

    $pdo = db();
    ensure_encounter_tables($pdo);
    ensure_lab_tables($pdo);
    ensure_pharmacy_tables($pdo);
    ensure_cashier_tables($pdo);

    $encStmt = $pdo->prepare('SELECT id FROM encounters WHERE id = :id LIMIT 1');
    $encStmt->execute(['id' => $encounterId]);
    if (!$encStmt->fetch()) {
        json_response(['ok' => false, 'error' => 'Encounter not found'], 404);
    }

    $counts = [];

    $labStmt = $pdo->prepare('SELECT COUNT(*) AS c FROM lab_requests WHERE encounter_id = :id');
    $labStmt->execute(['id' => $encounterId]);
    $counts['lab_requests'] = (int)($labStmt->fetch()['c'] ?? 0);

    $resitStmt = $pdo->prepare('SELECT COUNT(*) AS c FROM pharmacy_resits WHERE encounter_id = :id');
    $resitStmt->execute(['id' => $encounterId]);
    $counts['pharmacy_resits'] = (int)($resitStmt->fetch()['c'] ?? 0);

    $chargeStmt = $pdo->prepare('SELECT COUNT(*) AS c FROM cashier_charges WHERE encounter_id = :id');
    $chargeStmt->execute(['id' => $encounterId]);
    $counts['cashier_charges'] = (int)($chargeStmt->fetch()['c'] ?? 0);

    $invStmt = $pdo->prepare('SELECT COUNT(*) AS c FROM cashier_invoices WHERE encounter_id = :id');
    $invStmt->execute(['id' => $encounterId]);
    $counts['cashier_invoices'] = (int)($invStmt->fetch()['c'] ?? 0);

    $totalLinked = array_sum($counts);
    if ($totalLinked > 0) {
        json_response([
            'ok' => false,
            'error' => 'Cannot delete encounter: linked records exist.',
            'counts' => $counts,
        ], 409);
    }

    $pdo->beginTransaction();
    $del = $pdo->prepare('DELETE FROM encounters WHERE id = :id');
    $del->execute(['id' => $encounterId]);
    $pdo->commit();

    json_response(['ok' => true]);
} catch (Throwable $e) {
    if (isset($pdo) && $pdo instanceof PDO && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    json_response(['ok' => false, 'error' => $e->getMessage()], 500);
}
