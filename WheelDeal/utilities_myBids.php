<?php

// print_listing_li:
// This function prints an HTML <li> element containing an auction listing
function print_listing_li($item_id, $title, $desc, $price, $bid_time, $image_url = 'wheel.png')
{
  // Truncate long descriptions
  if (strlen($desc) > 250) {
    $desc_shortened = substr($desc, 0, 250) . '...';
  }
  else {
    $desc_shortened = $desc;
  }
  
  // NEW LINE: Format the bid timestamp
  $bid_time_obj = new DateTime($bid_time);
  $formatted_bid_time = $bid_time_obj->format('Y-m-d H:i');

  // Print HTML
  echo('
    <li class="list-group-item d-flex justify-content-between">
    <div class="p-2 mr-5">');
  
  if ($image_url) {
    echo('<img src="' . $image_url . '" alt="Listing Image" class="img-thumbnail mb-2" style="max-width: 150px;">');
  }
  
  echo('</div><div class="flex-grow-1"><h5>
    <a href="listing.php?item_id=' . $item_id . '">' . $title . '</a></h5>' . $desc_shortened .
    '<br/><span style="font-size: 0.9em;">Item ID: ' . $item_id . '</span></div>
    <div class="text-center text-nowrap">
    <span style="font-size: 1.5em">£' . number_format($price, 2) . '</span><br/>Bid Time: ' . $formatted_bid_time . '<br/>' . '</div>
  </li>'
  );
}

?>