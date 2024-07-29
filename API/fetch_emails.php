<?php
// Database connection
include 'db.php';

// Query to fetch user emails
$sql = "SELECT email FROM Users";
$results = $conn->query($sql);

// Fetch emails and store them in an array
$emails = [];
while ($row = $results->fetch_assoc()) {
    $emails[] = $row['email'];
}

// Return emails as JSON
echo json_encode($emails);
?>