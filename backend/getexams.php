<?php

global $conn;

include 'connect2.php';

$sql = "SELECT * from Exam ORDER BY 'ExamID'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0)
{
    $exam = '{"Exams":[';
    while($row = mysqli_fetch_assoc($result)) {
        $exam = $exam . ' {"ExamID":' . $row["ExamID"] . ', "QuestionID":' . $row["QuestionID"] . ', "Points":' . $row["Points"] . '},';  
    }
    $exam = $exam . '{"Test":false}]}';
    echo $exam;
} else {
    echo '{ "dbSuccess":false, "SQL_ERROR":' . json_encode(mysqli_error($conn)) . '}';
}

?>
