<?php

$target = 'http://127.0.0.1:8000/api.php';

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => true,
        'message' => 'Monitoring infusion API bridge is running',
        'target' => $target,
    ]);
    exit;
}

$body = file_get_contents('php://input') ?: '{}';

$context = stream_context_create([
    'http' => [
        'method' => 'POST',
        'header' => "Content-Type: application/json\r\n",
        'content' => $body,
        'timeout' => 5,
        'ignore_errors' => true,
    ],
]);

$response = @file_get_contents($target, false, $context);
$statusCode = 502;
$responseHeaders = function_exists('http_get_last_response_headers')
    ? http_get_last_response_headers()
    : [];

foreach ($responseHeaders ?: [] as $header) {
    if (preg_match('/^HTTP\/\S+\s+(\d+)/', $header, $matches)) {
        $statusCode = (int) $matches[1];
        break;
    }
}

http_response_code($statusCode);
header('Content-Type: application/json');
echo $response !== false ? $response : json_encode([
    'success' => false,
    'message' => 'Bridge failed to reach Docker API',
]);
