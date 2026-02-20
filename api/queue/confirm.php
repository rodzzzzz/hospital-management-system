<?php
declare(strict_types=1);

require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';
require_once __DIR__ . '/_tables.php';

require_method('POST');

try {
    $raw = file_get_contents('php://input');
    $data = json_decode($raw ?: 'null', true);
    if (!is_array($data)) {
        json_response(['ok' => false, 'error' => 'Invalid JSON body'], 400);
    }

    $queueIdRaw = $data['queue_id'] ?? null;
    if (!is_numeric($queueIdRaw) || (int)$queueIdRaw <= 0) {
        json_response(['ok' => false, 'error' => 'Missing queue_id'], 400);
    }
    $queueId = (int)$queueIdRaw;

    $confirmedBy = trim((string)($data['confirmed_by'] ?? ''));
    $confirmedBy = ($confirmedBy !== '') ? $confirmedBy : null;

    $pdo = db();
    ensure_patient_queue_table($pdo);

    $pdo->beginTransaction();

    $stmt = $pdo->prepare('SELECT * FROM patient_queue WHERE id = :id FOR UPDATE');
    $stmt->execute(['id' => $queueId]);
    $row = $stmt->fetch();
    if (!$row) {
        $pdo->rollBack();
        json_response(['ok' => false, 'error' => 'Queue item not found'], 404);
    }

    if ((string)$row['status'] !== 'queued') {
        $pdo->rollBack();
        json_response(['ok' => false, 'error' => 'Queue item is not in queued status'], 409);
    }

    $payload = json_decode((string)$row['payload_json'], true);
    if (!is_array($payload)) {
        $pdo->rollBack();
        json_response(['ok' => false, 'error' => 'Invalid queue payload'], 500);
    }

    // Reuse existing patient creation endpoint logic via direct insert (keeps this endpoint self-contained)
    $fullName = trim((string)($payload['full_name'] ?? ''));
    if ($fullName === '') {
        $pdo->rollBack();
        json_response(['ok' => false, 'error' => 'Queue payload missing full_name'], 400);
    }

    $pinRaw = trim((string)($payload['philhealth_pin'] ?? ''));
    $pinDigits = preg_replace('/\D/', '', $pinRaw);
    $philhealthPin = ($pinDigits !== '') ? $pinDigits : null;

    $dob = trim((string)($payload['dob'] ?? ''));
    $dob = ($dob !== '') ? $dob : null;

    $sex = $payload['sex'] ?? null;
    $sex = is_string($sex) ? trim($sex) : null;
    $sex = ($sex !== '') ? $sex : null;

    $bloodType = $payload['blood_type'] ?? null;
    $bloodType = is_string($bloodType) ? trim($bloodType) : null;
    $bloodType = ($bloodType !== '') ? $bloodType : null;

    $civilStatus = $payload['civil_status'] ?? null;
    $civilStatus = is_string($civilStatus) ? trim($civilStatus) : null;
    $civilStatus = ($civilStatus !== '') ? $civilStatus : null;

    $contact = $payload['contact'] ?? null;
    $contact = is_string($contact) ? trim($contact) : null;
    $contact = ($contact !== '') ? $contact : null;

    $email = $payload['email'] ?? null;
    $email = is_string($email) ? trim($email) : null;
    $email = ($email !== '') ? $email : null;

    $street = $payload['street_address'] ?? null;
    $street = is_string($street) ? trim($street) : null;
    $street = ($street !== '') ? $street : null;

    $barangay = $payload['barangay'] ?? null;
    $barangay = is_string($barangay) ? trim($barangay) : null;
    $barangay = ($barangay !== '') ? $barangay : null;

    $city = $payload['city'] ?? null;
    $city = is_string($city) ? trim($city) : null;
    $city = ($city !== '') ? $city : null;

    $province = $payload['province'] ?? null;
    $province = is_string($province) ? trim($province) : null;
    $province = ($province !== '') ? $province : null;

    $zip = $payload['zip_code'] ?? null;
    $zip = is_string($zip) ? trim($zip) : null;
    $zip = ($zip !== '') ? $zip : null;

    $employerName = $payload['employer_name'] ?? null;
    $employerName = is_string($employerName) ? trim($employerName) : null;
    $employerName = ($employerName !== '') ? $employerName : null;

    $employerAddress = $payload['employer_address'] ?? null;
    $employerAddress = is_string($employerAddress) ? trim($employerAddress) : null;
    $employerAddress = ($employerAddress !== '') ? $employerAddress : null;

    $patientType = $payload['patient_type'] ?? null;
    $patientType = is_string($patientType) ? trim($patientType) : null;
    $patientType = ($patientType !== '') ? $patientType : null;

    $initialLocation = $payload['initial_location'] ?? null;
    $initialLocation = is_string($initialLocation) ? trim($initialLocation) : null;
    $initialLocation = ($initialLocation !== '') ? $initialLocation : null;

    $department = $payload['department'] ?? null;
    $department = is_string($department) ? trim($department) : null;
    $department = ($department !== '') ? $department : null;

    $diagnosis = $payload['diagnosis'] ?? null;
    $diagnosis = is_string($diagnosis) ? trim($diagnosis) : null;
    $diagnosis = ($diagnosis !== '') ? $diagnosis : null;

    $emergencyName = $payload['emergency_contact_name'] ?? null;
    $emergencyName = is_string($emergencyName) ? trim($emergencyName) : null;
    $emergencyName = ($emergencyName !== '') ? $emergencyName : null;

    $emergencyRelationship = $payload['emergency_contact_relationship'] ?? null;
    $emergencyRelationship = is_string($emergencyRelationship) ? trim($emergencyRelationship) : null;
    $emergencyRelationship = ($emergencyRelationship !== '') ? $emergencyRelationship : null;

    $emergencyPhone = $payload['emergency_contact_phone'] ?? null;
    $emergencyPhone = is_string($emergencyPhone) ? trim($emergencyPhone) : null;
    $emergencyPhone = ($emergencyPhone !== '') ? $emergencyPhone : null;

    if (is_string($philhealthPin) && $philhealthPin !== '') {
        $stmt = $pdo->prepare('SELECT id FROM patients WHERE philhealth_pin = :pin LIMIT 1');
        $stmt->execute(['pin' => $philhealthPin]);
        if ($stmt->fetch()) {
            $pdo->rollBack();
            json_response(['ok' => false, 'error' => 'Duplicate PhilHealth PIN'], 409);
        }
    }

    $stmt = $pdo->prepare(
        'INSERT INTO patients (
            philhealth_pin,
            full_name,
            dob,
            sex,
            blood_type,
            contact,
            civil_status,
            email,
            street_address,
            barangay,
            city,
            province,
            zip_code,
            employer_name,
            employer_address,
            patient_type,
            initial_location,
            department,
            diagnosis,
            emergency_contact_name,
            emergency_contact_relationship,
            emergency_contact_phone
        ) VALUES (
            :philhealth_pin,
            :full_name,
            :dob,
            :sex,
            :blood_type,
            :contact,
            :civil_status,
            :email,
            :street_address,
            :barangay,
            :city,
            :province,
            :zip_code,
            :employer_name,
            :employer_address,
            :patient_type,
            :initial_location,
            :department,
            :diagnosis,
            :emergency_contact_name,
            :emergency_contact_relationship,
            :emergency_contact_phone
        )'
    );

    $stmt->execute([
        'philhealth_pin' => $philhealthPin,
        'full_name' => $fullName,
        'dob' => $dob,
        'sex' => $sex,
        'blood_type' => $bloodType,
        'contact' => $contact,
        'civil_status' => $civilStatus,
        'email' => $email,
        'street_address' => $street,
        'barangay' => $barangay,
        'city' => $city,
        'province' => $province,
        'zip_code' => $zip,
        'employer_name' => $employerName,
        'employer_address' => $employerAddress,
        'patient_type' => $patientType,
        'initial_location' => $initialLocation,
        'department' => $department,
        'diagnosis' => $diagnosis,
        'emergency_contact_name' => $emergencyName,
        'emergency_contact_relationship' => $emergencyRelationship,
        'emergency_contact_phone' => $emergencyPhone,
    ]);

    $patientId = (int)$pdo->lastInsertId();

    $code = trim((string)($payload['patient_code'] ?? ''));
    if ($code === '') {
        $code = 'P-' . str_pad((string)$patientId, 6, '0', STR_PAD_LEFT);
    }

    $stmt = $pdo->prepare('UPDATE patients SET patient_code = :code WHERE id = :id');
    $stmt->execute(['code' => $code, 'id' => $patientId]);

    $stmt = $pdo->prepare(
        'UPDATE patient_queue
         SET status = \'confirmed\',
             confirmed_patient_id = :patient_id,
             confirmed_by = :confirmed_by,
             confirmed_at = NOW()
         WHERE id = :id'
    );
    $stmt->execute([
        'patient_id' => $patientId,
        'confirmed_by' => $confirmedBy,
        'id' => $queueId,
    ]);

    $stmt = $pdo->prepare('SELECT * FROM patients WHERE id = :id LIMIT 1');
    $stmt->execute(['id' => $patientId]);
    $patient = $stmt->fetch();

    $pdo->commit();

    json_response([
        'ok' => true,
        'patient' => $patient,
        'queue_id' => $queueId,
    ]);
} catch (Throwable $e) {
    if (isset($pdo) && $pdo instanceof PDO && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    json_response([
        'ok' => false,
        'error' => $e->getMessage(),
    ], 500);
}
