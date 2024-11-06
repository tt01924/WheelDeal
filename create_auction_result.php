<?php include_once("header.php")?>

<div class="container my-5">

<?php

// This function takes the form data and adds the new auction to the database.

/* TODO #1: Connect to MySQL database (perhaps by requiring a file that
            already does this). */

    // Connecting to the database
    require 'db_connect.php';

/* TODO #2: Extract form data into variables. Because the form was a 'post'
            form, its data can be accessed via $POST['auctionTitle'], 
            $POST['auctionDetails'], etc. Perform checking on the data to
            make sure it can be inserted into the database. If there is an
            issue, give some semi-helpful feedback to user. */

    // Using the post method to extract data from the create_auction.php form
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $auctionTitle = $_POST['auctionTitle'] ?? '';
        $auctionDetails = $_POST['auctionDetails'] ?? '';
        $auctionCategory = $_POST['auctionCategory'] ?? '';
        $auctionStartPrice = $_POST['auctionStartPrice'] ?? '';
        $auctionReservePrice = $_POST['auctionReservePrice'] ?? '';
        $auctionEndDate = $_POST['auctionEndDate'] ?? '';
    }

    $errors = [];

    // Validating the parameters entered to make sure they can be safely inserted into the database
    if (empty($auctionTitle) || strlen($auctionTitle > 100)) {
        $errors[] = "Please provide an auction title which is between 1-100 character long.";
    }
    if (empty($auctionDetails) || strlen($auctionDetails) > 400) {
        $errors[] = "Please provide details on your auction item, up to 400 characters.";
    }
    $allowedCategories = ["Bikes", "Electric Bikes", "Clothing & Helmets", "Accessories", "Maintenance"];

    if (empty($auctionCategory) || $auctionCategory === "Choose..." || strlen($auctionCategory) > 100) {
        $errors[] = "Please choose a valid auction category from the drop down menu.";
    }
    if (!is_numeric($auctionStartPrice) || $auctionStartPrice < 0 || $auctionStartPrice > 1000000000) {
        $errors[] = "Please choose a valid, start price which is greater than or equal to zero.";
    } 
    if (!is_numeric($auctionReservePrice) || $auctionReservePrice < 0 || $auctionReservePrice > 1000000000) {
        $errors[] = "Please choose a valid, reserve price which is greater than or equal to zero.";
    } 
    if (empty($auctionEndDate) || strtotime($auctionEndDate) <= time()) {
        $errors[] = "Please provide an end date which is in the future.";
    }

    
    //Need to sync up table with prepare statement

/* TODO #3: If everything looks good, make the appropriate call to insert
            data into the database. */
    
    // printing out errors for the user to see
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<p>$error</p>";
        }
    } else {
        $insertAuctionStmt = $mysqli->prepare("INSERT INTO Item (title, description, categoryId, StartPrice, reservePrice, endTime) VALUES (?, ?, ?, ?, ?, ?)");
        $insertAuctionStmt->bind_param("ssddis", $auctionTitle, $auctionDetails, $auctionCategory, $auctionStartPrice, $auctionReservePrice, $auctionEndDate);
        
        if ($insertAuctionStmt->execute()) {
            echo "<p>Item successfully added and auction created.</p>"
        } else {
            echo "<p>Unable to create auction and add item. Please try again.</p>"
        }
    }
    

// If all is successful, let user know.
echo('<div class="text-center">Auction successfully created! <a href="FIXME">View your new listing.</a></div>');


?>

</div>


<?php include_once("footer.php")?>


$insertAuctionStmt->close();
$mysqli->close();