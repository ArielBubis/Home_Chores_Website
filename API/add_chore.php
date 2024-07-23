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
        $userInfo = getUserInfo($conn, $choreUser);
        $response = [
            'success' => 1,
            'message' => 'Chore added successfully.',
            'choreUserName' => $userInfo['first_name'] . ' ' . $userInfo['last_name'],
            'avatar_color' => $userInfo['avatar_color'],
            'dateAdded' => $dateAdded,
            'choreTitle' => $choreTitle,
            'chore_num' => $stmt->insert_id
        ];
        return $response;
    }
    return ['success' => 0, 'message' => 'Failed to add chore.'];
}


// Prepare the response array
$response = ['success' => 0];

// Validate form data
if (empty($choreTitle) || empty($choreUser) || empty($dateAdded)) {
    $response['message'] = 'All fields are required.';
} else {
    // Add the chore to the database
    $response = addChore($conn, $list_id, $choreTitle, $choreUser, $dateAdded);
}

$conn->close();
echo json_encode($response);


// Define a function to get the user's first name, last name, and image path by user id
function getUserInfo($conn, $userId) {
    $firstName = '';
    $lastName = '';
    $imageColorCode = '';

    $sql = "SELECT first_name, last_name, avatar_color FROM Users WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        // Return an indication of failure to prepare the statement
        return ['success' => false, 'message' => 'Failed to prepare statement.'];
    }
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $stmt->bind_result($firstName, $lastName, $imageColorCode);
    if ($stmt->fetch()) {
        // Check if the fetched data is not empty or null
        if (!empty($firstName) && !empty($lastName)) {
            $stmt->close();
            return ['first_name' => $firstName, 'last_name' => $lastName, 'avatar_color' => $imageColorCode];
        }
    }
    $stmt->close();
    // Return a default structure indicating no user found or incomplete data
    return ['success' => false, 'message' => 'No user found or incomplete data.'];
}

// Get the user's first name and last name
?>