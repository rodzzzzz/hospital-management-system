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
    $recordedBy   = trim($body['recorded_by'] ?? '');
    $recordedAt   = $body['recorded_at'] ?? date('Y-m-d H:i:s');

    if ($admissionId <= 0 || $patientId <= 0) {
        json_response(['ok' => false, 'error' => 'admission_id and patient_id are required'], 422);
    }

    $chk = $pdo->prepare('SELECT id, ward FROM admissions WHERE id = :id AND patient_id = :pid LIMIT 1');
    $chk->execute(['id' => $admissionId, 'pid' => $patientId]);
    $adm = $chk->fetch();
    if (!$adm) {
        json_response(['ok' => false, 'error' => 'Admission not found'], 404);
    }

    $ward = (string)($adm['ward'] ?? '');

    $ins = $pdo->prepare(
        "INSERT INTO ward_vitals
            (admission_id, patient_id, ward, temperature, blood_pressure, pulse_rate,
             respiratory_rate, oxygen_saturation, pain_scale, weight_kg, blood_glucose,
             recorded_by, recorded_at)
         VALUES
            (:admission_id, :patient_id, :ward, :temperature, :blood_pressure, :pulse_rate,
             :respiratory_rate, :oxygen_saturation, :pain_scale, :weight_kg, :blood_glucose,
             :recorded_by, :recorded_at)"
    );

    $ins->execute([
        'admission_id'      => $admissionId,
        'patient_id'        => $patientId,
        'ward'              => $ward,
        'temperature'       => !empty($body['temperature'])       ? (float)$body['temperature']       : null,
        'blood_pressure'    => trim($body['blood_pressure'] ?? '') ?: null,
        'pulse_rate'        => !empty($body['pulse_rate'])        ? (int)$body['pulse_rate']          : null,
        'respiratory_rate'  => !empty($body['respiratory_rate'])  ? (int)$body['respiratory_rate']    : null,
        'oxygen_saturation' => isset($body['oxygen_saturation']) && $body['oxygen_saturation'] !== '' ? (int)$body['oxygen_saturation'] : null,
        'pain_scale'        => isset($body['pain_scale'])        && $body['pain_scale'] !== ''        ? (int)$body['pain_scale']        : null,
        'weight_kg'         => !empty($body['weight_kg'])         ? (float)$body['weight_kg']         : null,
        'blood_glucose'     => !empty($body['blood_glucose'])     ? (float)$body['blood_glucose']     : null,
        'recorded_by'       => $recordedBy,
        'recorded_at'       => $recordedAt,
    ]);

    json_response(['ok' => true, 'vital_id' => (int)$pdo->lastInsertId()]);
} catch (Throwable $e) {
    json_response(['ok' => false, 'error' => $e->getMessage()], 500);
}
