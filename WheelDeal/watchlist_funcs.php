<?php
require("utilities.php");
require("db_connect.php");

// start session if not already started
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}


if (!isset($_POST['functionname']) || !isset($_POST['arguments'])) {
  return;
}

if (!isset($_SESSION['logged_in']) || !isset($_SESSION['user_id'])) {
  return;
  //echo '<div class="alert alert-danger">Please log in to add to your watchlist.</div>';
  //echo '<div class="text-center"><a href="login.php" class="btn btn-primary">Log in</a></div>';
} else {
  // extract arguments from the POST variable
  
  $item_id = $_POST['arguments'][0];
  $user_id = $_SESSION['user_id'] ?? null;

  if ($_POST['functionname'] == "add_to_watchlist") {
    if($user_id) {
      $success = add_to_watchlist($user_id, $item_id);
      $res = $success ? "success" : "error";
    } else {
      $res = "error";
    }
  }
  else if ($_POST['functionname'] == "remove_from_watchlist") {
    if($user_id) {
      $conn = new mysqli('localhost', 'root', 'root', 'WheelDeal');
      if ($conn->connect_error) {
          die("Connection failed: " . $conn->connect_error);
      } else {
        $res = "error";
      }

      $command = $conn->prepare("SELECT watchListId FROM WatchList WHERE userId = ?");
      $command->execute([$user_id]);
      $command->bind_result($watchlist_id);
      $command->fetch();
      
      
      if($watchlist_id) {
        $success = remove_from_watchlist($watchlist_id, $item_id);
        $res = $success ? "success" : "error";
      } else {
        $res = "error";
      }
    } else {
      $res = "error";
    }
  }
}

function add_to_watchlist($user_id, $item_id) {
    // connect to database
    ////////////////////////////////// TODO: Get from session or global
    $conn = new mysqli('localhost', 'root', 'root', 'WheelDeal');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // get watch list id
    $command = $conn->prepare("SELECT watchListId FROM WatchList WHERE userId = ?");
    $command->bind_param("i", $user_id); 
    $command->execute();
    $command->bind_result($watchlist_id);
    $command->fetch();
    $command->close();

    // create new watchlist if none exists so far
    if (!$watchlist_id) {
        $command = $conn->prepare("INSERT INTO WatchList (userId) VALUES (?)");
        $command->bind_param("i", $user_id); 
        $command->execute();
        $watchlist_id = $conn->insert_id; ## get id of last inserted row
        $command->close();
    }

    // add item to watchlist
    $command = $conn->prepare("INSERT INTO WatchListEntry (watchListId, itemId) VALUES (?, ?)");
    $command->bind_param("ii", $watchlist_id, $item_id);
    $result = $command->execute();
    $command->close();
    return $result;
  }

  function remove_from_watchlist($watchlist_id, $item_id) {
    // connect to database
    /////////////////////// TODO: Get from session or global
    $conn = new mysqli('localhost', 'root', 'root', 'WheelDeal');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // return false if watchlist is empty
    if (!$watchlist_id) {
      return false;
    } else {
      $command = $conn->prepare("DELETE FROM WatchListEntry WHERE watchListId = ? AND itemId = ?");
      $command->bind_param("ii", $watchlist_id, $item_id);
      $result = $command->execute();
      $command->close();
      return $result;
    }
  }

  echo $res;
?>