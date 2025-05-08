<?php
require_once __DIR__ . '/../models/ReportModel.php';

function handleRegisterReport($lat, $lng, $title, $description, $createdBy)
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

    $reports = getReportsById($createdBy);
    $currentDate = date('Y-m-d');
    $reports = array_filter($reports, function ($report) use ($currentDate) {
        return date('Y-m-d', strtotime($report['createdAt'])) === $currentDate;
    });

    if (count($reports) >= 3) {
        echo "<script>alert('You have reached the maximum number of daily reports.');</script>";
        return;
    }

    if (!(registerReport($lat, $lng, $title, $description, $createdBy))) {
        echo "script>alert('Error signing up. Please try again.');</script>";
        return;
    }
}

function getAllReportsController()
{
    return getAllReports();
}
