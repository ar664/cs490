<?php

if(!isset($_POST["QuestionID"]))
{
    die("POST: QuestionID not set");
}

global $conn;

include 'connect2.php';

$sql = "DELETE from Exam WHERE QuestionID=" . $_POST["QuestionID"];
$result = mysqli_query($conn, $sql);
$error = mysqli_error($conn);
if($error)
{
    echo "SQL ERROR: " . $error;
} else {
    echo $result;
}

?>
