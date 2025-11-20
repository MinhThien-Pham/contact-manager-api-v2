<?php
// delete a contact
require_once __DIR__ . '/../src/helpers.php';

$inData = getRequestJson();

if(!isset($inData['id'])) {
    sendError("Missing contact ID", 400);
}

$contactID = (int)$inData['id'];

$conn = getDbConnection();
$stmt = $conn->prepare("DELETE FROM Contacts WHERE ID = ?");

if(!$stmt) {
    sendError("Database error: " . $conn->error, 500);
}

$stmt->bind_param("i", $contactID);

if($stmt->execute()) {
    if($stmt->affected_rows === 0) {
        sendError("Contact not found", 404);
    }
    $response = [
        "success" => true,
        "error"   => null
    ];
    sendJson($response, 200);
} else {
    sendError("Failed to delete contact", 500);

}
$stmt->close();
$conn->close();
?>
