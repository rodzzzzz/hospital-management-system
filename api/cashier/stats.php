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
    ensure_cashier_tables($pdo);

    $todayRevenueStmt = $pdo->query("SELECT COALESCE(SUM(amount - COALESCE(change_amount, 0)), 0) AS total FROM cashier_payments WHERE DATE(created_at) = CURDATE()");
    $todayRevenueRow = $todayRevenueStmt->fetch();
    $todayRevenue = (float)($todayRevenueRow['total'] ?? 0);

    $monthRevenueStmt = $pdo->query("SELECT COALESCE(SUM(amount - COALESCE(change_amount, 0)), 0) AS total FROM cashier_payments WHERE YEAR(created_at) = YEAR(CURDATE()) AND MONTH(created_at) = MONTH(CURDATE())");
    $monthRevenueRow = $monthRevenueStmt->fetch();
    $monthRevenue = (float)($monthRevenueRow['total'] ?? 0);

    $pendingChargesStmt = $pdo->query("SELECT COUNT(*) AS cnt FROM cashier_charges WHERE status = 'pending_invoice'");
    $pendingChargesRow = $pendingChargesStmt->fetch();
    $pendingCharges = (int)($pendingChargesRow['cnt'] ?? 0);

    // Hourly transaction volume (today) - bucketed
    $hourlyStmt = $pdo->query("SELECT HOUR(created_at) AS hr, COUNT(*) AS cnt FROM cashier_payments WHERE DATE(created_at) = CURDATE() GROUP BY HOUR(created_at) ORDER BY hr");
    $hours = [];
    foreach ($hourlyStmt->fetchAll() as $r) {
        $h = (int)($r['hr'] ?? -1);
        $c = (int)($r['cnt'] ?? 0);
        if ($h >= 0 && $h <= 23) {
            $hours[$h] = $c;
        }
    }

    $bucketLabels = ['8-10am', '10-12pm', '12-2pm', '2-4pm', '4-6pm', '6-8pm'];
    $bucketRanges = [
        [8, 9],
        [10, 11],
        [12, 13],
        [14, 15],
        [16, 17],
        [18, 19],
    ];
    $bucketCounts = [];
    foreach ($bucketRanges as $rng) {
        [$a, $b] = $rng;
        $sum = 0;
        for ($h = $a; $h <= $b; $h++) {
            $sum += (int)($hours[$h] ?? 0);
        }
        $bucketCounts[] = $sum;
    }

    // Revenue by Department (based on charge source_module)
    $deptStmt = $pdo->query(
        "SELECT COALESCE(c.source_module, 'other') AS source_module, COALESCE(SUM(p.amount - COALESCE(p.change_amount, 0)), 0) AS total
         FROM cashier_payments p
         JOIN cashier_invoices i ON i.id = p.invoice_id
         LEFT JOIN cashier_charges c ON c.id = i.charge_id
         WHERE YEAR(p.created_at) = YEAR(CURDATE()) AND MONTH(p.created_at) = MONTH(CURDATE())
         GROUP BY COALESCE(c.source_module, 'other')"
    );
    $deptMap = [];
    foreach ($deptStmt->fetchAll() as $r) {
        $k = (string)($r['source_module'] ?? 'other');
        $deptMap[$k] = (float)($r['total'] ?? 0);
    }

    $deptLabel = function (string $k): string {
        if ($k === 'pharmacy_resit') return 'Pharmacy';
        if ($k === 'laboratory') return 'Lab Tests';
        if ($k === 'radiology') return 'Radiology';
        if ($k === 'consultation') return 'Consultation';
        return 'Other';
    };

    $deptLabels = [];
    $deptSeries = [];
    foreach ($deptMap as $k => $v) {
        $deptLabels[] = $deptLabel($k);
        $deptSeries[] = (float)$v;
    }
    if (count($deptLabels) === 0) {
        $deptLabels = ['Pharmacy', 'Consultation', 'Lab Tests', 'Radiology', 'Other'];
        $deptSeries = [0, 0, 0, 0, 0];
    }

    // Bill status overview
    $statusStmt = $pdo->query("SELECT status, COUNT(*) AS cnt FROM cashier_invoices GROUP BY status");
    $statusMap = ['paid' => 0, 'partial' => 0, 'unpaid' => 0];
    foreach ($statusStmt->fetchAll() as $r) {
        $k = (string)($r['status'] ?? '');
        if ($k === '') continue;
        $statusMap[$k] = (int)($r['cnt'] ?? 0);
    }

    // Payment method distribution (month)
    $methodStmt = $pdo->query("SELECT method, COALESCE(SUM(amount - COALESCE(change_amount, 0)), 0) AS total FROM cashier_payments WHERE YEAR(created_at) = YEAR(CURDATE()) AND MONTH(created_at) = MONTH(CURDATE()) GROUP BY method");
    $methods = [];
    foreach ($methodStmt->fetchAll() as $r) {
        $m = (string)($r['method'] ?? 'cash');
        $methods[$m] = (float)($r['total'] ?? 0);
    }
    $methodLabels = [];
    $methodSeries = [];
    foreach ($methods as $k => $v) {
        $methodLabels[] = $k;
        $methodSeries[] = (float)$v;
    }
    if (count($methodLabels) === 0) {
        $methodLabels = ['cash', 'credit_card', 'insurance'];
        $methodSeries = [0, 0, 0];
    }

    json_response([
        'ok' => true,
        'cards' => [
            'today_revenue' => number_format($todayRevenue, 2, '.', ''),
            'pending_charges' => $pendingCharges,
            'month_revenue' => number_format($monthRevenue, 2, '.', ''),
            'voided_today' => 0,
        ],
        'charts' => [
            'hourly_volume' => [
                'labels' => $bucketLabels,
                'series' => $bucketCounts,
            ],
            'department_revenue' => [
                'labels' => $deptLabels,
                'series' => $deptSeries,
            ],
            'bill_status' => [
                'labels' => ['Paid', 'Partial', 'Unpaid'],
                'series' => [$statusMap['paid'] ?? 0, $statusMap['partial'] ?? 0, $statusMap['unpaid'] ?? 0],
            ],
            'payment_method' => [
                'labels' => $methodLabels,
                'series' => $methodSeries,
            ],
        ],
    ]);
} catch (Throwable $e) {
    json_response(['ok' => false, 'error' => $e->getMessage()], 500);
}
