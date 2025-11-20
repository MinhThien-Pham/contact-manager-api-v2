<?php
// search for contacts
require_once __DIR__ . '/../src/helpers.php';

$inData = getRequestJson();

if(!isset($inData['searchTerm']) || !isset($inData['userID'])) {
    sendError("Missing searchTerm or userID", 400);
}

$searchCount = 0;
$searchResults = "";

$conn = getDbConnection();

$searchTerm = "%" . $inData['searchTerm'] . "%";
$userID = (int)$inData['userID'];
$stmt = $conn->prepare("SELECT * FROM Contacts WHERE (firstName LIKE ? OR lastName LIKE ? OR phone LIKE ? OR email LIKE ?) AND userID = ?");

if(!$stmt) {
    sendError("Database error: " . $conn->error, 500);
}

$stmt->bind_param("ssssi", $searchTerm, $searchTerm, $searchTerm, $searchTerm, $userID);
$stmt->execute();

$result  = $stmt->get_result();
$results = [];

while($row = $result->fetch_assoc()) {
    $results[] = $row;
}

if(count($results) > 0) {
    $response = [
        "results" => $results,
        "error"   => null
    ];
    sendJson($response, 200);
} else {
    sendError("No contacts found", 404);
}

$stmt->close();
$conn->close();
?>
