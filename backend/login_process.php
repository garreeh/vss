<?php
ob_start();
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);
$client_email = isset($data['client_email']) ? $data['client_email'] : '';
$client_password = isset($data['client_password']) ? $data['client_password'] : '';

// Connection details for all databases
$databases = [
    'animated' => ['conn_file' => 'conn_animated.php'],
    'avvet' => ['conn_file' => 'conn_av_vet.php'],
    'betlehem' => ['conn_file' => 'conn_betlehem.php'],
    'cebuvetcentral' => ['conn_file' => 'conn_cebuvetcentral.php'],
    'circleoflife' => ['conn_file' => 'conn_circleoflife.php'],
    'dre' => ['conn_file' => 'conn_dre.php'],
    'furpetsake' => ['conn_file' => 'conn_furpetsake.php'],
    'glittersparkle' => ['conn_file' => 'conn_glittersparkle.php'],
    'jollyvet' => ['conn_file' => 'conn_jollyvet.php'],
    'northeastvetclinic' => ['conn_file' => 'conn_northeastvetclinic.php'],
    'panacea' => ['conn_file' => 'conn_panacea.php'],
    'pawprints' => ['conn_file' => 'conn_pawprints.php'],
    'pet911' => ['conn_file' => 'conn_pet911.php'],
    'pet911camarin' => ['conn_file' => 'conn_pet911camarin.php'],
    'pethelp' => ['conn_file' => 'conn_pethelp.php'],
    'petish' => ['conn_file' => 'conn_petish.php'],
    'petlandia' => ['conn_file' => 'conn_petlandia.php'],
    'petlife' => ['conn_file' => 'conn_petlife.php'],
    'petstacular' => ['conn_file' => 'conn_petstacular.php'],
    'petwell' => ['conn_file' => 'conn_petwell.php'],
    'petworx' => ['conn_file' => 'conn_petworx.php'],
    'prinmevet' => ['conn_file' => 'conn_primevet.php'],
    'thecat' => ['conn_file' => 'conn_thecat.php'],
    'trial' => ['conn_file' => 'conn_trial.php'],
    'vetcorner' => ['conn_file' => 'conn_vetcorner.php'],
    'vetcornervc' => ['conn_file' => 'conn_vetcornervc.php'],
    'veterinarius' => ['conn_file' => 'conn_veterinarius.php'],
    'vetlink' => ['conn_file' => 'conn_vetlink.php'],
    'vetsquare' => ['conn_file' => 'conn_vetsquare.php'],
    'wtclinic' => ['conn_file' => 'conn_wtclinic.php'],
    'lafurry' => ['conn_file' => 'conn_lafurry.php'],
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
