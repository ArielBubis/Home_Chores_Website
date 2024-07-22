<?php
session_start();
$current_page = basename($_SERVER['PHP_SELF']); // Save current page
require "API/db.php";

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
                    <?php if (isset($_SESSION['userLoggedIn'])) : ?>
                        <?php
                        // Use prepared statements to prevent SQL injection
                        $sql = "SELECT first_name FROM users WHERE email = ?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("s", $_SESSION['email']); 
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $row = $result->fetch_assoc();
                        ?>
                        <span class="navbar-text text-black">Welcome, <?= htmlspecialchars($row['first_name']) ?>!</span>
                        <a id="logoutPage" class="nav-link text-black" href="API/logout.php">Logout</a>
                    <?php else : ?>
                        <a id="signinPage" class="nav-link text-black <?php echo ($current_page == 'log_in.php') ? 'active' : ''; ?>" href="log_in.php">Login</a>
                        <a id="signupPage" class="nav-link text-black <?php echo ($current_page == 'signupscreen.php') ? 'active' : ''; ?>" href="signupscreen.php">Sign up</a>
                    <?php endif; ?>

                </div>
            </div>
        </nav>
    </header>
</body>

</html>