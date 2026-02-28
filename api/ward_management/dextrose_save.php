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

    $id          = !empty($body['id']) ? (int)$body['id'] : null;
    $admissionId = (int)($body['admission_id'] ?? 0);
    $patientId   = (int)($body['patient_id'] ?? 0);
    $solution    = trim($body['solution'] ?? '');
    $action      = $body['action'] ?? '';

    // Status-update on existing record
    if ($id !== null && in_array($action, ['completed', 'discontinued'], true)) {
        $upd = $pdo->prepare(
            "UPDATE ward_dextrose SET status = :status, completed_at = :completed_at WHERE id = :id"
        );
        $upd->execute([
            'status'       => $action,
            'completed_at' => date('Y-m-d H:i:s'),
            'id'           => $id,
        ]);
        json_response(['ok' => true, 'updated' => $id]);
    }

    if ($admissionId <= 0 || $patientId <= 0) {
        json_response(['ok' => false, 'error' => 'admission_id and patient_id are required'], 422);
    }
    if ($solution === '') {
        json_response(['ok' => false, 'error' => 'solution is required'], 422);
    }

    $chk = $pdo->prepare('SELECT id, ward FROM admissions WHERE id = :id AND patient_id = :pid LIMIT 1');
    $chk->execute(['id' => $admissionId, 'pid' => $patientId]);
    $adm = $chk->fetch();
    if (!$adm) {
        json_response(['ok' => false, 'error' => 'Admission not found'], 404);
    }

    // Determine next bottle_no
    $maxStmt = $pdo->prepare('SELECT COALESCE(MAX(bottle_no), 0) FROM ward_dextrose WHERE admission_id = :id');
    $maxStmt->execute(['id' => $admissionId]);
    $bottleNo = (int)$maxStmt->fetchColumn() + 1;

    $ins = $pdo->prepare(
        "INSERT INTO ward_dextrose
            (admission_id, patient_id, ward, bottle_no, solution, volume_ml, rate_ml_hr,
             iv_site, started_at, status, recorded_by, notes)
         VALUES
            (:admission_id, :patient_id, :ward, :bottle_no, :solution, :volume_ml, :rate_ml_hr,
             :iv_site, :started_at, 'running', :recorded_by, :notes)"
    );
    $ins->execute([
        'admission_id' => $admissionId,
        'patient_id'   => $patientId,
        'ward'         => (string)($adm['ward'] ?? ''),
        'bottle_no'    => $bottleNo,
        'solution'     => $solution,
        'volume_ml'    => !empty($body['volume_ml'])   ? (int)$body['volume_ml']   : 1000,
        'rate_ml_hr'   => !empty($body['rate_ml_hr'])  ? (int)$body['rate_ml_hr']  : null,
        'iv_site'      => trim($body['iv_site'] ?? '') ?: null,
        'started_at'   => $body['started_at'] ?? date('Y-m-d H:i:s'),
        'recorded_by'  => trim($body['recorded_by'] ?? ''),
        'notes'        => trim($body['notes'] ?? '') ?: null,
    ]);

    json_response(['ok' => true, 'dextrose_id' => (int)$pdo->lastInsertId(), 'bottle_no' => $bottleNo]);
} catch (Throwable $e) {
    json_response(['ok' => false, 'error' => $e->getMessage()], 500);
}
