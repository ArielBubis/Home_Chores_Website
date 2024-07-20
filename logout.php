<?php
    session_start();
    $_SESSION = array(); // Clear all session variables

    if(isset($_COOKIE["email"])) {
        setcookie("email", $_COOKIE["email"], time() - 1);
    }
    if(isset($_COOKIE["password_hash"])) {
        setcookie("password_hash", $_COOKIE["password"], time() - 1);
    }
    session_destroy(); // Destroy the session

    // Redirect to login page
    header("Location: loginscreen.php");
    exit;
?>