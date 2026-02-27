<?php
declare(strict_types=1);

require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';
require_once __DIR__ . '/../_cors.php';
require_once __DIR__ . '/../lab/_tables.php';
require_once __DIR__ . '/../pharmacy/_tables.php';
require_once __DIR__ . '/../cashier/_tables.php';

cors_headers();
require_method('GET');

try {
    $pdo = db();

    ensure_lab_tables($pdo);
    ensure_pharmacy_tables($pdo);
    ensure_cashier_tables($pdo);

    $q = trim((string)($_GET['q'] ?? ''));
    $idRaw = $_GET['id'] ?? null;
    $patientId = null;
    if ($idRaw !== null && $idRaw !== '' && ctype_digit((string)$idRaw)) {
        $patientId = (int)$idRaw;
        if ($patientId <= 0) {
            $patientId = null;
        }
    }
    $limit = 200;

    $baseSelect = "SELECT
            p.id,
            p.patient_code,
            p.full_name,
            p.dob,
            p.sex,
            p.contact,
            p.email,
            p.civil_status,
            p.patient_type,
            p.initial_location,
            p.department,
            p.philhealth_pin,
            p.updated_at,
            (SELECT lr.status FROM lab_requests lr WHERE lr.patient_id = p.id ORDER BY lr.updated_at DESC, lr.id DESC LIMIT 1) AS lab_status,
            (SELECT lr.updated_at FROM lab_requests lr WHERE lr.patient_id = p.id ORDER BY lr.updated_at DESC, lr.id DESC LIMIT 1) AS lab_updated_at,
            (SELECT COUNT(*) FROM pharmacy_resits pr WHERE pr.patient_id = p.id) AS pharmacy_resits_count,
            (SELECT pr.updated_at FROM pharmacy_resits pr WHERE pr.patient_id = p.id ORDER BY pr.updated_at DESC, pr.id DESC LIMIT 1) AS pharmacy_resits_updated_at,
            (SELECT cc.status FROM cashier_charges cc WHERE cc.patient_id = p.id ORDER BY cc.updated_at DESC, cc.id DESC LIMIT 1) AS charge_status,
            (SELECT cc.updated_at FROM cashier_charges cc WHERE cc.patient_id = p.id ORDER BY cc.updated_at DESC, cc.id DESC LIMIT 1) AS charge_updated_at,
            (SELECT COUNT(*) FROM cashier_charges cc WHERE cc.patient_id = p.id) AS charges_count,
            (SELECT ci.status FROM cashier_invoices ci WHERE ci.patient_id = p.id ORDER BY ci.updated_at DESC, ci.id DESC LIMIT 1) AS invoice_status,
            (SELECT ci.updated_at FROM cashier_invoices ci WHERE ci.patient_id = p.id ORDER BY ci.updated_at DESC, ci.id DESC LIMIT 1) AS invoice_updated_at
        FROM patients p";

    if ($patientId !== null) {
        $stmt = $pdo->prepare(
            $baseSelect .
            "
             WHERE p.id = :id
             LIMIT 1"
        );
        $stmt->execute(['id' => $patientId]);
        $rows = $stmt->fetchAll();
    } elseif ($q !== '') {
        $qLower = strtolower($q);
        $stmt = $pdo->prepare(
            $baseSelect .
            "
             WHERE LOWER(p.full_name) LIKE :q_like_fn OR LOWER(p.patient_code) LIKE :q_like_code OR LOWER(p.philhealth_pin) LIKE :q_like_pin
             ORDER BY
                CASE
                    WHEN LOWER(p.patient_code) LIKE :q_prefix_code THEN 0
                    WHEN LOWER(p.patient_code) LIKE :q_like_code_order THEN 1
                    WHEN LOWER(p.full_name) LIKE :q_prefix_fn THEN 2
                    WHEN LOWER(p.full_name) LIKE :q_like_fn_order THEN 3
                    ELSE 4
                END,
                p.updated_at DESC
             LIMIT {$limit}"
        );
        $stmt->execute([
            'q_like_fn' => '%' . $qLower . '%',
            'q_like_code' => '%' . $qLower . '%',
            'q_like_pin' => '%' . $qLower . '%',
            'q_prefix_code' => $qLower . '%',
            'q_like_code_order' => '%' . $qLower . '%',
            'q_prefix_fn' => $qLower . '%',
            'q_like_fn_order' => '%' . $qLower . '%',
        ]);
        $rows = $stmt->fetchAll();
    } else {
        $stmt = $pdo->query(
            $baseSelect .
            "
             ORDER BY p.updated_at DESC
             LIMIT {$limit}"
        );
        $rows = $stmt->fetchAll();
    }

    foreach ($rows as &$row) {
        $labStatus = isset($row['lab_status']) ? (string)$row['lab_status'] : '';
        $labUpdatedAt = $row['lab_updated_at'] ?? null;
        $resitsCount = (int)($row['pharmacy_resits_count'] ?? 0);
        $resitsUpdatedAt = $row['pharmacy_resits_updated_at'] ?? null;
        $chargeStatus = isset($row['charge_status']) ? (string)$row['charge_status'] : '';
        $chargeUpdatedAt = $row['charge_updated_at'] ?? null;
        $chargesCount = (int)($row['charges_count'] ?? 0);
        $invoiceStatus = isset($row['invoice_status']) ? (string)$row['invoice_status'] : '';
        $invoiceUpdatedAt = $row['invoice_updated_at'] ?? null;

        $doneLabels = [];
        $progressStatus = 'Registered';
        $nextProcedure = 'Lab';
        $progressTime = $row['updated_at'] ?? null;

        $labHas = $labStatus !== '';
        $chargeHas = $chargesCount > 0 || $chargeStatus !== '';
        $invoiceHas = $invoiceStatus !== '';

        if ($labHas && strtolower($labStatus) === 'completed') $doneLabels[] = 'Done Lab Test';
        if ($resitsCount > 0) $doneLabels[] = 'Done Resit';

        if ($invoiceHas) {
            $doneLabels[] = 'Done Billing';
            if (strtolower($invoiceStatus) === 'paid') {
                $doneLabels[] = 'Done Payment';
                $progressStatus = 'Completed';
                $nextProcedure = '-';
            } else {
                $progressStatus = 'Billing: ' . $invoiceStatus;
                $nextProcedure = 'Payment';
            }
            if ($invoiceUpdatedAt) $progressTime = $invoiceUpdatedAt;
        } elseif ($chargeHas) {
            $progressStatus = 'Billing: Pending Invoice';
            $nextProcedure = 'Billing';
            if ($chargeUpdatedAt) $progressTime = $chargeUpdatedAt;
        } elseif ($resitsCount > 0) {
            $progressStatus = 'Awaiting Billing';
            $nextProcedure = 'Billing';
            if ($resitsUpdatedAt) $progressTime = $resitsUpdatedAt;
        } elseif ($labHas) {
            $ls = strtolower($labStatus);
            if ($ls === 'completed') {
                $progressStatus = 'Lab Completed';
                $nextProcedure = 'Resit';
            } elseif ($ls === 'in_progress') {
                $progressStatus = 'Lab: In Progress';
                $nextProcedure = 'Lab';
            } elseif ($ls === 'collected') {
                $progressStatus = 'Lab: Collected';
                $nextProcedure = 'Lab';
            } elseif ($ls === 'approved') {
                $progressStatus = 'Lab: Approved';
                $nextProcedure = 'Lab';
            } elseif ($ls === 'pending_approval') {
                $progressStatus = 'Lab: Pending Approval';
                $nextProcedure = 'Lab';
            } elseif ($ls === 'rejected') {
                $progressStatus = 'Lab: Rejected';
                $nextProcedure = 'Lab';
            } elseif ($ls === 'cancelled') {
                $progressStatus = 'Lab: Cancelled';
                $nextProcedure = '-';
            } else {
                $progressStatus = 'Lab: ' . $labStatus;
                $nextProcedure = 'Lab';
            }
            if ($labUpdatedAt) $progressTime = $labUpdatedAt;
        }

        $row['progress_status'] = $progressStatus;
        $row['done_process'] = count($doneLabels) ? implode(' • ', $doneLabels) : '-';
        $row['next_procedure'] = $nextProcedure;
        $row['progress_time'] = $progressTime;
    }
    unset($row);

    json_response([
        'ok' => true,
        'patients' => $rows,
    ]);
} catch (Throwable $e) {
    json_response([
        'ok' => false,
        'error' => $e->getMessage(),
    ], 500);
}
