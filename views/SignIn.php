<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MapaAyos - Sign In</title>

    <link rel="stylesheet" href="../assets/css/root.css">
    <link rel="stylesheet" href="../assets/css/main.css">
    <link rel="stylesheet" href="../assets/css/form.css">
</head>

<body>
    <div class="btn-group">
        <a class="btn" href="../index.php">Home</a>
        <a class="btn" href="./SignUp.php">Sign Up</a>
    </div>

    <main>
        <form action="signup.php" method="POST">
            <h1>Sign In</h1>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <br>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <br>
            <button class="btn" type="submit">Sign In</button>
        </form>
    </main>
</body>

</html>