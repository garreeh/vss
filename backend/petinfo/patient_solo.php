<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// Read POST data
$data = json_decode(file_get_contents("php://input"), true);
$client_id = isset($data['client_id']) ? (int)$data['client_id'] : 0;
$database_name = isset($data['database_name']) ? $data['database_name'] : '';
$patient_name = isset($data['patient_name']) ? $data['patient_name'] : '';

// Validate input
if ($client_id <= 0 || empty($database_name) || empty($patient_name)) {
    echo json_encode(array('status' => 'error', 'message' => 'Invalid input.'));
    exit();
}

// Connection details for all databases
$databases = [
    'vssphcom_animed' => 'conn_animed.php',
    'vssphcom_avvet' =>  'conn_av_vet.php',
    'vssphcom_betlehem' =>  'conn_betlehem.php',
    'vssphcom_cebuvetcentral' =>  'conn_cebuvetcentral.php',
    'vssphcom_circleoflife' =>  'conn_circleoflife.php',
    'vssphcom_dre' =>  'conn_dre.php',
    'vssphcom_furpetsake' =>  'conn_furpetsake.php',
    'vssphcom_glittersparkle' =>  'conn_glittersparkle.php',
    'vssphcom_jollyvet' =>  'conn_jollyvet.php',
    'vssphcom_northeastvetclinic' =>  'conn_northeastvetclinic.php',
    'vssphcom_panacea' =>  'conn_panacea.php',
    'vssphcom_pawprints' =>  'conn_pawprints.php',
    'vssphcom_pet911' =>  'conn_pet911.php',
    'vssphcom_pet911camarin' =>  'conn_pet911camarin.php',
    'vssphcom_pethelp' =>  'conn_pethelp.php',
    'vssphcom_petish' =>  'conn_petish.php',
    'vssphcom_petlandia' =>  'conn_petlandia.php',
    'vssphcom_petlife' =>  'conn_petlife.php',
    'vssphcom_petstacular' =>  'conn_petstacular.php',
    'vssphcom_petwell' =>  'conn_petwell.php',
    'vssphcom_petworx' =>  'conn_petworx.php',
    'vssphcom_prinmevet' =>  'conn_primevet.php',
    'vssphcom_thecat' =>  'conn_thecat.php',
    'vssphcom_trial' =>  'conn_trial.php',
    'vssphcom_vetcorner' =>  'conn_vetcorner.php',
    'vssphcom_vetcornervc' =>  'conn_vetcornervc.php',
    'vssphcom_veterinarius' =>  'conn_veterinarius.php',
    'vssphcom_vetlink' =>  'conn_vetlink.php',
    'vssphcom_vetsquare' =>  'conn_vetsquare.php',
    'vssphcom_wtclinic' =>  'conn_wtclinic.php',
    'vssphcom_lafurry' =>  'conn_lafurry.php',
];


if (!isset($databases[$database_name])) {
    echo json_encode(array('status' => 'error', 'message' => 'Invalid database name.'));
    exit();
}

// Include the correct connection file
include "./../../connection/" . $databases[$database_name];

if (!$conn) {
    echo json_encode(array('status' => 'error', 'message' => 'Database connection failed.'));
    exit();
}

// Query the database for the patient details
$sql = "SELECT * FROM patient 
        LEFT JOIN client ON patient.client_id = client.client_id
        WHERE client.client_id = '$client_id' AND patient.patient_name = '$patient_name'";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();

    $pet = array(
        'patient_id' => $row['patient_id'],
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
