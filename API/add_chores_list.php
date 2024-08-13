<?php
require_once 'db.php'; // Include the database connection file

// Retrieve form data from POST request
$res_user_id = $_POST['res_user_id'];
$house_id = $_POST['cl_house_id'];
$list_title = $_POST['list_title'] ?? '';
$due_date = $_POST['due_date'] ?? '';
$status = $_POST['status'] ?? 'not finished';

// Define a function to add a chores list to the database
/**
 * Adds a chores list to the database.
 *
 */
function addChoresList($conn, $house_id, $res_user_id, $list_title, $due_date, $list_status) {
    // Prepare the SQL statement to insert a new chores list
    $sql = "INSERT INTO chores_list (house_id, responsible_user_id, list_title, due_date, status) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        return ['success' => 0, 'message' => 'Failed to prepare statement.'];
    }

    // Bind parameters to the SQL statement
    $stmt->bind_param('iisss', $house_id, $res_user_id, $list_title, $due_date, $list_status);
    $stmt->execute();

    // Check if the chores list was successfully added
    if ($stmt->affected_rows > 0) {
        $list_id = $stmt->insert_id; // Get the ID of the newly inserted list
        $list_userInfo = getUserInfo($conn, $res_user_id);

        $response = [
            'success' => 1,
            'message' => 'Chores list added successfully.',
            'listUserName' => $list_userInfo['first_name'] . ' ' . $list_userInfo['last_name'],
            'avatar_color' => $list_userInfo['avatar_color'],
            'list_id' => $list_id,
            'list_title' => $list_title,
            'due_date' => $due_date,
            'status' => $list_status
        ];
        return $response;
    }
    return ['success' => 0, 'message' => 'Failed to add chores list.'];
}

// Prepare the response array
$response = ['success' => 0];

// Validate form data
if (empty($list_title) || empty($due_date)) {
    $response['message'] = 'List title and due date are required.';
} else {
    // Add the chores list to the database
    $response = addChoresList($conn, $house_id, $res_user_id, $list_title, $due_date, $status);
}

// Close the database connection
$conn->close();

// Return the response as JSON
echo json_encode($response);

// Define a function to get the user's first name, last name, and image path by user id
/**
 * Retrieves user information from the database.
 */
function getUserInfo($conn, $userId) {
    $firstName = '';
    $lastName = '';
    $imageColorCode = '';

    // Prepare the SQL statement to retrieve user information
    $sql = "SELECT first_name, last_name, avatar_color FROM Users WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        // Return an indication of failure to prepare the statement
        return ['success' => false, 'message' => 'Failed to prepare statement.'];
    }

    // Bind parameters to the SQL statement
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $stmt->bind_result($firstName, $lastName, $imageColorCode);

    // Fetch the user information
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
?>