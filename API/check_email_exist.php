<?php
// Database connection
include 'db.php';

// Get the email from the POST request
$email = $_POST['email'];

/**
 * Check if the provided email exists in the Users table.
 *
 * @param mysqli $conn The database connection object.
 * @param string $email The email to check.
 * @return bool True if the email exists, false otherwise.
 */
function checkEmailExists($conn, $email) {
    // Prepare the SQL query to check if the email exists
    $sql = "SELECT COUNT(*) as count FROM Users WHERE email = ?";
    $stmt = $conn->prepare($sql);

    // Bind the email parameter to the SQL statement
    $stmt->bind_param("s", $email);

    // Execute the SQL statement
    $stmt->execute();

    // Get the result of the query
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    // Close the statement
    $stmt->close();

    // Return true if the email exists, false otherwise
    return $row['count'] > 0;
}

// Check if the email exists and return the result as JSON
echo json_encode(['exists' => checkEmailExists($conn, $email)]);

// Close the database connection
$conn->close();
?>