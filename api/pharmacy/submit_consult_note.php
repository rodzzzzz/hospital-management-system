<?php
declare(strict_types=1);

require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';
require_once __DIR__ . '/../auth/_session.php';
require_once __DIR__ . '/_tables.php';
require_once __DIR__ . '/../opd_notes/_tables.php';
require_once __DIR__ . '/../er_notes/_tables.php';

require_method('POST');

try {
    $raw = file_get_contents('php://input');
    $data = json_decode($raw ?: 'null', true);
    if (!is_array($data)) {
        json_response(['ok' => false, 'error' => 'Invalid JSON body'], 400);
    }

    $sourceRaw = strtoupper(trim((string)($data['source'] ?? '')));
    if (!in_array($sourceRaw, ['OPD', 'ER'], true)) {
        json_response(['ok' => false, 'error' => 'Missing or invalid source'], 400);
    }

    $noteIdRaw = $data['note_id'] ?? null;
    if (!is_int($noteIdRaw) && !(is_string($noteIdRaw) && ctype_digit((string)$noteIdRaw))) {
        json_response(['ok' => false, 'error' => 'Missing or invalid note_id'], 400);
    }
    $noteId = (int)$noteIdRaw;
    if ($noteId <= 0) {
        json_response(['ok' => false, 'error' => 'Missing or invalid note_id'], 400);
    }

    $pdo = db();
    ensure_pharmacy_tables($pdo);

    $authUser = auth_current_user_optional_token($pdo);
    $submittedByUserId = $authUser ? (int)($authUser['id'] ?? 0) : null;
    $submittedByName = null;
    if ($authUser) {
        $submittedByNameRaw = (string)($authUser['full_name'] ?? ($authUser['username'] ?? ''));
        $submittedByName = trim($submittedByNameRaw) !== '' ? $submittedByNameRaw : null;
    }

    $payload = null;

    if ($sourceRaw === 'OPD') {
        ensure_opd_notes_tables($pdo);
        $stmt = $pdo->prepare(
            'SELECT id, patient_id, appointment_id, note_text, created_at, doctor_name FROM opd_consultation_notes WHERE id = :id LIMIT 1'
        );
        $stmt->execute(['id' => $noteId]);
        $row = $stmt->fetch();
        if (!$row) {
            json_response(['ok' => false, 'error' => 'OPD note not found'], 404);
        }
        $payload = [
            'source_module' => 'OPD',
            'source_note_id' => (int)($row['id'] ?? 0),
            'patient_id' => (int)($row['patient_id'] ?? 0),
            'appointment_id' => (int)($row['appointment_id'] ?? 0),
            'encounter_id' => null,
            'provider_name' => $row['doctor_name'] !== null ? (string)$row['doctor_name'] : null,
            'note_text' => (string)($row['note_text'] ?? ''),
            'note_created_at' => $row['created_at'] !== null ? (string)$row['created_at'] : null,
        ];
        if ($payload['appointment_id'] <= 0) {
            $payload['appointment_id'] = null;
        }
    } else {
        ensure_er_notes_tables($pdo);
        $stmt = $pdo->prepare(
            'SELECT id, patient_id, encounter_id, note_text, created_at, author_name FROM er_consultation_notes WHERE id = :id LIMIT 1'
        );
        $stmt->execute(['id' => $noteId]);
        $row = $stmt->fetch();
        if (!$row) {
            json_response(['ok' => false, 'error' => 'ER note not found'], 404);
        }
        $encId = (int)($row['encounter_id'] ?? 0);
        $payload = [
            'source_module' => 'ER',
            'source_note_id' => (int)($row['id'] ?? 0),
            'patient_id' => (int)($row['patient_id'] ?? 0),
            'appointment_id' => null,
            'encounter_id' => ($encId > 0 ? $encId : null),
            'provider_name' => $row['author_name'] !== null ? (string)$row['author_name'] : null,
            'note_text' => (string)($row['note_text'] ?? ''),
            'note_created_at' => $row['created_at'] !== null ? (string)$row['created_at'] : null,
        ];
    }

    if (!$payload || (int)($payload['patient_id'] ?? 0) <= 0 || trim((string)($payload['note_text'] ?? '')) === '') {
        json_response(['ok' => false, 'error' => 'Invalid note payload'], 400);
    }

    $ins = $pdo->prepare(
        'INSERT INTO pharmacy_consultation_notes
            (source_module, source_note_id, patient_id, appointment_id, encounter_id, provider_name, note_text, note_created_at, submitted_by_user_id, submitted_by_name)
         VALUES
            (:source_module, :source_note_id, :patient_id, :appointment_id, :encounter_id, :provider_name, :note_text, :note_created_at, :submitted_by_user_id, :submitted_by_name)
         ON DUPLICATE KEY UPDATE
            patient_id = VALUES(patient_id),
            appointment_id = VALUES(appointment_id),
            encounter_id = VALUES(encounter_id),
            provider_name = VALUES(provider_name),
            note_text = VALUES(note_text),
            note_created_at = VALUES(note_created_at),
            submitted_by_user_id = VALUES(submitted_by_user_id),
            submitted_by_name = VALUES(submitted_by_name),
            submitted_at = CURRENT_TIMESTAMP'
    );

    $ins->execute([
        'source_module' => $payload['source_module'],
        'source_note_id' => (int)$payload['source_note_id'],
        'patient_id' => (int)$payload['patient_id'],
        'appointment_id' => $payload['appointment_id'],
        'encounter_id' => $payload['encounter_id'],
        'provider_name' => $payload['provider_name'],
        'note_text' => $payload['note_text'],
        'note_created_at' => $payload['note_created_at'],
        'submitted_by_user_id' => ($submittedByUserId && $submittedByUserId > 0) ? $submittedByUserId : null,
        'submitted_by_name' => $submittedByName,
    ]);

    json_response(['ok' => true]);
} catch (Throwable $e) {
    json_response(['ok' => false, 'error' => $e->getMessage()], 500);
}
