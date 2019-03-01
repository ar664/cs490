<?php

if(!isset($result))
{
    echo "Incorrect use of this file";
}

$questions = '"Questions": [';
while($row = mysqli_fetch_assoc($result)) {
    $questions = $questions . '{"ID": ' . $row["ID"] . ' , "Question":"' . $row["Question"] . '" , "Difficulty":"' . $row["Difficulty"] . '" , "TestCases":' . $row["TestCases"] . ' , "Topic":"' . $row["Topic"] . '" },';
}
$questions = $questions . '{}]';



?>
