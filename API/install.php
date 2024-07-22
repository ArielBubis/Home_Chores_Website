<?php
    mysqli_report(MYSQLI_REPORT_OFF);
    $servername = "localhost";
    $username = "root";
    $password = "";

    // Create connection
    $conn = new mysqli($servername, $username, $password);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    // Create database
    $dbName = "hw2_205735749_208019729";
    $sql = "CREATE DATABASE IF NOT EXISTS $dbName";
    if ($conn->query($sql)) {
        echo "Database created successfully <br>";

        // Select the newly created database
        $conn->select_db($dbName);
        
        require_once('create_tables.php');
        create_users_table($conn);
        create_chores_list_table($conn);
        create_chrores_table($conn);
        create_responsible_for_list_table($conn);

    } else{
        echo"Error creating data base:".$conn->error . "<br>";
        die();
    }
    
    // ---- Insert values to tables ----

    // Add test yser record 
    $sql = "INSERT INTO Users(email, first_name, last_name, password)
    VALUES('test@test.com', 'test', 'user', 'test123')";
    if($conn->query($sql)) {
        echo "Test user added successfully<br>";
    }
    else {
        echo "Error: " . $sql . " " . $conn->error . "<br>";
    }

    // Add chores lists records
    $sql = "INSERT INTO Chores_List(list_title, due_date, status)
    VALUES
        ('House chores', '2024-06-07', 0),
        ('Shopping' ,'2024-06-08', 1)";
    if($conn->query($sql)) {
        echo "2 Chores lists added successfully<br>";
    }
    else {
        echo "Error: " . $sql . " " . $conn->error . "<br>";
    }

    // Add reponsible users to chores list
    $sql = "INSERT INTO Responsible_For_List(user_id, list_id)
    VALUES
        (1, 1),
        (1, 2)";
    if($conn->query($sql)) {
        echo "Reponsible users assigned successfully<br>";
    }
    else {
        echo "Error: " . $sql . " " . $conn->error . "<br>";
    }

    // Add chores to chores lists 
    $sql = "INSERT INTO Chores(list_id, chore_title, date_added, user_id, finished)
    VALUES
        (1, 'Clean the house', '2024-06-07', 1, 0),
        (1, 'Organize the closet' ,'2024-06-07', 1, 0),
        (1, 'Washing dishes', '2024-06-07', 1, 0),
        (1, 'Paint the wall', '2024-06-09', 1, 0),
        (2, 'Buy milk', '2024-06-08', 1, 0),
        (2, 'Buy wall paint' ,'2024-06-09', 1, 0),
        (2, 'Buy paintbrush', '2024-06-09', 1, 0)";

    if($conn->query($sql)) {
        echo "Chores added successefully to chores lists";
    }
    else {
        echo "Error: " . $sql . " " . $conn->error . "<br>";
    }

    // $stmt = $conn->prepare("INSERT INTO Users(
    //     email, first_name, last_name, password) VALUES(?,?,?,?)");
    // $stmt->bind_param("ssss", $email,$first_name, $last_name, $password);

    $conn->close();
    
?>

