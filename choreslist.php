<?php require("db.php"); ?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Chores Inc | list</title>
  <link rel="icon" type="image/x-icon" href="img/logo.png">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">

  <link rel="stylesheet" type="text/css" href="style/style.css">
</head>

<body>
  <!-- <header>
    <nav class="navbar bg-light bg-gradient">
      <div class="container-fluid">
        <h3 class="company_title mb-1"><a class="nav-link" href="index.html">Chores Inc.</a></h3>
        <div class="nav nav-underline">
          <a class="nav-link text-black" href="index.html">Home</a>
          <a class="nav-link text-black" href="loginscreen.html">Login</a>
          <a class="nav-link text-black" href="signupscreen.html">Sign up</a>
        </div>
      </div>
    </nav>
  </header> -->
  <!-- import navbar from header.php -->
  <?php require_once('header.php'); ?>


  <div class="container all_style">
    <div class="row align-items-center">
      <div class="col=auto">
        <button class="btn btn-outline-secondary mb-2 mr-3" onclick="window.history.back();">Back</button>
      </div>
      <div class="col">
        <h3 class="company_title mb-2">To do list:</h3>
      </div>


    </div>
    <div class="row">
      <div class="col-12">
        <div class="table-responsive bg-light">
          <table class="table text-center align-middle mb-0" style="border-radius: 10px;">
            <thead class="bg-light">
              <tr>
                <th class="text-center">Chore title</th>
                <th class="text-center">Date added</th>
                <th class="text-center">User assigned</th>
                <th class="text-center">Finished?</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $sql = "SELECT Users.first_name, Users.last_name, Responsible_For_List.list_id, Chores.chore_title, Chores.date_added, Chores.finished, Chores.chore_num 
                FROM Users 
                INNER JOIN Responsible_For_List ON Users.user_id = Responsible_For_List.user_id 
                INNER JOIN Chores_List ON Responsible_For_List.list_id = Chores_List.list_id
                INNER JOIN Chores ON Chores_List.list_id = Chores.list_id;
                ";
              $result = $conn->query($sql);
              ?>
              <?php while ($row = $result->fetch_assoc()) : ?>
                <tr>
                  <td>
                    <div class="ms-6 text-center">
                      <p class="fw-bold mb-1"><?= $row['chore_title']; ?></p>
                    </div>
                  </td>
                  <td class="text-center">
                    <p class="text-break"><?= $row['date_added']; ?></p>
                  </td>
                  <td class="text-center">
                    <div class="d-flex flex-column align-items-center justify-content-center">
                      <img src="https://mdbootstrap.com/img/new/avatars/8.jpg" alt="" style="width: 45px; height: 45px" class="img-fluid rounded-circle mb-2 d-none d-sm-block" />
                      <div>
                      <p class="mb-1"><?= $row['first_name'] . " " . $row['last_name']; ?></p>                      </div>
                    </div>
                  </td>
                  <?php if ($row['finished'] == 1) : ?>
                    <td class=""><input type="checkbox" id="chore-checkbox" name="chore-checkbox" class="form-check-input status" data-id="<?= $row['chore_num']; ?>" checked></td>
                  <?php else : ?>
                    <td class=""><input type="checkbox" id="chore-checkbox" name="chore-checkbox" class="form-check-input status" data-id="<?= $row['chore_num']; ?>"></td>
                  <?php endif; ?>
                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- import footer from footer.php -->
  <?php require_once('footer.php'); ?>

  <!-- <footer class="bg-light bg-gradient d-flex flex-wrap justify-content-between align-items-center">
    <p class="col-md-4 mb-0 text-body-secondary">© 2024 Ariel & Narkis, Chores Inc</p>
    <ul class="nav col-md-4 justify-content-end">
      <li class="nav-item"><a href="index.html" class="nav-link px-2 text-body-secondary">Home</a></li>
      <li class="nav-item"><a href="signupscreen.html" class="nav-link px-2 text-body-secondary">Sign Up</a></li>
      <li class="nav-item"><a href="http://www.google.com" class="nav-link px-2 text-body-secondary">Google</a></li>
      <li class="nav-item"><a href="http://www.facebook.com" class="nav-link px-2 text-body-secondary">Facebook</a></li>
    </ul>
  </footer> -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>

</html>