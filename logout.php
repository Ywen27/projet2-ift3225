<?php if (isset($_GET['source'])) die(highlight_file(__FILE__, 1));
session_start();
session_destroy();
header('Location: login.php');
exit;
?>