<?php
require_once __DIR__ . '/../config/db.php';

class UserModel
{
    private $pdo;

    public function __construct()
    {
        global $pdo; // from db.php
        $this->pdo = $pdo;
    }

    public function signUp($firstName, $lastName, $email, $hashedPassword) // a function for signing up a user
    {
        $sql = "INSERT INTO users (firstName, lastName, email, password) 
                VALUES (:firstName, :lastName, :email, :password)";

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindParam(':firstName', $firstName);
        $stmt->bindParam(':lastName', $lastName);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);

        return $stmt->execute();
    }

    public function findUserByEmail($email) // a function to find a user by email
    {
        $sql = "SELECT * FROM users WHERE email = :email LIMIT 1";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
