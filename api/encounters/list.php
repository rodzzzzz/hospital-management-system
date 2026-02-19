<?php
declare(strict_types=1);

require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';
require_once __DIR__ . '/_tables.php';
require_once __DIR__ . '/../lab/_tables.php';
require_once __DIR__ . '/../pharmacy/_tables.php';
require_once __DIR__ . '/../cashier/_tables.php';

require_method('GET');

try {
    $pdo = db();
    ensure_encounter_tables($pdo);
    ensure_lab_tables($pdo);
    ensure_pharmacy_tables($pdo);
    ensure_cashier_tables($pdo);

    $patientIdRaw = $_GET['patient_id'] ?? null;
    $patientId = null;
    if (is_int($patientIdRaw)) {
        $patientId = $patientIdRaw;
    } elseif (is_string($patientIdRaw) && ctype_digit($patientIdRaw)) {
        $patientId = (int)$patientIdRaw;
    }

    $status = strtolower(trim((string)($_GET['status'] ?? '')));
    $type = strtoupper(trim((string)($_GET['type'] ?? '')));

    $where = [];
    $params = [];

    if ($patientId !== null) {
        $where[] = 'e.patient_id = :patient_id';
        $params['patient_id'] = $patientId;
    }

    if ($status !== '' && in_array($status, ['open', 'closed', 'cancelled'], true)) {
        $where[] = 'e.status = :status';
        $params['status'] = $status;
    }

    if ($type !== '' && in_array($type, ['ER', 'OPD', 'IPD', 'PHARMACY'], true)) {
        $where[] = 'e.type = :type';
        $params['type'] = $type;
    }

    $sqlWhere = count($where) ? ('WHERE ' . implode(' AND ', $where)) : '';

    $stmt = $pdo->prepare(
        "SELECT
            e.id,
            e.encounter_no,
            e.patient_id,
            p.patient_code,
            p.full_name,
            e.type,
            e.status,
            e.started_at,
            e.ended_at,
            e.created_at,
            e.updated_at,
            COALESCE(er.er_forms_count, 0) AS er_forms_count,
            COALESCE(lab.lab_results_count, 0) AS lab_results_count,
            COALESCE(res.resits_count, 0) AS resits_count,
            COALESCE(chg.charges_count, 0) AS charges_count,
            COALESCE(inv.invoices_count, 0) AS invoices_count,
            COALESCE(pay.payments_count, 0) AS payments_count
         FROM encounters e
         JOIN patients p ON p.id = e.patient_id
         LEFT JOIN (
            SELECT encounter_id, COUNT(*) AS er_forms_count
            FROM lab_requests
            WHERE encounter_id IS NOT NULL AND source_unit = 'ER'
            GROUP BY encounter_id
         ) er ON er.encounter_id = e.id
         LEFT JOIN (
            SELECT r.encounter_id, COUNT(DISTINCT r.id) AS lab_results_count
            FROM lab_requests r
            JOIN lab_request_items i ON i.request_id = r.id
            JOIN lab_results res ON res.request_item_id = i.id
            WHERE r.encounter_id IS NOT NULL AND COALESCE(res.released_at, '') <> ''
            GROUP BY r.encounter_id
         ) lab ON lab.encounter_id = e.id
         LEFT JOIN (
            SELECT encounter_id, COUNT(*) AS resits_count
            FROM pharmacy_resits
            WHERE encounter_id IS NOT NULL
            GROUP BY encounter_id
         ) res ON res.encounter_id = e.id
         LEFT JOIN (
            SELECT encounter_id, COUNT(*) AS charges_count
            FROM cashier_charges
            WHERE encounter_id IS NOT NULL
            GROUP BY encounter_id
         ) chg ON chg.encounter_id = e.id
         LEFT JOIN (
            SELECT encounter_id, COUNT(*) AS invoices_count
            FROM cashier_invoices
            WHERE encounter_id IS NOT NULL
            GROUP BY encounter_id
         ) inv ON inv.encounter_id = e.id
         LEFT JOIN (
            SELECT inv.encounter_id, COUNT(*) AS payments_count
            FROM cashier_payments pay
            JOIN cashier_invoices inv ON inv.id = pay.invoice_id
            WHERE inv.encounter_id IS NOT NULL
            GROUP BY inv.encounter_id
         ) pay ON pay.encounter_id = e.id
         {$sqlWhere}
         ORDER BY e.started_at DESC, e.id DESC
         LIMIT 200"
    );
    $stmt->execute($params);

    json_response([
        'ok' => true,
        'encounters' => $stmt->fetchAll(),
    ]);
} catch (Throwable $e) {
    json_response([
        'ok' => false,
        'error' => $e->getMessage(),
    ], 500);
}
