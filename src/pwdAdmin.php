<?php if (isset($_GET['source'])) die(highlight_file(__FILE__, 1));
$password = 'yass1234';
$hash = password_hash($password, PASSWORD_BCRYPT);
echo $hash;
?>