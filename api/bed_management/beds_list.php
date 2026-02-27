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
    ensure_bed_management_tables($pdo);

    $ward   = $_GET['ward'] ?? '';
    $roomId = !empty($_GET['room_id']) ? (int)$_GET['room_id'] : null;
    $status = $_GET['status'] ?? '';

    $where = ['b.id IS NOT NULL'];
    $params = [];

    if ($ward !== '') {
        $where[] = 'b.ward = :ward';
        $params['ward'] = $ward;
    }
    if ($roomId !== null) {
        $where[] = 'b.room_id = :room_id';
        $params['room_id'] = $roomId;
    }
    if ($status !== '') {
        $where[] = 'b.status = :status';
        $params['status'] = $status;
    }

    $stmt = $pdo->prepare(
        "SELECT
            b.id, b.bed_code, b.ward, b.status, b.notes,
            b.current_admission_id, b.current_patient_id,
            b.created_at, b.updated_at,
            r.id AS room_id, r.room_no, r.room_type, r.floor,
            p.full_name AS patient_name, p.patient_code,
            a.admission_no, a.admitting_diagnosis, a.admission_date
         FROM ward_beds b
         JOIN ward_rooms r ON r.id = b.room_id
         LEFT JOIN patients p ON p.id = b.current_patient_id
         LEFT JOIN admissions a ON a.id = b.current_admission_id
         WHERE " . implode(' AND ', $where) . "
         ORDER BY r.room_no ASC, b.bed_code ASC"
    );
    $stmt->execute($params);
    $rows = $stmt->fetchAll();

    json_response(['ok' => true, 'beds' => $rows]);
} catch (Throwable $e) {
    json_response(['ok' => false, 'error' => $e->getMessage()], 500);
}
