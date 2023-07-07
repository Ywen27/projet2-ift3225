<?php if (isset($_GET['source'])) die(highlight_file(__FILE__, 1));
session_start();
include('connectionDB.php');
$response = array('success' => false, "message" => "");

if (!isset($_SESSION['user_id'], $_POST['$taskId']) ) {
    $taskId = $_POST['taskId'];

    $stmt = $conn->prepare("UPDATE taches SET etat = 'complete', date_fin = CURDATE() WHERE tache_id = ?");
    
    $stmt->bind_param('i', $taskId);
    $result = $stmt->execute();

    if ($result) {
        $response["success"] = true;
        $response["message"] = "Task finished successfully";
    } else {
        $response["message"] = "Error: " . $stmt->error;
    }
    

} else {
    $response["message"] = "Missing required data";
}

echo json_encode($response);

?>
