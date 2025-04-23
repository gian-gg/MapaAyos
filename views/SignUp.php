<?php
require_once __DIR__ . '/../controllers/SignUpController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userController = new UserController();
    $userController->signUp();
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
    <link rel="stylesheet" href="../assets/css/form.css">
</head>

<body>
    <div class="btn-group">
        <a class="btn" href="../index.php">Home</a>
        <a class="btn" href="./SignIn.php">Sign In</a>
    </div>

    <main>
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
            <button class="btn" type="submit">Sign Up</button>
        </form>
    </main>
</body>

</html>