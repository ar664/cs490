<?php

global $conn;

if(!isset($_POST["QuestionID"]))
{
    die('{"POST":"QuestionID not set"}');
}

if( !(isset($_POST["Points"]) || isset($_POST["Answer"])  || isset($_POST["PointsGiven"]) || isset($_POST["Points"]) || isset($_POST["AutoComments"]) || isset($_POST["TeacherComments"]) ))
{
    die('{"POST":"no variables set"}');
}

include 'connect2.php';

$sql = "UPDATE Exam SET ";

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

$sql =  $sql . " WHERE  QuestionID=" . $_POST["QuestionID"];
$result = mysqli_query($conn, $sql);
$error = mysqli_error($conn);

if($error)
{
    echo "SQL ERROR: " . $error;
} else 
{
    echo $result;
}


mysqli_close($conn);

?>
