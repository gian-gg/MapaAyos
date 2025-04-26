<?php
session_start();

require_once __DIR__ . '/../controllers/AuthController.php';
redirectIfAuthenticated(); // redirect to dashboard if authenticated

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = htmlspecialchars(trim($_POST['firstNameInput']));
    $lastName = htmlspecialchars(trim($_POST['lastNameInput']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = htmlspecialchars(trim($_POST['password']));

    handleSignUp($firstName, $lastName, $email, $password);
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
        <a class="ma-btn" href="./SignIn.php">Sign In</a>
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