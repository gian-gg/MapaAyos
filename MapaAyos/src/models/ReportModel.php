<?php
require_once __DIR__ . '/../../config/db.php';


function registerReport($lat, $lng, $title, $description, $createdBy) // a function for signing up a user
{
    global $pdo;
    $sql = "INSERT INTO reports (lat, lng, title, description, createdBy) 
                VALUES (:lat, :lng, :title, :description, :createdBy)";

    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':lat', $lat);
    $stmt->bindParam(':lng', $lng);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':createdBy', $createdBy);

    return $stmt->execute();
}

function getReportsById($userID)
{
    global $pdo;
    $sql = "SELECT * FROM reports WHERE createdBy = :userID";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':userID', $userID);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getReports($statusFilter)
{
    global $pdo;

    if ($statusFilter == "all") {
        $sql = "SELECT * FROM reports";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
    } else {
        $sql = "SELECT * FROM reports WHERE status = :statusFilter";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':statusFilter', $statusFilter);
        $stmt->execute();
    }


    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
