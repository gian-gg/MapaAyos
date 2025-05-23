<?php
session_start();
require_once __DIR__ . '/../models/UserModel.php';

if (!isset($_SESSION['userID'])) {
    header("Location: /MapaAyos/signin");
    exit;
}

$userID = $_SESSION['userID'];

if (updateFirstLoginStatus($userID)) {
    header("Location: /MapaAyos/mapa");
    exit;
} else {
    header("Location: /MapaAyos/src/views/404.php");
    exit;
}