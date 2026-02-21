<?php
declare(strict_types=1);

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/_functions.php';
require_once __DIR__ . '/../auth/_session.php';

$pdo = db();

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
            $currentUser = auth_current_user($pdo);
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
            echo json_encode(['success' => true, 'queue_id' => $queueId]);
            break;
            
        case 'call-next':
            $stationId = (int)($input['station_id'] ?? 0);
            
            if (!$stationId) {
                throw new Exception('Station ID required');
            }
            
            $patient = callNextPatient($pdo, $stationId, $currentUser['id']);
            if ($patient) {
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
            
            $success = completeServiceWithTarget($pdo, $queueId, $currentUser['id'], $targetStationId);
            echo json_encode(['success' => $success]);
            break;
            
        case 'call-next-mark-unavailable':
            $stationId = (int)($input['station_id'] ?? 0);
            
            if (!$stationId) {
                throw new Exception('Station ID required');
            }
            
            $patient = callNextAndMarkUnavailable($pdo, $stationId, $currentUser['id']);
            if ($patient) {
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
            echo json_encode(['success' => $success]);
            break;
            
        default:
            throw new Exception('Invalid endpoint');
    }
}
