<?php

global $conn;

if( !isset($_POST["ID"]) )
{
    die('{"POST":"ID not set"}');
}

if( !(
    isset($_POST["Question"]) or
    isset($_POST["Difficulty"]) or 
    isset($_POST["TestCases"]) or
    isset($_POST["Topic"]) or
    isset($_POST["Constraints"]) 
      ) )
{
    die('{"POST":"updatequestion.php no variables set"}');
}

include 'connect2.php';

$sql = "UPDATE Questions SET ";

if( isset($_POST["Constraints"]) ) {
    $sql = $sql . " Constraints='" . $_POST["Constraints"] . "'";
    if( isset($_POST["Questions"]) || isset($_POST["Difficulty"]) || isset($_POST["TestCases"]) || isset($_POST["Topic"]) )
    {
        $sql = $sql . ",";
    }
}

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
    echo '{ "dbSuccess":true, "SQL_ERROR":' . json_encode($error, JSON_UNSECAPED_UNICODE) . '}';
} else 
{
    echo '{ "dbSuccess":true }';
}


mysqli_close($conn);



?>
