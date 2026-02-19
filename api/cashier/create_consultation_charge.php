<?php
declare(strict_types=1);

require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/_tables.php';
require_once __DIR__ . '/../encounters/_tables.php';

function create_consultation_charge(PDO $pdo, int $patientId, string $sourceModule, int $sourceId, ?int $encounterId = null): ?int
{
    $consultationFee = 500.00; // Default consultation fee
    
    $pdo->beginTransaction();
    
    try {
        // Ensure encounter exists if not provided
        if ($encounterId === null) {
            $encounterId = create_encounter($pdo, $patientId, $sourceModule === 'er_consultation' ? 'ER' : 'OPD');
        }
        
        // Create the charge with pending_invoice status
        $insCharge = $pdo->prepare(
            'INSERT INTO cashier_charges (source_module, source_id, patient_id, encounter_id, status) VALUES (:source_module, :source_id, :patient_id, :encounter_id, :status)'
        );
        $insCharge->execute([
            'source_module' => $sourceModule,
            'source_id' => $sourceId,
            'patient_id' => $patientId,
            'encounter_id' => $encounterId,
            'status' => 'pending_invoice'
        ]);
        
        $chargeId = (int)$pdo->lastInsertId();
        
        // Create charge item for consultation
        $insItem = $pdo->prepare(
            'INSERT INTO cashier_charge_items (charge_id, medicine_name, qty) VALUES (:charge_id, :medicine_name, :qty)'
        );
        $insItem->execute([
            'charge_id' => $chargeId,
            'medicine_name' => 'Consultation Fee',
            'qty' => 1
        ]);
        
        // Don't create invoice automatically - let cashier create it when processing payment
        // This keeps the charge in "pending_invoice" status so it appears in Pending Charges view
        
        $pdo->commit();
        
        return $chargeId;
        
    } catch (Throwable $e) {
        $pdo->rollBack();
        throw $e;
    }
}
