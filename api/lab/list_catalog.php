<?php
declare(strict_types=1);

require_once __DIR__ . '/../_cors.php';
require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';
require_once __DIR__ . '/_tables.php';

cors_headers();
require_method('GET');

try {
    $pdo = db();
    ensure_lab_tables($pdo);

    $catalog = lab_test_catalog();
    $tests = [];
    foreach ($catalog as $code => $info) {
        $tests[] = [
            'test_code' => (string)$code,
            'test_name' => (string)($info['name'] ?? $code),
            'specimen' => $info['specimen'] ?? null,
        ];
    }

    usort($tests, static function (array $a, array $b): int {
        return strcmp((string)($a['test_name'] ?? ''), (string)($b['test_name'] ?? ''));
    });

    json_response([
        'ok' => true,
        'tests' => $tests,
    ]);
} catch (Throwable $e) {
    json_response([
        'ok' => false,
        'error' => $e->getMessage(),
    ], 500);
}
