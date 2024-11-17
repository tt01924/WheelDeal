<?php
  session_start();
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap and FontAwesome CSS -->
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

  <!-- Custom CSS file -->
  <link rel="stylesheet" href="css/custom.css">

  <title>[My Auction Site] <!--CHANGEME!--></title>
</head>

<body>

<!-- Navbars -->
<nav class="navbar navbar-expand-lg navbar-light bg-light mx-2">
  <a class="navbar-brand" href="#">
    <img src="image_uploads/Black and White Bicycle Store Logo (500 x 100 px) (700 x 100 px) (500 x 100 px) (600 x 100 px) (700 x 100 px)-5.png" alt="WheelDeal Logo" style="height: 50px;">
  </a>
  <ul class="navbar-nav ml-auto">
    <li class="nav-item">

<?php
  // Displays either login or account info + logout
  if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {
    echo '<span class="navbar-text mr-2">
            <strong>' . htmlspecialchars($_SESSION['username']) . '</strong>
            (' . htmlspecialchars($_SESSION['account_type']) . ')
          </span>
          <a class="nav-link" href="logout.php">Logout</a>';
  }
  else {
    echo '<button type="button" class="btn nav-link" data-toggle="modal" data-target="#loginModal">Login</button>';
  }
?>

    </li>
  </ul>
</nav>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <ul class="navbar-nav align-middle">
    <li class="nav-item mx-1">
      <a class="nav-link" href="browse.php">Browse</a>
    </li>

<?php
  // Only show these options if user is logged in
  if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    if ($_SESSION['account_type'] === 'buyer') {
      echo('
        <li class="nav-item mx-1">
          <a class="nav-link" href="mybids.php">My Bids</a>
        </li>
        <li class="nav-item mx-1">
          <a class="nav-link" href="recommendations.php">Recommended</a>
        </li>
        <li class="nav-item mx-1">
          <a class="nav-link" href="watchlist.php">My Watchlist</a>
        </li>');
    }
    if ($_SESSION['account_type'] === 'seller') {
      echo('
        <li class="nav-item mx-1">
          <a class="nav-link" href="mylistings.php">My Listings</a>
        </li>
        <li class="nav-item ml-3">
          <a class="nav-link btn border-light" href="create_auction.php">+ Create auction</a>
        </li>');
    }
  }
?>
  </ul>
</nav>

<!-- Login modal -->
<div class="modal fade" id="loginModal">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Login</h4>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <form method="POST" action="login_result.php">
          <div class="form-group">
            <label for="email">Email</label>
            <input type="text" class="form-control" id="email" name="email" placeholder="Email">
          </div>
          <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Password">
          </div>
          <button type="submit" class="btn btn-primary form-control">Sign in</button>
        </form>
        <div class="text-center">or <a href="register.php">create an account</a></div>
      </div>

    </div>
  </div>
</div> <!-- End modal -->

</body>
</html>


