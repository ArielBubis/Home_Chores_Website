<?php
// Database connection
include 'db.php';

// Get the email from the POST request
$email = $_POST['email'];

// Prepare and execute the query to check if the email exists
$sql = "SELECT COUNT(*) as count FROM Users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

// Return the result as JSON
echo json_encode(['exists' => $row['count'] > 0]);

$stmt->close();
$conn->close();
?>