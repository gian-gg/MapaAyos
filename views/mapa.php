<?php
require_once __DIR__ . '/../controllers/ReportController.php';

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
    <link rel="stylesheet" href="/MapaAyos/assets/css/root.css">
    <link rel="stylesheet" href="/MapaAyos/assets/css/main.css">
    <link rel="stylesheet" href="/MapaAyos/assets/css/landing.css">
    <link rel="stylesheet" href="/MapaAyos/assets/css/mapa.css">
</head>

<body>
    <nav>
        <div class="btn-group">
            <a class="ma-btn" href="/MapaAyos/">Home</a>
            <a class="ma-btn" href="/MapaAyos/signup">Sign Up</a>
        </div>
    </nav>

    <button id="my-location-btn">My Location</button>
    <div id="map"></div> <!-- Map -->

    <!-- For JS to access mysql data -->
    <script>
        const reports = <?php echo json_encode(getAllReports()); ?>;
    </script>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script src="/MapaAyos/assets/js/mapa-init.js"></script>
</body>

</html>