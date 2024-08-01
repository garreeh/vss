<?php
header("Access-Control-Allow-Origin: *"); // Allow all origins. Adjust if needed for security.
header("Access-Control-Allow-Methods: GET, POST, OPTIONS"); // Allow specific methods
header("Access-Control-Allow-Headers: Content-Type"); // Allow specific headers

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "vss_clean";

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
