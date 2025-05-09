<?php
session_start();
require_once __DIR__ . '/../../models/UserModel.php';
require_once __DIR__ . '/../../controllers/AuthController.php';
require_once __DIR__ . '/../../models/DashboardStats.php';
require_once __DIR__ . '/../../models/RoleRequestModel.php';

requireSignIn();

$userID = $_SESSION['userID'];
$user = findUserByID($userID);
$totalUsers = getTotalUsers($pdo);
$activeReports = getActiveReports($pdo);
$resolvedReports = getResolvedPercentage($pdo);
$civilians = getUsersByRole('user');
$officials = getUsersByRole('official');
$requests = getAllRoleRequests();

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
                    <div class="card-value"><?php echo $totalUsers; ?></div>
                </div>
                <div class="card">
                    <div class="card-title">Active Reports</div>
                    <div class="card-value"><?php echo $activeReports; ?></div>
                </div>
                <div class="card">
                    <div class="card-title">Resolved Issues</div>
                    <div class="card-value"><?php echo $resolvedReports; ?>%</div>
                </div>
                <div class="card">
                    <div class="card-title">Civilians</div>
                    <div class="card-value"><?= count($civilians) ?></div>
                </div>
                <div class="card">
                    <div class="card-title">Officials</div>
                    <div class="card-value"><?= count($officials) ?></div>
                </div>
                <div class="card">
                    <div class="card-title">Role Requests</div>
                    <div class="card-value"><?= count($requests) ?></div>
                </div>
            </div>

            <section>
                <h2>User Management</h2>

                <h3>Civilians</h3>
                <table>
                    <tr><th>Name</th><th>Email</th><th>Action</th></tr>
                    <?php foreach ($civilians as $civilian): ?>
                        <tr>
                            <td><?= htmlspecialchars($civilian['firstName'] . ' ' . $civilian['lastName']) ?></td>
                            <td><?= htmlspecialchars($civilian['email']) ?></td>
                            <td>
                                <form method="POST" action="/MapaAyos/update-role">
                                    <input type="hidden" name="user_id" value="<?= $civilian['id'] ?>">
                                    <input type="hidden" name="new_role" value="official">
                                    <button type="submit">Promote to Official</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>

                <h3>Officials</h3>
                <table>
                    <tr><th>Name</th><th>Email</th><th>Action</th></tr>
                    <?php foreach ($officials as $official): ?>
                        <tr>
                            <td><?= htmlspecialchars($official['firstName'] . ' ' . $official['lastName']) ?></td>
                            <td><?= htmlspecialchars($official['email']) ?></td>
                            <td>
                                <form method="POST" action="/MapaAyos/update-role">
                                    <input type="hidden" name="user_id" value="<?= $official['id'] ?>">
                                    <input type="hidden" name="new_role" value="user">
                                    <button type="submit">Demote to Civilian</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </section>

            <section>
                <h2>Role Requests</h2>
                <table>
                    <tr><th>User</th><th>Answers</th><th>Proof</th><th>Action</th></tr>
                    <?php foreach ($requests as $req): ?>
                        <tr>
                            <td><?= htmlspecialchars($req['firstName'] . ' ' . $req['lastName']) ?></td>
                            <td><?= nl2br(htmlspecialchars($req['answers'])) ?></td>
                            <td>
                                <?php if ($req['proof_image']): ?>
                                    <a href="../../uploads/<?= $req['proof_image'] ?>" target="_blank">View</a>
                                <?php else: ?>
                                    No file
                                <?php endif; ?>
                            </td>
                            <td>
                                <form method="POST" action="/MapaAyos/update-role">
                                    <input type="hidden" name="user_id" value="<?= $req['userID'] ?>">
                                    <input type="hidden" name="new_role" value="official">
                                    <button type="submit">Approve</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </section>

        </main>
    </div>
</body>

</html>