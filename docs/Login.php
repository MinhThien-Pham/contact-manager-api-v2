<?php
// user login
require_once __DIR__ . '/../src/helpers.php';

$inData = getRequestJson();

if(!isset($inData['login']) || !isset($inData['password'])) {
    sendError("Missing login or password", 400);
}

$login = $inData['login'];
$password = $inData['password'];

$conn = getDbConnection();

$stmt = $conn->prepare("SELECT ID, firstName, lastName, Password FROM Users WHERE Login = ?");
$stmt->bind_param("s", $login);
$stmt->execute();
$result = $stmt->get_result();

if($row = $result->fetch_assoc()) {
    $storedHash = $row['Password'];
    if(password_verify($password, $storedHash)) {  // Verify the password against the stored hash
        // Successful login
        $response = [
            "success"   => true,
            "ID"        => $row['ID'],
            "firstName" => $row['firstName'],
            "lastName"  => $row['lastName'],
            "error"     => null
        ];
        sendJson($response, 200);
    } else {
        sendError("Invalid login or password", 401);
    }
}
else {
    sendError("Invalid login or password", 401);
}

$stmt->close();
$conn->close();
?>
