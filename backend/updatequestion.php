<?php

global $conn;

if( !isset($_POST["ID"]) )
{
    die('{"POST":"ID not set"}');
}

if( !(isset($_POST["Question"]) || isset($_POST["Difficulty"]) || isset($_POST["TestCases"]) || isset($_POST["Topic"]) ) )
{
    die('{"POST":"no variables set"}');
}

include 'connect2.php';

$sql = "UPDATE Questions SET ";

if( isset($_POST["Question"]) )
{
    $sql = $sql . " Question='" . $_POST["Question"] . "'";
    if( isset($_POST["Difficulty"]) || isset($_POST["TestCases"]) || isset($_POST["Topic"]) )
    {
        $sql = $sql . ",";
    }
}

if( isset($_POST["Difficulty"]) )
{
    $sql = $sql . " Difficulty='" . $_POST["Difficulty"] . "'";
    if( isset($_POST["TestCases"]) || isset($_POST["Topic"]) ) 
    {
        $sql = $sql . ",";
    }
}

if( isset($_POST["TestCases"]) )
{
    $sql = $sql . " TestCases='" . $_POST["TestCases"] . "'";
    if(isset($_POST["Topic"]))
    {
        $sql = $sql . ",";
    }

} 

if( isset($_POST["Topic"]) )
{
    $sql = $sql . " Topic='" . $_POST["Topic"] . "'";
}

$sql =  $sql . " WHERE  ID=" . $_POST["ID"];
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
