<?php
/*
* Filename: db_connect.php
* Purpose: Establishes database connection for the auction website
* Dependencies: None
* Flow: Sets up PDO connection parameters and creates a connection object
*/

// Database connection parameters
$host = 'localhost';
$db   = 'WheelDeal';
$user = 'root';
$password = 'root';

// Attempt database connection with error handling
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    die("Database connection failed. Please try again later.");
}


    $conn = new mysqli($host, $user, $password, $db);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

?>