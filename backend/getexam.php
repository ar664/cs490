<?php

global $conn;

include 'connect2.php';

$sql = "SELECT * from Exam";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0)
{
    //output data of each row
    $sql2 = "SELECT * from Questions WHERE ";
    $exam = ' "Exam": [';
    while($row = mysqli_fetch_assoc($result)) {
        $exam = $exam .'{"QuestionID": ' . $row["QuestionID"] . ' , "Points":' . $row["Points"] . ' , "PointsGiven":' . $row["PointsGiven"]  . ' , "Answer":' . json_encode($row["Answer"], JSON_UNESCAPED_UNICODE) . ' , "AutoComments":' . json_encode($row["AutoComments"], JSON_UNESCAPED_UNICODE) . ' , "TeacherComments":' . json_encode($row["TeacherComments"], JSON_UNESCAPED_UNICODE) . '},';
        $sql2 = $sql2 . "ID=" . $row["QuestionID"] . " OR ";
    }
    $exam = $exam . '{"Test":false}]';
    $sql2 = $sql2 . "ID IS NULL";
    $result = mysqli_query($conn, $sql2);
    if(mysqli_num_rows($result) > 0)
    {
        global $questions;
        include 'listquestions.php';
        echo '{' . $exam . ',' . $questions . '}';
    } else {
        echo '{ "dbSuccess":false, "SQL_ERROR":' . json_encode(mysqli_error($conn)) . '}';
    }
} else {
    echo '{ "dbSuccess":false, "SQL_ERROR":' . json_encode(mysqli_error($conn)) . '}';
}

mysqli_close($conn);

?>
