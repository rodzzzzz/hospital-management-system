<?php
declare(strict_types=1);

require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';
require_once __DIR__ . '/_tables.php';
require_once __DIR__ . '/../auth/_session.php';

require_method('POST');

try {
    $raw = file_get_contents('php://input');
    $data = json_decode($raw ?: 'null', true);
    if (!is_array($data)) {
        json_response(['ok' => false, 'error' => 'Invalid JSON body'], 400);
    }

    $noteIdRaw = $data['note_id'] ?? null;
    if (!is_int($noteIdRaw) && !(is_string($noteIdRaw) && ctype_digit((string)$noteIdRaw))) {
        json_response(['ok' => false, 'error' => 'Missing or invalid note_id'], 400);
    }
    $noteId = (int)$noteIdRaw;
    if ($noteId <= 0) {
        json_response(['ok' => false, 'error' => 'Missing or invalid note_id'], 400);
    }

    $patientIdRaw = $data['patient_id'] ?? null;
    $patientId = null;
    if ($patientIdRaw !== null && $patientIdRaw !== '') {
        if (!is_int($patientIdRaw) && !(is_string($patientIdRaw) && ctype_digit((string)$patientIdRaw))) {
            json_response(['ok' => false, 'error' => 'Invalid patient_id'], 400);
        }
        $patientId = (int)$patientIdRaw;
        if ($patientId <= 0) {
            $patientId = null;
        }
    }

    $noteText = trim((string)($data['note_text'] ?? ''));
    if ($noteText === '') {
        json_response(['ok' => false, 'error' => 'Missing note_text'], 400);
    }

    $pdo = db();
    ensure_er_notes_tables($pdo);

    $authUser = auth_current_user($pdo);
    if (!$authUser) {
        json_response(['ok' => false, 'error' => 'Not authenticated'], 401);
    }

    $isAdmin = auth_user_has_module($authUser, 'ADMIN');
    if (!$isAdmin && !auth_user_has_module($authUser, 'ER') && !auth_user_has_module($authUser, 'DOCTOR')) {
        json_response(['ok' => false, 'error' => 'Forbidden'], 403);
    }

    $stmt = $pdo->prepare('SELECT * FROM er_consultation_notes WHERE id = :id LIMIT 1');
    $stmt->execute(['id' => $noteId]);
    $note = $stmt->fetch();
    if (!$note) {
        json_response(['ok' => false, 'error' => 'Note not found'], 404);
    }

    if ($patientId !== null && (int)($note['patient_id'] ?? 0) !== $patientId) {
        json_response(['ok' => false, 'error' => 'Patient mismatch'], 400);
    }

    if (!$isAdmin) {
        $authUserId = (int)($authUser['id'] ?? 0);
        $authorUserId = (int)($note['author_user_id'] ?? 0);

        $allowed = false;
        if ($authUserId > 0 && $authorUserId > 0 && $authUserId === $authorUserId) {
            $allowed = true;
        }

        if (!$allowed) {
            $userFullName = trim((string)($authUser['full_name'] ?? ''));
            $authorName = trim((string)($note['author_name'] ?? ''));
            if ($userFullName !== '' && $authorName !== '') {
                $normUser = strtolower(preg_replace('/\\s+/', ' ', $userFullName));
                $normAuthor = strtolower(preg_replace('/\\s+/', ' ', $authorName));
                if ($normUser === $normAuthor) {
                    $allowed = true;
                }
            }
        }

        if (!$allowed) {
            json_response(['ok' => false, 'error' => 'Forbidden'], 403);
        }
    }

    $upd = $pdo->prepare('UPDATE er_consultation_notes SET note_text = :note_text WHERE id = :id LIMIT 1');
    $upd->execute([
        'note_text' => $noteText,
        'id' => $noteId,
    ]);

    $get = $pdo->prepare(
        'SELECT n.*, p.patient_code, p.full_name, e.encounter_no
         FROM er_consultation_notes n
         JOIN patients p ON p.id = n.patient_id
         JOIN encounters e ON e.id = n.encounter_id
         WHERE n.id = :id
         LIMIT 1'
    );
    $get->execute(['id' => $noteId]);
    $row = $get->fetch();

    json_response(['ok' => true, 'note' => $row]);
} catch (Throwable $e) {
    json_response(['ok' => false, 'error' => $e->getMessage()], 500);
}
