<?php
declare(strict_types=1);

require_once __DIR__ . '/../_cors.php';
cors_headers();
require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/_functions.php';
require_once __DIR__ . '/../auth/_session.php';
require_once __DIR__ . '/../websocket/_broadcast.php';

$pdo = db();

// Ensure queue tables exist
function ensureQueueTables(PDO $pdo): void {
    try {
        $pdo->exec("CREATE TABLE IF NOT EXISTS queue_error_log (
            id INT(11) NOT NULL AUTO_INCREMENT,
            queue_id INT(11) NOT NULL,
            patient_id INT(11) NOT NULL,
            wrong_station_id INT(11) NOT NULL,
            correct_station_id INT(11) NOT NULL,
            reported_by INT(11) NOT NULL,
            confirmed_by INT(11) DEFAULT NULL,
            status ENUM('pending','confirmed','rejected') NOT NULL DEFAULT 'pending',
            notes TEXT DEFAULT NULL,
            reported_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            confirmed_at DATETIME DEFAULT NULL,
            PRIMARY KEY (id),
            KEY idx_wrong_station_status (wrong_station_id, status),
            KEY idx_queue_id (queue_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    } catch (Exception $e) {
        // Table might already exist, ignore
    }
}

ensureQueueTables($pdo);

$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$pathParts = explode('/', trim($path, '/'));

try {
    switch ($method) {
        case 'GET':
            // GET endpoints (display, stations) are public — no auth required
            handleGetRequest($pdo, $pathParts);
            break;
        case 'POST':
            // POST endpoints require authentication
            $currentUser = auth_current_user_optional_token($pdo);
            if (!$currentUser) {
                http_response_code(401);
                echo json_encode(['error' => 'Unauthorized']);
                exit;
            }
            handlePostRequest($pdo, $pathParts, $currentUser);
            break;
        default:
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

function handleGetRequest(PDO $pdo, array $pathParts): void
{
    // Find the 'queue' index in the path parts
    $queueIndex = array_search('queue', $pathParts);
    if ($queueIndex === false) {
        throw new Exception('Invalid API path');
    }
    
    $endpoint = $pathParts[$queueIndex + 1] ?? '';
    
    switch ($endpoint) {
        case 'stations':
            $stations = getAllQueueStations($pdo);
            echo json_encode(['stations' => $stations]);
            break;

        case 'list':
            $stationIdentifier = $pathParts[$queueIndex + 2] ?? '';
            if ($stationIdentifier === '') {
                throw new Exception('Station identifier required');
            }

            $station = getQueueStation($pdo, $stationIdentifier);
            if (!$station) {
                throw new Exception('Station not found');
            }

            $queue = getStationQueueList($pdo, (int)$station['id']);
            echo json_encode([
                'ok' => true,
                'station' => $station,
                'queue' => $queue,
            ]);
            break;
            
        case 'display':
            if (isset($pathParts[$queueIndex + 2]) && $pathParts[$queueIndex + 2] === 'all') {
                // Get all stations display data
                $stations = getAllQueueStations($pdo);
                $displayData = [];
                foreach ($stations as $station) {
                    $displayData[$station['station_name']] = getStationDisplayData($pdo, $station['id']);
                }
                echo json_encode(['displays' => $displayData]);
            } else {
                $stationId = (int)($pathParts[$queueIndex + 2] ?? 0);
                if (!$stationId) {
                    throw new Exception('Station ID required');
                }
                $displayData = getStationDisplayData($pdo, $stationId);
                echo json_encode($displayData);
            }
            break;

        case 'recent-transfers':
            $stationId = (int)($pathParts[$queueIndex + 2] ?? 0);
            if (!$stationId) {
                throw new Exception('Station ID required');
            }
            $transfers = getRecentTransfersFromStation($pdo, $stationId);
            echo json_encode(['success' => true, 'transfers' => $transfers]);
            break;

        case 'pending-corrections':
            $stationId = (int)($pathParts[$queueIndex + 2] ?? 0);
            if (!$stationId) {
                throw new Exception('Station ID required');
            }
            $corrections = getPendingCorrections($pdo, $stationId);
            echo json_encode(['success' => true, 'corrections' => $corrections]);
            break;

        case 'confirmed-corrections':
            $stationId = (int)($pathParts[$queueIndex + 2] ?? 0);
            if (!$stationId) {
                throw new Exception('Station ID required');
            }
            $corrections = getRecentConfirmedCorrections($pdo, $stationId);
            echo json_encode(['success' => true, 'corrections' => $corrections]);
            break;

        default:
            throw new Exception('Invalid endpoint');
    }
}

function handlePostRequest(PDO $pdo, array $pathParts, array $currentUser): void
{
    // Find the 'queue' index in the path parts
    $queueIndex = array_search('queue', $pathParts);
    if ($queueIndex === false) {
        throw new Exception('Invalid API path');
    }
    
    $endpoint = $pathParts[$queueIndex + 1] ?? '';
    $input = json_decode(file_get_contents('php://input'), true);
    
    switch ($endpoint) {
        case 'add':
            $patientId = (int)($input['patient_id'] ?? 0);
            $stationId = (int)($input['station_id'] ?? 0);
            
            if (!$patientId || !$stationId) {
                throw new Exception('Patient ID and Station ID required');
            }
            
            $queueId = addPatientToQueue($pdo, $patientId, $stationId, $currentUser['id']);
            broadcastQueueUpdate('patient-added', [$stationId], ['queue_id' => $queueId, 'station_id' => $stationId]);
            echo json_encode(['success' => true, 'queue_id' => $queueId]);
            break;
            
        case 'call-next':
            $stationId = (int)($input['station_id'] ?? 0);
            
            if (!$stationId) {
                throw new Exception('Station ID required');
            }
            
            $patient = callNextPatient($pdo, $stationId, $currentUser['id']);
            if ($patient) {
                broadcastQueueUpdate('call-next', [$stationId], ['patient' => $patient, 'station_id' => $stationId]);
                echo json_encode(['success' => true, 'patient' => $patient]);
            } else {
                echo json_encode(['success' => false, 'message' => 'No patients in queue']);
            }
            break;
            
        case 'complete-service':
            $queueId = (int)($input['queue_id'] ?? 0);
            $targetStationId = (int)($input['target_station_id'] ?? 0);
            
            if (!$queueId) {
                throw new Exception('Queue ID required');
            }
            
            $queue = $pdo->prepare('SELECT station_id FROM patient_queue WHERE id = ?');
            $queue->execute([$queueId]);
            $queueData = $queue->fetch();
            
            $success = completeServiceWithTarget($pdo, $queueId, $currentUser['id'], $targetStationId);
            if ($success && $queueData) {
                $affectedStations = $targetStationId ? [$queueData['station_id'], $targetStationId] : [$queueData['station_id']];
                broadcastQueueUpdate('service-completed', $affectedStations, ['queue_id' => $queueId, 'target_station_id' => $targetStationId]);
            }
            echo json_encode(['success' => $success]);
            break;
            
        case 'call-next-mark-unavailable':
            $stationId = (int)($input['station_id'] ?? 0);
            
            if (!$stationId) {
                throw new Exception('Station ID required');
            }
            
            $patient = callNextAndMarkUnavailable($pdo, $stationId, $currentUser['id']);
            if ($patient) {
                broadcastQueueUpdate('mark-unavailable', [$stationId], ['patient' => $patient, 'station_id' => $stationId]);
                echo json_encode(['success' => true, 'patient' => $patient]);
            } else {
                echo json_encode(['success' => false, 'message' => 'No patients in queue']);
            }
            break;
            
        case 'recall-unavailable':
            $queueId = (int)($input['queue_id'] ?? 0);
            
            if (!$queueId) {
                throw new Exception('Queue ID required');
            }
            
            $success = recallUnavailablePatient($pdo, $queueId, $currentUser['id']);
            if ($success) {
                $queue = $pdo->prepare('SELECT station_id FROM patient_queue WHERE id = ?');
                $queue->execute([$queueId]);
                $queueData = $queue->fetch();
                if ($queueData) {
                    broadcastQueueUpdate('recall-unavailable', [$queueData['station_id']], ['queue_id' => $queueId]);
                }
            }
            echo json_encode(['success' => $success]);
            break;

        case 'report-error':
            $queueId = (int)($input['queue_id'] ?? 0);
            $patientId = (int)($input['patient_id'] ?? 0);
            $wrongStationId = (int)($input['wrong_station_id'] ?? 0);
            $correctStationId = (int)($input['correct_station_id'] ?? 0);
            $notes = $input['notes'] ?? null;

            if (!$queueId || !$patientId || !$wrongStationId || !$correctStationId) {
                throw new Exception('queue_id, patient_id, wrong_station_id, and correct_station_id are required');
            }

            $errorLogId = reportQueueError($pdo, $queueId, $patientId, $wrongStationId, $correctStationId, $currentUser['id'], $notes);
            
            $correctionData = getPendingCorrections($pdo, $wrongStationId);
            if (!empty($correctionData)) {
                broadcastCorrectionAlert($wrongStationId, $correctionData[0]);
            }
            
            echo json_encode(['success' => true, 'error_log_id' => $errorLogId]);
            break;

        case 'confirm-correction':
            $errorLogId = (int)($input['error_log_id'] ?? 0);

            if (!$errorLogId) {
                throw new Exception('error_log_id is required');
            }

            $result = confirmQueueCorrection($pdo, $errorLogId, $currentUser['id']);
            if ($result) {
                $affectedStations = [$result['wrong_station_id'], $result['correct_station_id']];
                broadcastQueueUpdate('correction-confirmed', $affectedStations, ['result' => $result]);
            }
            echo json_encode(['success' => true, 'result' => $result]);
            break;

        default:
            throw new Exception('Invalid endpoint');
    }
}
