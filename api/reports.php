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
    case 'getAll': // MapaAyos/api/reports.php?mode=getAll
        $reports = getAllReports();
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

    case 'getByUserID': // MapaAyos/api/reports.php?mode=getByUserID&userID=<ID>
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
