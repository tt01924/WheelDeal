<?php
/*
* Filename: header.php
* Purpose: Provides main layout, navigation, and login functionality
* Dependencies: Bootstrap CSS, FontAwesome, custom.css, login_result.php
* Flow: Starts session -> Loads styling -> Renders navigation -> Shows login modal
*/

// Start session if not already active
  if (session_status() == PHP_SESSION_NONE) {
      session_start();
  }
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

  <title>WheelDeal</title>
</head>


<body>

<!-- Top navigation bar with logo and login -->
<nav class="navbar navbar-expand-lg navbar-light bg-light mx-2">
  <a class="navbar-brand" href="#">
    <a href="browse.php">
      <img src="image_uploads/Black and White Bicycle Store Logo (500 x 100 px) (700 x 100 px) (500 x 100 px) (600 x 100 px) (700 x 100 px)-5.png" alt="WheelDeal Logo" style="height: 50px;">
    </a>
  </a>
  <ul class="navbar-nav ml-auto">
    <li class="nav-item">
    
<?php
  // Displays either login or logout on the right, depending on user's state
  // current status (session).
  if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {
    echo '<a class="nav-link" href="logout.php">Logout</a>';
  }
  else {
    echo '<button type="button" class="btn nav-link" data-toggle="modal" data-target="#loginModal">Login</button>';
  }
?>

    </li>
  </ul>
</nav>

<!-- Main navigation menu -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <ul class="navbar-nav align-middle">
	<li class="nav-item mx-1">
      <a class="nav-link" href="browse.php">Browse</a>
    </li>
<?php

// Show buyer-specific navigation options
  if (isset($_SESSION['account_type']) && $_SESSION['account_type'] == 'buyer') {
  echo('
	<li class="nav-item mx-1">
      <a class="nav-link" href="mybids.php">My Bids</a>
    </li>
	<li class="nav-item mx-1">
      <a class="nav-link" href="recommendations.php">Recommended</a>
    </li>
	<li class="nav-item mx-1">
    <a class="nav-link" href="watchlist.php">My Watchlist</a>
  </li>
	<li class="nav-item mx-1">
    <a class="nav-link" href="profile.php">My profile</a>
  </li>');
  }

// Show seller-specific navigation options
  if (isset($_SESSION['account_type']) && $_SESSION['account_type'] == 'seller') {
  echo('
	<li class="nav-item mx-1">
    <a class="nav-link" href="mylistings.php">My Listings</a>
  </li>
	<li class="nav-item ml-3">
    <a class="nav-link btn border-light" href="create_auction.php">+ Create auction</a>
  </li>
  <li class="nav-item mx-1">
    <a class="nav-link" href="profile.php">Personal profile</a>
  </li>');
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