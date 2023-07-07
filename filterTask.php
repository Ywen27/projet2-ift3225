<?php
session_start();
include('connectionDB.php');

$response = array('success' => false, "message" => "", "tasks" => array());

if (isset($_POST['filterTitle']) || isset($_POST['filterStartDate']) || isset($_POST['filterEndDate']) || isset($_POST['filterCategory']) || isset($_POST['filterState'])) {
    $title = isset($_POST['filterTitle']) ? $_POST['filterTitle'] : '';
    $startDate = isset($_POST['filterStartDate']) ? $_POST['filterStartDate'] : '';
    $endDate = isset($_POST['filterEndDate']) ? $_POST['filterEndDate'] : '';
    $category = isset($_POST['filterCategory']) ? $_POST['filterCategory'] : '';
    $state = isset($_POST['filterState']) ? $_POST['filterState'] : '';

    $sql = "SELECT * FROM taches WHERE user_id = {$_SESSION['user_id']}";

    if ($title != '') {
        $sql .= " AND nom_tache LIKE '%$title%'";
    }
    if ($startDate != '') {
        $sql .= " AND date_debut = '$startDate'";
    }
    if ($endDate != '') {
        $sql .= " AND date_fin = '$endDate'";
    }
    if ($category != '') {
        $sql .= " AND categorie_id = $category";
    }
    if ($state != '') {
        $sql .= " AND etat = '$state'";
    }

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $response['tasks'] = array();

        while ($row = $result->fetch_assoc()) {
            $response['tasks'][] = $row;
        }
        $response['success'] = true;
    } else {
        $response['success'] = true;
        $response['message'] = "No tasks found.";
    }
} else {
    $response['message'] = "No filter data sent.";
}

echo json_encode($response);
?>
