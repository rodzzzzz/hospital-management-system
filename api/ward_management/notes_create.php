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

    $admissionId  = (int)($body['admission_id'] ?? 0);
    $patientId    = (int)($body['patient_id'] ?? 0);
    $ward         = trim($body['ward'] ?? '');
    $noteType     = $body['note_type'] ?? 'general';
    $noteText     = trim($body['note_text'] ?? '');
    $vitalsJson   = isset($body['vitals']) ? json_encode($body['vitals']) : null;
    $authorName   = trim($body['author_name'] ?? '');

    if ($admissionId <= 0 || $patientId <= 0) {
        json_response(['ok' => false, 'error' => 'admission_id and patient_id are required'], 422);
    }
    if ($noteText === '') {
        json_response(['ok' => false, 'error' => 'note_text is required'], 422);
    }

    $chk = $pdo->prepare('SELECT id, ward FROM admissions WHERE id = :id AND patient_id = :pid LIMIT 1');
    $chk->execute(['id' => $admissionId, 'pid' => $patientId]);
    $adm = $chk->fetch();
    if (!$adm) {
        json_response(['ok' => false, 'error' => 'Admission not found'], 404);
    }

    $resolvedWard = $ward ?: (string)($adm['ward'] ?? '');

    $ins = $pdo->prepare(
        "INSERT INTO ward_notes
            (admission_id, patient_id, ward, note_type, note_text, vitals_json, author_name)
         VALUES
            (:admission_id, :patient_id, :ward, :note_type, :note_text, :vitals_json, :author_name)"
    );
    $ins->execute([
        'admission_id' => $admissionId,
        'patient_id'   => $patientId,
        'ward'         => $resolvedWard,
        'note_type'    => $noteType,
        'note_text'    => $noteText,
        'vitals_json'  => $vitalsJson,
        'author_name'  => $authorName,
    ]);

    json_response(['ok' => true, 'note_id' => (int)$pdo->lastInsertId()]);
} catch (Throwable $e) {
    json_response(['ok' => false, 'error' => $e->getMessage()], 500);
}
