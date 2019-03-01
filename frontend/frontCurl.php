<?php
    if(isset($_POST["Username"]) && isset($_POST["Password"])) { // If the formatting ain't pristine then it's a bad request
        $req = curl_init();

        $us = $_POST["Username"];
        $pw = $_POST["Password"];
        
        curl_setopt($req, CURLOPT_URL,"https://web.njit.edu/~amp85/cs490/middleend/middleEnd.php");
        curl_setopt($req, CURLOPT_POST, 1);
        curl_setopt($req, CURLOPT_POSTFIELDS,"Username=" . $us . "&Password=" . $pw);

        curl_setopt($req, CURLOPT_RETURNTRANSFER, true);

        $output = curl_exec($req);
        if(empty($output)) { // If the error is related to php
            $server_output = "PHP Error.";
        }
        else {
            $server_output = $output;
        }
        curl_close($req);
    }
    else {
        $server_output = "Bad request.";
    }
    echo $server_output;
?>
