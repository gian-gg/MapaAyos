<?php
session_start();
require_once __DIR__ . '/../../models/UserModel.php';
require_once __DIR__ . '/../../controllers/AuthController.php';

require_once __DIR__ . '/../components/sidebar.php';

requireSignIn();

$userID = $_SESSION['userID'] ?? null;
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
    <title>MapaAyos - Officials Dashboard</title>
    <link rel="shortcut icon" href="/MapaAyos/public/img/favicon.png" type="image/png">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" />

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <!-- Project CSS -->
    <link rel="stylesheet" href="/MapaAyos/public/css/root.css">
    <link rel="stylesheet" href="/MapaAyos/public/css/main.css">
    <link rel="stylesheet" href="/MapaAyos/public/css/dashboard.css">
    <link rel="stylesheet" href="/MapaAyos/public/css/mapa-init.css">
    <link rel="stylesheet" href="/MapaAyos/public/css/sidebar.css">
    <link rel="stylesheet" href="/MapaAyos/public/css/header.css">
</head>

<body>
    <div class="dashboard">
        <?php
        renderSideBar(
            $user ? $user["role"] : "null",
            "mapa",
            isAuthenticated()
        )
        ?>
        <main class="main-content">
            <?php
            $pageTitle = 'Official Dashboard';
            ?>
            <div class="dashboard-container">
                <div class="header">
                    <h1>Officials Dashboard</h1>
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
                        <div class="card-title">Assigned Reports</div>
                        <div class="card-value">45</div>
                    </div>
                    <div class="card">
                        <div class="card-title">Pending Actions</div>
                        <div class="card-value">12</div>
                    </div>
                    <div class="card">
                        <div class="card-title">Resolved by You</div>
                        <div class="card-value">78%</div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>

</html>