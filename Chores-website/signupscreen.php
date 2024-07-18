<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chores Inc | Sign up</title>
    <link rel="icon" type="image/x-icon" href="img/logo.png">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="style/style.css">
    <script src="scripts.js"></script>

</head>

<body>
    <!-- import navbar from header.php -->
    <?php require_once('header.php'); ?>

    <div class="container all_style" style="max-width: 500px; max-height: min-content;">
        <h2 class="text-center company_title w-100">Chores Inc.</h2>
        <img src="img/logo.png" class="logo img-fluid mx-auto d-block mb-4" alt="Logo">
        <h2 class="mb-4 text-center">Register to our site</h2>
        <form action="add_user.php" method="post" id="signUpForm" class="form-signin w-100">
            <div class="row mb-3">
                    <div class="col">
                        <input type="email" class="form-control" name="email" id="email" placeholder="Email" required>
                    </div>
                </div>
            <div class="row mb-3">
                <div class="col">
                    <input type="text" class="form-control" name="first_name" id="first-name" placeholder="First name" required>
                </div>
                <div class="col">
                    <input type="text" class="form-control" name="last_name" id="last-name" placeholder="Last name" required>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-12 col-md-6 mb-3 mb-md-0">
                    <input type="password" class="form-control" name="password" id="password"placeholder="Password" required>
                </div>
                <div class="col-12 col-md-6">
                    <input type="password" class="form-control" id="password-confirm"placeholder="Confirm Password" required>
                </div>
            </div>
            <div class="row">
                <div class="col d-flex justify-content-center">
                    <button type="submit" class="btn btn-success btn-lg">Sign Up</button>
                </div>
            </div>
        </form>
    </div>

    <!-- import footer from footer.php -->
    <?php require_once('footer.php'); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</html>