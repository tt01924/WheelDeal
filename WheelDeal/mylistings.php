<?php 
$_SESSION['logged_in'] = true;
$_SESSION['account_type'] = 'buyer';
$_SESSION['user_id'] = 1;

include_once("header.php")?>
<?php require("utilities_mylistings.php")?>

<div class="container">

<h2 class="my-3">My listings</h2>

<?php
  // This page is for showing a user the auction listings they've made.
  // It will be pretty similar to browse.php, except there is no search bar.
  // This can be started after browse.php is working with a database.
  // Feel free to extract out useful functions from browse.php and put them in
  // the shared "utilities.php" where they can be shared by multiple files.
  

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
  FROM Item
  WHERE userId = ?
  ORDER BY endTime DESC;");
$stmt->bind_param("i", $userId);

// temporary variable ** TO BE UPDATED *** once session variable for userId is set
$userId = 4;
$stmt->execute();

$result = $stmt->get_result();

$stmt2 = $conn->prepare("SELECT MAX(amount) AS highest_bid
FROM Bid
WHERE itemId = ?;");


if ($result->num_rows > 0) {
    // output data of each row
    echo '<div class="row justify-content-center">';
    echo '<div class="col-12 col-md-8">';
    echo '<ul class="list-group">';
    while($row = $result->fetch_assoc()) {
      $itemId = $row['itemId'];
      $stmt2->bind_param("i", $itemId);
      $stmt2->execute();
      $bid_result = $stmt2->get_result();
      $highest_bid = $bid_result->fetch_assoc()['highest_bid'] ?? 'No bids';
      
      print_listing_li($row['itemId'], $row['title'], $row['description'], $row['itemCondition'], $highest_bid, $row['endTime'], $row['image']);
    }
    echo '</ul>';
    echo '</div>';
    echo '</div>';
  } else {
    echo "<p class='text-center'>No bids found.</p>";
  }
$stmt->close();
$stmt2->close();
$conn->close();

  
  // TODO: Check user's credentials (cookie/session).
  
  // TODO: Perform a query to pull up their auctions.
  
  // TODO: Loop through results and print them out as list items.
  
?>

<?php include_once("footer.php")?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="js/bootstrap.bundle.min.js"></script>