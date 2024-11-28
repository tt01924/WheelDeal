<?php
require_once 'mail_function_test.php';
require_once 'user_interactions.php';
require_once 'db_connect.php';

// start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['logged_in']) || $_SESSION['account_type'] != 'buyer') {
    header('Location: browse.php');
    exit();
}
?>

<?php
// check for form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // get bid amount, reserve price and item ID
    $bid_amount = isset($_POST['bid']) ? floatval($_POST['bid']) : 0;
    $reserve_price = isset($_POST['reserve_price']) ? floatval($_POST['reserve_price']) : 0;
    $item_id = isset($_POST['item_id']) ? intval($_POST['item_id']) : 0;
    $is_highest_bidder = isset($_POST['is_highest_bidder']) ? intval($_POST['is_highest_bidder']) : false;
    // echo $bid_amount . "<br>";
    // echo $item_id . "<br>";

    // validate bid amount
    if ($bid_amount <= 0 || $bid_amount > 9999) {
        header("Location: listing.php?item_id=$item_id&error=Invalid bid amount");
        exit();
    }

    // database connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // check if bid is higher than current max bid
    $stmt = $conn->prepare("SELECT MAX(amount) FROM Bid WHERE itemId = ?");
    $stmt->bind_param("i", $item_id);
    $stmt->execute();
    $stmt->bind_result($current_highest_bid);
    $stmt->fetch();
    $current_highest_bid = ($current_highest_bid === null) ? 0 : $current_highest_bid;
    $stmt->close();

    if ($bid_amount <= $current_highest_bid) {
        header("Location: listing.php?item_id=$item_id&error=Insufficient bid amount.");
        exit();
    } elseif ($bid_amount < $reserve_price) {
        header("Location: listing.php?item_id=$item_id&error=Bid below reserve price, please try increasing your bid.");
        exit();
    } elseif ($is_highest_bidder) {
        header("Location: listing.php?item_id=$item_id&error=You are already the highest bidder for this item.");
        exit();
    } else {
        // insert new bid into database
        $stmt = $conn->prepare("INSERT INTO Bid (itemId, amount, userId, timeStamp) VALUES (?, ?, ?, ?)");
        $user_id = $_SESSION['user_id'];

        // use current time for the bid
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
