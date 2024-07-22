<?php
require_once 'db.php';

// Retrieve form data
$list_id = $_POST['listId'];
$choreTitle = $_POST['choreTitle'] ?? '';
$choreUser = $_POST['choreUser'] ?? '';
$dateAdded = $_POST['choreDate'] ?? '';
// $formattedDate = date('d/m/Y');


// Define a function to add a chore to the database
function addChore($conn, $list_id, $choreTitle, $choreUser, $dateAdded) {
    $sql = "INSERT INTO Chores (list_id, chore_title, date_added, user_id, finished) VALUES (?, ?, ?, ?, 0)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        return ['success' => 0, 'message' => 'Failed to prepare statement.'];
    }
    $stmt->bind_param('issi', $list_id, $choreTitle, $dateAdded, $choreUser);
    $stmt->execute();
    if ($stmt->affected_rows > 0) {
        $choreUserName = getUserName($conn, $choreUser);
        return ['success' => 1, 'message' => 'Chore added successfully.', 'choreUserName' => $choreUserName, 'dateAdded' => $dateAdded, 'choreTitle' => $choreTitle, 'chore_num' => $stmt->insert_id];
    }
    return ['success' => 0, 'message' => 'Failed to add chore.'];
}


// Prepare the response array
$response = ['success' => 0];

// Validate form data
if (empty($choreTitle) || empty($choreUser) || empty($dateAdded)) {
    $response['message'] = 'All fields are required. ' . "listId= " . $list_id . " ,choreTitle= " . $choreTitle . " ,choreUser= " . $choreUser . " ,dateAdded= " . $dateAdded;
} else {
    // Add the chore to the database
    $response = addChore($conn, $list_id, $choreTitle, $choreUser, $dateAdded);
}

$conn->close();
echo json_encode($response);


// Define a function to get the user's first name and last name by user id
function getUserName($conn, $userId) {
    $sql = "SELECT first_name, last_name FROM Users WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        return '';
    }
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $stmt->bind_result($firstName, $lastName);
    $stmt->fetch();
    $stmt->close();
    return $firstName . ' ' . $lastName;
}

// Get the user's first name and last name
