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

    $target_dir = "image_uploads/";
    $target_file = $target_dir . basename($_FILES["itemImage"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    
    // Check if image file is a actual image or fake image
    if(isset($_POST["submit"])) {
      $check = getimagesize($_FILES["itemImage"]["tmp_name"]);
      if($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
      } else {
        echo "File is not an image.";
        $uploadOk = 0;
      }
    }
    
    // Check if file already exists
    if (file_exists($target_file)) {
      echo "Sorry, file already exists.";
      $uploadOk = 0;
    }
    
    // Check file size
    if ($_FILES["itemImage"]["size"] > 500000) {
      echo "Sorry, your file is too large.";
      $uploadOk = 0;
    }
    
    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
      echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
      $uploadOk = 0;
    }
    
    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
      echo "Sorry, your file was not uploaded.";
    // if everything is ok, try to upload file
    } else {
      if (move_uploaded_file($_FILES["itemImage"]["tmp_name"], $target_file)) {
        echo "The file ". htmlspecialchars( basename( $_FILES["itemImage"]["name"])). " has been uploaded.";
      } else {
        echo "Sorry, there was an error uploading your file.";
      }
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
        $auctionImage = testInput($target_file);
        // to create functionality which allows an image to be inserted into the database
        $sql = "INSERT INTO Item (userId, categoryId, title, description, itemCondition, tags, startPrice, reservePrice, timeCreated, endTime, image)
    VALUES ('$userId', '$auctionCategory', '$auctionTitle', '$auctionDecription', '$itemCondition', '$itemTags', '$auctionStartPrice', '$auctionReservePrice', '$auctionTimeCreated', REPLACE('$auctionEndTimeFormatted', 'T', ' '), '$auctionImage')";
        
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

