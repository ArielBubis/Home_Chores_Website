<?php 
require 'db.php'; // Your database connection file
// function updateChoreListStatus($conn, $choreNum) {
//     // Check the count of unfinished chores in the same list
//     $stmt = $conn->prepare("SELECT COUNT(*) FROM Chores WHERE list_id = (SELECT list_id FROM Chores WHERE chore_num = ?) AND finished = '0'");
//     $stmt->bind_param("i", $choreNum);
//     $stmt->execute();
//     $result = $stmt->get_result();
//     $row = $result->fetch_array();
//     $unfinishedCount = $row[0];

//     // Determine the new status based on whether there are any unfinished chores
//     $newStatus = ($unfinishedCount > 0) ? 0 : 1; // 0 for unfinished, 1 for finished

//     // Update the Chores_List status based on the presence of unfinished chores
//     $stmt = $conn->prepare("UPDATE Chores_List SET status = ? WHERE list_id = (SELECT list_id FROM Chores WHERE chore_num = ?)");
//     $stmt->bind_param("ii", $newStatus, $choreNum);
//     $stmt->execute();

//     if ($newStatus == 1) {
//         return ['message' => 'Chore status updated successfully, and all chores in the list are finished.'];
//     } else {
//         return ['message' => 'Chore status updated successfully, but some chores in the list are unfinished.'];
//     }
// }
function updateChoreListStatus($conn, $listId) {
    // Check the count of unfinished chores in the same list
    $stmt = $conn->prepare("SELECT COUNT(*) FROM Chores WHERE list_id = ? AND finished = '0'");
    $stmt->bind_param("i", $listId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_array();
    $unfinishedCount = $row[0];

    // Determine the new status based on whether there are any unfinished chores
    $newStatus = ($unfinishedCount > 0) ? 0 : 1; // 0 for unfinished, 1 for finished

    // Update the Chores_List status based on the presence of unfinished chores
    $stmt = $conn->prepare("UPDATE Chores_List SET status = ? WHERE list_id = ?");
    $stmt->bind_param("ii", $newStatus, $listId);
    $stmt->execute();

    if ($newStatus == 1) {
        return ['message' => 'Chore status updated successfully, and all chores in the list are finished.'];
    } else {
        return ['message' => 'Chore status updated successfully, but some chores in the list are unfinished.'];
    }
}
?>


<?php
// Assuming connection $conn is already established and updateChoreListStatus function is defined

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action']) && $_POST['action'] == 'updateChoreListStatus' && isset($_POST['listId'])) {
        $listId = $_POST['listId'];
        // Call the function
        $result = updateChoreListStatus($conn, $listId);
        // Echo the result as JSON
        echo json_encode($result);
        exit;
    }
}

// Function updateChoreListStatus remains the same
?>