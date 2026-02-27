<?php
declare(strict_types=1);

require_once __DIR__ . '/../_cors.php';
require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../queue/_functions.php';

cors_headers();

echo "<h1>Refresh Patient Queue - OPD</h1>";
echo "<p>This script will clear the current patient_queue table and add all patients to the OPD station with fresh queue numbers.</p>";

try {
    $pdo = db();
    
    // Start transaction
    $pdo->beginTransaction();
    
    echo "<h2>Step 1: Clear existing queue data</h2>";
    
    // Clear patient_queue table completely
    $stmt = $pdo->prepare("DELETE FROM patient_queue");
    $stmt->execute();
    $deletedRows = $stmt->rowCount();
    echo "<p style='color: orange;'>Cleared $deletedRows existing queue entries</p>";
    
    // Clear queue_transfers table (optional - keeps audit log clean)
    $stmt = $pdo->prepare("DELETE FROM queue_transfers");
    $stmt->execute();
    $deletedTransfers = $stmt->rowCount();
    echo "<p style='color: orange;'>Cleared $deletedTransfers transfer records</p>";
    
    // Verify table is empty
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM patient_queue");
    $stmt->execute();
    $remainingCount = $stmt->fetch()['count'];
    if ($remainingCount > 0) {
        throw new Exception("Failed to clear patient_queue table completely. $remainingCount entries remain.");
    }
    echo "<p style='color: green;'>✓ Patient queue table verified as empty</p>";
    
    echo "<h2>Step 2: Get OPD station information</h2>";
    
    // Get OPD station (station_name = 'opd')
    $opdStation = getQueueStation($pdo, 'opd');
    if (!$opdStation) {
        throw new Exception("OPD station not found. Please ensure queue system is set up properly.");
    }
    
    echo "<p style='color: green;'>Found OPD station: {$opdStation['station_display_name']} (ID: {$opdStation['id']})</p>";
    
    echo "<h2>Step 3: Get all patients</h2>";
    
    // Get all patients from the database
    $stmt = $pdo->prepare("SELECT id, full_name, patient_code FROM patients ORDER BY full_name");
    $stmt->execute();
    $patients = $stmt->fetchAll();
    
    if (empty($patients)) {
        echo "<p style='color: red;'>No patients found in the database!</p>";
        exit;
    }
    
    echo "<p style='color: blue;'>Found " . count($patients) . " patients in the database</p>";
    
    echo "<h2>Step 4: Add all patients to OPD queue</h2>";
    
    $addedCount = 0;
    $queuePosition = 1;
    
    foreach ($patients as $patient) {
        try {
            // Check if patient already exists in queue (shouldn't happen after clearing, but safety check)
            $checkStmt = $pdo->prepare("SELECT COUNT(*) as count FROM patient_queue WHERE patient_id = ? AND station_id = ?");
            $checkStmt->execute([$patient['id'], $opdStation['id']]);
            $exists = $checkStmt->fetch()['count'];
            
            if ($exists > 0) {
                echo "<p style='color: orange;'>⚠ Skipped {$patient['full_name']} - already in queue</p>";
                continue;
            }
            
            // Get next queue number for OPD (resets daily)
            $queueNumber = getNextQueueNumber($pdo, $opdStation['id']);
            
            // Insert patient into queue
            $stmt = $pdo->prepare("
                INSERT INTO patient_queue 
                (patient_id, station_id, queue_number, queue_position, status)
                VALUES (?, ?, ?, ?, 'waiting')
            ");
            $stmt->execute([
                $patient['id'],
                $opdStation['id'],
                $queueNumber,
                $queuePosition
            ]);
            
            $addedCount++;
            $queuePosition++;
            
            echo "<p style='color: green;'>✓ Added: {$patient['full_name']} (Code: {$patient['patient_code']}) - Queue #: $queueNumber</p>";
            
        } catch (Exception $e) {
            echo "<p style='color: red;'>✗ Failed to add {$patient['full_name']}: " . htmlspecialchars($e->getMessage()) . "</p>";
        }
    }
    
    // Commit transaction
    $pdo->commit();
    
    echo "<h2>Refresh Complete!</h2>";
    echo "<p style='color: green; font-size: 18px;'>Successfully added $addedCount patients to the OPD queue</p>";
    
    echo "<h2>Queue Summary:</h2>";
    
    // Get current queue summary
    $stmt = $pdo->prepare("
        SELECT COUNT(*) as total_patients,
               MIN(queue_number) as first_queue,
               MAX(queue_number) as last_queue
        FROM patient_queue 
        WHERE station_id = ? AND status = 'waiting'
    ");
    $stmt->execute([$opdStation['id']]);
    $summary = $stmt->fetch();
    
    echo "<p><strong>Total patients in OPD queue:</strong> {$summary['total_patients']}</p>";
    echo "<p><strong>Queue number range:</strong> {$summary['first_queue']} - {$summary['last_queue']}</p>";
    
    echo "<h2>Next Steps:</h2>";
    echo "<p><a href='out-patient-department.php' target='_blank'>Go to OPD Department Page</a></p>";
    echo "<p><a href='opd-display.php' target='_blank'>Open OPD Queue Display</a></p>";
    echo "<p><a href='api/queue/index.php' target='_blank'>View Queue API</a></p>";
    
    echo "<h2>Actions Available:</h2>";
    echo "<ul>";
    echo "<li><strong>Call Next Patient:</strong> Staff can call the next patient from the queue</li>";
    echo "<li><strong>Transfer to Doctor:</strong> Move patients to the next station (Doctor)</li>";
    echo "<li><strong>Mark Unavailable:</strong> Mark patients as unavailable if they don't respond</li>";
    echo "<li><strong>Complete Service:</strong> Complete service and discharge or transfer to next station</li>";
    echo "</ul>";
    
} catch (Exception $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo "<p style='color: red; font-size: 18px;'>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p><strong>Stack trace:</strong></p>";
    echo "<pre style='background: #f0f0f0; padding: 10px; border-radius: 5px;'>";
    echo htmlspecialchars($e->getTraceAsString());
    echo "</pre>";
}
?>

<style>
body { 
    font-family: Arial, sans-serif; 
    margin: 20px; 
    line-height: 1.6;
}
h1 { 
    color: #2c3e50; 
    border-bottom: 3px solid #3498db;
    padding-bottom: 10px;
}
h2 { 
    color: #34495e; 
    margin-top: 30px;
    border-left: 4px solid #3498db;
    padding-left: 15px;
}
p { 
    margin: 10px 0; 
}
a {
    color: #3498db;
    text-decoration: none;
    padding: 8px 16px;
    background: #ecf0f1;
    border-radius: 4px;
    display: inline-block;
    margin: 5px 5px 5px 0;
}
a:hover {
    background: #3498db;
    color: white;
}
ul {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 5px;
    border-left: 4px solid #28a745;
}
li {
    margin: 8px 0;
}
</style>
