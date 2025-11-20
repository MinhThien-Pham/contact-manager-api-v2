<?php
// add a new contact
require_once __DIR__ . '/helpers.php';

$inData = getRequestJson();

if(!isset($inData['firstName']) || !isset($inData['lastName']) || !isset($inData['phone']) || !isset($inData['email']) || !isset($inData['userID'])) {
    sendError("Missing required contact fields", 400);
}

$firstName = trim($inData['firstName']);
$lastName = trim($inData['lastName']);
$phone = trim($inData['phone']);
$email = trim($inData['email']);
$userID = (int)$inData['userID'];

if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    sendError("Invalid email format", 400);
}

$conn = getDbConnection();

$stmt = $conn->prepare("INSERT INTO Contacts (firstName, lastName, phone, email, userID) VALUES (?, ?, ?, ?, ?)");

if(!$stmt) {
    sendError("Database error: " . $conn->error, 500);
}

$stmt->bind_param("ssssi", $firstName, $lastName, $phone, $email, $userID);

if($stmt->execute()) {
    $newContactID = $stmt->insert_id;
    $response = [
        "success"    => true,
        "contactID"  => $newContactID,
        "error"      => null
    ];
    sendJson($response, 201);
} else {
    sendError("Failed to add contact", 500);
}

$stmt->close();
$conn->close();
?>
