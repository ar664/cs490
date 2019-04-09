<?php

$gradefile = fopen("grade.txt", "r") or die('{"Error":"Unable to open file"}');

echo fread($gradefile, 255);
fclose($gradefile);

?>
