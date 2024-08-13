<?php
// Include the database connection file
require_once('db.php');

// Get the user ID from the session
$user_id = $_SESSION['user_id'];

// Check if the user is logged in
if (!$_SESSION['userLoggedIn']) {
    die("User not logged in.");
}

// SQL query to create a new household with the current user as the responsible user
$sql = "INSERT INTO Household (responsible_user_id) VALUES (?)";
$stmt = $conn->prepare($sql);

// Check if the SQL statement was prepared successfully
if ($stmt === false) {
    die("Prepare failed: " . $conn->error);
}

// Bind the user ID parameter to the SQL statement
$stmt->bind_param("i", $user_id);

// Execute the SQL statement
if ($stmt->execute()) {
    // Get the ID of the newly created household
    $house_id = $stmt->insert_id;

    // SQL query to assign the user to the new household
    $sql = "INSERT INTO Users_partOf_Household (user_id, house_id) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);

    // Check if the SQL statement was prepared successfully
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    // Bind the user ID and household ID parameters to the SQL statement
    $stmt->bind_param("ii", $user_id, $house_id);

    // Execute the SQL statement
    if ($stmt->execute()) {
        // User successfully assigned to the household
    } else {
        die("Error assigning user to household: " . $stmt->error);
    }
} else {
    die("Error creating household: " . $stmt->error);
}
?>