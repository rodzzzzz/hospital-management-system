<?php
try {
    $pdo = new PDO("mysql:host=127.0.0.1;dbname=ttsi_auto;charset=utf8mb4", 'root', '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
    
    echo "Checking patients table:\n";
    $stmt = $pdo->query("SHOW COLUMNS FROM patients LIKE 'purpose_of_visit'");
    if ($stmt->rowCount() > 0) {
        echo "✓ purpose_of_visit column EXISTS in patients table\n";
    } else {
        echo "✗ purpose_of_visit column MISSING in patients table\n";
    }
    
    echo "\nChecking patient_queue table:\n";
    $stmt = $pdo->query("SHOW COLUMNS FROM patient_queue LIKE 'purpose_of_visit'");
    if ($stmt->rowCount() > 0) {
        echo "✓ purpose_of_visit column EXISTS in patient_queue table\n";
    } else {
        echo "✗ purpose_of_visit column MISSING in patient_queue table\n";
        echo "\nAdding column to patient_queue...\n";
        $pdo->exec("ALTER TABLE patient_queue ADD COLUMN purpose_of_visit varchar(255) DEFAULT NULL AFTER notes");
        echo "✓ Column added successfully!\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
