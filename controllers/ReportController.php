<?php
require_once __DIR__ . '/../models/ReportModel.php';

function handleRegisterReport($lat, $lng, $title, $description, $createdBy)
{
    // Validation of Data
    if (empty($title) && empty($description)) {
        echo "Title & Description are required.";
        return;
    }

    if (empty($lat) && empty($lng)) {
        echo "Location is required.";
        return;
    }

    // verify if report is already registered

    $status = 'pending'; // default status

    if (!(registerReport($lat, $lng, $title, $description, $status, $createdBy))) {
        echo "script>alert('Error signing up. Please try again.');</script>";
        return;
    }
}
