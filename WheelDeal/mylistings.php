<?php 
include_once("header.php");
require("db_connect.php");
require("utilities.php")
?> 

<div class="container">

<h2 class="my-3">My listings</h2>

<?php
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !isset($_SESSION['user_id'])) {
    echo '<div class="alert alert-danger">Please log in to view your listings.</div>';
    echo '<div class="text-center"><a href="login.php" class="btn btn-primary">Log in</a></div>';
} else {
    $userId = $_SESSION['user_id'];

    // Prepare and execute the SQL statement using PDO
    $stmt = $pdo->prepare("SELECT * FROM Item WHERE userId = ? ORDER BY endTime DESC;");
    $stmt->execute([$userId]);

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!empty($result)) {
        // output data of each row
        echo '<div class="row justify-content-center">';
        echo '<div class="col-12 col-md-8">';
        echo '<ul class="list-group">';
        foreach ($result as $row) {
            $itemId = $row['itemId'];

            // Prepare and execute the SQL statement to get the highest bid
            $stmt2 = $pdo->prepare("SELECT MAX(amount) AS highest_bid FROM Bid WHERE itemId = ?;");
            $stmt2->execute([$itemId]);
            $highest_bid = $stmt2->fetch(PDO::FETCH_ASSOC)['highest_bid'] ?? 'No bids';

            print_listing_li(
              $row['itemId'], 
              $row['title'],
              $row['description'],
              $highest_bid, 
              0, ### todo implement number of bids here
              (new DateTime($row['endTime']))->format('Y-m-d H:i:s'), 
              $row['itemCondition'], 
              $row['tags'],
              $row['image']);
        }
        echo '</ul>';
        echo '</div>';
        echo '</div>';
    } else {
        echo "<p class='text-center'>No listings found.</p>";
    }
}
?>


<?php include_once("footer.php")?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="js/bootstrap.bundle.min.js"></script>