<?php
require 'db.php'; // Adjust the path as necessary
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $input = json_decode(file_get_contents('php://input'), true);
  $choreId = intval($input['choreId']);

  if ($choreId) {
    $stmt = $conn->prepare("DELETE FROM Chores WHERE chore_num = ?");
    if ($stmt) {
      $stmt->bind_param('i', $choreId);

      if ($stmt->execute()) {
        echo json_encode(['success' => 1, 'message' => 'Chore deleted successfully']);
      } else {
        echo json_encode(['success' => 0, 'error' => $stmt->error]);
      }

      $stmt->close();
    } else {
      echo json_encode(['success' => 0, 'error' => $conn->error]);
    }
  } else {
    echo json_encode(['success' => 0, 'error' => 'Invalid chore ID or house ID']);
  }
} else {
  echo json_encode(['success' => 0, 'error' => 'Invalid request method']);
}

$conn->close();

?>