<?php
declare(strict_types=1);

require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';
require_once __DIR__ . '/_schema.php';

require_method('GET');

try {
    $pdo = db();
    xray_ensure_schema($pdo);

    $id = require_int('id');

    $stmt = $pdo->prepare(
        'SELECT id, patient_name, exam_type, priority, status, ordered_at, scheduled_at, completed_at, technologist_name, notes
           FROM xray_orders
          WHERE id = :id'
    );
    $stmt->execute(['id' => $id]);
    $row = $stmt->fetch();

    if (!$row) {
        json_response([
            'ok' => false,
            'error' => 'Not found',
        ], 404);
    }

    $exam = (string)($row['exam_type'] ?? '');
    $prio = (string)($row['priority'] ?? '');
    $notes = trim((string)($row['notes'] ?? ''));

    $impression = 'No acute cardiopulmonary abnormality.';
    if (stripos($exam, 'Chest') !== false && in_array(strtolower($prio), ['urgent', 'stat'], true)) {
        $impression = 'Findings suspicious for pneumonia. Correlate clinically.';
    }

    $report = [
        'clinical_history' => ($notes !== '') ? $notes : 'Imaging requested for clinical correlation.',
        'technique' => $exam . ' (standard views).',
        'findings' => 'Cardiomediastinal silhouette within normal limits. No pleural effusion or pneumothorax.',
        'impression' => $impression,
        'radiologist' => 'Dr. Ramos',
        'report_status' => (in_array((string)($row['status'] ?? ''), ['reported'], true) ? 'Final' : 'Draft'),
    ];

    json_response([
        'ok' => true,
        'order' => $row,
        'report' => $report,
        'sample_image_url' => 'resources/sample-xray.svg',
    ]);
} catch (Throwable $e) {
    json_response([
        'ok' => false,
        'error' => $e->getMessage(),
    ], 500);
}
