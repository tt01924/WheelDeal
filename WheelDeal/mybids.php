<?php
include_once("header.php");
require("utilities.php");
require("db_connect.php");

// start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

?>
<div class="container mt-4"></div>
 <h2 class="text-center mb-4">My Bids</h2>

<?php

if (!isset($_SESSION['logged_in']) || !isset($_SESSION['user_id'])) {
  echo '<div class="alert alert-danger">Please log in to view your watchlist.</div>';
  echo '<div class="text-center"><a href="login.php" class="btn btn-primary">Log in</a></div>';
} else {
  $userId = $_SESSION['user_id'];

  $sql = "SELECT Item.*, 
                 MAX(Bid.amount) AS amount, 
                 COUNT(Bid.bidId) AS num_bids
          FROM Bid
          LEFT JOIN Item
          ON Bid.itemId = Item.itemId
          WHERE Bid.userId = ?
          GROUP BY Item.itemId;";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$userId]);

  $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

  if (!empty($result)) {
      echo "<p class='text-center'>You have placed bids on " . count($result) . " items.</p>";
      // output data of each row
      echo '<div class="row justify-content-center">';
      echo '<div class="col-12 col-md-8">';
      echo '<ul class="list-group">';
      foreach ($result as $row) {
        print_listing_li(
          $row['itemId'], 
          $row['title'],
          $row['description'],
          $row['amount'],
          $row['num_bids'],
          (new DateTime($row['endTime']))->format('Y-m-d H:i:s'), 
          $row['itemCondition'], 
          $row['tags'],
          $row['image']);
      }
      echo '</ul>';
      echo '</div>';
      echo '</div>';
    } else {
      echo "<p class='text-center'>No bids found.</p>";
    }
  }
?>


</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="js/bootstrap.bundle.min.js"></script>