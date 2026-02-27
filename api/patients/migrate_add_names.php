<?php
declare(strict_types=1);

require_once __DIR__ . '/../_cors.php';
require_once __DIR__ . '/../philhealth/_db.php';
require_once __DIR__ . '/../_response.php';

cors_headers();

try {
    $pdo = philhealth_db();

    // Add first_name and last_name columns to patients table
    $pdo->exec(
        "ALTER TABLE patients 
         ADD COLUMN IF NOT EXISTS first_name VARCHAR(128) NULL AFTER full_name,
         ADD COLUMN IF NOT EXISTS last_name VARCHAR(128) NULL AFTER first_name"
    );

    // Optional: Migrate existing data by splitting full_name
    // This attempts to split "Juan Dela Cruz" into first_name="Juan" and last_name="Dela Cruz"
    $pdo->exec(
        "UPDATE patients 
         SET first_name = SUBSTRING_INDEX(full_name, ' ', 1),
             last_name = SUBSTRING(full_name, LENGTH(SUBSTRING_INDEX(full_name, ' ', 1)) + 2)
         WHERE first_name IS NULL AND full_name IS NOT NULL AND full_name != ''"
    );

    json_response([
        'ok' => true,
        'message' => 'Successfully added first_name and last_name columns to patients table',
    ]);
} catch (Throwable $e) {
    json_response([
        'ok' => false,
        'error' => $e->getMessage(),
    ], 500);
}
