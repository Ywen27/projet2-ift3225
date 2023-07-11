<?php if (isset($_GET['source']))
    die(highlight_file(__FILE__, 1));
session_start();
include('connectionDB.php');

$response = array('success' => false, "message" => "", "tasks" => array());

if (isset($_POST['filterUserId']) || isset($_POST['filterTitle']) || isset($_POST['filterStartDate']) || isset($_POST['filterEndDate']) || isset($_POST['filterCategory']) || isset($_POST['filterState'])) {
    $userId = isset($_POST['filterUserId']) ? $_POST['filterUserId'] : '';
    $title = isset($_POST['filterTitle']) ? $_POST['filterTitle'] : '';
    $startDate = isset($_POST['filterStartDate']) ? $_POST['filterStartDate'] : '';
    $endDate = isset($_POST['filterEndDate']) ? $_POST['filterEndDate'] : '';
    $category = isset($_POST['filterCategory']) ? $_POST['filterCategory'] : '';
    $state = isset($_POST['filterState']) ? $_POST['filterState'] : '';

    $sql = "SELECT * FROM taches ";

    $filters = [];

    if ($userId != '') {
        $filters[] = "user_id = $userId";
    }
    if ($title != '') {
        $filters[] = "nom_tache LIKE '%$title%'";
    }
    if ($startDate != '') {
        $filters[] = "date_debut = '$startDate'";
    }
    if ($endDate != '') {
        $filters[] = "date_fin = '$endDate'";
    }
    if ($category != '') {
        $filters[] = "categorie_id = $category";
    }
    if ($state != '') {
        $filters[] = "etat = '$state'";
    }

    if (!empty($filters)) {
        $sql .= " WHERE " . implode(" AND ", $filters);
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