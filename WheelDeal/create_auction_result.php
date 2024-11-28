<?php
/*
* Filename: create_auction_result.php
* Purpose: Processes form data from create_auction.php to insert new auctions into the database
* Dependencies: utilities.php, db_connect.php
* Flow: Validates user login -> Processes image upload -> Creates auction record
*/

require("utilities.php");
require("db_connect.php");

// Starts session if not already started
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

?>


<div class="container my-5">

<?php

// Ensures user is logged in so that they can view their specific watchlist

    if (!isset($_SESSION['logged_in']) || !isset($_SESSION['user_id'])) {
    echo '<div class="alert alert-danger">Please log in to view your watchlist.</div>';
    echo '<div class="text-center"><a href="login.php" class="btn btn-primary">Log in</a></div>';
    } else {
        
        $errors = [];

        $uploadOk = 1; ## default to upload

        // Set up image upload parameters
        $target_dir = "image_uploads/";
        $basename = basename($_FILES["itemImage"]["name"]);
        
        if (empty($basename)) {
            $basename = "wheel.png";
            $uploadOk = 2; ## set to two to indicate that default file was chosen
        }
        $target_file = $target_dir . $basename;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is an actual image or a fake image

        if (isset($_POST["submit"]) && $uploadOk !== 2) {
            $check = getimagesize($_FILES["itemImage"]["tmp_name"]);
            if ($check !== false) {
                echo "File is an image - " . $check["mime"] . ".";
                $uploadOk = 1;
            } else {
                $errors[] = "File is not an image.";
                $uploadOk = 0;
            }
        }

        // check file size
        if ($_FILES["itemImage"]["size"] > 500000) {
            $errors[] = "Sorry, your file is too large.";
            $uploadOk = 0;
        }
        
        // only allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            $errors[] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }
        
        // Check if file already exists to prevent duplicate file names
        if (file_exists($target_file) && $uploadOk !== 2) {
            $file_counter = 1;
            $new_target_file = $target_dir . pathinfo($target_file, PATHINFO_FILENAME) . '_' . $file_counter . '.' . $imageFileType;
            while (file_exists($new_target_file)) {
                $file_counter++;
                $new_target_file = $target_dir . pathinfo($target_file, PATHINFO_FILENAME) . '_' . $file_counter . '.' . $imageFileType;
            }
            $target_file = $new_target_file;
        }

        // Check if $uploadOk is set to 0 by an error - file upload success/failure
        if ($uploadOk === 0) {
            $errors[] = "Sorry, your file was not uploaded.";
        } elseif ($uploadOk === 2) {
        } else { // if everything is ok, upload file
            if (!move_uploaded_file($_FILES["itemImage"]["tmp_name"], $target_file)) {
                $errors[] = "Sorry, there was an error uploading your file.";
            }
        }
        
        // Assigning variables by the POST method and inserting them into the database
        if ($_SERVER["REQUEST_METHOD"] =="POST") {

            // Validate auctionTitle
            if (empty($_POST["auctionTitle"])) {
                $errors[] = "Auction title is required.";
            } else {
                $auctionTitle = testInput($_POST["auctionTitle"]);
            }

            // Validate itemCondition
            if (empty($_POST["itemCondition"]) || $_POST["itemCondition"] === "Choose...") {
                $errors[] = "Item condition is required.";
            } else {
                $itemCondition = testInput($_POST["itemCondition"]);
            }

            // Validate auctionCategory
            if (empty($_POST["auctionCategory"]) || $_POST["auctionCategory"] === "Choose...") {
                $errors[] = "Auction category is required.";
            } else {
                // Map category string to numeric value
                $categoryMapping = [
                    "bike" => 1,
                    "accessories" => 2,
                    "parts" => 3,
                    "apparel" => 4
                ];
                
                $categoryInput = testInput($_POST["auctionCategory"]);
                if (array_key_exists($categoryInput, $categoryMapping)) {
                    $auctionCategory = $categoryMapping[$categoryInput];
                } else {
                    $errors[] = "Invalid category selected.";
                }
            }

            // Validate auctionStartPrice
            if (empty($_POST["auctionStartPrice"])) {
                $errors[] = "Auction starting price is required.";
            } elseif (!is_numeric($_POST["auctionStartPrice"]) || $_POST["auctionStartPrice"] < 0) {
                $errors[] = "Auction starting price must be a positive number.";
            } else {
                $auctionStartPrice = testInput($_POST["auctionStartPrice"], "price");
            }

            // Validate auctionReservePrice
            if (!empty($_POST["auctionReservePrice"])) {
                if (!is_numeric($_POST["auctionReservePrice"]) || $_POST["auctionReservePrice"] < 0) {
                    $errors[] = "Auction reserve price must be a positive number.";
                } else {
                    $auctionReservePrice = testInput($_POST["auctionReservePrice"], "price");
                }
            }

            // Validate auctionEndTime
            if (empty($_POST["auctionEndDate"])) {
                $errors[] = "Auction end time is required.";
            } else {
                $auctionEndTime = testInput($_POST["auctionEndDate"], "datetime");
                $auctionEndTimeFormatted = str_replace("T", " ", $auctionEndTime);
                $currentDateTime = new DateTime();
                $currentDateTimeFormatted = $currentDateTime->format('Y-m-d H:i:s');
                
                // Valid auctionEndTime is in the future
                if ($auctionEndTimeFormatted <= $currentDateTimeFormatted) {
                $errors[] = "Auction end time must be in the future.";
            }
            }
            
            // If there are any errors present, display them
            if (!empty($errors)) {
                $_SESSION['errors'] = $errors;
                header('Location: create_auction.php');
                exit();
            } else {

            $userId = $_SESSION['user_id'];
            $auctionDecription = testInput($_POST["auctionDetails"]);
            $itemTags = testInput($_POST["itemTags"]);
            
            $auctionTimeCreated = date("Y-m-d H:i:s");
            $auctionImage = $target_file;

            // SQL to insert item
            $sql = "INSERT INTO Item (userId, categoryId, title, description, itemCondition, tags, startPrice, reservePrice, timeCreated, endTime, image)
                    VALUES (:userId, :auctionCategory, :auctionTitle, :auctionDecription, :itemCondition, :itemTags, :auctionStartPrice, :auctionReservePrice, :auctionTimeCreated, :auctionEndTimeFormatted, :auctionImage)";
            // This is the what happens when session variable is set
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':userId', $userId);
            $stmt->bindParam(':auctionCategory', $auctionCategory);
            $stmt->bindParam(':auctionTitle', $auctionTitle);
            $stmt->bindParam(':auctionDecription', $auctionDecription);
            $stmt->bindParam(':itemCondition', $itemCondition);
            $stmt->bindParam(':itemTags', $itemTags);
            $stmt->bindParam(':auctionStartPrice', $auctionStartPrice);
            $stmt->bindParam(':auctionReservePrice', $auctionReservePrice);
            $stmt->bindParam(':auctionTimeCreated', $auctionTimeCreated);
            $stmt->bindParam(':auctionEndTimeFormatted', $auctionEndTimeFormatted);
            $stmt->bindParam(':auctionImage', $auctionImage);

            if ($stmt->execute()) {
                $lastInsertId = $pdo->lastInsertId();
                echo('<div class="text-center">Auction successfully created! <a href="listing.php?item_id=' . $lastInsertId . '">View your new listing.</a></div>');
            } else {
                echo "Error: " . $stmt->errorInfo()[2];
            }
            }
        }
    }

?>
</div>
<!-- Attaching the footer file at the bottom of the file -->
<?php include_once("footer.php")?>