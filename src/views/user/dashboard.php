<?php
session_start();
require_once __DIR__ . '/../../models/UserModel.php';
require_once __DIR__ . '/../../controllers/AuthController.php';

requireSignIn();

$userID = $_SESSION['userID'];
$user = findUserByID($userID);

redirectIfNotAllowed($user["role"], "user");

if (isset($_POST['logout'])) {
    handleSignOut();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MapaAyos - User Dashboard</title>
    <link rel="shortcut icon" href="/MapaAyos/public/img/favicon.png" type="image/png">

    <!-- Project CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">

    <link rel="stylesheet" href="/MapaAyos/public/css/root.css">
    <link rel="stylesheet" href="/MapaAyos/public/css/main.css">
    <link rel="stylesheet" href="/MapaAyos/public/css/dashboard.css">
    <link rel="stylesheet" href="/MapaAyos/public/css/sidebar.css">
    <link rel="stylesheet" href="/MapaAyos/public/css/header.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
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
                    <a href="/MapaAyos/user/dashboard" class="nav-item<?php if ($_SERVER['REQUEST_URI'] === '/MapaAyos/user/dashboard') echo ' active'; ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none">
                            <path d="M5 1H1.66667C1.29848 1 1 1.29848 1 1.66667V6.33333C1 6.70152 1.29848 7 1.66667 7H5C5.36819 7 5.66667 6.70152 5.66667 6.33333V1.66667C5.66667 1.29848 5.36819 1 5 1Z" stroke="#4F5051" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M12.3333 1H9C8.63181 1 8.33333 1.29848 8.33333 1.66667V3.66667C8.33333 4.03486 8.63181 4.33333 9 4.33333H12.3333C12.7015 4.33333 13 4.03486 13 3.66667V1.66667C13 1.29848 12.7015 1 12.3333 1Z" stroke="#4F5051" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M12.3333 7H9C8.63181 7 8.33333 7.29848 8.33333 7.66667V12.3333C8.33333 12.7015 8.63181 13 9 13H12.3333C12.7015 13 13 12.7015 13 12.3333V7.66667C13 7.29848 12.7015 7 12.3333 7Z" stroke="#4F5051" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M5 9.66667H1.66667C1.29848 9.66667 1 9.96514 1 10.3333V12.3333C1 12.7015 1.29848 13 1.66667 13H5C5.36819 13 5.66667 12.7015 5.66667 12.3333V10.3333C5.66667 9.96514 5.36819 9.66667 5 9.66667Z" stroke="#4F5051" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <span class="nav-text">Dashboard</span>
                        <?php if ($_SERVER['REQUEST_URI'] === '/MapaAyos/user/dashboard') : ?>
                            <i class="bi bi-chevron-right"></i>
                        <?php endif; ?>
                    </a>
                    <a href="/MapaAyos/user/mapa" class="nav-item<?php if ($_SERVER['REQUEST_URI'] === '/MapaAyos/user/mapa') echo ' active'; ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                            <path d="M10.3333 3.01896C10.092 3.01896 9.85391 2.96128 9.638 2.85049L6.362 1.16847C6.14609 1.05768 5.90803 1 5.66667 1M10.3333 3.01896C10.5747 3.01896 10.8128 2.96128 11.0287 2.85049L13.8746 1.3889C13.9932 1.32801 14.1251 1.2993 14.2576 1.3055C14.3901 1.31169 14.5189 1.35259 14.6317 1.42431C14.7445 1.49603 14.8375 1.59617 14.902 1.71523C14.9665 1.83429 15.0002 1.9683 15 2.10452V12.2989C14.9999 12.4472 14.9596 12.5925 14.8837 12.7186C14.8077 12.8447 14.6991 12.9466 14.5699 13.0129L11.0287 14.8315C10.8128 14.9423 10.5747 15 10.3333 15M10.3333 3.01896V15M10.3333 15C10.092 15 9.85391 14.9423 9.638 14.8315L6.362 13.1495C6.14609 13.0387 5.90803 12.981 5.66667 12.981M5.66667 12.981C5.4253 12.981 5.18725 13.0387 4.97133 13.1495L2.12545 14.6111C2.00672 14.672 1.87479 14.7007 1.7422 14.6945C1.60962 14.6883 1.48079 14.6473 1.36798 14.5755C1.25517 14.5037 1.16214 14.4034 1.09773 14.2842C1.03332 14.1651 0.999675 14.031 1 13.8947V3.70109C1.00008 3.55281 1.04036 3.40747 1.11632 3.28136C1.19229 3.15525 1.30094 3.05335 1.43011 2.98707L4.97133 1.16847C5.18725 1.05768 5.4253 1 5.66667 1M5.66667 12.981V1" stroke="#4F5051" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <span class="nav-text">Mapa</span>
                        <?php if ($_SERVER['REQUEST_URI'] === '/MapaAyos/user/mapa') : ?>
                            <i class="bi bi-chevron-right"></i>
                        <?php endif; ?>
                    </a>
                    <a href="/MapaAyos/user/leaderboard" class="nav-item">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                            <path d="M3.8 5.9H2.75C2.28587 5.9 1.84075 5.71563 1.51256 5.38744C1.18437 5.05925 1 4.61413 1 4.15C1 3.68587 1.18437 3.24075 1.51256 2.91256C1.84075 2.58437 2.28587 2.4 2.75 2.4H3.8M3.8 5.9V1H12.2V5.9M3.8 5.9C3.8 7.01391 4.2425 8.0822 5.03015 8.86985C5.8178 9.6575 6.88609 10.1 8 10.1C9.11391 10.1 10.1822 9.6575 10.9698 8.86985C11.7575 8.0822 12.2 7.01391 12.2 5.9M12.2 5.9H13.25C13.7141 5.9 14.1592 5.71563 14.4874 5.38744C14.8156 5.05925 15 4.61413 15 4.15C15 3.68587 14.8156 3.24075 14.4874 2.91256C14.1592 2.58437 13.7141 2.4 13.25 2.4H12.2M2.4 15H13.6M6.6 9.862V11.5C6.6 11.885 6.271 12.186 5.921 12.347C5.095 12.725 4.5 13.768 4.5 15M9.4 9.862V11.5C9.4 11.885 9.729 12.186 10.079 12.347C10.905 12.725 11.5 13.768 11.5 15" stroke="#4F5051" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <span class="nav-text">Leaderboard</span>
                        <?php if ($_SERVER['REQUEST_URI'] === '/MapaAyos/user/leaderboard') : ?>
                            <i class="bi bi-chevron-right"></i>
                        <?php endif; ?>
                    </a>
                    <p class="nav-text">My Activity</p>
                    <a href="/MapaAyos/user/user-reports" class="nav-item">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-post" viewBox="0 0 16 16">
                            <path d="M4 3.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 .5.5v8a.5.5 0 0 1-.5.5h-7a.5.5 0 0 1-.5-.5z" />
                            <path d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2zm10-1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1" />
                        </svg>
                        <span class="nav-text">Ulat Tracker</span>
                        <?php if ($_SERVER['REQUEST_URI'] === '/MapaAyos/user/user-reports') : ?>
                            <i class="bi bi-chevron-right"></i>
                        <?php endif; ?>
                    </a>
                    <a href="/MapaAyos/user/history" class="nav-item">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clock-history" viewBox="0 0 16 16">
                            <path d="M8.515 1.019A7 7 0 0 0 8 1V0a8 8 0 0 1 .589.022zm2.004.45a7 7 0 0 0-.985-.299l.219-.976q.576.129 1.126.342zm1.37.71a7 7 0 0 0-.439-.27l.493-.87a8 8 0 0 1 .979.654l-.615.789a7 7 0 0 0-.418-.302zm1.834 1.79a7 7 0 0 0-.653-.796l.724-.69q.406.429.747.91zm.744 1.352a7 7 0 0 0-.214-.468l.893-.45a8 8 0 0 1 .45 1.088l-.95.313a7 7 0 0 0-.179-.483m.53 2.507a7 7 0 0 0-.1-1.025l.985-.17q.1.58.116 1.17zm-.131 1.538q.05-.254.081-.51l.993.123a8 8 0 0 1-.23 1.155l-.964-.267q.069-.247.12-.501m-.952 2.379q.276-.436.486-.908l.914.405q-.24.54-.555 1.038zm-.964 1.205q.183-.183.35-.378l.758.653a8 8 0 0 1-.401.432z" />
                            <path d="M8 1a7 7 0 1 0 4.95 11.95l.707.707A8.001 8.001 0 1 1 8 0z" />
                            <path d="M7.5 3a.5.5 0 0 1 .5.5v5.21l3.248 1.856a.5.5 0 0 1-.496.868l-3.5-2A.5.5 0 0 1 7 9V3.5a.5.5 0 0 1 .5-.5" />
                        </svg>
                        <span class="nav-text">History</span>
                        <?php if ($_SERVER['REQUEST_URI'] === '/MapaAyos/user/history') : ?>
                            <i class="bi bi-chevron-right"></i>
                        <?php endif; ?>
                    </a>
                </div>
                <div class="nav-bottom">
                    <a href="/MapaAyos/user/settings" class="nav-item<?php if ($_SERVER['REQUEST_URI'] === '/MapaAyos/user/settings') echo ' active'; ?>" style="font-size: 1.1rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"></path>
                            <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                        </svg>
                        <span class="nav-text">Settings</span>
                        <?php if ($_SERVER['REQUEST_URI'] === '/MapaAyos/user/settings') : ?>
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
            $pageTitle = 'Dashboard';
            require_once __DIR__ . '/../partials/_header.php';
            ?>
            <div class="dashboard-container">
                <div class="cards-grid">
                    <div class="card">
                        <div class="card-title">Your Reports</div>
                        <div class="card-value">
                            000
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-title">Pending Issues</div>
                        <div class="card-value">
                            000
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>

    <script src="/MapaAyos/public/js/sidebar.js"></script>
</body>

</html>