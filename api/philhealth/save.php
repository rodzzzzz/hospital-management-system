<?php
declare(strict_types=1);

require_once __DIR__ . '/../_cors.php';
require_once __DIR__ . '/_db.php';
require_once __DIR__ . '/../_response.php';

cors_headers();
require_method('POST');

try {
    $raw = file_get_contents('php://input');
    $data = json_decode($raw ?: 'null', true);
    if (!is_array($data)) {
        json_response(['ok' => false, 'error' => 'Invalid JSON body'], 400);
    }

    $basic = $data['basic'] ?? null;
    $cf1 = $data['cf1'] ?? null;
    $cf2 = $data['cf2'] ?? null;
    $cf3 = $data['cf3'] ?? null;
    $cf4 = $data['cf4'] ?? null;

    $finalize = (bool)($data['finalize'] ?? false);
    $claimStatusRaw = $data['claim_status'] ?? null;
    $claimStatusRaw = is_string($claimStatusRaw) ? strtolower(trim($claimStatusRaw)) : null;
    $allowedStatuses = ['pending', 'approved', 'rejected', 'eligible', 'ineligible'];
    $targetClaimStatus = null;
    if (is_string($claimStatusRaw) && $claimStatusRaw !== '' && in_array($claimStatusRaw, $allowedStatuses, true)) {
        $targetClaimStatus = $claimStatusRaw;
    } elseif ($finalize) {
        $targetClaimStatus = 'pending';
    }

    if (!is_array($basic) && is_array($cf1)) {
        $pinDigits = '';
        $memberPin = $cf1['member_pin'] ?? null;
        $pinDigits = preg_replace('/\D/', '', (string)$memberPin);

        $nameParts = array_filter([
            trim((string)($cf1['patient_last_name'] ?? '')),
            trim((string)($cf1['patient_first_name'] ?? '')),
            trim((string)($cf1['patient_middle_name'] ?? '')),
        ], fn($v) => $v !== '');

        if (!$nameParts) {
            $nameParts = array_filter([
                trim((string)($cf1['member_last_name'] ?? '')),
                trim((string)($cf1['member_first_name'] ?? '')),
                trim((string)($cf1['member_middle_name'] ?? '')),
            ], fn($v) => $v !== '');
        }

        $mm = $cf1['patient_birth_month[]'] ?? null;
        $dd = $cf1['patient_birth_day[]'] ?? null;
        $yy = $cf1['patient_birth_year[]'] ?? null;
        $dob = null;
        if (is_array($mm) && is_array($dd) && is_array($yy)) {
            $m = implode('', array_map('strval', $mm));
            $d = implode('', array_map('strval', $dd));
            $y = implode('', array_map('strval', $yy));
            if (preg_match('/^\d{4}$/', $y) && preg_match('/^\d{2}$/', $m) && preg_match('/^\d{2}$/', $d)) {
                $dob = "{$y}-{$m}-{$d}";
            }
        }

        $basic = [
            'philhealthId' => $pinDigits,
            'patientName' => implode(', ', $nameParts),
            'patientDOB' => $dob,
            'patientGender' => $cf1['patient_sex'] ?? null,
            'patientContact' => $cf1['patient_mobile'] ?? null,
            'civilStatus' => $cf1['patient_civil_status'] ?? null,
            'patientEmail' => $cf1['patient_email'] ?? null,
            'streetAddress' => $cf1['patient_street_address'] ?? null,
            'barangay' => $cf1['patient_barangay'] ?? null,
            'city' => $cf1['patient_city'] ?? null,
            'province' => $cf1['patient_province'] ?? null,
            'zipCode' => $cf1['patient_zip_code'] ?? null,
            'employerName' => $cf1['patient_employer_name'] ?? null,
            'employerAddress' => $cf1['patient_employer_address'] ?? null,
        ];
    }

    if (!is_array($basic)) {
        json_response(['ok' => false, 'error' => 'Missing basic (or cf1 patient info)'], 400);
    }

    $pin = trim((string)($basic['philhealthId'] ?? ''));
    $patientName = trim((string)($basic['patientName'] ?? ''));
    $basicPatientId = $basic['patientId'] ?? null;
    $basicPatientId = is_int($basicPatientId) ? $basicPatientId : (ctype_digit((string)$basicPatientId) ? (int)$basicPatientId : null);
    if ($patientName === '' && is_array($cf3)) {
        $nameParts = array_filter([
            trim((string)($cf3['patient_last_name'] ?? '')),
            trim((string)($cf3['patient_first_name'] ?? '')),
            trim((string)($cf3['patient_middle_name'] ?? '')),
        ], fn($v) => $v !== '');
        $patientName = implode(', ', $nameParts);
    }
    if ($pin === '' || $patientName === '') {
        json_response(['ok' => false, 'error' => 'Missing philhealthId or patientName'], 400);
    }

    $pdo = philhealth_db();

    $patientDob = $basic['patientDOB'] ?? null;
    $patientDob = is_string($patientDob) && $patientDob !== '' ? $patientDob : null;

    $patientId = null;

    if (is_int($basicPatientId) && $basicPatientId > 0) {
        $stmt = $pdo->prepare('SELECT id, philhealth_pin FROM patients WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $basicPatientId]);
        $existingById = $stmt->fetch();
        if (!$existingById || !isset($existingById['id'])) {
            json_response([
                'ok' => false,
                'error' => 'Member not found for update',
            ], 404);
        }

        $existingPin = (string)($existingById['philhealth_pin'] ?? '');
        if ($pin !== '' && $existingPin !== '' && $pin !== $existingPin) {
            $stmt = $pdo->prepare('SELECT id FROM patients WHERE philhealth_pin = :pin AND id <> :id LIMIT 1');
            $stmt->execute(['pin' => $pin, 'id' => $basicPatientId]);
            $conflict = $stmt->fetch();
            if ($conflict) {
                json_response([
                    'ok' => false,
                    'error' => 'Duplicate PhilHealth PIN. Member already exists and cannot use this PIN.',
                ], 409);
            }
        }

        $stmt = $pdo->prepare(
            'UPDATE patients
             SET philhealth_pin = :pin,
                 full_name = :full_name,
                 dob = :dob,
                 sex = :sex,
                 contact = :contact,
                 civil_status = :civil_status,
                 email = :email,
                 street_address = :street_address,
                 barangay = :barangay,
                 city = :city,
                 province = :province,
                 zip_code = :zip_code,
                 employer_name = :employer_name,
                 employer_address = :employer_address
             WHERE id = :id'
        );
        $stmt->execute([
            'id' => $basicPatientId,
            'pin' => $pin,
            'full_name' => $patientName,
            'dob' => $patientDob,
            'sex' => $basic['patientGender'] ?? null,
            'contact' => $basic['patientContact'] ?? null,
            'civil_status' => $basic['civilStatus'] ?? null,
            'email' => $basic['patientEmail'] ?? null,
            'street_address' => $basic['streetAddress'] ?? null,
            'barangay' => $basic['barangay'] ?? null,
            'city' => $basic['city'] ?? null,
            'province' => $basic['province'] ?? null,
            'zip_code' => $basic['zipCode'] ?? null,
            'employer_name' => $basic['employerName'] ?? null,
            'employer_address' => $basic['employerAddress'] ?? null,
        ]);

        $patientId = $basicPatientId;
    } else {
        $stmt = $pdo->prepare(
            'INSERT INTO patients (philhealth_pin, full_name, dob, sex, contact, civil_status, email, street_address, barangay, city, province, zip_code, employer_name, employer_address)
             VALUES (:pin, :full_name, :dob, :sex, :contact, :civil_status, :email, :street_address, :barangay, :city, :province, :zip_code, :employer_name, :employer_address)
             ON DUPLICATE KEY UPDATE
                full_name = VALUES(full_name),
                dob = VALUES(dob),
                sex = VALUES(sex),
                contact = VALUES(contact),
                civil_status = VALUES(civil_status),
                email = VALUES(email),
                street_address = VALUES(street_address),
                barangay = VALUES(barangay),
                city = VALUES(city),
                province = VALUES(province),
                zip_code = VALUES(zip_code),
                employer_name = VALUES(employer_name),
                employer_address = VALUES(employer_address)'
        );

        $stmt->execute([
            'pin' => $pin,
            'full_name' => $patientName,
            'dob' => $patientDob,
            'sex' => $basic['patientGender'] ?? null,
            'contact' => $basic['patientContact'] ?? null,
            'civil_status' => $basic['civilStatus'] ?? null,
            'email' => $basic['patientEmail'] ?? null,
            'street_address' => $basic['streetAddress'] ?? null,
            'barangay' => $basic['barangay'] ?? null,
            'city' => $basic['city'] ?? null,
            'province' => $basic['province'] ?? null,
            'zip_code' => $basic['zipCode'] ?? null,
            'employer_name' => $basic['employerName'] ?? null,
            'employer_address' => $basic['employerAddress'] ?? null,
        ]);

        $patientId = (int)$pdo->query('SELECT id FROM patients WHERE philhealth_pin = ' . $pdo->quote($pin))->fetch()['id'];
    }

    $patientId = (int)$patientId;

    $shouldUpsertMember = false;
    if ($finalize) {
        $shouldUpsertMember = true;
    }
    if (is_string($targetClaimStatus) && $targetClaimStatus !== '') {
        $shouldUpsertMember = true;
    }

    if ($shouldUpsertMember) {
        $stmt = $pdo->prepare(
            'INSERT INTO philhealth_members (patient_id, philhealth_pin, employer_name, employer_address)
             VALUES (:patient_id, :philhealth_pin, :employer_name, :employer_address)
             ON DUPLICATE KEY UPDATE
                philhealth_pin = VALUES(philhealth_pin),
                employer_name = VALUES(employer_name),
                employer_address = VALUES(employer_address)'
        );
        $stmt->execute([
            'patient_id' => $patientId,
            'philhealth_pin' => $pin,
            'employer_name' => $basic['employerName'] ?? null,
            'employer_address' => $basic['employerAddress'] ?? null,
        ]);
    }

    $stmt = $pdo->prepare(
        'INSERT INTO philhealth_claims (patient_id, status)
         VALUES (:patient_id, :status)
         ON DUPLICATE KEY UPDATE updated_at = CURRENT_TIMESTAMP'
    );
    $stmt->execute([
        'patient_id' => $patientId,
        'status' => (is_string($targetClaimStatus) && $targetClaimStatus !== '') ? $targetClaimStatus : 'pending',
    ]);

    $stmt = $pdo->prepare('SELECT id FROM philhealth_claims WHERE patient_id = :patient_id ORDER BY updated_at DESC LIMIT 1');
    $stmt->execute(['patient_id' => $patientId]);
    $row = $stmt->fetch();
    if (!$row || !isset($row['id'])) {
        json_response(['ok' => false, 'error' => 'Failed to resolve claim'], 500);
    }
    $claimId = (int)$row['id'];

    $formStmt = $pdo->prepare(
        "INSERT INTO philhealth_forms (claim_id, philhealth_pin, form_code, data_json)
         VALUES (:claim_id, :philhealth_pin, :form_code, :data_json)
         ON DUPLICATE KEY UPDATE
         philhealth_pin = VALUES(philhealth_pin),
         data_json = VALUES(data_json),
         updated_at = CURRENT_TIMESTAMP"
    );

    $forms = [
        'cf1' => $cf1,
        'cf2' => $cf2,
        'cf3' => $cf3,
        'cf4' => $cf4,
    ];
    foreach ($forms as $code => $payload) {
        if (!is_array($payload)) continue;
        $formStmt->execute([
            'claim_id' => $claimId,
            'philhealth_pin' => $pin,
            'form_code' => $code,
            'data_json' => json_encode($payload, JSON_UNESCAPED_UNICODE),
        ]);
    }

    if (is_string($targetClaimStatus) && $targetClaimStatus !== '') {
        $stmt = $pdo->prepare('UPDATE philhealth_claims SET status = :status, updated_at = CURRENT_TIMESTAMP WHERE id = :id');
        $stmt->execute([
            'status' => $targetClaimStatus,
            'id' => $claimId,
        ]);
    }

    json_response([
        'ok' => true,
        'patient_id' => $patientId,
        'claim_id' => $claimId,
    ]);
} catch (Throwable $e) {
    json_response([
        'ok' => false,
        'error' => $e->getMessage(),
    ], 500);
}
