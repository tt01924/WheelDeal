<?php
/*
* Filename: login_result.php
* Purpose: Process login form submission and set session variables
* Dependencies: db_connect.php, user_interactions.php
* Flow: Validates credentials -> Sets session -> Redirects user
*/
?>

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
        
        // Use loginUser function to attempt login
        $user = loginUser($email, $password);
        
        if ($user) {
            // Login successful
            $_SESSION['logged_in'] = true;
            $_SESSION['username'] = strstr($email, '@', true);  // Store username for display
            $_SESSION['user_id'] = $user['userId'];
            $_SESSION['account_type'] = $user['userType'];
            
            echo('<div class="text-center">You are now logged in! You will be redirected shortly.</div>');
            
            // redirect after 3s
            header("refresh:3;url=index.php");
        } else {
            // Login failed
            echo('<div class="text-center text-danger">Invalid email or password. Please try again.</div>');
            header("refresh:3;url=login.php");
        }
    } else {
        // If there are missing credentials
        echo('<div class="text-center text-danger">Please provide both email and password.</div>');
        header("refresh:3;url=login.php");
    }
}
?>