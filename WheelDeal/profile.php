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

<h2 class="my-3">My profile</h2>

<?php
// Check user authentication
if (!isset($_SESSION['logged_in']) || !isset($_SESSION['user_id'])) {
    echo '<div class="alert alert-danger">Please log in to view your watchlist.</div>';
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
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$userId]);
        $watchedItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (empty($watchedItems)) {
            echo '<div class="alert alert-info">Your watchlist is empty.</div>';
            
            // Show recommended items
            $recommendations = recommendItems($userId);
            if (!empty($recommendations)) {
                echo '<h3 class="my-4">Recommended for you:</h3>';
                echo '<ul class="list-group">';
                foreach ($recommendations as $item) {
                    $currentPrice = getCurrentHighestBid($item['itemId']) ?: $item['reservePrice'];
                    $bids = getCurrentBid($item['itemId']) ?: $item['reservePrice'];
                    $endDate = new DateTime($item['endTime']);
                    
                    print_listing_li(
                        $item['itemId'],
                        $item['description'],
                        $item['description'],
                        $currentPrice,
                        $bids,
                        // 0, // num_bids could be added with another function
                        $endDate
                    );
                }
                echo '</ul>';
            }
        } else {
            echo '<ul class="list-group">';
            foreach ($watchedItems as $item) {
                $currentPrice = getCurrentHighestBid($item['itemId']) ?: $item['reservePrice'];
                $bids = getCurrentBid($item['itemId']) ?: $item['reservePrice'];
                $endDate = new DateTime($item['endTime']);
                
                // Add remove from watchlist button
                $removeButton = '
                <form method="post" action="remove_from_watchlist.php" style="display: inline;">
                    <input type="hidden" name="itemId" value="' . $item['itemId'] . '">
                    <button type="submit" class="btn btn-sm btn-danger float-right">Remove from watchlist</button>
                </form>';
                
                print_listing_li(
                    $item['itemId'],
                    $item['description'],
                    $item['description'] . $removeButton,
                    $currentPrice,
                    $bids,
                    // 0, // num_bids could be added with another function
                    $endDate
                );
            }
            echo '</ul>';
        }
        
    } catch (PDOException $e) {
        echo '<div class="alert alert-danger">An error occurred while retrieving your watchlist.</div>';
    }
}
?>

</div>

<?php include_once("footer.php")?>