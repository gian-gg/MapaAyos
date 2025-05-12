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

function getBaranggayInfo($baranggayID)
{
    global $pdo;
    $sql = "SELECT * FROM baranggayInfo WHERE baranggayID = :baranggayID";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':baranggayID', $baranggayID);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC);
}
