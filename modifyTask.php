<?php if (isset($_GET['source'])) die(highlight_file(__FILE__, 1));
session_start();
include('connectionDB.php');

$response = array('success' => false, "message" => "");

if (isset($_POST['taskId']) || isset($_POST['modifiedTitle']) || isset($_POST['modifiedStartDate']) || isset($_POST['modifiedCategory']) || isset($_POST['modifiedDescription'])) {
    $title = $_POST['modifiedTitle'];
    $startDate = $_POST['modifiedStartDate'];
    $category = $_POST['modifiedCategory'];
    $description = isset($_POST['modifiedDescription']) ? $_POST['modifiedDescription'] : null;

    $stmt = $conn->prepare("UPDATE taches SET nom_tache = ?, date_debut = ?, categorie_id = ?, description = ? WHERE tache_id = ?");
    $stmt->bind_param('ssssi',$title, $startDate, $category, $description, $_POST['taskId']);
    $result = $stmt->execute();

    if ($result) {
        $response["success"] = true;
        $response["message"] = "Task modified successfully";
    } else {
        $response["message"] = "Error: " . $stmt->error;
    }
} else {
    $response['message'] = "No modified data sent.";
}

echo json_encode($response);
?>
