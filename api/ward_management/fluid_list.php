<?php
declare(strict_types=1);

require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';
require_once __DIR__ . '/../_cors.php';
require_once __DIR__ . '/_tables.php';

cors_headers();
require_method('GET');

try {
    $pdo = db();
    ensure_ward_management_tables($pdo);

    $admissionId = !empty($_GET['admission_id']) ? (int)$_GET['admission_id'] : null;
    $patientId   = !empty($_GET['patient_id'])   ? (int)$_GET['patient_id']   : null;
    $shift       = $_GET['shift'] ?? '';
    $date        = $_GET['date']  ?? '';

    $where  = ['1=1'];
    $params = [];

    if ($admissionId !== null) {
        $where[] = 'f.admission_id = :admission_id';
        $params['admission_id'] = $admissionId;
    }
    if ($patientId !== null) {
        $where[] = 'f.patient_id = :patient_id';
        $params['patient_id'] = $patientId;
    }
    if ($shift !== '') {
        $where[] = 'f.shift = :shift';
        $params['shift'] = $shift;
    }
    if ($date !== '') {
        $where[] = 'DATE(f.recorded_at) = :date';
        $params['date'] = $date;
    }

    $stmt = $pdo->prepare(
        "SELECT f.id, f.admission_id, f.patient_id, f.ward, f.shift, f.entry_type,
                f.volume_ml, f.notes, f.recorded_by, f.recorded_at, f.created_at,
                p.full_name, p.patient_code
         FROM ward_fluid_balance f
         JOIN patients p ON p.id = f.patient_id
         WHERE " . implode(' AND ', $where) . "
         ORDER BY f.recorded_at DESC
         LIMIT 200"
    );
    $stmt->execute($params);
    $rows = $stmt->fetchAll();

    // Compute totals grouped by entry_type
    $intake  = 0;
    $output  = 0;
    $byShift = [];
    foreach ($rows as $r) {
        $type = $r['entry_type'];
        $vol  = (int)$r['volume_ml'];
        $s    = $r['shift'];
        if (!isset($byShift[$s])) {
            $byShift[$s] = ['intake' => 0, 'output' => 0];
        }
        if (in_array($type, ['oral_intake', 'iv_intake'], true)) {
            $intake += $vol;
            $byShift[$s]['intake'] += $vol;
        } else {
            $output += $vol;
            $byShift[$s]['output'] += $vol;
        }
    }

    json_response([
        'ok'       => true,
        'entries'  => $rows,
        'totals'   => ['intake' => $intake, 'output' => $output, 'net' => $intake - $output],
        'by_shift' => $byShift,
    ]);
} catch (Throwable $e) {
    json_response(['ok' => false, 'error' => $e->getMessage()], 500);
}
