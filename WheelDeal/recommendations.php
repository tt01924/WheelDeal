<?php
/*
 * File: recommendations.php
 * Purpose: Provides personalised item recommendations based on user bidding history and interests
 * Dependencies: header.php, utilities.php, db_connect.php
 * Flow: Check user login -> Get recommendations from database -> Display recommended items or login prompt
 */


include_once("header.php");
require("utilities.php");
require("db_connect.php");

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
?>

<div class="container">

<h2 class="my-3">Recommendations for you</h2>

<?php 

if (!isset($_SESSION['logged_in']) || !isset($_SESSION['user_id'])) {
    echo '<div class="alert alert-danger">Please log in to view your recommendations.</div>';
} elseif (isset($_SESSION['account_type']) && $_SESSION['account_type'] === 'seller') {
    echo '<div class="alert alert-info">You are logged in as a seller. Please switch to a buyer account to view item recommendations.</div>';
} else {
  echo '<p><em>These recommendations are generated for you based on what similar users have bid on and the categories of items that you\'ve showed interest in.</em></p>';


  // Get user ID from session
  $userId = $_SESSION['user_id'];

  try {

    // Get recommendations for user
    $recommendations = recommendItems($userId);
    if (!empty($recommendations)) {
        // Start list for recommendations
        echo '<ul class="list-group">';
        // iterate through recommendations and output using print_listing
        foreach ($recommendations as $item) {
            // get current price from highest bid or startprice
            $currentPrice = getCurrentHighestBid($item['itemId']) ?: $item['startPrice'];
            $endDate = new DateTime($item['endTime']);

            // display item listing
            print_listing_li(
              $item['itemId'], 
              $item['title'],
              $item['description'],
              $currentPrice, 
              $item['num_bids'],
              (new DateTime($item['endTime']))->format('Y-m-d H:i:s'), 
              $item['itemCondition'], 
              $item['image']);
            
        } 
        echo '</ul>';
    } else {
      
      // Show message if no recommendations
      echo '<div class="alert alert-info">No recommendations yet...<br>Browse some items and start bidding <a href="browse.php">here</a> and then come back later!</div>';
    }
  } catch (PDOException $e) {
    // Handle database errors
    echo $e->getMessage();
    echo '<div class="alert alert-danger">An error occurred while retrieving recommendations.</div>';
  }
}


?>
