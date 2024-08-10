<?php
session_start();
$current_page = basename($_SERVER['PHP_SELF']); // Save current page
require_once('API/db.php');
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <header> <!-- navbar -->
        <nav class="navbar bg-light bg-gradient">
            <div class="container-fluid">
                <h3 class="company_title mb-1"><a class="nav-link" href="index.php">Chores Inc.</a></h3>
                <div class="nav nav-underline">
                    <a id="homepage" class="nav-link text-black <?php echo ($current_page == 'index.php') ? 'active' : ''; ?>" href="index.php">
                    <i class="fa-solid fa-house"></i> Home
                    </a>
                    <?php if (isset($_SESSION['userLoggedIn']) || ((isset($_COOKIE['email'])) && (isset($_COOKIE['password'])))) : ?>
                        <?php
                        // Check if the user is logged in using cookies and set the session variables
                        if (isset($_COOKIE['email']) && isset($_COOKIE['password'])) {
                            $_SESSION['email'] = $_COOKIE['email'];
                            $_SESSION['userLoggedIn'] = true;

                        }
                        $sql = "SELECT user_id,first_name, avatar_color FROM users WHERE email = ?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("s", $_SESSION['email']);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $row = $result->fetch_assoc();
                        $_SESSION['user_id'] = $row['user_id'];
                        ?>
                        <span class="navbar-text text-black">Welcome, <?= htmlspecialchars($row['first_name']) ?>!</span>
                        <img src="https://api.dicebear.com/9.x/bottts/svg?baseColor=<?= ($row['avatar_color']) ?>&seed= <?= rand() ?>" alt="" style="width: 35px; height: 35px" class="img-fluid rounded-circle mb-2 d-none d-sm-block" />
                        <a id="logoutNav" class="nav-link text-black" href="API/logout.php">
                        Logout <i class="fa-solid fa-right-from-bracket"></i>
                    </a>
                    <?php else : ?>
                        <?php // If the user is not logged in, display the login and signup links
                        $current_page = basename($_SERVER['PHP_SELF']);
                        $excluded_pages = ['log_in.php', 'sign_up.php'];
                        if (!isset($_SESSION['userLoggedIn'])  && !in_array($current_page, $excluded_pages)) {
                            header("Location: log_in.php?message=not_signed_in");
                            exit;
                        }
                        ?>
                        <a id="signinPage" class="nav-link text-black <?php echo ($current_page == 'log_in.php') ? 'active' : ''; ?>" href="log_in.php">
                        <i class="fa-solid fa-right-to-bracket"></i> Login
                    </a>
                        <a id="signupPage" class="nav-link text-black <?php echo ($current_page == 'sign_up.php') ? 'active' : ''; ?>" href="sign_up.php">
                        <i class="fa-solid fa-address-card"></i> Sign up</a>
                    <?php endif; ?>
                </div>
            </div>
        </nav>
    </header>
</body>
<?php require_once('components/LogInModals.php'); ?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="scripts.js"></script>

</html>