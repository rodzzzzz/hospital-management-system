<?php
declare(strict_types=1);

require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';
require_once __DIR__ . '/../_cors.php';
require_once __DIR__ . '/_tables.php';
require_once __DIR__ . '/../encounters/_tables.php';

cors_headers();
require_method('POST');

try {
    $pdo = db();
    ensure_admissions_tables($pdo);

    $body = json_decode(file_get_contents('php://input'), true) ?? [];

    $patientId          = (int)($body['patient_id'] ?? 0);
    $admissionType      = $body['admission_type'] ?? 'scheduled';
    $ward               = trim($body['ward'] ?? '');
    $roomNo             = trim($body['room_no'] ?? '');
    $bedId              = !empty($body['bed_id']) ? (int)$body['bed_id'] : null;
    $admittingPhysician = trim($body['admitting_physician'] ?? '');
    $admittingDiagnosis = trim($body['admitting_diagnosis'] ?? '');
    $admissionDate      = $body['admission_date'] ?? date('Y-m-d H:i:s');
    $philhealthPin      = trim($body['philhealth_pin'] ?? '');
    $insuranceInfo      = trim($body['insurance_info'] ?? '');
    $allergyNotes       = trim($body['allergy_notes'] ?? '');
    $dietNotes          = trim($body['diet_notes'] ?? '');
    $specialInstructions = trim($body['special_instructions'] ?? '');
    $preAdmissionId     = !empty($body['pre_admission_id']) ? (int)$body['pre_admission_id'] : null;

    if ($patientId <= 0) {
        json_response(['ok' => false, 'error' => 'patient_id is required'], 422);
    }
    if ($ward === '') {
        json_response(['ok' => false, 'error' => 'ward is required'], 422);
    }

    // Verify patient exists
    $p = $pdo->prepare('SELECT id FROM patients WHERE id = :id LIMIT 1');
    $p->execute(['id' => $patientId]);
    if (!$p->fetch()) {
        json_response(['ok' => false, 'error' => 'Patient not found'], 404);
    }

    // Create or reuse IPD encounter
    $encId = resolve_encounter_id($pdo, $patientId, null, 'IPD');

    $pdo->beginTransaction();

    $ins = $pdo->prepare(
        "INSERT INTO admissions
            (patient_id, encounter_id, pre_admission_id, admission_type, ward, room_no, bed_id,
             admitting_physician, admitting_diagnosis, admission_date, status,
             philhealth_pin, insurance_info, allergy_notes, diet_notes, special_instructions)
         VALUES
            (:patient_id, :encounter_id, :pre_admission_id, :admission_type, :ward, :room_no, :bed_id,
             :admitting_physician, :admitting_diagnosis, :admission_date, 'admitted',
             :philhealth_pin, :insurance_info, :allergy_notes, :diet_notes, :special_instructions)"
    );
    $ins->execute([
        'patient_id'          => $patientId,
        'encounter_id'        => $encId,
        'pre_admission_id'    => $preAdmissionId,
        'admission_type'      => $admissionType,
        'ward'                => $ward,
        'room_no'             => $roomNo,
        'bed_id'              => $bedId,
        'admitting_physician' => $admittingPhysician,
        'admitting_diagnosis' => $admittingDiagnosis,
        'admission_date'      => $admissionDate,
        'philhealth_pin'      => $philhealthPin,
        'insurance_info'      => $insuranceInfo,
        'allergy_notes'       => $allergyNotes,
        'diet_notes'          => $dietNotes,
        'special_instructions' => $specialInstructions,
    ]);

    $admissionId = (int)$pdo->lastInsertId();
    $admissionNo = 'ADM-' . date('Ymd') . '-' . str_pad((string)$admissionId, 6, '0', STR_PAD_LEFT);
    $pdo->prepare('UPDATE admissions SET admission_no = :no WHERE id = :id')
        ->execute(['no' => $admissionNo, 'id' => $admissionId]);

    // If bed_id provided, mark bed as occupied
    if ($bedId !== null) {
        $pdo->prepare(
            "UPDATE ward_beds SET status = 'occupied', current_admission_id = :adm_id, current_patient_id = :pat_id WHERE id = :bed_id"
        )->execute(['adm_id' => $admissionId, 'pat_id' => $patientId, 'bed_id' => $bedId]);
    }

    // If linked to pre-admission, mark it as admitted
    if ($preAdmissionId !== null) {
        $pdo->prepare("UPDATE pre_admissions SET status = 'admitted' WHERE id = :id")
            ->execute(['id' => $preAdmissionId]);
    }

    $pdo->commit();

    json_response([
        'ok'           => true,
        'admission_id' => $admissionId,
        'admission_no' => $admissionNo,
    ]);
} catch (Throwable $e) {
    if (isset($pdo) && $pdo->inTransaction()) $pdo->rollBack();
    json_response(['ok' => false, 'error' => $e->getMessage()], 500);
}
