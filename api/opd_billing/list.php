<?php
declare(strict_types=1);

require_once __DIR__ . '/../_cors.php';
require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';
require_once __DIR__ . '/_tables.php';

cors_headers();
require_method('GET');

try {
    $appointmentIdRaw = $_GET['appointment_id'] ?? null;
    if ($appointmentIdRaw === null || $appointmentIdRaw === '' || !ctype_digit((string)$appointmentIdRaw)) {
        json_response(['ok' => false, 'error' => 'Missing or invalid appointment_id'], 400);
    }
    $appointmentId = (int)$appointmentIdRaw;

    $pdo = db();
    ensure_opd_billing_tables($pdo);

    $stmt = $pdo->prepare(
        'SELECT id, appointment_id, patient_id, created_by_user_id, item_type, description, qty, unit_price, (qty * unit_price) AS subtotal, created_at
         FROM opd_billing_items
         WHERE appointment_id = :appointment_id
         ORDER BY created_at DESC, id DESC'
    );
    $stmt->execute(['appointment_id' => $appointmentId]);
    $rows = $stmt->fetchAll();

    $total = 0.0;
    foreach (is_array($rows) ? $rows : [] as $r) {
        $total += (float)($r['subtotal'] ?? 0);
    }

    json_response([
        'ok' => true,
        'items' => $rows,
        'total' => number_format($total, 2, '.', ''),
    ]);
} catch (Throwable $e) {
    json_response(['ok' => false, 'error' => $e->getMessage()], 500);
}
