<?php
session_start();

require_once __DIR__ . '/../controllers/AuthController.php';
redirectIfNotAllowed("all", "signup");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    handleSignUp($_POST['firstName'], $_POST['lastName'], $_POST['email'], $_POST['password']);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MapaAyos - Sign Up</title>
    <link rel="shortcut icon" href="/MapaAyos/public/img/favicon.png" type="image/png">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">

    <link rel="stylesheet" href="/MapaAyos/public/css/root.css">
    <link rel="stylesheet" href="/MapaAyos/public/css/main.css">
    <link rel="stylesheet" href="/MapaAyos/public/css/navbar.css">
    <link rel="stylesheet" href="/MapaAyos/public/css/signinup.css">

    <link rel="stylesheet" href="/MapaAyos/public/css/signinup-mobile.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
</head>

<body class="signing-body">
    <!-- header | navbar -->
    <header>
        <a href="/MapaAyos/index.php">
            <div class="branding">
                <img src="/MapaAyos/public/img/logo.png" alt="MapaAyos">
                <div class="brand-title">
                    <h1>MapaAyos</h1>
                    <p>nisi commodo laborum</p>
                </div>
            </div>
        </a>

        <!-- toggle button for mobile -->
        <button class="btn d-md-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar" aria-controls="mobileSidebar" aria-label="Toggle navigation" style="background:none; border:none; font-size: 1.5rem; color: var(--text-primary);">
            <i class="bi bi-three-dots-vertical"></i>
        </button>

        <div class="nav-items d-none d-md-flex">
            <a href="/MapaAyos/index.php#how_it_works">How it Works</a>
            <a href="/MapaAyos/index.php#features">Features</a>
            <a href="/MapaAyos/index.php#about">About</a>
            <a href="/MapaAyos/index.php#contact">Contact</a>
        </div>

        <div class="btn-group d-none d-md-flex">
            <?php
            if (!isAuthenticated()) {
                echo '<a class="signin-btn" href="/MapaAyos/signin">Sign In</a>';
                echo '<a class="signup-btn" href="/MapaAyos/signup">Sign Up</a>';
            } else {
                echo '<a class="btn" href="/MapaAyos/user/dashboard">Dashboard</a>';
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
                    echo '<a class="btn btn-outline-primary w-100 mb-2" href="/MapaAyos/signin" data-bs-dismiss="offcanvas">Sign In</a>';
                    echo '<a class="btn btn-primary w-100" href="/MapaAyos/signup" data-bs-dismiss="offcanvas">Sign Up</a>';
                } else {
                    echo '<a class="btn btn-primary w-100" href="/MapaAyos/user/dashboard" data-bs-dismiss="offcanvas">Dashboard</a>';
                }
                ?>
            </div>
        </div>
    </div>

    <main class="signing-main">
        <section class="card">
            <div class="feature-card">
                <img src="/MapaAyos/public/img/feature.png" alt="MapaAyos">
            </div>

            <div class="log-card">
                <h1>Sign Up</h1>
                <p>Tuloy po kayo! Please enter your details below.</p>

                <form method="POST">
                    <label for="firstName">First Name:</label>
                    <input type="text" id="firstName" name="firstName" required>
                    <br>
                    <label for="lastName">Last Name:</label>
                    <input type="text" id="lastName" name="lastName" required>
                    <br>
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
                    <button class="btn" type="submit">Sign Up</button>
                </form>
                <p>May account ka na? <a href="/MapaAyos/signin">Sign in here</a></p>
            </div>
        </section>
    </main>

    <script src="/MapaAyos/public/js/password.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
</body>

</html>