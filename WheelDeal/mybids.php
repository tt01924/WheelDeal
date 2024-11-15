<?php
// temporary variables to simulate a buyer logged in ** TO DELETE ***
$_SESSION['logged_in'] = true;
$_SESSION['account_type'] = 'buyer';
$_SESSION['user_id'] = 1;

include("header.php");?>
<?php require("utilities_myBids.php")?>

<div class="container mt-4"></div>
 <h2 class="text-center mb-4">My Bids</h2>

<?php
// Database connection variables
$servername = "localhost"; // MAMP default server address
$username = "root";        // MAMP default username
$password = "root";        // MAMP default password
$dbname = "WheelDeal";     // Your database name
$socket = "/Applications/MAMP/tmp/mysql/mysql.sock";
$port = 8889;

// Create connection with socket
$conn = new mysqli($servername, $username, $password, $dbname, $port, $socket);

// Check connection
if ($conn->connect_error) {
      die("<p class='text-danger'>Connection failed: " . $conn->connect_error . "</p>");
}

$stmt = $conn->prepare("SELECT *
  FROM Bid
  LEFT JOIN Item
  ON Bid.itemId = Item.itemId
  WHERE Bid.userId = ?
  ORDER BY Bid.timeStamp DESC;");
$stmt->bind_param("i", $userId);

// temporary variable ** TO BE UPDATED *** once session variable for userId is set
$userId = 4;
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // output data of each row
    echo '<div class="row justify-content-center">';
    echo '<div class="col-12 col-md-8">';
    echo '<ul class="list-group">';
    while($row = $result->fetch_assoc()) {
      print_listing_li($row['itemId'], $row['title'], $row['description'], $row['amount'], $row['timeStamp']);
    }
    echo '</ul>';
    echo '</div>';
    echo '</div>';
  } else {
    echo "<p class='text-center'>No bids found.</p>";
  }

$conn->close();
?>


</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="js/bootstrap.bundle.min.js"></script>