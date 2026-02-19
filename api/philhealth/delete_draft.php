<?php
declare(strict_types=1);

require_once __DIR__ . '/_db.php';
require_once __DIR__ . '/../_response.php';

require_method('POST');

try {
    $raw = file_get_contents('php://input');
    $data = json_decode($raw ?: 'null', true);
    if (!is_array($data)) {
        json_response(['ok' => false, 'error' => 'Invalid JSON body'], 400);
    }

    $patientId = $data['patient_id'] ?? null;
    $patientId = is_int($patientId) ? $patientId : (ctype_digit((string)$patientId) ? (int)$patientId : null);
    if (!is_int($patientId) || $patientId <= 0) {
        json_response(['ok' => false, 'error' => 'Missing or invalid patient_id'], 400);
    }

    $pdo = philhealth_db();

    $stmt = $pdo->prepare("SELECT id, status FROM philhealth_claims WHERE patient_id = :patient_id ORDER BY updated_at DESC LIMIT 1");
    $stmt->execute(['patient_id' => $patientId]);
    $claim = $stmt->fetch();
    if (!$claim || !isset($claim['id'])) {
        json_response(['ok' => false, 'error' => 'Draft claim not found'], 404);
    }

    $claimId = (int)$claim['id'];
    $status = strtolower(trim((string)($claim['status'] ?? '')));
    if ($status !== 'draft') {
        json_response(['ok' => false, 'error' => 'Only draft claims can be deleted'], 409);
    }

    $pdo->beginTransaction();

    $delForms = $pdo->prepare('DELETE FROM philhealth_forms WHERE claim_id = :claim_id');
    $delForms->execute(['claim_id' => $claimId]);

    $delClaim = $pdo->prepare("DELETE FROM philhealth_claims WHERE id = :id");
    $delClaim->execute(['id' => $claimId]);

    $stmt = $pdo->prepare('SELECT COUNT(*) AS c FROM philhealth_claims WHERE patient_id = :patient_id');
    $stmt->execute(['patient_id' => $patientId]);
    $cntRow = $stmt->fetch();
    $remaining = (int)($cntRow['c'] ?? 0);
    if ($remaining === 0) {
        $delMember = $pdo->prepare('DELETE FROM philhealth_members WHERE patient_id = :patient_id');
        $delMember->execute(['patient_id' => $patientId]);

        $stmt = $pdo->prepare('SELECT COUNT(*) AS c FROM philhealth_members WHERE patient_id = :patient_id');
        $stmt->execute(['patient_id' => $patientId]);
        $mRow = $stmt->fetch();
        $mCnt = (int)($mRow['c'] ?? 0);
        if ($mCnt === 0) {
            $delPatient = $pdo->prepare('DELETE FROM patients WHERE id = :id');
            $delPatient->execute(['id' => $patientId]);
        }
    }

    $pdo->commit();

    session_start();
    unset($_SESSION['philhealth_claim_active'], $_SESSION['philhealth_claim_started_at']);

    json_response(['ok' => true]);
} catch (Throwable $e) {
    if (isset($pdo) && $pdo instanceof PDO && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    json_response(['ok' => false, 'error' => $e->getMessage()], 500);
}
