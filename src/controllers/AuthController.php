<?php
require_once __DIR__ . '/../models/UserModel.php';


function handleSignUp($firstName, $lastName, $email, $password)
{
    // Sanitize Inputs
    $firstName = htmlspecialchars(trim($firstName));
    $lastName = htmlspecialchars(trim($lastName));
    $email = filter_var(trim($email), FILTER_SANITIZE_EMAIL);

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
        header("Location: /MapaAyos/signin"); // Redirect to sign-in page
        exit();
    } else {
        echo "Error signing up. Please try again.";
    }
}

function handleSignIn($email, $password)
{
    // Sanitize Inputs
    $email = filter_var(trim($email), FILTER_SANITIZE_EMAIL);

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
        session_regenerate_id(true);
        $_SESSION['userID'] = $user['id'];

        header("Location: /MapaAyos/" . $user["role"] . "/dashboard"); // Redirect Dashboard

        exit();
    } else {
        echo "Invalid email or password.";
    }
}

function handleSignOut()
{
    session_unset(); // Unset all session variables
    session_destroy(); // Destroy the session

    header("Location: /MapaAyos/");
    exit();
}

function isAuthenticated()
{
    return isset($_SESSION['userID']);
}

function requireSignIn()
{
    if (!isAuthenticated()) {
        header("Location: /MapaAyos/");
        exit();
    }
}

function redirectIfNotAllowed($userRole, $pageRole)
{
    if (isAuthenticated()) {
        if ($userRole == "all" && ($pageRole == "signup" || $pageRole == "signin")) {
            header("Location: /MapaAyos/");
            exit();
        } else if ($userRole != $pageRole) {
            header("Location: /MapaAyos/" . $userRole . "/dashboard");
            exit();
        }
    }
}
