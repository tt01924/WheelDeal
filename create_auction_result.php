<?php include_once("header.php")?>

<div class="container my-5">

<?php
// TODO #1: Connect to MySQL database
require("db_connect.php");

// TODO #2: Extract form data into variables
$title = $_POST['auctionTitle'] ?? '';
$details = $_POST['auctionDetails'] ?? '';
$category = $_POST['auctionCategory'] ?? '';
$startPrice = $_POST['auctionStartPrice'] ?? '';
$reservePrice = $_POST['auctionReservePrice'] ?? $startPrice; // If no reserve price, use start price
$endDate = $_POST['auctionEndDate'] ?? '';

// Perform checking on the data
if (empty($title) || empty($category) || empty($startPrice) || empty($endDate)) {
    echo('<div class="text-center text-danger">Please fill in all required fields.</div>');
    exit();
}

if ($startPrice <= 0) {
    echo('<div class="text-center text-danger">Starting price must be greater than 0.</div>');
    exit();
}

if ($reservePrice < $startPrice) {
    echo('<div class="text-center text-danger">Reserve price cannot be less than starting price.</div>');
    exit();
}

// TODO #3: Insert data into the database
try {
    // Get user ID from session (assuming it's stored during login)
    if (!isset($_SESSION['user_id'])) {
        echo('<div class="text-center text-danger">You must be logged in to create an auction.</div>');
        exit();
    }
    
    $userId = $_SESSION['user_id'];
    
    // Format the end date for database
    $endDateTime = new DateTime($endDate);
    
    // Call your createAuction function
    $success = createAuction(
        $details,                              // description
        $endDateTime->format('Y-m-d H:i:s'),  // endTime
        $reservePrice,                        // reservePrice
        'New',                                // itemCondition (default)
        '',                                   // image (empty for now)
        $title,                               // Using title as tags for now
        $userId,                              // userId
        $category                             // categoryId
    );
    
    if ($success) {
        echo('<div class="text-center">Auction successfully created! <a href="browse.php">View your new listing.</a></div>');
    } else {
        echo('<div class="text-center text-danger">Failed to create auction. Please try again.</div>');
    }
} catch (PDOException $e) {
    echo('<div class="text-center text-danger">An error occurred while creating the auction.</div>');
}

?>

</div>

<?php include_once("footer.php")?>