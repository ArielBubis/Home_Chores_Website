<?php
require_once 'db.php'; // Include the database connection file

// Retrieve form data from POST request
$list_id = $_POST['listId'];
$choreTitle = $_POST['choreTitle'] ?? '';
$choreUser = $_POST['choreUser'] ?? '';
$dateAdded = $_POST['choreDate'] ?? '';

// Define a function to add a chore to the database
/**
 * Adds a chore to the database.
 *
 * @param mysqli $conn The database connection object.
 * @param int $list_id The ID of the list to which the chore belongs.
 * @param string $choreTitle The title of the chore.
 * @param int $choreUser The ID of the user assigned to the chore.
 * @param string $dateAdded The date the chore was added.
 * @return array The response array indicating success or failure.
 */
function addChore($conn, $list_id, $choreTitle, $choreUser, $dateAdded) {
    // Prepare the SQL statement to insert a new chore
    $sql = "INSERT INTO Chores (list_id, chore_title, date_added, user_id, finished) VALUES (?, ?, ?, ?, 0)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        return ['success' => 0, 'message' => 'Failed to prepare statement.'];
    }

    // Bind parameters to the SQL statement
    $stmt->bind_param('issi', $list_id, $choreTitle, $dateAdded, $choreUser);
    $stmt->execute();

    // Check if the chore was successfully added
    if ($stmt->affected_rows > 0) {
        // Retrieve user information for the response
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

// Close the database connection
$conn->close();

// Return the response as JSON
echo json_encode($response);

// Define a function to get the user's first name, last name, and image path by user id
/**
 * Retrieves user information from the database.
 *
 * @param mysqli $conn The database connection object.
 * @param int $userId The ID of the user.
 * @return array The user information including first name, last name, and avatar color.
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