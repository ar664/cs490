<?php

global $conn;

include 'connect2.php';

$sql="SELECT * from Questions";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    //output data of each row
    global $table;
    $table = '{ "Test":"Test"';
    while($row = mysqli_fetch_assoc($result)) {
        $table = $table . ', {"Question":"' . $row["Question"] . '" , "Answer":"' . $row["Answer"] . '" , "Difficulty":"' . $row["Difficulty"] . '" , "TestCases":' . $row["TestCases"] . ' , "Points":' . $row["Points"] . '}';
    }
    $table = $table . '}';
    echo $table;
} else {
    echo "0 results";
}

mysqli_close($conn);

?>
