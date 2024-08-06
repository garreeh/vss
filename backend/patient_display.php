<?php
include "../connection/connection.php";
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Read POST data
$data = json_decode(file_get_contents("php://input"), true);
error_log("Received data: " . print_r($data, true)); // Log received data

// Extract client_id from request data
$client_id = isset($data['client_id']) ? intval($data['client_id']) : 0;

// Validate client_id
if ($client_id <= 0) {
    echo json_encode(array('status' => 'error', 'message' => 'Invalid client ID.'));
    exit;
}

// Sanitize client_id
$client_id = $conn->real_escape_string($client_id);

// Fetch data specific to the authenticated user
$sql = "SELECT * FROM patient LEFT JOIN client ON patient.client_id = client.client_id WHERE client.client_id = '$client_id'";
$result = $conn->query($sql);

// Check if any data is found
if ($result->num_rows > 0) {
    // Fetch all results as associative array
    $data = $result->fetch_all(MYSQLI_ASSOC);

    // Output the data as JSON
    echo json_encode(array(
        'status' => 'success',
        'data' => $data
    ));
} else {
    echo json_encode(array('status' => 'error', 'message' => 'No data found for the specified client ID.'));
}

$conn->close();
?>
