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

// Prepare the response
$response = ['logged_out' => true];

// Set Content-Type header to application/json
header('Content-Type: application/json');

// Return the JSON response
echo json_encode($response);

exit;
?>