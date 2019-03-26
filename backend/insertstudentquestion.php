<?php

global $conn;

include 'connect2.php';

if(!isset($_POST["QuestionID"])) 
{
    die('{"POST":"Question ID not set"}');
}

if(!isset($_POST["StudentID"])) 
{
    die('{"POST":"StudentID not set"}');
}

if(!isset($_POST["ExamID"])) 
{
    die('{"POST":"ExamID not set"}');
}

$sql = 'INSERT INTO Students (StudentID, ExamID, QuestionID) VALUES ("' . $_POST["StudentID"] . '",' . $_POST["ExamID"] . "," . $_POST["QuestionID"] . ")";
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
