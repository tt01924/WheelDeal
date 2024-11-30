<?php

/*
* Filename: browse.php
* Purpose: Main auction browsing page that allows users to search, filter, and view auction listings
* Dependencies: header.php, utilities.php, check_ended_auctions.php, db_connect.php, footer.php
* Flow: 1. Checks for ended auctions
*       2. Displays search and filter interface
*       3. Processes search parameters
*       4. Queries database for matching items
*       5. Displays results with pagination
*/

?>

<!------------- Header and utility functions -->
<?php include_once("header.php")?>
<?php require("utilities.php")?>

<?php
require_once 'check_ended_auctions.php';
checkEndedAuctions();
?>

<!------------- Main container holding the search and filter forms -->
<div class="container">
<h2 class="my-3">Browse listings</h2>
<div id="searchSpecs">
  <form method="get" action="browse.php"> <!-- When submitted, this PHP page is what processes it. -->
    <div class="row">
      <div class="col-md-5 pr-0">
        <div class="form-group">
          <label for="keyword" class="sr-only">Search keyword:</label>
        <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text bg-transparent pr-0 text-muted">
                <i class="fa fa-search"></i>
              </span>
            </div>
            <input type="text" class="form-control border-left-0" id="keyword" name="keyword" placeholder="Search for anything" value="<?php echo isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : ''; ?>">
          </div>
        </div>
      </div>
      <div class="col-md-3 pr-0">
        <div class="form-group">
          <label for="cat" class="sr-only">Search within:</label>
          <select class="form-control" id="cat" name="cat">
            <!-- categories -->
            <option value="all" <?php echo (isset($_GET['cat']) && $_GET['cat'] === 'all') ? 'selected' : ''; ?>>All categories</option>
            <option value="1" <?php echo (isset($_GET['cat']) && $_GET['cat'] == '1') ? 'selected' : ''; ?>>Bikes</option> 
            <option value="2" <?php echo (isset($_GET['cat']) && $_GET['cat'] == '2') ? 'selected' : ''; ?>>Accessories</option>
            <option value="3" <?php echo (isset($_GET['cat']) && $_GET['cat'] == '3') ? 'selected' : ''; ?>>Parts</option>
            <option value="4" <?php echo (isset($_GET['cat']) && $_GET['cat'] == '4') ? 'selected' : ''; ?>>Apparel</option>
          </select>
        </div>
      </div>
      <div class="col-md-3 pr-0">
        <div class="form-inline">
          <!-- sort by search menu -->
          <label class="mx-2" for="order_by">Sort by:</label>
          <select class="form-control" id="order_by" name="order_by">
            <option value="pricelow" <?php echo (isset($_GET['order_by']) && $_GET['order_by'] === 'pricelow') ? 'selected' : ''; ?>>Price (low to high)</option>
            <option value="pricehigh" <?php echo (isset($_GET['order_by']) && $_GET['order_by'] === 'pricehigh') ? 'selected' : ''; ?>>Price (high to low)</option>
            <option value="date" <?php echo (isset($_GET['order_by']) && $_GET['order_by'] === 'date') ? 'selected' : ''; ?>>Soonest expiry</option>
          </select>
        </div>
      </div>
      <div class="col-md-1 px-0">
        <button type="submit" class="btn btn-primary">Search</button>
      </div>
    </div>
  </form>
</div>


</div>

<?php

  // Include database connection
  include 'db_connect.php';
///////////////// Parameters for the browse page
  $keyword = isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : ''; 
  $cat = isset($_GET['cat']) ? $_GET['cat'] : 'all';
  $order_by = isset($_GET['order_by']) ? $_GET['order_by'] : 'date';
  $page = isset($_GET['page']) ? $_GET['page'] : 1;

////////////////////// SQL query to fetch items
  
  $search_term = '%' . $keyword . '%';

////////////////// Pagination
  $results_per_page = 5;
  $offset = ($page - 1) * $results_per_page;
  $keyword = isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : '';
  $search_term = '%' . $keyword . '%';

  $query = "
    SELECT Item.*, MAX(Bid.amount) AS current_price, COUNT(Bid.bidId) AS num_bids 
    FROM Item 
    LEFT JOIN Bid ON Item.itemId = Bid.itemId 
    WHERE Item.description LIKE :search_term
    AND Item.endTime > NOW()";
    
  if ($cat !== 'all') {
    $query .= " AND Item.categoryId = :cat";
  }


  $keyword = isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : '';
  $search_term = '%' . $keyword . '%';
  // Count for items
  $total_query = "
    SELECT COUNT(DISTINCT itemId) 
    FROM Item 
    WHERE (Item.description LIKE :search_term) AND Item.endTime > NOW()
    ";


  if ($cat !== 'all') {
    $total_query .= " AND Item.categoryId = :cat";
  }

    // Prepare and execute the total count statement
    $total_stmt = $pdo->prepare($total_query);
    $total_stmt->bindValue(':search_term', $search_term, PDO::PARAM_STR);
    if ($cat !== 'all') {
        $total_stmt->bindValue(':cat', (int)$cat, PDO::PARAM_INT);
    }
    $total_stmt->execute();


  // Group by Item ID to prevent duplicates
  $query .= " GROUP BY Item.itemId";

    // Sorting order based on user selection
    if ($order_by === 'pricehigh') {
        $query .= " ORDER BY current_price DESC";
    } elseif ($order_by === 'pricelow') {
        $query .= " ORDER BY current_price ASC";
    } elseif ($order_by === 'date') {
        $query .= " ORDER BY Item.endTime ASC"; 
    }
    $query .= " LIMIT :results_per_page OFFSET :offset"; # Must be placed below sorting order due to sequential logic



  
///////////// Factors based on category filter
  // Applying keyword to the statement and reaching out to the database
  $stmt = $pdo->prepare($query);
  $stmt->bindValue(':search_term', $search_term, PDO::PARAM_STR);
  $stmt->bindValue(':results_per_page', $results_per_page, PDO::PARAM_INT);
  $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

  if ($cat !== 'all') {
    $stmt->bindValue(':cat', (int)$cat, PDO::PARAM_INT); // Bind as integer
  }



/////// Total number of results
$num_results = $total_stmt->fetchColumn(); // This fetches the total count from the prepared query
$max_page = ceil($num_results / $results_per_page);


///////// Execute and fetch results
  $stmt->execute();
  $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="container mt-2">
    <p><?php echo $num_results; ?> Results found.</p>
</div>


<div class="container mt-5">
<?php
  if (empty($result)) {
    /////////// When the search result is empty 
      echo '<p>No listings found for your search criteria.</p>'; # Working!
  } else {
      echo '<ul class="list-group">';
      foreach ($result as $row) {

          $highest_bid = isset($row['current_price']) ? $row['current_price'] : 0;
          $current_price = max($highest_bid, $row['startPrice']);
          
          print_listing_li(
            $row['itemId'],
            $row['title'],  
            $row['description'], 
            $current_price,
            $row['num_bids'],
            (new DateTime($row['endTime']))->format('Y-m-d H:i:s'), 
            $row['itemCondition'],
            $row['image']);
      }
      echo '</ul>';
  }
?>
<!-- Pagination for results listings - navigate between pages of search results -->
<nav aria-label="Search results pages" class="mt-5"> 
  <ul class="pagination justify-content-center">
  <?php

    // Copy any currently-set GET variables to the URL.
    $querystring = "";
    foreach ($_GET as $key => $value) {
      if ($key != "page") {
        $querystring .= "$key=$value&amp;";
      }
    }
    // This generates the page links for the results
    $high_page_boost = max(3 - $page, 0);
    $low_page_boost = max(2 - ($max_page - $page), 0);
    $low_page = max(1, $page - 2 - $low_page_boost);
    $high_page = min($max_page, $page + 2 + $high_page_boost);
    
    if ($page != 1) {
      echo('
      <li class="page-item">
        <a class="page-link" href="browse.php?' . $querystring . 'page=' . ($page - 1) . '" aria-label="Previous">
          <span aria-hidden="true"><i class="fa fa-arrow-left"></i></span>
          <span class="sr-only">Previous</span>
        </a>
      </li>');
    }
      
    for ($i = $low_page; $i <= $high_page; $i++) {
      if ($i == $page) {
        // Highlight the link
        echo('
      <li class="page-item active">');
      }
      else {
        // Non-highlighted link
        echo('
      <li class="page-item">');
      }
      
      // Do this in any case
      echo('
        <a class="page-link" href="browse.php?' . $querystring . 'page=' . $i . '">' . $i . '</a>
      </li>');
    }
    
    if ($page != $max_page) {
      echo('
      <li class="page-item">
        <a class="page-link" href="browse.php?' . $querystring . 'page=' . ($page + 1) . '" aria-label="Next">
          <span aria-hidden="true"><i class="fa fa-arrow-right"></i></span>
          <span class="sr-only">Next</span>
        </a>
      </li>');
    }
  ?>

  </ul>
</nav>
</div>

<!-- Footer for the site -->
<?php include_once("footer.php")?>