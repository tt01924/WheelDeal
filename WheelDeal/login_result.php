<?php
// Include the database connection and functions
require("db_connect.php");
require("user_interactions.php");

session_start();

// Extract POST variables and attempt login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if email and password were provided
    if (isset($_POST['email']) && isset($_POST['password'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];
        
        // Use your loginUser function to attempt login
        $user = loginUser($email, $password);
        
        if ($user) {
            // Login successful
            $_SESSION['logged_in'] = true;
            $_SESSION['username'] = strstr($email, '@', true);  // Store username for display
            $_SESSION['user_id'] = $user['userId'];
            $_SESSION['account_type'] = $user['userType'];
            
            echo('<div class="text-center">You are now logged in! You will be redirected shortly.</div>');
            
            // Redirect to index after 5 seconds
            header("refresh:5;url=index.php");
        } else {
            // Login failed
            echo('<div class="text-center text-danger">Invalid email or password. Please try again.</div>');
            header("refresh:5;url=login.php");
        }
    } else {
        echo('<div class="text-center text-danger">Please provide both email and password.</div>');
        header("refresh:5;url=login.php");
    }
}
?>