<?php include_once("header.php")?>
<?php require("utilities.php")?>

<?php
  // Get info from the URL:
  $item_id = isset($_GET['item_id']) ? intval($_GET['item_id']) : 0;
  
  // Check if item_id is valid
  if ($item_id > 0) {
      // Establish a database connection
      $conn = new mysqli('localhost', 'root', 'root', 'WheelDeal');

      // Check connection
      if ($conn->connect_error) {
          die("Connection failed: " . $conn->connect_error);
      }

      // Prepare and execute the query
      $stmt = $conn->prepare("SELECT title, description, startPrice, endTime, image FROM Item WHERE itemId = ?");
      $stmt->bind_param("i", $item_id);
      $stmt->execute();
      $stmt->store_result();
      
      // Check if the item exists
      if ($stmt->num_rows > 0) {
          $stmt->bind_result($title, $description, $start_price, $end_time, $image);
          $stmt->fetch();
          $exists = True;
      } else {
          // Handle non-existent item_id
          $title = "Item not found";
          $description = "No description available.";
          $start_price = 0;
          $end_time = new DateTime();
          $image = 'wheel.png';
          $exists = False;
      }

      $stmt->close();
      $conn->close();
      
  } else {
      // Handle invalid item_id
      $title = "Invalid item";
      $description = "No description available.";
      $$start_price = 0;
      $end_time = new DateTime();
      $image = 'wheel.png';
      $exists = False;
  }

  if ($exists) {
    ### convert end_time to DateTime object
    $end_time = DateTime::createFromFormat('Y-m-d H:i:s', $end_time);
    
    ## fetch num of results from database
    $conn = new mysqli('localhost', 'root', 'root', 'WheelDeal');

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    $stmt = $conn->prepare("SELECT bidId FROM Bid WHERE itemId = ?");
    $stmt->bind_param("i", $item_id);
    $stmt->execute();
    $stmt->store_result();
    $num_bids = $stmt->num_rows; 

    $stmt = $conn->prepare("SELECT MAX(amount) FROM Bid WHERE itemId = ?");
    $stmt->bind_param("i", $item_id);
    $stmt->execute();
    $stmt->bind_result($current_price);
    $stmt->fetch();

    $stmt->close();
    $conn->close();

    if (is_null($image) || !file_exists($image)) {
        $image = 'wheel.png';
    }
    // TODO: Note: Auctions that have ended may pull a different set of data,
    //       like whether the auction ended in a sale or was cancelled due
    //       to lack of high-enough bids. Or maybe not.
    
    // Calculate time to auction end:
    $now = new DateTime();
    // Check if the current time is before the auction end time
    if ($now < $end_time) { 
      $time_to_end = date_diff($now, $end_time);
      $time_remaining = ' (in ' . display_time_remaining($time_to_end) . ')';
    } else {
      $time_remaining = ' (Auction has ended)';
    }
    
    // TODO: If the user has a session, use it to make a query to the database
    //       to determine if the user is already watching this item.
    //       For now, this is hardcoded.
    $has_session = true;
    $watching = false;
  }
?>


<div class="container">
  <div class="row"> <!-- Row #1 with auction title + watch button -->
    <div class="col-sm-8"> <!-- Left col -->
      <h2 class="my-3"><?php echo($title); ?></h2>
    </div>
    <div class="col-sm-4 align-self-center"> <!-- Right col -->
      <?php if ($exists && $now < $end_time): ?>
      <div id="watch_nowatch" <?php if ($has_session && $watching) echo('style="display: none"'); ?>>
        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="addToWatchlist()">+ Add to watchlist</button>
      </div>
      <div id="watch_watching" <?php if (!$has_session || !$watching) echo('style="display: none"'); ?>>
        <button type="button" class="btn btn-success btn-sm" disabled>Watching</button>
        <button type="button" class="btn btn-danger btn-sm" onclick="removeFromWatchlist()">Remove watch</button>
      </div>
      <?php endif; ?>
    </div>
  </div>

  <div class="row"> <!-- Row #2 with auction description + bidding info -->
    <div class="col-sm-8"> <!-- Left col with item info -->

      <div class="itemDescription">
        <?php echo($description); ?>
      </div>
      <div class="itemImage">
        <img src="<?php echo $image; ?>" alt="Item Image" class="img-fluid">
      </div>
    </div>
    <div class="col-sm-4"> <!-- Right col with bidding info -->
    <p>
    <?php if ($exists): ?> 
      <?php if ($now > $end_time): ?> 
        <!-- TODO: Print the result of the auction here? -->
      <?php else: ?>
        <?php echo "Number of bids: " .$num_bids . '<br>'; ?>
        <?php echo "Remaining time: " . display_time_remaining($time_to_end); ?>
        <p class="lead mb-1">Current bid: £<?php echo(number_format($current_price, 2)) ?></p> 
        <p class="text-muted mt-1">Starting price was £<?php echo(number_format($start_price, 2)); ?></p>
      <?php endif; ?>

      <!-- Bidding form -->
      <form method="POST" action="place_bid.php">
          <input type="hidden" name="item_id" value="<?php echo $item_id; ?>"> 
          <div class="input-group">
              <div class="input-group-prepend">
                  <span class="input-group-text">£</span>
              </div>
              <input type="number" class="form-control" name="bid" id="bid" required> 
          </div>
          <button type="submit" class="btn btn-primary form-control">Place bid</button>
      </form>
      <?php endif; ?>

  
  </div> <!-- End of right col with bidding info -->

</div> <!-- End of row #2 -->



<?php include_once("footer.php")?>


<script> 
// JavaScript functions: addToWatchlist and removeFromWatchlist.

function addToWatchlist(button) {
  console.log("These print statements are helpful for debugging btw");

  // This performs an asynchronous call to a PHP function using POST method.
  // Sends item ID as an argument to that function.
  $.ajax('watchlist_funcs.php', {
    type: "POST",
    data: {functionname: 'add_to_watchlist', arguments: [<?php echo($item_id);?>]},

    success: 
      function (obj, textstatus) {
        // Callback function for when call is successful and returns obj
        console.log("Success");
        var objT = obj.trim();
 
        if (objT == "success") {
          $("#watch_nowatch").hide();
          $("#watch_watching").show();
        }
        else {
          var mydiv = document.getElementById("watch_nowatch");
          mydiv.appendChild(document.createElement("br"));
          mydiv.appendChild(document.createTextNode("Add to watch failed. Try again later."));
        }
      },

    error:
      function (obj, textstatus) {
        console.log("Error");
      }
  }); // End of AJAX call

} // End of addToWatchlist func

function removeFromWatchlist(button) {
  // This performs an asynchronous call to a PHP function using POST method.
  // Sends item ID as an argument to that function.
  $.ajax('watchlist_funcs.php', {
    type: "POST",
    data: {functionname: 'remove_from_watchlist', arguments: [<?php echo($item_id);?>]},

    success: 
      function (obj, textstatus) {
        // Callback function for when call is successful and returns obj
        console.log("Success");
        var objT = obj.trim();
 
        if (objT == "success") {
          $("#watch_watching").hide();
          $("#watch_nowatch").show();
        }
        else {
          var mydiv = document.getElementById("watch_watching");
          mydiv.appendChild(document.createElement("br"));
          mydiv.appendChild(document.createTextNode("Watch removal failed. Try again later."));
        }
      },

    error:
      function (obj, textstatus) {
        console.log("Error");
      }
  }); // End of AJAX call

} // End of addToWatchlist func
</script>