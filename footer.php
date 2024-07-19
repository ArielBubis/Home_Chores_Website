<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <!-- define curret page vairable to find what page we on -->
    <?php $current_page = basename($_SERVER['PHP_SELF']);?>

    <!-- footer -->
<footer class="bg-light bg-gradient d-flex flex-wrap justify-content-between align-items-center">
    <p class="col-md-4 mb-0 text-body-secondary">© 2024 Ariel & Narkis, Chores Inc</p>
    <ul class="nav col-md-4 justify-content-end">
      <li class="nav-item"><a href="index.php" class="nav-link px-2 text-body-secondary">Home</a></li>
      <li class="nav-item"><a href="signupscreen.php" class="nav-link px-2 text-body-secondary">Sign Up</a></li>
      <li class="nav-item"><a href="http://www.google.com" class="nav-link px-2 text-body-secondary">Google</a></li>
      <li class="nav-item"><a href="http://www.facebook.com" class="nav-link px-2 text-body-secondary">Facebook</a>
      </li>
    </ul>
  </footer>
</body>
</html>