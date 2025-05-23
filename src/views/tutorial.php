<?php
session_start();

require_once __DIR__ . '/../controllers/AuthController.php';

requireSignIn();

$userID = $_SESSION['userID'] ?? null;
$user = findUserByID($userID);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MapaAyos - Tutorial</title>
    <link rel="shortcut icon" href="/MapaAyos/public/img/favicon.png" type="image/png">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">

    <link rel="stylesheet" href="/MapaAyos/public/css/root.css">
    <link rel="stylesheet" href="/MapaAyos/public/css/main.css">
    <link rel="stylesheet" href="/MapaAyos/public/css/tutorial.css">

    <link rel="stylesheet" href="/MapaAyos/public/css/footer.css">
    <link rel="stylesheet" href="/MapaAyos/public/css/navbar.css">
    <link rel="stylesheet" href="/MapaAyos/public/css/navbar-mobile.css">
    <link rel="stylesheet" href="/MapaAyos/public/css/footer-mobile.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
</head>

<body>

    <div class="tutorial-page">
        <div class="tutorial-content">
            <img id="tutorial-image" src="/MapaAyos/public/tutorial/step1.png" alt="Tutorial Step 1">
            <div id="tutorial-text" class="tutorial-text"></div>
        </div>
        <div class="controls">
            <div class="left-controls">
                <button id="back-btn" onclick="prevStep()">Back</button>
            </div>
            <div class="right-controls">
                <button id="next-btn" onclick="nextStep()">Next</button>
                <a href="/MapaAyos/api/complete_tutorial" id="finish-btn" class="Finish-btn" onclick="completeTutorial()">Finish Tutorial</a>
            </div>
        </div>
    </div>

    <script src="/MapaAyos/public/js/tutorial.js"></script>
</body>

</html>