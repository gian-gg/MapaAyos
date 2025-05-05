<?php
session_start();

require_once __DIR__ . '/../controllers/AuthController.php';
redirectIfNotAllowed("all", "signup");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    handleSignUp($_POST['firstNameInput'], $_POST['lastNameInput'], $_POST['email'], $_POST['password']);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MapaAyos - Sign Up</title>

    <link rel="stylesheet" href="../assets/css/root.css">
    <link rel="stylesheet" href="../assets/css/main.css">
    <link rel="stylesheet" href="../assets/css/landing.css">
</head>

<body>
    <div class="btn-group">
        <a class="ma-btn" href="../index.php">Home</a>
        <a class="ma-btn" href="./signin.php">Sign In</a>
    </div>

    <div class="card">
        <form method="POST">
            <h1>Sign Up</h1>
            <label for="firstNameInput">First Name:</label>
            <input type="text" id="firstNameInput" name="firstNameInput" required>
            <br>
            <label for="lastNameInput">Last Name:</label>
            <input type="text" id="lastNameInput" name="lastNameInput" required>
            <br>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <br>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <br>
            <button class="ma-btn" type="submit">Sign Up</button>
        </form>
    </div>
</body>

</html>