<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Chores Inc | Main</title>
  <link rel="icon" type="image/x-icon" href="img/logo.png">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" type="text/css" href="style/style.css">
</head>

<body>
  <!-- import navbar from header.php -->
  <?php
  require_once('components/header.php');
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


  // Check if user is assigned to a household
  if (!$house_id) {
    require_once('API/add_household.php');
  }
  ?>

  <div class="container all_style">
    <div class="row">
      <div class="col-12">
        <h3 class="company_title">Chores:</h3>
      </div>
    </div>
    <div class="row">
      <div class="col-12">
        <div class="table-responsive bg-light">
          <table class="table text-center align-middle mb-0" id="chores_list_table" style="border-radius: 10px;">
            <thead class="bg-light">
              <tr>
                <th class="text-center">List title</th>
                <th class="text-center">Due date</th>
                <th class="text-center">Responsible user</th>
                <th class="text-center">Status</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $sql = "SELECT cl.list_title, cl.due_date, cl.list_id, cl.status
              FROM Chores_List cl
              JOIN Household h ON cl.house_id = h.house_id
              JOIN Users_partOf_Household uph ON h.house_id = uph.house_id
              WHERE uph.user_id = ?";
              $stmt = $conn->prepare($sql);
              if ($stmt === false) {
                die("Prepare failed: " . $conn->error);
              }

              // Bind parameters
              $stmt->bind_param("i", $user_id);

              // Execute the statement
              $stmt->execute();

              // Get the result
              $results = $stmt->get_result();
              $stmt->close();

              ?>
              <?php while ($row = $results->fetch_assoc()) : ?>
                <tr>
                  <td>
                    <p class="fw-bold mb-1">
                      <a href="chores.php?list_id=<?= $row['list_id']; ?>">
                        <?= $row['list_title']; ?>
                      </a>
                    </p>
                  </td>
                  <td class="text-center">
                    <p class="text-break mb-1"><?= $row['due_date']; ?></p>
                  </td>
                  <td class="text-center">
                    <div class="d-flex flex-column align-items-center justify-content-center">
                      <?php
                      // Get the user's avatar color and name from the database
                      $sql = "SELECT u.first_name, u.last_name, u.avatar_color 
                      FROM users u
                      JOIN chores_List cl ON u.user_id = cl.responsible_user_id
                      WHERE cl.list_id = " . $row['list_id'] . ";";
                      $user_Responsible = $conn->query($sql);
                      if ($user_Responsible && $user_Responsible->num_rows > 0) {
                        $user = $user_Responsible->fetch_assoc();
                      } else {
                        $user = ['first_name' => 'N/A', 'last_name' => 'N/A', 'avatar_color' => 'gray'];
                      }
                      ?>
                      <img src="https://api.dicebear.com/9.x/bottts/svg?baseColor=<?= $user['avatar_color'] ?>&seed= <?= rand() ?>" alt="" style="width: 45px; height: 45px" class="img-fluid rounded-circle mb-2 d-none d-sm-block" />
                      <p class="mb-1"><?= $user['first_name'] . " " . $user['last_name']; ?></p>
                    </div>
                  </td>
                  <td class="text-center">
                    <?php if ($row['status']) : ?>
                      <span class="badge text-wrap text-bg-success" style="height: 20px;">
                        <p class="d-none d-sm-block">Finished</p>
                      </span>
                    <?php else : ?>
                      <span class="badge text-wrap text-bg-danger" style="height: 20px;">
                        <p class="d-none d-sm-block">Not Finished</p>
                      </span>
                    <?php endif; ?>
                    </span>
                  </td>
                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>

      </div>
    </div>
    <div class="text-center">
      <button type="button" class="btn btn-info mt-4" data-bs-toggle="modal" data-bs-target="#newUserModal">
        <i class="fas fa-user-plus"></i> Add user
      </button>
      <button type="button" class="btn btn-info mt-4" data-bs-toggle="modal" data-bs-target="#newChoresListModal">
        <i class="fas fa-list"></i> Add list
      </button>
    </div>
    <div class="text-center">
      <p class="d-inline-flex mt-3">
        <a class="btn btn-info mt-4" data-bs-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
          Show users in my house hold:
        </a>
      </p>
    </div>

    <div id="collapseExample" class="container collapse all_style ">
      <div class="row">
        <div class="col-12">
          <h3 class="company_title">Users in house hold</h3>
<p>(A user can belong to my household, but it doesn’t necessarily mean that I belong to theirs. As a result, there might be task lists from users who don’t belong to my household, but I belong to theirs)</p>
          <?php
          // Get the users that are part of the household from the database and display them in a list
          $userSql = "SELECT u.user_id, u.first_name, u.last_name 
            FROM Users u 
            JOIN Users_partOf_Household uph ON u.user_id = uph.user_id
            WHERE uph.house_id = " . intval($_SESSION['user_id']) . "
            ORDER BY first_name ASC";
          $userResult = $conn->query($userSql);
          if (!$userResult) {
            echo "Error: " . htmlspecialchars($conn->error);
          } else {
            echo '<ul id="userList">';
            while ($user = $userResult->fetch_assoc()) : ?>
              <li><?= htmlspecialchars($user['first_name']) . " " . htmlspecialchars($user['last_name']); ?></li>
            <?php endwhile; ?>
          <?php
            echo '</ul>';
          }
          ?>
        </div>
      </div>
    </div>

  </div>
  <!-- Add user to household modal -->
  </div>
  <div class="modal fade" id="newUserModal" tabindex="-1" aria-labelledby="newUserModalLabel" aria-hidden="true">
    <div class="modal-dialog ">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="newUserModalLabel">New User</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body all_style">
          <form id="newUserForm" action="API/add_user_to_household.php" method="post">
            <div class="mb-3">
              <label for="house_id" class="form-label" hidden></label>
              <input name="house_id" id="house_id" class="form-control" value="<?= $house_id ?>" hidden>
              <div hidden class="alert alert-danger text-center" id="addUserError" role="alert">User already in the household</div>
              <label for="emailInput" class="form-label">Email address</label>
              <input type="email" name="email" id="emailInput" class="form-control" placeholder="Email address" autocomplete="email" required autofocus>
              <div id="suggesstion-box"></div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-success">Add user</button>
            </div>
        </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Add new chores list modal -->
  <div class="modal fade" id="newChoresListModal" tabindex="-1" aria-labelledby="newChoresListModalLabel" aria-hidden="true">
    <div class="modal-dialog ">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="newChoresListModalLabel">New chores list</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body all_style">
          <form id="newChoresListForm" action="API/add_chores_list.php" method="POST">
            <div class="mb-3">
              <!-- Hidden Fields -->
              <input type="hidden" name="res_user_id" id="res_user_id" value="<?= $user_id ?>">
              <input type="hidden" name="cl_house_id" id="cl_house_id" value="<?= $house_id ?>">

              <!-- Responsible User Dropdown -->
              <div class="mb-3">
                <label for="choreListUser" class="form-label">Assigned User</label>
                <?php
                $sql = "SELECT first_name, last_name FROM users WHERE email = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $_SESSION['email']);
                $stmt->execute();
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();
                ?>
                <span class="form-control" style="background-color: #e9ecef; color: #495057; border: 1px solid #ced4da; cursor: not-allowed;"><?= htmlspecialchars($row['first_name']) ?> <?= htmlspecialchars($row['last_name']) ?></span>
                <!-- <input type="text" class="form-control" id="choreDate" name="choreDate" value="<?= date('Y-m-d') ?>" readonly style="background-color: #e9ecef; color: #495057; border: 1px solid #ced4da; cursor: not-allowed;"> -->
              </div>

              <!-- List Title -->
              <div class="mb-3">
                <label for="list_title" class="form-label">List Title</label>
                <input type="text" name="list_title" id="list_title" class="form-control" placeholder="Enter list title" required>
              </div>

              <!-- Due Date -->
              <div class="mb-3">
                <label for="due_date" class="form-label">Due Date</label>
                <input type="date" name="due_date" id="due_date" class="form-control" required>
              </div>

              <!-- Status (Hidden, default to "not finished") -->
              <input type="hidden" name="status" value="not finished">


            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-success">Add list</button>
            </div>
        </div>
        </form>
      </div>
    </div>
  </div>

  <!-- import footer from footer.php -->
  <?php require_once('components/footer.php'); ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!-- jQuery UI -->
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.3/themes/base/jquery-ui.css">
  <script src="https://code.jquery.com/ui/1.13.3/jquery-ui.min.js"></script>

</body>

</html>