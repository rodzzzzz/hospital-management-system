<?php
declare(strict_types=1);

require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';

require_method('GET');

try {
    $pdo = db();

    $firstName = trim((string)($_GET['first_name'] ?? ''));
    $lastName = trim((string)($_GET['last_name'] ?? ''));
    $philhealth = trim((string)($_GET['philhealth'] ?? ''));

    // Validation: Must have (first name AND last name) OR philhealth
    $hasName = $firstName !== '' && $lastName !== '';
    $hasPhilhealth = $philhealth !== '';

    if (!$hasName && !$hasPhilhealth) {
        json_response(['ok' => false, 'error' => 'Please provide first name and last name, or PhilHealth ID'], 400);
    }

    $conditions = [];
    $params = [];

    // Search by exact first and last name match (case-insensitive)
    if ($hasName) {
        // First try to match using first_name and last_name columns (new approach)
        // Fall back to searching in full_name for backward compatibility
        $conditions[] = '(
            (LOWER(p.first_name) = :first_name_exact AND LOWER(p.last_name) = :last_name_exact)
            OR (LOWER(p.full_name) LIKE :first_name_like AND LOWER(p.full_name) LIKE :last_name_like)
        )';
        $params['first_name_exact'] = strtolower($firstName);
        $params['last_name_exact'] = strtolower($lastName);
        $params['first_name_like'] = '%' . strtolower($firstName) . '%';
        $params['last_name_like'] = '%' . strtolower($lastName) . '%';
    }

    if ($hasPhilhealth) {
        $conditions[] = 'LOWER(p.philhealth_pin) LIKE :philhealth';
        $params['philhealth'] = '%' . strtolower($philhealth) . '%';
    }

    $where = implode(' OR ', $conditions);

    $sql = "SELECT
                p.id,
                p.patient_code,
                p.full_name,
                p.dob,
                p.sex,
                p.contact,
                p.civil_status,
                p.blood_type,
                p.philhealth_pin,
                p.street_address,
                p.barangay,
                p.city,
                p.province,
                p.updated_at
            FROM patients p
            WHERE {$where}
            ORDER BY p.full_name ASC
            LIMIT 20";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $rows = $stmt->fetchAll();

    json_response([
        'ok' => true,
        'patients' => $rows,
    ]);
} catch (Throwable $e) {
    json_response([
        'ok' => false,
        'error' => $e->getMessage(),
    ], 500);
}
