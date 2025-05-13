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
    <link rel="shortcut icon" href="/public/img/favicon.png" type="image/png">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" />

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <!-- Project CSS -->
    <link rel="stylesheet" href="/public/css/root.css">
    <link rel="stylesheet" href="/public/css/main.css">
    <link rel="stylesheet" href="/public/css/dashboard.css">
    <link rel="stylesheet" href="/public/css/mapa-init.css">
    <link rel="stylesheet" href="/public/css/sidebar.css">
    <link rel="stylesheet" href="/public/css/header.css">
</head>

<body>
    <div class="dashboard">
        <?php
        renderSideBar(
            $user ? $user["role"] : "null",
            "dashboard",
            isAuthenticated()
        )
        ?>
        <main class="main-content">
            <?php
            renderHeader(
                $user ?? null
            );
            ?>

            <div class="cards-grid">
                <div class="card">
                    <div class="card-title">Total Users</div>
                    <div class="card-value"><?= count($allUsers) ?></div>
                    <div class="card-trend">
                        <i class="bi bi-people-fill"></i>
                    </div>
                </div>
                <div class="card">
                    <div class="card-title">Registered Baranggays</div>
                    <div class="card-value"><?= count($allBaranggays) ?></div>
                    <div class="card-trend">
                        <i class="bi bi-buildings-fill"></i>
                    </div>
                </div>
                <div class="card">
                    <div class="card-title">Reports Issued</div>
                    <div class="card-value"><?= count($allReports) ?></div>
                    <div class="card-trend">
                        <i class="bi bi-file-text-fill"></i>
                    </div>
                </div>
            </div>

            <div class="users-table">
                <div class="table-header">
                    <h2>Users Management</h2>
                    <form method="GET" action="" id="filterForm" class="filter-form">
                        <div class="input-group">
                            <label for="filterInput">Filter by role:</label>
                            <select name="filter" id="filterInput" onchange="document.getElementById('filterForm').submit()">
                                <option value="all" <?= $userFilter === 'all' ? 'selected' : '' ?>>All Users</option>
                                <option value="user" <?= $userFilter === 'user' ? 'selected' : '' ?>>Regular Users</option>
                                <option value="admin" <?= $userFilter === 'admin' ? 'selected' : '' ?>>Administrators</option>
                                <option value="official" <?= $userFilter === 'official' ? 'selected' : '' ?>>Barangay Officials</option>
                            </select>
                        </div>
                    </form>
                </div>

                <div class="table-container">
                    <table class="table">
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
                                $roleClass = strtolower($user['role']);
                                echo "<tr class='user-row {$roleClass}' id='user-{$userID}' onclick=\"displayUser('{$userID}')\">";
                                echo "<td><span class='user-id'>{$userID}</span></td>";
                                echo "<td>{$user['firstName']}</td>";
                                echo "<td>{$user['lastName']}</td>";
                                echo "<td><span class='user-email'>{$user['email']}</span></td>";
                                echo "<td><span class='role-badge {$roleClass}'>{$user['role']}</span></td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card user-details" id="user-information"></div>
        </main>
    </div>
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq"
        crossorigin="anonymous"></script>

    <script type="module" src="/src/scripts/admin.js"></script>

</body>

</html>