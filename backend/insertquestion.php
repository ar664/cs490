<?php

global $conn;

if( !(isset($_POST["Question"]) && isset($_POST["Answer"]) && isset($_POST["Difficulty"]) && isset($_POST["TestCases"]) && isset($_POST["Points"]) && isset($_POST["Topic"]) )) {
    die("Post variables not set");
}

include 'connect2.php';

$sql="SELECT * FROM Questions";
$result = mysqli_query($conn, $sql);

if(isset($result))
{
    $num_rows = mysqli_num_rows($result)+1;
} else {
    $num_rows = 1;
}

$sql="INSERT INTO Questions (ID, Question, Answer, Difficulty, TestCases, Points, Topic) VALUES (" . $num_rows . ", '" . $_POST["Question"] . "', '" . $_POST["Answer"] . "', '" . $_POST["Difficulty"] . "', '" . $_POST["TestCases"] . "', " . $_POST["Points"] . ", '" . $_POST["Topic"] . "')";
echo $sql;
$result = mysqli_query($conn,$sql);
$error = mysqli_error($conn);
if($error)
{
    echo "SQL ERROR: " . $error;
}

mysqli_close($conn);

?>
