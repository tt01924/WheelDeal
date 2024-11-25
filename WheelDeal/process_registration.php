<?php
require("db_connect.php");
require("user_interactions.php");

session_start();

// extract $_POST variables, check they're OK
$accountType = $_POST['accountType'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$passwordConfirmation = $_POST['passwordConfirmation'] ?? '';
$phoneNumber = $_POST['phoneNumber'] ?? '';

// basic validation
if (empty($accountType) || empty($email) || empty($password) || empty($passwordConfirmation) || empty($phoneNumber)) {
    echo('<div class="text-center text-danger">All fields are required. You will be redirected back.</div>');
    header("refresh:5;url=register.php");
    exit();
}

if ($password !== $passwordConfirmation) {
    echo('<div class="text-center text-danger">Passwords do not match. You will be redirected back.</div>');
    header("refresh:5;url=register.php");
    exit();
}

// generate username from email (everything before @)
$username = strstr($email, '@', true);

// create an account using registerUser function
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