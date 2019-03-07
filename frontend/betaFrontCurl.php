<?php
    if(isset($_POST["query"])) {
        $req = curl_init();

        curl_setopt($req, CURLOPT_URL,"https://web.njit.edu/~ar664/cs490/middleend/middleEnd.php");
        curl_setopt($req, CURLOPT_POST, 1);
        curl_setopt($req, CURLOPT_POSTFIELDS, http_build_query($_POST));

        curl_setopt($req, CURLOPT_RETURNTRANSFER, true);

        $output = curl_exec($req);
        if(empty($output)) { // If there is no response
            $server_output = "Empty result.";
        }
        else {
            $server_output = $output;
        }
        curl_close($req);
    }
    else if(isset($_POST["username"]) && isset($_POST["password"])) { // If the formatting ain't pristine then it's a bad request
        $req = curl_init();

        $us = $_POST["username"];
        $pw = $_POST["password"];
        
        curl_setopt($req, CURLOPT_URL,"https://web.njit.edu/~ar664/cs490/middleend/middleEnd.php");
        curl_setopt($req, CURLOPT_POST, 1);
        curl_setopt($req, CURLOPT_POSTFIELDS,"Username=" . $us . "&Password=" . $pw);

        curl_setopt($req, CURLOPT_RETURNTRANSFER, true);

        $output = curl_exec($req);
        if(empty($output)) { // If there is no response
            $server_output = "Empty result.";
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