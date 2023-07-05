<?php
session_start();
include('connectionDB.php');

$response = array("success" => false, "tasks" => array());

if (isset($_SESSION['user_id'])) {
    $stmt = $conn->prepare("SELECT * FROM taches WHERE user_id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $response["tasks"][] = $row;
        }
        $response["success"] = true;
    }
}
?>