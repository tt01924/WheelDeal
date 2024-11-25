<?php include_once("header.php")?>
<?php require("utilities.php")?>

<?php
  if (session_status() == PHP_SESSION_NONE) {
      session_start();
  }

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
      $stmt = $conn->prepare("SELECT title, description, startPrice, reservePrice, timeCreated, endTime, image FROM Item WHERE itemId = ?");
      $stmt->bind_param("i", $item_id);
      $stmt->execute();
      $stmt->store_result();

      // Check if the item exists
      if ($stmt->num_rows > 0) {
          $stmt->bind_result($title, $description, $startPrice, $reservePrice, $timeCreated, $endTime, $image);
          $stmt->fetch();
          $exists = True;
      } else {
          // Handle non-existent item_id
          $title = "Item not found";
          $description = "No description available.";
          $startPrice = 0;
          $endTime = new DateTime();
          $image = 'wheel.png';
          $exists = False;
      }

      $stmt->close();
      $conn->close();

  } else {
      // Handle invalid item_id
      $title = "Invalid item";
      $description = "No description available.";
      $$startPrice = 0;
      $endTime = new DateTime();
      $image = 'wheel.png';
      $exists = False;
  }

  if ($exists) {
    ### convert endTime to DateTime object
    $endTime = DateTime::createFromFormat('Y-m-d H:i:s', $endTime);

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

    // Calculate time to auction end
    $now = new DateTime();
    // Check if the current time is before the auction end time
    if ($now < $endTime) {
      $ended = false;
      $time_to_end = date_diff($now, $endTime);
      $time_remaining = ' (in ' . display_time_remaining($time_to_end) . ')';
    } else {
      $ended = true;
      $time_to_end = date_diff($endTime, $endTime);
      $time_remaining = ' (Auction has ended)';
    }

    $has_session = true;
    $watching = false;

    if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
      $conn = new mysqli('localhost', 'root', 'root', 'WheelDeal');
      if ($conn->connect_error) {
          die("Connection failed: " . $conn->connect_error);
      }
      $command = $conn->prepare("SELECT watchListId FROM WatchList WHERE userId = ?");
      $command->bind_param("i", $_SESSION['user_id']);
      $command->execute();
      $command->bind_result($watchListId);
      $command->fetch();
      $command->close();

      ### get itemId to see if watching and to pass to remove if remove is called
      $command = $conn->prepare("SELECT itemId FROM WatchListEntry WHERE watchListId = ? AND itemId = ?");
      $command->bind_param("ii", $watchListId, $item_id);
      $command->execute();
      $command->store_result();
      if($command->num_rows > 0) { // if there is a watch list entry for this item and this user's watchlist
        $watching = true;
      }
      $command->close();
      $conn->close();
    }
  }
?>


<div class="container">
  <?php
  // Check if the user is logged in
  if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
  ?>
    <div class="row"> <!-- Row #1 with auction title + watch button -->
      <div class="col-sm-8"> <!-- Left col -->
        <h2 class="my-3"><?php echo($title); ?></h2>
      </div>
      <div class="col-sm-4 align-self-center"> <!-- Right col -->
        <?php if (isset($_SESSION['account_type']) && $_SESSION['account_type'] === 'buyer'): ?>
          <div id="watch_nowatch" <?php if ($watching) echo('style="display: none"'); ?>>
            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="addToWatchlist()">+ Add to watchlist</button>
          </div>
          <div id="watch_watching" <?php if (!$watching) echo('style="display: none"'); ?>>
            <button type="button" class="btn btn-success btn-sm" disabled>Watching</button>
            <button type="button" class="btn btn-danger btn-sm" onclick="removeFromWatchlist()">Remove watch</button>
          </div>
        <?php endif; ?>
      </div>
    </div>
  <?php
  }
  ?>

  <div class="row"> <!-- Row #2 with auction description + bidding info -->
    <div class="col-sm-8"> <!-- Left col with item info -->
      <?php if ($ended): ?>
        <div class="alert alert-warning text-center">AUCTION HAS ENDED</div>
      <?php endif; ?>
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
      <?php echo $ended ? "Final number of bids: " : "Current number of bids: "; echo $num_bids . '<br>'; ?>
      <span id="remaining-time"><?php echo "Remaining time: " . display_time_remaining($time_to_end); ?></span>
      <p class="lead mb-1"><?php echo $ended ? 'Final bid: £' : 'Current bid: £'; ?><?php echo(number_format($current_price, 2)) ?></p>
      <p class="text-muted mt-1">Starting price was £<?php echo(number_format($startPrice, 2)); ?></p>

      <!-- Bidding form -->
      <?php if (!isset($_SESSION['logged_in'])): ?>
          <div class="alert alert-info">Please log in to bid</div>
      <?php elseif (isset($_SESSION['account_type']) && $_SESSION['account_type'] === 'buyer' && $ended === False): ?>
          <form method="POST" action="place_bid.php">
              <input type="hidden" name="item_id" value="<?php echo $item_id; ?>">
              <input type="hidden" name="reserve_price" value="<?php echo $reservePrice; ?>">
              <div class="input-group">
                  <div class="input-group-prepend">
                      <span class="input-group-text">£</span>
                  </div>
                  <input type="number" class="form-control" name="bid" id="bid" required>
              </div>
              <button type="submit" class="btn btn-primary form-control">Place bid</button>
          </form>
      <?php elseif (isset($_SESSION['account_type']) && $_SESSION['account_type'] === 'seller'): ?>
          <div class="alert alert-info">As a seller, you cannot place bids.</div>
      <?php elseif ($ended === true): ?>
        <div class="alert alert-info">Auction has ended</div>
      <?php endif; ?>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success mt-2"><?php echo htmlspecialchars($_GET['success']); ?></div>
        <?php elseif (isset($_GET['error'])): ?>
            <div class="alert alert-danger mt-2"><?php echo htmlspecialchars($_GET['error']); ?></div>
        <?php endif; ?>
      <?php endif; ?>
  </div> <!-- End of right col with bidding info -->
</div> <!-- End of row #2 -->

<?php include_once("footer.php")?>


<script>


// autorefresh page and preserving form content, code written with help of GPT-4o
function autoRefreshPage() {
  setTimeout(() => {
    location.reload();
  }, 5000);
}

document.addEventListener("DOMContentLoaded", function() {
  const formElements = document.querySelectorAll("form input, form textarea");
  formElements.forEach(element => {
    const savedValue = localStorage.getItem(element.name);
    if (savedValue) {
      element.value = savedValue;
    }
  });

  formElements.forEach(element => {
    element.addEventListener("input", function() {
      localStorage.setItem(element.name, element.value);
    });
  });
});

autoRefreshPage();





function addToWatchlist(button) {
  console.log("These print statements are helpful for debugging btw");

  // This performs an asynchronous call to a PHP function using POST method.
  // Sends item ID as an argument to that function.
  $.ajax('watchlist_funcs.php', {
    type: "POST",
    data: {functionname: 'add_to_watchlist', arguments: [<?php echo($item_id);?>, <?php echo($_SESSION['user_id']);?>]},

    success:
      function (obj, textstatus) {
        var objT = obj.replace(/\/\/.*$/gm, '').trim(); // trim and remove weird MAMP disclaimer
        if (objT === "success") {
          console.log("Success");
          $("#watch_nowatch").hide();
          $("#watch_watching").show();
        }
        else {
          console.log("Failed");
          console.log("Response: " + objT);
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
        var objT = obj.replace(/\/\/.*$/gm, '').trim(); // trim weird MAMP disclaimer
        console.log(objT);
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