<?php
declare(strict_types=1);

require_once __DIR__ . '/../_cors.php';
require_once __DIR__ . '/_db.php';
require_once __DIR__ . '/../_response.php';

cors_headers();
require_method('GET');

try {
    $pdo = philhealth_db();

    $patientId = null;
    $pin = null;

    if (isset($_GET['patient_id']) && $_GET['patient_id'] !== '') {
        $patientId = require_int('patient_id');
    } elseif (isset($_GET['pin']) && $_GET['pin'] !== '') {
        $pin = require_string('pin');
    } else {
        json_response(['ok' => false, 'error' => 'Missing patient_id or pin'], 400);
    }

    if ($patientId === null) {
        $stmt = $pdo->prepare('SELECT id FROM patients WHERE philhealth_pin = :pin LIMIT 1');
        $stmt->execute(['pin' => $pin]);
        $row = $stmt->fetch();
        if (!$row || !isset($row['id'])) {
            json_response(['ok' => false, 'error' => 'Member not found'], 404);
        }
        $patientId = (int)$row['id'];
    }

    $stmt = $pdo->prepare('SELECT * FROM patients WHERE id = :id LIMIT 1');
    $stmt->execute(['id' => $patientId]);
    $patient = $stmt->fetch();
    if (!$patient) {
        json_response(['ok' => false, 'error' => 'Member not found'], 404);
    }

    $stmt = $pdo->prepare(
        "SELECT c.id, c.status, c.created_at, c.updated_at
         FROM philhealth_claims c
         INNER JOIN philhealth_forms f ON f.claim_id = c.id
         WHERE c.patient_id = :patient_id
         ORDER BY c.updated_at DESC
         LIMIT 1"
    );
    $stmt->execute(['patient_id' => $patientId]);
    $claim = $stmt->fetch();

    if (!$claim || !isset($claim['id'])) {
        $stmt = $pdo->prepare('SELECT id, status, created_at, updated_at FROM philhealth_claims WHERE patient_id = :patient_id ORDER BY updated_at DESC LIMIT 1');
        $stmt->execute(['patient_id' => $patientId]);
        $claim = $stmt->fetch();
    }

    $claimId = null;
    $forms = [
        'cf1' => null,
        'cf2' => null,
        'cf3' => null,
        'cf4' => null,
    ];

    if ($claim && isset($claim['id'])) {
        $claimId = (int)$claim['id'];
        $stmt = $pdo->prepare('SELECT form_code, data_json FROM philhealth_forms WHERE claim_id = :claim_id');
        $stmt->execute(['claim_id' => $claimId]);
        foreach ($stmt->fetchAll() as $row) {
            $code = (string)($row['form_code'] ?? '');
            if (!isset($forms[$code])) continue;
            $decoded = json_decode((string)($row['data_json'] ?? ''), true);
            if (is_array($decoded)) $forms[$code] = $decoded;
        }
    }

    $sex = $patient['sex'] ?? null;
    if (is_string($sex)) {
        $s = strtoupper(trim($sex));
        if ($s === 'M') $sex = 'male';
        if ($s === 'F') $sex = 'female';
    }

    $basic = [
        'patientId' => (int)$patient['id'],
        'philhealthId' => $patient['philhealth_pin'] ?? null,
        'patientName' => $patient['full_name'] ?? null,
        'patientDOB' => $patient['dob'] ?? null,
        'patientGender' => $sex,
        'patientContact' => $patient['contact'] ?? null,
        'civilStatus' => $patient['civil_status'] ?? null,
        'patientEmail' => $patient['email'] ?? null,
        'streetAddress' => $patient['street_address'] ?? null,
        'barangay' => $patient['barangay'] ?? null,
        'city' => $patient['city'] ?? null,
        'province' => $patient['province'] ?? null,
        'zipCode' => $patient['zip_code'] ?? null,
        'employerName' => $patient['employer_name'] ?? null,
        'employerAddress' => $patient['employer_address'] ?? null,
    ];

    json_response([
        'ok' => true,
        'patient_id' => (int)$patient['id'],
        'claim_id' => $claimId,
        'basic' => $basic,
        'forms' => $forms,
        'claim' => $claim ?: null,
    ]);
} catch (Throwable $e) {
    json_response(['ok' => false, 'error' => $e->getMessage()], 500);
}
