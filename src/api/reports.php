<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../models/ReportModel.php';

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
    case 'getReports':
        if (!isset($_GET['status'])) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Status not specified.'
            ]);
            exit;
        }

        $status = $_GET['status'];

        $reports = getReports($status);
        if (empty($reports)) {
            echo json_encode([
                'status' => 'error',
                'message' => 'No reports found.'
            ]);
            exit;
        } else {
            echo json_encode([
                'status' => 'success',
                'reports' => $reports
            ]);
        }
        break;

    case 'getReportsByUserID':
        if (!isset($_GET['userID'])) {
            echo json_encode([
                'status' => 'error',
                'message' => 'userID not specified.'
            ]);
            exit;
        }

        $userID = $_GET['userID'];
        $reports = getReportsById($userID);
        if (empty($reports)) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Report not found.'
            ]);
            exit;
        } else {
            echo json_encode([
                'status' => 'success',
                'reports' => $reports
            ]);
        }
        break;

    default:
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid mode.'
        ]);
        exit;
}
