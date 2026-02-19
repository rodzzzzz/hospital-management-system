<?php
declare(strict_types=1);

require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';
require_once __DIR__ . '/_tables.php';

require_method('GET');

try {
    $id = require_int('id');

    $pdo = db();
    ensure_lab_tables($pdo);

    $stmt = $pdo->prepare(
        "SELECT
            r.*, p.patient_code, p.full_name,
            p.dob, p.sex, p.blood_type,
            p.street_address, p.barangay, p.city, p.province, p.zip_code,
            u.full_name AS doctor_full_name,
            u.username AS doctor_username
         FROM lab_requests r
         JOIN patients p ON p.id = r.patient_id
         LEFT JOIN users u ON u.id = r.doctor_id
         WHERE r.id = :id
         LIMIT 1"
    );
    $stmt->execute(['id' => $id]);
    $request = $stmt->fetch();
    if (!$request) {
        json_response(['ok' => false, 'error' => 'Request not found'], 404);
    }

    $vitals = null;
    if (array_key_exists('vitals_json', $request) && $request['vitals_json'] !== null && $request['vitals_json'] !== '') {
        $decoded = json_decode((string)$request['vitals_json'], true);
        if (is_array($decoded)) {
            $vitals = $decoded;
        }
    }

    $itemsStmt = $pdo->prepare(
        "SELECT
            i.*, res.result_text, res.released_by, res.released_at
         FROM lab_request_items i
         LEFT JOIN lab_results res ON res.request_item_id = i.id
         WHERE i.request_id = :id
         ORDER BY i.id ASC"
    );
    $itemsStmt->execute(['id' => $id]);
    $items = $itemsStmt->fetchAll();

    json_response([
        'ok' => true,
        'request' => $request,
        'vitals' => $vitals,
        'items' => $items,
    ]);
} catch (Throwable $e) {
    json_response([
        'ok' => false,
        'error' => $e->getMessage(),
    ], 500);
}
