<?php
require_once __DIR__ . '/../config/config.php';

function getDbConnection() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) {
        sendError("Database connection failed: " . $conn->connect_error, 500);
    }
    $conn->set_charset("utf8mb4");
    return $conn;
}

function getRequestJson() {
    // check invalid json
    $input = file_get_contents('php://input');
    if ($input === false) {
        sendError("Failed to read input", 400);
    }
    $data = json_decode($input, true);
    if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
        sendError("Invalid JSON input", 400);
    }
    return $data;
}

function sendJson($data, int $status = 200) {
    http_response_code($status);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

function sendError(string $message, int $status = 400) {
    sendJson(['error' => $message], $status);
}
?>