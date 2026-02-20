<?php
declare(strict_types=1);

require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';

require_method('POST');

header('Content-Type: application/json');

try {
    $raw = file_get_contents('php://input');
    $data = json_decode($raw ?: 'null', true);
    
    if (!is_array($data)) {
        json_response(['ok' => false, 'error' => 'Invalid JSON body'], 400);
    }

    $fullName = trim((string)($data['full_name'] ?? ''));
    if ($fullName === '') {
        json_response(['ok' => false, 'error' => 'Full name is required'], 400);
    }

    $dob = trim((string)($data['dob'] ?? ''));
    if ($dob === '') {
        json_response(['ok' => false, 'error' => 'Date of birth is required'], 400);
    }

    $sex = $data['sex'] ?? null;
    $sex = is_string($sex) ? trim($sex) : null;
    if ($sex === '' || !in_array($sex, ['Male', 'Female'])) {
        json_response(['ok' => false, 'error' => 'Valid sex is required'], 400);
    }

    $contact = trim((string)($data['contact'] ?? ''));
    if ($contact === '') {
        json_response(['ok' => false, 'error' => 'Contact number is required'], 400);
    }

    if (!preg_match('/^\d{11}$/', $contact)) {
        json_response(['ok' => false, 'error' => 'Contact number must be 11 digits'], 400);
    }

    $email = trim((string)($data['email'] ?? ''));
    if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        json_response(['ok' => false, 'error' => 'Invalid email format'], 400);
    }

    $streetAddress = trim((string)($data['street_address'] ?? ''));
    if ($streetAddress === '') {
        json_response(['ok' => false, 'error' => 'Street address is required'], 400);
    }

    $barangay = trim((string)($data['barangay'] ?? ''));
    if ($barangay === '') {
        json_response(['ok' => false, 'error' => 'Barangay is required'], 400);
    }

    $city = trim((string)($data['city'] ?? ''));
    if ($city === '') {
        json_response(['ok' => false, 'error' => 'City is required'], 400);
    }

    $province = trim((string)($data['province'] ?? ''));
    if ($province === '') {
        json_response(['ok' => false, 'error' => 'Province is required'], 400);
    }

    $emergencyName = trim((string)($data['emergency_contact_name'] ?? ''));
    if ($emergencyName === '') {
        json_response(['ok' => false, 'error' => 'Emergency contact name is required'], 400);
    }

    $emergencyRelationship = trim((string)($data['emergency_contact_relationship'] ?? ''));
    if ($emergencyRelationship === '') {
        json_response(['ok' => false, 'error' => 'Emergency contact relationship is required'], 400);
    }

    $emergencyPhone = trim((string)($data['emergency_contact_phone'] ?? ''));
    if ($emergencyPhone === '') {
        json_response(['ok' => false, 'error' => 'Emergency contact phone is required'], 400);
    }

    if (!preg_match('/^\d{11}$/', $emergencyPhone)) {
        json_response(['ok' => false, 'error' => 'Emergency contact phone must be 11 digits'], 400);
    }

    $patientType = trim((string)($data['patient_type'] ?? ''));
    if ($patientType === '') {
        json_response(['ok' => false, 'error' => 'Patient type is required'], 400);
    }

    $initialLocation = trim((string)($data['initial_location'] ?? ''));
    if ($initialLocation === '') {
        json_response(['ok' => false, 'error' => 'Initial location is required'], 400);
    }

    $diagnosis = trim((string)($data['diagnosis'] ?? ''));
    if ($diagnosis === '') {
        json_response(['ok' => false, 'error' => 'Diagnosis is required'], 400);
    }

    $pinRaw = trim((string)($data['philhealth_pin'] ?? ''));
    $pinDigits = preg_replace('/\D/', '', $pinRaw);
    $philhealthPin = ($pinDigits !== '') ? $pinDigits : null;

    if ($philhealthPin !== '' && !preg_match('/^\d{12}$/', $philhealthPin)) {
        json_response(['ok' => false, 'error' => 'PhilHealth PIN must be 12 digits'], 400);
    }

    $bloodType = $data['blood_type'] ?? null;
    $bloodType = is_string($bloodType) ? trim($bloodType) : null;
    $bloodType = ($bloodType !== '') ? $bloodType : null;

    $civilStatus = $data['civil_status'] ?? null;
    $civilStatus = is_string($civilStatus) ? trim($civilStatus) : null;
    $civilStatus = ($civilStatus !== '') ? $civilStatus : null;

    $zipCode = $data['zip_code'] ?? null;
    $zipCode = is_string($zipCode) ? trim($zipCode) : null;
    $zipCode = ($zipCode !== '') ? $zipCode : null;

    $employerName = $data['employer_name'] ?? null;
    $employerName = is_string($employerName) ? trim($employerName) : null;
    $employerName = ($employerName !== '') ? $employerName : null;

    $employerAddress = $data['employer_address'] ?? null;
    $employerAddress = is_string($employerAddress) ? trim($employerAddress) : null;
    $employerAddress = ($employerAddress !== '') ? $employerAddress : null;

    $pdo = db();

    if (is_string($philhealthPin) && $philhealthPin !== '') {
        $stmt = $pdo->prepare('SELECT id FROM patients WHERE philhealth_pin = :pin LIMIT 1');
        $stmt->execute(['pin' => $philhealthPin]);
        if ($stmt->fetch()) {
            json_response(['ok' => false, 'error' => 'This PhilHealth PIN is already registered'], 409);
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
        'email' => $email ?: null,
        'street_address' => $streetAddress,
        'barangay' => $barangay,
        'city' => $city,
        'province' => $province,
        'zip_code' => $zipCode,
        'employer_name' => $employerName,
        'employer_address' => $employerAddress,
        'patient_type' => $patientType,
        'initial_location' => $initialLocation,
        'diagnosis' => $diagnosis,
        'emergency_contact_name' => $emergencyName,
        'emergency_contact_relationship' => $emergencyRelationship,
        'emergency_contact_phone' => $emergencyPhone,
    ]);

    $id = (int)$pdo->lastInsertId();

    $code = 'P-' . str_pad((string)$id, 6, '0', STR_PAD_LEFT);

    $stmt = $pdo->prepare('UPDATE patients SET patient_code = :code WHERE id = :id');
    $stmt->execute(['code' => $code, 'id' => $id]);

    $stmt = $pdo->prepare('SELECT * FROM patients WHERE id = :id LIMIT 1');
    $stmt->execute(['id' => $id]);
    $patient = $stmt->fetch();

    json_response([
        'ok' => true,
        'patient' => $patient,
        'message' => 'Patient registered successfully via kiosk',
    ]);
} catch (Throwable $e) {
    json_response([
        'ok' => false,
        'error' => $e->getMessage(),
    ], 500);
}
