<?php
/*
* Filename: mybids.php
* Purpose: Display user's bid history and current bid status
* Dependencies: header.php, utilities.php, db_connect.php
* Flow: Validates login -> Shows bids -> Links to listings
*/


include_once("header.php");
require("utilities.php");
require("db_connect.php");

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

?>
<div class="container mt-4"></div>
 <h2 class="text-center mb-4">My Bids</h2>

<?php

// Check login status
if (!isset($_SESSION['logged_in']) || !isset($_SESSION['user_id'])) {
  echo '<div class="alert alert-danger">Please log in to view your watchlist.</div>';
  echo '<div class="text-center"><a href="login.php" class="btn btn-primary">Log in</a></div>';
} else {
  $userId = $_SESSION['user_id'];

  // Get items the user has bid on
  $sqlUserBids = "SELECT Item.*, 
                         MAX(Bid.amount) AS amount, 
                         MAX(Bid.timeStamp) AS latest_bid_time
                  FROM Bid
                  LEFT JOIN Item
                  ON Bid.itemId = Item.itemId
                  WHERE Bid.userId = ?
                  GROUP BY Item.itemId
                  ORDER BY latest_bid_time DESC;";
  
  // Execute query and display results
  $stmtUserBids = $pdo->prepare($sqlUserBids);
  $stmtUserBids->execute([$userId]);

  $userBidsResult = $stmtUserBids->fetchAll(PDO::FETCH_ASSOC);

  if (!empty($userBidsResult)) {
      echo "<p class='text-center'>You have placed bids on " . count($userBidsResult) . " items.</p>";
      // Output data of each row
      echo '<div class="row justify-content-center">';
      echo '<div class="col-12 col-md-8">';
      echo '<ul class="list-group">';
      foreach ($userBidsResult as $row) {
        $sqlCountBids = "SELECT COUNT(bidId) AS num_bids FROM Bid WHERE itemId = ?";
        $stmtCountBids = $pdo->prepare($sqlCountBids);
        $stmtCountBids->execute([$row['itemId']]);
        $numBids = $stmtCountBids->fetchColumn();

        print_listing_li(
          $row['itemId'], 
          $row['title'],
          $row['description'],
          $row['amount'],
          $numBids,
          (new DateTime($row['endTime']))->format('Y-m-d H:i:s'), 
          $row['itemCondition'], 
          $row['tags'],
          $row['image']);
      }
      echo '</ul>';
      echo '</div>';
      echo '</div>';
    } else {
      echo "<p class='text-center'>No bids found. <br> Browse some items <a href='browse.php'>here</a>.</p>";
    }
  }
?>


</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="js/bootstrap.bundle.min.js"></script>


</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="js/bootstrap.bundle.min.js"></script>