<?php
require_once 'db.php';

// Retrieve form data
$list_id = $_POST['listId'];
$choreTitle = $_POST['choreTitle'] ?? '';
$choreUser = $_POST['choreUser'] ?? '';
$dateAdded = $_POST['choreDate'] ?? '';

$formattedDate = date('d/m/Y', strtotime($dateAdded));


// Define a function to add a chore to the database
function addChore($conn, $list_id, $choreTitle, $choreUser, $formattedDate) {
    $sql = "INSERT INTO Chores (list_id, chore_title, date_added, user_id, finished) VALUES (?, ?, ?, ?, 0)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        return ['success' => 0, 'message' => 'Failed to prepare statement.'];
    }
    $stmt->bind_param('issi', $list_id, $choreTitle, $formattedDate, $choreUser);
    $stmt->execute();
    if ($stmt->affected_rows > 0) {
        return ['success' => 1, 'message' => 'Chore added successfully.'];
    }
    return ['success' => 0, 'message' => 'Failed to add chore.' . "listId= " . $list_id . " ,choreTitle= " . $choreTitle . " ,choreUser= " . $choreUser . " ,dateAdded= " . $formattedDate];
}

// Prepare the response array
$response = ['success' => 0];

// Validate form data
if (empty($choreTitle) || empty($choreUser) || empty($dateAdded)) {
    $response['message'] = 'All fields are required. ' . "listId= " . $list_id . " ,choreTitle= " . $choreTitle . " ,choreUser= " . $choreUser . " ,dateAdded= " . $dateAdded;
} else {
    // Add the chore to the database
    $response = addChore($conn, $list_id, $choreTitle, $choreUser, $formattedDate);
}

$conn->close();
echo json_encode($response);
