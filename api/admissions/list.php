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
    ensure_admissions_tables($pdo);

    $status = $_GET['status'] ?? '';
    $ward   = $_GET['ward'] ?? '';
    $q      = trim((string)($_GET['q'] ?? ''));

    $where = ['1=1'];
    $params = [];

    if ($status !== '') {
        $where[] = 'a.status = :status';
        $params['status'] = $status;
    }
    if ($ward !== '') {
        $where[] = 'a.ward = :ward';
        $params['ward'] = $ward;
    }
    if ($q !== '') {
        $where[] = '(LOWER(p.full_name) LIKE :q OR LOWER(p.patient_code) LIKE :q OR a.admission_no LIKE :q)';
        $params['q'] = '%' . strtolower($q) . '%';
    }

    $sql = "SELECT
                a.id, a.admission_no, a.admission_type, a.ward, a.room_no,
                a.admitting_physician, a.admitting_diagnosis, a.admission_date,
                a.discharge_date, a.status, a.philhealth_pin, a.insurance_info,
                a.allergy_notes, a.diet_notes, a.special_instructions,
                a.created_at, a.updated_at,
                p.id AS patient_id, p.full_name, p.patient_code,
                p.dob, p.sex, p.contact
            FROM admissions a
            JOIN patients p ON p.id = a.patient_id
            WHERE " . implode(' AND ', $where) . "
            ORDER BY a.admission_date DESC
            LIMIT 200";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $rows = $stmt->fetchAll();

    json_response(['ok' => true, 'admissions' => $rows]);
} catch (Throwable $e) {
    json_response(['ok' => false, 'error' => $e->getMessage()], 500);
}
