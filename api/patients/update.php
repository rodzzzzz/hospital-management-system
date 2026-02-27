<?php
declare(strict_types=1);

require_once __DIR__ . '/../_cors.php';
require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';

cors_headers();
require_method('POST');

try {
    $raw = file_get_contents('php://input');
    $data = json_decode($raw ?: 'null', true);
    if (!is_array($data)) {
        json_response(['ok' => false, 'error' => 'Invalid JSON body'], 400);
    }

    $id = $data['id'] ?? null;
    if (!is_int($id) && !(is_string($id) && ctype_digit($id))) {
        json_response(['ok' => false, 'error' => 'Missing or invalid id'], 400);
    }
    $id = (int)$id;

    $fullName = trim((string)($data['full_name'] ?? ''));
    if ($fullName === '') {
        json_response(['ok' => false, 'error' => 'Missing full_name'], 400);
    }

    $pinRaw = trim((string)($data['philhealth_pin'] ?? ''));
    $pinDigits = preg_replace('/\D/', '', $pinRaw);
    $philhealthPin = ($pinDigits !== '') ? $pinDigits : null;

    $dob = trim((string)($data['dob'] ?? ''));
    $dob = ($dob !== '') ? $dob : null;

    $sex = $data['sex'] ?? null;
    $sex = is_string($sex) ? trim($sex) : null;
    $sex = ($sex !== '') ? $sex : null;

    $bloodType = $data['blood_type'] ?? null;
    $bloodType = is_string($bloodType) ? trim($bloodType) : null;
    $bloodType = ($bloodType !== '') ? $bloodType : null;

    $civilStatus = $data['civil_status'] ?? null;
    $civilStatus = is_string($civilStatus) ? trim($civilStatus) : null;
    $civilStatus = ($civilStatus !== '') ? $civilStatus : null;

    $contact = $data['contact'] ?? null;
    $contact = is_string($contact) ? trim($contact) : null;
    $contact = ($contact !== '') ? $contact : null;

    $email = $data['email'] ?? null;
    $email = is_string($email) ? trim($email) : null;
    $email = ($email !== '') ? $email : null;

    $street = $data['street_address'] ?? null;
    $street = is_string($street) ? trim($street) : null;
    $street = ($street !== '') ? $street : null;

    $barangay = $data['barangay'] ?? null;
    $barangay = is_string($barangay) ? trim($barangay) : null;
    $barangay = ($barangay !== '') ? $barangay : null;

    $city = $data['city'] ?? null;
    $city = is_string($city) ? trim($city) : null;
    $city = ($city !== '') ? $city : null;

    $province = $data['province'] ?? null;
    $province = is_string($province) ? trim($province) : null;
    $province = ($province !== '') ? $province : null;

    $zip = $data['zip_code'] ?? null;
    $zip = is_string($zip) ? trim($zip) : null;
    $zip = ($zip !== '') ? $zip : null;

    $employerName = $data['employer_name'] ?? null;
    $employerName = is_string($employerName) ? trim($employerName) : null;
    $employerName = ($employerName !== '') ? $employerName : null;

    $employerAddress = $data['employer_address'] ?? null;
    $employerAddress = is_string($employerAddress) ? trim($employerAddress) : null;
    $employerAddress = ($employerAddress !== '') ? $employerAddress : null;

    $patientType = $data['patient_type'] ?? null;
    $patientType = is_string($patientType) ? trim($patientType) : null;
    $patientType = ($patientType !== '') ? $patientType : null;

    $initialLocation = $data['initial_location'] ?? null;
    $initialLocation = is_string($initialLocation) ? trim($initialLocation) : null;
    $initialLocation = ($initialLocation !== '') ? $initialLocation : null;

    $department = $data['department'] ?? null;
    $department = is_string($department) ? trim($department) : null;
    $department = ($department !== '') ? $department : null;

    $diagnosis = $data['diagnosis'] ?? null;
    $diagnosis = is_string($diagnosis) ? trim($diagnosis) : null;
    $diagnosis = ($diagnosis !== '') ? $diagnosis : null;

    $emergencyName = $data['emergency_contact_name'] ?? null;
    $emergencyName = is_string($emergencyName) ? trim($emergencyName) : null;
    $emergencyName = ($emergencyName !== '') ? $emergencyName : null;

    $emergencyRelationship = $data['emergency_contact_relationship'] ?? null;
    $emergencyRelationship = is_string($emergencyRelationship) ? trim($emergencyRelationship) : null;
    $emergencyRelationship = ($emergencyRelationship !== '') ? $emergencyRelationship : null;

    $emergencyPhone = $data['emergency_contact_phone'] ?? null;
    $emergencyPhone = is_string($emergencyPhone) ? trim($emergencyPhone) : null;
    $emergencyPhone = ($emergencyPhone !== '') ? $emergencyPhone : null;

    $pdo = db();

    $stmt = $pdo->prepare('SELECT id FROM patients WHERE id = :id LIMIT 1');
    $stmt->execute(['id' => $id]);
    if (!$stmt->fetch()) {
        json_response(['ok' => false, 'error' => 'Patient not found'], 404);
    }

    if (is_string($philhealthPin) && $philhealthPin !== '') {
        $stmt = $pdo->prepare('SELECT id FROM patients WHERE philhealth_pin = :pin AND id <> :id LIMIT 1');
        $stmt->execute(['pin' => $philhealthPin, 'id' => $id]);
        if ($stmt->fetch()) {
            json_response(['ok' => false, 'error' => 'Duplicate PhilHealth PIN'], 409);
        }
    }

    $stmt = $pdo->prepare(
        'UPDATE patients SET
            philhealth_pin = :philhealth_pin,
            full_name = :full_name,
            dob = :dob,
            sex = :sex,
            blood_type = :blood_type,
            contact = :contact,
            civil_status = :civil_status,
            email = :email,
            street_address = :street_address,
            barangay = :barangay,
            city = :city,
            province = :province,
            zip_code = :zip_code,
            employer_name = :employer_name,
            employer_address = :employer_address,
            patient_type = :patient_type,
            initial_location = :initial_location,
            department = :department,
            diagnosis = :diagnosis,
            emergency_contact_name = :emergency_contact_name,
            emergency_contact_relationship = :emergency_contact_relationship,
            emergency_contact_phone = :emergency_contact_phone
         WHERE id = :id'
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
        'id' => $id,
    ]);

    $stmt = $pdo->prepare('SELECT * FROM patients WHERE id = :id LIMIT 1');
    $stmt->execute(['id' => $id]);
    $patient = $stmt->fetch();

    json_response([
        'ok' => true,
        'patient' => $patient,
    ]);
} catch (Throwable $e) {
    json_response([
        'ok' => false,
        'error' => $e->getMessage(),
    ], 500);
}
