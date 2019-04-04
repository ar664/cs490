<?php

function error_log_json($jsonErr) {
    echo $jsonError;
    
    global $errorlog;
    $errorlog = "Hello there\n";
    switch($jsonErr){
	 case JSON_ERROR_STATE_MISMATCH:
	     $errorlog ="This JSON underflowed or mismatched\n";
	     break;

	 case JSON_ERROR_DEPTH:
	     $errorlog = "Maxmium stack depth has been exceed for this JSON\n";
	     break;
	      
	 case JSON_ERROR_CTRL_CHAR:
	     $errorlog = "This JSON has a control character error and has been incorrectly
	     encoded\n";
	     break;

	 case JSON_ERROR_SYNTAX:
	     $errorlog= "This JSON has a syntax error\n";
	     break;
	 
	 case JSON_ERROR_STATE_UTF8:
	     $errorlog = "This JSON is encoded incorrectly because it has malformed UTF-8 characters \n";
	     break;

	 default:
	   $errorlog = "No error occurred for this JSON\n";
	   break;
    }
}
?>
