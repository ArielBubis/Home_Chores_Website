
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Chores Inc | list</title>
  <link rel="icon" type="image/x-icon" href="img/logo.png">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

  <link rel="stylesheet" type="text/css" href="style/style.css">
</head>

<body>
  <!-- import navbar from header.php -->
  <?php 
  require_once('components/header.php');
  // Get the list_id from URL query parameters
  $list_id = isset($_GET['list_id']) ? intval($_GET['list_id']) : 0;
  $_SESSION['list_id'] = $list_id; // Store the list_id in session
  // Get the list name from the database
  $listNameSql = "SELECT list_title FROM Chores_List WHERE list_id = $list_id";
  $listNameResult = $conn->query($listNameSql);
  $listName = $listNameResult->fetch_assoc()['list_title'];
  // Store the list name in session
  $_SESSION['list_title'] = $listName;

  $user_id = $_SESSION['user_id'];
  // Query to find the user by email
  $sql = "SELECT house_id FROM household WHERE responsible_user_id = ?;";
  $stmt = $conn->prepare($sql);
  $stmt = $conn->prepare($sql);
  if ($stmt === false) {
    die("Prepare failed: " . $conn->error);
  }

  $stmt->bind_param("i", $user_id);
  $stmt->execute();
  $stmt->bind_result($house_id);
  $stmt->fetch();
  $stmt->close();
  ?>


  <div class="container all_style">
    <div class="row align-items-center">
      <div class="col=auto">
        <button class="btn btn-outline-secondary mb-2 mr-3" value=<?= $_SESSION['list_id'] ?> id="choresBackButton">
        <i class="fa-solid fa-arrow-left"></i> Back
      </button>
        <button type="button" class="btn btn-primary float-end mb-2 mr-3" data-bs-toggle="modal" data-bs-target="#newChoreModal">
        <i class="fa-solid fa-square-plus"></i> Add chore</button>
      </div>
      <div class="col">
        <h3 class="company_title mb-2">To do list:</h3>
      </div>

    </div>
    <div class="row">
      <div class="col-12">
        <div class="table-responsive bg-light">
          <table class="table text-center align-middle mb-0"  style="border-radius: 10px;">
            <thead class="bg-light">
              <tr>
                <th class="text-center">Chore title</th>
                <th class="text-center">Date added</th>
                <th class="text-center">User assigned</th>
                <th class="text-center">Finished?</th>
                <th class="text-center">Delete Chore</th>
              </tr>
            </thead>
            <tbody id="choresContainer">
              <?php
              // Get the chores from the database for the selected list 
              $sql = "SELECT 
                  Users.user_id, Users.first_name, Users.last_name, Users.avatar_color, Chores_List.list_id, Chores.chore_title, Chores.date_added, Chores.finished, Chores.chore_num
                  FROM 
                      Users
                  INNER JOIN Chores ON Users.user_id = Chores.user_id
                  INNER JOIN Chores_List ON Chores.list_id = Chores_List.list_id
                  WHERE Chores.list_id = $list_id;";
              $result = $conn->query($sql);
              ?>
              <?php while ($row = $result->fetch_assoc()) : //loop through the chores and display them in a table row?>
                <tr class = "chore-item">
                  <td>
                    <div class="ms-6 text-center">
                    <input name="house_id" id="house_id" class="form-control" value="<?= $house_id ?>" hidden>
                      <p class="fw-bold mb-1"><?= htmlspecialchars($row['chore_title']); ?></p>
                    </div>
                  </td>
                  <td class="text-center">
                    <p class="text-break"><?= htmlspecialchars($row['date_added']); ?></p>
                  </td>
                  <td class="text-center">
                    <div class="d-flex flex-column align-items-center justify-content-center">
                      <img src="https://api.dicebear.com/9.x/bottts/svg?baseColor=<?= ($row['avatar_color']) ?>&seed= <?= rand() ?>" alt="" style="width: 45px; height: 45px" class="img-fluid rounded-circle mb-2 d-none d-sm-block" />
                      <div>
                        <p class="mb-1"><?= htmlspecialchars($row['first_name']) . " " . htmlspecialchars($row['last_name']); ?></p>
                      </div>
                    </div>
                  </td>
                  <?php if ($row['finished'] == 1) : ?>
                    <td><input type="checkbox" name="chore-checkbox-<?= $row['chore_num']; ?>" class="form-check-input status" id="<?= $row['chore_num']; ?>" checked></td>
                  <?php else : ?>
                    <td><input type="checkbox" name="chore-checkbox-<?= $row['chore_num']; ?>" class="form-check-input status" id="<?= $row['chore_num']; ?>"></td>
                  <?php endif; ?>
                  <td>
                    <button class="btn btn-danger delete-chore" value="<?= $row['chore_num']; ?>">Delete</button>
                  </td>
                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

<!-- Add New Chore Modal -->
<div class="modal fade" id="newChoreModal" tabindex="-1" aria-labelledby="newChoreModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="newChoreModalLabel">Add New Chore</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="newChoreForm" action="API/add_chore.php" method="POST">
          <div class="mb-3">
            <input hidden type="text" class="form-control" id="listId" name="listId" value="<?= htmlspecialchars($_SESSION['list_id']); ?>">
            <p> List title: <br>
            <h3><?= htmlspecialchars($_SESSION['list_title']); ?></h3>
            </p>
          </div>
          <div class="mb-3">
            <label for="choreTitle" class="form-label">Chore Title</label>
            <input type="text" class="form-control" id="choreTitle" name="choreTitle" required>
          </div>
          <div class="mb-3">
            <label for="choreUser" class="form-label">Assign User</label>
            <select class="form-select" id="choreUser" name="choreUser" required>
              <option value="">Select a user</option>
              <?php
              // Get the users from the database and display them in a dropdown
              echo $_SESSION['user_id'];
              $userSql = "SELECT u.user_id, u.first_name, u.last_name 
                          FROM Users u 
                          JOIN Users_partOf_Household uph ON u.user_id = uph.user_id
                          WHERE uph.house_id = " . intval($_SESSION['user_id']) . "
                          ORDER BY first_name ASC";
              $userResult = $conn->query($userSql);
              if (!$userResult) {
                echo "Error: " . htmlspecialchars($conn->error);
              }
              while ($user = $userResult->fetch_assoc()) : ?>
                <option value="<?= htmlspecialchars($user['user_id']); ?>"><?= htmlspecialchars($user['first_name']) . " " . htmlspecialchars($user['last_name']); ?></option>
              <?php endwhile; ?>
            </select>
          </div>
          <div class="mb-3">
            <label for="choreDate" class="form-label">Date Added</label>
            <input type="text" class="form-control" id="choreDate" name="choreDate" value="<?= date('Y-m-d') ?>" readonly style="background-color: #e9ecef; color: #495057; border: 1px solid #ced4da; cursor: not-allowed;">
          </div>
      </div>
      <div class="modal-footer">
        <button type="submit" id="addChore" class="btn btn-primary">Save</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
      </div>
      </form>
    </div>
  </div>
</div>

<!-- import footer from footer.php -->
  <?php require_once('components/footer.php'); ?>

  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <!-- <script src="scripts.js"></script> -->

</body>

</html>