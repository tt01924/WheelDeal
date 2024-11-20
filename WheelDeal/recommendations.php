<?php 
include_once("header.php");
require("utilities.php");
require("db_connect.php");

// start session if not already started
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
?>

<div class="container">

<h2 class="my-3">Recommendations for you</h2>
<p><em>These recommendations are generated for you based on the categories and tags of items you've bid on or added to your watchlist.</em></p>

<?php 

if (!isset($_SESSION['logged_in']) || !isset($_SESSION['user_id'])) {
    echo '<div class="alert alert-danger">Please log in to view your recommendations.</div>';
    echo '<div class="text-center"><a href="login.php" class="btn btn-primary">Log in</a></div>';
} else {
  $userId = $_SESSION['user_id'];

  try {
    // Get watched items from database
    $sql = "SELECT i.* 
            FROM Item i 
            JOIN WatchListEntry we ON i.itemId = we.itemId
            JOIN WatchList w ON we.watchListId = w.watchListId
            WHERE w.userId = ?";
    $command = $pdo->prepare($sql);
    $command->execute([$userId]);
    $watchedItems = $command->fetchAll(PDO::FETCH_ASSOC);
    
    $recommendations = recommendItems($userId);
    if (!empty($recommendations)) {
        echo '<ul class="list-group">';
        foreach ($recommendations as $item) {
            $currentPrice = getCurrentHighestBid($item['itemId']) ?: $item['reservePrice'];
            $bids = getCurrentBid($item['itemId']) ?: $item['reservePrice'];
            $endDate = new DateTime($item['endTime']);
            
            print_listing_li(
              $item['itemId'], 
              $item['title'],
              $item['description'],
              $currentPrice, 
              $bids,
              (new DateTime($item['endTime']))->format('Y-m-d H:i:s'), 
              $item['itemCondition'], 
              $item['tags'],
              $item['image']);
            
        }
        echo '</ul>';
    }
  } catch (PDOException $e) {
    echo $e->getMessage();
    echo '<div class="alert alert-danger">An error occurred while retrieving recommendations.</div>';
  }
}


?>

<?php
  // TODO: Check user's credentials (cookie/session).
  
  // TODO: Perform a query to pull up auctions they might be interested in.
  
  // TODO: Loop through results and print them out as list items.
  
?>