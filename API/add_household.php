<?php
require_once('db.php');

$user_id = $_SESSION['user_id'];

if (!$_SESSION['userLoggedIn']) {
    die("User not logged in.");
}

// Create a new household
$sql = "INSERT INTO Household (responsible_user_id) VALUES (?)";
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("i", $user_id);
if ($stmt->execute()) {
    $house_id = $stmt->insert_id;

    // Assign user to the new household
    $sql = "INSERT INTO Users_partOf_Household (user_id, house_id) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("ii", $user_id, $house_id);
    if ($stmt->execute()) {
        // Redirect to the main page after creating the household
        header("Location: ../index.php");
        exit();
    } else {
        die("Error assigning user to household: " . $stmt->error);
    }
} else {
    die("Error creating household: " . $stmt->error);
}
?>
