<?php
require_once __DIR__ . '/../../config/db.php';

function signUp($firstName, $lastName, $email, $hashedPassword) // a function for signing up a user
{
    global $pdo;
    try {
        $pdo->beginTransaction();

        // Insert into users table
        $sql = "INSERT INTO users (firstName, lastName, email, password) 
                VALUES (:firstName, :lastName, :email, :password)";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':firstName', $firstName);
        $stmt->bindParam(':lastName', $lastName);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->execute();

        // Get the new user's ID
        $userID = $pdo->lastInsertId();

        // Create user_preferences entry
        $sql = "INSERT INTO user_preferences (user_id) VALUES (:user_id)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':user_id', $userID);
        $stmt->execute();

        $pdo->commit();
        return true;
    } catch (PDOException $e) {
        $pdo->rollBack();
        error_log("Error signing up user: " . $e->getMessage());
        return false;
    }
}

function findUserByEmail($email) // a function to find a user by email
{
    global $pdo;
    try {
        $sql = "SELECT * FROM users WHERE email = :email LIMIT 1";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error finding user by email: " . $e->getMessage());
        return false;
    }
}

function findUserByID($userID) // a function to find a user by ID
{
    global $pdo;
    try {
        $sql = "SELECT * FROM users WHERE id = :id LIMIT 1";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $userID);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error finding user by ID: " . $e->getMessage());
        return false;
    }
}

function getBaranggayOfficial($userID) // a function to get the baranggay of a user
{
    global $pdo;
    try {
        $sql = "SELECT * FROM officialsInfo WHERE userID = :userID LIMIT 1";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':userID', $userID);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error getting baranggay official: " . $e->getMessage());
        return false;
    }
}
