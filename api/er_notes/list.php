<?php
declare(strict_types=1);

require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';
require_once __DIR__ . '/_tables.php';
require_once __DIR__ . '/../encounters/_tables.php';

require_method('GET');

try {
    $patientIdRaw = $_GET['patient_id'] ?? null;
    $patientId = null;
    if (is_int($patientIdRaw)) {
        $patientId = $patientIdRaw;
    } elseif (is_string($patientIdRaw) && ctype_digit((string)$patientIdRaw)) {
        $patientId = (int)$patientIdRaw;
    }

    $encounterIdRaw = $_GET['encounter_id'] ?? null;
    $encounterId = null;
    if (is_int($encounterIdRaw)) {
        $encounterId = $encounterIdRaw;
    } elseif (is_string($encounterIdRaw) && ctype_digit((string)$encounterIdRaw)) {
        $encounterId = (int)$encounterIdRaw;
    }

    if ($patientId === null && $encounterId === null) {
        json_response(['ok' => false, 'error' => 'Missing patient_id or encounter_id'], 400);
    }

    $pdo = db();
    ensure_er_notes_tables($pdo);

    if ($encounterId === null) {
        $open = $patientId !== null ? find_open_encounter_by_type($pdo, $patientId, 'ER') : null;
        $encounterId = $open && isset($open['id']) ? (int)$open['id'] : null;
        if ($encounterId === null) {
            json_response(['ok' => true, 'encounter' => null, 'notes' => []]);
        }
    }

    $encStmt = $pdo->prepare(
        'SELECT e.id, e.encounter_no, e.patient_id, e.type, e.status, e.started_at, e.ended_at, p.patient_code, p.full_name
         FROM encounters e
         JOIN patients p ON p.id = e.patient_id
         WHERE e.id = :id
         LIMIT 1'
    );
    $encStmt->execute(['id' => $encounterId]);
    $enc = $encStmt->fetch();
    if (!$enc) {
        json_response(['ok' => false, 'error' => 'Encounter not found'], 404);
    }

    if (strtoupper((string)($enc['type'] ?? '')) !== 'ER') {
        json_response(['ok' => false, 'error' => 'Encounter is not ER'], 400);
    }

    if ($patientId !== null && (int)($enc['patient_id'] ?? 0) !== (int)$patientId) {
        json_response(['ok' => false, 'error' => 'Encounter mismatch'], 400);
    }

    $stmt = $pdo->prepare(
        'SELECT n.*, p.patient_code, p.full_name, e.encounter_no
         FROM er_consultation_notes n
         JOIN patients p ON p.id = n.patient_id
         JOIN encounters e ON e.id = n.encounter_id
         WHERE n.encounter_id = :encounter_id
         ORDER BY n.created_at DESC, n.id DESC'
    );
    $stmt->execute(['encounter_id' => $encounterId]);
    $rows = $stmt->fetchAll();

    json_response(['ok' => true, 'encounter' => $enc, 'notes' => $rows]);
} catch (Throwable $e) {
    json_response(['ok' => false, 'error' => $e->getMessage()], 500);
}
