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
    <title>MapaAyos Officials - Reports</title>

    <!-- Project CSS -->
    <link rel="stylesheet" href="/MapaAyos/public/css/root.css">
    <link rel="stylesheet" href="/MapaAyos/public/css/main.css">
    <link rel="stylesheet" href="/MapaAyos/public/css/dashboard.css">
</head>

<body>
    <div class="dashboard">
        <aside class="sidebar">
            <div class="logo">MapaAyos</div>
            <nav>
                <div>
                    <a href="/MapaAyos/official/dashboard" class="nav-item">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                            <polyline points="9 22 9 12 15 12 15 22"></polyline>
                        </svg>
                        Dashboard
                    </a>
                    <a href="/MapaAyos/official/reports" class="nav-item">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="8" y1="6" x2="21" y2="6"></line>
                            <line x1="8" y1="12" x2="21" y2="12"></line>
                            <line x1="8" y1="18" x2="21" y2="18"></line>
                            <circle cx="4" cy="6" r="1.5"></circle>
                            <circle cx="4" cy="12" r="1.5"></circle>
                            <circle cx="4" cy="18" r="1.5"></circle>
                        </svg>
                        Reports
                    </a>
                </div>
                <form method="POST">
                    <button type="submit" name="logout" class="ma-btn">Log Out</button>
                </form>

            </nav>
        </aside>
        <main class="main-content">
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
        </main>
    </div>
</body>

</html>