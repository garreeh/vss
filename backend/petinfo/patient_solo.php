<?php
include "../../connection/connection.php";
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

$data = json_decode(file_get_contents("php://input"), true);
$client_id = isset($data['client_id']) ? (int)$data['client_id'] : 0;
$patient_name = isset($data['patient_name']) ? $conn->real_escape_string($data['patient_name']) : '';

if ($client_id <= 0 || empty($patient_name)) {
    echo json_encode(array('status' => 'error', 'message' => 'Invalid client ID or pet name.'));
    exit();
}

$sql = "SELECT * FROM patient LEFT JOIN client ON patient.client_id = client.client_id WHERE client.client_id = '$client_id' AND patient.patient_name = '$patient_name'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $pet = array(
        'patient_name' => $row['patient_name'],
        'patient_microchip' => $row['patient_microchip'],
        'patient_breed' => $row['patient_breed'],
        'patient_species' => $row['patient_species'],
        'patient_birthdate' => $row['patient_birthdate'],
        'patient_neutered' => $row['patient_neutered'],
    );

    echo json_encode(array(
        'status' => 'success',
        'data' => $pet
    ));
} else {
    echo json_encode(array('status' => 'error', 'message' => 'No pet found with this name for the client.'));
}

$conn->close();
?>
