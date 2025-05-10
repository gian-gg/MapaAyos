<?php
session_start();
require_once __DIR__ . '/../../models/UserModel.php';
require_once __DIR__ . '/../../controllers/AuthController.php';

requireSignIn();

$userID = $_SESSION['userID'];
$user = findUserByID($userID);

redirectIfNotAllowed($user["role"], "official");

if (isset($_POST['logout'])) {
    handleSignOut();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MapaAyos - Officials Reports</title>
    <link rel="shortcut icon" href="/MapaAyos/public/img/favicon.png" type="image/png">

    <!-- Project CSS -->
    <link rel="stylesheet" href="/MapaAyos/public/css/root.css">
    <link rel="stylesheet" href="/MapaAyos/public/css/main.css">
    <link rel="stylesheet" href="/MapaAyos/public/css/dashboard.css">
    <link rel="stylesheet" href="/MapaAyos/public/css/sidebar.css">
    <link rel="stylesheet" href="/MapaAyos/public/css/header.css">
</head>

<body>
    <div class="dashboard">
        <aside class="sidebar">
            <a href="/MapaAyos/index.php">
                <div class="branding">
                    <img src="/MapaAyos/public/img/brand-logo.png" alt="MapaAyos" width="34" height="34" style="border-radius: 5px;">
                    <div class="brand-title">
                        <h1>MapaAyos</h1>
                        <p>nisi commodo laborum</p>
                    </div>
                </div>
            </a>

            <nav>
                <div>
                    <p class="nav-text">General</p>
                    <a href="/MapaAyos/official/dashboard" class="nav-item<?php if ($_SERVER['REQUEST_URI'] === '/MapaAyos/official/dashboard') echo ' active'; ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none">
                            <path d="M5 1H1.66667C1.29848 1 1 1.29848 1 1.66667V6.33333C1 6.70152 1.29848 7 1.66667 7H5C5.36819 7 5.66667 6.70152 5.66667 6.33333V1.66667C5.66667 1.29848 5.36819 1 5 1Z" stroke="#4F5051" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M12.3333 1H9C8.63181 1 8.33333 1.29848 8.33333 1.66667V3.66667C8.33333 4.03486 8.63181 4.33333 9 4.33333H12.3333C12.7015 4.33333 13 4.03486 13 3.66667V1.66667C13 1.29848 12.7015 1 12.3333 1Z" stroke="#4F5051" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M12.3333 7H9C8.63181 7 8.33333 7.29848 8.33333 7.66667V12.3333C8.33333 12.7015 8.63181 13 9 13H12.3333C12.7015 13 13 12.7015 13 12.3333V7.66667C13 7.29848 12.7015 7 12.3333 7Z" stroke="#4F5051" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M5 9.66667H1.66667C1.29848 9.66667 1 9.96514 1 10.3333V12.3333C1 12.7015 1.29848 13 1.66667 13H5C5.36819 13 5.66667 12.7015 5.66667 12.3333V10.3333C5.66667 9.96514 5.36819 9.66667 5 9.66667Z" stroke="#4F5051" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <span class="nav-text">Dashboard</span>
                        <?php if ($_SERVER['REQUEST_URI'] === '/MapaAyos/official/dashboard') : ?>
                            <i class="bi bi-chevron-right"></i>
                        <?php endif; ?>
                    </a>
                    <a href="/MapaAyos/official/reports" class="nav-item<?php if ($_SERVER['REQUEST_URI'] === '/MapaAyos/official/reports') echo ' active'; ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-post" viewBox="0 0 16 16">
                            <path d="M4 3.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 .5.5v8a.5.5 0 0 1-.5.5h-7a.5.5 0 0 1-.5-.5z" />
                            <path d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2zm10-1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1" />
                        </svg>
                        <span class="nav-text">Reports</span>
                        <?php if ($_SERVER['REQUEST_URI'] === '/MapaAyos/official/reports') : ?>
                            <i class="bi bi-chevron-right"></i>
                        <?php endif; ?>
                    </a>
                </div>
                <div class="nav-bottom">
                    <a href="/MapaAyos/official/settings" class="nav-item<?php if ($_SERVER['REQUEST_URI'] === '/MapaAyos/official/settings') echo ' active'; ?>" style="font-size: 1.1rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"></path>
                            <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                        </svg>
                        <span class="nav-text">Settings</span>
                        <?php if ($_SERVER['REQUEST_URI'] === '/MapaAyos/official/settings') : ?>
                            <i class="bi bi-chevron-right"></i>
                        <?php endif; ?>
                    </a>
                    <div style="margin-top: 1rem;">
                        <form method="POST">
                            <button type="submit" name="logout" class="ma-btn">Log Out</button>
                        </form>
                    </div>
                </div>
            </nav>
        </aside>
        <main class="main-content">
            <?php
            $pageTitle = 'Reports';
            require_once __DIR__ . '/../partials/_header.php';
            ?>
            <div class="reports-container">
                <div class="header">
                    <h1>Reports</h1>
                    <div class="user-info">
                        <?php
                        if ($user) {
                            echo "<p>Welcome, " . htmlspecialchars($user['firstName']) . " " . htmlspecialchars($user['lastName']) . "</p>";
                        } else {
                            echo "<p>User not found.</p>";
                        }
                        ?>
                    </div>
                </div>
                <div class="cards-grid">

                </div>
            </div>
        </main>
    </div>
</body>

</html>