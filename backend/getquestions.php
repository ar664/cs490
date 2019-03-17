<?php

global $conn;

include 'connect2.php';

if(isset($_POST["ID"]))
{
    $ID = 'ID=' . $_POST["ID"];
} else {
    $ID = 'ID IS NOT NULL';
}

if(isset($_POST["Question"]) && $_POST["Question"] != "")
{
    $Question = 'Question REGEXP "' . $_POST["Question"] . '"';
} else {
    $Question = 'Question REGEXP ".*"';
}

if(isset($_POST["Difficulty"]) && $_POST["Difficulty"] != "")
{
    $Difficulty = 'Difficulty="' . $_POST["Difficulty"] . '"';
} else {
    $Difficulty = 'Difficulty REGEXP ".*"';
}

if(isset($_POST["Topic"]) && $_POST["Topic"] != "")
{
    $Topic = 'Topic REGEXP "' . $_POST["Topic"] . '"';
} else {
    $Topic = 'Topic REGEXP ".*"';
}

if($ID != '' || $Question != '' || $Difficulty != '' || $Topic != '')
{
    $sql = "SELECT * from Questions WHERE ";
    $sql = $sql . $ID . ' AND ' . $Question . ' AND ' . $Difficulty . ' AND ' . $Topic;
} else 
{
    $sql="SELECT * from Questions";
}
//echo $sql;
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    //output data of each row
    global $questions;
    include 'listquestions.php';
    echo '{' . $questions . '}';
} else {
    echo '{ "dbSuccess":false, "SQL_ERROR":' . json_encode(mysqli_error($conn), JSON_UNESCAPED_UNICODE) . '}';
}

mysqli_close($conn);

?>
