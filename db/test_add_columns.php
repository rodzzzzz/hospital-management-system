<?php
try {
    // Try connecting directly
    $pdo = new PDO("mysql:host=127.0.0.1;dbname=ttsi_auto;charset=utf8mb4", 'root', '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
    echo "Connected successfully\n";
    
    // Check if column exists in patients
    $stmt = $pdo->query("SHOW COLUMNS FROM patients LIKE 'purpose_of_visit'");
    if ($stmt->rowCount() > 0) {
        echo "Column purpose_of_visit already exists in patients table\n";
    } else {
        echo "Adding column to patients...\n";
        $pdo->exec("ALTER TABLE patients ADD COLUMN purpose_of_visit varchar(255) DEFAULT NULL AFTER diagnosis");
        echo "✓ Added to patients table\n";
    }
    
    // Check if column exists in patient_queue
    $stmt = $pdo->query("SHOW COLUMNS FROM patient_queue LIKE 'purpose_of_visit'");
    if ($stmt->rowCount() > 0) {
        echo "Column purpose_of_visit already exists in patient_queue table\n";
    } else {
        echo "Adding column to patient_queue...\n";
        $pdo->exec("ALTER TABLE patient_queue ADD COLUMN purpose_of_visit varchar(255) DEFAULT NULL AFTER notes");
        echo "✓ Added to patient_queue table\n";
    }
    
    echo "\n✅ Done!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
