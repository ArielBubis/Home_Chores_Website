<?php
// Include the database connection file
require_once 'db.php';

// Retrieve email and house_id from POST request
$email = $_POST['email'] ?? '';
$house_id = $_POST['house_id'] ?? 0;

// Check if the user exists in the Users table
$sql = "SELECT user_id FROM Users WHERE email = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo json_encode(['success' => 0, 'message' => 'Failed to prepare statement.']);
    exit;
}
$stmt->bind_param('s', $email);
$stmt->execute();
$stmt->store_result();

// If no user is found with the provided email, return an error message
if ($stmt->num_rows == 0) {
    echo json_encode(['success' => 0, 'message' => 'User does not exist.']);
    exit;
}
$stmt->bind_result($user_id);
$stmt->fetch();
$stmt->close();

// Check if the user is already part of the specified household
$sql = "SELECT * FROM Users_partOf_Household WHERE user_id = ? AND house_id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo json_encode(['success' => 0, 'message' => 'Failed to prepare statement.']);
    exit;
}
$stmt->bind_param('ii', $user_id, $house_id);
$stmt->execute();
$stmt->store_result();

// If the user is already part of the household, return an error message
if ($stmt->num_rows > 0) {
    echo json_encode(['success' => 0, 'message' => 'User is already part of the household.']);
    exit;
}
$stmt->close();

// Insert the user into the Users_partOf_Household table
$sql = "INSERT INTO Users_partOf_Household(user_id, house_id) VALUES(?, ?)";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo json_encode(['success' => 0, 'message' => 'Failed to prepare statement.']);
    exit;
}
$stmt->bind_param('ii', $user_id, $house_id);
$stmt->execute();

// If the insertion is successful, retrieve the user's details
if ($stmt->affected_rows > 0) {
    $sql = "SELECT first_name, last_name FROM Users WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo json_encode(['success' => 0, 'message' => 'Failed to prepare statement.']);
        exit;
    }
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $stmt->bind_result($firstName, $lastName);
    $stmt->fetch();
    $stmt->close();

    // Return a success message along with the user's details
    echo json_encode([
        'success' => 1,
        'message' => 'User registered successfully.',
        'user_id' => $user_id,
        'first_name' => $firstName,
        'last_name' => $lastName
    ]);
    exit;
}

// If the insertion fails, return an error message
echo json_encode(['success' => 0, 'message' => 'Failed to add user to household.']);
?>