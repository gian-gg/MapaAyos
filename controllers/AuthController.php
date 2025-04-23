<?php
require_once __DIR__ . '/../models/UserModel.php';

function handleSignUp($firstName, $lastName, $email, $password)
{
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

    if (findUserByEmail($email)) {
        echo "Email already exists.";
        return;
    }

    if (signUp($firstName, $lastName, $email, $hashedPassword)) {
        echo "User registered successfully.";
        header("Location: ../views/SignIn.php"); // Redirect to sign-in page
        exit();
    } else {
        echo "Error signing up. Please try again.";
    }
}

function handleSignIn($email, $password)
{
    // Validation of Data
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format";
        return;
    }

    if (strlen($password) < 4) {
        echo "Password must be at least 4 characters.";
        return;
    }

    $user = findUserByEmail($email);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['userID'] = $user['id'];
        header("Location: ../views/dashboard/dashboard.php"); // Redirect dashboard
        exit();
    } else {
        echo "Invalid email or password.";
    }
}

function handleSignOut()
{
    session_unset(); // Unset all session variables
    session_destroy(); // Destroy the session

    header("Location: ../../index.php");
    exit();
}

function isAuthenticated()
{
    return isset($_SESSION['userID']);
}

function requireSignIn()
{
    if (!isAuthenticated()) {
        header("Location: ../../index.php");
        exit();
    }
}
