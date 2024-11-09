<!------------- Header and utility functions -->
<?php include_once("header.php")?>
<?php require("utilities.php")?>

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
            <input type="text" class="form-control border-left-0" id="keyword" name="keyword" placeholder="Search for anything">
          </div>
        </div>
      </div>
      <div class="col-md-3 pr-0">
        <div class="form-group">
          <label for="cat" class="sr-only">Search within:</label>
          <select class="form-control" id="cat" name="cat">
            <option selected value="all">All categories</option>
            <option value="fill">Fill me in</option>
            <option value="with">with options</option>
            <option value="populated">populated from a database?</option>
          </select>
        </div>
      </div>
      <div class="col-md-3 pr-0">
        <div class="form-inline">
          <label class="mx-2" for="order_by">Sort by:</label>
          <select class="form-control" id="order_by" name="order_by">
            <option selected value="pricelow">Price (low to high)</option>
            <option value="pricehigh">Price (high to low)</option>
            <option value="date">Soonest expiry</option>
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

<?php ///////////////// Parameters for the browse page
  $keyword = isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : ''; 
  $cat = isset($_GET['cat']) ? $_GET['cat'] : 'all';
  $order_by = isset($_GET['order_by']) ? $_GET['order_by'] : 'date';
  $page = isset($_GET['page']) ? $_GET['page'] : 1;

////////////////////// SQL query to fetch items
  
  $search_term = '%' . $keyword . '%';

////////////////// Pagination
  $results_per_page = 10;
  $offset = ($curr_page - 1) * $results_per_page;
  $query = "SELECT * FROM Item WHERE (title LIKE :search_term OR description LIKE: search_term)";
  $query .= " LIMIT :results_per_page OFFSET :offset";

  // Count for items
  $total_query = "SELECT COUNT(*) FROM Item WHERE (title LIKE :search_term OR description LIKE :search_term)";
  if ($cat !== 'all') {
    $total_query .= " AND cat = :cat";
  }
  $total_stmt = $pdo->prepare($total_query);
  $total_stmt->bindValue(':search_term', $search_term, PDO::PARAM_STR);
  if ($cat !== 'all') {
      $total_stmt->bindValue(':category', $cat, PDO::PARAM_STR);
  }
  $total_stmt->execute();
  $num_results = $total_stmt->fetchColumn();
  $max_page = ceil($num_results / $results_per_page);

  // Sorting order based on user selection
  if ($order_by === 'pricehigh') {
      $query .= " ORDER BY price DESC"; # The full-stop concatenates rather than replaces
  } elseif ($order_by === 'pricelow') {
      $query .= " ORDER BY price ASC";
  } elseif ($order_by === 'date') {
      $query .= " ORDER BY end_date ASC";
  }
  

///////////// Factors based on category filter
  // if ($cat === 'all') {
  //   $stmt->bind_param("ssii", $search_term, $search_term, $results_per_page, $offset);
  // } else {
  //   $stmt->bind_param("ssisi", $search_term, $search_term, $cat, $results_per_page, $offset);
  // }
  
  // Applying keyword to the statement and reaching out to the database
  $stmt = $pdo->prepare($query);
  $stmt->bindValue(':search_term', $search_term, PDO::PARAM_STR);
  $stmt->bindValue(':results_per_page', $results_per_page, PDO::PARAM_INT);
  $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

if ($cat !== 'all') {
    $stmt->bindValue(':category', $cat, PDO::PARAM_STR);
}


/////// Total number of results

  // $num_results = SELECT COUNT(items) FROM Item WHERE (description = ? OR tags = ?); // DOUBLE-CHECK!!!!!! TODO: Calculate me for real
  // $max_page = ceil($num_results / $results_per_page);

///////// Execute and fetch results
  $stmt->execute();
  $result = $stmt->get_result();
?>

<div class="container mt-5">

<!-- TODO: If result set is empty, print an informative message. Otherwise... -->

<ul class="list-group">

<!-- TODO: Use a while loop to print a list item for each auction listing
     retrieved from the query -->

  <?php /////////////// This is how the query you have selected will be presented
    $item_id = "87021"; // This is an example using the dummy data.
    $title = "Dummy title";
    $description = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum eget rutrum ipsum. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Phasellus feugiat, ipsum vel egestas elementum, sem mi vestibulum eros, et facilisis dui nisi eget metus. In non elit felis. Ut lacus sem, pulvinar ultricies pretium sed, viverra ac sapien. Vivamus condimentum aliquam rutrum. Phasellus iaculis faucibus pellentesque. Sed sem urna, maximus vitae cursus id, malesuada nec lectus. Vestibulum scelerisque vulputate elit ut laoreet. Praesent vitae orci sed metus varius posuere sagittis non mi.";
    $current_price = 30;
    $num_bids = 1;
    $end_date = new DateTime('2020-09-16T11:00:00');
    
    // This uses a function defined in utilities.php
    print_listing_li($item_id, $title, $description, $current_price, $num_bids, $end_date);
    
    $item_id = "516";
    $title = "Different title";
    $description = "Very short description.";
    $current_price = 13.50;
    $num_bids = 3;
    $end_date = new DateTime('2020-11-02T00:00:00');
    
    print_listing_li($item_id, $title, $description, $current_price, $num_bids, $end_date);
  ?>

</ul>

<nav aria-label="Search results pages" class="mt-5"> <!-------- Pagination for results listings - navigate between pages of search results -->
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
    $high_page_boost = max(3 - $curr_page, 0);
    $low_page_boost = max(2 - ($max_page - $curr_page), 0);
    $low_page = max(1, $curr_page - 2 - $low_page_boost);
    $high_page = min($max_page, $curr_page + 2 + $high_page_boost);
    
    if ($curr_page != 1) {
      echo('
      <li class="page-item">
        <a class="page-link" href="browse.php?' . $querystring . 'page=' . ($curr_page - 1) . '" aria-label="Previous">
          <span aria-hidden="true"><i class="fa fa-arrow-left"></i></span>
          <span class="sr-only">Previous</span>
        </a>
      </li>');
    }
      
    for ($i = $low_page; $i <= $high_page; $i++) {
      if ($i == $curr_page) {
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
    
    if ($curr_page != $max_page) {
      echo('
      <li class="page-item">
        <a class="page-link" href="browse.php?' . $querystring . 'page=' . ($curr_page + 1) . '" aria-label="Next">
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