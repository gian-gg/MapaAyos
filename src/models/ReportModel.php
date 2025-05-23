<?php
require_once __DIR__ . '/../../config/db.php';


function registerReport($lat, $lng, $baranggay, $title, $description, $imagePath, $createdBy) // a function for signing up a user
{
    global $pdo;
    $sql = "INSERT INTO reports (lat, lng,baranggay, title, description, imagePath, createdBy) 
                VALUES (:lat, :lng, :baranggay, :title, :description, :imagePath, :createdBy)";

    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':lat', $lat);
    $stmt->bindParam(':lng', $lng);
    $stmt->bindParam(':baranggay', $baranggay);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':imagePath', $imagePath);
    $stmt->bindParam(':createdBy', $createdBy);

    return $stmt->execute();
}

function updateReport($reportID, $status, $comment, $moderatedBy)
{
    global $pdo;

    $sql = "UPDATE reports SET status = :status, comment = :comment, moderatedBy = :moderatedBy WHERE id = :reportID";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':comment', $comment);
    $stmt->bindParam(':moderatedBy', $moderatedBy);
    $stmt->bindParam(':reportID', $reportID);

    return $stmt->execute();
}

function getReportsById($userID, $statusFilter)
{
    global $pdo;

    if ($statusFilter == "all") {
        $sql = "SELECT * FROM reports WHERE createdBy = :userID";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':userID', $userID);
        $stmt->execute();
    } else if ($statusFilter == "pending") {
        $sql = "SELECT * FROM reports WHERE createdBy = :userID AND status = 'pending'";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':userID', $userID);
        $stmt->execute();
    } else if ($statusFilter == "active") {
        $sql = "SELECT * FROM reports WHERE createdBy = :userID AND status = 'active'";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':userID', $userID);
        $stmt->execute();
    }

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getReportById($reportID)
{
    global $pdo;

    $sql = "SELECT * FROM reports WHERE id = :reportID";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':reportID', $reportID);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC);
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

function getReportsData($baranggay, $statusFilter)
{
    global $pdo;

    if ($statusFilter == "all") {
        $sql = "SELECT * FROM reports WHERE baranggay = :baranggay";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':baranggay', $baranggay);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    if ($statusFilter == "!pending") {
        $sql = "SELECT * FROM reports WHERE baranggay = :baranggay AND status != 'pending'";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':baranggay', $baranggay);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    $sql = "SELECT * FROM reports WHERE baranggay = :baranggay AND status = :statusFilter";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':baranggay', $baranggay);
    $stmt->bindParam(':statusFilter', $statusFilter);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
