<?php
require 'db.php'; // Your database connection file

if (isset($_POST['chore_num'], $_POST['finished'])) {
    $choreNum = $_POST['chore_num'];
    $finished = $_POST['finished'];

    // Prepare SQL statement to update the finished status
    $stmt = $conn->prepare("UPDATE Chores SET finished = ? WHERE chore_num = ?");
    $stmt->bind_param("si", $finished, $choreNum); // 's' for string, 'i' for integer
    $success = $stmt->execute();
    echo json_encode (['message' => 'Chore status updated successfully']);
} else {
    echo json_encode(['message' => 'Invalid request.']);
}
?>