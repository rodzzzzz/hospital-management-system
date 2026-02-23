<?php
declare(strict_types=1);

require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_method('POST');

try {
    $pdo = db();

    $input = json_decode(file_get_contents('php://input'), true);
    
    $firstName = $input['first_name'] ?? '';
    $lastName = $input['last_name'] ?? '';
    $dob = $input['dob'] ?? null;
    $sex = $input['sex'] ?? null;

    // Check by first name, last name, and DOB (for check after DOB entry)
    if (!empty($firstName) && !empty($lastName) && !empty($dob) && empty($sex)) {
        $stmt = $pdo->prepare('
            SELECT id, first_name, last_name, dob, sex, blood_type, patient_code, philhealth_pin
            FROM patients 
            WHERE LOWER(first_name) = LOWER(?) 
            AND LOWER(last_name) = LOWER(?)
            AND dob = ?
            LIMIT 1
        ');
        $stmt->execute([$firstName, $lastName, $dob]);
        $patient = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($patient) {
            // Calculate age
            $dobDate = new DateTime($patient['dob']);
            $now = new DateTime();
            $age = $dobDate->diff($now)->y;
            
            $patient['age'] = $age;
            
            json_response([
                'ok' => true,
                'found' => true,
                'patient' => $patient
            ]);
        } else {
            json_response([
                'ok' => true,
                'found' => false
            ]);
        }
    }
    
    // Check by first name, last name, DOB, and sex (for final duplicate check before saving)
    if (!empty($firstName) && !empty($lastName) && !empty($dob) && !empty($sex)) {
        $stmt = $pdo->prepare('
            SELECT id, first_name, last_name, dob, sex, blood_type, patient_code, philhealth_pin
            FROM patients 
            WHERE LOWER(first_name) = LOWER(?) 
            AND LOWER(last_name) = LOWER(?)
            AND dob = ?
            AND sex = ?
            LIMIT 1
        ');
        $stmt->execute([$firstName, $lastName, $dob, $sex]);
        $patient = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($patient) {
            // Calculate age
            $dobDate = new DateTime($patient['dob']);
            $now = new DateTime();
            $age = $dobDate->diff($now)->y;
            
            $patient['age'] = $age;
            
            json_response([
                'ok' => true,
                'found' => true,
                'exact_match' => true,
                'patient' => $patient
            ]);
        } else {
            json_response([
                'ok' => true,
                'found' => false,
                'exact_match' => false
            ]);
        }
    }

    json_response([
        'ok' => false,
        'error' => 'Invalid parameters'
    ], 400);

} catch (Exception $e) {
    json_response([
        'ok' => false,
        'error' => $e->getMessage()
    ], 500);
}
