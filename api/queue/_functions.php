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

/**
 * Get recent transfers originating from a station (today only)
 * Returns each transfer enriched with the patient's current station, status, and journey trail.
 */
function getRecentTransfersFromStation(PDO $pdo, int $fromStationId): array
{
    $today = date('Y-m-d');
    $stmt = $pdo->prepare("
        SELECT qt.*, 
               p.full_name, p.patient_code,
               fs.station_display_name as from_station_name,
               ts.station_display_name as to_station_name,
               u.full_name as transferred_by_name
        FROM queue_transfers qt
        LEFT JOIN patients p ON qt.patient_id = p.id
        LEFT JOIN queue_stations fs ON qt.from_station_id = fs.id
        LEFT JOIN queue_stations ts ON qt.to_station_id = ts.id
        LEFT JOIN users u ON qt.transferred_by = u.id
        WHERE qt.from_station_id = ? AND DATE(qt.transferred_at) = ?
        ORDER BY qt.transferred_at DESC
        LIMIT 20
    ");
    $stmt->execute([$fromStationId, $today]);
    $transfers = $stmt->fetchAll();

    foreach ($transfers as &$t) {
        // Find the patient's latest queue entry to get current station and status
        $pqStmt = $pdo->prepare("
            SELECT pq.id as current_queue_id, pq.station_id as current_station_id,
                   pq.status as current_status, pq.queue_number,
                   qs.station_display_name as current_station_name
            FROM patient_queue pq
            LEFT JOIN queue_stations qs ON pq.station_id = qs.id
            WHERE pq.patient_id = ?
            ORDER BY pq.updated_at DESC
            LIMIT 1
        ");
        $pqStmt->execute([$t['patient_id']]);
        $currentEntry = $pqStmt->fetch();

        $t['current_queue_id'] = $currentEntry ? $currentEntry['current_queue_id'] : null;
        $t['current_station_id'] = $currentEntry ? (int)$currentEntry['current_station_id'] : null;
        $t['current_station_name'] = $currentEntry ? $currentEntry['current_station_name'] : null;
        $t['current_status'] = $currentEntry ? $currentEntry['current_status'] : null;
        $t['queue_number'] = $currentEntry ? $currentEntry['queue_number'] : null;

        // Get the journey: all transfers for this patient after this transfer's timestamp
        $journeyStmt = $pdo->prepare("
            SELECT qt2.to_station_id, qs.station_display_name as station_name, qt2.transferred_at
            FROM queue_transfers qt2
            LEFT JOIN queue_stations qs ON qt2.to_station_id = qs.id
            WHERE qt2.patient_id = ? AND qt2.transferred_at > ?
            ORDER BY qt2.transferred_at ASC
        ");
        $journeyStmt->execute([$t['patient_id'], $t['transferred_at']]);
        $t['journey'] = $journeyStmt->fetchAll();
    }
    unset($t);

    return $transfers;
}

/**
 * Report a queue error (wrong station transfer)
 */
function reportQueueError(PDO $pdo, int $queueId, int $patientId, int $wrongStationId, int $correctStationId, int $reportedBy, ?string $notes = null): int
{
    $wrongStation = getQueueStation($pdo, $wrongStationId);
    $correctStation = getQueueStation($pdo, $correctStationId);
    if (!$wrongStation || !$correctStation) {
        throw new Exception("Invalid station");
    }

    $checkStmt = $pdo->prepare("
        SELECT COUNT(*) as count FROM queue_error_log
        WHERE queue_id = ? AND status = 'pending'
    ");
    $checkStmt->execute([$queueId]);
    if ($checkStmt->fetch()['count'] > 0) {
        throw new Exception("A pending correction already exists for this patient at this station");
    }

    $stmt = $pdo->prepare("
        INSERT INTO queue_error_log
        (queue_id, patient_id, wrong_station_id, correct_station_id, reported_by, status, notes)
        VALUES (?, ?, ?, ?, ?, 'pending', ?)
    ");
    $stmt->execute([$queueId, $patientId, $wrongStationId, $correctStationId, $reportedBy, $notes]);
    return (int)$pdo->lastInsertId();
}

/**
 * Get pending corrections for a station (where patients were wrongly sent)
 */
function getPendingCorrections(PDO $pdo, int $stationId): array
{
    $stmt = $pdo->prepare("
        SELECT qel.*,
               p.full_name, p.patient_code,
               ws.station_display_name as wrong_station_name,
               cs.station_display_name as correct_station_name,
               u.full_name as reported_by_name,
               pq.queue_number
        FROM queue_error_log qel
        LEFT JOIN patients p ON qel.patient_id = p.id
        LEFT JOIN queue_stations ws ON qel.wrong_station_id = ws.id
        LEFT JOIN queue_stations cs ON qel.correct_station_id = cs.id
        LEFT JOIN users u ON qel.reported_by = u.id
        LEFT JOIN patient_queue pq ON pq.id = qel.queue_id
        WHERE qel.wrong_station_id = ? AND qel.status = 'pending'
        ORDER BY qel.reported_at ASC
    ");
    $stmt->execute([$stationId]);
    return $stmt->fetchAll();
}

/**
 * Get recently confirmed corrections for a station (for display page announcements)
 */
function getRecentConfirmedCorrections(PDO $pdo, int $stationId): array
{
    $stmt = $pdo->prepare("
        SELECT qel.*,
               p.full_name, p.patient_code,
               ws.station_display_name as wrong_station_name,
               cs.station_display_name as correct_station_name,
               pq.queue_number
        FROM queue_error_log qel
        LEFT JOIN patients p ON qel.patient_id = p.id
        LEFT JOIN queue_stations ws ON qel.wrong_station_id = ws.id
        LEFT JOIN queue_stations cs ON qel.correct_station_id = cs.id
        LEFT JOIN patient_queue pq ON pq.id = qel.queue_id
        WHERE qel.wrong_station_id = ? AND qel.status = 'confirmed'
              AND qel.confirmed_at >= NOW() - INTERVAL 30 SECOND
        ORDER BY qel.confirmed_at DESC
    ");
    $stmt->execute([$stationId]);
    return $stmt->fetchAll();
}

/**
 * Confirm a queue error correction — move patient from wrong station to correct station
 */
function confirmQueueCorrection(PDO $pdo, int $errorLogId, int $confirmedBy): array
{
    $pdo->beginTransaction();

    try {
        $stmt = $pdo->prepare("SELECT * FROM queue_error_log WHERE id = ? AND status = 'pending'");
        $stmt->execute([$errorLogId]);
        $errorLog = $stmt->fetch();

        if (!$errorLog) {
            throw new Exception("Correction not found or already processed");
        }

        $queueId = $errorLog['queue_id'];
        $correctStationId = $errorLog['correct_station_id'];

        $positionStmt = $pdo->prepare("
            SELECT COALESCE(MAX(queue_position), 0) + 1 as next_position
            FROM patient_queue
            WHERE station_id = ? AND status IN ('waiting', 'in_progress')
        ");
        $positionStmt->execute([$correctStationId]);
        $nextPosition = $positionStmt->fetch()['next_position'];

        $updateStmt = $pdo->prepare("
            UPDATE patient_queue
            SET station_id = ?,
                queue_position = ?,
                status = 'waiting',
                updated_at = NOW()
            WHERE id = ?
        ");
        $updateStmt->execute([$correctStationId, $nextPosition, $queueId]);

        $logStmt = $pdo->prepare("
            INSERT INTO queue_transfers
            (patient_id, from_station_id, to_station_id, transferred_by, transfer_reason, notes)
            VALUES (?, ?, ?, ?, 'manual', 'Queue error correction')
        ");
        $logStmt->execute([$errorLog['patient_id'], $errorLog['wrong_station_id'], $correctStationId, $confirmedBy]);

        $confirmStmt = $pdo->prepare("
            UPDATE queue_error_log
            SET status = 'confirmed',
                confirmed_by = ?,
                confirmed_at = NOW()
            WHERE id = ?
        ");
        $confirmStmt->execute([$confirmedBy, $errorLogId]);

        $pdo->commit();

        // Return info for display announcement
        $patientStmt = $pdo->prepare("SELECT full_name, patient_code FROM patients WHERE id = ?");
        $patientStmt->execute([$errorLog['patient_id']]);
        $patient = $patientStmt->fetch();

        $correctStation = getQueueStation($pdo, $correctStationId);

        return [
            'patient_name' => $patient['full_name'] ?? '',
            'patient_code' => $patient['patient_code'] ?? '',
            'correct_station_name' => $correctStation['station_display_name'] ?? '',
            'error_log_id' => $errorLogId
        ];
    } catch (Exception $e) {
        $pdo->rollBack();
        throw $e;
    }
}
