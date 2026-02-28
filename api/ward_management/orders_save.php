<?php
declare(strict_types=1);

require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';
require_once __DIR__ . '/../_cors.php';
require_once __DIR__ . '/_tables.php';

cors_headers();
require_method('POST');

try {
    $pdo = db();
    ensure_ward_management_tables($pdo);

    $body = json_decode(file_get_contents('php://input'), true) ?? [];

    $id          = !empty($body['id']) ? (int)$body['id'] : null;
    $admissionId = (int)($body['admission_id'] ?? 0);
    $patientId   = (int)($body['patient_id']   ?? 0);
    $action      = trim($body['action'] ?? '');

    // Status update on existing order
    if ($id !== null && in_array($action, ['completed', 'cancelled', 'on_hold', 'active'], true)) {
        $upd = $pdo->prepare(
            "UPDATE ward_physician_orders SET status = :status, updated_at = NOW() WHERE id = :id"
        );
        $upd->execute(['status' => $action, 'id' => $id]);
        json_response(['ok' => true, 'updated' => $id]);
    }

    if ($admissionId <= 0 || $patientId <= 0) {
        json_response(['ok' => false, 'error' => 'admission_id and patient_id are required'], 422);
    }

    $orderText = trim($body['order_text'] ?? '');
    if ($orderText === '') {
        json_response(['ok' => false, 'error' => 'order_text is required'], 422);
    }

    $chk = $pdo->prepare('SELECT id, ward FROM admissions WHERE id = :id AND patient_id = :pid LIMIT 1');
    $chk->execute(['id' => $admissionId, 'pid' => $patientId]);
    $adm = $chk->fetch();
    if (!$adm) {
        json_response(['ok' => false, 'error' => 'Admission not found'], 404);
    }

    $ins = $pdo->prepare(
        "INSERT INTO ward_physician_orders
            (admission_id, patient_id, ward, order_type, order_text, ordered_by, ordered_at, status, noted_by, noted_at)
         VALUES
            (:admission_id, :patient_id, :ward, :order_type, :order_text, :ordered_by, :ordered_at, 'active', :noted_by, :noted_at)"
    );
    $notedBy  = trim($body['noted_by'] ?? '');
    $notedAt  = !empty($body['noted_at']) ? $body['noted_at'] : null;
    $ins->execute([
        'admission_id' => $admissionId,
        'patient_id'   => $patientId,
        'ward'         => (string)($adm['ward'] ?? ''),
        'order_type'   => $body['order_type'] ?? 'other',
        'order_text'   => $orderText,
        'ordered_by'   => trim($body['ordered_by'] ?? ''),
        'ordered_at'   => $body['ordered_at'] ?? date('Y-m-d H:i:s'),
        'noted_by'     => $notedBy ?: null,
        'noted_at'     => $notedAt,
    ]);

    json_response(['ok' => true, 'order_id' => (int)$pdo->lastInsertId()]);
} catch (Throwable $e) {
    json_response(['ok' => false, 'error' => $e->getMessage()], 500);
}
