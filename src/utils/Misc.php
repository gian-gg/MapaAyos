<?php

function getRelativeDate($dateTimeString)
{
    $inputDate = new DateTime(substr($dateTimeString, 0, 10)); // extract date part only
    $today = new DateTime();
    $today->setTime(0, 0, 0); // normalize to midnight
    $inputDate->setTime(0, 0, 0);

    $diff = (int)$today->diff($inputDate)->format("%R%a");

    return match ($diff) {
        0 => "Today",
        -1 => "Yesterday",
        1 => "Tomorrow",
        default => $diff < 0 ? abs($diff) . " days ago" : "In $diff days",
    };
}

function capitalizeFirstLetter($string)
{
    return ucfirst(strtolower($string));
}


function getStatusColor($status)
{
    return match ($status) {
        'resolved' => 'blue',
        'active' => 'green',
        'pending' => 'yellow',
        'inactive' => 'red',
    };
}
