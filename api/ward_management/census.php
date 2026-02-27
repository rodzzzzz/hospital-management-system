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

    $today = date('Y-m-d');

    // Per-ward counts of currently admitted patients
    $wardStmt = $pdo->query(
        "SELECT ward,
                COUNT(*) AS total_admitted,
                SUM(CASE WHEN DATE(admission_date) = CURDATE() THEN 1 ELSE 0 END) AS admitted_today,
                SUM(CASE WHEN DATE(discharge_date) = CURDATE() THEN 1 ELSE 0 END) AS discharged_today,
                ROUND(AVG(DATEDIFF(COALESCE(discharge_date, NOW()), admission_date)), 1) AS avg_los_days
         FROM admissions
         WHERE status = 'admitted'
         GROUP BY ward
         ORDER BY ward"
    );
    $wardCounts = $wardStmt->fetchAll();

    // Totals
    $totals = [
        'total_admitted'   => 0,
        'admitted_today'   => 0,
        'discharged_today' => 0,
    ];
    foreach ($wardCounts as $row) {
        $totals['total_admitted']   += (int)$row['total_admitted'];
        $totals['admitted_today']   += (int)$row['admitted_today'];
        $totals['discharged_today'] += (int)$row['discharged_today'];
    }

    // Full admitted patient list with LOS
    $patStmt = $pdo->query(
        "SELECT a.id AS admission_id, a.admission_no, a.ward, a.room_no,
                a.admitting_physician, a.admitting_diagnosis, a.admission_date,
                a.discharge_date, a.status, a.allergy_notes, a.diet_notes,
                DATEDIFF(COALESCE(a.discharge_date, NOW()), a.admission_date) AS los_days,
                p.id AS patient_id, p.full_name, p.patient_code, p.dob, p.sex,
                b.bed_code,
                aa.fall_risk, aa.code_status
         FROM admissions a
         JOIN patients p ON p.id = a.patient_id
         LEFT JOIN ward_beds b ON b.current_admission_id = a.id
         LEFT JOIN admission_assessments aa ON aa.admission_id = a.id
         WHERE a.status = 'admitted'
         ORDER BY a.ward, a.admission_date ASC
         LIMIT 500"
    );
    $patients = $patStmt->fetchAll();

    // Admissions per day for the last 7 days (for dashboard chart)
    $chartStmt = $pdo->query(
        "SELECT DATE(admission_date) AS day, COUNT(*) AS count
         FROM admissions
         WHERE admission_date >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
         GROUP BY DATE(admission_date)
         ORDER BY day"
    );
    $admissionsChart = $chartStmt->fetchAll();

    json_response([
        'ok'              => true,
        'ward_counts'     => $wardCounts,
        'totals'          => $totals,
        'patients'        => $patients,
        'admissions_chart'=> $admissionsChart,
    ]);
} catch (Throwable $e) {
    json_response(['ok' => false, 'error' => $e->getMessage()], 500);
}
