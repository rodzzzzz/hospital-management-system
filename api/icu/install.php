<?php
declare(strict_types=1);

require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';
require_once __DIR__ . '/../_cors.php';
require_once __DIR__ . '/_schema.php';

cors_headers();

try {
    $pdo = db();

    icu_ensure_schema($pdo);

    $bedsCount = (int)$pdo->query('SELECT COUNT(*) AS c FROM icu_beds')->fetch()['c'];
    if ($bedsCount === 0) {
        $pdo->beginTransaction();

        $insertBed = $pdo->prepare('INSERT INTO icu_beds (bed_code, status) VALUES (:bed_code, :status)');
        for ($i = 1; $i <= 10; $i++) {
            $code = 'ICU-' . str_pad((string)$i, 2, '0', STR_PAD_LEFT);
            $status = ($i <= 5) ? 'occupied' : 'available';
            $insertBed->execute(['bed_code' => $code, 'status' => $status]);
        }

        $beds = $pdo->query("SELECT id, bed_code FROM icu_beds ORDER BY id ASC")->fetchAll();
        $bedIds = array_map(static fn($b) => (int)($b['id'] ?? 0), is_array($beds) ? $beds : []);

        $insertAdm = $pdo->prepare(
            'INSERT INTO icu_admissions (patient_name, bed_id, admitted_at, discharged_at, status, diagnosis) VALUES (:patient_name, :bed_id, :admitted_at, :discharged_at, :status, :diagnosis)'
        );

        $now = new DateTimeImmutable();
        $patients = [
            ['patient_name' => 'Juan Dela Cruz', 'bed_idx' => 1, 'hours_ago' => 18, 'diagnosis' => 'Sepsis'],
            ['patient_name' => 'Maria Santos', 'bed_idx' => 2, 'hours_ago' => 6, 'diagnosis' => 'Post-op monitoring'],
            ['patient_name' => 'Emily White', 'bed_idx' => 3, 'hours_ago' => 30, 'diagnosis' => 'Respiratory failure'],
            ['patient_name' => 'Michael Brown', 'bed_idx' => 4, 'hours_ago' => 10, 'diagnosis' => 'Severe pneumonia'],
            ['patient_name' => 'David Chen', 'bed_idx' => 5, 'hours_ago' => 42, 'diagnosis' => 'Cardiac arrest (recovery)'],
        ];

        foreach ($patients as $p) {
            $bedId = $bedIds[$p['bed_idx'] - 1] ?? 0;
            if ($bedId <= 0) {
                continue;
            }
            $admittedAt = $now->sub(new DateInterval('PT' . (int)$p['hours_ago'] . 'H'))->format('Y-m-d H:i:s');
            $insertAdm->execute([
                'patient_name' => $p['patient_name'],
                'bed_id' => $bedId,
                'admitted_at' => $admittedAt,
                'discharged_at' => null,
                'status' => 'admitted',
                'diagnosis' => $p['diagnosis'],
            ]);
        }

        $insertAdm->execute([
            'patient_name' => 'Sarah Lee',
            'bed_id' => $bedIds[6] ?? $bedIds[0] ?? 1,
            'admitted_at' => $now->sub(new DateInterval('P4D'))->format('Y-m-d H:i:s'),
            'discharged_at' => $now->sub(new DateInterval('P2D'))->format('Y-m-d H:i:s'),
            'status' => 'discharged',
            'diagnosis' => 'DKA',
        ]);

        $pdo->commit();
    }

    json_response([
        'ok' => true,
        'message' => 'ICU tables are ready.',
    ]);
} catch (Throwable $e) {
    if (isset($pdo) && $pdo instanceof PDO && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    json_response([
        'ok' => false,
        'error' => $e->getMessage(),
    ], 500);
}
