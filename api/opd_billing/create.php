<?php
declare(strict_types=1);

require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';
require_once __DIR__ . '/_tables.php';
require_once __DIR__ . '/../auth/_session.php';

require_method('POST');

try {
    $raw = file_get_contents('php://input');
    $data = json_decode($raw ?: 'null', true);
    if (!is_array($data)) {
        json_response(['ok' => false, 'error' => 'Invalid JSON body'], 400);
    }

    $appointmentIdRaw = $data['appointment_id'] ?? null;
    if (!is_int($appointmentIdRaw) && !(is_string($appointmentIdRaw) && ctype_digit((string)$appointmentIdRaw))) {
        json_response(['ok' => false, 'error' => 'Missing or invalid appointment_id'], 400);
    }
    $appointmentId = (int)$appointmentIdRaw;

    $itemType = strtolower(trim((string)($data['item_type'] ?? 'misc')));
    if ($itemType === '') $itemType = 'misc';

    $description = trim((string)($data['description'] ?? ''));
    if ($description === '') {
        json_response(['ok' => false, 'error' => 'Missing description'], 400);
    }

    $qtyRaw = $data['qty'] ?? 1;
    if (!is_int($qtyRaw) && !(is_string($qtyRaw) && ctype_digit((string)$qtyRaw))) {
        json_response(['ok' => false, 'error' => 'Invalid qty'], 400);
    }
    $qty = (int)$qtyRaw;
    if ($qty < 1) $qty = 1;

    $unitPriceRaw = $data['unit_price'] ?? 0;
    if (!is_numeric($unitPriceRaw)) {
        json_response(['ok' => false, 'error' => 'Invalid unit_price'], 400);
    }
    $unitPrice = (float)$unitPriceRaw;
    if ($unitPrice < 0) $unitPrice = 0;

    $pdo = db();
    ensure_opd_billing_tables($pdo);

    $authUser = auth_current_user($pdo);
    if (!$authUser) {
        json_response(['ok' => false, 'error' => 'Not authenticated'], 401);
    }

    $isAdmin = auth_user_has_module($authUser, 'ADMIN');
    if (!$isAdmin && !auth_user_has_module($authUser, 'OPD') && !auth_user_has_module($authUser, 'DOCTOR')) {
        json_response(['ok' => false, 'error' => 'Forbidden'], 403);
    }

    $apptStmt = $pdo->prepare('SELECT id, patient_id FROM opd_appointments WHERE id = :id LIMIT 1');
    $apptStmt->execute(['id' => $appointmentId]);
    $appt = $apptStmt->fetch();
    if (!$appt) {
        json_response(['ok' => false, 'error' => 'Appointment not found'], 404);
    }

    $patientId = (int)($appt['patient_id'] ?? 0);
    if ($patientId <= 0) {
        json_response(['ok' => false, 'error' => 'Invalid patient'], 400);
    }

    $createdByUserId = (int)($authUser['id'] ?? 0);
    if ($createdByUserId <= 0) {
        $createdByUserId = null;
    }

    $ins = $pdo->prepare(
        'INSERT INTO opd_billing_items (appointment_id, patient_id, created_by_user_id, item_type, description, qty, unit_price) VALUES (:appointment_id, :patient_id, :created_by_user_id, :item_type, :description, :qty, :unit_price)'
    );
    $ins->execute([
        'appointment_id' => $appointmentId,
        'patient_id' => $patientId,
        'created_by_user_id' => $createdByUserId,
        'item_type' => $itemType,
        'description' => $description,
        'qty' => $qty,
        'unit_price' => number_format($unitPrice, 2, '.', ''),
    ]);

    $id = (int)$pdo->lastInsertId();

    $stmt = $pdo->prepare(
        'SELECT id, appointment_id, patient_id, created_by_user_id, item_type, description, qty, unit_price, (qty * unit_price) AS subtotal, created_at
         FROM opd_billing_items
         WHERE id = :id
         LIMIT 1'
    );
    $stmt->execute(['id' => $id]);
    $row = $stmt->fetch();

    json_response(['ok' => true, 'item' => $row]);
} catch (Throwable $e) {
    json_response(['ok' => false, 'error' => $e->getMessage()], 500);
}
