<?php
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
?>