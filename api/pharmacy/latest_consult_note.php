<?php
declare(strict_types=1);

require_once __DIR__ . '/../_cors.php';
require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';

cors_headers();
require_method('GET');

function table_exists(PDO $pdo, string $tableName): bool
{
    try {
        $stmt = $pdo->prepare(
            'SELECT 1 FROM information_schema.tables WHERE table_schema = DATABASE() AND table_name = :t LIMIT 1'
        );
        $stmt->execute(['t' => $tableName]);
        return $stmt->fetchColumn() !== false;
    } catch (Throwable $e) {
        return false;
    }
}

try {
    $patientIdRaw = $_GET['patient_id'] ?? null;
    if ($patientIdRaw === null || $patientIdRaw === '' || !ctype_digit((string)$patientIdRaw)) {
        json_response(['ok' => false, 'error' => 'Missing or invalid patient_id'], 400);
    }
    $patientId = (int)$patientIdRaw;

    $sourceRaw = strtoupper(trim((string)($_GET['source'] ?? '')));
    $source = in_array($sourceRaw, ['ER', 'OPD'], true) ? $sourceRaw : '';

    $pdo = db();

    $hasOpd = table_exists($pdo, 'opd_consultation_notes');
    $hasEr = table_exists($pdo, 'er_consultation_notes');

    if (!$hasOpd && !$hasEr) {
        json_response(['ok' => true, 'note' => null]);
    }

    if ($source === 'OPD' && !$hasOpd) {
        json_response(['ok' => true, 'note' => null]);
    }

    if ($source === 'ER' && !$hasEr) {
        json_response(['ok' => true, 'note' => null]);
    }

    $best = null;

    if ($hasOpd && ($source === '' || $source === 'OPD')) {
        try {
            $stmt = $pdo->prepare(
                "SELECT
                    'OPD' AS source,
                    n.note_text AS note_text,
                    n.created_at AS created_at,
                    n.doctor_name AS provider_name,
                    n.id AS note_id,
                    'appointment' AS ref_type,
                    n.appointment_id AS ref_id
                 FROM opd_consultation_notes n
                 WHERE n.patient_id = :pid
                 ORDER BY n.created_at DESC, n.id DESC
                 LIMIT 1"
            );
            $stmt->execute(['pid' => $patientId]);
            $row = $stmt->fetch();
            if ($row) {
                $best = $row;
            }
        } catch (Throwable $e) {
        }
    }

    if ($hasEr && ($source === '' || $source === 'ER')) {
        try {
            $stmt = $pdo->prepare(
                "SELECT
                    'ER' AS source,
                    n.note_text AS note_text,
                    n.created_at AS created_at,
                    n.author_name AS provider_name,
                    n.id AS note_id,
                    'encounter' AS ref_type,
                    n.encounter_id AS ref_id
                 FROM er_consultation_notes n
                 WHERE n.patient_id = :pid
                 ORDER BY n.created_at DESC, n.id DESC
                 LIMIT 1"
            );
            $stmt->execute(['pid' => $patientId]);
            $row = $stmt->fetch();
            if ($row) {
                if (!$best) {
                    $best = $row;
                } else {
                    $bestTs = strtotime((string)($best['created_at'] ?? ''));
                    $rowTs = strtotime((string)($row['created_at'] ?? ''));
                    $bestId = (int)($best['note_id'] ?? 0);
                    $rowId = (int)($row['note_id'] ?? 0);
                    if ($rowTs > $bestTs || ($rowTs === $bestTs && $rowId > $bestId)) {
                        $best = $row;
                    }
                }
            }
        } catch (Throwable $e) {
        }
    }

    if (!$best) {
        json_response(['ok' => true, 'note' => null]);
    }

    json_response([
        'ok' => true,
        'note' => [
            'source' => (string)($best['source'] ?? ''),
            'note_text' => (string)($best['note_text'] ?? ''),
            'created_at' => (string)($best['created_at'] ?? ''),
            'provider_name' => $best['provider_name'] !== null ? (string)$best['provider_name'] : null,
            'note_id' => (int)($best['note_id'] ?? 0),
            'ref_type' => (string)($best['ref_type'] ?? ''),
            'ref_id' => (int)($best['ref_id'] ?? 0),
        ],
    ]);
} catch (Throwable $e) {
    json_response(['ok' => true, 'note' => null]);
}
