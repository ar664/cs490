<?php

$servername = "sql2.njit.edu";
$username = "ar664";
$password = file_get_contents("password.txt");

$password = trim($password);
//Create connection
$conn = mysqli_connect($servername, $username, $password, $username);

// Check connection
if (mysqli_connect_error()) {
    die("Connection failed: " . mysqli_connect_error());
}

?>
