<?php
/*
* Filename: place_bid.php
* Purpose: Process bid submissions and validate bid amounts
* Dependencies: mail_function_test.php, user_interactions.php
* Flow: Validates bid -> Checks conditions -> Places bid -> Notifies watchers
*/


require_once 'mail_function_test.php';
require_once 'user_interactions.php';
require_once 'db_connect.php';
require_once 'utilities.php';

// Start session if not already started and verify buyer status
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['logged_in']) || $_SESSION['account_type'] != 'buyer') {
    header('Location: browse.php');
    exit();
}
?>

<?php
// Check for form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // get bid amount, reserve price, start price and item ID
    $bid_amount = isset($_POST['bid']) ? floatval($_POST['bid']) : 0;
    $start_price = isset($_POST['start_price']) ? floatval($_POST['start_price']) : 0;
    $reserve_price = isset($_POST['reserve_price']) ? floatval($_POST['reserve_price']) : 0;
    $item_id = isset($_POST['item_id']) ? intval($_POST['item_id']) : 0;
    $is_highest_bidder = isset($_POST['is_highest_bidder']) ? intval($_POST['is_highest_bidder']) : false;
    // echo $bid_amount . "<br>";
    // echo $item_id . "<br>";

    // Validate bid amount
    if ($bid_amount <= 0 || $bid_amount > 9999) {
        header("Location: listing.php?item_id=$item_id&error=Invalid bid amount");
        exit();
    }

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if bid is higher than current max bid
    $highest_bid = getCurrentHighestBid($item_id);

    if ($bid_amount <= $highest_bid) {
        header("Location: listing.php?item_id=$item_id&error=Insufficient bid amount.");
        exit();
    } elseif ($bid_amount < $start_price) {
        header("Location: listing.php?item_id=$item_id&error=Bid below start price, please try increasing your bid.");
        exit();
    } elseif ($bid_amount < $reserve_price) {
        header("Location: listing.php?item_id=$item_id&error=Bid below reserve price, please try increasing your bid.");
        exit();
    } elseif ($is_highest_bidder) {
        header("Location: listing.php?item_id=$item_id&error=You are already the highest bidder for this item.");
        exit();
    } else {
        // Insert new bid into database
        $stmt = $conn->prepare("INSERT INTO Bid (itemId, amount, userId, timeStamp) VALUES (?, ?, ?, ?)");
        $user_id = $_SESSION['user_id'];

        // Use current time for the bid
        $current_time = date('Y-m-d H:i:s');
        $stmt->bind_param("idis", $item_id, $bid_amount, $user_id, $current_time);

        if ($stmt->execute()) {
            echo "Bid Amount: £" . number_format($bid_amount, 2) . "<br>";
            echo "Current Highest Bid: £" . number_format($current_highest_bid, 2) . "<br>";
            notifyWatchersOfNewBid($item_id, $bid_amount); // Notify watchers of the new bid
            header("Location: listing.php?item_id=$item_id&success=Bid placed successfully!");
        } else {
            header("Location: listing.php?item_id=$item_id&error=Failed to place bid.");
        }

        $stmt->close();
        $conn->close();
    }
}
?>
