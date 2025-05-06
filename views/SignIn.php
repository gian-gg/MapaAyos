<?php
session_start();

require_once __DIR__ . '/../controllers/AuthController.php';
redirectIfAuthenticated(); // redirect to dashboard if authenticated

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    handleSignIn($_POST['email'], $_POST['password']);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MapaAyos - Sign In</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">

    <link rel="stylesheet" href="../assets/css/root.css">
    <link rel="stylesheet" href="../assets/css/main.css">
    <link rel="stylesheet" href="../assets/css/navbar.css">
    <link rel="stylesheet" href="../assets/css/signinup.css">

    <link rel="stylesheet" href="../assets/css/signinup-mobile.css">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
</head>

<body class="signing-body">
    <!-- header | navbar -->
    <header>
        <a href="../index.php">
            <div class="branding">
            <img src="../assets/img/logo.png" alt="MapaAyos">
            <div class="brand-title">
                <h1>MapaAyos</h1>
                <p>nisi commodo laborum</p>
            </div>
            </div>
        </a>

        <!-- Toggle button for mobile -->
        <button class="btn d-md-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar" aria-controls="mobileSidebar" aria-label="Toggle navigation" style="background:none; border:none; font-size: 1.5rem; color: var(--text-primary);">
            <i class="bi bi-three-dots-vertical"></i>
        </button>

        <div class="nav-items d-none d-md-flex">
            <a href="../index.php#how_it_works">How it Works</a>
            <a href="../index.php#features">Features</a>
            <a href="../index.php#about">About</a>
            <a href="../index.php#contact">Contact</a>
        </div>

        <div class="btn-group d-none d-md-flex">
            <?php
                if (!isAuthenticated()) {
                echo '<a class="signin-btn" href="./SignIn.php">Sign In</a>';
                echo '<a class="signup-btn" href="./SignUp.php">Sign Up</a>';
                } else {
                echo '<a class="btn" href="./dashboard/Dashboard.php">Dashboard</a>';
                }
            ?>
        </div>
    </header>

    <!-- Offcanvas sidebar for mobile -->
    <div class="offcanvas offcanvas-start" tabindex="-1" id="mobileSidebar" aria-labelledby="mobileSidebarLabel">
      <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="mobileSidebarLabel">MapaAyos</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
      <div class="offcanvas-body">
        <nav class="nav flex-column">
          <a class="nav-link" href="#how_it_works" data-bs-dismiss="offcanvas">How it Works</a>
          <a class="nav-link" href="#features" data-bs-dismiss="offcanvas">Features</a>
          <a class="nav-link" href="#about" data-bs-dismiss="offcanvas">About</a>
          <a class="nav-link" href="#contact" data-bs-dismiss="offcanvas">Contact</a>
        </nav>
        <div class="mt-3">
          <?php
            if (!isAuthenticated()) {
              echo '<a class="btn btn-outline-primary w-100 mb-2" href="./views/SignIn.php" data-bs-dismiss="offcanvas">Sign In</a>';
              echo '<a class="btn btn-primary w-100" href="./views/SignUp.php" data-bs-dismiss="offcanvas">Sign Up</a>';
            } else {
              echo '<a class="btn btn-primary w-100" href="./views/dashboard/Dashboard.php" data-bs-dismiss="offcanvas">Dashboard</a>';
            }
          ?>
        </div>
      </div>
    </div>


    <main class="signing-main">
        <section class="card">
            <div class="feature-card">
                <img src="../assets/img/feature.png" alt="MapaAyos">
            </div>
            <div class="log-card">
                <h1>Sign In</h1>
                <p>Tuloy po kayo! Please enter your details below.</p>
                
                <form method="POST">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                    <br>
                    <label for="password">Password:</label>
                    <div class="password-container">
                        <input type="password" id="password" name="password" required>
                        <span class="toggle-password" onclick="togglePasswordVisibility()">
                            <i id="toggleIcon" class="bi bi-eye-slash-fill"></i>
                        </span>
                    </div>
                    <br>
                    <button class="btn" type="submit">Sign In</button>
                </form>

                <p>Wala ka pang account? <a href="./SignUp.php">Sign up here</a></p>
            </div>
        </section>
    </main>

    <script src="../assets/js/password.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
</body>

</html>
