<?php
/*
* Filename: profile.php
* Purpose: Display user profile information
* Dependencies: header.php, utilities.php, db_connect.php
* Flow: Verifies login -> Fetches profile -> Displays info
*/


include_once("header.php");
require("utilities.php");
require("db_connect.php");

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<div class="container">

<h2 class="my-3">My profile</h2>

<?php
// Check user auth
if (!isset($_SESSION['logged_in']) || !isset($_SESSION['user_id'])) {
    echo '<div class="alert alert-danger">Please log in to view your profile.</div>';
} else {
    $userId = $_SESSION['user_id'];
    
    try {
        // Get profile info from database
        ///////////// TO ADD POTENTIALLY: profile photo, summary and about information, review site.
        $sql = "SELECT U.username, U.email, U.phoneNumber, a.street, a.city, a.county, a.postcode
                FROM User U
                LEFT JOIN Address a ON a.userId = U.userId
                WHERE U.userId = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$userId]);
        $userProfile = $stmt->fetch(PDO::FETCH_ASSOC);


        if (!$userProfile) {
            echo '<div class="alert alert-danger">Your profile does not exist in our database.</div>';
        } else {
            echo '<ul class="list-group">';
            echo '<li class="list-group-item"><strong>Username:</strong> ' . htmlspecialchars($userProfile['username']) . '</li>';
            echo '<li class="list-group-item"><strong>Email:</strong> ' . htmlspecialchars($userProfile['email']) . '</li>';
            echo '<li class="list-group-item"><strong>Phone Number:</strong> ' . htmlspecialchars($userProfile['phoneNumber']) . '</li>';
            $indent = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
            echo '<li class="list-group-item"><strong>Address:</strong><br>' . 
                 (isset($userProfile['street']) ? $indent . htmlspecialchars($userProfile['street']) . '<br>' : '') . 
                 (isset($userProfile['city']) ? $indent . htmlspecialchars($userProfile['city']) . '<br>' : '') . 
                 (isset($userProfile['county']) ? $indent . htmlspecialchars($userProfile['county']) . '<br>' : '') . 
                 (isset($userProfile['postcode']) ? $indent . htmlspecialchars($userProfile['postcode']) : '');
            echo '</li>';
            echo '</ul>';     

        }
    } catch (PDOException $e) {
        echo '<div class="alert alert-danger">An error occurred while retrieving your profile: ' . htmlspecialchars($e->getMessage()) . '</div>';
    }
}
?>
<?php
// Allow users to make changes to their profile
  if (isset($_SESSION['account_type']) && ($_SESSION['account_type'] == 'seller' || $_SESSION['account_type'] == 'buyer')) {
  echo('
  <div class="nav-item mx-1">
    <a class="btn btn-secondary mt-3" href="editProfile.php">Edit profile</a>
  </div>');
  }
?>

</div>

<?php include_once("footer.php")?>