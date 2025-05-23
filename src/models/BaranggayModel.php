<?php
require_once __DIR__ . '/../../config/db.php';

function getBaranggays()
{
    global $pdo;
    $sql = "SELECT * FROM baranggays";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getBaranggayByName($baranggayName)
{
    global $pdo;
    $sql = "SELECT * FROM baranggays WHERE name = :name";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':name', $baranggayName);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getBaranggayData($baranggayID)
{
    global $pdo;
    $sql = "SELECT * FROM baranggays WHERE id = :baranggayID";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':baranggayID', $baranggayID);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getBaranggayInfo($baranggayID)
{
    global $pdo;
    $sql = "SELECT * FROM baranggayInfo WHERE baranggayID = :baranggayID";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':baranggayID', $baranggayID);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function updateBaranggay($baranggayID, $description, $population, $landArea, $phone, $email, $address, $operating_hours_weekdays, $operating_hours_saturday)
{
    global $pdo;
    $sql = "UPDATE baranggayInfo SET description = :description, population = :population, landArea = :landArea, phone = :phone, email = :email, address = :address, operating_hours_weekdays = :operating_hours_weekdays, operating_hours_saturday = :operating_hours_saturday WHERE baranggayID = :baranggayID";

    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':population', $population);
    $stmt->bindParam(':landArea', $landArea);
    $stmt->bindParam(':phone', $phone);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':address', $address);
    $stmt->bindParam(':operating_hours_weekdays', $operating_hours_weekdays);
    $stmt->bindParam(':operating_hours_saturday', $operating_hours_saturday);
    $stmt->bindParam(':baranggayID', $baranggayID);

    return $stmt->execute();
}
