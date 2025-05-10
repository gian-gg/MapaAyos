<?php
require_once __DIR__ . '/../models/ReportModel.php';
date_default_timezone_set('Asia/Manila');

function hasReachedMaxReports($userID)
{
    $maxReports = 3; // Maximum number of reports allowed

    $reports = getReportsById($userID);
    $currentDate = date('Y-m-d');
    $reports = array_filter($reports, function ($report) use ($currentDate) {
        return date('Y-m-d', strtotime($report['createdAt'])) === $currentDate;
    });

    return count($reports) >= $maxReports;
}

function handleRegisterReport($lat, $lng, $title, $description, $filePath, $createdBy)
{ // all returns and echo are for temporary testing
    // Sanitize Input
    $lat = htmlspecialchars(trim($lat));
    $lng = htmlspecialchars(trim($lng));
    $title = htmlspecialchars(trim($title));
    $description = htmlspecialchars(trim($description));

    // Validation of Data
    if (empty($title) && empty($description)) {
        echo "Title & Description are required.";
        return;
    }

    if (empty($lat) && empty($lng)) {
        echo "Location is required.";
        return;
    }

    if (!(registerReport($lat, $lng, $title, $description, $filePath, $createdBy))) {
        echo "script>alert('Error signing up. Please try again.');</script>";
        return;
    }
}
