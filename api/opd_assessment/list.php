<?php
declare(strict_types=1);

ini_set('display_errors', '0');
ini_set('html_errors', '0');
error_reporting(0);
ob_start();

require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';
require_once __DIR__ . '/_tables.php';

require_method('GET');

try {
    $all = isset($_GET['all']) && (string)$_GET['all'] === '1';
    $appointmentId = null;
    $patientId = null;
    if (!$all) {
        $pidRaw = $_GET['patient_id'] ?? null;
        if ($pidRaw !== null && $pidRaw !== '') {
            if (!ctype_digit((string)$pidRaw)) {
                json_response(['ok' => false, 'error' => 'Invalid patient_id'], 400);
            }
            $patientId = (int)$pidRaw;
        }

        if ($patientId === null) {
            $apptIdRaw = $_GET['appointment_id'] ?? null;
            if ($apptIdRaw === null || $apptIdRaw === '' || !ctype_digit((string)$apptIdRaw)) {
                json_response(['ok' => false, 'error' => 'Missing or invalid appointment_id'], 400);
            }
            $appointmentId = (int)$apptIdRaw;
        }
    }

    $q = trim((string)($_GET['q'] ?? ''));

    $pdo = db();
    ensure_opd_assessment_tables($pdo);

    if ($all) {
        $where = '';
        $params = [];
        if ($q !== '') {
            $where = 'WHERE p.full_name LIKE :q_name OR p.patient_code LIKE :q_code';
            $params['q_name'] = '%' . $q . '%';
            $params['q_code'] = '%' . $q . '%';
        }
        $stmt = $pdo->prepare(
            'SELECT a.*, p.patient_code, p.full_name
             FROM opd_nursing_assessments a
             JOIN patients p ON p.id = a.patient_id
             ' . $where . '
             ORDER BY a.created_at DESC, a.id DESC
             LIMIT 500'
        );
        $stmt->execute($params);
    } else if ($patientId !== null) {
        $stmt = $pdo->prepare(
            'SELECT a.*, p.patient_code, p.full_name
             FROM opd_nursing_assessments a
             JOIN patients p ON p.id = a.patient_id
             WHERE a.patient_id = :patient_id
             ORDER BY a.created_at DESC, a.id DESC
             LIMIT 200'
        );
        $stmt->execute(['patient_id' => $patientId]);
    } else {
        $stmt = $pdo->prepare(
            'SELECT a.*, p.patient_code, p.full_name
             FROM opd_nursing_assessments a
             JOIN patients p ON p.id = a.patient_id
             WHERE a.appointment_id = :appointment_id
             ORDER BY a.created_at DESC, a.id DESC'
        );
        $stmt->execute(['appointment_id' => $appointmentId]);
    }
    $rows = $stmt->fetchAll();

    if (ob_get_length()) {
        ob_clean();
    }
    json_response(['ok' => true, 'assessments' => $rows]);
} catch (Throwable $e) {
    if (ob_get_length()) {
        ob_clean();
    }
    json_response(['ok' => false, 'error' => $e->getMessage()], 200);
}
