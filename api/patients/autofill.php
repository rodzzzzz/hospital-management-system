<?php
declare(strict_types=1);

require_once __DIR__ . '/../_cors.php';
require_once __DIR__ . '/../_response.php';

cors_headers();
require_method('GET');

try {
    $firstNames = [
        'Juan', 'Maria', 'Jose', 'Ana', 'Mark', 'John', 'Grace', 'Angel', 'Michael', 'Carlo',
        'Jasmine', 'Paolo', 'Rafael', 'Christine', 'Daniel', 'Sofia', 'Noah', 'Mika', 'Liam', 'Aira',
    ];
    $lastNames = [
        'Dela Cruz', 'Santos', 'Reyes', 'Garcia', 'Mendoza', 'Torres', 'Flores', 'Ramos', 'Navarro', 'Castillo',
        'Bautista', 'Villanueva', 'Fernandez', 'Domingo', 'Aquino', 'Cruz', 'Lopez', 'Rivera', 'Gonzales', 'Morales',
    ];

    $barangays = ['San Isidro', 'Poblacion', 'Santa Cruz', 'San Roque', 'San Juan', 'Bagong Silang', 'Maligaya', 'San Vicente'];
    $cities = ['Quezon City', 'Manila', 'Caloocan', 'Makati', 'Pasig', 'Cebu City', 'Davao City', 'Baguio'];
    $provinces = ['Metro Manila', 'Cebu', 'Davao del Sur', 'Bulacan', 'Laguna', 'Pampanga', 'Cavite', 'Rizal'];

    $departments = ['cardiology', 'neurology', 'orthopedics', 'pediatrics', 'surgery'];
    $locations = ['emergency', 'ward', 'icu', 'or', 'pharmacy'];
    $patientTypes = ['opd', 'er', 'inpatient', 'dialysis'];
    $civilStatuses = ['single', 'married', 'widowed', 'separated'];
    $sexes = ['male', 'female'];

    $bloodTypes = ['O+', 'O-', 'A+', 'A-', 'B+', 'B-', 'AB+', 'AB-'];

    $fn = $firstNames[array_rand($firstNames)];
    $ln = $lastNames[array_rand($lastNames)];
    $fullName = $fn . ' ' . $ln;

    $start = strtotime('1955-01-01');
    $end = strtotime('2010-12-31');
    $dob = date('Y-m-d', random_int($start, $end));

    $sex = $sexes[array_rand($sexes)];
    $civilStatus = $civilStatuses[array_rand($civilStatuses)];

    $bloodType = $bloodTypes[array_rand($bloodTypes)];

    $contact = '09' . str_pad((string)random_int(0, 999999999), 9, '0', STR_PAD_LEFT);
    $email = strtolower(str_replace(' ', '.', $fullName)) . random_int(10, 99) . '@example.com';

    $patientType = $patientTypes[array_rand($patientTypes)];
    $initialLocation = $locations[array_rand($locations)];
    $department = $departments[array_rand($departments)];

    $diagnoses = [
        'Fever and cough',
        'Hypertension',
        'Diabetes Mellitus',
        'Abdominal pain',
        'CKD Stage 5 / ESRD',
        'Headache and dizziness',
        'Chest pain',
    ];
    $diagnosis = $diagnoses[array_rand($diagnoses)];

    $streetNo = random_int(1, 250);
    $streetNames = ['Rizal', 'Bonifacio', 'Aguinaldo', 'Mabini', 'Luna', 'Burgos', 'Del Pilar'];
    $street = $streetNo . ' ' . $streetNames[array_rand($streetNames)] . ' St.';

    $barangay = $barangays[array_rand($barangays)];
    $city = $cities[array_rand($cities)];
    $province = $provinces[array_rand($provinces)];
    $zip = (string)random_int(1000, 9999);

    $employers = ['ABC Trading', 'Sunrise Manufacturing', 'Northstar Logistics', 'City Hall', 'General Hospital', 'Bright Tech'];
    $employerName = $employers[array_rand($employers)];
    $employerAddress = random_int(10, 120) . ' ' . $streetNames[array_rand($streetNames)] . ' Ave., ' . $city;

    $emergencyFirst = $firstNames[array_rand($firstNames)];
    $emergencyLast = $lastNames[array_rand($lastNames)];
    $emergencyName = $emergencyFirst . ' ' . $emergencyLast;

    $relationships = ['Spouse', 'Parent', 'Sibling', 'Child', 'Friend'];
    $emergencyRelationship = $relationships[array_rand($relationships)];
    $emergencyPhone = '09' . str_pad((string)random_int(0, 999999999), 9, '0', STR_PAD_LEFT);

    $philhealthPin = null;
    if (random_int(1, 100) <= 60) {
        $pinDigits = str_pad((string)random_int(0, 999999999999), 12, '0', STR_PAD_LEFT);
        $philhealthPin = substr($pinDigits, 0, 2) . '-' . substr($pinDigits, 2, 9) . '-' . substr($pinDigits, 11, 1);
    }

    json_response([
        'ok' => true,
        'patient' => [
            'full_name' => $fullName,
            'dob' => $dob,
            'sex' => $sex,
            'blood_type' => $bloodType,
            'civil_status' => $civilStatus,
            'contact' => $contact,
            'email' => $email,
            'patient_type' => $patientType,
            'philhealth_pin' => $philhealthPin,
            'initial_location' => $initialLocation,
            'department' => $department,
            'diagnosis' => $diagnosis,
            'street_address' => $street,
            'barangay' => $barangay,
            'city' => $city,
            'province' => $province,
            'zip_code' => $zip,
            'employer_name' => $employerName,
            'employer_address' => $employerAddress,
            'emergency_contact_name' => $emergencyName,
            'emergency_contact_relationship' => $emergencyRelationship,
            'emergency_contact_phone' => $emergencyPhone,
        ],
    ]);
} catch (Throwable $e) {
    json_response([
        'ok' => false,
        'error' => $e->getMessage(),
    ], 500);
}
