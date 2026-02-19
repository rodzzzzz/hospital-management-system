<?php
declare(strict_types=1);

require_once __DIR__ . '/_db.php';
require_once __DIR__ . '/../_response.php';

try {
    $pdo = philhealth_db();

    $membersStmt = $pdo->query(
        "SELECT p.id AS patient_id,
                p.full_name,
                p.dob,
                m.philhealth_pin,
                p.updated_at,
                lc.claim_id AS latest_claim_id,
                lc.status AS latest_claim_status
         FROM philhealth_members m
         INNER JOIN patients p ON p.id = m.patient_id
         LEFT JOIN (
            SELECT c1.patient_id, c1.id AS claim_id, c1.status
            FROM philhealth_claims c1
            INNER JOIN (
                SELECT patient_id, MAX(updated_at) AS max_updated
                FROM philhealth_claims
                GROUP BY patient_id
            ) x ON x.patient_id = c1.patient_id AND x.max_updated = c1.updated_at
         ) lc ON lc.patient_id = p.id
         ORDER BY p.updated_at DESC
         LIMIT 200"
    );

    $claimsStmt = $pdo->query(
        "SELECT c.id AS claim_id, c.patient_id, c.status, c.created_at, c.updated_at, p.full_name, m.philhealth_pin
         FROM philhealth_claims c
         INNER JOIN patients p ON p.id = c.patient_id
         INNER JOIN philhealth_members m ON m.patient_id = p.id
         ORDER BY c.updated_at DESC
         LIMIT 200"
    );

    json_response([
        'ok' => true,
        'members' => $membersStmt->fetchAll(),
        'claims' => $claimsStmt->fetchAll(),
    ]);
} catch (Throwable $e) {
    json_response([
        'ok' => false,
        'error' => $e->getMessage(),
    ], 500);
}
