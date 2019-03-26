<?php

global $conn;

if(!isset($_POST["QuestionID"]))
{
    die('{"POST":"QuestionID not set"}');
}

if(!isset($_POST["StudentID"]))
{
    die('{"POST":"StudentID not set"}');
}

if(!isset($_POST["ExamID"]))
{
    die('{"POST":"ExamID not set"}');
}

if( !( isset($_POST["Answer"])  || isset($_POST["PointsGiven"]) || isset($_POST["Points"]) || isset($_POST["AutoComments"]) || isset($_POST["TeacherComments"]) ))
{
    die('{"POST":"no variables set"}');
}

include 'connect2.php';

$sql = "UPDATE Students SET ";

if( isset($_POST["Answer"]) )
{
    $sql = $sql . " Answer='" . $_POST["Answer"] . "'";
    if( isset($_POST["PointsGiven"]) || isset($_POST["AutoComments"]) || isset($_POST["TeacherComments"]) )
    {
        $sql = $sql . ",";
    }
}

if( isset($_POST["PointsGiven"]) )
{
    $sql = $sql . " PointsGiven=" . $_POST["PointsGiven"];
    if( isset($_POST["AutoComments"]) || isset($_POST["TeacherComments"]) ) 
    {
        $sql = $sql . ",";
    }
}

if( isset($_POST["AutoComments"]) )
{
    $sql = $sql . " AutoComments='" . $_POST["AutoComments"] . "'";
    if(isset($_POST["TeacherComments"]))
    {
        $sql = $sql . ",";
    }

} 

if( isset($_POST["TeacherComments"]) )
{
    $sql = $sql . " TeacherComments='" . $_POST["TeacherComments"] . "'";
}

$sql =  $sql . " WHERE  QuestionID=" . $_POST["QuestionID"] . " AND ExamID=" . $_POST["ExamID"] . ' AND StudentID LIKE "' . $_POST["StudentID"] . '"';
$result = mysqli_query($conn, $sql);
$error = mysqli_error($conn);

if($error)
{
    echo '{ "dbSuccess":false, "SQL_ERROR":' . json_encode($error, JSON_UNESCAPED_UNICODE) . '}';
} else 
{
    echo '{ "dbSuccess":true }';
}


mysqli_close($conn);

?>
