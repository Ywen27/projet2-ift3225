<?php if (isset($_GET['source'])) die(highlight_file(__FILE__, 1));
session_start();
include('connectionDB.php');
session_destroy();
mysqli_close($conn) or die("Probleme lors de la fermeture de la connection ". msqli_error());
header('Location: login.php');
exit;
?>