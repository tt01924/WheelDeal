<?php
require_once 'mail_function_test.php';
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
    // get rating, seller ID, and item ID
    $rating = isset($_POST['rating']) ? intval($_POST['rating']) : -1;
    $item_id = isset($_POST['item_id']) ? intval($_POST['item_id']) : 0;
    $seller_id = isset($_POST['seller_id']) ? intval($_POST['seller_id']) : 0;

    // validate rating
    if ($rating < 0 || $rating > 5) {
        header("Location: listing.php?item_id=$item_id&ratingError=Invalid rating value");
        exit();
    }

    if (!$seller_id || $seller_id == 0) {
        header("Location: listing.php?item_id=$item_id&ratingError=Seller not found");
        exit();
    }

    $user_id = $_SESSION['user_id'];
    
    // check if there is no previous rating by the buyer for the seller on the same item
    $stmt = $conn->prepare("SELECT COUNT(*) FROM SellerRating WHERE buyerId = ? AND sellerId = ?");
    $stmt->bind_param("ii", $user_id, $seller_id);
    $stmt->execute();
    $stmt->bind_result($rating_count);
    $stmt->fetch();
    $stmt->close();

    if ($rating_count > 0) {
        header("Location: listing.php?item_id=$item_id&ratingError=You have already rated this seller.");
        exit();
    }

    // insert new rating into database
    $stmt = $conn->prepare("INSERT INTO SellerRating (sellerId, rating, buyerId, timeStamp) VALUES (?, ?, ?, ?)");

    $current_time = date('Y-m-d H:i:s');
    $stmt->bind_param("iiis", $seller_id, $rating, $user_id, $current_time);

    if ($stmt->execute()) {
        header("Location: listing.php?item_id=$item_id&ratingSuccess=Rating submitted successfully!");
    } else {
        header("Location: listing.php?item_id=$item_id&ratingError=Failed to submit rating.");
    }

    $stmt->close();
    $conn->close();
}
?>
