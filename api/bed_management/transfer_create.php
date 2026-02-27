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

    $body        = json_decode(file_get_contents('php://input'), true) ?? [];
    $admissionId = (int)($body['admission_id'] ?? 0);
    $patientId   = (int)($body['patient_id'] ?? 0);
    $toBedId     = (int)($body['to_bed_id'] ?? 0);
    $reason      = trim($body['reason'] ?? '');
    $transferredBy = trim($body['transferred_by'] ?? '');
    $transferredAt = $body['transferred_at'] ?? date('Y-m-d H:i:s');

    if ($admissionId <= 0 || $patientId <= 0 || $toBedId <= 0) {
        json_response(['ok' => false, 'error' => 'admission_id, patient_id and to_bed_id are required'], 422);
    }

    // Load admission
    $adm = $pdo->prepare('SELECT id, bed_id, ward, room_no FROM admissions WHERE id = :id AND patient_id = :pid AND status = "admitted" LIMIT 1');
    $adm->execute(['id' => $admissionId, 'pid' => $patientId]);
    $admission = $adm->fetch();
    if (!$admission) {
        json_response(['ok' => false, 'error' => 'Active admission not found'], 404);
    }

    // Load destination bed
    $bedStmt = $pdo->prepare('SELECT id, bed_code, ward, status, room_id FROM ward_beds WHERE id = :id LIMIT 1');
    $bedStmt->execute(['id' => $toBedId]);
    $toBed = $bedStmt->fetch();
    if (!$toBed) {
        json_response(['ok' => false, 'error' => 'Destination bed not found'], 404);
    }
    if ($toBed['status'] !== 'available') {
        json_response(['ok' => false, 'error' => 'Destination bed is not available'], 409);
    }

    // Load destination room
    $roomStmt = $pdo->prepare('SELECT room_no FROM ward_rooms WHERE id = :id LIMIT 1');
    $roomStmt->execute(['id' => $toBed['room_id']]);
    $toRoom = $roomStmt->fetch();

    $pdo->beginTransaction();

    // Release old bed if any
    $fromBedId = !empty($admission['bed_id']) ? (int)$admission['bed_id'] : null;
    $fromBedCode = null;
    $fromRoomNo  = null;
    $fromWard    = $admission['ward'];

    if ($fromBedId !== null) {
        $oldBed = $pdo->prepare('SELECT bed_code, room_id FROM ward_beds WHERE id = :id LIMIT 1');
        $oldBed->execute(['id' => $fromBedId]);
        $oldBedRow = $oldBed->fetch();
        $fromBedCode = $oldBedRow['bed_code'] ?? null;

        if (!empty($oldBedRow['room_id'])) {
            $oldRoom = $pdo->prepare('SELECT room_no FROM ward_rooms WHERE id = :id LIMIT 1');
            $oldRoom->execute(['id' => $oldBedRow['room_id']]);
            $fromRoomNo = ($oldRoom->fetch())['room_no'] ?? null;
        }

        $pdo->prepare(
            "UPDATE ward_beds SET status = 'cleaning', current_admission_id = NULL, current_patient_id = NULL WHERE id = :id"
        )->execute(['id' => $fromBedId]);
    }

    // Occupy new bed
    $pdo->prepare(
        "UPDATE ward_beds SET status = 'occupied', current_admission_id = :adm_id, current_patient_id = :pat_id WHERE id = :id"
    )->execute(['adm_id' => $admissionId, 'pat_id' => $patientId, 'id' => $toBedId]);

    // Update admission with new bed/room/ward
    $pdo->prepare(
        "UPDATE admissions SET bed_id = :bed_id, room_no = :room_no, ward = :ward WHERE id = :id"
    )->execute([
        'bed_id'  => $toBedId,
        'room_no' => $toRoom['room_no'] ?? null,
        'ward'    => $toBed['ward'],
        'id'      => $admissionId,
    ]);

    // Record transfer
    $ins = $pdo->prepare(
        "INSERT INTO room_transfers
            (admission_id, patient_id, from_bed_id, from_bed_code, from_room_no, from_ward,
             to_bed_id, to_bed_code, to_room_no, to_ward, reason, transferred_by, transferred_at)
         VALUES
            (:admission_id, :patient_id, :from_bed_id, :from_bed_code, :from_room_no, :from_ward,
             :to_bed_id, :to_bed_code, :to_room_no, :to_ward, :reason, :transferred_by, :transferred_at)"
    );
    $ins->execute([
        'admission_id'   => $admissionId,
        'patient_id'     => $patientId,
        'from_bed_id'    => $fromBedId,
        'from_bed_code'  => $fromBedCode,
        'from_room_no'   => $fromRoomNo,
        'from_ward'      => $fromWard,
        'to_bed_id'      => $toBedId,
        'to_bed_code'    => (string)$toBed['bed_code'],
        'to_room_no'     => $toRoom['room_no'] ?? null,
        'to_ward'        => (string)$toBed['ward'],
        'reason'         => $reason,
        'transferred_by' => $transferredBy,
        'transferred_at' => $transferredAt,
    ]);

    $transferId = (int)$pdo->lastInsertId();
    $transferNo = 'TRF-' . date('Ymd') . '-' . str_pad((string)$transferId, 6, '0', STR_PAD_LEFT);
    $pdo->prepare('UPDATE room_transfers SET transfer_no = :no WHERE id = :id')
        ->execute(['no' => $transferNo, 'id' => $transferId]);

    $pdo->commit();

    json_response([
        'ok'          => true,
        'transfer_id' => $transferId,
        'transfer_no' => $transferNo,
    ]);
} catch (Throwable $e) {
    if (isset($pdo) && $pdo->inTransaction()) $pdo->rollBack();
    json_response(['ok' => false, 'error' => $e->getMessage()], 500);
}
