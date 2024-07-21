<?php
session_start();
require_once 'db.php'; // connect to database

$response = array();
$email_entered = $_POST['email'];
$password_entered = $_POST['password'];

if (empty($email_entered) || empty($password_entered)) {
    $response = ['success' => 0];
        $response['message'] = 'All fields are required';
    } else {
        $stmt = $conn->prepare("SELECT email, password FROM users WHERE email = ?");
        if (!$stmt) {
            $response = ['success' => 0];
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
                    $response = ['success' => 1];
                    $response['message'] = '';

                    // header("Location: index.php");
                    // exit;

                } else {
                    $response = ['success' => 0];
                    $response['message'] = 'Incorrect password. Please try again';
                    //  header("Location: loginscreen.php"); // Redirect back to the login page

                }
            } else {
                $response = ['success' => 0];
                $response['message'] = 'User not found. Please check your details';
                //  header("Location: loginscreen.php"); // Redirect back to the login page

            }
        $stmt->close();
        }
    }
$conn->close();

echo json_encode($response);
exit;