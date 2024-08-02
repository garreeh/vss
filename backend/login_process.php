<?php
include "../connection/connection.php";
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Read POST data
$data = json_decode(file_get_contents("php://input"), true);
error_log("Received data: " . print_r($data, true)); // Log received data

$user_name = isset($data['user_name']) ? $data['user_name'] : '';
$user_password = isset($data['user_password']) ? $data['user_password'] : '';

// Check credentials (this is just a simple example, use prepared statements and hashing for real applications)
$sql = "SELECT user_firstname, user_lastname FROM user WHERE user_name = '$user_name' AND user_password = '$user_password'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    
    // Example token, you should generate a secure token or use JWT in a real application
    $token = bin2hex(random_bytes(16));

    echo json_encode(array(
        'status' => 'success',
        'message' => 'Login successful!',
        'token' => $token,
        'user_firstname' => $user['user_firstname'],
        'user_lastname' => $user['user_lastname']
    ));
} else {
    echo json_encode(array('status' => 'error', 'message' => 'Invalid username or password.'));
}

$conn->close();
?>
