<?php

$username = $_POST['Username'];
$password = $_POST['Password'];
$studntAnswer= $_POST['Response'];
if (isset($username) && isset($password)){
	class Student {
		function Student() {
		$this->dbSuccess = false;
		$this->Student="false";
		$this->Teacher="false";
	}  
    }
	$Dummy = new Student();   

	// MAKE DB Login Call
	$url1 = "https://web.njit.edu/~ar664/cs490/backend/connect.php";
	$ch1 = curl_init($url1);

	curl_setopt($ch1, CURLOPT_POST, TRUE);
	curl_setopt($ch1, CURLOPT_POSTFIELDS, "Username=" . $username . "&Password=" . $password);
	curl_setopt($ch1, CURLOPT_RETURNTRANSFER, TRUE); // store result in var
	$db_result = curl_exec($ch1);

	$db_json_res = json_decode($db_result, TRUE);
	$Dummy->dbSuccess = $db_json_res['dbSuccess'];
	$Dummy->Student = $db_json_res['Student'];
        $Dummy->Teacher = $db_json_res['Teacher'];
 	echo json_encode($Dummy);
	curl_close($ch1);
} else {
	echo "Username and Password not set in POST FIELDS\n";
}
?>
