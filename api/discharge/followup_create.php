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
    ensure_discharge_tables($pdo);

    $body          = json_decode(file_get_contents('php://input'), true) ?? [];
    $dischargePlanId = (int)($body['discharge_plan_id'] ?? 0);
    $patientId     = (int)($body['patient_id'] ?? 0);
    $followupDate  = trim($body['followup_date'] ?? '');
    $followupTime  = trim($body['followup_time'] ?? '');
    $department    = trim($body['department'] ?? '');
    $physician     = trim($body['physician'] ?? '');
    $reason        = trim($body['reason'] ?? '');
    $notes         = trim($body['notes'] ?? '');

    if ($dischargePlanId <= 0 || $patientId <= 0) {
        json_response(['ok' => false, 'error' => 'discharge_plan_id and patient_id are required'], 422);
    }
    if ($followupDate === '') {
        json_response(['ok' => false, 'error' => 'followup_date is required'], 422);
    }

    // Verify plan exists
    $chk = $pdo->prepare('SELECT id FROM discharge_plans WHERE id = :id AND patient_id = :pid LIMIT 1');
    $chk->execute(['id' => $dischargePlanId, 'pid' => $patientId]);
    if (!$chk->fetch()) {
        json_response(['ok' => false, 'error' => 'Discharge plan not found'], 404);
    }

    $ins = $pdo->prepare(
        "INSERT INTO discharge_followups
            (discharge_plan_id, patient_id, followup_date, followup_time, department, physician, reason, notes, status)
         VALUES
            (:discharge_plan_id, :patient_id, :followup_date, :followup_time, :department, :physician, :reason, :notes, 'scheduled')"
    );
    $ins->execute([
        'discharge_plan_id' => $dischargePlanId,
        'patient_id'        => $patientId,
        'followup_date'     => $followupDate,
        'followup_time'     => $followupTime ?: null,
        'department'        => $department,
        'physician'         => $physician,
        'reason'            => $reason,
        'notes'             => $notes,
    ]);

    json_response(['ok' => true, 'followup_id' => (int)$pdo->lastInsertId()]);
} catch (Throwable $e) {
    json_response(['ok' => false, 'error' => $e->getMessage()], 500);
}
