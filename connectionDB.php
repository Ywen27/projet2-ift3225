<?php if (isset($_GET['source'])) die(highlight_file(__FILE__, 1));

include("pwdDB.php");

$db_user = "yinwen";
$db_host = "www-ens.iro.umontreal.ca";
$db_name = "yinwen_projet2_ift3225";

$conn = new mysqli($db_host, $db_user, $db_password, $db_name);
if ($conn->connect_error)
    die("Connection failed: " . $conn->connect_error);

echo "Connection success\n"

?>