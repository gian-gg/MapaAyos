<?php
class UserSettingsManager {
    private $pdo;
    public function __construct($pdo) {
        $this->pdo = $pdo; // Initialize the PDO connection
    }
    
    // Update the user's name and email
    public function updateNameAndEmail($userID, $firstName, $lastName, $email) {
        // Sanitize inputs
        $firstName = htmlspecialchars(strip_tags(trim($firstName)));
        $lastName = htmlspecialchars(strip_tags(trim($lastName)));
        $email = filter_var(trim($email), FILTER_SANITIZE_EMAIL);

        // Validate inputs
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return 'Invalid email format.';
        } elseif (strlen($firstName) < 2) {
            return 'First name must be at least 2 characters.';
        } elseif (strlen($lastName) < 2) {
            return 'Last name must be at least 2 characters.';
        } elseif (strlen($firstName) > 50) {
            return 'First name must be less than 50 characters.';
        } elseif (strlen($lastName) > 50) {
            return 'Last name must be less than 50 characters.';
        } elseif (strlen($email) > 100) {
            return 'Email must be less than 100 characters.';
        } else {
            try {
                $stmt = $this->pdo->prepare("UPDATE users SET firstName = ?, lastName = ?, email = ? WHERE id = ?");
                $stmt->execute([$firstName, $lastName, $email, $userID]);
                return 'Profile updated successfully!';
            } catch (PDOException $e) {
                error_log("Error updating user profile: " . $e->getMessage());
                return 'An error occurred while updating your profile.';
            }
        }
    }
} 