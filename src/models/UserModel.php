<?php
require_once __DIR__ . '/../../config/db.php';

function signUp($firstName, $lastName, $email, $hashedPassword) // a function for signing up a user
{
    global $pdo;
    try {
        $sql = "INSERT INTO users (firstName, lastName, email, password) 
                VALUES (:firstName, :lastName, :email, :password)";

        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(':firstName', $firstName);
        $stmt->bindParam(':lastName', $lastName);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);

        return $stmt->execute();
    } catch (PDOException $e) {
        error_log("Error signing up user: " . $e->getMessage());
        return false; // can change this to throw a custom exception if needed
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

function getAllUsers() // a function to get all users
{
    global $pdo;
    try {
        $sql = "SELECT * FROM users";

        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error getting all users: " . $e->getMessage());
        return false;
    }
}

function getAllUsersByRole($role) // a function to get all users by role
{
    global $pdo;
    try {
        if ($role === "all") {
            $sql = "SELECT * FROM users";
            $stmt = $pdo->prepare($sql);
        } else {
            $sql = "SELECT * FROM users WHERE role = :role";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':role', $role);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error getting all users by role: " . $e->getMessage());
        return false;
    }
}

function getAllBaranggayOfficials() // a function to get all baranggay officials
{
    global $pdo;
    try {
        $sql = "SELECT * FROM officialsInfo";

        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error getting all baranggay officials: " . $e->getMessage());
        return false;
    }
}


function updateUserRole($userID, $newRole, $assignedBaranggay) // a function to update a user
{
    global $pdo;

    if ($assignedBaranggay === "none") {
        $assignedBaranggay = null; // Set to null if "none" is selected
    }

    try {
        $sql = "UPDATE users SET role = :role, assignedBaranggay = :assignedBaranggay WHERE id = :id";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':role', $newRole);
        $stmt->bindParam(':assignedBaranggay', $assignedBaranggay);
        $stmt->bindParam(':id', $userID);
        $stmt->execute();

        return true;
    } catch (PDOException $e) {
        error_log("Error updating user role: " . $e->getMessage());
        return false;
    }
}
