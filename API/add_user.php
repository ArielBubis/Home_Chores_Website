<?php
// Include the database connection file
require_once 'db.php';

// Retrieve signup form variables from POST request
$email = $_POST['email'] ?? '';
$firstName = $_POST['first_name'] ?? '';
$lastName = $_POST['last_name'] ?? '';
$password = $_POST['password'] ?? '';
$avatarColor = ltrim($_POST['avatar_color'] ?? '', '#');
$passwordConfirm = $_POST['password_confirm'] ?? '';

// Function to add a user to the database
/**
 * Registers a new user in the database.
 *
 * @param mysqli $conn The database connection object.
 * @param string $email The user's email.
 * @param string $firstName The user's first name.
 * @param string $lastName The user's last name.
 * @param string $avatarColor The user's avatar color.
 * @param string $password The user's password.
 * @return array An associative array containing the success status and message.
 */
function registerUser($conn, $email, $firstName, $lastName, $avatarColor, $password) {
    // Hash the user's password
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // Prepare the SQL statement to insert the new user
    $sql = "INSERT INTO Users(email, first_name, last_name, avatar_color, password) VALUES(?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    // Check if the statement was prepared successfully
    if (!$stmt) {
        return ['success' => 0, 'message' => 'Failed to prepare statement.'];
    }

    // Bind the parameters to the SQL statement
    $stmt->bind_param('sssss', $email, $firstName, $lastName, $avatarColor, $passwordHash);

    // Execute the SQL statement
    $stmt->execute();

    // Check if the user was successfully registered
    if ($stmt->affected_rows > 0) {
        session_start();
        // $_SESSION['signedUp'] = 'true';
        return ['success' => 1, 'message' => 'User registered successfully.'];
    }

    // Return an error message if the user registration failed
    return ['success' => 0, 'message' => 'Failed to register user.'];
}

// Initialize the response array
$response = ['success' => 0];

// Check if all required fields are provided
if (empty($email) || empty($firstName) || empty($lastName) || empty($avatarColor) || empty($password) || empty($passwordConfirm)) {
    $response['message'] = 'All fields are required.';
} else {
    // Prepare the SQL statement to check if the email already exists
    $stmt = $conn->prepare("SELECT email FROM Users WHERE email = ?");
    if (!$stmt) {
        $response['message'] = 'Failed to prepare statement.';
    } else {
        // Bind the email parameter to the SQL statement
        $stmt->bind_param('s', $email);

        // Execute the SQL statement
        $stmt->execute();
        $stmt->store_result();

        // Check if the email already exists
        if ($stmt->num_rows > 0) {
            $response['message'] = 'Email already exists.';
        } else {
            // Check if the passwords match
            if ($password !== $passwordConfirm) {
                $response['message'] = 'Passwords do not match.';
            } else {
                // If all checks pass, register the user
                $response = registerUser($conn, $email, $firstName, $lastName, $avatarColor, $password);
            }
        }
        $stmt->close();
    }
}

// Close the database connection
$conn->close();

// Return the response as a JSON object
echo json_encode($response);
?>