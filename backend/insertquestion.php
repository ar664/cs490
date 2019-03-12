<?php

global $conn;

if(!(isset($_POST["Question"])))
{
    die('{"POST":"Question not set"}');
}

if(!(isset($_POST['Difficulty']))) 
{
    die('{"POST":"Difficulty not set"}');
}

if(!(isset($_POST['TestCases'])))
{
    die('{"POST":"TestCases not set"}');
}

if(!(isset($_POST['Topic'])))
{
    die('{"POST":"Topic not set"}');
}

if(!(isset($_POST['FunctionName'])))
{
    die('{"POST":"FunctionName not set"}');
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

$sql="INSERT INTO Questions (ID, Question, Difficulty, TestCases, Topic, FunctionName) VALUES (" . $num_rows . ", '" . $_POST["Question"] . "', '" . $_POST["Difficulty"] . "', '" . $_POST["TestCases"] . "', '" . $_POST["Topic"] . "', '" . $_POST["FunctionName"] . "')";

$result = mysqli_query($conn,$sql);
$error = mysqli_error($conn);
if($error)
{
    echo '{ "dbSuccess":false, "SQL_ERROR":' . json_encode($error) . '}';
} else {
    echo '{ "dbSuccess":true }';
}

mysqli_close($conn);

?>
