<?php
require_once __DIR__ . '/../models/UserModel.php';

class UserController
{
    public function signUp()
    {
        $firstName = htmlspecialchars(trim($_POST['firstNameInput']));
        $lastName = htmlspecialchars(trim($_POST['lastNameInput']));
        $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
        $password = htmlspecialchars(trim($_POST['password']));

        // Validation of Data
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "Invalid email format";
            return;
        }

        if (strlen($firstName) < 2 || strlen($lastName) < 2) {
            echo "First and Last name must be at least 2 characters.";
            return;
        }

        if (strlen($password) < 6) {
            echo "Password must be at least 6 characters.";
            return;
        }

        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $userModel = new UserModel();

        if ($userModel->findUserByEmail($email)) {
            echo "Email already exists.";
            return;
        }

        if ($userModel->signUp($firstName, $lastName, $email, $hashedPassword)) {
            echo "User registered successfully.";
            header("Location: ../views/SignIn.php"); // Redirect to sign-in page
            exit();
        } else {
            echo "Error signing up. Please try again.";
        }
    }
}
