<?php
    if(isset($_POST["username"]) && isset($_POST["password"])) { // If the formatting ain't pristine then it's a bad request
        $req = curl_init();

        $us = $_POST["Username"];
        $pw = $_POST["Password"];
        
        curl_setopt($req, CURLOPT_URL,"https://web.njit.edu/~amp85/middleCurlsRevised.php");
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

    else if(isset($_POST["question"]) && isset($_POST["difficulty"]) && isset($_POST["testCases"]) && isset($_POST["topic"])) {
        $req = curl_init();

        $q = $_POST["question"];
        $diff = $_POST["difficulty"];
        $tc = $_POST["testCases"];
        $top = $_POST["topic"];
        
        curl_setopt($req, CURLOPT_URL,"https://web.njit.edu/~amp85/middleCurlsRevised.php");
        curl_setopt($req, CURLOPT_POST, 1);
        curl_setopt($req, CURLOPT_POSTFIELDS,"Question=" . $q . "&Difficulty=" . $diff . "&TestCases=" . $tc . "&Topic=" . $top);

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

    else if(isset($_POST["query"])) {//isset($_POST["keyword"]) || (isset($_POST["topic"])) || (isset($_POST["difficulty"]))) {
        $req = curl_init();
        
        $fields = "";

        if(isset($_POST["keyword"])) {
            $fields = $fields . "Keyword=" . $_POST["keyword"];
        }
        if(isset($_POST["topic"]) && strlen($fields) > 0) {
            $fields = $fields . "Topic=" . $_POST["topic"];
        }
        if(isset($_POST["difficulty"]) && strlen($fields) > 0) {
            $fields = $fields . "Difficulty=" . $_POST["difficulty"];
        }

        curl_setopt($req, CURLOPT_URL,"https://web.njit.edu/~amp85/middleCurlsRevised.php");
        curl_setopt($req, CURLOPT_POST, 1);
        curl_setopt($req, CURLOPT_POSTFIELDS, $fields);

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