<?php
declare(strict_types=1);

require_once __DIR__ . '/../_db.php';

function philhealth_db(): PDO
{
    return db();
}
