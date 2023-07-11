<?php if (isset($_GET['source'])) die(highlight_file(__FILE__, 1));
session_start();
include('connectionDB.php');

$response = array("success" => false, "message" => "No error");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['taskUserId'], $_POST['taskTitle'], $_POST['taskStartDate'], $_POST['taskCategory'], $_POST['taskDescription'])) {
        $stmt = $conn->prepare("INSERT INTO taches (nom_tache, user_id, date_debut, categorie_id, description) VALUES (?, ?, ?, ?, ?)");

        $stmt->bind_param("sisss", $_POST['taskTitle'], $_POST['taskUserId'], $_POST['taskStartDate'], $_POST['taskCategory'], $_POST['taskDescription']);

        if ($stmt->execute()) {
            $response["success"] = true;
            $response["message"] = "Task created successfully";
        } else {
            $response["message"] = "Error: " . $stmt->error;
        }
    } else {
        $response["message"] = "Missing required data";
    }    
} else {
    $response["message"] = "Error of request method";
}


echo json_encode($response);

?>