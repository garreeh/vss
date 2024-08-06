<?php
include "../connection/connection.php";
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

$data = json_decode(file_get_contents("php://input"), true);
$client_id = isset($data['client_id']) ? (int)$data['client_id'] : 0;

if ($client_id <= 0) {
    echo json_encode(array('status' => 'error', 'message' => 'Invalid client ID.'));
    exit();
}

$sql = "SELECT * FROM client WHERE client_id = '$client_id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    echo json_encode(array(
        'status' => 'success',
        'data' => array(
            'client_address' => $user['client_address'],
            'client_email' => $user['client_email'],
            'client_mobile' => $user['client_mobile'],
            'client_home' => $user['client_home']
        )
    ));
} else {
    echo json_encode(array('status' => 'error', 'message' => 'User not found.'));
}

$conn->close();
?>
