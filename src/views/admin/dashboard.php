<?php
session_start();
require_once __DIR__ . '/../../models/UserModel.php';
require_once __DIR__ . '/../../models/BaranggayModel.php';
require_once __DIR__ . '/../../models/ReportModel.php';
require_once __DIR__ . '/../../controllers/AuthController.php';

require_once __DIR__ . '/../components/sidebar.php';
require_once __DIR__ . '/../components/header.php';

requireSignIn();

$userID = $_SESSION['userID'];
$user = findUserByID($userID);

redirectIfNotAllowed($user["role"], "admin");

if (isset($_POST['logout'])) {
    handleSignOut();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    updateUserRole($_POST['userID'], $_POST['update-role'], $_POST['baranggay'] ?? "none");
}

$allUsers = getAllUsers();
$allBaranggays = getBaranggays();
$allReports = getReports("all");

$userFilter = isset($_GET['filter']) ? $_GET['filter'] : 'all';

if ($userFilter != 'admin' && $userFilter != 'official' && $userFilter != 'user') {
    $userFilter = 'all';
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MapaAyos - Admin Dashboard</title>
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
            renderHeader(
                isAuthenticated(),
                $user ? $user["hasProfilePic"] : false,
                $userID
            );
            ?>

            <div class="cards-grid">
                <div class="card">
                    <div class="card-title">Total Users</div>
                    <div class="card-value"><?= count($allUsers) ?></div>
                </div>
                <div class="card">
                    <div class="card-title">Registered Baranggays</div>
                    <div class="card-value"><?= count($allBaranggays) ?></div>
                </div>
                <div class="card">
                    <div class="card-title">Reports Issued</div>
                    <div class="card-value"><?= count($allReports) ?></div>
                </div>
            </div>

            <div class="users-table">
                <h2>Users</h2>
                <form method="GET" action="" id="filterForm">
                    <select name="filter" id="filterInput" onchange="document.getElementById('filterForm').submit()">
                        <option value="all" <?= $userFilter === 'all' ? 'selected' : '' ?>>All</option>
                        <option value="user" <?= $userFilter === 'user' ? 'selected' : '' ?>>User</option>
                        <option value="admin" <?= $userFilter === 'admin' ? 'selected' : '' ?>>Admin</option>
                        <option value="official" <?= $userFilter === 'official' ? 'selected' : '' ?>>Official</option>
                    </select>
                </form>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Role</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $users = getAllUsersByRole($userFilter);
                        foreach ($users as $user) {
                            $userID = htmlspecialchars($user['id'], ENT_QUOTES, 'UTF-8');
                            echo "<tr class='user-row' id='user-{$userID}' onclick=\"displayUser('{$userID}')\" style=\"cursor: pointer;\">";
                            echo "<td>{$userID}</td>";
                            echo "<td>{$user['firstName']}</td>";
                            echo "<td>{$user['lastName']}</td>";
                            echo "<td>{$user['email']}</td>";
                            echo "<td>{$user['role']}</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="card" id="user-information"></div>
    </div>
    </main>
    </div>
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq"
        crossorigin="anonymous"></script>

    <script type="module" src="/MapaAyos/src/scripts/admin.js"></script>

</body>

</html>