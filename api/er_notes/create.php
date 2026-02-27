<?php
declare(strict_types=1);

require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';
require_once __DIR__ . '/_tables.php';
require_once __DIR__ . '/../auth/_session.php';
require_once __DIR__ . '/../encounters/_tables.php';
require_once __DIR__ . '/../cashier/create_consultation_charge.php';

require_method('POST');

try {
    $raw = file_get_contents('php://input');
    $data = json_decode($raw ?: 'null', true);
    if (!is_array($data)) {
        json_response(['ok' => false, 'error' => 'Invalid JSON body'], 400);
    }

    $patientIdRaw = $data['patient_id'] ?? null;
    if (!is_int($patientIdRaw) && !(is_string($patientIdRaw) && ctype_digit((string)$patientIdRaw))) {
        json_response(['ok' => false, 'error' => 'Missing or invalid patient_id'], 400);
    }
    $patientId = (int)$patientIdRaw;

    $encounterIdRaw = $data['encounter_id'] ?? null;
    $encounterId = null;
    if ($encounterIdRaw !== null && $encounterIdRaw !== '') {
        if (!is_int($encounterIdRaw) && !(is_string($encounterIdRaw) && ctype_digit((string)$encounterIdRaw))) {
            json_response(['ok' => false, 'error' => 'Invalid encounter_id'], 400);
        }
        $encounterId = (int)$encounterIdRaw;
        if ($encounterId <= 0) {
            $encounterId = null;
        }
    }

    $noteText = trim((string)($data['note_text'] ?? ''));
    if ($noteText === '') {
        json_response(['ok' => false, 'error' => 'Missing note_text'], 400);
    }

    $pdo = db();
    ensure_er_notes_tables($pdo);

    $authUser = auth_current_user_optional_token($pdo);
    if (!$authUser) {
        json_response(['ok' => false, 'error' => 'Not authenticated'], 401);
    }

    $isAdmin = auth_user_has_module($authUser, 'ADMIN');
    if (!$isAdmin && !auth_user_has_module($authUser, 'ER') && !auth_user_has_module($authUser, 'DOCTOR')) {
        json_response(['ok' => false, 'error' => 'Forbidden'], 403);
    }

    $stmt = $pdo->prepare('SELECT id FROM patients WHERE id = :id LIMIT 1');
    $stmt->execute(['id' => $patientId]);
    if (!$stmt->fetch()) {
        json_response(['ok' => false, 'error' => 'Patient not found'], 404);
    }

    $resolvedEncounterId = resolve_er_encounter_id($pdo, $patientId, $encounterId);

    $chk = $pdo->prepare("SELECT id, type, status FROM encounters WHERE id = :id AND patient_id = :patient_id LIMIT 1");
    $chk->execute(['id' => $resolvedEncounterId, 'patient_id' => $patientId]);
    $enc = $chk->fetch();
    if (!$enc) {
        json_response(['ok' => false, 'error' => 'Encounter not found'], 404);
    }

    $encType = strtoupper((string)($enc['type'] ?? ''));
    if ($encType !== 'ER') {
        json_response(['ok' => false, 'error' => 'Encounter is not ER'], 400);
    }

    $authorUserId = (int)($authUser['id'] ?? 0);
    if ($authorUserId <= 0) {
        $authorUserId = null;
    }

    $authorName = trim((string)($authUser['full_name'] ?? ''));
    $authorName = $authorName !== '' ? $authorName : null;

    $ins = $pdo->prepare(
        'INSERT INTO er_consultation_notes (encounter_id, patient_id, author_user_id, author_name, note_text) VALUES (:encounter_id, :patient_id, :author_user_id, :author_name, :note_text)'
    );
    $ins->execute([
        'encounter_id' => $resolvedEncounterId,
        'patient_id' => $patientId,
        'author_user_id' => $authorUserId,
        'author_name' => $authorName,
        'note_text' => $noteText,
    ]);

    $id = (int)$pdo->lastInsertId();

    $get = $pdo->prepare(
        'SELECT n.*, p.patient_code, p.full_name, e.encounter_no
         FROM er_consultation_notes n
         JOIN patients p ON p.id = n.patient_id
         JOIN encounters e ON e.id = n.encounter_id
         WHERE n.id = :id
         LIMIT 1'
    );
    $get->execute(['id' => $id]);
    $row = $get->fetch();

    // Create automatic consultation charge
    try {
        $chargeId = create_consultation_charge($pdo, $patientId, 'er_consultation', $id, $resolvedEncounterId);
        if ($chargeId) {
            $row['charge_id'] = $chargeId;
        }
    } catch (Throwable $e) {
        // Log error but don't fail the note creation
        error_log('Failed to create consultation charge: ' . $e->getMessage());
    }

    json_response(['ok' => true, 'note' => $row]);
} catch (Throwable $e) {
    json_response(['ok' => false, 'error' => $e->getMessage()], 500);
}
