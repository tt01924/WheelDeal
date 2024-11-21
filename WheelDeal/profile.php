<?php 
include_once("header.php");
require("utilities.php");
require("db_connect.php");

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<div class="container">

<h2 class="my-3">My profile</h2>

<?php
// Check user authentication
if (!isset($_SESSION['logged_in']) || !isset($_SESSION['user_id'])) {
    echo '<div class="alert alert-danger">Please log in to view your profile.</div>';
    echo '<div class="text-center"><a href="login.php" class="btn btn-primary">Log in</a></div>';
} else {
    $userId = $_SESSION['user_id'];
    
    try {
        // Get profile info from database
        $sql = "SELECT U.* 
                FROM User U";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$userId]);
        $watchedItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
           
    } catch (PDOException $e) {
        echo '<div class="alert alert-danger">An error occurred while retrieving your profile.</div>';
    }
}
?>

</div>

<?php include_once("footer.php")?>