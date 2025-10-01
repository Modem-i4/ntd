<?php
// app/api/utils.php — утиліти для API

function json_input(): array {
    $raw = file_get_contents('php://input');
    $data = json_decode($raw, true);
    if (!is_array($data)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid JSON body']);
        exit;
    }
    return $data;
}

function respond($data, int $code = 200): void {
    http_response_code($code);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
}

function require_fields(array $data, array $keys): void {
    foreach ($keys as $k) {
        if (!array_key_exists($k, $data)) {
            respond(['error' => "Missing field: $k"], 400);
            exit;
        }
    }
}

function int_or_null($v) {
    if ($v === null || $v === '' || !is_numeric($v)) return null;
    return (int)$v;
}
