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
    <link rel="stylesheet" href="/public/css/baranggay.css">
    <link rel="stylesheet" href="/public/css/official-dashboard.css">
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
            $pageTitle = 'Official Dashboard';
            ?>
            <div class="dashboard-container">
                <?php
                renderHeader(
                    $user ?? null
                );
                ?>

                <div class="cards-grid">
                    <div class="card">
                        <div class="card-title">
                            <i class="bi bi-file-text me-2"></i>
                            Total Reports
                        </div>
                        <div class="card-value"><?= count(getReportsData($baranggayData["name"], "all")) ?></div>
                    </div>
                    <div class="card">
                        <div class="card-title">
                            <i class="bi bi-hourglass-split me-2"></i>
                            Pending Reports
                        </div>
                        <div class="card-value"><?= count(getReportsData($baranggayData["name"], "pending")) ?></div>
                    </div>
                    <div class="card">
                        <div class="card-title">
                            <i class="bi bi-check-circle me-2"></i>
                            Resolved Reports
                        </div>
                        <div class="card-value"><?= count(getReportsData($baranggayData["name"], "resolved")) ?></div>
                    </div>
                </div>

                <div class="reports-table">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2>
                            <i class="bi bi-table me-2"></i>
                            Reports Overview
                        </h2>
                        <form method="GET" action="" id="filterForm" class="mb-0">
                            <select name="filter" id="filterInput" onchange="document.getElementById('filterForm').submit()">
                                <option value="all" <?= $reportFilter === 'all' ? 'selected' : '' ?>>All Reports</option>
                                <option value="pending" <?= $reportFilter === 'pending' ? 'selected' : '' ?>>Pending Reports</option>
                                <option value="resolved" <?= $reportFilter === 'resolved' ? 'selected' : '' ?>>Resolved Reports</option>
                                <option value="denied" <?= $reportFilter === 'denied' ? 'selected' : '' ?>>Denied Reports</option>
                            </select>
                        </form>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover">
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
                                    echo "<tr><td colspan='4' class='text-center py-4'><i class='bi bi-inbox me-2'></i>No reports found.</td></tr>";
                                }

                                foreach ($reports as $report) {
                                    $reportID = htmlspecialchars($report['id'], ENT_QUOTES, 'UTF-8');
                                    $statusClass = '';
                                    $statusIcon = '';

                                    switch ($report['status']) {
                                        case 'pending':
                                            $statusClass = 'text-warning';
                                            $statusIcon = 'hourglass-split';
                                            break;
                                        case 'resolved':
                                            $statusClass = 'text-success';
                                            $statusIcon = 'check-circle';
                                            break;
                                        case 'denied':
                                            $statusClass = 'text-danger';
                                            $statusIcon = 'x-circle';
                                            break;
                                        default:
                                            $statusClass = 'text-primary';
                                            $statusIcon = 'circle';
                                    }

                                    echo "<tr class='report-row' id='report-{$reportID}' onclick=\"displayReport('{$reportID}')\">";
                                    echo "<td><small class='text-muted'>#</small>{$report['id']}</td>";
                                    echo "<td>{$report['title']}</td>";
                                    echo "<td><i class='bi bi-{$statusIcon} me-2 {$statusClass}'></i><span class='{$statusClass}'>" . ucfirst($report['status']) . "</span></td>";
                                    echo "<td>" . date('M d, Y', strtotime($report['createdAt'])) . "</td>";
                                    echo "</tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="d-flex gap-4 justify-content-between" style="height: 50vh;">
                    <div class="card flex-grow-1" id="report-information">
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-file-earmark-text display-4"></i>
                            <p class="mt-3">Select a report to view details</p>
                        </div>
                    </div>
                    <div class="map-wrapper">
                        <div id="map"></div>
                        <div class="card info-container hidden" id="info-container"></div>
                        <div class="map-controls-container">
                            <button id="my-location-btn" title="My Location"><i class="bi bi-crosshair"></i></button>
                            <button id="zoom-in-btn" class="zoom-btn" title="Zoom In">+</button>
                            <button id="zoom-out-btn" class="zoom-btn" title="Zoom Out">−</button>
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

    <script src="/src/scripts/mapa-init.js"></script>
    <script type="module" src="/src/scripts/officer-mapa.js"></script>


</body>

</html>