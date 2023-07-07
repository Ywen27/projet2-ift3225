<?php
session_start();
include('connectionDB.php');
$response = array('success' => false, "message" => "");
// Vérifiez si l'utilisateur est connecté
if (isset($_SESSION['user_id'], $_POST['$taskId']) ) {
    $taskId = $_POST['taskId'];

    // Préparez la requête SQL pour supprimer la tâche
    $stmt = $conn->prepare("DELETE FROM taches WHERE tache_id = ?");
    
    // Lier les paramètres et exécuter la requête
    $stmt->bind_param('i', $taskId);
    $result = $stmt->execute();

        // Vérifiez si la requête a réussi
    if ($result) {
        $response["message"] = "Task deleted successfully";
        
    } else {
        $response["message"] = "Error: " . $stmt->error;
    }
    

} else {
    $response["message"] = "Missing required data";
}

echo json_encode($response);



?>
