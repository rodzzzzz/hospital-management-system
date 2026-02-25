<?php
declare(strict_types=1);

require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';
require_once __DIR__ . '/_tables.php';
require_once __DIR__ . '/../users/_tables.php';

require_method('GET');

try {
    $pdo = db();
    ensure_doctor_tables($pdo);
    ensure_users_tables($pdo);

    // Simple query to get all users with DOCTOR module
    $stmt = $pdo->prepare('
        SELECT DISTINCT 
            u.id,
            u.full_name,
            COALESCE(da.status, "available") as status,
            da.updated_at
        FROM users u
        INNER JOIN user_roles ur ON u.id = ur.user_id
        LEFT JOIN doctor_availability da ON u.id = da.user_id
        WHERE ur.module = "DOCTOR" 
        AND u.status = "active"
        ORDER BY u.full_name ASC
    ');
    $stmt->execute();
    $doctors = $stmt->fetchAll();

    // If no doctors found, create some sample data
    if (empty($doctors)) {
        // Check if we have any users at all
        $userCheck = $pdo->query('SELECT COUNT(*) as count FROM users')->fetch()['count'];
        
        if ($userCheck == 0) {
            // Create sample doctors
            $sampleDoctors = [
                ['full_name' => 'Dr. Juan Santos', 'username' => 'juan.santos'],
                ['full_name' => 'Dr. Maria Reyes', 'username' => 'maria.reyes'],
                ['full_name' => 'Dr. Jose Cruz', 'username' => 'jose.cruz']
            ];
            
            foreach ($sampleDoctors as $doctor) {
                // Insert user
                $insertUser = $pdo->prepare('INSERT INTO users (username, full_name, status) VALUES (?, ?, "active")');
                $insertUser->execute([$doctor['username'], $doctor['full_name']]);
                $userId = $pdo->lastInsertId();
                
                // Insert doctor role
                $insertRole = $pdo->prepare('INSERT INTO user_roles (user_id, module, role) VALUES (?, "DOCTOR", "DOCTOR")');
                $insertRole->execute([$userId]);
                
                // Insert availability
                $insertAvail = $pdo->prepare('INSERT INTO doctor_availability (user_id, status) VALUES (?, "available")');
                $insertAvail->execute([$userId]);
            }
            
            // Re-fetch doctors
            $stmt->execute();
            $doctors = $stmt->fetchAll();
        }
    }

    // Format the response
    $formattedDoctors = array_map(function ($doctor) {
        return [
            'id' => (int)($doctor['id'] ?? 0),
            'full_name' => (string)($doctor['full_name'] ?? ''),
            'status' => (string)($doctor['status'] ?? 'available'),
            'updated_at' => $doctor['updated_at'] ?? null
        ];
    }, $doctors);

    json_response([
        'ok' => true,
        'doctors' => $formattedDoctors
    ]);
} catch (Throwable $e) {
    json_response(['ok' => false, 'error' => $e->getMessage()], 500);
}
