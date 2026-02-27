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
    ensure_bed_management_tables($pdo);

    $body      = json_decode(file_get_contents('php://input'), true) ?? [];
    $ward      = trim($body['ward'] ?? '');
    $roomNo    = trim($body['room_no'] ?? '');
    $roomType  = $body['room_type'] ?? 'ward';
    $floor     = trim($body['floor'] ?? '');
    $bedCount  = max(1, (int)($body['bed_count'] ?? 1));
    $notes     = trim($body['notes'] ?? '');

    if ($ward === '') {
        json_response(['ok' => false, 'error' => 'ward is required'], 422);
    }
    if ($roomNo === '') {
        json_response(['ok' => false, 'error' => 'room_no is required'], 422);
    }

    // Check duplicate
    $chk = $pdo->prepare('SELECT id FROM ward_rooms WHERE ward = :ward AND room_no = :room_no LIMIT 1');
    $chk->execute(['ward' => $ward, 'room_no' => $roomNo]);
    if ($chk->fetch()) {
        json_response(['ok' => false, 'error' => "Room $roomNo already exists in $ward ward"], 409);
    }

    $pdo->beginTransaction();

    $ins = $pdo->prepare(
        "INSERT INTO ward_rooms (ward, room_no, room_type, floor, notes, is_active)
         VALUES (:ward, :room_no, :room_type, :floor, :notes, 1)"
    );
    $ins->execute([
        'ward'      => $ward,
        'room_no'   => $roomNo,
        'room_type' => $roomType,
        'floor'     => $floor ?: null,
        'notes'     => $notes ?: null,
    ]);
    $roomId = (int)$pdo->lastInsertId();

    // Create beds for this room
    $bedIns = $pdo->prepare(
        "INSERT INTO ward_beds (room_id, ward, bed_code, status)
         VALUES (:room_id, :ward, :bed_code, 'available')"
    );

    $wardPrefix = strtoupper(substr($ward, 0, 4));
    $createdBeds = [];
    for ($i = 1; $i <= $bedCount; $i++) {
        $bedCode = $wardPrefix . '-' . $roomNo . '-' . str_pad((string)$i, 2, '0', STR_PAD_LEFT);
        $bedIns->execute([
            'room_id'  => $roomId,
            'ward'     => $ward,
            'bed_code' => $bedCode,
        ]);
        $createdBeds[] = ['id' => (int)$pdo->lastInsertId(), 'bed_code' => $bedCode];
    }

    $pdo->commit();

    json_response([
        'ok'      => true,
        'room_id' => $roomId,
        'room_no' => $roomNo,
        'beds'    => $createdBeds,
    ]);
} catch (Throwable $e) {
    if (isset($pdo) && $pdo->inTransaction()) $pdo->rollBack();
    json_response(['ok' => false, 'error' => $e->getMessage()], 500);
}
