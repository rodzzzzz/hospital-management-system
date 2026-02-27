<?php
declare(strict_types=1);

require_once __DIR__ . '/../_cors.php';
cors_headers();
require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../auth/_session.php';

$pdo = db();
$currentUser = auth_current_user_optional_token($pdo);

if (!$currentUser) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

try {
    $stmt = $pdo->prepare("
        SELECT id, patient_code, full_name, 
               CASE 
                   WHEN patient_code IS NOT NULL AND patient_code != '' THEN patient_code
                   ELSE CONCAT('P', LPAD(id, 6, '0'))
               END as display_code
        FROM patients 
        ORDER BY full_name ASC
        LIMIT 100
    ");
    $stmt->execute();
    $patients = $stmt->fetchAll();
    
    echo json_encode([
        'success' => true,
        'patients' => $patients
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
