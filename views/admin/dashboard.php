<?php
session_start();
require_once __DIR__ . '/../../models/UserModel.php';
require_once __DIR__ . '/../../controllers/AuthController.php';

requireSignIn();

$userID = $_SESSION['userID'];
$user = findUserByID($userID);

redirectIfNotAllowed($user["role"], "admin");

if (isset($_POST['logout'])) {
    handleSignOut();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MapaAyos Admin Dashboard</title>

    <!-- Project CSS -->
    <link rel="stylesheet" href="/MapaAyos/assets/css/root.css">
    <link rel="stylesheet" href="/MapaAyos/assets/css/main.css">
    <link rel="stylesheet" href="/MapaAyos/assets/css/dashboard.css">
</head>

<body>
    <div class="dashboard">
        <aside class="sidebar">
            <div class="logo">MapaAyos</div>
            <nav>
                <div>
                    <a href="/MapaAyos/admin/dashboard" class="nav-item">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                            <polyline points="9 22 9 12 15 12 15 22"></polyline>
                        </svg>
                        Dashboard
                    </a>
                </div>
                <form method="POST">
                    <button type="submit" name="logout" class="ma-btn">Log Out</button>
                </form>

            </nav>
        </aside>
        <main class="main-content">
            <div class="header">
                <h1>Admin Dashboard</h1>
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
                <div class="card">
                    <div class="card-title">Total Users</div>
                    <div class="card-value">1,234</div>
                </div>
                <div class="card">
                    <div class="card-title">Active Reports</div>
                    <div class="card-value">56</div>
                </div>
                <div class="card">
                    <div class="card-title">Resolved Issues</div>
                    <div class="card-value">89%</div>
                </div>
            </div>
        </main>
    </div>
</body>

</html>