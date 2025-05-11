<?php
session_start();

require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../models/BaranggayModel.php';
require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../controllers/ReportController.php';
require_once __DIR__ . '/../controllers/ProcessFileController.php';

require_once __DIR__ . '/components/sidebar.php';
require_once __DIR__ . '/components/header.php';

$userID = $_SESSION['userID'] ?? null;
$user = findUserByID($userID);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (hasReachedMaxReports($userID)) {
        echo "<script>alert('You have reached the maximum number of reports for today.');</script>";
    } else {
        $fileUpload = uploadImage($_FILES, $userID);
        handleRegisterReport($_POST['latInput'], $_POST['lngInput'], $_POST['titleInput'], $_POST['descriptionInput'], $fileUpload, $userID);
    }
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
    <title>MapaAyos - User Mapa</title>
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

</head>

<body>
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="ma-toast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <strong class="me-auto" id="ma-toast-title">Toast Title</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body" id="ma-toast-body">
                Toast Message
            </div>
        </div>
    </div>

    <!-- Report Modal -->
    <div class="modal" id="reportModal" tabindex="-1" aria-labelledby="reportModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="reportModalLabel">Enter Report</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <label for="titleInput">Title:</label>
                        <input type="text" id="titleInput" name="titleInput" required>
                        <br>
                        <label for="descriptionInput">Description:</label>
                        <textarea name="descriptionInput" id="descriptionInput" required></textarea>
                        <br>
                        <label for="fileInput">Upload Image:</label>
                        <input type="file" id="fileInput" name="fileInput" accept=".jpg, .jpeg, .png" required>
                        <br>
                        <input type="hidden" name="latInput" id="latInput" required>
                        <input type="hidden" name="lngInput" id="lngInput" required>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="ma-btn">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

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

            <div class="map-wrapper">
                <select name="selectBaranggayInput" id="selectBaranggayInput">
                    <option value="null" selected disabled>Select Baranggay To Report</option>
                    <?php
                    $baranggays = getBaranggays();

                    foreach ($baranggays as $baranggay) {
                        echo "<option value='" . htmlspecialchars($baranggay['name']) . "'>" . htmlspecialchars($baranggay['name']) . "</option>";
                    }
                    ?>
                </select>
                <div id="map"></div> <!-- Map -->
                <div class="card info-container hidden" id="info-container"></div>
                <div class="map-controls-container">
                    <button id="my-location-btn"><i class="bi bi-crosshair"></i></button>
                    <button id="zoom-in-btn" class="zoom-btn">+</button>
                    <button id="zoom-out-btn" class="zoom-btn">−</button>
                </div>

            </div>
        </main>
    </div>

    <script>
        const currentUser = "<?php echo $userID ?>";
        const currentUserRole = "<?php echo $user ? $user["role"] : "null" ?>";
    </script>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
    <!-- Page JS -->
    <script src="/MapaAyos/src/scripts/mapa-init.js"></script>
    <script type="module" src="/MapaAyos/src/scripts/mapa.js"></script>

    <script src="/MapaAyos/public/js/sidebar.js"></script>

</body>

</html>