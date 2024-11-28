<?php
/*
* Filename: editProfile.php
* Purpose: Allow users to update their profile information
* Dependencies: header.php, utilities.php, db_connect.php
* Flow: Verifies login -> Displays current data -> Process form submission (validating update entries)
*/

include_once("header.php");
require("utilities.php");
require("db_connect.php");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Redirect if not logged in
if (!isset($_SESSION['logged_in']) || !isset($_SESSION['user_id'])) {
    echo '<div class="alert alert-danger">Please log in to edit your profile.</div>';
    echo '<div class="text-center"><a href="login.php" class="btn btn-primary">Log in</a></div>';
    include_once("footer.php");
    exit;
}

$userId = $_SESSION['user_id'];
$errorMsg = '';
$successMsg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $phoneNumber = trim($_POST['phoneNumber']);
    $street = trim($_POST['street']);
    $city = trim($_POST['city']);
    $county = trim($_POST['county']);
    $postcode = trim($_POST['postcode']);

    if (empty($username) || empty($email)) {
        $errorMsg = "Username and Email are required fields.";
    } else {
        try {            
            // check if the username or email already exists for another user
            $sqlCheck = "SELECT COUNT(*) FROM User WHERE (username = ? OR email = ?) AND userId != ?";
            $stmtCheck = $pdo->prepare($sqlCheck);
            $stmtCheck->execute([$username, $email, $userId]);
            $count = $stmtCheck->fetchColumn();

            if ($count == 0) {
                $sqlUser = "UPDATE User SET username = ?, email = ?, phoneNumber = ? WHERE userId = ?";
                $stmtUser = $pdo->prepare($sqlUser);
                $stmtUser->execute([$username, $email, $phoneNumber, $userId]);
            } else {
                throw new PDOException("Username or Email already exists.");
            }

            // fetch current addressId via userId
            $sqlFetchAddressId = "SELECT addressId FROM Address WHERE userId = ?";
            $stmtFetchAddressId = $pdo->prepare($sqlFetchAddressId);
            $stmtFetchAddressId->execute([$userId]);
            $addressId = $stmtFetchAddressId->fetchColumn();

            if ($addressId) {
                // update existing address entry
                $sqlUpdateAddress = "UPDATE Address SET street = ?, city = ?, county = ?, postcode = ? WHERE addressId = ?";
                $stmtUpdateAddress = $pdo->prepare($sqlUpdateAddress);
                $stmtUpdateAddress->execute([$street, $city, $county, $postcode, $addressId]);
            } else {
                // insert new address entry if none exists
                $sqlInsertAddress = "INSERT INTO Address (userId, street, city, county, postcode) VALUES (?, ?, ?, ?, ?)";
                $stmtInsertAddress = $pdo->prepare($sqlInsertAddress);
                $stmtInsertAddress->execute([$userId, $street, $city, $county, $postcode]);
            }

            $pdo->commit();
            $successMsg = "Profile updated successfully!";
        } catch (PDOException $e) {
            $errorMsg = "Error updating profile: " . htmlspecialchars($e->getMessage());
        }
    }
}

// retrieve profile info
try {
    $sql = "SELECT U.username, U.email, U.phoneNumber, a.street, a.city, a.county, a.postcode
            FROM User U
            LEFT JOIN Address a ON a.userId = U.userId
            WHERE U.userId = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$userId]);
    $userProfile = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo '<div class="alert alert-danger">An error occurred while retrieving your profile: ' . htmlspecialchars($e->getMessage()) . '</div>';
    include_once("footer.php");
    exit;
}
?>

<!-- HTML for page to appear -->
<div class="container">
    <h2 class="my-3">Edit Profile</h2>

    <?php if ($errorMsg): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($errorMsg) ?></div>
    <?php endif; ?>
    <?php if ($successMsg): ?>
        <div class="alert alert-success"><?= htmlspecialchars($successMsg) ?></div>
    <?php endif; ?>
    <!-- Present current information with the ability for it to be changed -->
    <form method="POST" action="">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" name="username" id="username" class="form-control" value="<?= htmlspecialchars($userProfile['username'] ?? '') ?>" required>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" class="form-control" value="<?= htmlspecialchars($userProfile['email'] ?? '') ?>" required>
        </div>

        <div class="form-group">
            <label for="phoneNumber">Phone Number</label>
            <input type="text" name="phoneNumber" id="phoneNumber" class="form-control" value="<?= htmlspecialchars($userProfile['phoneNumber'] ?? '') ?>">
        </div>

        <h5 class="mt-4">Address</h5>
        <div class="form-group">
            <label for="street">Street</label>
            <input type="text" name="street" id="street" class="form-control" value="<?= htmlspecialchars($userProfile['street'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label for="city">City</label>
            <input type="text" name="city" id="city" class="form-control" value="<?= htmlspecialchars($userProfile['city'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label for="county">County</label>
            <input type="text" name="county" id="county" class="form-control" value="<?= htmlspecialchars($userProfile['county'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label for="postcode">Postcode</label>
            <input type="text" name="postcode" id="postcode" class="form-control" value="<?= htmlspecialchars($userProfile['postcode'] ?? '') ?>">
        </div>

        <button type="submit" class="btn btn-primary mt-3">Save Changes</button>
        <a href="profile.php" class="btn btn-secondary mt-3">Cancel</a>
    </form>
</div>

<?php include_once("footer.php"); ?>