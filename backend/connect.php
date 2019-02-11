<?php

function checkpassword($un, $pw){

    $servername = "sql1.njit.edu";
    $username = "ar664";
    $password = "UMqgYbp3";

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
        //echo "Name: " . $row["Username"]. " Hash: " . $row["Password"] . "\n";
        if($myhash == $row["Password"]) {
            echo '{ "dbSuccess":true }';
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
