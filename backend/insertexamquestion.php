<?php

global $conn;

include 'connect2.php';

if(!isset($_POST["QuestionID"])) {
    die("POST: Question ID not set");
}

if(!isset($_POST["Points"])) {
    die("POST: Points not set");
}

$sql = "INSERT INTO Exam (QuestionID, Points) VALUES (" . $_POST["QuestionID"] .  "," . $_POST["Points"] . ")";
$result = mysqli_query($conn, $sql);
$error = mysqli_error($conn);
if($error)
{
    echo "SQL ERROR: " . $error;
}

$sql = "SELECT * from Exam";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    //output data of each row
    $sql2 = "SELECT * from Questions WHERE ";
    $exam = ' "Exam": [';
    while($row = mysqli_fetch_assoc($result)) {
        $exam = $exam .'{"QuestionID": ' . $row["QuestionID"] . ' , "Points":' . $row["Points"] . ' , "PointsGiven":' . $row["PointsGiven"]  . ' , "AutoComments":"' . $row["AutoComments"] . '" , "TeacherComments":"' . $row["TeacherComments"] . '"},';
        $sql2 = $sql2 . "ID=" . $row["QuestionID"] . " OR ";
    }
    $exam = $exam . "{}]";
    $sql2 = $sql2 . "ID IS NULL";
    $result = mysqli_query($conn, $sql2);
    if(mysqli_num_rows($result) > 0)
    {
        global $questions;
        include 'listquestions.php';
        echo '{' . $exam . ',' . $questions . '}';
    } else {
        echo "0 results: " . mysqli_error($conn);
    }
} else {
    echo "0 results: " . mysqli_error($conn);
}


mysqli_close($conn);

?>
