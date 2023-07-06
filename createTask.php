<?php
session_start();
include('connectionDB.php');

$response = array("success" => false);

if (isset($_SESSION['user_id'], $_POST['task-title'], $_POST['task-start-date'], $_POST['task-category'], $_POST['task-description'])) {

    $stmt = $conn->prepare("INSERT INTO taches (nom_tache, user_id, date_debut, categorie_id, description) VALUES (?, ?, ?, ?, ?)");

    $stmt->bind_param("sisss", $_POST['task-title'], $_SESSION['user_id'], $_POST['task-start-date'], $_POST['task-category'], $_POST['task-description']);

    if ($stmt->execute()) {
        $response["success"] = true;
    }
}

echo json_encode($response);

?>