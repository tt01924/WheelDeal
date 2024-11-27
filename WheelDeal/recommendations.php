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
<p><em>These recommendations are generated for you based on what similar users have bid on and the categories of items that you've showed interest in.</em></p>

<?php 

if (!isset($_SESSION['logged_in']) || !isset($_SESSION['user_id'])) {
    echo '<div class="alert alert-danger">Please log in to view your recommendations.</div>';
    echo '<div class="text-center"><a href="login.php" class="btn btn-primary">Log in</a></div>';
} else {
  $userId = $_SESSION['user_id'];

  try {
    /// use recommendItems from utilities.php to generate a list of recommendations
    $recommendations = recommendItems($userId);
    if (!empty($recommendations)) {
        echo '<ul class="list-group">';
        // iterate through recommendations and output using print_listing
        foreach ($recommendations as $item) {
            $currentPrice = getCurrentHighestBid($item['itemId']) ?: $item['reservePrice'];
            $endDate = new DateTime($item['endTime']);
            
            print_listing_li(
              $item['itemId'], 
              $item['title'],
              $item['description'],
              $currentPrice, 
              $item['num_bids'],
              (new DateTime($item['endTime']))->format('Y-m-d H:i:s'), 
              $item['itemCondition'], 
              $item['tags'],
              $item['image']);
            
        } 
        echo '</ul>';
    } else {
      echo '<div class="alert alert-info">No recommendations yet...<br>Browse some items and start bidding <a href="browse.php">here</a> and then come back later!</div>';
    }
  } catch (PDOException $e) {
    echo $e->getMessage();
    echo '<div class="alert alert-danger">An error occurred while retrieving recommendations.</div>';
  }
}


?>
