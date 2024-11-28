<?php
/*
* Filename: logout.php
* Purpose: Ends user session and cleans up session data
* Dependencies: None
* Flow: Clears session -> Deletes cookie -> Redirects to index
*/

session_start();

// Clear all session data
unset($_SESSION['logged_in']);
unset($_SESSION['account_type']);

// Remove session cookie
setcookie(session_name(), "", time() - 360);

// Destroy session
session_destroy();


// Redirect to homepage
header("Location: index.php");

?>