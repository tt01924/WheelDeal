<?php include_once("header.php")?>

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
    
            // assigning variables by the POST method and inserting them into the database.
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $auctionTitle = $_POST["auctionTitle"];
        $auctionDetails = $_POST["auctionDetails"];
        $itemCondition = $_POST["itemCondition"];
        $auctionCategory = $_POST["auctionCategory"];
        $itemTags = $_POST["itemTags"];
        $auctionStartPrice = $_POST["auctionStartPrice"];
        $auctionReservePrice = $_POST["auctionReservePrice"];
        $auctionEndDate = $_POST["auctionEndDate"];
        $auctionEndDateFormatted = str_replace("T", " ", $auctionEndDate);
        echo $auctionEndDate;
        $sql = "INSERT INTO Item (title, details, itemCondition, tags, startPrice, reservePrice, endDate, itemImage)
    VALUES ('$auctionTitle', '$auctionDetails', '$itemCondition', '$itemTags', '$auctionStartPrice', '$auctionReservePrice', REPLACE('$auctionEndDateFormatted', 'T', ' '), '/Pictures123/bike.png')";
        
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

