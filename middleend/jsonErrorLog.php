<?php
	function errorlog() {
	$jsonErr= json_last_error();
	switch($jsonErr){
	 case JSON_ERROR_STATE_MISMATCH:
	     $error ="This JSON underflowed or mismatched\n";
	     break;

	 case JSON_ERROR_DEPTH:
	     $error = "Maxmium stack depth has been exceed for this JSON\n";
	     break;
	      
	 case JSON_ERROR_CTRL_CHAR:
	     $error = "This JSON has a control character error and has been incorrectly
	     encoded\n";
	     break;

	 case JSON_ERROR_SYNTAX:
	     $error= "This JSON has a syntax error\n";
	     break;
	 
	 case JSON_ERROR_STATE_UTF8:
	     $error = "This JSON is encoded incorrectly because it has malformed UTF-8 characters \n";
	     break;

	 default:
	   $error = "No error occurred for this JSON\n";
	   break;
	 }
     }
?>
