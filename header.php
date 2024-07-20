<?php
session_start(); 
$current_page = basename($_SERVER['PHP_SELF']); // Save current page
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="img/logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="style/style.css">
</head>

<body>
    <header> <!-- navbar -->
        <nav class="navbar bg-light bg-gradient">
            <div class="container-fluid">
                <h3 class="company_title mb-1"><a class="nav-link" href="index.php">Chores Inc.</a></h3>
                <div class="nav nav-underline">
                    <a id="homepage" class="nav-link text-black <?php echo ($current_page == 'index.php') ? 'active' : ''; ?>" href="index.php">Home</a>
                    
                    <?php if (isset($_SESSION['userLoggedIn'])): ?>
                        <!-- <span class="nav-link text-black">Welcome!</span> -->
                        <a action="logout.php" id="logoutPage" class="nav-link text-black" href="logout.php">Logout</a>
                    <?php else: ?>
                        <a id="signupPage" class="nav-link text-black <?php echo ($current_page == 'loginscreen.php') ? 'active' : ''; ?>" href="loginscreen.php">Login</a>
                        <a id="signinPage" class="nav-link text-black <?php echo ($current_page == 'signupscreen.php') ? 'active' : ''; ?>" href="signupscreen.php">Sign up</a>
                    <?php endif; ?>

                    <!-- <a class="nav-link text-black active" href="index.html">Home</a>
            <a class="nav-link text-black" href="loginscreen.html">Login</a>
            <a class="nav-link text-black" href="signupscreen.html">Sign up</a> -->
                </div>
            </div>
        </nav>
    </header>
</body>

</html>