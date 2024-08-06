<?php
include "../connection/connection.php";
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Read POST data
$data = json_decode(file_get_contents("php://input"), true);
error_log("Received data: " . print_r($data, true)); // Log received data

$client_email = isset($data['client_email']) ? $data['client_email'] : '';
$client_password = isset($data['client_password']) ? $data['client_password'] : '';

// Sanitize inputs
$client_email = $conn->real_escape_string($client_email);

// Fetch user record
$sql = "SELECT * FROM client WHERE client_email = '$client_email'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();

    // Verify password
    if (password_verify($client_password, $user['client_password_decode'])) {
        // Generate token (use JWT in a real application)
        $token = bin2hex(random_bytes(16));

        // Return user data including ID
        echo json_encode(array(
            'status' => 'success',
            'message' => 'Login successful!',
            'token' => $token,
            'client_id' => $user['client_id'],
            'client_firstname' => $user['client_firstname'],
            'client_lastname' => $user['client_lastname']
        ));
    } else {
        echo json_encode(array('status' => 'error', 'message' => 'Invalid password.'));
    }
} else {
    echo json_encode(array('status' => 'error', 'message' => 'Invalid email.'));
}

$conn->close();
?>
