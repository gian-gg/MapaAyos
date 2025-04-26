<?php
require_once __DIR__ . '/../config/db.php';

function registerReport($lat, $lng, $title, $description, $status, $createdBy) // a function for signing up a user
{
    global $pdo;
    $sql = "INSERT INTO reports (lat, lng, title, description, status, createdBy) 
                VALUES (:lat, :lng, :title, :description, :status, :createdBy)";

    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':lat', $lat);
    $stmt->bindParam(':lng', $lng);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':status', $status);
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
