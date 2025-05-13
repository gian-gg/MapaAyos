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
        header("Location: /MapaAyos/signup?error=" . urlencode("Invalid email format"));
        exit();
    }

    if (strlen($firstName) < 2 || strlen($lastName) < 2) {
        header("Location: /MapaAyos/signup?error=" . urlencode("First and Last name must be at least 2 characters"));
        exit();
    }

    if (strlen($password) < 6) {
        header("Location: /MapaAyos/signup?error=" . urlencode("Password must be at least 6 characters"));
        exit();
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    if (findUserByEmail($email)) {
        header("Location: /MapaAyos/signup?error=" . urlencode("Email already exists"));
        exit();
    }

    if (signUp($firstName, $lastName, $email, $hashedPassword)) {
        header("Location: /MapaAyos/signin?success=" . urlencode("User registered successfully"));
        exit();
    } else {
        header("Location: /MapaAyos/signup?error=" . urlencode("Error signing up. Please try again"));
        exit();
    }
}

function handleSignIn($email, $password)
{
    // Sanitize Inputs
    $email = filter_var(trim($email), FILTER_SANITIZE_EMAIL);

    // Validation of Data
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: /MapaAyos/signin?error=" . urlencode("Invalid email format"));
        exit();
    }

    if (strlen($password) < 4) {
        header("Location: /MapaAyos/signin?error=" . urlencode("Password must be at least 4 characters"));
        exit();
    }

    $user = findUserByEmail($email);

    if ($user && password_verify($password, $user['password'])) {
        session_regenerate_id(true);
        $_SESSION['userID'] = $user['id'];

        if ($user["role"] == "user") {
            header("Location: /MapaAyos/mapa");
        } else {
            header("Location: /MapaAyos/" . $user["role"] . "/dashboard");
        }
        exit();
    } else {
        header("Location: /MapaAyos/signin?error=" . urlencode("Invalid email or password"));
        exit();
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
