<?php

global $conn;

if( !isset($_POST["ID"])) {
    die("Post variables not set. Need question ID");
}

include 'connect2.php';

$sql="DELETE FROM Questions WHERE ID=" . $_POST["ID"];
$result = mysqli_query($conn, $sql);
$error = mysqli_error($conn);
if($error)
{
    echo $error;
} else 
{
    echo $result;
}

mysqli_close($conn);

?>
