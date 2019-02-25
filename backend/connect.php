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
    
    $sql="SELECT * FROM Credentials WHERE Username='$un'";

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
            echo '{ "dbSuccess":false }';
        }

    } else {
        echo '{ "dbSuccess":false }';
    }
    mysqli_close($conn);
}


try {
    if(isset($_POST["Username"]) && isset($_POST["Password"]))
    {
        checkpassword($_POST["Username"], $_POST["Password"]);
    } else {
        echo "No post variables set";
    }
} 
catch(Exception $e) {
    echo "Did not send Post values Username & Password: " . $e->getMessage();
}

?>
