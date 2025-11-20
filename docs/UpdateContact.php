<?php
// update a contact
require_once __DIR__ . '/../src/helpers.php';

$inData = getRequestJson();

if(!isset($inData['ID']) || !isset($inData['firstName']) || !isset($inData['lastName']) || !isset($inData['phone']) || !isset($inData['email'])) {
    sendError("Missing required contact fields", 400);
}

if(!filter_var($inData['email'], FILTER_VALIDATE_EMAIL)) {
    sendError("Invalid email format", 400);
}

$conn = getDbConnection();

$stmt = $conn->prepare("UPDATE Contacts SET firstName=?, lastName=?, phone=?, email=? WHERE ID=?");

if(!$stmt) {
    sendError("Database error: " . $conn->error, 500);
}

$stmt->bind_param("ssssi", $inData['firstName'], $inData['lastName'], $inData['phone'], $inData['email'], $inData['ID']);
$stmt->execute();

if($stmt->affected_rows > 0) {
    sendJson(["success" => true, "message" => "Contact updated successfully"], 200);
} else {
    sendError("Contact not found or no changes made", 404);
}

$stmt->close();
$conn->close();
?>
