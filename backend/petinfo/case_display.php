<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// Read POST data
$data = json_decode(file_get_contents("php://input"), true);
$client_id = isset($data['client_id']) ? (int)$data['client_id'] : 0;
$database_name = isset($data['database_name']) ? $data['database_name'] : '';

// Validate input
if ($client_id <= 0 || empty($database_name)) {
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

$sql = "SELECT * FROM `case`
        LEFT JOIN patient ON `case`.patient_id = patient.patient_id
        WHERE patient.client_id = '$client_id'";
$result = $conn->query($sql);

$pets = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $case_date = date("Y-m-d", strtotime($row['case_date']));
        $case_date_followup = date("Y-m-d", strtotime($row['case_date_followup']));
        
        $pets[] = array(
            'patient_id' => $row['patient_id'],
            'patient_name' => $row['patient_name'],
            'case_id' => $row['case_id'],
            'case_date' => $case_date,
            'case_date_followup' => $case_date_followup,
            'attending_vet' => $row['attending_vet'],
            'temp' => $row['temp'],
            'weight' => $row['weight'],
            'case_chief_complaint' => $row['case_chief_complaint'],
            'case_laboratory_requested' => $row['case_laboratory_requested'],
            'case_differential' => $row['case_differential'],
            'working_diagnosis' => $row['working_diagnosis'],
            'prescription' => $row['prescription'],
            'treatment' => $row['treatment'],
            'patient_history' => $row['patient_history'],
            'case_status' => $row['case_status'],
        );
    }
    echo json_encode(array('status' => 'success', 'data' => $pets));
} else {
    echo json_encode(array('status' => 'error', 'message' => 'No cases found for this patient.'));
}


$conn->close();
?>
