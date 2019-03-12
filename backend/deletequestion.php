<?php

global $conn;

if( !isset($_POST["ID"])) {
    die('{"POST":"variables not set. Need question ID" }');
}

include 'connect2.php';

$sql="DELETE FROM Questions WHERE ID=" . $_POST["ID"];
$result = mysqli_query($conn, $sql);
$error = mysqli_error($conn);
if($error)
{
    echo '{ "dbSuccess":false, SQL_ERROR:' . json_encode($error, JSON_UNESCAPED_UNICODE) . '}';
} else 
{
    echo '{ "dbSuccess":true }';
}

mysqli_close($conn);

?>
