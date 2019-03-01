<?php

$username = $_POST['Username'];
$password = $_POST['Password'];

if (isset($username) && isset($password)){
	class Student {
		function Student() {
		$this->dbSuccess = false;
		$this->dbType="student";
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
	$Dummy->dbType= $db_json_res['dbType'];
 	echo json_encode($Dummy);
	curl_close($ch1);

	//Tell me your Account Type

} else {
	echo "Username and Password not set in POST FIELDS\n";
}
?>
