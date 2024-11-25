<?php
// Include database connection and functions
require("db_connect.php");
require("user_interactions.php");

session_start();

// Extract $_POST variables, check they're OK
$accountType = $_POST['accountType'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$passwordConfirmation = $_POST['passwordConfirmation'] ?? '';
$phoneNumber = $_POST['phoneNumber'] ?? '';

// Basic validation
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

// Generate username from email (everything before @)
$username = strstr($email, '@', true);

// Attempt to create an account using your registerUser function
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
        echo('<div class="text-center text-danger">Registration failed. Please try again.</div>');
        header("refresh:3;url=register.php");
    }
} catch (PDOException $e) {
    // Show the actual error message for debugging
    echo('<div class="text-center text-danger">Database Error: ' . $e->getMessage() . '</div>');
    header("refresh:10;url=register.php");  // Giving more time to read the error
}

// Add debug information
echo('<div class="text-muted mt-3">
    <small>
    Debug Info:<br>
    Username: ' . htmlspecialchars($username) . '<br>
    Email: ' . htmlspecialchars($email) . '<br>
    PhoneNumber: ' . htmlspecialchars($phoneNumber) . '<br>
    Account Type: ' . htmlspecialchars($accountType) . '<br>
    </small>
</div>');
?>