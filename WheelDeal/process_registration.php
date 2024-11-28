<?php
/*
* Filename: register_result.php
* Purpose: Process new user registration and set up session
* Dependencies: db_connect.php, user_interactions.php
* Flow: Validates input -> Creates account -> Sets session
*/


require("db_connect.php");
require("user_interactions.php");

session_start();

// Validate and sanitise input
// Pass through html special chars to avoid injections
$accountType = htmlspecialchars($_POST['accountType'] ?? '', ENT_QUOTES, 'UTF-8');
$email = htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8');
$password = htmlspecialchars($_POST['password'] ?? '', ENT_QUOTES, 'UTF-8');
$passwordConfirmation = htmlspecialchars($_POST['passwordConfirmation'] ?? '', ENT_QUOTES, 'UTF-8');
$phoneNumber = htmlspecialchars($_POST['phoneNumber'] ?? '', ENT_QUOTES, 'UTF-8');

// Basic validation
if (empty($accountType) || empty($email) || empty($password) || empty($passwordConfirmation) || empty($phoneNumber)) {
    echo('<div class="text-center text-danger">All fields are required. You will be redirected back.</div>');
    header("refresh:5;url=register.php");
    exit();
}

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo('<div class="text-center text-danger">Invalid email format. You will be redirected back.</div>');
    header("refresh:5;url=register.php");
    exit();
}

// Validate phone number format
if (!preg_match('/^\+?\d{0,11}$/', $phoneNumber)) {
    echo('<div class="text-center text-danger">Invalid phone number format. Only numbers and + are allowed, up to 10 digits. You will be redirected back.</div>');
    header("refresh:5;url=register.php");
    exit();
}

if ($password !== $passwordConfirmation) {
    echo('<div class="text-center text-danger">Passwords did not match. You will be redirected back.</div>');
    header("refresh:5;url=register.php");
    exit();
}

// Generate username from email (everything before @)
$username = strstr($email, '@', true);

// Create an account using registerUser function
try {
    $success = registerUser($username, $password, $email, $phoneNumber, $accountType);
    
    if ($success) {
        // Set session variables like in login
        $_SESSION['logged_in'] = true;
        $_SESSION['username'] = $username;
        $_SESSION['phoneNumber'] = $phoneNumber;
        $_SESSION['account_type'] = $accountType;
        $_SESSION['user_id'] = getUserId($username);
        
        echo('<div class="text-center text-success">Registration successful! You will be redirected shortly.</div>');
        header("refresh:3;url=index.php");
    } else {
        echo('<div class="text-center text-danger">Registration failed. Username or e-mail already exist.</div>');
        header("refresh:3;url=register.php");
    }
} catch (PDOException $e) {
    echo('<div class="text-center text-danger">Registration failed. Please contact a system administrator.</div>');
    echo('<div class="text-center text-danger">Database Error: ' . $e->getMessage() . '</div>');
    header("refresh:10;url=register.php"); 
}
?>