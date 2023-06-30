<?php if (isset($_GET['source'])) die(highlight_file(__FILE__, 1));

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

include('connectionDB.php');





mysqli_close($conn) or die("Probleme lors de la fermeture de la connection ". msqli_error());

echo "Test\n"

?>

