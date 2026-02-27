<?php
// Add purpose_of_visit columns to database
require_once 'api/_db.php';

try {
    $pdo = db();
    
    // Add column to patients table
    echo "Adding purpose_of_visit column to patients table...\n";
    $pdo->exec("ALTER TABLE patients ADD COLUMN purpose_of_visit varchar(255) DEFAULT NULL AFTER diagnosis");
    echo "✓ Added to patients table\n";
    
    // Add column to patient_queue table
    echo "Adding purpose_of_visit column to patient_queue table...\n";
    $pdo->exec("ALTER TABLE patient_queue ADD COLUMN purpose_of_visit varchar(255) DEFAULT NULL AFTER notes");
    echo "✓ Added to patient_queue table\n";
    
    echo "\n✅ All columns added successfully!\n";
    
} catch (Exception $e) {
    // Check if column already exists
    if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
        echo "⚠️  Column purpose_of_visit already exists in one or both tables\n";
    } else {
        echo "❌ Error: " . $e->getMessage() . "\n";
    }
}
?>
