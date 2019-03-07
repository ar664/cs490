<?php

$username = $_POST['Username'];
$password = $_POST['Password'];

if (isset($username) && isset($password)){

	// MAKE DB Login Call
	$url1 = "https://web.njit.edu/~ar664/cs490/backend/connect.php";
	$ch1 = curl_init($url1);

	curl_setopt($ch1, CURLOPT_POST, TRUE);
	curl_setopt($ch1, CURLOPT_POSTFIELDS, "Username=" . $username . "&Password=" . $password);
	curl_setopt($ch1, CURLOPT_RETURNTRANSFER, TRUE); // store result in var
	$db_result = curl_exec($ch1);

 	echo $db_result;
	curl_close($ch1);
	//Tell me your Account Type

} else if(isset($_POST["query"])) {
    $query = $_POST["query"];
    $url = "https://web.njit.edu/~ar664/cs490/backend/getquestions.php";
    if($query == "GetQuestions") {
        $url1 = $url;
    } else if ($query == "InsertQuestion") {
        $url = "https://web.njit.edu/~ar664/cs490/backend/insertquestion.php";
    } else if ($query == "DeleteQuestion") {
        $url = "https://web.njit.edu/~ar664/cs490/backend/deletequestion.php";
    } else if ($query == "GetExam") {
        $url = "https://web.njit.edu/~ar664/cs490/backend/getexam.php";
    } else if ($query == "InsertExamQuestion") {
        $url = "https://web.njit.edu/~ar664/cs490/backend/insertexamquestion.php";
    } else if ($query == "UpdateExamQuestion") {
        $url = "https://web.njit.edu/~ar664/cs490/backend/updateexamquestions.php";
    } else if ($query == "DeleteExamQuestion") {
        $url = "https://web.njit.edu/~ar664/cs490/backend/deleteexamquestions.php";
    } else {
        echo "ERROR: Query not found";
    }
    /*
     $otherQueries=array('InsertQuestion','DeleteQuestion','InsertExamQuestion','UpdateExamQuestion','DeleteExamQuestion');
     if ($_POST['query'] == 'GetQuestions' || $_POST['query'] == 'GetExam'){ 
      $ch1 = curl_init($url);
      curl_setopt($ch1, CURLOPT_RETURNTRANSFER, TRUE); // store result in var
      $query_result = curl_exec($ch1);
      echo $query_result;
      curl_close($ch1);
     } else if (in_array($_POST['query'], $otherQueries)){ 
       $ch1 = curl_init($url);
       curl_setopt($ch1, CURLOPT_POST, TRUE);
       curl_setopt($ch1, CURLOPT_POSTFIELDS, http_build_query($_POST));
       curl_setopt($ch1, CURLOPT_RETURNTRANSFER, TRUE); // store result in var
       $query_result = curl_exec($ch1);
       echo $query_result;
       curl_close($ch1);
     }
   */
     //Exam grading
     $answer = $_POST['Answer'];
     $points = $_POST['Points'];
     echo 'Answer: ' . $answer . PHP_EOL;
     //Array of json pairs
     $tstCases = $_POST['TestCases'];
     echo 'TestCases: ' . $tstCases  . PHP_EOL;
     $foundNeedle= strpos($answer, "print");
     if ($foundNeedle != false){ 
       echo "Found a print statement on line " . $foundNeedle " of your answer";
     }
    
    
     
    /* $ch1 = curl_init($url);
        curl_setopt($ch1, CURLOPT_RETURNTRANSFER, TRUE); // store result in var
        $query_result = curl_exec($ch1);
        echo $query_result;
        curl_close($ch1);
      */
} else {
	echo "Username and Password not set in POST FIELDS\n";
}
?>
