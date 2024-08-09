<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// Read POST data
$data = json_decode(file_get_contents("php://input"), true);
$client_id = isset($data['client_id']) ? (int)$data['client_id'] : 0;
$database_name = isset($data['database_name']) ? $data['database_name'] : '';
$case_id = isset($data['case_id']) ? (int)$data['case_id'] : 0;
// Validate input
if ($client_id <= 0 || empty($database_name) || empty($case_id)) {
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

// Prepare the SQL query
$sql = "SELECT * FROM lab_file_attachments WHERE case_id = $case_id";
$result = $conn->query($sql);

// Execute the query
if ($result) {
    if ($result->num_rows > 0) {
        $attachments = array();
        
        // Fetch data and format it as an array
        while ($row = $result->fetch_assoc()) {
            $attachments[] = $row;
        }

        // Return the data as JSON
        echo json_encode(array('status' => 'success', 'data' => $attachments));
    } else {
        echo json_encode(array('status' => 'error', 'message' => 'No attachments found for the given case ID'));
    }
} else {
    echo json_encode(array('status' => 'error', 'message' => 'Failed to execute query'));
}

// Close the connection
$conn->close();
?>
