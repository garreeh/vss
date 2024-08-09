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
    'vssphcom_pet911' => 'conn_pet911.php',
    'vssphcom_petish' => 'conn_petish.php',
    'vssphcom_trial' => 'conn_trial.php'
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
