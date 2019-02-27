<?php

if(!isset($result))
{
    echo "Incorrect use of this file";
}

$table = '{ "Questions": [';
while($row = mysqli_fetch_assoc($result)) {
    $table = $table . '{"ID": ' . $row["ID"] . ' , "Question":"' . $row["Question"] . '" , "Difficulty":"' . $row["Difficulty"] . '" , "TestCases":' . $row["TestCases"] . ' , "Topic":"' . $row["Topic"] . '" },';
}
$table = $table . '{}]}';



?>
