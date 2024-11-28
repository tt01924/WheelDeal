<?php ///////// connecting to the database
    //  obtain details from MAMP
    $host = 'localhost';
    $db   = 'WheelDeal';
    $user = 'root';
    $password = 'root';

    // opening connection to database
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

