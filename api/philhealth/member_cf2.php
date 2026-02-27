<?php
declare(strict_types=1);

require_once __DIR__ . '/../_cors.php';
require_once __DIR__ . '/_db.php';
require_once __DIR__ . '/../_response.php';

cors_headers();
require_method('GET');

try {
    $pdo = philhealth_db();

    $patientId = null;
    $pin = null;

    if (isset($_GET['patient_id']) && $_GET['patient_id'] !== '') {
        $patientId = require_int('patient_id');
    } elseif (isset($_GET['pin']) && $_GET['pin'] !== '') {
        $pin = require_string('pin');
    } else {
        json_response([
            'ok' => false,
            'error' => 'Missing patient_id or pin',
        ], 400);
    }

    if ($patientId === null) {
        $stmt = $pdo->prepare('SELECT id FROM patients WHERE philhealth_pin = :pin LIMIT 1');
        $stmt->execute(['pin' => $pin]);
        $row = $stmt->fetch();
        if (!$row || !isset($row['id'])) {
            json_response([
                'ok' => false,
                'error' => 'Member not found',
            ], 404);
        }
        $patientId = (int)$row['id'];
    }

    $stmt = $pdo->prepare('SELECT * FROM patients WHERE id = :id LIMIT 1');
    $stmt->execute(['id' => $patientId]);
    $patient = $stmt->fetch();
    if (!$patient) {
        json_response([
            'ok' => false,
            'error' => 'Member not found',
        ], 404);
    }

    $stmt = $pdo->prepare('SELECT id FROM philhealth_claims WHERE patient_id = :patient_id ORDER BY updated_at DESC LIMIT 1');
    $stmt->execute(['patient_id' => $patientId]);
    $claim = $stmt->fetch();

    $claimId = null;
    $cf2 = null;

    if ($claim && isset($claim['id'])) {
        $claimId = (int)$claim['id'];

        $stmt = $pdo->prepare('SELECT data_json FROM philhealth_forms WHERE claim_id = :claim_id AND form_code = :code LIMIT 1');
        $stmt->execute([
            'claim_id' => $claimId,
            'code' => 'cf2',
        ]);
        $form = $stmt->fetch();
        if ($form && isset($form['data_json'])) {
            $cf2 = json_decode((string)$form['data_json'], true);
            if (!is_array($cf2)) {
                $cf2 = null;
            }
        }
    }

    json_response([
        'ok' => true,
        'patient' => $patient,
        'claim_id' => $claimId,
        'cf2' => $cf2,
    ]);
} catch (Throwable $e) {
    json_response([
        'ok' => false,
        'error' => $e->getMessage(),
    ], 500);
}
