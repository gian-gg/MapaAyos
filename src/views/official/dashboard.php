<?php
session_start();
require_once __DIR__ . '/../../models/UserModel.php';
require_once __DIR__ . '/../../controllers/AuthController.php';
require_once __DIR__ . '/../../models/ReportModel.php';
require_once __DIR__ . '/../../models/BaranggayModel.php';

require_once __DIR__ . '/../components/sidebar.php';
require_once __DIR__ . '/../components/header.php';

requireSignIn();

$userID = $_SESSION['userID'] ?? null;
$user = findUserByID($userID);
$baranggayData = getBaranggayData($user["assignedBaranggay"]);

redirectIfNotAllowed($user["role"], "official");

if (isset($_POST['logout'])) {
    handleSignOut();
}

$reportFilter = $_GET['filter'] ?? 'all';

if ($reportFilter !== 'all' && $reportFilter != 'pending' && $reportFilter != 'resolved' && $reportFilter != 'denied') {
    $reportFilter = 'all'; // Default to 'all' if the filter is invalid
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    updateReport(
        $_POST['reportID'],
        $_POST['verificationStatus'],
        $_POST['comment'],
        $userID
    );
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
    <link rel="stylesheet" href="/MapaAyos/public/css/baranggay.css">
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
                <?php
                renderHeader(
                    isAuthenticated(),
                    $user ? $user["hasProfilePic"] : false,
                    $userID
                );
                ?>

                <div class="cards-grid">
                    <div class="card">
                        <div class="card-title">Total Reports</div>
                        <div class="card-value"><?= count(getReportsData($baranggayData["name"], "all")) ?></div>
                    </div>
                    <div class="card">
                        <div class="card-title">Pending Reports</div>
                        <div class="card-value"><?= count(getReportsData($baranggayData["name"], "pending")) ?></div>
                    </div>
                    <div class="card">
                        <div class="card-title">Resolved Reports</div>
                        <div class="card-value"><?= count(getReportsData($baranggayData["name"], "resolved")) ?></div>
                    </div>
                </div>

                <div class="reports-table">
                    <h2>Reports</h2>
                    <form method="GET" action="" id="filterForm">
                        <select name="filter" id="filterInput" onchange="document.getElementById('filterForm').submit()">
                            <option value="all" <?= $reportFilter === 'all' ? 'selected' : '' ?>>All</option>
                            <option value="pending" <?= $reportFilter === 'pending' ? 'selected' : '' ?>>Pending</option>
                            <option value="resolved" <?= $reportFilter === 'resolved' ? 'selected' : '' ?>>Resolved</option>
                            <option value="denied" <?= $reportFilter === 'denied' ? 'selected' : '' ?>>Denied</option>
                        </select>
                    </form>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Report ID</th>
                                <th>Title</th>
                                <th>Status</th>
                                <th>Date Submitted</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $reports = getReportsData($baranggayData["name"], $reportFilter);
                            if (empty($reports)) {
                                echo "<tr><td colspan='4' class='text-center'>No reports found.</td></tr>";
                            }

                            foreach ($reports as $report) {
                                $reportID = htmlspecialchars($report['id'], ENT_QUOTES, 'UTF-8');
                                echo "<tr class='report-row' id='report-{$reportID}' onclick=\"displayReport('{$reportID}')\" style=\"cursor: pointer;\">";
                                echo "<td>{$report['id']}</td>";
                                echo "<td>{$report['title']}</td>";
                                echo "<td>{$report['status']}</td>";
                                echo "<td>{$report['createdAt']}</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="d-flex gap-5">
                    <div class="card" id="report-information"></div>
                    <div class="map-wrapper" style="width: 40vw; height: 50vh;">
                        <div id="map"></div> <!-- Map -->
                        <div class="card info-container hidden" id="info-container"></div>
                        <div class="map-controls-container">
                            <button id="my-location-btn"><i class="bi bi-crosshair"></i></button>
                            <button id="zoom-in-btn" class="zoom-btn">+</button>
                            <button id="zoom-out-btn" class="zoom-btn">−</button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        const currentUser = "<?php echo $userID ?>";
        const currentBaranggay = "<?php echo $baranggayData ? $baranggayData["name"] : "null" ?>";
        const currentFilter = "<?php echo $reportFilter ?>";
    </script>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script src="/MapaAyos/src/scripts/mapa-init.js"></script>
    <script type="module" src="/MapaAyos/src/scripts/officer-mapa.js"></script>


</body>

</html>