<?php
session_start();
require_once('db.php'); // connect to database

$response = array();
$email_entered = $_POST['email'];
$password_entered = $_POST['password'];

// Handle cookies if exists
if (!isset($_SESSION['userLoggedIn']) && isset($_COOKIE['email']) && isset($_COOKIE['password'])) {
    $email_entered = $_COOKIE['email'];
    $password_entered = $_COOKIE['password'];

    $_SESSION['userLoggedIn'] = true;
    $_SESSION['email'] = $email_entered;
    // $response['success'] = true;

    header("Location: index.php");
    exit;
}

// login authentication 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the submitted form data
    if (empty($email_entered) || empty($password_entered)) {
        $response['message'] = 'All fields are required';
    } else {
        // Use prepared statements to prevent SQL injection
        $stmt = $conn->prepare("SELECT email, password FROM users WHERE email = ?");
        if (!$stmt) {
            $response['message'] = 'Failed to prepare statement.';
        } else {
            $stmt->bind_param("s", $email_entered);
            $stmt->execute();
            $result = $stmt->get_result();
            $user_record = $result->fetch_assoc();

            if ($user_record) { // User exists in database 
                $user_email = $user_record['email'];
                $hash = $user_record['password'];

                if (crypt($password_entered, $hash) === $hash) {
                    // compare hashes to check whether the password is correct
                    $_SESSION['userLoggedIn'] = true;
                    $_SESSION['email'] = $email_entered;

                    // Handle "remember me" functionality
                    if (isset($_POST['rememberMe'])) {
                        setcookie('email', $email_entered, time() + (86400 * 14), "/");
                        setcookie('password', $hash, time() + (86400 * 14), "/");
                    }
                    // $response = ['success' => 1];
                    header("Location: index.php");
                    exit;

                } else {
                    $response['message'] = 'Incorrect password. Please try again';
                }
            } else {
                $response['message'] = 'User not found. Please check your details';
            }
        }
        $stmt->close();
    }
}

$conn->close();
echo json_encode($response);

