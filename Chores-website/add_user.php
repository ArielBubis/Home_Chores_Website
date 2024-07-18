<?php
    // signup form vairables
    $email = $_POST['email'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $password = $_POST['password'];

    if (isset($email) && isset($first_name) && isset($last_name) && isset($password)) { // check that all form fields are filled
        require_once('db.php'); // database connection

        // Check if email exists
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            echo "Email already exists. Please use a different email.";
        } else {
            // Proceed with signup
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO Users(email, first_name, last_name, password)
            VALUES(?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ssss', $email, $first_name, $last_name, $password_hash);
            $stmt->execute();

            echo "Signup successful!";
        }
        $stmt->close();
    }
    $conn->close();
    
?>