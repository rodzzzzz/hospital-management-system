<?php
declare(strict_types=1);

require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';
require_once __DIR__ . '/../_cors.php';
require_once __DIR__ . '/_tables.php';

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

function extract_doctor_signature(string $noteText): string
{
    $text = trim($noteText);
    if ($text === '') return '';

    if (preg_match("/^(?:Doctor['’]s|Doctor’s) Name & Signature:\s*(.+)$/mi", $text, $m)) {
        return trim((string)($m[1] ?? ''));
    }
    return '';
}

try {
    $pdo = db();
    ensure_pharmacy_tables($pdo);

    $stmt = $pdo->prepare(
        "SELECT
            r.id,
            r.patient_id,
            r.prescribed_by,
            r.notes,
            r.created_at AS submitted_at,
            p.patient_code,
            p.full_name
         FROM pharmacy_resits r
         LEFT JOIN patients p ON r.patient_id = p.id
         ORDER BY r.created_at DESC, r.id DESC"
    );
    $stmt->execute();
    $resits = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $patientIds = [];
    foreach ($resits as $r) {
        $pid = (int)($r['patient_id'] ?? 0);
        if ($pid > 0) $patientIds[$pid] = true;
    }
    $patientIds = array_keys($patientIds);

    $doctorSigByPatientId = [];
    if (!empty($patientIds)) {
        $hasOpd = table_exists($pdo, 'opd_consultation_notes');
        $hasEr = table_exists($pdo, 'er_consultation_notes');

        $parts = [];
        if ($hasOpd) {
            $parts[] = "SELECT patient_id, note_text, created_at, id FROM opd_consultation_notes WHERE patient_id IN (%s)";
        }
        if ($hasEr) {
            $parts[] = "SELECT patient_id, note_text, created_at, id FROM er_consultation_notes WHERE patient_id IN (%s)";
        }

        if (!empty($parts)) {
            $in = implode(',', array_fill(0, count($patientIds), '?'));
            $sql = implode(' UNION ALL ', array_map(function ($p) use ($in) {
                return sprintf($p, $in);
            }, $parts));
            $sql .= ' ORDER BY created_at DESC, id DESC';

            $noteStmt = $pdo->prepare($sql);
            $params = [];
            foreach ($parts as $_) {
                foreach ($patientIds as $pid) {
                    $params[] = $pid;
                }
            }
            $noteStmt->execute($params);
            $notes = $noteStmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($notes as $n) {
                $pid = (int)($n['patient_id'] ?? 0);
                if ($pid <= 0) continue;
                if (isset($doctorSigByPatientId[$pid]) && $doctorSigByPatientId[$pid] !== '') continue;
                $sig = extract_doctor_signature((string)($n['note_text'] ?? ''));
                if ($sig !== '') {
                    $doctorSigByPatientId[$pid] = $sig;
                }
            }
        }
    }

    $byId = [];
    $ids = [];
    foreach ($resits as $r) {
        $rid = (int)($r['id'] ?? 0);
        $byId[$rid] = $r;

        $pid = (int)($r['patient_id'] ?? 0);
        if ($pid > 0 && isset($doctorSigByPatientId[$pid]) && $doctorSigByPatientId[$pid] !== '') {
            $byId[$rid]['prescribed_by'] = $doctorSigByPatientId[$pid];
        }

        $byId[$rid]['items'] = [];
        $ids[] = $rid;
    }

    if (!empty($ids)) {
        $in = implode(',', array_fill(0, count($ids), '?'));
        $itemsStmt = $pdo->prepare(
            "SELECT resit_id, medicine_id, medicine_name, qty, instructions FROM pharmacy_resit_items WHERE resit_id IN ($in) ORDER BY id ASC"
        );
        $itemsStmt->execute($ids);
        $items = $itemsStmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($items as $it) {
            $rid = (int)($it['resit_id'] ?? 0);
            if (!isset($byId[$rid])) continue;
            $byId[$rid]['items'][] = [
                'medicine_id' => ($it['medicine_id'] === null ? null : (int)$it['medicine_id']),
                'name' => (string)($it['medicine_name'] ?? ''),
                'qty' => (string)($it['qty'] ?? ''),
                'sig' => (string)($it['instructions'] ?? ''),
            ];
        }
    }

    json_response(['ok' => true, 'resits' => array_values($byId)]);
} catch (Throwable $e) {
    json_response(['ok' => false, 'error' => 'Failed to list resits'], 500);
}
