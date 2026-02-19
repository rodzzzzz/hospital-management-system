<?php
declare(strict_types=1);

require_once __DIR__ . '/../_db.php';

function ensure_encounter_tables(PDO $pdo): void
{
    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS encounters (
            id INT AUTO_INCREMENT PRIMARY KEY,
            encounter_no VARCHAR(32) NULL UNIQUE,
            patient_id INT NOT NULL,
            type ENUM('ER','OPD','IPD','PHARMACY') NOT NULL DEFAULT 'OPD',
            status ENUM('open','closed','cancelled') NOT NULL DEFAULT 'open',
            started_at DATETIME NOT NULL,
            ended_at DATETIME NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            CONSTRAINT fk_encounters_patient FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE RESTRICT,
            INDEX idx_encounters_patient (patient_id),
            INDEX idx_encounters_status (status),
            INDEX idx_encounters_started (started_at)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
    );

    try {
        $pdo->exec("ALTER TABLE encounters ADD COLUMN encounter_no VARCHAR(32) NULL UNIQUE");
    } catch (Throwable $e) {
    }

    try {
        $pdo->exec("ALTER TABLE encounters ADD COLUMN started_at DATETIME NOT NULL DEFAULT '1970-01-01 00:00:00'");
    } catch (Throwable $e) {
    }

    try {
        $pdo->exec("ALTER TABLE encounters ADD COLUMN ended_at DATETIME NULL");
    } catch (Throwable $e) {
    }

    try {
        $pdo->exec("ALTER TABLE encounters ADD INDEX idx_encounters_patient (patient_id)");
    } catch (Throwable $e) {
    }

    try {
        $pdo->exec("ALTER TABLE encounters ADD INDEX idx_encounters_status (status)");
    } catch (Throwable $e) {
    }

    try {
        $pdo->exec("ALTER TABLE encounters ADD INDEX idx_encounters_started (started_at)");
    } catch (Throwable $e) {
    }
}

function find_open_encounter(PDO $pdo, int $patientId): ?array
{
    $stmt = $pdo->prepare(
        "SELECT id, encounter_no, patient_id, type, status, started_at, ended_at
         FROM encounters
         WHERE patient_id = :patient_id AND status = 'open'
         ORDER BY started_at DESC, id DESC
         LIMIT 1"
    );
    $stmt->execute(['patient_id' => $patientId]);
    $row = $stmt->fetch();
    return $row ?: null;
}

function find_open_encounter_by_type(PDO $pdo, int $patientId, string $type): ?array
{
    $typeNorm = strtoupper(trim($type));
    if (!in_array($typeNorm, ['ER', 'OPD', 'IPD', 'PHARMACY'], true)) {
        return null;
    }

    $stmt = $pdo->prepare(
        "SELECT id, encounter_no, patient_id, type, status, started_at, ended_at
         FROM encounters
         WHERE patient_id = :patient_id AND status = 'open' AND type = :type
         ORDER BY started_at DESC, id DESC
         LIMIT 1"
    );
    $stmt->execute([
        'patient_id' => $patientId,
        'type' => $typeNorm,
    ]);
    $row = $stmt->fetch();
    return $row ?: null;
}

function create_encounter(PDO $pdo, int $patientId, string $type): int
{
    $typeNorm = strtoupper(trim($type));
    if (!in_array($typeNorm, ['ER', 'OPD', 'IPD', 'PHARMACY'], true)) {
        $typeNorm = 'OPD';
    }

    $startedAt = date('Y-m-d H:i:s');

    $ins = $pdo->prepare(
        "INSERT INTO encounters (patient_id, type, status, started_at)
         VALUES (:patient_id, :type, 'open', :started_at)"
    );
    $ins->execute([
        'patient_id' => $patientId,
        'type' => $typeNorm,
        'started_at' => $startedAt,
    ]);

    $id = (int)$pdo->lastInsertId();
    $no = 'ENC-' . date('Ymd') . '-' . str_pad((string)$id, 6, '0', STR_PAD_LEFT);

    $pdo->prepare('UPDATE encounters SET encounter_no = :no WHERE id = :id')->execute([
        'no' => $no,
        'id' => $id,
    ]);

    return $id;
}

function resolve_encounter_id(PDO $pdo, int $patientId, ?int $preferredEncounterId, string $defaultTypeIfCreate): int
{
    if ($preferredEncounterId !== null && $preferredEncounterId > 0) {
        $stmt = $pdo->prepare(
            "SELECT id FROM encounters WHERE id = :id AND patient_id = :patient_id LIMIT 1"
        );
        $stmt->execute([
            'id' => $preferredEncounterId,
            'patient_id' => $patientId,
        ]);
        $row = $stmt->fetch();
        if ($row && isset($row['id'])) {
            return (int)$row['id'];
        }
    }

    $open = find_open_encounter($pdo, $patientId);
    if ($open && isset($open['id'])) {
        return (int)$open['id'];
    }

    return create_encounter($pdo, $patientId, $defaultTypeIfCreate);
}

function resolve_er_encounter_id(PDO $pdo, int $patientId, ?int $preferredEncounterId): int
{
    if ($preferredEncounterId !== null && $preferredEncounterId > 0) {
        $stmt = $pdo->prepare(
            "SELECT id FROM encounters WHERE id = :id AND patient_id = :patient_id LIMIT 1"
        );
        $stmt->execute([
            'id' => $preferredEncounterId,
            'patient_id' => $patientId,
        ]);
        $row = $stmt->fetch();
        if ($row && isset($row['id'])) {
            return (int)$row['id'];
        }
    }

    $openEr = find_open_encounter_by_type($pdo, $patientId, 'ER');
    if ($openEr && isset($openEr['id'])) {
        return (int)$openEr['id'];
    }

    return create_encounter($pdo, $patientId, 'ER');
}
