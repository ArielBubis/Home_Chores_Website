<?php
require_once 'db.php';
// var_dump($_POST);
// signup form variables
$email = $_POST['email'] ?? '';
$firstName = $_POST['first_name'] ?? '';
$lastName = $_POST['last_name'] ?? '';
$password = $_POST['password'] ?? '';
$avatarColor = ltrim($_POST['avatar_color'] ?? '', '#');
$passwordConfirm = $_POST['password_confirm'] ?? '';


function registerUser($conn, $email, $firstName, $lastName, $avatarColor, $password) {
	$passwordHash = password_hash($password, PASSWORD_DEFAULT);
	$sql = "INSERT INTO Users(email, first_name, last_name, avatar_color, password) VALUES(?, ?, ?, ?, ?)";
	$stmt = $conn->prepare($sql);
	if (!$stmt) {
		return ['success' => 0, 'message' => 'Failed to prepare statement.'];
	}
	$stmt->bind_param('sssss', $email, $firstName, $lastName, $avatarColor, $passwordHash);
	$stmt->execute();
	if ($stmt->affected_rows > 0) {
        session_start();
        // $_SESSION['signedUp'] = 'true';
		return ['success' => 1, 'message' => 'User registered successfully.'];
	}
	return ['success' => 0, 'message' => 'Failed to register user.'];
}

$response = ['success' => 0];

// check if all fields are provided
if (empty($email) || empty($firstName) || empty($lastName) || empty($avatarColor) || empty($password) || empty($passwordConfirm)) {
    $response['message'] = 'All fields are required.';
} else {
    // check if the email already exists
    $stmt = $conn->prepare("SELECT email FROM Users WHERE email = ?");
    if (!$stmt) {
        $response['message'] = 'Failed to prepare statement.';
    } else {
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $response['message'] = 'Email already exists.';
        } else {
            // check if the passwords match
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

$conn->close();
echo json_encode($response);
?>