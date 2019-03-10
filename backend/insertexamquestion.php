<?php

global $conn;

include 'connect2.php';

if(!isset($_POST["QuestionID"])) {
    die('{"POST":"Question ID not set"}');
}

if(!isset($_POST["Points"])) {
    die('{"POST":"Points not set"}');
}

$sql = "INSERT INTO Exam (QuestionID, Points) VALUES (" . $_POST["QuestionID"] .  "," . $_POST["Points"] . ")";
$result = mysqli_query($conn, $sql);
$error = mysqli_error($conn);
if($error)
{
    echo '{ "dbSuccess":false, "SQL_ERROR":' . json_encode($error) . '}';
} else {
    echo '{ "dbSuccess":true }';
}



mysqli_close($conn);

?>
