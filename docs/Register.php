<?php
// register a new user with hashed password
require_once __DIR__ . '/../src/helpers.php';

$inData = getRequestJson();

if(!isset($inData['firstName']) || !isset($inData['lastName']) || !isset($inData['login']) || !isset($inData['password'])) {
    sendError("Missing required fields", 400);
}

$firstName = trim($inData['firstName']);
$lastName = trim($inData['lastName']);
$login = trim($inData['login']);
$password = password_hash(trim($inData['password']), PASSWORD_DEFAULT);

$conn = getDbConnection();
$stmt = $conn->prepare("INSERT INTO Users (firstName, lastName, Login, Password) VALUES (?, ?, ?, ?)");

if(!$stmt) {
    sendError("Database error: " . $conn->error, 500);
}

$stmt->bind_param("ssss", $firstName, $lastName, $login, $password);

if($stmt->execute()) {
    $newUserID = $stmt->insert_id;
    $response = [
        "success"   => true,
        "ID"        => $newUserID,
        "firstName" => $firstName,
        "lastName"  => $lastName,
        "error"     => null
    ];
    sendJson($response, 201);
} else {
    if($conn->errno === 1062) { // Duplicate entry error code
        sendError("Login already exists", 409);
    }
    sendError("Failed to register user", 500);
}

$stmt->close();
$conn->close();
?>
