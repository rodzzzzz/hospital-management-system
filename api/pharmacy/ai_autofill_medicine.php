<?php
declare(strict_types=1);

require_once __DIR__ . '/../_cors.php';
require_once __DIR__ . '/../_response.php';

cors_headers();
require_method('POST');

try {
    $getEnvValue = function (string $key): string {
        $v = getenv($key);
        if ($v !== false && trim((string)$v) !== '') {
            return trim((string)$v);
        }
        if (isset($_SERVER[$key]) && trim((string)$_SERVER[$key]) !== '') {
            return trim((string)$_SERVER[$key]);
        }
        if (isset($_ENV[$key]) && trim((string)$_ENV[$key]) !== '') {
            return trim((string)$_ENV[$key]);
        }
        if (function_exists('apache_getenv')) {
            $v2 = apache_getenv($key, true);
            if ($v2 !== false && trim((string)$v2) !== '') {
                return trim((string)$v2);
            }
        }
        return '';
    };

    $getPhpIniValue = function (string $key): string {
        $path = php_ini_loaded_file();
        if (!is_string($path) || $path === '' || !is_file($path)) {
            return '';
        }
        $lines = @file($path, FILE_IGNORE_NEW_LINES);
        if (!is_array($lines)) {
            return '';
        }
        $pattern = '/^\s*' . preg_quote($key, '/') . '\s*=\s*(.*?)\s*(?:;.*)?$/';
        foreach ($lines as $line) {
            if (!is_string($line)) {
                continue;
            }
            if (preg_match($pattern, $line, $m)) {
                $val = (string)($m[1] ?? '');
                $val = trim($val);
                if ($val === '') {
                    return '';
                }
                if (str_starts_with($val, '"') || str_starts_with($val, "'")) {
                    $val = substr($val, 1);
                }
                if (str_ends_with($val, '"') || str_ends_with($val, "'")) {
                    $val = substr($val, 0, -1);
                }
                return trim($val);
            }
        }
        return '';
    };

    $apiKeyEnv = $getEnvValue('AICC_API_KEY');
    $apiKeyIni = $getPhpIniValue('aicc.api_key');
    $apiKey = $apiKeyEnv ?: $apiKeyIni;
    $apiKeySource = $apiKeyEnv !== '' ? 'env' : ($apiKeyIni !== '' ? 'php.ini' : '');
    if ($apiKey === '') {
        json_response([
            'ok' => false,
            'error' => 'Missing AICC_API_KEY environment variable (or php.ini aicc.api_key)',
            'diag' => [
                'apiKeySource' => $apiKeySource,
            ],
        ], 500);
    }

    $baseUrl = $getEnvValue('AICC_BASE_URL') ?: ($getPhpIniValue('aicc.base_url') ?: 'https://api.ai.cc/v1');
    $model = $getEnvValue('AICC_MODEL') ?: ($getPhpIniValue('aicc.model') ?: 'gpt-4o');

    $raw = file_get_contents('php://input');
    $payload = json_decode($raw ?: 'null', true);
    if (!is_array($payload)) {
        $payload = [];
    }

    $seed = trim((string)($payload['seed'] ?? ''));
    if ($seed === '') {
        $seed = bin2hex(random_bytes(8));
    }

    $avoidNames = $payload['avoid_names'] ?? [];
    if (!is_array($avoidNames)) {
        $avoidNames = [];
    }
    $avoidNames = array_values(array_filter(array_map(function ($v) {
        return trim((string)$v);
    }, $avoidNames), function ($v) {
        return $v !== '';
    }));

    $system = 'You generate realistic test data for hospital pharmacy medicine inventory. Output ONLY valid JSON. No markdown, no extra text.';

    $namePool = [
        'Amoxicillin', 'Azithromycin', 'Ciprofloxacin', 'Cephalexin', 'Doxycycline',
        'Paracetamol', 'Ibuprofen', 'Naproxen', 'Tramadol',
        'Cetirizine', 'Loratadine', 'Salbutamol', 'Montelukast',
        'Amlodipine', 'Losartan', 'Metoprolol', 'Atorvastatin',
        'Omeprazole', 'Metformin', 'Insulin Glargine',
        'Ascorbic Acid', 'Ferrous Sulfate', 'Zinc Sulfate',
    ];
    $brandPool = ['Generic', 'MediCare', 'HealthPlus', 'PharmaOne', 'MedHealth', 'PrimeCare', 'VitaLabs'];
    $formPool = ['Tablets', 'Capsules', 'Syrup', 'Suspension', 'Injection', 'Inhaler', 'Drops'];
    $strengthPool = ['250mg', '500mg', '875mg', '5mg', '10mg', '20mg', '100mg/5mL', '200mg/5mL'];

    $user = json_encode([
        'task' => 'Generate a single medicine inventory item. Output JSON object with keys: name, category, quantity, price, expiry_date, manufacturer, description. Use the provided seed to vary output. Never repeat a name in avoid_names.',
        'seed' => $seed,
        'avoid_names' => $avoidNames,
        'suggested_name_pool' => $namePool,
        'suggested_brand_pool' => $brandPool,
        'suggested_form_pool' => $formPool,
        'suggested_strength_pool' => $strengthPool,
        'rules' => [
            'category must be one of: antibiotics, painRelievers, vitamins, cardiovascular, respiratory',
            'quantity must be an integer between 0 and 500',
            'price must be a number (string or number ok) between 1 and 500 with 2 decimals',
            'expiry_date must be YYYY-MM-DD and should be in the future',
            'name should be a plausible real medicine name (generic or brand) with strength/form in description',
            'ensure medicine name differs from any value inside avoid_names',
            'prefer picking a different item from suggested_name_pool based on seed',
        ],
        'output_schema' => [
            'name' => 'string',
            'category' => 'string',
            'quantity' => 'int',
            'price' => 'string or number',
            'expiry_date' => 'YYYY-MM-DD',
            'manufacturer' => 'string',
            'description' => 'string',
        ],
    ], JSON_UNESCAPED_UNICODE);

    $body = json_encode([
        'model' => $model,
        'messages' => [
            ['role' => 'system', 'content' => $system],
            ['role' => 'user', 'content' => $user],
        ],
        'temperature' => 1.0,
        'top_p' => 0.95,
        'presence_penalty' => 0.6,
    ]);

    $auth = $apiKey;
    if (stripos($auth, 'bearer ') !== 0) {
        $auth = 'Bearer ' . $auth;
    }

    $doCall = function (array $headers) use ($baseUrl, $body): array {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => rtrim($baseUrl, '/') . '/chat/completions',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_POSTFIELDS => $body,
            CURLOPT_TIMEOUT => 30,
        ]);

        $resp = curl_exec($ch);
        $err = curl_error($ch);
        $status = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return ['resp' => $resp, 'err' => $err, 'status' => $status];
    };

    $authTried = 'authorization';
    $call = $doCall([
        'Content-Type: application/json',
        'Accept: application/json',
        'Authorization: ' . $auth,
    ]);

    $resp = $call['resp'];
    $err = (string)($call['err'] ?? '');
    $status = (int)($call['status'] ?? 0);

    $json = is_string($resp) ? json_decode($resp, true) : null;
    if ($status === 401) {
        $authTried = 'authorization,x-api-key';
        $call2 = $doCall([
            'Content-Type: application/json',
            'Accept: application/json',
            'x-api-key: ' . $apiKey,
        ]);
        if ($call2['resp'] !== false && is_string($call2['resp'])) {
            $resp = $call2['resp'];
            $err = (string)($call2['err'] ?? '');
            $status = (int)($call2['status'] ?? 0);
            $json = json_decode($resp, true);
        }
    }

    if ($resp === false) {
        json_response(['ok' => false, 'error' => $err ?: 'AI request failed'], 500);
    }

    if (!is_array($json)) {
        json_response([
            'ok' => false,
            'error' => 'AI returned non-JSON response',
            'status' => $status,
            'raw' => $resp,
        ], 500);
    }

    if ($status < 200 || $status >= 300 || isset($json['error'])) {
        $msg = $json['error']['message'] ?? ('AI error (HTTP ' . $status . ')');
        $code = ($status >= 400 && $status <= 599) ? $status : 500;
        json_response([
            'ok' => false,
            'error' => (string)$msg,
            'status' => $status,
            'provider_error' => $json['error'] ?? null,
            'provider_raw' => $json,
            'diag' => [
                'apiKeySource' => $apiKeySource,
                'baseUrl' => $baseUrl,
                'model' => $model,
                'authTried' => $authTried,
            ],
        ], $code);
    }

    $content = $json['choices'][0]['message']['content'] ?? null;
    if (!is_string($content) || trim($content) === '') {
        json_response([
            'ok' => false,
            'error' => 'AI response missing message content',
            'status' => $status,
            'provider_raw' => $json,
        ], 500);
    }

    $extractJson = function (string $text): string {
        $t = trim($text);
        if (preg_match('/```(?:json)?\s*(.*?)\s*```/is', $t, $m)) {
            $t = trim((string)$m[1]);
        }
        $startObj = strpos($t, '{');
        $endObj = strrpos($t, '}');
        if ($startObj !== false && $endObj !== false && $endObj > $startObj) {
            return substr($t, $startObj, $endObj - $startObj + 1);
        }
        return $t;
    };

    $clean = $extractJson($content);
    $out = json_decode($clean, true);
    if (!is_array($out)) {
        json_response([
            'ok' => false,
            'error' => 'AI response did not match expected JSON schema',
            'raw' => $content,
            'raw_extracted' => $clean,
        ], 500);
    }

    $medicine = [
        'name' => (string)($out['name'] ?? ''),
        'category' => (string)($out['category'] ?? ''),
        'quantity' => (int)($out['quantity'] ?? 0),
        'price' => (string)($out['price'] ?? ''),
        'expiry_date' => (string)($out['expiry_date'] ?? ''),
        'manufacturer' => (string)($out['manufacturer'] ?? ''),
        'description' => (string)($out['description'] ?? ''),
    ];

    json_response([
        'ok' => true,
        'medicine' => $medicine,
        'seed' => $seed,
    ]);
} catch (Throwable $e) {
    json_response([
        'ok' => false,
        'error' => $e->getMessage(),
    ], 500);
}
