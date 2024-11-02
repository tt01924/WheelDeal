
// Parameters for connecting to the database
$host = 'localhost';
$db = 'WheelDeal';
$user = 'root';
$password = '';

// Creating a connection with the database
$connection = new mysqli($host, $user, $password, $db);

// Checking the connection with the database
if ($connection->connect_error) {
    error_log("Connection failed: " . $connection->connect_error);
    die("Database connection failed. Please try again later.");
}

// Setting the character set for the connection to UTF-8 
// for handling special characters correctly
$connection->set_charset("utf8");
