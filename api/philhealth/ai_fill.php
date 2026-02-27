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
    $diag = [
        'apiKeySource' => $apiKeySource,
        'apiKeyLen' => strlen($apiKey),
        'apiKeyPrefix' => substr($apiKey, 0, 3),
        'baseUrl' => $baseUrl,
        'model' => $model,
    ];

    $raw = file_get_contents('php://input');
    $payload = json_decode($raw ?: 'null', true);
    if (!is_array($payload)) {
        json_response(['ok' => false, 'error' => 'Invalid JSON body'], 400);
    }

    $page = (string)($payload['page'] ?? '');
    $seed = (string)($payload['seed'] ?? '');
    $fields = $payload['fields'] ?? null;
    if (!is_array($fields) || count($fields) === 0) {
        json_response(['ok' => false, 'error' => 'Missing fields'], 400);
    }

    $isConsultNotes = str_starts_with(strtolower($page), 'consultation_notes');

    if ($isConsultNotes) {
        $system = 'You generate realistic draft values for a hospital doctor consultation note form. Output ONLY valid JSON. No markdown, no extra text.';

        $user = json_encode([
            'task' => 'Fill each field with appropriate realistic values for a consultation note. Keep outputs concise and clinical. Do NOT output "N/A". If something does not apply, use an empty string, or "None" only when medically appropriate. For selects/radios, choose one of the provided option values. For checkboxes, set "checked" to true/false and set "value" to "1" or "0". Return an array "values" aligned by index to the input "fields" array.',
            'page' => $page,
            'seed' => $seed,
            'seed_rule' => 'Use the provided seed to keep the generated case consistent across multiple calls. If the same seed is used, keep the chief complaint, context, and narrative consistent.',
            'fields' => $fields,
            'guidelines' => [
                'Write short phrases where possible (not essays).',
                'Use plausible vitals and findings, but do not include extreme/emergency values unless indicated by the inputs.',
                'Prefer common primary care / ER presentations (e.g., URI, gastritis, headache, musculoskeletal pain).',
                'If a field includes current_value, preserve it and only suggest missing fields.',
                'Avoid personally identifying details not present in the inputs.',
            ],
            'output_schema' => [
                'values' => [
                    ['index' => 0, 'value' => 'string or null', 'checked' => 'bool optional'],
                ],
            ],
        ], JSON_UNESCAPED_UNICODE);
    } else {
        $system = 'You generate realistic test data for hospital PhilHealth claim forms. Output ONLY valid JSON. No markdown, no extra text.';

        $user = json_encode([
            'task' => 'Fill each field with an appropriate realistic example value. For selects/radios, choose one of the provided option values. Return an array "values" aligned by index to the input "fields" array.',
            'page' => $page,
            'seed' => $seed,
            'seed_rule' => 'Use the provided seed to keep the generated patient/persona consistent across multiple calls/pages. If the same seed is used, keep name/contact/address/birthdate consistent. If seed is empty, still generate realistic values but they may not be consistent across calls.',
            'fields' => $fields,
            'output_schema' => [
                'values' => [
                    ['index' => 0, 'value' => 'string or null', 'checked' => 'bool optional'],
                ],
            ],
        ], JSON_UNESCAPED_UNICODE);
    }

    $body = json_encode([
        'model' => $model,
        'messages' => [
            ['role' => 'system', 'content' => $system],
            ['role' => 'user', 'content' => $user],
        ],
        'temperature' => 0.7,
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
            'diag' => array_merge($diag, ['authTried' => $authTried]),
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

    $extractJson = function (string $text) {
        $t = trim($text);
        if (preg_match('/```(?:json)?\s*(.*?)\s*```/is', $t, $m)) {
            $t = trim((string)$m[1]);
        }

        $startObj = strpos($t, '{');
        $endObj = strrpos($t, '}');
        if ($startObj !== false && $endObj !== false && $endObj > $startObj) {
            return substr($t, $startObj, $endObj - $startObj + 1);
        }

        $startArr = strpos($t, '[');
        $endArr = strrpos($t, ']');
        if ($startArr !== false && $endArr !== false && $endArr > $startArr) {
            return substr($t, $startArr, $endArr - $startArr + 1);
        }

        return $t;
    };

    $clean = $extractJson($content);
    $out = json_decode($clean, true);
    if (is_array($out) && array_is_list($out)) {
        $out = ['values' => $out];
    }

    if (!is_array($out) || !isset($out['values']) || !is_array($out['values'])) {
        json_response([
            'ok' => false,
            'error' => 'AI response did not match expected JSON schema',
            'raw' => $content,
            'raw_extracted' => $clean,
        ], 500);
    }

    $pickFrom = function (string $seed, string $key, array $choices): string {
        if (count($choices) === 0) {
            return '';
        }
        $h = hash('sha256', $seed . '|' . $key);
        $n = (int)hexdec(substr($h, 0, 8));
        $idx = $n % count($choices);
        $v = $choices[$idx] ?? '';
        return is_string($v) ? $v : (string)$v;
    };

    $digitsFrom = function (string $seed, string $key, int $len): string {
        if ($len <= 0) {
            $len = 10;
        }
        $out = '';
        $i = 0;
        while (strlen($out) < $len) {
            $h = hash('sha256', $seed . '|' . $key . '|' . (string)$i);
            $chunk = preg_replace('/\D/', '', (string)hexdec(substr($h, 0, 12)));
            $out .= $chunk;
            $i++;
        }
        return substr($out, 0, $len);
    };

    $seedSafe = $seed !== '' ? $seed : bin2hex(random_bytes(8));
    $firstNames = ['Juan', 'Jose', 'Maria', 'Ana', 'Mark', 'Paolo', 'John', 'James', 'Grace', 'Angela', 'Carlo', 'Jasmine', 'Michael', 'Sofia', 'Daniel', 'Patricia'];
    $lastNames = ['Dela Cruz', 'Santos', 'Reyes', 'Garcia', 'Mendoza', 'Torres', 'Flores', 'Ramos', 'Gonzales', 'Bautista', 'Navarro', 'Castillo', 'Rivera', 'Aquino', 'Lopez', 'Lim'];
    $cities = ['Quezon City', 'Manila', 'Caloocan', 'Pasig', 'Taguig', 'Makati', 'Cebu City', 'Davao City', 'Baguio', 'Iloilo City', 'Bacolod', 'Cagayan de Oro'];
    $barangays = ['San Roque', 'San Isidro', 'Sto. Nino', 'Poblacion', 'San Jose', 'Bagong Silang', 'Maligaya', 'Santa Cruz', 'San Antonio', 'San Miguel'];
    $streets = ['Rizal Ave', 'Quezon Ave', 'Bonifacio St', 'Mabini St', 'Del Pilar St', 'Main St', 'National Highway', 'JP Rizal', 'Kalayaan Ave', 'Commonwealth Ave'];
    $provinces = ['Metro Manila', 'Cebu', 'Davao del Sur', 'Laguna', 'Cavite', 'Bulacan', 'Pampanga', 'Rizal', 'Batangas', 'Negros Occidental', 'Iloilo', 'Benguet'];

    $fallbackForField = function (array $field) use ($isConsultNotes, $seedSafe, $pickFrom, $digitsFrom, $firstNames, $lastNames, $cities, $barangays, $streets, $provinces): array {
        $kind = (string)($field['kind'] ?? 'input');
        $name = strtolower((string)($field['name'] ?? ''));
        $placeholder = strtolower((string)($field['placeholder'] ?? ''));
        $label = strtolower((string)($field['label'] ?? ''));
        $type = strtolower((string)($field['type'] ?? 'text'));
        $required = (bool)($field['required'] ?? false);

        if ($isConsultNotes) {
            $caseKey = 'consult_case';
            $chief = $pickFrom($seedSafe, $caseKey . '|chief', ['Fever and cough', 'Abdominal pain', 'Headache and dizziness', 'Chest pain (non-cardiac)', 'Low back pain', 'Sore throat', 'Vomiting and diarrhea']);
            $duration = $pickFrom($seedSafe, $caseKey . '|duration', ['1 day', '2 days', '3 days', '1 week', '2 weeks']);
            $start = $pickFrom($seedSafe, $caseKey . '|start', ['Yesterday', '2 days ago', 'This morning', 'Last week']);
            $assoc = $pickFrom($seedSafe, $caseKey . '|assoc', ['Nausea', 'Cough', 'Fatigue', 'Body malaise', 'No associated symptoms']);
            $factors = $pickFrom($seedSafe, $caseKey . '|factors', ['Worse with activity', 'Relieved by rest', 'Worse after meals', 'Relieved with hydration']);
            $pmhOther = $pickFrom($seedSafe, $caseKey . '|pmh_other', ['None', 'G6PD', 'Hyperlipidemia', 'Peptic ulcer disease']);
            $surg = $pickFrom($seedSafe, $caseKey . '|surg', ['None', 'Appendectomy (2015)', 'CS (2019)']);
            $meds = $pickFrom($seedSafe, $caseKey . '|meds', ['None', 'Paracetamol PRN', 'Amlodipine 5mg OD', 'Metformin 500mg BID']);
            $fam = $pickFrom($seedSafe, $caseKey . '|fam', ['None', 'Hypertension', 'Diabetes']);
            $addl = $pickFrom($seedSafe, $caseKey . '|addl', ['Patient advised on hydration and rest.', 'Return if symptoms worsen.', '']);
            $dx = $pickFrom($seedSafe, $caseKey . '|dx', ['Upper respiratory tract infection', 'Acute gastroenteritis', 'Tension headache', 'Muscle strain']);
            $ddx = $pickFrom($seedSafe, $caseKey . '|ddx', ['', 'GERD', 'UTI', 'Viral syndrome']);
            $inv = $pickFrom($seedSafe, $caseKey . '|inv', ['CBC', 'Urinalysis', 'RBS', '']);
            $planMeds = $pickFrom($seedSafe, $caseKey . '|plan_meds', ['Paracetamol 500mg q6h PRN', 'Oral rehydration salts', 'Ibuprofen 200mg PRN']);
            $advice = $pickFrom($seedSafe, $caseKey . '|advice', ['Increase oral fluids', 'Rest', 'Avoid oily/spicy food', '']);
            $follow = $pickFrom($seedSafe, $caseKey . '|follow', ['PRN / if symptoms persist', 'Follow-up in 3 days', 'Follow-up in 1 week']);
            $doc = $pickFrom($seedSafe, $caseKey . '|doc', ['Dr. Reyes', 'Dr. Santos', 'Dr. Garcia', 'Dr. Mendoza']);

            $pct = function (string $seed, string $key): int {
                $h = hash('sha256', $seed . '|' . $key);
                return (int)hexdec(substr($h, 0, 2));
            };

            if ($kind === 'checkbox') {
                $truePct = 15;
                if (str_contains($name, 'allergiesnone')) $truePct = 65;
                if (str_contains($name, 'pmh')) $truePct = 20;
                $on = $pct($seedSafe, 'chk|' . $name) < $truePct;
                return ['value' => $on ? '1' : '0', 'checked' => $on];
            }

            if ($kind === 'select') {
                $options = $field['options'] ?? [];
                if (is_array($options) && count($options) > 0) {
                    $vals = array_values(array_filter(array_map(function ($o) {
                        return is_array($o) && isset($o['value']) ? (string)$o['value'] : '';
                    }, $options), fn($v) => $v !== ''));
                    if (count($vals) > 0) {
                        return ['value' => $pickFrom($seedSafe, 'sel|' . $name, $vals)];
                    }
                }
                return ['value' => ''];
            }

            if ($kind === 'radio') {
                $options = $field['options'] ?? [];
                if (is_array($options) && count($options) > 0) {
                    $vals = array_values(array_filter(array_map(function ($o) {
                        return is_array($o) && isset($o['value']) ? (string)$o['value'] : '';
                    }, $options), fn($v) => $v !== ''));
                    if (count($vals) > 0) {
                        return ['value' => $pickFrom($seedSafe, 'rad|' . $name, $vals)];
                    }
                }
                return ['value' => ''];
            }

            if ($type === 'date') {
                return ['value' => date('Y-m-d')];
            }

            if (str_contains($name, 'hpistart')) return ['value' => $start];
            if (str_contains($name, 'hpiduration')) return ['value' => $duration];
            if (str_contains($name, 'hpiassociated')) return ['value' => $assoc];
            if (str_contains($name, 'hpifactors')) return ['value' => $factors];
            if (str_contains($name, 'pmhother')) return ['value' => $pmhOther];
            if (str_contains($name, 'surgicalhistory')) return ['value' => $surg];
            if (str_contains($name, 'currentmedications')) return ['value' => $meds];
            if (str_contains($name, 'allergiesother')) return ['value' => $pickFrom($seedSafe, $caseKey . '|allergy', ['', 'Penicillin', 'Seafood'])];
            if (str_contains($name, 'familyhistory')) return ['value' => $fam];
            if (str_contains($name, 'socialoccupation')) return ['value' => $pickFrom($seedSafe, $caseKey . '|occ', ['Office worker', 'Driver', 'Student', 'Vendor'])];
            if (str_contains($name, 'additionalnotes')) return ['value' => $addl];
            if (str_contains($name, 'patientsignature')) return ['value' => $pickFrom($seedSafe, $caseKey . '|psig', ['Patient', ''])];

            if (str_contains($name, 'soapchiefcomplaint')) return ['value' => $chief];
            if (str_contains($name, 'soapbp')) return ['value' => $pickFrom($seedSafe, $caseKey . '|bp', ['110/70', '120/80', '130/85'])];
            if (str_contains($name, 'soappulse')) return ['value' => $pickFrom($seedSafe, $caseKey . '|pulse', ['78', '82', '88', '92'])];
            if (str_contains($name, 'soaptemp')) return ['value' => $pickFrom($seedSafe, $caseKey . '|temp', ['36.8', '37.0', '37.5', '38.0'])];
            if (str_contains($name, 'soapexam')) return ['value' => $pickFrom($seedSafe, $caseKey . '|exam', ['Awake, alert. Lungs clear. Abdomen soft, non-tender.', 'No acute distress. Mild pharyngeal erythema.', 'Mild epigastric tenderness. No guarding.'])];
            if (str_contains($name, 'soapprimarydx')) return ['value' => $dx];
            if (str_contains($name, 'soapdifferentialdx')) return ['value' => $ddx];
            if (str_contains($name, 'soapinvestigations')) return ['value' => $inv];
            if (str_contains($name, 'soapmedications')) return ['value' => $planMeds];
            if (str_contains($name, 'soapadvice')) return ['value' => $advice];
            if (str_contains($name, 'soapfollowup')) return ['value' => $follow];
            if (str_contains($name, 'soapdoctorsignature')) return ['value' => $doc];

            return ['value' => ''];
        }

        if ($kind === 'select' || $kind === 'radio') {
            $options = $field['options'] ?? [];
            if (is_array($options) && count($options) > 0) {
                $first = $options[0];
                if (is_array($first) && isset($first['value'])) {
                    return ['value' => (string)$first['value']];
                }
            }
            return ['value' => ''];
        }

        if ($kind === 'checkbox') {
            $checked = $required;
            if (str_contains($name, 'consent') || str_contains($name, 'agree')) {
                $checked = true;
            }
            return ['value' => $checked ? '1' : '0', 'checked' => $checked];
        }

        if ($kind === 'digits') {
            $length = (int)($field['length'] ?? 0);
            if ($length <= 0) $length = 10;
            if (str_contains($label, 'birth') || str_contains($label, 'date')) {
                return ['value' => $digitsFrom($seedSafe, 'digits_birth_date', $length)];
            }
            if (str_contains($label, 'pin') || str_contains($label, 'philhealth')) {
                return ['value' => $digitsFrom($seedSafe, 'digits_philhealth_pin', $length)];
            }
            return ['value' => $digitsFrom($seedSafe, 'digits_generic', $length)];
        }

        // input
        if ($type === 'email' || str_contains($name, 'email')) {
            $fn = strtolower(str_replace(' ', '.', $pickFrom($seedSafe, 'first', $firstNames)));
            $ln = strtolower(str_replace(' ', '.', $pickFrom($seedSafe, 'last', $lastNames)));
            return ['value' => $fn . '.' . $ln . '@email.com'];
        }
        if ($type === 'tel' || str_contains($name, 'contact') || str_contains($name, 'phone') || str_contains($placeholder, '09')) {
            return ['value' => '09' . $digitsFrom($seedSafe, 'phone', 9)];
        }
        if ($type === 'date' || str_contains($name, 'dob') || str_contains($name, 'birth')) {
            $yyyy = (int)('19' . substr($digitsFrom($seedSafe, 'birth_year', 2), 0, 2));
            if ($yyyy < 1950) $yyyy = 1950 + ($yyyy % 55);
            if ($yyyy > 2006) $yyyy = 2006 - ($yyyy % 10);
            $mm = (int)substr($digitsFrom($seedSafe, 'birth_month', 2), 0, 2);
            if ($mm < 1) $mm = 1;
            if ($mm > 12) $mm = ($mm % 12) + 1;
            $dd = (int)substr($digitsFrom($seedSafe, 'birth_day', 2), 0, 2);
            if ($dd < 1) $dd = 1;
            if ($dd > 28) $dd = ($dd % 28) + 1;
            return ['value' => sprintf('%04d-%02d-%02d', $yyyy, $mm, $dd)];
        }
        if (str_contains($name, 'zip')) {
            return ['value' => substr($digitsFrom($seedSafe, 'zip', 4), 0, 4)];
        }
        if (str_contains($name, 'city')) {
            return ['value' => $pickFrom($seedSafe, 'city', $cities)];
        }
        if (str_contains($name, 'province')) {
            return ['value' => $pickFrom($seedSafe, 'province', $provinces)];
        }
        if (str_contains($name, 'barangay')) {
            return ['value' => $pickFrom($seedSafe, 'barangay', $barangays)];
        }
        if (str_contains($name, 'address')) {
            $num = (int)substr($digitsFrom($seedSafe, 'house_no', 3), 0, 3);
            if ($num < 1) $num = 1;
            return ['value' => (string)$num . ' ' . $pickFrom($seedSafe, 'street', $streets)];
        }
        if (str_contains($name, 'employer')) {
            $corp = $pickFrom($seedSafe, 'corp_word', ['Health', 'Care', 'General', 'Prime', 'City', 'Metro', 'Golden', 'Unity', 'North', 'South']);
            $suffix = $pickFrom($seedSafe, 'corp_suffix', ['Corporation', 'Inc.', 'Co.', 'Trading', 'Services']);
            return ['value' => $corp . ' ' . $suffix];
        }
        if (str_contains($name, 'philhealth') || str_contains($name, 'pin')) {
            $d = $digitsFrom($seedSafe, 'philhealth_pin_fmt', 12);
            return ['value' => substr($d, 0, 2) . '-' . substr($d, 2, 9) . '-' . substr($d, 11, 1)];
        }
        if (str_contains($name, 'name')) {
            $fn = $pickFrom($seedSafe, 'first', $firstNames);
            $ln = $pickFrom($seedSafe, 'last', $lastNames);
            $mn = $pickFrom($seedSafe, 'middle', $firstNames);
            return ['value' => $ln . ', ' . $fn . ' ' . $mn];
        }

        return ['value' => 'N/A'];
    };

    // Normalize to one entry per field index
    $n = count($fields);
    $byIndex = [];
    foreach ($out['values'] as $item) {
        if (!is_array($item)) {
            continue;
        }
        if (!isset($item['index'])) {
            continue;
        }
        $idx = (int)$item['index'];
        if ($idx < 0 || $idx >= $n) {
            continue;
        }
        $byIndex[$idx] = $item;
    }

    $normalized = [];
    for ($i = 0; $i < $n; $i++) {
        $item = $byIndex[$i] ?? null;
        $value = null;
        $checked = null;
        if (is_array($item)) {
            $value = $item['value'] ?? null;
            $checked = $item['checked'] ?? null;
        }

        $needsFallback = !is_string($value) || trim($value) === '';
        if (!$needsFallback && $isConsultNotes) {
            $vv = strtoupper(trim((string)$value));
            if ($vv === 'N/A' || $vv === 'NA') {
                $needsFallback = true;
            }
        }
        if ($needsFallback) {
            $fb = $fallbackForField(is_array($fields[$i] ?? null) ? $fields[$i] : []);
            $value = (string)($fb['value'] ?? '');
            if (array_key_exists('checked', $fb)) {
                $checked = $fb['checked'];
            }
        }

        $row = ['index' => $i, 'value' => $value];
        if ($checked !== null) {
            $row['checked'] = (bool)$checked;
        }
        $normalized[] = $row;
    }

    json_response([
        'ok' => true,
        'values' => $normalized,
    ]);
} catch (Throwable $e) {
    json_response([
        'ok' => false,
        'error' => $e->getMessage(),
    ], 500);
}
