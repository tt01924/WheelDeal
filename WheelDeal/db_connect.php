<?php ///////// connecting to the database using a UNIX socket. Obtain from connection parameters on MAMP webstart.
    // Assigning parameters for use in the code
    $host = 'localhost';
    $db   = 'WheelDeal';
    $user = 'root';
    $password = 'root';

    // Calling out to the database
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
        die("Database connection failed. Please try again later.");
    }
?>

