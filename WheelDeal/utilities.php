<?php

// display_time_remaining:
// Helper function to help figure out what time to display
function display_time_remaining($interval) {

    if ($interval->days == 0 && $interval->h == 0) {
      // Less than one hour remaining: print mins + seconds:
      $time_remaining = $interval->format('%im %Ss');
    }
    else if ($interval->days == 0) {
      // Less than one day remaining: print hrs + mins:
      $time_remaining = $interval->format('%hh %im');
    }
    else {
      // At least one day remaining: print days + hrs:
      $time_remaining = $interval->format('%ad %hh');
    }

  return $time_remaining;

}

// print_listing_li:
// This function prints an HTML <li> element containing an auction listing
function print_listing_li(
                  $item_id, 
                  $title, 
                  $desc, 
                  $price, 
                  $num_bids, 
                  $end_time, 
                  $item_condition,
                  $tags,
                  $image_url = 'wheel.png')
{
  // Truncate long descriptions
  if (is_null($desc) || strlen($desc) == 0) {
    $desc_shortened = 'No description found';
  } else if (strlen($desc) > 250) {
    $desc_shortened = substr($desc, 0, 250) . '...';
  } else {
    $desc_shortened = $desc;
  }
  
  // Fix language of bid vs. bids
  if ($num_bids == 1) {
    $bid = ' bid';
  }
  else {
    $bid = ' bids';
  }
  
  // Calculate time to auction end
  $now = new DateTime();
  if (is_string($end_time)) {
    $end_time = new DateTime($end_time);
  }
  if ($now > $end_time) {
    $time_to_end = date_diff($now, $end_time);
    $time_remaining = 'Ended ' . display_time_remaining($time_to_end) . ' ago';
  }
  else {
    // Get interval:
    $time_to_end = date_diff($now, $end_time);
    $time_remaining = display_time_remaining($time_to_end) . ' remaining';
  }
  
  // Print HTML
  echo('
    <li class="list-group-item d-flex justify-content-between">
    <div class="p-2 mr-5">');
  
  if ($image_url) {
    echo('<img src="' . $image_url . '" alt="Listing Image" class="img-thumbnail mb-2" style="max-width: 280px;">');
  }
  
  echo('</div><div class="flex-grow-1"><h5><a href="listing.php?item_id=' . $item_id . '">' . $title . '</a></h5>' . $desc_shortened . '<br/><strong>Condition:</strong> <span>' . $item_condition . '</span><br/><strong style="color: grey;">Tags:</strong> <span style="color: grey;">' . $tags . '</span></div>
    <div class="text-center text-nowrap"><span style="font-size: 1.5em">£' . number_format((float)$price, 2) . '</span><br/>' . $num_bids . $bid . '<br/>' . $time_remaining . '</div>
  </li>'
  );
}

function getCurrentHighestBid($itemId) {
  global $pdo;
  $sql = "SELECT MAX(bid.amount) AS highest_bid FROM Bid WHERE itemId = ?";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$itemId]);
  $result = $stmt->fetch(PDO::FETCH_ASSOC);
  return $result['highest_bid'] ?? null; // Return null if no bids are found
}
function getCurrentBid($itemId) {
  global $pdo; // Ensure you use your database connection
  $sql = "SELECT COUNT(bid.bidId) AS highest_bid FROM Bid WHERE itemId = ? GROUP BY bidId";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$itemId]);
  $result = $stmt->fetch(PDO::FETCH_ASSOC);
  return $result['highest_bid'] ?? null; // Return null if no bids are found
}

function recommendItems($userId) {
  global $pdo; 

  $sql = "SELECT i.*, 
                 MAX(b.amount) AS amount, 
                 COUNT(b.bidId) AS num_bids
          FROM Item i
          LEFT JOIN Bid b ON i.itemId = b.itemId
          JOIN ItemCategory c ON i.categoryId = c.categoryId
          WHERE i.itemId NOT IN (
              SELECT we.itemId
              FROM WatchListEntry we
              JOIN WatchList w ON we.watchListId = w.watchListId
              WHERE w.userId = ?
          ) AND
          c.categoryId IN (
              SELECT DISTINCT i.categoryId
              FROM Item i
              JOIN WatchListEntry we ON i.itemId = we.itemId
              JOIN WatchList w ON we.watchListId = w.watchListId
              WHERE w.userId = ?
          )
          GROUP BY i.itemId";

  $command = $pdo->prepare($sql);
  $command->execute([$userId, $userId]);
  return $command->fetchAll(PDO::FETCH_ASSOC);
}

?>