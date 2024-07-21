<?php
    // Check if a session is not already active
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    $_SESSION = array(); // Clear all session variables

    // Assuming cookies were set with the root path '/'
    $cookieParams = ['expires' => time() - 3600, 'path' => '/'];

    if(isset($_COOKIE['email']) && isset($_COOKIE['password'])) {
        setcookie('email', '', $cookieParams);
        setcookie('password', '', $cookieParams);

    }
    session_destroy(); // Destroy the session
    debug_to_console("logged_out: " . $_GET['logged_out']);
    // Check if "logged_out=true" is not in the query string
    if (!isset($_GET['logged_out']) && $_GET['logged_out'] !== 'true'){ 
        // Redirect to login page only if "logged_out=true" is not present
        header("Location: ../loginscreen.php?logged_out=true");    
        exit;
    }    


    function debug_to_console($data) {
        $output = $data;
        if (is_array($output))
            $output = implode(',', $output);
    
        echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
    }
?>

