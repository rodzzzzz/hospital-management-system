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

    // Debug: Log what we received
    error_log('Enqueue request received: ' . json_encode($data));

    // Check if this looks like a full registration (has many fields)
    $isFullRegistration = isset($data['full_name']) && isset($data['dob']) && isset($data['sex']) && 
                          isset($data['contact']) && isset($data['street_address']) && 
                          isset($data['barangay']) && isset($data['city']) && isset($data['province']);

    $pdo = db();
    $patient = null;
    $patientId = 0;

    if ($isFullRegistration) {
        // This looks like a registration request - create the patient first
        error_log("Detected full registration request, creating patient");
        
        // Add missing patient_type if not provided
        if (!isset($data['patient_type']) || trim($data['patient_type']) === '') {
            $data['patient_type'] = null; // Set to null as requested
        }
        
        // Set initial_location to null as requested
        $data['initial_location'] = null;
        
        try {
            // Check if patient with this PhilHealth ID already exists
            if (!empty($data['philhealth_pin'])) {
                $stmt = $pdo->prepare('SELECT id, full_name, patient_code FROM patients WHERE philhealth_pin = ? LIMIT 1');
                $stmt->execute([$data['philhealth_pin']]);
                $existingPatient = $stmt->fetch();
                
                if ($existingPatient) {
                    // Patient already exists, use their ID
                    $patientId = (int)$existingPatient['id'];
                    $patient = $existingPatient;
                    error_log("Patient with PhilHealth ID already exists: ID {$patientId}");
                    
                    // Skip to queueing this existing patient
                    goto queue_patient;
                }
            }
            
            // Insert patient (similar to register.php logic)
            $stmt = $pdo->prepare(
                'INSERT INTO patients (
                    philhealth_pin, full_name, first_name, last_name, dob, sex, blood_type, contact, civil_status,
                    email, street_address, barangay, city, province, zip_code,
                    employer_name, employer_address, patient_type, initial_location,
                    diagnosis, emergency_contact_name, emergency_contact_relationship,
                    emergency_contact_phone, is_new_patient
                ) VALUES (
                    :philhealth_pin, :full_name, :first_name, :last_name, :dob, :sex, :blood_type, :contact, :civil_status,
                    :email, :street_address, :barangay, :city, :province, :zip_code,
                    :employer_name, :employer_address, :patient_type, :initial_location,
                    :diagnosis, :emergency_contact_name, :emergency_contact_relationship,
                    :emergency_contact_phone, :is_new_patient
                )'
            );
            
            $stmt->execute([
                'philhealth_pin' => $data['philhealth_pin'] ?? null,
                'full_name' => $data['full_name'],
                'first_name' => $data['first_name'] ?? null,
                'last_name' => $data['last_name'] ?? null,
                'dob' => $data['dob'],
                'sex' => $data['sex'],
                'blood_type' => $data['blood_type'] ?? null,
                'contact' => $data['contact'],
                'civil_status' => $data['civil_status'] ?? null,
                'email' => $data['email'] ?? null,
                'street_address' => $data['street_address'],
                'barangay' => $data['barangay'],
                'city' => $data['city'],
                'province' => $data['province'],
                'zip_code' => $data['zip_code'] ?? null,
                'employer_name' => $data['employer_name'] ?? null,
                'employer_address' => $data['employer_address'] ?? null,
                'patient_type' => $data['patient_type'],
                'initial_location' => $data['initial_location'],
                'diagnosis' => $data['diagnosis'],
                'emergency_contact_name' => $data['emergency_contact_name'],
                'emergency_contact_relationship' => $data['emergency_contact_relationship'],
                'emergency_contact_phone' => $data['emergency_contact_phone'],
                'is_new_patient' => true,
            ]);
            
            $patientId = (int)$pdo->lastInsertId();
            
            // Generate patient code
            $code = 'P-' . str_pad((string)$patientId, 6, '0', STR_PAD_LEFT);
            $stmt = $pdo->prepare('UPDATE patients SET patient_code = :code WHERE id = :id');
            $stmt->execute(['code' => $code, 'id' => $patientId]);
            
            error_log("Created new patient: ID {$patientId}, Code {$code}");
            
            // Get the created patient
            $stmt = $pdo->prepare('SELECT id, full_name, patient_code FROM patients WHERE id = ? LIMIT 1');
            $stmt->execute([$patientId]);
            $patient = $stmt->fetch();
            
        } catch (Exception $e) {
            error_log("Failed to create patient: " . $e->getMessage());
            json_response(['ok' => false, 'error' => 'Failed to create patient: ' . $e->getMessage()], 500);
        }
    } else {
        // This is a regular enqueue request - find existing patient
        error_log("Detected regular enqueue request, looking up existing patient");
        
        // Support both patient_id (new format) and full_name (old format)
        $patientId = (int)($data['patient_id'] ?? 0);
        $fullName = trim((string)($data['full_name'] ?? ''));
        
        if ($patientId <= 0 && $fullName === '') {
            json_response(['ok' => false, 'error' => 'Valid patient_id or full_name is required'], 400);
        }

        // Get patient by ID or name
        if ($patientId > 0) {
            error_log("Looking up patient by ID: {$patientId}");
            $stmt = $pdo->prepare('SELECT id, full_name FROM patients WHERE id = ? LIMIT 1');
            $stmt->execute([$patientId]);
            $patient = $stmt->fetch();
        } else {
            error_log("Looking up patient by name: '{$fullName}'");
            
            // Try exact match first
            $stmt = $pdo->prepare('SELECT id, full_name FROM patients WHERE full_name = ? ORDER BY id DESC LIMIT 1');
            $stmt->execute([$fullName]);
            $patient = $stmt->fetch();
            
            // If not found, try case-insensitive match
            if (!$patient) {
                error_log("Exact match failed, trying case-insensitive match");
                $stmt = $pdo->prepare('SELECT id, full_name FROM patients WHERE LOWER(full_name) = LOWER(?) ORDER BY id DESC LIMIT 1');
                $stmt->execute([$fullName]);
                $patient = $stmt->fetch();
            }
            
            // If still not found, try partial match
            if (!$patient) {
                error_log("Case-insensitive failed, trying partial match");
                $stmt = $pdo->prepare('SELECT id, full_name FROM patients WHERE full_name LIKE ? ORDER BY id DESC LIMIT 1');
                $stmt->execute(["%{$fullName}%"]);
                $patient = $stmt->fetch();
            }
            
            if ($patient) {
                $patientId = (int)$patient['id'];
            }
        }
        
        if (!$patient) {
            error_log("Patient not found. ID: {$patientId}, Name: '{$fullName}'");
            json_response(['ok' => false, 'error' => 'Patient not found', 'debug' => [
                'patient_id_requested' => $patientId,
                'full_name_requested' => $fullName,
                'station_name' => $data['station_name'] ?? 'opd'
            ]], 404);
        }
    }

    queue_patient:
    error_log("Found/created patient: {$patient['full_name']} (ID: {$patient['id']})");

    $stationName = trim((string)($data['station_name'] ?? ($data['initial_location'] ?? 'opd')));
    if ($stationName === '') {
        json_response(['ok' => false, 'error' => 'Station name is required'], 400);
    }

    // Get station ID
    $stmt = $pdo->prepare('SELECT id FROM queue_stations WHERE station_name = ? AND is_active = 1 LIMIT 1');
    $stmt->execute([$stationName]);
    $station = $stmt->fetch();
    
    if (!$station) {
        json_response(['ok' => false, 'error' => 'Station not found'], 404);
    }
    
    $stationId = (int)$station['id'];

    // Check if patient is already in queue for this station
    $stmt = $pdo->prepare('SELECT id, queue_number, queue_position FROM patient_queue WHERE patient_id = ? AND station_id = ? AND status IN ("waiting", "in_progress") LIMIT 1');
    $stmt->execute([$patientId, $stationId]);
    $existingQueue = $stmt->fetch();
    
    if ($existingQueue) {
        // Format station name for display (e.g., 'opd' -> 'OPD Queue')
        $stationDisplayName = strtoupper($stationName) . ' Queue';
        
        json_response([
            'ok' => false, 
            'error' => 'Patient is already in queue for this station',
            'queue_number' => $existingQueue['queue_number'],
            'queue_position' => $existingQueue['queue_position'],
            'station_name' => $stationDisplayName
        ], 409);
    }

    // Get next queue number and position
    $stmt = $pdo->prepare('SELECT COALESCE(MAX(queue_number), 0) as max_number FROM patient_queue WHERE station_id = ?');
    $stmt->execute([$stationId]);
    $maxNumber = $stmt->fetch()['max_number'] ?? 0;
    $queueNumber = $maxNumber + 1;
    
    $stmt = $pdo->prepare('SELECT COALESCE(MAX(queue_position), 0) as max_position FROM patient_queue WHERE station_id = ? AND status = "waiting"');
    $stmt->execute([$stationId]);
    $maxPosition = $stmt->fetch()['max_position'] ?? 0;
    $queuePosition = $maxPosition + 1;

    // Create queue entry
    $stmt = $pdo->prepare(
        'INSERT INTO patient_queue (
            patient_id, station_id, queue_number, queue_position, status, arrived_at
        ) VALUES (?, ?, ?, ?, "waiting", NOW())'
    );
    $stmt->execute([$patientId, $stationId, $queueNumber, $queuePosition]);

    $queueId = (int)$pdo->lastInsertId();

    $response = [
        'ok' => true,
        'queue_id' => $queueId,
        'queue_number' => $queueNumber,
        'queue_position' => $queuePosition,
        'station_name' => $stationName,
        'patient' => $patient,
    ];

    // Add registration info if this was a full registration
    if ($isFullRegistration) {
        $response['registration'] = [
            'patient_code' => $patient['patient_code'],
            'message' => 'Patient registered and queued successfully via kiosk',
        ];
    }

    json_response($response);
} catch (Throwable $e) {
    json_response([
        'ok' => false,
        'error' => $e->getMessage(),
    ], 500);
}
