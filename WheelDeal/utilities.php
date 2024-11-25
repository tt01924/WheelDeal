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
  
  if ($image_url && file_exists($image_url)) {
    echo('<img src="' . $image_url . '" alt="Listing Image" class="img-thumbnail mb-2" style="max-width: 280px; max-height: 280px;">');
  } else {
    echo('<img src="wheel.png" alt="Default Listing Image" class="img-thumbnail mb-2" style="max-width: 280px; max-height: 280px;">');
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

  $sql = " 
    ### this generates recommendations for a user based on three criteria:
    ### 1. items of the same category as items that user has on watchlist
    ### 2. items of the same category as items that user has bid on 
    ### 3. items on which other users have bid on, who have also bid on same items as user
    ### additionally, auctions that user already watches or bid on or that already ended, are filtered out
    
    SELECT * FROM
        (SELECT i_details.*, MAX(b.amount) as amount, COUNT(b.bidId) as num_bids 
        FROM (
          (
            SELECT i.itemId
            FROM Item i
            LEFT JOIN Bid b ON i.itemId = b.itemId
            JOIN ItemCategory c ON i.categoryId = c.categoryId
            WHERE c.categoryId IN (
              SELECT DISTINCT i.categoryId
              FROM Item i
              JOIN WatchListEntry we ON i.itemId = we.itemId
              JOIN WatchList w ON we.watchListId = w.watchListId
              WHERE w.userId = ?
            )
            GROUP BY i.itemId
          )
          UNION
          (
            SELECT i.itemId 
            FROM (
              SELECT DISTINCT i1.itemId 
              FROM (
                SELECT b1.userId 
                FROM (
                  SELECT i.itemId, u.userId 
                  FROM User u
                  JOIN Bid b ON b.userId = u.userId
                  JOIN Item i ON i.itemId = b.itemId
                  WHERE u.userId = ?
                ) AS i
                JOIN Bid b1 ON i.itemId = b1.itemId
                WHERE i.userId != b1.userId
              ) AS u
              JOIN Bid b ON u.userId = b.userId
              JOIN Item i1 ON b.itemId = i1.itemId
            ) AS i
          )
          UNION
          (
            SELECT DISTINCT i2.itemId 
            FROM Bid b
            JOIN User u ON b.userId = u.userId
            JOIN Item i ON i.itemId = b.itemId
            JOIN ItemCategory c ON c.categoryId = i.categoryId
            JOIN Item i2 ON i2.categoryId = c.categoryId
            WHERE u.userId = ?
          )
        ) as i
        JOIN Bid b on i.itemId = b.itemId
        JOIN Item i_details on i.itemId = i_details.itemId
        GROUP BY i_details.itemId) as foundItems
    WHERE foundItems.itemId NOT IN 
    ((
    	SELECT DISTINCT i.itemId 
        FROM Bid b
        JOIN User u ON b.userId = u.userId
        JOIN Item i ON i.itemId = b.itemId
        WHERE u.userId = ?
    )
    UNION
    (
     SELECT DISTINCT i.itemId
     FROM Item i
     JOIN WatchListEntry we ON i.itemId = we.itemId
     JOIN WatchList w ON we.watchListId = w.watchListId
     WHERE w.userId = ?  
    )
     UNION
     (
     SELECT DISTINCT i.itemId
     FROM Item i
     WHERE i.endTime < NOW()
     )
    )
  ";

  $command = $pdo->prepare($sql);
  $command->execute([$userId, $userId,$userId,$userId,$userId]);
  return $command->fetchAll(PDO::FETCH_ASSOC);
}

?>