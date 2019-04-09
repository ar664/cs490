<?php
$username = $_POST['Username'];
$password = $_POST['Password'];

//echo "I hope you see me" . PHP_EOL;
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
	return;
} 

if(isset($_POST["query"])) {
	$query = $_POST["query"]; 
       switch ($query){
         case "GetQuestions":
	   $url = "https://web.njit.edu/~ar664/cs490/backend/getquestions.php";
           break; 
	 case "GetExam":
	   $url = "https://web.njit.edu/~ar664/cs490/backend/getexam.php";
	   break;
         case "InsertQuestion":
           $url = "https://web.njit.edu/~ar664/cs490/backend/insertquestion.php";
           break;
         case "DeleteQuestion":
           $url = "https://web.njit.edu/~ar664/cs490/backend/deletequestion.php";
           break;
         case "InsertExamQuestion":
           $url = "https://web.njit.edu/~ar664/cs490/backend/insertexamquestion.php";
           break;
         case "UpdateExamQuestion":
           $url = "https://web.njit.edu/~ar664/cs490/backend/updateexamquestion.php";
           break;
         case "DeleteExamQuestion":
           $url = "https://web.njit.edu/~ar664/cs490/backend/deleteexamquestion.php";
           break; 
	 case "GetFinalGrade":
           $url = "https://web.njit.edu/~ar664/cs490/backend/getfinalgrade.php";
           break;
	 case "SetFinalGrade":
           $url = "https://web.njit.edu/~ar664/cs490/backend/setfinalgrade.php";
           break;
	 default:
	   echo "Query \"$query\" not found" . PHP_EOL;
	   return;
       } 
        
	//echo "Post Variable Data: \n";
	//var_dump($_POST);
	//echo "Answer is: \n" . var_dump($_POST['Answer']) . PHP_EOL;
	//HTML form submits will sometimes submit blank strings so empty is necessary
	if ((isset($_POST['Answer']) == FALSE && $query != "SetFinalGrade") || (empty($_POST['Answer']) && $query != "SetFinalGrade")){
	      //  echo "I don't have an answer\n";
		$ch1 = curl_init($url);
		curl_setopt($ch1, CURLOPT_POST, TRUE); // store result in var
		curl_setopt($ch1, CURLOPT_POSTFIELDS, http_build_query($_POST)); // store result in var
		curl_setopt($ch1, CURLOPT_RETURNTRANSFER, TRUE); // store result in var 
		$query_result = curl_exec($ch1);
		echo $query_result; 
		curl_close($ch1);
		return;
	}
		//Exam grading
		//echo "I have an Answer: " . $_POST['Answer'] . PHP_EOL;
	if (isset($_POST['Answer']) && $query=='UpdateExamQuestion'){ 
	     if (!isset($_POST['QuestionID']) || empty($_POST['QuestionID'])) {
	           //! Missing required post params
	          //echo "QuestionID:" . $_POST['QuestionID'] . PHP_EOL;
	         //echo 'You didn’t give me a Post Var: QuestionID';
	           return;
             }
		
	
		//Create the file from Answer
		$answer=$_POST['Answer']; 
		//echo "This is the answer: " .PHP_EOL . $answer . PHP_EOL;


		//execute file and mark points
		$comments="";
		$pointsGiven=0;
		$constraints="";  	
		//Get all The questions
		$jsonTestCases="nothing";
		$functName="nothing";
		$actualQuestion="";
		$jsonQuestion="";
		$url3="https://web.njit.edu/~ar664/cs490/backend/getquestions.php";
		$ch3=curl_init($url3);
		curl_setopt($ch3, CURLOPT_POST, TRUE); // store result in var
		curl_setopt($ch3, CURLOPT_POSTFIELDS, http_build_query($_POST["ID"])); // store result in var
		curl_setopt($ch3, CURLOPT_RETURNTRANSFER, TRUE); // store result in var 
		$json_QuestionLst= curl_exec($ch3);
		$questionLst= json_decode($json_QuestionLst);
		$arrayQuestions=$questionLst->Questions;
		//echo "Here are all the questions: " . var_dump($arrayQuestions)  . PHP_EOL;	
		curl_close($ch3);
	       
		//Get functionName, TestCases, and the Question itself  
		//from GetQuestions when given the QuestionID
		foreach($arrayQuestions as $json_question => $value){
		  //echo "Question value: " . $value->ID . PHP_EOL;
		  if ($value->ID == $_POST["QuestionID"]){
			 //echo "Here's the question with ID:" . $_POST["QuestionID"] . PHP_EOL . 
			 //"Question:  " . $value->Question . PHP_EOL;
			 $jsonTestCases=$value->TestCases;
			 //echo "JsonTestCases is: " . var_dump($jsonTestCases) . PHP_EOL;
			 $functName=$value->FunctionName;
			 $actualQuestion=$value->Question;
			 $constraints= $value->Constraints;
			 //echo "Constraints are:" . PHP_EOL;
			 //var_dump($constraints);
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
		//echo "Here is the exam stuff: " . var_dump($examArry) . PHP_EOL;	
		curl_close($ch4);

		//Get The questions Total Point worth
		foreach($examArry as $json_exam=>$exam_Value){
		  // echo "Question ID: " . $exam_Value->QuestionID . PHP_EOL;
		  if ($exam_Value->QuestionID == $_POST["QuestionID"]){
			//echo "Question with ID: " . $_POST["QuestionID"] . 
			 //" is worth $exam_Value->Points Points" . PHP_EOL; 
			 $pointsGiven= $exam_Value->Points;
			 $totalPoints= $exam_Value->Points;
			 //echo "Points is now set to: " . $pointsGiven . PHP_EOL;
		  }
	       }


		//if "def" or ( not in the student's Function 
		//give them a zero because function will not compile 
		$defFirstIdx = stripos($answer, "def ");
		$parensFirstIdx = stripos($answer, "(");
		// echo $defFirstIdx, " ", $parensFirstIdx;
		if ($defFirstIdx === FALSE || $parensFirstIdx === FALSE) {
			//! the answer string provided is invalid, so handle this error.
			$pointsGiven = 0;
			$_POST['AutoComments'].= "answer provided isn't a valid python function, missing def declaration.\n";
		        echo "function provided isn't a valid Python function missing def declaration Points Given: " . $pointsGiven;
			return;
		}

		// replace the functionName in studentFunction 
		//if its not FunctName
		$answerFuncNameStartIdx = $defFirstIdx + 4;
		$answerFuncNameLen = $parensFirstIdx - $answerFuncNameStartIdx;
		$answerFuncName = substr($answer, $answerFuncNameStartIdx, $answerFuncNameLen);
	
		if ($answerFuncName != $functName) {
			echo "Function name is incorrect So I'm taking away 10 points" . PHP_EOL;
			echo "answerFuncName:$answerFuncName   functName:$functName" . PHP_EOL;
			$pointsGiven -= 5;
		        $_POST['AutoComments'].="FunctionName should be: " . $functName . "\n";
			$answer = substr_replace($answer, $functName, $answerFuncNameStartIdx, $answerFuncNameLen);
			//echo $answer . PHP_EOL;
		}
		
		$pyBinPath=exec('which python');
		$scriptPath=exec('pwd');
		$qID=$_POST['QuestionID'];
		$filepath=$scriptPath . '/studentAnswers/' . "student_answer" . "$qID" . ".py"; 
		file_put_contents($filepath, $answer, LOCK_EX);
		
		//Check for the Basic Constraints within the student's Answer: |for|While|print|
		//If the value of a constraint is true | If the constraint is false
		//check for a match within the string  | Do nothing
		//If a match is found do nothing otherwise subtracts points
		foreach($constraints as $constraint=>$value){
		   $checkConstraint=$value;
		   //echo "Constraint: \"$constraint\" value: $checkConstraint" . PHP_EOL;
		   //echo "Boolean" . PHP_EOL;
		   //var_dump($checkConstraint);
		   if ($checkConstraint){
		     $pos=strpos($answer, $constraint);
		     $len= strlen($constraint);
		     //echo "The Constraint \"$constraint\" is in the If statement" . PHP_EOL;
		    // if (!empty($pos)){
			// echo "\"$constraint\"'s Position in the string is: $pos" . PHP_EOL;
			// echo "MatchFound: \n" . substr($studentAnswer, $pos, $len) . PHP_EOL; 
		    // } 
		     if (empty($pos)){ 
			 echo "MatchNotFound: -5 Points\nThe constraint \"$constraint\" doesn't exist within the string\n";
			 $pointsGiven-=5; 
			 if ($constraint == 'print')
			  $_POST['AutoComments'].="You forgot to include a  $constraint statement\n";
			 else if($constraint == 'while' || $constraint == 'for'){   
			  $_POST['AutoComments'].="You forgot to include a $constraint loop\n";
			 }
		     }
		   }
		}       
	      

		//echo "This is Json: " . var_dump($jsonTestCases) . PHP_EOL;	
		//echo "Testcases below:" . var_dump($testCases) . PHP_EOL; 
		if ($jsonTestCases == NULL) {
			//echo "Testcases within if statement:" . var_dump($testCases["Input"]) . PHP_EOL; 
	        	//echo "TestCases: Invalid json. Please enter valid json for TestCases param.";
			return;
		}


		//Forloop for testCases to be split one by one and executed
		$inputCases=$jsonTestCases->Input;
		$length=count($inputCases);
		$outputCases=$jsonTestCases->Output;
		for($i=0; $i < $length; $i++ ) {
			$expInput = explode("," ,$inputCases[$i]); //expects an array of two args
			$inputSize = count($expInput);
			$expOutput = $outputCases[$i];
			$data="\n\n#Testing Question" . $_POST['QuestionID'] . "\n";
			$temp="";
		     
		       // ForLoop checks the input Elements for strings 
		       // that can be converted to Int 
		       // and stores them as a string of arguments 
		       // to be written to the student answer file 
		     for($j=0; $j < $inputSize; $j++) {
			 if(is_numeric($expInput[$j])){
			   $strtoInt=intval($expInput[$j]);
			   $expInput[$j]=$strtoInt;
			 }
			 if (gettype($expInput[$j]) == "string"){
			    $data.="arg$j=" . "\"" . $expInput[$j] . "\"" . "\n";
			    $temp.="arg$j, ";
			  }else{ 
			  $data.="arg$j=".$expInput[$j]."\n";
			  $temp.="arg$j, "; 
			  }
			  if ($j == $inputSize - 1){
			     //echo "Temp is: $temp\n";
			     if (strpos($answer, 'print') === FALSE){
			     // echo "Function doesn't have a Print Statement\n";
			      $data.="print($functName($temp))";
			      $endingParenth = strpos($data, ", )");
			      $doubleRightParenth = strpos($data, "))");
			     } else{
			     // echo "Function has a Print Statement\n";
			      $data.="$functName($temp)";
			      $endingParenth = strpos($data, ", )");
			      $doubleRightParenth = strpos($data, ")");
			     }
			     //echo "Data Currently contians: $data\n";
			    $diffPos = $doubleRightParenth - $endingParenth;
			    //echo "doubleRightPanranthese:$doubleRightParenth   endingParenth:$endingParenth  difPos:$diffPos\n"; 
			   $data = substr_replace($data, '', $endingParenth, $diffPos);
			  }
		      }
		       echo "Data after replace contains: $data" . PHP_EOL;
		       //echo "TestCase is: " . var_dump($expInput) . PHP_EOL;
		       //echo "expInput is: " . var_dump($expInput) . PHP_EOL;
		       //echo "expOutput is: " . var_dump($expOutput) . PHP_EOL;
		
		     
			// expInput must always be an array with two arguments inside to work
			$output=array(); 
			file_put_contents($filepath, $data, FILE_APPEND | LOCK_EX);
                        $qid = $_POST["QuestionID"];
			exec($pyBinPath . " " . "$filepath " . '2>&1', $output, $status);	
			$actualOutput = $output[0]; 
			
			//echo "ExpectedOutput is " . var_dump($expOutput) . PHP_EOL;
			//echo "Whats in Output: " . var_dump($output) . PHP_EOL;
			echo "Actual output is: " . $actualOutput . PHP_EOL;
			//echo "The current status is: " . $status . PHP_EOL;
			if ($status == 1) {    
				//! Handle case where provide answer doesn't successfully run
			      $pointsGiven = 0;
			      echo "Program Doesn't compile, Automatic 0\n";
			      $_POST['PointsGiven']=$pointsGiven;
			      $_POST['AutoComments'].="Program couldn't compile \n";
			      $_POST['AutoComments'].= implode("\n", $output);
			      echo "Output:\n" . print_r($output) . "\n";
			      //echo "AutoComments: " . $_POST['AutoComments']. PHP_EOL;
			      
			      $ch2 = curl_init($url);
			      curl_setopt($ch2, CURLOPT_POST, TRUE); // store result in var
			      curl_setopt($ch2, CURLOPT_POSTFIELDS, http_build_query($_POST)); // store result in var
			      curl_setopt($ch2, CURLOPT_RETURNTRANSFER, TRUE); // store result in var 
			      $updatedQuestion = curl_exec($ch2);
			      echo $updatedQuestion; 
			      curl_close($ch2);
			      return;
			}

			if ($actualOutput != $expOutput) {
				//! Handle case where the test case fails 
			        echo "actualOutput:$actualOutput expOutput:$expOutput".PHP_EOL;
				echo "Taking away 7 Points\n";
				$pointsGiven -= 7;
				$_POST['AutoComments'].= implode("\n", $output);
			        echo "testCases failed" . " Current Points: " . $pointsGiven . PHP_EOL;
			}

		    //Restoring file to orginal form
		    file_put_contents($filepath, $answer, LOCK_EX);

	       }			
		//echo "The current amount of points outside the Grading Loop is: $pointsGiven points" . PHP_EOL;
		//echo "Points is a \n";
		//var_dump($_POST['Points']);
	
		//Grading is done output result
		if ($pointsGiven < 0) {
			$pointsGiven = 0;
		}
		if ($pointsGiven == $totalPoints){
		  //echo "Good Job you got a perfect score" . PHP_EOL;
		  $_POST['AutoComments'].='Good Job';
		}
		
		// We are done running the test cases so now lets send the points given.
		   $_POST['PointsGiven']=$pointsGiven;
		   //echo "student received " . $pointsGiven . " points" . PHP_EOL;
		   //echo "The AutoComments are: \n";
		   //var_dump($_POST['AutoComments']);
		   $ch2 = curl_init($url);
		   curl_setopt($ch2, CURLOPT_POST, TRUE); // store result in var
		   curl_setopt($ch2, CURLOPT_POSTFIELDS, http_build_query($_POST, null, '&', PHP_QUERY_RFC3986)); // store result in var
		   curl_setopt($ch2, CURLOPT_RETURNTRANSFER, TRUE); // store result in var 
		   $updatedQuestion = curl_exec($ch2);
		   echo $updatedQuestion;
		   curl_close($ch2);
		   return;
     }

	if ($query == "SetFinalGrade") {
		$url2 = "https://web.njit.edu/~ar664/cs490/backend/getexam.php";
		$ch=curl_init($url2);
		curl_setopt($ch, CURLOPT_POST, TRUE); // store result in var
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($_POST)); // store result in var
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); // store result in var 
		$json_getExam= curl_exec($ch);
		$examValues= json_decode($json_getExam);
		$examArry= $examValues->Exam;
		//echo "Here is the exam stuff: " . var_dump($examArry) . PHP_EOL;	
		curl_close($ch);

		//Grades the Student's entire exam
		// Exam is assumed to be out of 100 points
		$finalGrade=0;
		$counter=1;
		foreach($examArry as $json_exam=>$exam_Value){
		    $pointsGiven=$exam_Value->PointsGiven;
		    //echo "You got $pointsGiven Points on Question $counter++\n";
		    $finalGrade+=$pointsGiven;
		    //echo "Points is now set to: " . $pointsGiven . PHP_EOL;
		    //echo "FinalGrade is now set to: " . $pointsGiven . PHP_EOL;
	        }
                //echo "Student's finalgrade: $finalGrade" . PHP_EOL;
	        $_POST['FinalGrade']=$finalGrade;
		$ch2=curl_init($url);
		curl_setopt($ch2, CURLOPT_POST, TRUE); // store result in var
		curl_setopt($ch2, CURLOPT_POSTFIELDS, http_build_query($_POST)); // store result in var
		curl_setopt($ch2, CURLOPT_RETURNTRANSFER, TRUE); // store result in var 
		$releaseExam= curl_exec($ch2);
		curl_close($ch2);
		return;
       }
}else{
   echo "Enter Username and Password Post Vars" . PHP_EOL; 
}
?>
