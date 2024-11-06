Updated watchlist_funcs.php

<?php
require("db_connect.php");

if (!isset($_POST['functionname']) || !isset($_POST['arguments'])) {
  return;
}

// Extract arguments from the POST variables:
$item_id = $_POST['arguments'];

session_start();
$user_id = $_SESSION['user_id'] ?? null;

if ($_POST['functionname'] == "add_to_watchlist") {
  // TODO: Update database and return success/failure.
  if($user_id) {
    $success = addItemToWatchList($user_id, $item_id);
    $res = $success ? "success" : "error";
  } else {
    $res = "error";
  }
}
else if ($_POST['functionname'] == "remove_from_watchlist") {
  // TODO: Update database and return success/failure.
  if($user_id) {
    // Get watchlist ID first
    $stmt = $pdo->prepare("SELECT watchListId FROM WatchList WHERE userId = ?");
    $stmt->execute([$user_id]);
    $watchlist_id = $stmt->fetchColumn();
    
    if($watchlist_id) {
      $success = removeItemFromWatchList($watchlist_id, $item_id);
      $res = $success ? "success" : "error";
    } else {
      $res = "error";
    }
  } else {
    $res = "error";
  }
}

echo $res;
?>