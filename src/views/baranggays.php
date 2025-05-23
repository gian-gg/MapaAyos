<?php
session_start();

require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../models/BaranggayModel.php';
require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../controllers/ReportController.php';
require_once __DIR__ . '/../controllers/BaranggayController.php';

require_once __DIR__ . '/../utils/Baranggay.php';
require_once __DIR__ . '/../utils/ProcessFile.php';
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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fileUpload = uploadImage($_FILES, $currentBaranggay, "public/img/baranggays");
    handleBaranggayUpdate($currentBaranggayID, $_POST['description'], $_POST['population'], $_POST["landArea"],  $_POST['phone'], $_POST['email'], $_POST['address'], $_POST['operating_hours_weekdays'], $_POST['operating_hours_saturday']);
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
    <link rel="stylesheet" href="/MapaAyos/public/css/baranggay-enhanced.css">
    <link rel="stylesheet" href="/MapaAyos/public/css/report-modal.css">

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>

</head>

<body class="bg-light">
    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-bottom-0">
                    <h1 class="modal-title fs-5" id="editModal">Edit Baranggay Information</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modal-body">
                    <form id="editBaranggayForm" method="POST" enctype="multipart/form-data">
                        <div class="mb-1">
                            <label for="fileInput" class="form-label">
                                <i class="bi bi-image"></i> Baranggay Photo
                            </label>
                            <input type="file" class="form-control" id="fileInput" name="fileInput" accept="image/*">
                            <small class="text-muted">Upload a new photo for the baranggay</small>
                        </div>

                        <div class="mb-1">
                            <label for="edit-description" class="form-label">
                                <i class="bi bi-card-text"></i> Description
                            </label>
                            <textarea class="form-control" id="edit-description" name="description" rows="4" required></textarea>
                        </div>

                        <div class="row mb-1">
                            <div class="col-md-6">
                                <label for="edit-population" class="form-label">
                                    <i class="bi bi-people"></i> Population
                                </label>
                                <input type="number" class="form-control" id="edit-population" name="population" required>
                            </div>
                            <div class="col-md-6">
                                <label for="edit-landArea" class="form-label">
                                    <i class="bi bi-geo-alt"></i> Land Area (km²)
                                </label>
                                <input type="number" step="0.01" class="form-control" id="edit-landArea" name="landArea" required>
                            </div>
                        </div>

                        <div class="mb-1">
                            <label class="form-label d-block">
                                <i class="bi bi-telephone"></i> Contact Information
                            </label>
                            <input type="tel" class="form-control" name="phone" id="edit-phone" required placeholder="Phone number">
                            <input type="email" class="form-control" name="email" id="edit-email" required placeholder="Email address">
                            <input type="text" class="form-control" name="address" id="edit-address" required placeholder="Baranggay Hall Address">
                        </div>

                        <div class="mb-1">
                            <label class="form-label d-block">
                                <i class="bi bi-clock"></i> Operating Hours
                            </label>
                            <div class="mb-1">
                                <label for="edit-weekdayHours" class="form-label small">Weekdays</label>
                                <input type="text" class="form-control" id="edit-weekdayHours" name="operating_hours_weekdays" required placeholder="e.g. 8:00 AM - 5:00 PM">
                            </div>
                            <div>
                                <label for="edit-saturdayHours" class="form-label small">Saturday</label>
                                <input type="text" class="form-control" id="edit-saturdayHours" name="operating_hours_saturday" required placeholder="e.g. 8:00 AM - 12:00 PM">
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn primary-color text-white">
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Report Modal -->
    <div class="modal fade" id="reportModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-bottom-0">
                    <h1 class="modal-title fs-5" id="reportModalLabel"></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="reportModal-body">
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
                    echo '<a href="/MapaAyos/baranggays" class="btn btn-back mb-4"><i class="bi bi-arrow-left"></i> Back to Baranggays</a>';
                }
                ?>

                <?php
                if ($currentBaranggay && $baranggayExists) {
                    $baranggayInfo = getBaranggayInfo($currentBaranggayID);
                    $baranggayReportsData = getReportsData($currentBaranggay, "!pending");
                    $resolvedReports = getReportsData($currentBaranggay, "resolved");

                    $infoJson = htmlspecialchars(json_encode($baranggayInfo), ENT_QUOTES, 'UTF-8');

                    echo "<div class='page-header'>";
                    echo "<div class='d-flex align-items-center gap-1'>";
                    echo "<h1 class='mb-0'>" . htmlspecialchars(capitalizeFirstLetter($currentBaranggay)) . "</h1>";
                    if ($user && $user["role"] == "official" && getBaranggayData($user["assignedBaranggay"])["name"] == $currentBaranggay) {
                        echo '<button style="color: #317A88;" class="btn btn-sm ms-2" title="Edit Baranggay" onclick="displayEditModal(' . $infoJson . ')">
                                <i class="bi bi-pencil"></i> Edit
                              </button>';
                    }
                    echo "</div>";
                    echo "</div>";

                    echo "<img src='/MapaAyos/public/img/baranggays/" . htmlspecialchars($currentBaranggay, ENT_QUOTES, 'UTF-8') . ".png' alt='Baranggay Image' class='baranggay-image'>";
                    echo "<p class='page-description mb-4'>" . htmlspecialchars($baranggayInfo["description"]) . "</p>";

                    echo "<div class='baranggay-dashboard'>
                            <div class='stats-grid'>
                                <div class='stat-card'>
                                    <div class='stat-header'>
                                        <i class='bi bi-people-fill' style='color: #317A88'></i>
                                        <h3>Population</h3>
                                    </div>
                                    <p class='stat-value'>" . number_format($baranggayInfo['population'] ?? 0) . "</p>
                                    <p class='stat-description'>Total residents</p>
                                </div>
                                
                                <div class='stat-card'>
                                    <div class='stat-header'>
                                        <i class='bi bi-geo-alt-fill' style='color: #317A88'></i>
                                        <h3>Land Area</h3>
                                    </div>
                                    <p class='stat-value'>" . ($baranggayInfo['landArea'] ?? 0) . " km²</p>
                                    <p class='stat-description'>Total area</p>
                                </div>

                                <div class='stat-card'>
                                    <div class='stat-header'>
                                        <i class='bi bi-clipboard2-data-fill' style='color: #317A88'></i>
                                        <h3>Reports</h3>
                                    </div>
                                    <p class='stat-value'>" . (count(getReportsData($currentBaranggay, "active")) ?? 0) . "</p>
                                    <p class='stat-description'>Active reports</p>
                                </div>

                                <div class='stat-card'>
                                    <div class='stat-header'>
                                        <i class='bi bi-check2-circle' style='color: #317A88'></i>
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
                                        <p><i class='bi bi-telephone' style='color: #317A88'></i> " . $baranggayInfo['phone'] . "</p>
                                        <p><i class='bi bi-envelope' style='color: #317A88'></i> " . $baranggayInfo['email'] . "</p>
                                        <p><i class='bi bi-geo' style='color: #317A88'></i> " . $baranggayInfo['address'] . "</p>
                                    </div>
                                </div>

                                <div class='info-card'>
                                    <h3>Operating Hours</h3>
                                    <div class='info-content'>
                                        <p><i class='bi bi-clock'  style='color: #317A88'></i> <strong>Weekdays:</strong> " . $baranggayInfo['operating_hours_weekdays'] . "</p>
                                        <p><i class='bi bi-clock'  style='color: #317A88'></i> <strong>Saturday:</strong> " . $baranggayInfo['operating_hours_saturday'] . "</p>
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

                    if (count($baranggayReportsData) == 0) {
                        echo "<tr><td colspan='4' class='text-center'>No reports available</td></tr>";
                    } else {
                        $reportCount = 1;
                        foreach ($baranggayReportsData as $report) {
                            $reportJson = htmlspecialchars(json_encode($report), ENT_QUOTES, 'UTF-8');
                            echo "
                                <tr onclick=\"displayReportModal({$reportJson})\" style=\"cursor: pointer;\">
                                    <td>{$reportCount}</td>
                                    <td>" . htmlspecialchars($report['title'], ENT_QUOTES, 'UTF-8') . "</td>
                                    <td><span class='badge bg-" . getStatusColor($report['status']) . "'>" . htmlspecialchars($report['status'], ENT_QUOTES, 'UTF-8') . "</span></td>
                                    <td>" . htmlspecialchars(getRelativeDate($report['createdAt']), ENT_QUOTES, 'UTF-8') . "</td>
                                </tr>
                            ";

                            $reportCount++;
                        }
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
                                        <tr onclick=\"window.location='/MapaAyos/baranggays?baranggay=" . htmlspecialchars($baranggay['name'], ENT_QUOTES, 'UTF-8') . "';\" style=\"cursor: pointer;\">
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

    <script type="module" src="/MapaAyos/src/scripts/baranggay.js"></script>

    <script src="/MapaAyos/public/js/sidebar.js"></script>

</body>

</html>