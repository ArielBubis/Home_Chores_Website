<?php
// Assuming you have a PDO connection $pdo
require 'db.php'; // Your database connection file

if (isset($_POST['chore_num'], $_POST['finished'])) {
    $choreNum = $_POST['chore_num'];
    $finished = $_POST['finished'];

    // Prepare SQL statement to update the finished status
    $stmt = $pdo->prepare("UPDATE chores SET finished = ? WHERE chore_num = ?");
    $success = $stmt->execute([$finished, $choreNum]);

    if ($success) {
        echo json_encode(['message' => 'Chore status updated successfully.']);
    } else {
        echo json_encode(['message' => 'Failed to update chore status.']);
    }
} else {
    echo json_encode(['message' => 'Invalid request.']);
}
?>