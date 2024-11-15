

<?php
// connecting to the database using a UNIX socket. Obtain from connection parameters on MAMP webstart.

$host = 'localhost';
$db   = 'WheelDeal';
$user = 'root';
$password = 'root';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    die("Database connection failed. Please try again later.");
}

//   NOTE TO DEV: We have a conflict here with the above code and below code that has been commented out. If you run into problems, try uncommenting this code to fix.
  
//   $db_host = 'localhost';
//   $db_user = 'root';
//   $db_password = 'root';
//   $db_db = 'mydatabase';
 
//   $mysqli = @new mysqli(
//     $db_host,
//     $db_user,
//     $db_password,
//     $db_db
//   );
	
//   if ($mysqli->connect_error) {
//     echo 'Errno: '.$mysqli->connect_errno;
//     echo '<br>';
//     echo 'Error: '.$mysqli->connect_error;
//     exit();
//   }

//   echo 'Success: A proper connection to MySQL was made.';
//   echo '<br>';
//   echo 'Host information: '.$mysqli->host_info;
//   echo '<br>';
//   echo 'Protocol version: '.$mysqli->protocol_version;

//   $mysqli->close();

?>