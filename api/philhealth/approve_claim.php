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

    $claimId = $data['claim_id'] ?? null;
    $claimId = is_int($claimId) ? $claimId : (ctype_digit((string)$claimId) ? (int)$claimId : null);
    if (!is_int($claimId) || $claimId <= 0) {
        json_response(['ok' => false, 'error' => 'Missing or invalid claim_id'], 400);
    }

    $pdo = philhealth_db();

    $stmt = $pdo->prepare('SELECT id, status FROM philhealth_claims WHERE id = :id LIMIT 1');
    $stmt->execute(['id' => $claimId]);
    $claim = $stmt->fetch();
    if (!$claim || !isset($claim['id'])) {
        json_response(['ok' => false, 'error' => 'Claim not found'], 404);
    }

    $status = strtolower(trim((string)($claim['status'] ?? '')));
    if ($status !== 'pending') {
        json_response(['ok' => false, 'error' => 'Only pending claims can be approved'], 409);
    }

    $stmt = $pdo->prepare("UPDATE philhealth_claims SET status = 'approved', updated_at = CURRENT_TIMESTAMP WHERE id = :id");
    $stmt->execute(['id' => $claimId]);

    json_response(['ok' => true, 'claim_id' => $claimId, 'status' => 'approved']);
} catch (Throwable $e) {
    json_response(['ok' => false, 'error' => $e->getMessage()], 500);
}
