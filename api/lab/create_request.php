<?php
declare(strict_types=1);

require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';
require_once __DIR__ . '/_tables.php';
require_once __DIR__ . '/../price_master/_tables.php';
require_once __DIR__ . '/../auth/_session.php';
require_once __DIR__ . '/../users/_tables.php';

require_method('POST');

try {
    $raw = file_get_contents('php://input');
    $data = json_decode($raw ?: 'null', true);
    if (!is_array($data)) {
        json_response(['ok' => false, 'error' => 'Invalid JSON body'], 400);
    }

    $patientId = $data['patient_id'] ?? null;
    if (!is_int($patientId) && !(is_string($patientId) && ctype_digit($patientId))) {
        json_response(['ok' => false, 'error' => 'Missing or invalid patient_id'], 400);
    }
    $patientId = (int)$patientId;

    $sourceUnit = strtoupper(trim((string)($data['source_unit'] ?? 'ER')));
    if (!in_array($sourceUnit, ['ER', 'OPD'], true)) {
        $sourceUnit = 'ER';
    }

    $encounterType = strtoupper(trim((string)($data['encounter_type'] ?? $sourceUnit)));
    if (!in_array($encounterType, ['ER', 'OPD', 'IPD', 'PHARMACY'], true)) {
        $encounterType = $sourceUnit;
    }

    $triageLevelRaw = $data['triage_level'] ?? null;
    if ($triageLevelRaw === null || $triageLevelRaw === '') {
        $triageLevelRaw = 5;
    }
    if (!is_int($triageLevelRaw) && !(is_string($triageLevelRaw) && ctype_digit($triageLevelRaw))) {
        json_response(['ok' => false, 'error' => 'Invalid triage_level'], 400);
    }
    $triageLevel = (int)$triageLevelRaw;
    if ($triageLevel < 1 || $triageLevel > 5) {
        json_response(['ok' => false, 'error' => 'triage_level must be 1-5'], 400);
    }

    $chiefComplaint = trim((string)($data['chief_complaint'] ?? ''));
    if ($chiefComplaint === '') {
        json_response(['ok' => false, 'error' => 'Missing chief_complaint'], 400);
    }

    $priority = strtolower(trim((string)($data['priority'] ?? 'routine')));
    if (!in_array($priority, ['routine', 'urgent', 'stat'], true)) {
        json_response(['ok' => false, 'error' => 'Invalid priority'], 400);
    }

    $requestedBy = trim((string)($data['requested_by'] ?? ''));
    $requestedBy = ($requestedBy !== '') ? $requestedBy : null;

    $approvedByInput = trim((string)($data['approved_by'] ?? ''));
    $approvedByInput = ($approvedByInput !== '') ? $approvedByInput : null;

    $requesterRole = strtolower(trim((string)($data['requester_role'] ?? '')));
    if ($requesterRole === '') {
        $requesterRole = 'nurse';
    }
    if (!in_array($requesterRole, ['nurse', 'np_pa', 'nurse_direct'], true)) {
        json_response(['ok' => false, 'error' => 'Invalid requester_role'], 400);
    }

    $doctorId = $data['doctor_id'] ?? null;
    if ($doctorId === '') {
        $doctorId = null;
    }
    if ($doctorId !== null && !is_int($doctorId) && !(is_string($doctorId) && ctype_digit($doctorId))) {
        json_response(['ok' => false, 'error' => 'Invalid doctor_id'], 400);
    }
    $doctorId = ($doctorId !== null) ? (int)$doctorId : null;
    if ($doctorId !== null && $doctorId <= 0) {
        json_response(['ok' => false, 'error' => 'Invalid doctor_id'], 400);
    }

    if ($requesterRole === 'nurse' && !$doctorId) {
        json_response(['ok' => false, 'error' => 'Missing doctor_id'], 400);
    }

    $notes = trim((string)($data['notes'] ?? ''));
    $notes = ($notes !== '') ? $notes : null;

    $vitals = $data['vitals'] ?? null;
    if ($vitals !== null && !is_array($vitals)) {
        json_response(['ok' => false, 'error' => 'Invalid vitals'], 400);
    }

    $tests = $data['tests'] ?? null;
    if (!is_array($tests) || count($tests) === 0) {
        json_response(['ok' => false, 'error' => 'Select at least 1 test'], 400);
    }

    $isDirectToLab = ($requesterRole === 'np_pa' || $requesterRole === 'nurse_direct');

    $status = $isDirectToLab ? 'approved' : 'pending_approval';
    $approvedBy = $isDirectToLab ? ($approvedByInput ?: ($requestedBy ?: (($requesterRole === 'nurse_direct') ? 'ER Nurse' : 'NP/PA'))) : null;
    $approvedAt = ($status === 'approved') ? date('Y-m-d H:i:s') : null;

    $pdo = db();
    $authUser = auth_current_user($pdo);
    if (!$authUser) {
        json_response(['ok' => false, 'error' => 'Not authenticated'], 401);
    }

    $isAdmin = auth_user_has_module($authUser, 'ADMIN');

    if ($sourceUnit === 'ER') {
        if (!$isAdmin && !auth_user_has_module($authUser, 'ER')) {
            json_response(['ok' => false, 'error' => 'Forbidden'], 403);
        }

        if ($requesterRole === 'np_pa') {
            if (!$isAdmin && !auth_user_has_role($authUser, 'ER', 'NP/PA')) {
                json_response(['ok' => false, 'error' => 'Forbidden'], 403);
            }
        } else {
            if (!$isAdmin && !auth_user_has_role($authUser, 'ER', 'ER Nurse')) {
                json_response(['ok' => false, 'error' => 'Forbidden'], 403);
            }
        }
    } elseif ($sourceUnit === 'OPD') {
        if ($requesterRole === 'np_pa') {
            if (!$isAdmin && !auth_user_has_module($authUser, 'DOCTOR') && !auth_user_has_module($authUser, 'OPD')) {
                json_response(['ok' => false, 'error' => 'Forbidden'], 403);
            }
        } else {
            if (!$isAdmin && !auth_user_has_module($authUser, 'OPD')) {
                json_response(['ok' => false, 'error' => 'Forbidden'], 403);
            }
        }
    }

    if ($requestedBy === null || $requestedBy === '') {
        $auto = trim((string)($authUser['full_name'] ?? ''));
        $requestedBy = ($auto !== '') ? $auto : null;
    }
    ensure_lab_tables($pdo);

    ensure_price_master_tables($pdo);
    $hardCatalog = lab_test_catalog();

    $requestedCodes = [];
    foreach ($tests as $t) {
        if (!is_string($t)) {
            continue;
        }
        $code = strtolower(trim($t));
        if ($code === '') {
            continue;
        }
        $requestedCodes[$code] = true;
    }
    $requestedCodes = array_keys($requestedCodes);
    if (count($requestedCodes) === 0) {
        json_response(['ok' => false, 'error' => 'Invalid tests'], 400);
    }

    $items = [];
    $feeByCode = [];
    if (count($requestedCodes) > 0) {
        $placeholders = implode(',', array_fill(0, count($requestedCodes), '?'));
        $feeStmt = $pdo->prepare("SELECT test_code, test_name FROM lab_test_fees WHERE test_code IN ({$placeholders})");
        $feeStmt->execute($requestedCodes);
        $feeRows = $feeStmt->fetchAll();
        foreach (is_array($feeRows) ? $feeRows : [] as $r) {
            $c = strtolower(trim((string)($r['test_code'] ?? '')));
            if ($c === '') continue;
            $feeByCode[$c] = [
                'name' => (string)($r['test_name'] ?? $c),
                'specimen' => null,
            ];
        }
    }

    foreach ($requestedCodes as $code) {
        if (array_key_exists($code, $feeByCode)) {
            $items[$code] = $feeByCode[$code];
            continue;
        }
        if (array_key_exists($code, $hardCatalog)) {
            $items[$code] = $hardCatalog[$code];
        }
    }

    $invalidCodes = array_values(array_diff($requestedCodes, array_keys($items)));
    if (count($invalidCodes) > 0) {
        json_response([
            'ok' => false,
            'error' => 'Invalid tests: ' . implode(', ', $invalidCodes),
        ], 400);
    }

    if ($doctorId !== null) {
        ensure_users_tables($pdo);
        $docStmt = $pdo->prepare(
            "SELECT u.id
             FROM users u
             JOIN user_roles ur ON ur.user_id = u.id
             WHERE u.id = :id AND u.status = 'active' AND ur.module = 'DOCTOR'
             LIMIT 1"
        );
        $docStmt->execute(['id' => $doctorId]);
        if (!$docStmt->fetch()) {
            json_response(['ok' => false, 'error' => 'Doctor not found'], 404);
        }
    }

    $stmt = $pdo->prepare('SELECT id FROM patients WHERE id = :id LIMIT 1');
    $stmt->execute(['id' => $patientId]);
    if (!$stmt->fetch()) {
        json_response(['ok' => false, 'error' => 'Patient not found'], 404);
    }

    $pdo->beginTransaction();

    $encounterId = create_encounter($pdo, $patientId, $encounterType);

    $insert = $pdo->prepare(
        "INSERT INTO lab_requests (
            patient_id,
            encounter_id,
            source_unit,
            triage_level,
            chief_complaint,
            priority,
            vitals_json,
            notes,
            status,
            requested_by,
            doctor_id,
            requester_role,
            approved_by,
            approved_at
        ) VALUES (
            :patient_id,
            :encounter_id,
            :source_unit,
            :triage_level,
            :chief_complaint,
            :priority,
            :vitals_json,
            :notes,
            :status,
            :requested_by,
            :doctor_id,
            :requester_role,
            :approved_by,
            :approved_at
        )"
    );

    $vitalsJson = ($vitals !== null) ? json_encode($vitals) : null;

    $insert->execute([
        'patient_id' => $patientId,
        'encounter_id' => $encounterId,
        'source_unit' => $sourceUnit,
        'triage_level' => $triageLevel,
        'chief_complaint' => $chiefComplaint,
        'priority' => $priority,
        'vitals_json' => $vitalsJson,
        'notes' => $notes,
        'status' => $status,
        'requested_by' => $requestedBy,
        'doctor_id' => $doctorId,
        'requester_role' => $requesterRole,
        'approved_by' => $approvedBy,
        'approved_at' => $approvedAt,
    ]);

    $requestId = (int)$pdo->lastInsertId();
    $requestNo = 'LAB-' . date('Ymd') . '-' . str_pad((string)$requestId, 6, '0', STR_PAD_LEFT);

    $pdo->prepare('UPDATE lab_requests SET request_no = :no WHERE id = :id')->execute([
        'no' => $requestNo,
        'id' => $requestId,
    ]);

    $itemStmt = $pdo->prepare(
        'INSERT INTO lab_request_items (request_id, test_code, test_name, specimen) VALUES (:request_id, :test_code, :test_name, :specimen)'
    );

    foreach ($items as $code => $info) {
        $itemStmt->execute([
            'request_id' => $requestId,
            'test_code' => $code,
            'test_name' => (string)$info['name'],
            'specimen' => (string)($info['specimen'] ?? null),
        ]);
    }

    $pdo->commit();

    json_response([
        'ok' => true,
        'request' => [
            'id' => $requestId,
            'request_no' => $requestNo,
            'status' => $status,
        ],
    ]);
} catch (Throwable $e) {
    if (isset($pdo) && $pdo instanceof PDO && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    json_response([
        'ok' => false,
        'error' => $e->getMessage(),
    ], 500);
}
