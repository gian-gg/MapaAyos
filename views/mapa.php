<?php
require_once __DIR__ . '/../controllers/ReportController.php';

session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../controllers/AuthController.php';

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MapaAyos - Mapa</title>

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <!-- Project CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">

    <link rel="stylesheet" href="/MapaAyos/assets/css/root.css">
    <link rel="stylesheet" href="/MapaAyos/assets/css/main.css">

    <link rel="stylesheet" href="/MapaAyos/assets/css/navbar.css">
    <link rel="stylesheet" href="/MapaAyos/assets/css/navbar-mobile.css">
    <link rel="stylesheet" href="/MapaAyos/assets/css/mapa-init.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
</head>

<body>
    <header>
        <a href="/MapaAyos/index.php">
            <div class="branding">
                <img src="/MapaAyos/assets/img/logo.png" alt="MapaAyos">
                <div class="brand-title">
                    <h1>MapaAyos</h1>
                    <p>nisi commodo laborum</p>
                </div>
            </div>
        </a>

        <!-- Toggle button for mobile -->
        <button class="btn d-md-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar" aria-controls="mobileSidebar" aria-label="Toggle navigation" style="background:none; border:none; font-size: 1.5rem; color: var(--text-primary);">
            <i class="bi bi-three-dots-vertical"></i>
        </button>

        <div class="nav-items d-none d-md-flex">
            <a href="/MapaAyos/index.php#how_it_works">How it Works</a>
            <a href="/MapaAyos/index.php#features">Features</a>
            <a href="/MapaAyos/index.php#about">About</a>
            <a href="/MapaAyos/index.php#contact">Contact</a>
        </div>

        <div class="btn-group d-none d-md-flex">
            <?php
            if (!isAuthenticated()) {
                echo '<a class="signin-btn" href="/MapaAyos/signin">Sign In</a>';
                echo '<a class="signup-btn" href="/MapaAyos/signup">Sign Up</a>';
            } else {
                echo '<a class="signup-btn" href="/MapaAyos/user/dashboard">Dashboard</a>';
            }
            ?>
        </div>
    </header>

    <!-- Offcanvas sidebar for mobile -->
    <div class="offcanvas offcanvas-start" tabindex="-1" id="mobileSidebar" aria-labelledby="mobileSidebarLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="mobileSidebarLabel">MapaAyos</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <nav class="nav flex-column">
                <a class="nav-link" href="#how_it_works" data-bs-dismiss="offcanvas">How it Works</a>
                <a class="nav-link" href="#features" data-bs-dismiss="offcanvas">Features</a>
                <a class="nav-link" href="#about" data-bs-dismiss="offcanvas">About</a>
                <a class="nav-link" href="#contact" data-bs-dismiss="offcanvas">Contact</a>
            </nav>
            <div class="mt-3">
                <?php
                if (!isAuthenticated()) {
                    echo '<a class="btn btn-outline-primary w-100 mb-2" href="/MapaAyos/signin" data-bs-dismiss="offcanvas">Sign In</a>';
                    echo '<a class="btn btn-primary w-100" href="/MapaAyos/signup" data-bs-dismiss="offcanvas">Sign Up</a>';
                } else {
                    echo '<a class="btn btn-primary w-100" href="/MapaAyos/user/dashboard" data-bs-dismiss="offcanvas">Dashboard</a>';
                }
                ?>
            </div>
        </div>
    </div>

    <div class="map-wrapper">
        <div class="left-panel">
            <p>Reports</p>
            <?php
            $reports = getAllReportsController();
            if (!empty($reports)) {
                echo '<ul class="reports-list">';
                foreach ($reports as $report) {
                    echo '<li class="report-item">';
                    echo 'Report Title:<br>' . '<small>' . htmlspecialchars($report["title"]) . '</small>';
                    echo 'Description:<br>' . '<small>' . htmlspecialchars($report["description"]) . '</small><br>';
                    $date = new DateTime($report["createdAt"]);
                    $formattedDate = $date->format('F j, Y, g:i a');
                    echo '<small><em>' . htmlspecialchars($formattedDate) . '</em></small>';
                    echo '</li>';
                }
                echo '</ul>';
            } else {
                echo '<p>No reports found.</p>';
            }
            ?>
        </div>
        <div class="map-container">
            <i class="bi bi-layout-sidebar"></i>
            <div id="map"></div>
            <div class="map-controls-container">
                <button id="my-location-btn">My Location</button>
                <div class="custom-zoom-controls">
                    <button id="zoom-in-btn" class="zoom-btn">+</button>
                    <button id="zoom-out-btn" class="zoom-btn">−</button>
                </div>
            </div>
        </div>
        <div class="right-panel" style="display:none;">
            <button id="right-panel-close-btn" aria-label="Close right panel" style="float:right; background:none; border:none; font-size:1.5rem; cursor:pointer;">&times;</button>
            <div id="right-panel-content">
                <!-- Report details will be populated here -->
            </div>
        </div>
    </div>

    <!-- For JS to access mysql data -->
    <script>
        const reports = <?php echo json_encode(getAllReports()); ?>;
    </script>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script src="/MapaAyos/assets/js/mapa-init.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
</body>

</html>
