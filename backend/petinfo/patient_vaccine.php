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
    echo json_encode(array('status' => 'error', 'message' => 'Invalid data.'));
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

$sql = "SELECT * FROM vaccination
        LEFT JOIN patient ON vaccination.patient_id = patient.patient_id
        WHERE patient.client_id = '$client_id' AND patient.patient_name = '$patient_name'";
$result = $conn->query($sql);

$pets = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $date_vaccine = date("Y-m-d", strtotime($row['date_vaccine']));
        $expiration = date("Y-m-d", strtotime($row['expiration']));
        
        $pets[] = array(
          'patient_id' => $row['patient_id'],
          'vaccine_id' => $row['vaccine_id'],
          'attending_vet' => $row['attending_vet'],
          'type_of_vaccine' => $row['type_of_vaccine'],
          'efficacy' => $row['efficacy'],
          'lot_number' => $row['lot_number'],
          'brand' => $row['brand'],
          'date_vaccine' => $date_vaccine,
          'expiration' => $expiration,
          'weight' => $row['weight'],
          'deworming' => $row['deworming'],
        );
    }
    echo json_encode(array('status' => 'success', 'data' => $pets));
} else {
    echo json_encode(array('status' => 'error', 'message' => 'No vaccine found for this patient.'));
}

$conn->close();
?>
