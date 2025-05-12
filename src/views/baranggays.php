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
    <link rel="stylesheet" href="/MapaAyos/public/css/baranggay.css">
    <link rel="stylesheet" href="/MapaAyos/public/css/sidebar.css">
    <link rel="stylesheet" href="/MapaAyos/public/css/header.css">

</head>

<body>
    <!-- Report Modal -->
    <div class="modal" id="modal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
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
            <div class="main-content">
                <?php
                if ($currentBaranggay) {
                    echo '<a href="/MapaAyos/baranggays" class="btn btn-secondary mb-2"><i class="bi bi-arrow-left"></i> Back</a>';
                }
                ?>

                <?php
                if ($currentBaranggay && $baranggayExists) {
                    $baranggayInfo = getBaranggayInfo($currentBaranggayID);
                    $baranggayReportsData = getReportsData($currentBaranggay, "!pending");
                    $resolvedReports = getReportsData($currentBaranggay, "resolved");

                    echo "<h1>" . htmlspecialchars($currentBaranggay) . "</h1>";
                    echo "<img src='/MapaAyos/public/img/baranggays/" . htmlspecialchars($currentBaranggay, ENT_QUOTES, 'UTF-8') . ".jpg' alt='Baranggay Image' class='img-fluid mb-3'>";
                    echo "<p>" . htmlspecialchars($baranggayInfo["description"]) . "</p>";
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
                                        <p><strong>Monday - Friday:</strong> " . ($baranggay['weekday_hours'] ?? '8:00 AM - 5:00 PM') . "</p>
                                        <p><strong>Saturday:</strong> " . ($baranggay['saturday_hours'] ?? '8:00 AM - 12:00 PM') . "</p>
                                        <p><strong>Sunday:</strong> " . ($baranggay['sunday_hours'] ?? 'Closed') . "</p>
                                    </div>
                                </div>
                            </div>
                        </div>";

                    echo "
                        <div class='reports-container'>
                            <h2>Recent Reports</h2>
                            <table class='table table-hover align-middle'>
                                <thead class='table-light'>
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

                    echo "</tbody></table></div>";
                } else if ($currentBaranggay && !$baranggayExists) {
                    echo "<h1>Baranggay is not yet Supported.</h1>";
                } else {
                ?>
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">Name</th>
                                <th scope="col">City</th>
                                <th scope="col">Country</th>
                                <th scope="col">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            echo "<h1>Baranggays</h1>";
                            foreach ($baranggays as $baranggay) {
                                echo "
                                    <tr onclick=\"window.location='/MapaAyos/baranggays?baranggay=" . htmlspecialchars($baranggay['name'], ENT_QUOTES, 'UTF-8') . "';\" style=\"cursor: pointer;\">
                                        <td>" . htmlspecialchars($baranggay['name'], ENT_QUOTES, 'UTF-8') . "</td>
                                        <td>" . htmlspecialchars($baranggay['city'], ENT_QUOTES, 'UTF-8') . "</td>
                                        <td>" . htmlspecialchars($baranggay['country'], ENT_QUOTES, 'UTF-8') . "</td>
                                        <td><span class='badge bg-success'>active</span></td>
                                    </tr>
                                ";
                            }
                            ?>
                        </tbody>
                    </table>
                <?php
                }
                ?>
            </div>

        </main>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>

    <script src="/MapaAyos/src/scripts/baranggay.js"></script>

    <script src="/MapaAyos/public/js/sidebar.js"></script>

</body>

</html>