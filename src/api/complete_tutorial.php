<?php
session_start();
require_once __DIR__ . '/../models/UserModel.php';

if (!isset($_SESSION['userID'])) {
    header("Location: /signin");
    exit;
}

$userID = $_SESSION['userID'];

if (updateFirstLoginStatus($userID)) {
    header("Location: /mapa");
    exit;
} else {
    header("Location: /src/views/404.php");
    exit;
}
