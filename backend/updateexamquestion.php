<?php

global $conn;

if(!isset($_POST["QuestionID"]))
{
    die('{"POST":"QuestionID not set"}');
}

if( !(isset($_POST["Points"]) || isset($_POST["Answer"])  || isset($_POST["PointsGiven"]) || isset($_POST["FinalGrade"]) || isset($_POST["AutoComments"]) || isset($_POST["TeacherComments"]) ))
{
    die('{"POST":"no variables set"}');
}

include 'connect2.php';

$sql = "UPDATE Exam SET ";

if( isset($_POST["FinalGrade"]) )
{
    $sql = $sql . " FinalGrade=" . $_POST["FinalGrade"];
    if( isset($_POST["Points"]) || isset($_POST["PointsGiven"]) || isset($_POST["Answer"]) || isset($_POST["AutoComments"]) || isset($_POST["TeacherComments"]) )
    {
        $sql = $sql . ",";
    }
}

if( isset($_POST["Points"]) )
{
    $sql = $sql . " Points=" . $_POST["Points"];
    if( isset($_POST["PointsGiven"]) || isset($_POST["Answer"]) || isset($_POST["AutoComments"]) || isset($_POST["TeacherComments"]) )
    {
        $sql = $sql . ",";
    }
}

if( isset($_POST["Answer"]) )
{
    $sql = $sql . " Answer='" . str_replace("'","''",$_POST["Answer"]) . "'";
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
    $sql = $sql . " AutoComments='" . str_replace("'","''",$_POST["AutoComments"]) . "'";
    if(isset($_POST["TeacherComments"]))
    {
        $sql = $sql . ",";
    }

} 

if( isset($_POST["TeacherComments"]) )
{
    $sql = $sql . " TeacherComments='" . str_replace("'","''",$_POST["TeacherComments"]) . "'";
}

$sql =  $sql . " WHERE  QuestionID=" . $_POST["QuestionID"];
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
