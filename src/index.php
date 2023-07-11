<?php if (isset($_GET['source'])) die(highlight_file(__FILE__, 1));
session_start();

// Vérifiez si l'utilisateur est connecté
if (isset($_SESSION['user_id'])) {
    // L'utilisateur est connecté, dirigez-le vers le tableau de bord
    if($_SESSION['role'] == 'user'){
        header('Location: dashboard.php');
        exit;
    }else{
        header('Location: dashboardAdmin.php');
        exit;
    }
    
} else {
    // L'utilisateur n'est pas connecté, dirigez-le vers la page de connexion
    header('Location: login.php');
    exit;
}
?>
