<?php
declare(strict_types=1);

require_once __DIR__ . '/../_cors.php';
require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';
require_once __DIR__ . '/_tables.php';

cors_headers();
require_method('POST');

try {
    $raw = file_get_contents('php://input');
    $data = json_decode($raw ?: 'null', true);
    if (!is_array($data)) {
        json_response(['ok' => false, 'error' => 'Invalid JSON body'], 400);
    }

    $encIdRaw = $data['encounter_id'] ?? null;
    if (!is_int($encIdRaw) && !(is_string($encIdRaw) && ctype_digit($encIdRaw))) {
        json_response(['ok' => false, 'error' => 'Missing or invalid encounter_id'], 400);
    }
    $encounterId = (int)$encIdRaw;

    $pdo = db();
    ensure_encounter_tables($pdo);

    $encStmt = $pdo->prepare('SELECT id, status FROM encounters WHERE id = :id LIMIT 1');
    $encStmt->execute(['id' => $encounterId]);
    $enc = $encStmt->fetch();
    if (!$enc) {
        json_response(['ok' => false, 'error' => 'Encounter not found'], 404);
    }

    $status = (string)($enc['status'] ?? '');
    if ($status === 'cancelled') {
        json_response(['ok' => false, 'error' => 'Cannot close a cancelled encounter'], 409);
    }

    if ($status !== 'closed') {
        $pdo->beginTransaction();
        $pdo->prepare("UPDATE encounters SET status = 'closed', ended_at = :ended_at WHERE id = :id")
            ->execute([
                'ended_at' => date('Y-m-d H:i:s'),
                'id' => $encounterId,
            ]);
        $pdo->commit();
    }

    $outStmt = $pdo->prepare('SELECT * FROM encounters WHERE id = :id LIMIT 1');
    $outStmt->execute(['id' => $encounterId]);

    json_response([
        'ok' => true,
        'encounter' => $outStmt->fetch(),
    ]);
} catch (Throwable $e) {
    if (isset($pdo) && $pdo instanceof PDO && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    json_response(['ok' => false, 'error' => $e->getMessage()], 500);
}
