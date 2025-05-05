<?php
session_start();

require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/controllers/AuthController.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MapaAyos</title>

    <link rel="stylesheet" href="./assets/css/root.css">
    <link rel="stylesheet" href="./assets/css/main.css">
    <link rel="stylesheet" href="./assets/css/landing.css">
</head>

<body>
    <div class="card">
        <h1>MapaAyos</h1>
        <div class="btn-group">
            <?php
            if (!isAuthenticated()) {
                echo '<a class="ma-btn" href="./views/signup.php">Sign Up</a>';
                echo '<a class="ma-btn" href="./views/signin.php">Sign In</a>';
            } else {
                echo '<a class="ma-btn" href="./views/user/dashboard.php">Dashboard</a>';
            }
            ?>
        </div>
    </div>

</body>

</html>