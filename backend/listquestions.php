<?php

if(!isset($result))
{
    echo "Incorrect use of this file";
}

$questions = '"Questions": [';
while($row = mysqli_fetch_assoc($result)) {
    //$questions = $questions . json_encode($row, JSON_UNESCAPED_UNICODE) . ",";
    $questions = $questions . '{"ID": ' . $row["ID"] . ' , "Question":' . json_encode($row["Question"], JSON_UNESCAPED_UNICODE) . ' , "Difficulty":"' . $row["Difficulty"] . '" , "TestCases":' . $row["TestCases"] . ' , "Topic":"' . $row["Topic"] . '" },';
}
$questions = $questions . '{"Test":false}]';



?>
