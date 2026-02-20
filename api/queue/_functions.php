<?php
declare(strict_types=1);

require_once __DIR__ . '/../_db.php';

/**
 * Get queue station by ID or name
 */
function getQueueStation(PDO $pdo, $identifier): ?array
{
    if (is_numeric($identifier)) {
        $stmt = $pdo->prepare("SELECT * FROM queue_stations WHERE id = ? AND is_active = 1");
        $stmt->execute([$identifier]);
    } else {
        $stmt = $pdo->prepare("SELECT * FROM queue_stations WHERE station_name = ? AND is_active = 1");
        $stmt->execute([$identifier]);
    }
    return $stmt->fetch() ?: null;
}

/**
 * Get all active queue stations
 */
function getAllQueueStations(PDO $pdo): array
{
    $stmt = $pdo->prepare("SELECT * FROM queue_stations WHERE is_active = 1 ORDER BY station_order");
    $stmt->execute();
    return $stmt->fetchAll();
}

/**
 * Get next queue number for a station (resets daily)
 */
function getNextQueueNumber(PDO $pdo, int $stationId): int
{
    $today = date('Y-m-d');
    $stmt = $pdo->prepare("
        SELECT MAX(queue_number) as max_number 
        FROM patient_queue 
        WHERE station_id = ? AND DATE(arrived_at) = ?
    ");
    $stmt->execute([$stationId, $today]);
    $result = $stmt->fetch();
    return ($result['max_number'] ?? 0) + 1;
}

/**
 * Get next position in queue for a station
 */
function getNextQueuePosition(PDO $pdo, int $stationId): int
{
    $stmt = $pdo->prepare("
        SELECT MAX(queue_position) as max_position 
        FROM patient_queue 
        WHERE station_id = ? AND status IN ('waiting', 'in_progress')
    ");
    $stmt->execute([$stationId]);
    $result = $stmt->fetch();
    return ($result['max_position'] ?? 0) + 1;
}

/**
 * Add patient to queue
 */
function addPatientToQueue(PDO $pdo, int $patientId, int $stationId, ?int $staffUserId = null): int
{
    $station = getQueueStation($pdo, $stationId);
    if (!$station) {
        throw new Exception("Invalid station");
    }

    $queueNumber = getNextQueueNumber($pdo, $stationId);
    $queuePosition = getNextQueuePosition($pdo, $stationId);

    $stmt = $pdo->prepare("
        INSERT INTO patient_queue 
        (patient_id, station_id, queue_number, queue_position, status, staff_user_id)
        VALUES (?, ?, ?, ?, 'waiting', ?)
    ");
    $stmt->execute([$patientId, $stationId, $queueNumber, $queuePosition, $staffUserId]);
    
    return (int)$pdo->lastInsertId();
}

/**
 * Get currently serving patient at a station
 */
function getCurrentlyServing(PDO $pdo, int $stationId): ?array
{
    $stmt = $pdo->prepare("
        SELECT pq.*, p.full_name, p.patient_code, u.full_name as staff_name
        FROM patient_queue pq
        LEFT JOIN patients p ON pq.patient_id = p.id
        LEFT JOIN users u ON pq.staff_user_id = u.id
        WHERE pq.station_id = ? AND pq.status = 'in_progress'
        ORDER BY pq.started_at ASC
        LIMIT 1
    ");
    $stmt->execute([$stationId]);
    return $stmt->fetch() ?: null;
}

/**
 * Call next patient and mark current patient as unavailable
 */
function callNextAndMarkUnavailable(PDO $pdo, int $stationId, int $staffUserId): ?array
{
    $pdo->beginTransaction();
    
    try {
        // Get currently serving patient
        $currentlyServing = getCurrentlyServing($pdo, $stationId);
        
        // If no patient is currently being served, don't allow this operation
        if (!$currentlyServing) {
            $pdo->rollBack();
            throw new Exception('Cannot call next and mark unavailable: No patient is currently being served at this station');
        }
        
        // Get next waiting patient
        $stmt = $pdo->prepare("
            SELECT pq.*, p.full_name, p.patient_code
            FROM patient_queue pq
            LEFT JOIN patients p ON pq.patient_id = p.id
            WHERE pq.station_id = ? AND pq.status = 'waiting'
            ORDER BY pq.queue_position ASC
            LIMIT 1
        ");
        $stmt->execute([$stationId]);
        $nextPatient = $stmt->fetch();
        
        if (!$nextPatient) {
            $pdo->rollBack();
            return null;
        }
        
        // Mark current patient as unavailable
        $markUnavailableStmt = $pdo->prepare("
            UPDATE patient_queue 
            SET status = 'unavailable',
                updated_at = NOW()
            WHERE id = ? AND status = 'in_progress'
        ");
        $markUnavailableStmt->execute([$currentlyServing['id']]);
        
        // Reorder remaining patients
        $reorderStmt = $pdo->prepare("
            UPDATE patient_queue 
            SET queue_position = queue_position - 1
            WHERE station_id = ? AND status = 'waiting' AND queue_position > ?
        ");
        $reorderStmt->execute([$stationId, $currentlyServing['queue_position']]);
        
        // Mark next patient as in_progress
        $updateStmt = $pdo->prepare("
            UPDATE patient_queue 
            SET status = 'in_progress', started_at = NOW(), staff_user_id = ?
            WHERE id = ?
        ");
        $updateStmt->execute([$staffUserId, $nextPatient['id']]);
        
        $pdo->commit();
        
        $nextPatient['status'] = 'in_progress';
        $nextPatient['started_at'] = date('Y-m-d H:i:s');
        $nextPatient['staff_user_id'] = $staffUserId;
        
        return $nextPatient;
    } catch (Exception $e) {
        $pdo->rollBack();
        throw $e;
    }
}

/**
 * Call next patient in queue
 */
function callNextPatient(PDO $pdo, int $stationId, int $staffUserId): ?array
{
    $pdo->beginTransaction();
    
    try {
        // Check if there's already a patient in progress at this station
        $inProgressStmt = $pdo->prepare("
            SELECT COUNT(*) as count
            FROM patient_queue 
            WHERE station_id = ? AND status = 'in_progress'
        ");
        $inProgressStmt->execute([$stationId]);
        $hasInProgress = $inProgressStmt->fetch()['count'] > 0;
        
        if ($hasInProgress) {
            $pdo->rollBack();
            throw new Exception('Cannot call next patient: There is already a patient being served at this station');
        }
        
        // Get next waiting patient
        $stmt = $pdo->prepare("
            SELECT pq.*, p.full_name, p.patient_code
            FROM patient_queue pq
            LEFT JOIN patients p ON pq.patient_id = p.id
            WHERE pq.station_id = ? AND pq.status = 'waiting'
            ORDER BY pq.queue_position ASC
            LIMIT 1
        ");
        $stmt->execute([$stationId]);
        $patient = $stmt->fetch();
        
        if (!$patient) {
            $pdo->rollBack();
            return null;
        }

        // Mark as in_progress
        $updateStmt = $pdo->prepare("
            UPDATE patient_queue 
            SET status = 'in_progress', started_at = NOW(), staff_user_id = ?
            WHERE id = ?
        ");
        $updateStmt->execute([$staffUserId, $patient['id']]);
        
        $pdo->commit();
        
        $patient['status'] = 'in_progress';
        $patient['started_at'] = date('Y-m-d H:i:s');
        $patient['staff_user_id'] = $staffUserId;
        
        return $patient;
    } catch (Exception $e) {
        $pdo->rollBack();
        throw $e;
    }
}

/**
 * Get display data for station screen
 */
function getStationDisplayData(PDO $pdo, int $stationId): array
{
    $station = getQueueStation($pdo, $stationId);
    if (!$station) {
        throw new Exception("Station not found");
    }

    // Get currently serving
    $currentlyServing = getCurrentlyServing($pdo, $stationId);
    
    // Get next patients (up to 10)
    $stmt = $pdo->prepare("
        SELECT pq.*, p.full_name, p.patient_code
        FROM patient_queue pq
        LEFT JOIN patients p ON pq.patient_id = p.id
        WHERE pq.station_id = ? AND pq.status = 'waiting'
        ORDER BY pq.queue_position ASC
        LIMIT 10
    ");
    $stmt->execute([$stationId]);
    $nextPatients = $stmt->fetchAll();
    
    // Get unavailable patients
    $unavailablePatients = getUnavailablePatients($pdo, $stationId);
    
    // Get queue count (only waiting patients)
    $stmt = $pdo->prepare("
        SELECT COUNT(*) as count
        FROM patient_queue 
        WHERE station_id = ? AND status = 'waiting'
    ");
    $stmt->execute([$stationId]);
    $queueCount = $stmt->fetch()['count'];
    
    // Calculate estimated wait time
    $avgServiceTime = getAverageServiceTime($pdo, $stationId);
    $estimatedWaitTime = $queueCount * $avgServiceTime;
    
    return [
        'station' => $station,
        'currently_serving' => $currentlyServing,
        'next_patients' => $nextPatients,
        'unavailable_patients' => $unavailablePatients,
        'queue_count' => $queueCount,
        'estimated_wait_time' => $estimatedWaitTime,
        'current_time' => date('Y-m-d H:i:s'),
        'last_updated' => date('Y-m-d H:i:s')
    ];
}

/**
 * Get average service time for station
 */
function getAverageServiceTime(PDO $pdo, int $stationId): int
{
    $stmt = $pdo->prepare("
        SELECT setting_value as avg_time
        FROM queue_settings
        WHERE station_id = ? AND setting_key = 'average_service_time'
    ");
    $stmt->execute([$stationId]);
    $result = $stmt->fetch();
    return (int)($result['avg_time'] ?? 15);
}

/**
 * Recall unavailable patient back to queue (using status instead of availability_status)
 */
function recallUnavailablePatient(PDO $pdo, int $queueId, int $staffUserId): bool
{
    $pdo->beginTransaction();
    
    try {
        // Get next queue position for the station
        $stmt = $pdo->prepare("
            SELECT station_id 
            FROM patient_queue 
            WHERE id = ?
        ");
        $stmt->execute([$queueId]);
        $queue = $stmt->fetch();
        
        if (!$queue) {
            throw new Exception("Queue entry not found");
        }
        
        $positionStmt = $pdo->prepare("
            SELECT COALESCE(MAX(queue_position), 0) + 1 as next_position
            FROM patient_queue 
            WHERE station_id = ? AND status = 'waiting'
        ");
        $positionStmt->execute([$queue['station_id']]);
        $nextPosition = $positionStmt->fetch()['next_position'];

        // Do not allow recalling when a patient is currently in progress at this station
        $stationInProgressStmt = $pdo->prepare("
            SELECT COUNT(*) as count
            FROM patient_queue
            WHERE station_id = ? AND status = 'in_progress'
        ");
        $stationInProgressStmt->execute([$queue['station_id']]);
        $stationHasInProgress = ((int)($stationInProgressStmt->fetch()['count'] ?? 0)) > 0;

        if ($stationHasInProgress) {
            throw new Exception('Cannot recall unavailable patient: A patient is currently being served at this station');
        }

        $stmt = $pdo->prepare("
            UPDATE patient_queue
            SET status = 'in_progress',
                queue_position = ?,
                staff_user_id = ?,
                started_at = NOW(),
                updated_at = NOW()
            WHERE id = ?
        ");
        $stmt->execute([$nextPosition, $staffUserId, $queueId]);
        
        $pdo->commit();
        return true;
    } catch (Exception $e) {
        $pdo->rollBack();
        throw $e;
    }
}

/**
 * Get unavailable patients for station (using status instead of availability_status)
 */
function getUnavailablePatients(PDO $pdo, int $stationId): array
{
    $stmt = $pdo->prepare("
        SELECT pq.*, p.full_name, p.patient_code, u.full_name as staff_name
        FROM patient_queue pq
        LEFT JOIN patients p ON pq.patient_id = p.id
        LEFT JOIN users u ON pq.staff_user_id = u.id
        WHERE pq.station_id = ? AND pq.status = 'unavailable'
        ORDER BY pq.updated_at ASC
    ");
    $stmt->execute([$stationId]);
    return $stmt->fetchAll();
}

/**
 * Complete service with target station selection (update existing entry)
 */
function completeServiceWithTarget(PDO $pdo, int $queueId, int $staffUserId, ?int $targetStationId = null): bool
{
    $pdo->beginTransaction();
    
    try {
        // Get current queue entry info
        $stmt = $pdo->prepare("
            SELECT patient_id, station_id 
            FROM patient_queue 
            WHERE id = ?
        ");
        $stmt->execute([$queueId]);
        $queue = $stmt->fetch();
        
        if (!$queue) {
            throw new Exception("Queue entry not found");
        }
        
        if ($targetStationId) {
            // Transfer to target station by updating existing entry
            // Get next queue position for target station
            $positionStmt = $pdo->prepare("
                SELECT COALESCE(MAX(queue_position), 0) + 1 as next_position
                FROM patient_queue 
                WHERE station_id = ? AND status IN ('waiting', 'in_progress')
            ");
            $positionStmt->execute([$targetStationId]);
            $nextPosition = $positionStmt->fetch()['next_position'];

            // Update existing queue entry
            $updateStmt = $pdo->prepare("
                UPDATE patient_queue 
                SET station_id = ?,
                    queue_position = ?,
                    status = 'waiting',
                    completed_at = NOW(),
                    staff_user_id = ?,
                    updated_at = NOW()
                WHERE id = ?
            ");
            $updateStmt->execute([$targetStationId, $nextPosition, $staffUserId, $queueId]);
            
            // Log transfer
            $logStmt = $pdo->prepare("
                INSERT INTO queue_transfers 
                (patient_id, from_station_id, to_station_id, transferred_by, transfer_reason)
                VALUES (?, ?, ?, ?, 'manual')
            ");
            $logStmt->execute([$queue['patient_id'], $queue['station_id'], $targetStationId, $staffUserId]);
        } else {
            // Complete service (discharge) - mark as completed
            $stmt = $pdo->prepare("
                UPDATE patient_queue 
                SET status = 'completed', 
                    completed_at = NOW(),
                    staff_user_id = ?,
                    updated_at = NOW()
                WHERE id = ?
            ");
            $stmt->execute([$staffUserId, $queueId]);
        }
        
        $pdo->commit();
        return true;
    } catch (Exception $e) {
        $pdo->rollBack();
        throw $e;
    }
}
