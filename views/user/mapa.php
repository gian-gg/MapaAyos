<?php
session_start();
require_once __DIR__ . '/../../models/UserModel.php';
require_once __DIR__ . '/../../controllers/AuthController.php';
require_once __DIR__ . '/../../controllers/ReportController.php';

requireSignIn();

$userID = $_SESSION['userID'];
$user = findUserByID($userID);

redirectIfNotAllowed($user["role"], "user");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    handleRegisterReport($_POST['latInput'], $_POST['lngInput'], $_POST['titleInput'], $_POST['descriptionInput'], $userID);
}

if (isset($_POST['logout'])) {
    handleSignOut();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MapaAyos Dashboard - Mapa</title>


    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" />

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <!-- Project CSS -->
    <link rel="stylesheet" href="/MapaAyos/assets/css/root.css">
    <link rel="stylesheet" href="/MapaAyos/assets/css/main.css">
    <link rel="stylesheet" href="/MapaAyos/assets/css/dashboard.css">
    <link rel="stylesheet" href="/MapaAyos/assets/css/mapa.css">

</head>

<body>
    <!-- Report Modal -->
    <div class="modal" id="reportModal" tabindex="-1" aria-labelledby="reportModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="reportModalLabel">Enter Report</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <label for="titleInput">Title:</label>
                        <input type="text" id="titleInput" name="titleInput" required>
                        <br>
                        <label for="descriptionInput">Description:</label>
                        <textarea name="descriptionInput" id="descriptionInput" required></textarea>
                        <br>
                        <label for="fileInput">Upload Image:</label>
                        <input type="file" id="fileInput" name="fileInput" accept=".jpg, .jpeg, .png">
                        <br>
                        <input type="hidden" name="latInput" id="latInput">
                        <input type="hidden" name="lngInput" id="lngInput">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="ma-btn">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="dashboard">
        <aside class="sidebar">
            <div class="logo">MapaAyos</div>
            <nav>
                <div>
                    <a href="/MapaAyos/user/dashboard" class="nav-item">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                            <polyline points="9 22 9 12 15 12 15 22"></polyline>
                        </svg>
                        Dashboard
                    </a>
                    <a href="/MapaAyos/user/mapa" class="nav-item">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"></path>
                            <circle cx="12" cy="9" r="2.5"></circle>
                        </svg>
                        Mapa
                    </a>
                </div>
                <form method="POST">
                    <button type="submit" name="logout" class="ma-btn">Log Out</button>
                </form>

            </nav>
        </aside>
        <main class="main-content">
            <div class="header">
                <h1>Mapa</h1>
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
            <button class="ma-btn" id="my-location-btn">My Location</button>
            <div id="map"></div> <!-- Map -->
        </main>
    </div>

    <script>
        const currentUser = "<?php echo $userID ?>";
    </script>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
    <!-- Page JS -->
    <script src="/MapaAyos/assets/js/mapa-init.js"></script>
    <script src="/MapaAyos/assets/js/user-mapa.js"></script>

</body>

</html>