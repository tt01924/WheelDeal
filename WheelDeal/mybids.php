<?php 
include_once("header.php");
require("utilities.php");
require("db_connect.php");

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<div class="container">

<h2 class="my-3">My bids</h2>

<?php
// Check user's credentials
if (!isset($_SESSION['logged_in']) || !isset($_SESSION['user_id'])) {
    echo '<div class="alert alert-danger">Please log in to view your bids.</div>';
    echo '<div class="text-center"><a href="login.php" class="btn btn-primary">Log in</a></div>';
} else {
    $userId = $_SESSION['user_id'];
    
    // Get user's bids
    try {
        $bids = getUserBids($userId);
        
        if (empty($bids)) {
            echo '<div class="alert alert-info">You haven\'t placed any bids yet.</div>';
        } else {
            echo '<ul class="list-group">';
            
            foreach ($bids as $bid) {
                // Get current highest bid for this item
                $highestBid = getCurrentHighestBid($bid['itemId']);
                
                // Check if auction has ended
                $endDate = new DateTime($bid['endTime']);
                $now = new DateTime();
                
                if ($endDate <= $now) {
                    // Auction has ended, check outcome
                    $outcome = checkAuctionOutcome($bid['itemId']);
                    if ($outcome['highestBidder'] == $userId) {
                        $status = '<span class="text-success">Won!</span>';
                    } else {
                        $status = '<span class="text-danger">Lost</span>';
                    }
                } else {
                    // Auction still ongoing
                    if ($highestBid == $bid['amount']) {
                        $status = '<span class="text-success">Highest Bidder</span>';
                    } else {
                        $status = '<span class="text-warning">Outbid</span>';
                    }
                }
                
                // Format bid information for display using the utility function
                print_listing_li(
                    $bid['itemId'],
                    $bid['description'],
                    "Your bid: £" . number_format($bid['amount'], 2) . " | Current highest: £" . number_format($highestBid, 2) . " | Status: " . $status,
                    $highestBid,
                    0,  // num_bids could be added with another function if needed
                    $endDate
                );
            }
            
            echo '</ul>';
        }
    } catch (PDOException $e) {
        echo '<div class="alert alert-danger">An error occurred while retrieving your bids.</div>';
    }
}
?>

</div>

<?php include_once("footer.php")?>