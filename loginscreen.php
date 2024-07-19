<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Chores Inc | Log in</title>
  <link rel="icon" type="image/x-icon" href="img/logo.png">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="style/style.css">

</head>

<body>
  <!-- import navbar from header.php -->
  <?php require_once('header.php'); ?>

  <div class="container all_style" style="max-width: 500px; max-height: min-content;">
    <h2 class="text-center company_title w-100">Chores Inc.</h2>
    <form action="login_db.php" method="post" id="signInForm" class="form-signin w-100">
      <div class="text-center mb-4">
        <img src="img/logo.png" class="logo img-fluid mx-auto d-block mb-4" alt="Logo">
      </div>
      <h2 class="mb-4 text-center">Sign in to our site</h2>
      <div class="form-group mb-3">
        <input type="email" name="email" id="email" class="form-control" placeholder="Email address" required autofocus>
      </div>
      <div class="form-group mb-3">
        <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
      </div>
      <div class="form-check mb-3">
        <input type="checkbox" class="form-check-input" id="rememberMe">
        <label class="form-check-label" for="rememberMe">Remember me</label>
      </div>
      <div class="col d-flex justify-content-center">
        <button type="submit" id="login-btn" class="btn btn-success btn-lg">Log In</button>
      </div>

      <div class="signup-section">
        <p>New here? <a href="signupscreen.php">Sign up here</a></p>
      </div>
    </form>
  </div>

  <!-- import footer from footer.php -->
  <?php require_once('footer.php'); ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>

</html>