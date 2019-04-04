<?php

if(!isset($_POST["FinalGrade"]))
{
    die('{"POST":"FinalGrade not set"}');
}

$gradefile = fopen("grade.txt", "w+") or die('{"Error":"Unable to open file"}');
fwrite($gradefile, $_POST["FinalGrade"]);
fclose($gradefile);

?>
