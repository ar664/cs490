<?php

function checkpassword($un, $pw){

    $servername = "sql2.njit.edu";
    $username = "ar664";
    $password = file_get_contents("password.txt");
    fclose($passwordFile);

    $password = trim($password);
    //Create connection
    $conn = mysqli_connect($servername, $username, $password, $username);

    // Check connection
    if (mysqli_connect_error()) {
        die("Connection failed: " . mysqli_connect_error());
    }
    //echo "Connected successfully to sql database\n";
    
    $sql="SELECT * FROM Credential WHERE Username='$un'";

    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        // output data
        $row = mysqli_fetch_assoc($result);
        $myhash = bin2hex(hash("sha256", "$pw", "false"));
        if($myhash == $row["Password"]) {
            if($row["Type"] == "Student")
            {
                echo '{ "dbSuccess":true, "Student":true, "Teacher":false }';
            } else 
            {
                echo '{ "dbSuccess":true, "Student":false, "Teacher":true }';
            }
            //echo '{ "dbSuccess":true, "dbType":"' . $row["Type"] . '" }';
        } else {
            echo '{ "dbSuccess":false, "Here1":true }';
        }

    } else {
        echo '{ "dbSuccess":false, "Here2": true }';
    }
    mysqli_close($conn);
}

if(!isset($_POST["Username"]))
{
    die('{"POST":"Username not set"}');
}

if(!isset($_POST["Password"]))
{
    die('{"POST":"Password not set"}');
}

checkpassword($_POST["Username"], $_POST["Password"]);

?>
