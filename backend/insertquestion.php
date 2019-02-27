<?php

global $conn;

if( !(isset($_POST["Question"]) && isset($_POST["Difficulty"]) && isset($_POST["TestCases"]) && isset($_POST["Topic"]) )) {
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

$sql="INSERT INTO Questions (ID, Question, Difficulty, TestCases, Topic) VALUES (" . $num_rows . ", '" . $_POST["Question"] . "', '" . $_POST["Difficulty"] . "', '" . $_POST["TestCases"] . "', '" . $_POST["Topic"] . "')";
echo $sql;
$result = mysqli_query($conn,$sql);
$error = mysqli_error($conn);
if($error)
{
    echo "SQL ERROR: " . $error;
}

$sql="SELECT * from Questions";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    //output data of each row
    global $table; 
    include 'listquestions.php';
    echo $table;
} else {
    echo "0 results" . mysqli_error($conn);
}

mysqli_close($conn);

?>
