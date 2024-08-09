<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// Read POST data
$data = json_decode(file_get_contents("php://input"), true);
$client_id = isset($data['client_id']) ? (int)$data['client_id'] : 0;
$database_name = isset($data['database_name']) ? $data['database_name'] : '';

if ($client_id <= 0 || empty($database_name)) {
    echo json_encode(array('status' => 'error', 'message' => 'Invalid client ID or database name.'));
    exit();
}

// Connection details for all databases
$databases = [
    'vssphcom_pet911' => 'conn_pet911.php',
    'vssphcom_petish' => 'conn_petish.php',
    'vssphcom_trial' => 'conn_trial.php'
];

if (!isset($databases[$database_name])) {
    echo json_encode(array('status' => 'error', 'message' => 'Invalid database name.'));
    exit();
}

// Include the correct connection file
include "../connection/" . $databases[$database_name];

if (!$conn) {
    echo json_encode(array('status' => 'error', 'message' => 'Database connection failed.'));
    exit();
}

// Fetch patient data
$sql = "SELECT * FROM patient LEFT JOIN client ON patient.client_id = client.client_id WHERE client.client_id = '$client_id'";
$result = $conn->query($sql);

$pets = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $pets[] = array(
            'patient_id' => $row['patient_id'],
            'patient_name' => $row['patient_name'],
            'patient_microchip' => $row['patient_microchip'],
            'patient_breed' => $row['patient_breed'],
            'patient_species' => $row['patient_species'],
            'patient_birthdate' => $row['patient_birthdate'],
            'patient_neutered' => $row['patient_neutered'],
        );
    }
    echo json_encode(array(
        'status' => 'success',
        'data' => $pets
    ));
} else {
    echo json_encode(array('status' => 'error', 'message' => 'No pets found for this client.'));
}

$conn->close();
?>
