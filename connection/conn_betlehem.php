<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

$servername = "localhost";
$username = "vssphcom_btlhm";
$password = "GreatCoal081677";
$dbname = "vssphcom_betlehem";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    echo json_encode(array('status' => 'error', 'message' => 'Connection failed: ' . $conn->connect_error));
    exit();
} else {
    // Comment this when it's successful.
    // echo json_encode(array('status' => 'success', 'message' => 'Connection Succeed!'));
}
?>
