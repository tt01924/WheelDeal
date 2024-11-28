<?php
/*
* File: watchlist.php
* Purpose: Display and manage user's watched auction items
* Dependencies: header.php, utilities.php, db_connect.php
* Flow: Check user authentication -> Retrieve watchlist items -> Display items with remove options -> Handle empty states
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

<h2 class="my-3">My watchlist</h2>

<?php
// Check user authentication
if (!isset($_SESSION['logged_in']) || !isset($_SESSION['user_id'])) {
    echo '<div class="alert alert-danger">Please log in to view your watchlist.</div>';
    echo '<div class="text-center"><a href="login.php" class="btn btn-primary">Log in</a></div>';
} else {
    $userId = $_SESSION['user_id'];
    
    try {
        // Get watched items from database
        $sql = "SELECT i.*, MAX(b.amount) AS amount, COUNT(b.bidId) AS num_bids
                FROM Item i
                JOIN WatchListEntry we ON i.itemId = we.itemId
                JOIN WatchList w ON we.watchListId = w.watchListId
                LEFT JOIN Bid b ON i.itemId = b.itemId
                WHERE w.userId = ?
                GROUP BY i.itemId";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$userId]);
        $watchedItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (empty($watchedItems)) {
            echo '<div class="alert alert-info">Your watchlist is empty.<br>Browse some items <a href="browse.php">here</a>.</div>';
        } else {
            echo '<ul class="list-group">';
            foreach ($watchedItems as $item) {
                
                // Add remove from watchlist button
                $removeButton = '
                <form method="post" action="remove_from_watchlist.php" style="display: inline;">
                    <input type="hidden" name="itemId" value="' . $item['itemId'] . '">
                    <button type="submit" class="btn btn-sm btn-danger float-right">Remove from watchlist</button>
                </form>';

                $endDate = new DateTime($item['endTime']);

                ### set highest bid or start price as current price
                $highest_bid = isset($item['amount']) ? $item['amount'] : 0;
                $current_price = max($highest_bid, $item['startPrice']);

                print_listing_li(
                    $item['itemId'], 
                    $item['title'],
                    $item['description'],
                    $current_price,
                    $item['num_bids'],
                    (new DateTime($item['endTime']))->format('Y-m-d H:i:s'), 
                    $item['itemCondition'], 
                    $item['tags'], 
                    $item['image']);
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