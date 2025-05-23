<?php
require_once __DIR__ . '/../models/BaranggayModel.php';
date_default_timezone_set('Asia/Manila');

function handleBaranggayUpdate($baranggayID, $description, $population, $landArea, $phone, $email, $address, $weekdayHours, $saturdayHours)
{
    $description = htmlspecialchars(trim($description), ENT_QUOTES, 'UTF-8');
    $population = filter_var($population, FILTER_SANITIZE_NUMBER_INT);

    $landArea = filter_var($landArea, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $landArea = str_replace(',', '', $landArea); // Remove commas for float conversion
    $landArea = filter_var($landArea, FILTER_VALIDATE_FLOAT);

    $phone = htmlspecialchars(trim($phone), ENT_QUOTES, 'UTF-8');
    $email = filter_var(trim($email), FILTER_SANITIZE_EMAIL);
    $address = htmlspecialchars(trim($address), ENT_QUOTES, 'UTF-8');
    $weekdayHours = htmlspecialchars(trim($weekdayHours), ENT_QUOTES, 'UTF-8');
    $saturdayHours = htmlspecialchars(trim($saturdayHours), ENT_QUOTES, 'UTF-8');

    // Validate input
    if (empty($description) || empty($population) || empty($landArea) || empty($phone) || empty($email) || empty($address)) {
        return json_encode(['status' => 'error', 'message' => 'All fields are required.']);
    }

    // Update baranggay information
    $baranggayData = getBaranggayData($baranggayID);
    if ($baranggayData) {
        $baranggayInfo = getBaranggayInfo($baranggayID);
        if ($baranggayInfo) {
            updateBaranggay($baranggayID, $description, $population, $landArea, $phone, $email, $address, $weekdayHours, $saturdayHours);
            return true;
        } else {
            return false; // Baranggay info not found
        }
    } else {
        return false; // Baranggay not found
    }
}
