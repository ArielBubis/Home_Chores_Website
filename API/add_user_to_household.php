<?php
require_once 'db.php';
$email = $_POST['email'] ?? '';
$house_id = $_POST['house_id'] ?? 0;

// Check if the user exists
$sql = "SELECT user_id FROM Users WHERE email = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo json_encode(['success' => 0, 'message' => 'Failed to prepare statement.']);
    exit;
}
$stmt->bind_param('s', $email);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows == 0) {
    echo json_encode(['success' => 0, 'message' => 'User does not exist.']);
    exit;
}
$stmt->bind_result($user_id);
$stmt->fetch();
$stmt->close();

// Check if the user is already part of the household
$sql = "SELECT * FROM Users_partOf_Household WHERE user_id = ? AND house_id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo json_encode(['success' => 0, 'message' => 'Failed to prepare statement.']);
    exit;
}
$stmt->bind_param('ii', $user_id, $house_id);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    echo json_encode(['success' => 0, 'message' => 'User is already part of the household.']);
    exit;
}
$stmt->close();

// Insert the user into the household
$sql = "INSERT INTO Users_partOf_Household(user_id, house_id) VALUES(?, ?)";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo json_encode(['success' => 0, 'message' => 'Failed to prepare statement.']);
    exit;
}
$stmt->bind_param('ii', $user_id, $house_id);
$stmt->execute();
if ($stmt->affected_rows > 0) {
    // Retrieve the user's details
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

    echo json_encode([
        'success' => 1,
        'message' => 'User registered successfully.',
        'user_id' => $user_id,
        'first_name' => $firstName,
        'last_name' => $lastName
    ]);
    exit;
}
echo json_encode(['success' => 0, 'message' => 'Failed to add user to household.']);
?>