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
    <link rel="shortcut icon" href="/public/img/favicon.png" type="image/png">

   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">

    <link rel="stylesheet" href="/MapaAyos/public/css/root.css">
    <link rel="stylesheet" href="/MapaAyos/public/css/main.css">
    <link rel="stylesheet" href="/MapaAyos/public/css/navbar.css">
    <link rel="stylesheet" href="/MapaAyos/public/css/signinup.css">

    <link rel="stylesheet" href="/MapaAyos/public/css/signinup-mobile.css">
    <link rel="stylesheet" href="/MapaAyos/public/css/navbar-mobile.css">

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
                    <p>Map your concerns, track the change</p>
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
            <div class="image-wrapper">
                <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 60 60" fill="none">
                <path fill-rule="evenodd" clip-rule="evenodd"
                    d="M59.9111 59.9562H37.6599C32.7331 59.9562 27.7296 60.2851 22.9007 59.141C18.2019 58.0277 13.6719 55.7207 10.0894 52.4725C6.79413 49.4847 4.14302 45.9267 2.3839 41.8321C-3.19568 28.8448 1.30984 13.4452 12.8614 5.3385C16.4727 2.80413 20.6088 1.25205 24.9235 0.445607C28.2092 -0.168513 31.7526 -0.132714 35.0377 0.459613C38.2943 1.0467 41.5181 2.07782 44.4027 3.72299C47.7762 5.64705 50.8164 8.08998 53.258 11.1261C56.7043 15.4116 58.8877 20.5156 59.6546 25.9549C60.2319 30.0494 59.9111 34.3321 59.9111 38.462V59.9562ZM30 20.5989C24.8095 20.5989 20.5955 24.8114 20.5955 30C20.5955 35.1885 24.8095 39.401 30 39.401C35.1904 39.401 39.4044 35.1885 39.4044 30C39.4044 24.8114 35.1904 20.5989 30 20.5989Z"
                    fill="#F6F8F9" />
                </svg>
                <img src="/MapaAyos/public/img/feature.png" alt="MapaAyos">
            </div>
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
                    <?php
                    if (isset($_GET['error'])) {
                        echo '<div class="text-center message error-message">' . htmlspecialchars($_GET['error']) . '</div>';
                    }
                    if (isset($_GET['success'])) {
                        echo '<div class="text-center message success-message">' . htmlspecialchars($_GET['success']) . '</div>';
                    }
                    ?>
                    <button class="btn" type="submit">Sign Up</button>
                </form>
                <p>May account ka na? <a href="/signin">Sign in here</a></p>
            </div>
        </section>
    </main>

    <script src="/public/js/password.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
</body>

</html>
