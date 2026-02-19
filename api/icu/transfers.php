<?php
declare(strict_types=1);

require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';
require_once __DIR__ . '/_schema.php';

require_method('GET');

function icu_seeded_pick(string $seed, array $items): string
{
    if (count($items) === 0) {
        return '';
    }
    $h = crc32($seed);
    return (string)$items[(int)($h % count($items))];
}

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
        "SELECT a.id,
                a.patient_name,
                a.admitted_at,
                a.diagnosis,
                b.bed_code
           FROM icu_admissions a
           JOIN icu_beds b ON b.id = a.bed_id
          WHERE a.status = 'admitted'
          ORDER BY a.admitted_at DESC
          LIMIT 50"
    )->fetchAll();

    $destinations = [
        'Stepdown / HDU',
        'Medical Ward',
        'Surgical Ward',
        'Telemetry Ward',
    ];

    $now = new DateTimeImmutable();

    $out = [];
    foreach (is_array($patients) ? $patients : [] as $p) {
        $pid = (int)($p['id'] ?? 0);
        $seed = 't|' . $pid;

        $stepdownCandidate = (icu_seeded_int($seed . '|cand', 0, 100) < 45);
        $transferStatus = icu_seeded_pick($seed . '|status', ['none', 'requested', 'approved']);
        if (!$stepdownCandidate) {
            $transferStatus = icu_seeded_pick($seed . '|status2', ['none', 'none', 'requested']);
        }

        $destination = ($transferStatus === 'none') ? null : icu_seeded_pick($seed . '|dest', $destinations);

        $days = icu_seeded_int($seed . '|days', 0, 3);
        $hour = icu_seeded_int($seed . '|hour', 8, 18);
        $eta = $now->add(new DateInterval('P' . $days . 'D'))
            ->setTime($hour, 0)
            ->format('Y-m-d H:i:s');

        $summary = icu_seeded_pick($seed . '|summary', ['pending', 'in_progress', 'ready']);
        if ($transferStatus === 'none') {
            $summary = icu_seeded_pick($seed . '|summary2', ['pending', 'pending', 'in_progress']);
        }

        $out[] = [
            'patient_id' => $pid,
            'patient_name' => (string)($p['patient_name'] ?? ''),
            'bed_code' => (string)($p['bed_code'] ?? ''),
            'diagnosis' => (string)($p['diagnosis'] ?? ''),
            'stepdown_candidate' => $stepdownCandidate,
            'transfer_request_status' => $transferStatus,
            'destination' => $destination,
            'eta' => $eta,
            'discharge_summary_status' => $summary,
        ];
    }

    json_response([
        'ok' => true,
        'transfers' => $out,
    ]);
} catch (Throwable $e) {
    json_response([
        'ok' => false,
        'error' => $e->getMessage(),
    ], 500);
}
