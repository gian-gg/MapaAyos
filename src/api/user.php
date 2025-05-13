<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../models/UserModel.php';

define('API_TOKEN', 'mapaayos123'); // temporary token for development mweheh

// Get the token from the Authorization header
$headers = apache_request_headers();
$token = isset($headers['Authorization']) ? str_replace('Bearer ', '', $headers['Authorization']) : null;

if ($token !== API_TOKEN) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Unauthorized access. Invalid token.'
    ]);
    exit;
}

if (!isset($_GET['mode'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Mode not specified.'
    ]);
    exit;
}

$mode = $_GET['mode'];

switch ($mode) {
    case 'getUserByID':

        if (!isset($_GET['userID'])) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Mode not specified.'
            ]);
            exit;
        }

        $userID = $_GET['userID'];
        $userData = findUserByID($userID);
        if (empty($userData)) {
            echo json_encode([
                'status' => 'error',
                'message' => 'User not found.'
            ]);
            exit;
        } else {
            echo json_encode([
                'status' => 'success',
                'data' => $userData
            ]);
        }
        break;
    default:
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid mode specified.'
        ]);
        exit;
}
