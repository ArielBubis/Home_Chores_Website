<?php
session_start();
require_once('db.php'); // connect to database

// Check if there are cookies saved to use at login & handle cookies
if (!isset($_SESSION['userLoggedIn']) && isset($_COOKIE['email']) && isset($_COOKIE['password'])) {
    $email = $_COOKIE['email'];
    $password = $_COOKIE['password'];

    $_SESSION['userLoggedIn'] = true;
    $_SESSION['email'] = $email;

    header("Location: index.php");
    exit;
}

// login authentication 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the submitted form data
    if (isset($_POST['email']) && isset($_POST['password'])) {
        $email_entered = $_POST['email'];
        $password_entered = $_POST['password'];

        // Use prepared statements to prevent SQL injection
        $stmt = $conn->prepare("SELECT email, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email_entered);
        $stmt->execute();
        $result = $stmt->get_result();
        $user_record = $result->fetch_assoc();

        if ($user_record) { // User exists
            $email = $user_record['email'];
            $password_hash = $user_record['password'];
            $verify = password_verify($password_entered, $password_hash);
            // $password = $user_record['password'];

            // Debugging: Check if the password hash is retrieved
            echo "Password hash from database: $password_hash<br>";
            echo "Password from user: $password_entered<br>";

            if ($verify) {
            // if ($password_hash === $password_entered) {
                $_SESSION['userLoggedIn'] = true;
                $_SESSION['email'] = $email;

                // Handle "remember me" functionality
                if (isset($_POST['rememberMe'])) {
                    setcookie('email', $email, time() + (86400 * 30), "/");
                    setcookie('password', $password_hash, time() + (86400 * 30), "/");
                }

                header("Location: index.php");
                exit;
            } else {
                echo "Incorrect password. Please try again.";
            }
        } else {
            echo "User not found. Please check your details.";
        }
    } else {
        echo "Please enter both email and password.";
    }
}
