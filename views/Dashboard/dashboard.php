<?php
session_start();
require_once __DIR__ . '/../../models/UserModel.php';
require_once __DIR__ . '/../../controllers/AuthController.php';

requireSignIn();

$userID = $_SESSION['userID'];
$user = findUserByID($userID);

if (isset($_POST['logout'])) {
    handleSignOut();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MapaAyos Dashboard</title>

    <link rel="stylesheet" href="../../assets/css/root.css">
    <link rel="stylesheet" href="../../assets/css/dashboard.css">
</head>

<body>
    <div class="dashboard">
        <aside class="sidebar">
            <div class="logo">MapaAyos</div>
            <nav>
                <a href="#" class="nav-item">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                        <polyline points="9 22 9 12 15 12 15 22"></polyline>
                    </svg>
                    Dashboard
                </a>
                <a href="#" class="nav-item">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="3"></circle>
                        <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                    </svg>
                    Settings
                </a>
                <form method="POST">
                    <button type="submit" name="logout">Log Out</button>
                </form>

            </nav>
        </aside>
        <main class="main-content">
            <div class="header">
                <h1>Dashboard</h1>
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