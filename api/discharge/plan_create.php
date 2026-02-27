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

    $body        = json_decode(file_get_contents('php://input'), true) ?? [];
    $admissionId = (int)($body['admission_id'] ?? 0);
    $patientId   = (int)($body['patient_id'] ?? 0);

    if ($admissionId <= 0 || $patientId <= 0) {
        json_response(['ok' => false, 'error' => 'admission_id and patient_id are required'], 422);
    }

    // Verify admission
    $chk = $pdo->prepare('SELECT id FROM admissions WHERE id = :id AND patient_id = :pid AND status = "admitted" LIMIT 1');
    $chk->execute(['id' => $admissionId, 'pid' => $patientId]);
    if (!$chk->fetch()) {
        json_response(['ok' => false, 'error' => 'Active admission not found'], 404);
    }

    // Check no existing plan
    $existing = $pdo->prepare('SELECT id FROM discharge_plans WHERE admission_id = :id LIMIT 1');
    $existing->execute(['id' => $admissionId]);
    if ($existing->fetch()) {
        json_response(['ok' => false, 'error' => 'Discharge plan already exists for this admission'], 409);
    }

    $ins = $pdo->prepare(
        "INSERT INTO discharge_plans
            (admission_id, patient_id, expected_discharge_date, discharge_destination,
             discharge_diagnosis, discharge_notes, medications_on_discharge,
             diet_instructions, activity_restrictions, wound_care_instructions,
             return_precautions, status, planned_by)
         VALUES
            (:admission_id, :patient_id, :expected_discharge_date, :discharge_destination,
             :discharge_diagnosis, :discharge_notes, :medications_on_discharge,
             :diet_instructions, :activity_restrictions, :wound_care_instructions,
             :return_precautions, 'planning', :planned_by)"
    );
    $ins->execute([
        'admission_id'              => $admissionId,
        'patient_id'                => $patientId,
        'expected_discharge_date'   => $body['expected_discharge_date'] ?? null,
        'discharge_destination'     => $body['discharge_destination'] ?? 'home',
        'discharge_diagnosis'       => trim($body['discharge_diagnosis'] ?? ''),
        'discharge_notes'           => trim($body['discharge_notes'] ?? ''),
        'medications_on_discharge'  => trim($body['medications_on_discharge'] ?? ''),
        'diet_instructions'         => trim($body['diet_instructions'] ?? ''),
        'activity_restrictions'     => trim($body['activity_restrictions'] ?? ''),
        'wound_care_instructions'   => trim($body['wound_care_instructions'] ?? ''),
        'return_precautions'        => trim($body['return_precautions'] ?? ''),
        'planned_by'                => trim($body['planned_by'] ?? ''),
    ]);

    $planId = (int)$pdo->lastInsertId();
    $planNo = 'DCP-' . date('Ymd') . '-' . str_pad((string)$planId, 6, '0', STR_PAD_LEFT);
    $pdo->prepare('UPDATE discharge_plans SET plan_no = :no WHERE id = :id')
        ->execute(['no' => $planNo, 'id' => $planId]);

    // Auto-create clearance checklist
    $departments = ['nursing', 'physician', 'pharmacy', 'cashier', 'laboratory'];
    $clrIns = $pdo->prepare(
        "INSERT INTO discharge_clearances (discharge_plan_id, admission_id, patient_id, department, status)
         VALUES (:plan_id, :admission_id, :patient_id, :department, 'pending')"
    );
    foreach ($departments as $dept) {
        $clrIns->execute([
            'plan_id'     => $planId,
            'admission_id' => $admissionId,
            'patient_id'  => $patientId,
            'department'  => $dept,
        ]);
    }

    json_response(['ok' => true, 'plan_id' => $planId, 'plan_no' => $planNo]);
} catch (Throwable $e) {
    json_response(['ok' => false, 'error' => $e->getMessage()], 500);
}
