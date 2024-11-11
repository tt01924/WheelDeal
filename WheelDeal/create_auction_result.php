<?php 
// temporary variables to simulate a seller logged in ** TO DELETE ***
$_SESSION['logged_in'] = true;
$_SESSION['account_type'] = 'seller';
$_SESSION['user_id'] = 1;

include_once("header.php")
?>

<div class="container my-5">

<?php

// This function takes the form data and adds the new auction to the database.

/* TODO #1: Connect to MySQL database (perhaps by requiring a file that
            already does this). */

    // Database connection variables
    $servername = "localhost"; // MAMP default server address
    $username = "root";        // MAMP default username
    $password = "root";        // MAMP default password
    $dbname = "WheelDeal";     // Your database name
    $socket = "/Applications/MAMP/tmp/mysql/mysql.sock";
    $port = 8889;

    // Create connection with socket
    $conn = new mysqli($servername, $username, $password, $dbname, $port, $socket);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } else {
        echo "Successfully connected to the database.";
    }

/* TODO #2: Extract form data into variables. Because the form was a 'post'
            form, its data can be accessed via $POST['auctionTitle'], 
            $POST['auctionDetails'], etc. Perform checking on the data to
            make sure it can be inserted into the database. If there is an
            issue, give some semi-helpful feedback to user. */

    // combined with TODO #3

/* TODO #3: If everything looks good, make the appropriate call to insert
            data into the database. */
    
    function testInput($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

            // assigning variables by the POST method and inserting them into the database.
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // temporary variable (ID and Category) ** TO BE UPDATED *** once session variable for userId is set
        $userId = testInput(1);
        $auctionTitle = testInput($_POST["auctionTitle"]);
        $auctionDecription = testInput($_POST["auctionDetails"]);
        $itemCondition = testInput($_POST["itemCondition"]);
        $auctionCategory = testInput(1);
        $itemTags = testInput($_POST["itemTags"]);
        $auctionStartPrice = testInput($_POST["auctionStartPrice"]);
        $auctionReservePrice = testInput($_POST["auctionReservePrice"]);
        $auctionTimeCreated = testInput(date("Y-m-d H:i:s"));
        $auctionEndTime = testInput($_POST["auctionEndDate"]);
        $auctionEndTimeFormatted = str_replace("T", " ", $auctionEndTime);
        echo $auctionEndTime;
        // to create functionality which allows an image to be inserted into the database
        $sql = "INSERT INTO Item (userId, categoryId, title, description, itemCondition, tags, startPrice, reservePrice, timeCreated, endTime, image)
    VALUES ('$userId', '$auctionCategory', '$auctionTitle', '$auctionDecription', '$itemCondition', '$itemTags', '$auctionStartPrice', '$auctionReservePrice', '$auctionTimeCreated', REPLACE('$auctionEndTimeFormatted', 'T', ' '), '/Pictures123/bike.png')";
        
        if ($conn->query($sql) === TRUE) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
    }
    }
            
    $conn->close();

// If all is successful, let user know.
echo('<div class="text-center">Auction successfully created! <a href="FIXME">View your new listing.</a></div>');


?>

</div>


<?php include_once("footer.php")?>

