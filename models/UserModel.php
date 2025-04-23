<?php
require_once __DIR__ . '/../config/db.php';

function signUp($firstName, $lastName, $email, $hashedPassword) // a function for signing up a user
{
    global $pdo;
    $sql = "INSERT INTO users (firstName, lastName, email, password) 
                VALUES (:firstName, :lastName, :email, :password)";

    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':firstName', $firstName);
    $stmt->bindParam(':lastName', $lastName);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashedPassword);

    return $stmt->execute();
}

function findUserByEmail($email) // a function to find a user by email
{
    global $pdo;
    $sql = "SELECT * FROM users WHERE email = :email LIMIT 1";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function findUserByID($userID) // a function to find a user by ID
{
    global $pdo;
    $sql = "SELECT * FROM users WHERE id = :id LIMIT 1";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $userID);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC);
}
