<?php
// Assuming you have a PDO connection $pdo
require 'db.php'; // Your database connection file
if (isset($_POST['chore_num'], $_POST['finished'])) {
    $choreNum = $_POST['chore_num'];
    $finished = $_POST['finished'];

    // Prepare SQL statement to update the finished status
    $stmt = $conn->prepare("UPDATE Chores SET finished = ? WHERE chore_num = ?");
    $stmt->bind_param("si", $finished, $choreNum); // 's' for string, 'i' for integer
    $success = $stmt->execute();

    if ($success) {
        // Check the count of unfinished chores in the same list
        $stmt = $conn->prepare("SELECT COUNT(*) FROM Chores WHERE list_id = (SELECT list_id FROM Chores WHERE chore_num = ?) AND finished = '0'");
        $stmt->bind_param("i", $choreNum);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_array();
        $unfinishedCount = $row[0];

        // Determine the new status based on whether there are any unfinished chores
        $newStatus = ($unfinishedCount > 0) ? 0 : 1; // 0 for unfinished, 1 for finished

        // Update the Chores_List status based on the presence of unfinished chores
        $stmt = $conn->prepare("UPDATE Chores_List SET status = ? WHERE list_id = (SELECT list_id FROM Chores WHERE chore_num = ?)");
        $stmt->bind_param("ii", $newStatus, $choreNum);
        $stmt->execute();

        if ($newStatus == 1) {
            echo json_encode(['message' => 'Chore status updated successfully, and all chores in the list are finished.']);
        } else {
            echo json_encode(['message' => 'Chore status updated successfully, but some chores in the list are unfinished.']);
        }
    } else {
        echo json_encode(['message' => 'Failed to update chore status.']);
    }
} else {
    echo json_encode(['message' => 'Invalid request.']);
}
?>
