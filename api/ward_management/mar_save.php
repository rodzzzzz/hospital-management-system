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
    $patientId   = (int)($body['patient_id']   ?? 0);
    $action      = trim($body['action'] ?? '');

    // Record administration status on existing MAR entry
    if ($id !== null && in_array($action, ['given', 'held', 'refused', 'not_available'], true)) {
        $upd = $pdo->prepare(
            "UPDATE ward_mar SET status = :status, given_at = :given_at, given_by = :given_by,
             remarks = :remarks, updated_at = NOW() WHERE id = :id"
        );
        $upd->execute([
            'status'   => $action,
            'given_at' => $action === 'given' ? (empty($body['given_at']) ? date('Y-m-d H:i:s') : $body['given_at']) : null,
            'given_by' => trim($body['given_by'] ?? '') ?: null,
            'remarks'  => trim($body['remarks']  ?? '') ?: null,
            'id'       => $id,
        ]);
        json_response(['ok' => true, 'updated' => $id]);
    }

    // Create new MAR entry
    if ($admissionId <= 0 || $patientId <= 0) {
        json_response(['ok' => false, 'error' => 'admission_id and patient_id are required'], 422);
    }
    $medName = trim($body['medication_name'] ?? '');
    if ($medName === '') {
        json_response(['ok' => false, 'error' => 'medication_name is required'], 422);
    }

    $chk = $pdo->prepare('SELECT id, ward FROM admissions WHERE id = :id AND patient_id = :pid LIMIT 1');
    $chk->execute(['id' => $admissionId, 'pid' => $patientId]);
    $adm = $chk->fetch();
    if (!$adm) {
        json_response(['ok' => false, 'error' => 'Admission not found'], 404);
    }

    $ins = $pdo->prepare(
        "INSERT INTO ward_mar
            (admission_id, patient_id, ward, medication_name, dose, route, frequency,
             scheduled_time, status, remarks)
         VALUES
            (:admission_id, :patient_id, :ward, :medication_name, :dose, :route, :frequency,
             :scheduled_time, 'pending', :remarks)"
    );
    $ins->execute([
        'admission_id'    => $admissionId,
        'patient_id'      => $patientId,
        'ward'            => (string)($adm['ward'] ?? ''),
        'medication_name' => $medName,
        'dose'            => trim($body['dose'] ?? '')      ?: null,
        'route'           => $body['route']     ?? 'oral',
        'frequency'       => trim($body['frequency'] ?? '') ?: null,
        'scheduled_time'  => !empty($body['scheduled_time']) ? $body['scheduled_time'] : null,
        'remarks'         => trim($body['remarks'] ?? '')   ?: null,
    ]);

    json_response(['ok' => true, 'mar_id' => (int)$pdo->lastInsertId()]);
} catch (Throwable $e) {
    json_response(['ok' => false, 'error' => $e->getMessage()], 500);
}
