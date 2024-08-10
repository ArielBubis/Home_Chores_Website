<?php
    // ---- Connect to the db ----
    mysqli_report(MYSQLI_REPORT_OFF);
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbName = "hw3_205735749_208019729";

    $conn = new mysqli($servername,$username,$password,$dbName);

    if($conn->connect_error){ // check connection
        die("Connection failed:".$conn->connect_error);
    }
    // ----------------------------
 ?>