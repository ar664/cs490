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
	           echo "QuestionID:" . $_POST['QuestionID'] . PHP_EOL;
	           echo 'You didn’t give me a Post Var: QuestionID';
	           return;
             }
		//The values Needed 
		$pointsGiven=0;
                $pointsOff=0;
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

                //All the vars necessary to get funct/TestCaseComments
		$answer=$_POST['Answer']; 
		$finalAutoComment=array();
		$syntxErrs=array();
		$comments_Arry=array();
		$studentFunct=(object)['SyntaxError'=>''];
		$autoComments=(object)['TestCaseID'=>1, 'TestCase'=>'', 'Points'=>0, 'Expected' =>'', 'Output'=>'', 'Comments'=>''];
		
		//if "def" or ( not in the student's Function 
		//give them a zero because function will not compile 
		$defFirstIdx = stripos($answer, "def ");
		$parensFirstIdx = stripos($answer, "(");
		//If student is missing a colon after function declaration
		//Subtract some points and add the colon
		$colonFirstIdx = stripos($answer, "):\n");
		// echo $defFirstIdx, " ", $parensFirstIdx;
		
		if ($defFirstIdx === FALSE || $parensFirstIdx === FALSE) {
			//! the answer string provided is invalid, so handle this error.i
			echo "you forgot the def statement\n";
			$pointsGiven = 0;
			array_push($syntxErrs, "answer provided isn't a valid python function, missing def declaration");
			//echo "function provided isn't a valid Python function missing def declaration Points Given: " . $pointsGiven
		}
	         //echo "firstColon: $colonFirstIdx\n";	
		if ($colonFirstIdx !== TRUE) {
		    if ($colonFirstIdx === FALSE) {
			//! the answer string provided is invalid, so handle this error.;
			echo "you forgot the colon after the def statement 1\n";
                        $pointsOff = floor(.05*$totalPoints);
			$pointsGiven -= $pointsOff;
		        $colonPIdx =  strpos($answer, ")\n"); 
		        $colonPIdx2 = strpos($answer, ") \n");
		        array_push($syntxErrs, "answer provided isn't a valid python function, missing ':' after def declaration. Adding Colon after def");
			if ($colonPIdx !== FALSE) {	
			    $answer=substr_replace($answer, "):\n", $colonPIdx, 2);
			    echo "This is answer now: \n";
			    echo $answer . PHP_EOL;
		        } else if ($colonPIdx2 !== FALSE) {			
			   $answer=substr_replace($answer, "):\n", $colonPIdx2, 3);
			   echo "This is answer now: \n";
			   echo $answer . PHP_EOL;
			}
		    }
	        }  
		// replace the functionName in studentFunction 
		//if its not FunctName
		$answerFuncNameStartIdx = $defFirstIdx + 4;
		$answerFuncNameLen = $parensFirstIdx - $answerFuncNameStartIdx;
		$answerFuncName = substr($answer, $answerFuncNameStartIdx, $answerFuncNameLen);
	
		if ($answerFuncName !== $functName) {
			//echo "Function name is incorrect So I'm taking away 10 points" . PHP_EOL;
			//echo "answerFuncName:$answerFuncName   functName:$functName" . PHP_EOL;
                        $pointsOff = floor(.05*$totalPoints);
			$pointsGiven -= $pointsOff;
			array_push($syntxErrs, "Deduct $pointsOff points or 10%, FunctionName should be: "  . $functName);
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
			 //echo "MatchNotFound: -5 Points\nThe constraint \"$constraint\" doesn't exist within the string\n";
                         $pointsOff = floor(.05*$totalPoints);
			 $pointsGiven -= $pointsOff; 
			 if ($constraint == 'print'){
			  array_push($syntxErrs, "Deduct $pointsOff points or 20%, You forgot to include a $constraint statement");
			 }else if($constraint == 'while' || $constraint == 'for'){   
			  array_push($syntxErrs, "Deduct $pointsOff points or 20%, You forgot to include a $constraint loop");
			 }
		     }
		   }
		}       

	
		foreach($syntxErrs as &$err) { 
		   $studentFunct=(object)['SyntaxError'=>'', 'PointsTken'=>0];
		   $studentFunct->SyntaxError=$err;
		   $studentFunct->PointsTken=floor( .05 * $totalPoints);
		   //echo "This is the Error: $err\n";
		   //echo "Here's SyntaxError now: $studentFunct->SyntaxError\n";
	           //var_dump($studentFunct) . PHP_EOL;
	 	   array_push($finalAutoComment, $studentFunct);
		   //print_r($finalAutoComment) . PHP_EOL;
		}
		
		//echo "This is Json: " . var_dump($jsonTestCases) . PHP_EOL;	
		//echo "Testcases below:" . var_dump($testCases) . PHP_EOL; 
		if ($jsonTestCases == NULL) {
			echo "Testcases within if statement:" . var_dump($testCases["Input"]) . PHP_EOL; 
	        	echo "TestCases: Invalid json. Please enter valid json for TestCases param." . PHP_EOL;
			return;
		}

		//Forloop for testCases to be split one by one and executed
		$inputCases=$jsonTestCases->Input;
		$length=count($inputCases);
		$pointsTaken=$totalPoints/$length;
		$outputCases=$jsonTestCases->Output;
		for($i=0; $i < $length; $i++ ) {
			$expInput = explode("," ,$inputCases[$i]); //expects an array of args
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
		       //echo "Data after replace contains: $data" . PHP_EOL;
		       //echo "TestCase is: " . var_dump($expInput) . PHP_EOL;
		       //echo "expInput is: " . var_dump($expInput) . PHP_EOL;
		       //echo "expOutput is: " . var_dump($expOutput) . PHP_EOL;		
		     
			// expInput must always be an array with specificed args inside to work
			$output=array(); 
			file_put_contents($filepath, $data, FILE_APPEND | LOCK_EX);
                        $qid = $_POST["QuestionID"];
			exec($pyBinPath . " " . "$filepath " . '2>&1', $output, $status);	
			$actualOutput = $output[0];
                        $autoComments->TestCaseID=$i+1;
			
			//echo "ExpectedOutput is " . var_dump($expOutput) . PHP_EOL;
			//echo "Whats in Output: " . var_dump($output) . PHP_EOL;
			//echo "Actual output is: " . $actualOutput . PHP_EOL;
			//echo "The current status is: " . $status . PHP_EOL;
			if ($status == 1) {    
				//! Handle case where provide answer doesn't successfully run
			      echo "We hit an error\n";
			      $pointsGiven = 0;
			      $_POST['PointsGiven']=$pointsGiven;
			      $autoComments->TestCase=$inputCases[$i];
			      $autoComments->Expected=$expOutput;
			      array_push($comments_Arry, "Program couldn't compile");
			      $txtDat=implode("\n", $output);
			      array_push($comments_Arry, $txtDat);
			      $autoComments->Comments=$comments_Arry;
			      $jsonAutoComment=json_encode($autoComments);
			      array_push($finalAutoComment, $jsonAutoComment);
			      $comments_Arry=array();
			      $_POST['AutoComments']=$finalAutoComment;
			      
			      //print_r($finalAutoComment);
			      //echo "Output:\n" . print_r($output) . "\n";
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
                        
			//Change the student's OutputType 
			//to match the Expected Output
                        $expType=gettype($expOutput);
			switch ($expType){
			case "string":
			 break;
			case "boolean":
			 $actualOutput=(bool)$actualOutput;
			 break;
			case "integer":
			 $actualOutput=(int)$actualOutput;
			 break;
			case "array":
			 $actualOutput=(array)$actualOutput;
			 break;
			case "object":
			 $actualOutput=(object)$actualOutput;
			 break;
			case "double":
			 $actualOutput=(double)$actualOutput;
			 break;
			default:
			 echo "Not an appropiate datatype \"$expType\"";
			 return;
			}

			if ($actualOutput !== $expOutput) {
				//! Handle case where the test case fails 
			        echo "actualOutput:$actualOutput expOutput:$expOutput".PHP_EOL;
				//echo "Taking away 25%\n";
                                //echo "Output doesn't match the expected one\n";
				$pointsOff = floor($pointsTaken);
				$pointsGiven -= $pointsOff;
				$autoComments->Points=0;
				$autoComments->TestCase=$inputCases[$i];
				$autoComments->Expected=$expOutput;
			        array_push($comments_Arry, "Deduct $pointsOff or (25%). Given:" . $actualOutput . " Expected:" . $expOutput);                                                    $autoComments->Comments=$comments_Arry;
				$autoComments->Output=$actualOutput;
				array_push($finalAutoComment, $autoComments);
				$comments_Arry=array();
				unset($autoComments);
			}else{  
			        echo "Good job the output matches what's expected\n";
				$autoComments->TestCase= $inputCases[$i];
				$autoComments->Points=floor($pointsTaken);
			        $autoComments->Expected= $expOutput;
			        array_push($comments_Arry, 'Good Job');
			        $autoComments->Output = $actualOutput;
				$autoComments->Comments=$comments_Arry;
				array_push($finalAutoComment, $autoComments);
				$comments_Arry=array();
				unset($autoComments);
				}

		    //Restoring file to orginal form
		    file_put_contents($filepath, $answer, LOCK_EX);

	       }			
		echo "The current amount of points outside the Grading Loop is: $pointsGiven points" . PHP_EOL;
		//var_dump($_POST['Points']);
		//echo "this is the AutoComment\n";
                //print_r($finalAutoComment);
		//echo PHP_EOL;

		//Grading is done output result
		 //echo "This is the PointsGiven: " . $pointsGiven . "\n";
		 if ($pointsGiven < 0) {
			$pointsGiven = 0;
		}
		
		// We are done running the test cases so now lets send the points given.
		   $_POST['PointsGiven']=$pointsGiven;
                   echo "encoding the array is the next line\n";
		   $json_AutoComments=json_encode($finalAutoComment);
		   $jsonErr=json_last_error_msg(). PHP_EOL;
		   echo $jsonErr . PHP_EOL;
		   //echo "This JsonFinalAutoComment " . PHP_EOL;  
		   //echo PHP_EOL
		   //echo $json_AutoComments;
		   var_dump($json_AutoComments);
		   $_POST['AutoComments']=$json_AutoComments;
		      
		   $ch2 = curl_init($url);
		   curl_setopt($ch2, CURLOPT_POST, TRUE); // store result in var
		   curl_setopt($ch2, CURLOPT_POSTFIELDS, http_build_query($_POST, null, '&', PHP_QUERY_RFC3986)); // store result in var
		   curl_setopt($ch2, CURLOPT_RETURNTRANSFER, TRUE); // store result in var 
		   $updatedQuestion = curl_exec($ch2);
		   echo $updatedQuestion;
		   curl_close($ch2);
		   $_POST['AutoComments']='';
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
