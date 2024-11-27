<?php include_once("header.php")?>
<?php include_once("db_connect.php")?>
<?php require("utilities.php")?>

<?php
  if (session_status() == PHP_SESSION_NONE) {
      session_start();
  }

  
  $item_id = isset($_GET['item_id']) ? intval($_GET['item_id']) : 0;
  
  // check if item_id is valid
  if ($item_id > 0) {
      
      $sql = "SELECT i.*, MAX(b.amount) AS amount, COUNT(b.bidId) AS num_bids 
              FROM Item i 
              LEFT JOIN Bid b ON i.itemId = b.itemId 
              WHERE i.itemId = ? 
              GROUP BY i.itemId";
      $stmt = $pdo->prepare($sql);
      $stmt->execute([$item_id]);

      // Check if the item exists
      $item = $stmt->fetch(PDO::FETCH_ASSOC);
      if ($item) {
          $title = $item['title'];
          $description = $item['description'];
          $startPrice = $item['startPrice'];
          $reservePrice = $item['reservePrice'];
          $timeCreated = $item['timeCreated'];
          $endTime = $item['endTime'];
          $image = $item['image'];
          $num_bids = $item['num_bids'];
          $current_price = isset($item['amount']) ? $item['amount'] : 0;
          $exists = true;
      } else {
          // handle non existing item
          $title = "Item not found";
          $description = "No description available.";
          $startPrice = 0;
          $current_price = 0;
          $num_bids = 0;
          $ended = true;
          $watching = false;
          $endTime = new DateTime();
          $image = 'wheel.png';
          $exists = false;
    }
  }
  if ($exists) {
    ### convert endTime to DateTime object
    $endTime = DateTime::createFromFormat('Y-m-d H:i:s', $endTime);

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
    
      // query to get the highest bidder and check if the user is a bidder
      $sql_bidder_info = "
      SELECT 
          (SELECT User.userId 
          FROM Bid 
          INNER JOIN User ON Bid.userId = User.userId 
          WHERE Bid.itemId = ? 
          ORDER BY Bid.amount DESC 
          LIMIT 1) AS highest_bidder_id,
          (SELECT COUNT(*) 
          FROM Bid 
          WHERE itemId = ? AND userId = ?) AS user_bid_count";

      $stmt_bidder_info = $pdo->prepare($sql_bidder_info);
      $stmt_bidder_info->execute([$item_id, $item_id, $_SESSION['user_id']]);

      $bidder_info_result = $stmt_bidder_info->fetch(PDO::FETCH_ASSOC);

      $is_highest_bidder = ($bidder_info_result['highest_bidder_id'] == $_SESSION['user_id']);
      $is_bidder = ($bidder_info_result['user_bid_count'] > 0);

      // decide if rating form can be shown
      if ($ended && $is_highest_bidder){
        $showRatingForm = true;
      } else {
        $showRatingForm = false;
      }
    } else {
      $showRatingForm = false; // show no rating form if user is not logged in
    }
    
    // query to fetch the item ID, seller's username, their average rating, for a specific item listed.
    // uses LEFT JOIN to include new sellers with no ratings.
    $sql = 
    "SELECT Item.itemId, User.username, AVG(SellerRating.rating) AS avg_rating, COUNT(SellerRating.rating) AS num_ratings
    FROM Item 
    LEFT JOIN SellerRating
    ON Item.userId = SellerRating.sellerId
    LEFT JOIN User 
    ON SellerRating.sellerId = User.userId
    WHERE Item.itemId = ?
    GROUP BY Item.itemId";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$item_id]);

    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
      if (!is_null($result["avg_rating"])) {  
        // If the query returns a result, it means the seller has previous sales. 
        // Set the seller's username, rounded average rating, and number of ratings.
        $seller_username = $result['username'];
        $seller_rating = round($result['avg_rating']);
        $num_ratings = $result['num_ratings'];
      } else {
        // if no avg_rating the seller is new, therefore '(New Seller)' appended to the username
        // initialise seller_rating variable to 0 and num_ratings to 0
        $seller_username = $result['username'] . ' (New Seller)';
        $seller_rating = 0;
        $num_ratings = 0;
      } 
    } else {
    // seller is unable to be found, therefore error is printed.
    echo "Unable to find seller in the database.";
    exit;
    }
  }
?>

<div class="container">
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

<div class="row mt-5"> <!-- New Row for seller info with spacing -->
  <div class="col-sm-12"> <!-- Full width col -->
    <div class="seller-info">
      <strong>Sold by user:</strong> <?php echo $seller_username; ?> 
      <br>
      <strong>Seller Rating</strong>:
      <span class="seller-rating">
        <?php for ($i = 0; $i < $seller_rating; $i++): ?>
          ★
        <?php endfor; ?>
        <?php for ($i = $seller_rating; $i < 5; $i++): ?>
          ☆
        <?php endfor; ?> (based on <?php echo $num_ratings; ?> user ratings)
      </span>
    </div>
    <?php if ($showRatingForm): ?>
      <div class="rating-form mt-3">
        <form method="POST" action="submit_rating.php">
          <label for="rating"><strong>Rate this seller (0-5):</strong></label>
          <input type="number" id="rating" name="rating" min="0" max="5" required>
          <input type="hidden" name="seller_username" value="<?php echo $seller_username; ?>">
          <input type="hidden" name="item_id" value="<?php echo $item_id; ?>">
          <button type="submit" class="btn btn-primary">Submit Rating</button>
        </form>
            
        <?php if (isset($_GET['ratingSuccess'])): ?>
            <div class="alert alert-success mt-2"><?php echo htmlspecialchars($_GET['ratingSuccess']); ?></div>
        <?php elseif (isset($_GET['ratingError'])): ?>
            <div class="alert alert-danger mt-2"><?php echo htmlspecialchars($_GET['ratingError']); ?></div>
        <?php endif; ?>
      </div>
    <?php endif; ?>
  </div>
</div>

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