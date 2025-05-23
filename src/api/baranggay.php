<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../models/BaranggayModel.php';

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
    case 'getAllBaranggays':
        $baranggayData = getBaranggays();

        if (empty($baranggayData)) {
            echo json_encode([
                'status' => 'error',
                'message' => 'No baranggay found.'
            ]);
            exit;
        } else {
            echo json_encode([
                'status' => 'success',
                'data' => $baranggayData
            ]);
        }
        break;
    case 'getBaranggayByName':
        if (!isset($_GET['baranggay'])) {
            echo json_encode([
                'status' => 'error',
                'message' => 'baranggay not specified.'
            ]);
            exit;
        }

        $baranggay = $_GET['baranggay'];

        $baranggayData = getBaranggayByName($baranggay);

        if (empty($baranggayData)) {
            echo json_encode([
                'status' => 'error',
                'message' => 'No baranggay found.'
            ]);
            exit;
        } else {
            echo json_encode([
                'status' => 'success',
                'data' => $baranggayData
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
