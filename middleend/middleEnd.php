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
    if($query == "GetQuestions") {
        $url = "https://web.njit.edu/~ar664/cs490/backend/getquestions.php";
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
	var_dump($output);
    }
      
       if (isset($_POST['Answer']) == NULL){
        $ch1 = curl_init($url);
        curl_setopt($ch1, CURLOPT_POST, TRUE); // store result in var
        curl_setopt($ch1, CURL_POSTFIELDS, http_build_query($_POST)); // store result in var
        curl_setopt($ch1, CURLOPT_RETURNTRANSFER, TRUE); // store result in var 
	$query_result = curl_exec($ch1);
        //echo $query_result; 
        curl_close($ch1);     
     } else
     //Exam grading
      if (isset($_POST['Answer']) && $query='UpdateExamQuestion'){ 
        $ch2=curl_init($url);
	$pointsGiven=$_POST['Points'];
	$answer=$_POST['Answer'];
	$output=array();
	$file='quest.py';
	$pyBinPath=exec('which python');
	$scriptPath= exec('pwd');
	$filepath=$scriptPath . '/' . $file; 
	echo file_put_contents('middleend/' . $file, $answer);
	exec($pyBinPath . ' ' . $filepath . ' 2>&1',  $output);
	echo 'PythonFileOutput: '; 
	var_dump($output) . PHP_EOL;
       
	 $url3= "https://web.njit.edu/~ar664/cs490/backend/getquestions.php";
	 $ch3 = curl_init($url3);
         curl_setopt($ch3, CURLOPT_POST, TRUE); // store result in var
	 curl_setopt($ch3, CURL_POSTFIELDS, http_build_query($_POST)); // store result in var
	 curl_setopt($ch3, CURLOPT_RETURNTRANSFER, TRUE); // store result in var 
	 $json_qst = curl_exec($ch3);
	 $question = json_decode($json_qst); 
       
	 foreach($question as $questionDetails){
	     var_dump($questionDetails); 
	 }
         curl_close($ch3);        
	//Indentation Points: -3

	//Forgot def Points: -5

	//cheat print answer: 0
     }
    
   /* 
	 $url2 = "https://web.njit.edu/~ar664/cs490/backend/getexam.php";
         $ch2 = curl_init($url2);
         curl_setopt($ch2, CURLOPT_POST, TRUE); // store result in var
         curl_setopt($ch2, CURL_POSTFIELDS, http_build_query($_POST)); // store result in var
         curl_setopt($ch2, CURLOPT_RETURNTRANSFER, TRUE); // store result in var 
	 $json_exam = curl_exec($ch2);
         $exam=json_decode($json_exam);
	 $questionDetails=$exam->Exam; //stored in here is a array full of json values 
         curl_close($ch2);
    */
    /* $answer = $_POST['Answer'];
       $pointsGiven= intval($_POST['Points']);
       echo 'Answer: ' . $answer . PHP_EOL;
       //Array of json pairs
       $tstCases = $_POST['TestCases'];
       echo 'TestCases: ' . $tstCases  . PHP_EOL;
       $foundNeedle= strpos($answer, "print");
       $foundNeedle= strpos($answer, "importantWords")
       if ($foundNeedle != false){ 
         echo "Found a print statement on line " . $foundNeedle " of your answer";
         $pointsGiven -= 5;
       } 
       if ($foundNeedle != false){ 
         echo "Function name is incorrect on line " . $foundNeedle " of your answer";
         $pointsGiven -= 5;
       }
    */
    
   
     
} else {
	echo "Username and Password not set in POST FIELDS\n";
}
?>
