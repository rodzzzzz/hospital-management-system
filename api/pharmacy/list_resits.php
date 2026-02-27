<?php
declare(strict_types=1);

require_once __DIR__ . '/../_cors.php';
require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';
require_once __DIR__ . '/_tables.php';

cors_headers();
require_method('GET');

try {
    $pdo = db();
    ensure_pharmacy_tables($pdo);

    $patientId = $_GET['patient_id'] ?? null;
    if ($patientId === null || $patientId === '' || !ctype_digit((string)$patientId)) {
        json_response(['ok' => false, 'error' => 'Missing or invalid patient_id'], 400);
    }
    $patientId = (int)$patientId;

    $encounterId = $_GET['encounter_id'] ?? null;
    $encounterId = ($encounterId !== null && $encounterId !== '' && ctype_digit((string)$encounterId)) ? (int)$encounterId : null;

    $limit = 20;

    $where = 'WHERE patient_id = :pid';
    $params = ['pid' => $patientId];
    if ($encounterId !== null) {
        $where .= ' AND encounter_id = :encounter_id';
        $params['encounter_id'] = $encounterId;
    }

    $resitsStmt = $pdo->prepare(
        'SELECT id, patient_id, encounter_id, prescribed_by, notes, created_at FROM pharmacy_resits ' . $where . ' ORDER BY created_at DESC LIMIT ' . $limit
    );
    $resitsStmt->execute($params);
    $resits = $resitsStmt->fetchAll();

    $byId = [];
    $ids = [];
    foreach ($resits as $r) {
        $rid = (int)$r['id'];
        $byId[$rid] = $r;
        $byId[$rid]['items'] = [];
        $ids[] = $rid;
    }

    if (count($ids) > 0) {
        $in = implode(',', array_fill(0, count($ids), '?'));
        $itemsStmt = $pdo->prepare(
            'SELECT resit_id, medicine_name, qty, instructions FROM pharmacy_resit_items WHERE resit_id IN (' . $in . ') ORDER BY id ASC'
        );
        $itemsStmt->execute($ids);
        $items = $itemsStmt->fetchAll();
        foreach ($items as $it) {
            $rid = (int)($it['resit_id'] ?? 0);
            if (!isset($byId[$rid])) continue;
            $byId[$rid]['items'][] = [
                'name' => (string)($it['medicine_name'] ?? ''),
                'qty' => (string)($it['qty'] ?? ''),
                'sig' => (string)($it['instructions'] ?? ''),
            ];
        }
    }

    json_response([
        'ok' => true,
        'resits' => array_values($byId),
    ]);
} catch (Throwable $e) {
    json_response([
        'ok' => false,
        'error' => $e->getMessage(),
    ], 500);
}
