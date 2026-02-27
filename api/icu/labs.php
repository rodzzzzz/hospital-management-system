<?php
declare(strict_types=1);

require_once __DIR__ . '/../_cors.php';
require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';
require_once __DIR__ . '/_schema.php';

cors_headers();
require_method('GET');

function icu_seeded_int(string $seed, int $min, int $max): int
{
    $h = crc32($seed);
    $range = max(1, $max - $min + 1);
    return $min + (int)($h % $range);
}

try {
    $pdo = db();
    icu_ensure_schema($pdo);

    $patients = $pdo->query(
        "SELECT a.id, a.patient_name, a.admitted_at, b.bed_code
           FROM icu_admissions a
           JOIN icu_beds b ON b.id = a.bed_id
          WHERE a.status = 'admitted'
          ORDER BY a.admitted_at DESC
          LIMIT 25"
    )->fetchAll();

    $tests = [
        ['name' => 'WBC', 'unit' => 'x10^9/L'],
        ['name' => 'Creatinine', 'unit' => 'mg/dL'],
        ['name' => 'Lactate', 'unit' => 'mmol/L'],
        ['name' => 'CRP', 'unit' => 'mg/L'],
    ];

    $now = new DateTimeImmutable();

    $rows = [];
    foreach (is_array($patients) ? $patients : [] as $p) {
        $pid = (int)($p['id'] ?? 0);
        $pname = (string)($p['patient_name'] ?? '');
        $bed = (string)($p['bed_code'] ?? '');

        foreach ($tests as $t) {
            $testName = (string)$t['name'];
            $unit = (string)$t['unit'];
            $seed = $pid . '|' . $testName;

            $pending = (icu_seeded_int($seed . '|pending', 0, 100) < 30);

            $flag = 'normal';
            $value = null;

            if (!$pending) {
                if ($testName === 'WBC') {
                    $value = icu_seeded_int($seed, 4, 22);
                    if ($value >= 16) $flag = 'abnormal';
                    if ($value >= 20) $flag = 'critical';
                } elseif ($testName === 'Creatinine') {
                    $value = icu_seeded_int($seed, 6, 28) / 10;
                    if ($value >= 1.6) $flag = 'abnormal';
                    if ($value >= 2.4) $flag = 'critical';
                } elseif ($testName === 'Lactate') {
                    $value = icu_seeded_int($seed, 8, 45) / 10;
                    if ($value >= 2.0) $flag = 'abnormal';
                    if ($value >= 3.8) $flag = 'critical';
                } elseif ($testName === 'CRP') {
                    $value = icu_seeded_int($seed, 3, 260);
                    if ($value >= 50) $flag = 'abnormal';
                    if ($value >= 150) $flag = 'critical';
                }
            }

            $minsAgo = icu_seeded_int($seed . '|mins', 30, 600);
            $collectedAt = $now->sub(new DateInterval('PT' . $minsAgo . 'M'))->format('Y-m-d H:i:s');

            $rows[] = [
                'patient_id' => $pid,
                'patient_name' => $pname,
                'bed_code' => $bed,
                'test_name' => $testName,
                'status' => $pending ? 'pending' : 'resulted',
                'value' => $value,
                'unit' => $unit,
                'flag' => $pending ? null : $flag,
                'collected_at' => $collectedAt,
            ];
        }
    }

    usort($rows, static function ($a, $b) {
        $aPending = (($a['status'] ?? '') === 'pending') ? 1 : 0;
        $bPending = (($b['status'] ?? '') === 'pending') ? 1 : 0;
        if ($aPending !== $bPending) return $bPending <=> $aPending;

        $aFlag = (string)($a['flag'] ?? '');
        $bFlag = (string)($b['flag'] ?? '');
        $rank = ['critical' => 3, 'abnormal' => 2, 'normal' => 1, '' => 0];
        $ra = $rank[$aFlag] ?? 0;
        $rb = $rank[$bFlag] ?? 0;
        if ($ra !== $rb) return $rb <=> $ra;

        return strcmp((string)($b['collected_at'] ?? ''), (string)($a['collected_at'] ?? ''));
    });

    json_response([
        'ok' => true,
        'labs' => $rows,
    ]);
} catch (Throwable $e) {
    json_response([
        'ok' => false,
        'error' => $e->getMessage(),
    ], 500);
}
