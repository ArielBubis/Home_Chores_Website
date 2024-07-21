<?php
    session_start();
    $_SESSION = array(); // Clear all session variables

    // Assuming cookies were set with the root path '/'
    $cookieParams = ['expires' => time() - 3600, 'path' => '/'];

    if(isset($_COOKIE['email']) && isset($_COOKIE['password'])) {
        setcookie('email', '', $cookieParams);
        setcookie('password', '', $cookieParams);

    }
    session_destroy(); // Destroy the session

    // Redirect to login page
    header("Location: loginscreen.php");
    exit;
?>