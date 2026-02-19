<?php
declare(strict_types=1);

require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';
require_once __DIR__ . '/_tables.php';

require_method('POST');

try {
    $raw = file_get_contents('php://input');
    $data = json_decode($raw ?: 'null', true);
    if (!is_array($data)) {
        json_response(['ok' => false, 'error' => 'Invalid JSON body'], 400);
    }

    $patientIdRaw = $data['patient_id'] ?? null;
    if (!is_int($patientIdRaw) && !(is_string($patientIdRaw) && ctype_digit($patientIdRaw))) {
        json_response(['ok' => false, 'error' => 'Missing or invalid patient_id'], 400);
    }
    $patientId = (int)$patientIdRaw;

    $type = strtoupper(trim((string)($data['type'] ?? 'OPD')));
    if (!in_array($type, ['ER', 'OPD', 'IPD', 'PHARMACY'], true)) {
        $type = 'OPD';
    }

    $pdo = db();
    ensure_encounter_tables($pdo);

    $stmt = $pdo->prepare('SELECT id FROM patients WHERE id = :id LIMIT 1');
    $stmt->execute(['id' => $patientId]);
    if (!$stmt->fetch()) {
        json_response(['ok' => false, 'error' => 'Patient not found'], 404);
    }

    $encId = create_encounter($pdo, $patientId, $type);

    $stmt = $pdo->prepare('SELECT * FROM encounters WHERE id = :id LIMIT 1');
    $stmt->execute(['id' => $encId]);

    json_response([
        'ok' => true,
        'encounter' => $stmt->fetch(),
    ]);
} catch (Throwable $e) {
    json_response([
        'ok' => false,
        'error' => $e->getMessage(),
    ], 500);
}
