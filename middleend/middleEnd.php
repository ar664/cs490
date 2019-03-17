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
		$url = "https://web.njit.edu/~ar664/cs490/backend/deleteexamquestion.php";
	} else {
		echo "ERROR: Query not found";
		return;
	}

        
	//var_dump($_POST);
	if (isset($_POST['Answer']) == FALSE){
		$ch1 = curl_init($url);
		curl_setopt($ch1, CURLOPT_POST, TRUE); // store result in var
		curl_setopt($ch1, CURLOPT_POSTFIELDS, http_build_query($_POST)); // store result in var
		curl_setopt($ch1, CURLOPT_RETURNTRANSFER, TRUE); // store result in var 
		$query_result = curl_exec($ch1);
		//echo $query_result; 
		curl_close($ch1);     
	} else {
		//Exam grading
		if (isset($_POST['Answer']) && $query='UpdateExamQuestion'){ 
			
			if (!isset($_POST['Points']) || !isset($_POST['QuestionID'])) {
				//! Missing required post params
				echo "Points:" . $_POST['Points'] . PHP_EOL, 
				     "QuestionID:" . $_POST['QuestionID'] . PHP_EOL;
				echo 'You didn’t give me one of these Post Vars: Points and QuestionID';
				return;
			}
			
			//Create the file from Answer
			$answer=$_POST['Answer'];
			$output=array();

			//execute file and mark points
			$comments="";
			$pointsGiven=0;
		
		        //Get all The questions
			$jsonTestCases="nothing";
			$functName="nothing";	
			$url3="https://web.njit.edu/~ar664/cs490/backend/getquestions.php";
			$ch3=curl_init($url3);
			curl_setopt($ch3, CURLOPT_POST, TRUE); // store result in var
			curl_setopt($ch3, CURLOPT_POSTFIELDS, http_build_query($_POST["ID"])); // store result in var
			curl_setopt($ch3, CURLOPT_RETURNTRANSFER, TRUE); // store result in var 
			$json_QuestionLst= curl_exec($ch3);
			$questionLst= json_decode($json_QuestionLst);
			$arrayQuestions=$questionLst->Questions;
		//	echo "Here are all the questions: " . var_dump($arrayQuestions)  . PHP_EOL;	
			curl_close($ch3);
		       
			//Get functionName and TestCases from GetQuestions
			foreach($arrayQuestions as $json_question => $value){
			  //echo "Question value: " . $value->ID . PHP_EOL;
			  if ( $value->ID == $_POST["QuestionID"]){
	//		         echo "Here's the question with ID:" . $_POST["QuestionID"] . PHP_EOL . 
	//			 "Question:  " . $value->Question . PHP_EOL;
				 $jsonTestCases=$value->TestCases;
				 $functName=$value->FunctionName;
			  }
		       }

			$url4="https://web.njit.edu/~ar664/cs490/backend/getexam.php";
			$ch4=curl_init($url4);
			curl_setopt($ch4, CURLOPT_POST, TRUE); // store result in var
			curl_setopt($ch4, CURLOPT_POSTFIELDS, http_build_query($_POST)); // store result in var
			curl_setopt($ch4, CURLOPT_RETURNTRANSFER, TRUE); // store result in var 
			$json_getExam= curl_exec($ch4);
			$examValues= json_decode($json_getExam);
		        $examArry= $examValues->Exam;
			//$points=$exam->Questions;
			//echo "Here is the exam stuff: " . var_dump($examArry) . PHP_EOL;	
			curl_close($ch4);

			foreach($examArry as $json_exam=>$exam_Value){
			  echo "Question ID: " . $exam_Value->QuestionID . PHP_EOL;
			  if ( $exam_Value->QuestionID == $_POST["QuestionID"]){
			         echo "Question with ID: " . $_POST["QuestionID"] . 
				 " is worth $exam_Value->Points Points" . PHP_EOL; 
				 $pointsGiven=$exam_Value->Points;
			  }
		       }


			//echo "Json_TestCases: " . $jsonTestCases;
			//StringSearch:

			$defFirstIdx = stripos($answer, "def ");
			$parensFirstIdx = stripos($answer, "(");
			// echo $defFirstIdx, " ", $parensFirstIdx;
			if ($defFirstIdx === FALSE || $parensFirstIdx === FALSE) {
				//! the answer string provided is invalid, so handle this error.
				$pointsGiven = 0;
				echo "answer provided isn't a valid python function, missing def declaration.";
				echo "Points Given: " . $pointsGiven;
				return;
			}

			$answerFuncNameStartIdx = $defFirstIdx + 4;
			$answerFuncNameLen = $parensFirstIdx - $answerFuncNameStartIdx;
			$answerFuncName = substr($answer, $answerFuncNameStartIdx, $answerFuncNameLen);
			// replace the functionName in answer string
			$answer = substr_replace($answer, "answer", $answerFuncNameStartIdx, $answerFuncNameLen);
		
			if ($answerFuncName != $functName) {
				$pointsGiven -= 10;
			}

			$pyBinPath=exec('which python');
			$scriptPath=exec('pwd');
			$filepath=$scriptPath . '/' . "student_answer.py"; 
		        file_put_contents($filepath, $answer);

          //              echo "This is Json: " . var_dump($jsonTestCases);
	
//			echo "Testcases below:" . var_dump($testCases) . PHP_EOL; 
			if ($jsonTestCases == NULL) {
	//			echo "Testcases within if statement:" . var_dump($testCases["Input"]) . PHP_EOL; 
				echo "TestCases: Invalid json. Please enter valid json for TestCases param.";
				return;
			}

			//Currentyl fixing the forloop for testCases
			foreach($jsonTestCases as $testCase) {
				// {"input":"args", "output":"results"}
				$expInput = $testCase; //expects an array of two args
				$expOutput = $testCase;

				echo "TestCase is: " . $testCase . PHP_EOL;
				echo "expInput is: " . var_dump($expInput) . PHP_EOL;
				echo "expOutput is: " . var_dump($expOutput) . PHP_EOL;
				
				// expInput must always be an array with two arguments inside to work.
				exec($pyBinPath . " " . "$scriptPath/py_exec_wrapper.py" . " " . $expInput[0] . " " . $expInput[1] . ' 2>&1', $output, $status);

				$actualOutput = implode($output);
				//echo "output is " . var_dump($output) . PHP_EOL;
				//echo "Actual output is: " . $actualOutput . PHP_EOL;

				if ($status == 1) {    
					//! Handle case where provide answer doesn't successfully run
					$pointsGiven = 0;
				       //echo "Points Given: " . $pointsGiven . PHP_EOL;
				      $_POST['AutoComments']= implode("\n", $output);
					return;
				}

				if ($actualOutput != $expOutput) {
					//! Handle case where the test case fails 
					$pointsGiven -= 7;
				      $_POST['AutoComments']= implode("\n", $output);
				      echo "testCases failed" . PHP_EOL;
				}
			}
			if ($pointsGiven < 0) {
				$pointsGiven = 0;
			}
                        if ($pointsGiven == $_POST['Points']){
			$_POST['AutoComments']='Good Job';
			}
			
			// We are done running the test cases so now lets send the points given.
       			  $_POST['PointsGiven']=$pointsGiven;
                           echo "student received " . $pointsGiven . "points";

			   $ch2 = curl_init($url);
			   curl_setopt($ch2, CURLOPT_POST, TRUE); // store result in var
			   curl_setopt($ch2, CURLOPT_POSTFIELDS, http_build_query($_POST)); // store result in var
			   curl_setopt($ch2, CURLOPT_RETURNTRANSFER, TRUE); // store result in var 
			   $updatedQuestion = curl_exec($ch2);
		//	   echo $updatedQuestion;
			   curl_close($ch2);
	}
}
}else{
echo "Enter Username and Password Post Vars" . PHP_EOL; 
}

?>
