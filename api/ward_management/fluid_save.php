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

    $admissionId = (int)($body['admission_id'] ?? 0);
    $patientId   = (int)($body['patient_id']   ?? 0);
    $shift       = $body['shift']      ?? 'AM';
    $entryType   = $body['entry_type'] ?? '';
    $volumeMl    = (int)($body['volume_ml'] ?? 0);
    $recordedBy  = trim($body['recorded_by'] ?? '');
    $recordedAt  = $body['recorded_at'] ?? date('Y-m-d H:i:s');

    if ($admissionId <= 0 || $patientId <= 0) {
        json_response(['ok' => false, 'error' => 'admission_id and patient_id are required'], 422);
    }
    if ($entryType === '') {
        json_response(['ok' => false, 'error' => 'entry_type is required'], 422);
    }

    $chk = $pdo->prepare('SELECT id, ward FROM admissions WHERE id = :id AND patient_id = :pid LIMIT 1');
    $chk->execute(['id' => $admissionId, 'pid' => $patientId]);
    $adm = $chk->fetch();
    if (!$adm) {
        json_response(['ok' => false, 'error' => 'Admission not found'], 404);
    }

    $ins = $pdo->prepare(
        "INSERT INTO ward_fluid_balance
            (admission_id, patient_id, ward, shift, entry_type, volume_ml, notes, recorded_by, recorded_at)
         VALUES
            (:admission_id, :patient_id, :ward, :shift, :entry_type, :volume_ml, :notes, :recorded_by, :recorded_at)"
    );
    $ins->execute([
        'admission_id' => $admissionId,
        'patient_id'   => $patientId,
        'ward'         => (string)($adm['ward'] ?? ''),
        'shift'        => $shift,
        'entry_type'   => $entryType,
        'volume_ml'    => $volumeMl,
        'notes'        => trim($body['notes'] ?? '') ?: null,
        'recorded_by'  => $recordedBy,
        'recorded_at'  => $recordedAt,
    ]);

    json_response(['ok' => true, 'fluid_id' => (int)$pdo->lastInsertId()]);
} catch (Throwable $e) {
    json_response(['ok' => false, 'error' => $e->getMessage()], 500);
}
