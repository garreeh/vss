<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// $password = 'test1005';
// $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// echo $hashedPassword;

$data = json_decode(file_get_contents("php://input"), true);
$client_email = isset($data['client_email']) ? $data['client_email'] : '';
$client_password = isset($data['client_password']) ? $data['client_password'] : '';

// Connection details for all databases
$databases = [
    'pet911' => ['conn_file' => 'conn_pet911.php'],
    'petish' => ['conn_file' => 'conn_petish.php'],
    'trial' => ['conn_file' => 'conn_trial.php']
];

$found = false;
$database_name = '';



foreach ($databases as $dbname => $details) {
    include "../connection/" . $details['conn_file'];

    if (!$conn) {
        echo json_encode(array('status' => 'error', 'message' => 'Database connection failed.'));
        exit();
    }

    $client_email = $conn->real_escape_string($client_email);
    $sql = "SELECT * FROM client WHERE client_email = '$client_email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $found = true;
        $user = $result->fetch_assoc();
        $database_name = $dbname; // Record which database was used
        break;
    }

    $conn->close();
}

if (!$found) {
    echo json_encode(array('status' => 'error', 'message' => 'Invalid email.'));
    exit();
}

if (password_verify($client_password, $user['client_password_decode'])) {
    $token = bin2hex(random_bytes(16));

    echo json_encode(array(
        'status' => 'success',
        'message' => 'Login successful!',
        'token' => $token,
        'client_id' => $user['client_id'],
        'client_firstname' => $user['client_firstname'],
        'client_lastname' => $user['client_lastname'],
        'client_email' => $user['client_email'],
        'database_name' => $database_name // Include the database name
    ));
} else {
    echo json_encode(array('status' => 'error', 'message' => 'Invalid password.'));
}

?>
