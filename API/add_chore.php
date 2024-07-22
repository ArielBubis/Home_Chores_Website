<?php
require_once 'db.php';

// Retrieve form data
$list_Id = $POST['list_id'] ?? '';
$choreTitle = $_POST['choreTitle'] ?? '';
$choreUser = $_POST['choreUser'] ?? '';
$dateAdded = $_POST['choreDate'] ?? '';

// // Define a function to add a chore to the database
// function addChore($conn, $choreTitle, $choreUser, $dateAdded) {
//     $sql = "INSERT INTO Chores (list_id, chore_title, date_added, user_id, finished) VALUES (?, ?, ?, ?, 0)";
//     $stmt = $conn->prepare($sql);
//     if (!$stmt) {
//         return ['success' => 0, 'message' => 'Failed to prepare statement.'];
//     }
//     $stmt->bind_param('issi', $list_id, $choreTitle, $dateAdded, $choreUser);
//     $stmt->execute();
//     if ($stmt->affected_rows > 0) {
//         return ['success' => 1, 'message' => 'Chore added successfully.'];
//     }
//     return ['success' => 0, 'message' => 'Failed to add chore.'];
// }

// // Prepare the response array
// $response = ['success' => 0];

// // Validate form data
// if (empty($choreTitle) || empty($choreUser) || empty($dateAdded)) {
//     $response['message'] = 'All fields are required.';
// } else {
//     // Add the chore to the database
//     $response = addChore($conn, $choreTitle, $choreUser, $dateAdded);
// }

// $conn->close();
// echo json_encode($response);
?>
