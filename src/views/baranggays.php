<?php
session_start();

require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../models/BaranggayModel.php';
require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../controllers/ReportController.php';

require_once __DIR__ . '/../utils/Baranggay.php';
require_once __DIR__ . '/../utils/Misc.php';

require_once __DIR__ . '/components/sidebar.php';
require_once __DIR__ . '/components/header.php';
require_once __DIR__ . '/components/toasts.php';

$userID = $_SESSION['userID'] ?? null;
$user = findUserByID($userID);
if (isset($_POST['logout'])) {
    handleSignOut();
}

$baranggays = getBaranggays();
$currentBaranggay = $_GET['baranggay'] ?? null;
$currentBaranggayID;
$baranggayExists = false;

if ($currentBaranggay) {
    foreach ($baranggays as $baranggay) {
        if ($baranggay['name'] === $currentBaranggay) {
            $baranggayExists = true;
            $currentBaranggayID = $baranggay['id'];
            break;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MapaAyos - Baranggays</title>
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
    <link rel="stylesheet" href="/public/css/baranggay.css">
    <link rel="stylesheet" href="/public/css/sidebar.css">
    <link rel="stylesheet" href="/public/css/header.css">
    <link rel="stylesheet" href="/public/css/baranggay-enhanced.css">

</head>

<body class="bg-light">
    <!-- Report Modal -->
    <div class="modal fade" id="modal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-bottom-0">
                    <h1 class="modal-title fs-5" id="modalLabel"></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modal-body">
                </div>
            </div>
        </div>
    </div>

    <?php renderToasts(); ?>

    <div class="dashboard">
        <?php
        renderSideBar(
            $user ? $user["role"] : null,
            "baranggays",
            isAuthenticated()
        )
        ?>

        <main class="main-content">
            <?php
            renderHeader(
                $user ?? null
            );
            ?>
            <div class="main-content" style="width: 78vw;">
                <?php
                if ($currentBaranggay) {
                    echo '<a href="/baranggays" class="btn btn-back mb-4"><i class="bi bi-arrow-left"></i> Back to Baranggays</a>';
                }
                ?>

                <?php
                if ($currentBaranggay && $baranggayExists) {
                    $baranggayInfo = getBaranggayInfo($currentBaranggayID);
                    $baranggayReportsData = getReportsData($currentBaranggay, "!pending");
                    $resolvedReports = getReportsData($currentBaranggay, "resolved");

                    echo "<div class='page-header'>";
                    echo "<h1>" . htmlspecialchars(capitalizeFirstLetter($currentBaranggay)) . "</h1>";
                    echo "</div>";

                    echo "<img src='/public/img/baranggays/" . htmlspecialchars($currentBaranggay, ENT_QUOTES, 'UTF-8') . ".jpg' alt='Baranggay Image' class='baranggay-image'>";
                    echo "<p class='page-description mb-4'>" . htmlspecialchars($baranggayInfo["description"]) . "</p>";

                    echo "<div class='baranggay-dashboard'>
                            <div class='stats-grid'>
                                <div class='stat-card'>
                                    <div class='stat-header'>
                                        <i class='bi bi-people-fill'></i>
                                        <h3>Population</h3>
                                    </div>
                                    <p class='stat-value'>" . number_format($baranggayInfo['population'] ?? 0) . "</p>
                                    <p class='stat-description'>Total residents</p>
                                </div>
                                
                                <div class='stat-card'>
                                    <div class='stat-header'>
                                        <i class='bi bi-geo-alt-fill'></i>
                                        <h3>Land Area</h3>
                                    </div>
                                    <p class='stat-value'>" . ($baranggayInfo['landArea'] ?? 0) . " km²</p>
                                    <p class='stat-description'>Total area</p>
                                </div>

                                <div class='stat-card'>
                                    <div class='stat-header'>
                                        <i class='bi bi-clipboard2-data-fill'></i>
                                        <h3>Reports</h3>
                                    </div>
                                    <p class='stat-value'>" . (count(getReportsData($currentBaranggay, "active")) ?? 0) . "</p>
                                    <p class='stat-description'>Active reports</p>
                                </div>

                                <div class='stat-card'>
                                    <div class='stat-header'>
                                        <i class='bi bi-check2-circle'></i>
                                        <h3>Resolved</h3>
                                    </div>
                                    <p class='stat-value'>" . (calculateResolutionRate(count(getReportsData($currentBaranggay, "resolved")), count($baranggayReportsData)) ?? 0) . "%</p>
                                    <p class='stat-description'>Resolution rate</p>
                                </div>
                            </div>

                            <div class='info-grid'>
                                <div class='info-card'>
                                    <h3>Contact Information</h3>
                                    <div class='info-content'>
                                        <p><i class='bi bi-telephone'></i> " . ($baranggay['phone'] ?? '(02) 8123-4567') . "</p>
                                        <p><i class='bi bi-envelope'></i> " . ($baranggay['email'] ?? 'contact@baranggay.gov.ph') . "</p>
                                        <p><i class='bi bi-geo'></i> " . ($baranggay['address'] ?? 'Main Street, City') . "</p>
                                    </div>
                                </div>

                                <div class='info-card'>
                                    <h3>Operating Hours</h3>
                                    <div class='info-content'>
                                        <p><i class='bi bi-clock'></i> <strong>Monday - Friday:</strong> " . ($baranggay['weekday_hours'] ?? '8:00 AM - 5:00 PM') . "</p>
                                        <p><i class='bi bi-clock'></i> <strong>Saturday:</strong> " . ($baranggay['saturday_hours'] ?? '8:00 AM - 12:00 PM') . "</p>
                                        <p><i class='bi bi-clock'></i> <strong>Sunday:</strong> " . ($baranggay['sunday_hours'] ?? 'Closed') . "</p>
                                    </div>
                                </div>
                            </div>
                        </div>";

                    echo "
                        <div class='reports-container mt-4'>
                            <h2 class='mb-3'>Recent Reports</h2>
                            <div class='table-responsive'>
                                <table class='table table-hover align-middle mb-0'>
                                    <thead>
                                        <tr>
                                            <th scope='col'>#</th>
                                            <th scope='col'>Title</th>
                                            <th scope='col'>Status</th>
                                            <th scope='col'>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                    ";

                    $reportCount = 1;
                    foreach ($baranggayReportsData as $report) {
                        $reportJson = htmlspecialchars(json_encode($report), ENT_QUOTES, 'UTF-8');
                        echo "
                            <tr onclick=\"displayModal({$reportJson})\" style=\"cursor: pointer;\">
                                <td>{$reportCount}</td>
                                <td>" . htmlspecialchars($report['title'], ENT_QUOTES, 'UTF-8') . "</td>
                                <td><span class='badge bg-" . getStatusColor($report['status']) . "'>" . htmlspecialchars($report['status'], ENT_QUOTES, 'UTF-8') . "</span></td>
                                <td>" . htmlspecialchars(getRelativeDate($report['createdAt']), ENT_QUOTES, 'UTF-8') . "</td>
                            </tr>
                        ";

                        $reportCount++;
                    }

                    echo "</tbody></table></div></div>";
                } else if ($currentBaranggay && !$baranggayExists) {
                    echo "<div class='alert alert-warning' role='alert'>
                            <h4 class='alert-heading'><i class='bi bi-exclamation-triangle-fill me-2'></i>Baranggay Not Supported</h4>
                            <p class='mb-0'>This baranggay is not yet supported in our system. Please check back later.</p>
                          </div>";
                } else {
                ?>
                    <div class="page-header">
                        <h1>Baranggays</h1>
                        <p class="page-description">Explore and manage baranggays in your area</p>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th scope="col">Name</th>
                                    <th scope="col">City</th>
                                    <th scope="col">Country</th>
                                    <th scope="col">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($baranggays as $baranggay) {
                                    echo "
                                        <tr onclick=\"window.location='/baranggays?baranggay=" . htmlspecialchars($baranggay['name'], ENT_QUOTES, 'UTF-8') . "';\" style=\"cursor: pointer;\">
                                            <td>" . htmlspecialchars(capitalizeFirstLetter($baranggay['name']), ENT_QUOTES, 'UTF-8') . "</td>
                                            <td>" . htmlspecialchars(capitalizeFirstLetter($baranggay['city']), ENT_QUOTES, 'UTF-8') . "</td>
                                            <td>" . htmlspecialchars(capitalizeFirstLetter($baranggay['country']), ENT_QUOTES, 'UTF-8') . "</td>
                                            <td><span class='badge bg-" . getStatusColor($baranggay['status']) . "'>" . htmlspecialchars(capitalizeFirstLetter($baranggay['status']), ENT_QUOTES, 'UTF-8') . "</span></td>
                                        </tr>
                                    ";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                <?php
                }
                ?>
            </div>
        </main>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>

    <script src="/src/scripts/baranggay.js"></script>

    <script src="/public/js/sidebar.js"></script>

</body>

</html>