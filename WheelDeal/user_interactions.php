<?php
/*
* File: user_interactions.php
* Purpose: Provides database interaction functions for user management, auctions, and notifications
* Dependencies: db_connect.php
* Flow: Check existing users -> Hash passwords -> Store/retrieve user data -> Handle auction operations -> Send notifications
*/

// db_connect.php
include 'db_connect.php';

// Register user if they don't already exist
function registerUser($username, $password, $email, $phoneNumber, $userType) {
    global $pdo;

    // Check if username exists
    $sql = "SELECT COUNT(*) FROM User WHERE username = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$username]);
    if ($stmt->fetchColumn() > 0) {
        return false; 
    }

    // Check if email exists
    $sql = "SELECT COUNT(*) FROM User WHERE email = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);
    if ($stmt->fetchColumn() > 0) {
        return false; 
    }

    // If email and username don't exist yet, insert user
    $sql = "INSERT INTO User (username, password, email, phoneNumber, userType) VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $hashedPassword = hash('sha256', $password);
    $stmt->execute([$username, $hashedPassword, $email, $phoneNumber, $userType]);
    return true;
}
// Retrieves userId based on username
function getUserId($username) {
    global $pdo;
    $sql = "SELECT userId FROM User WHERE username = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$username]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result ? $result['userId'] : null;
}
// Authenticate login details
function loginUser($email, $password) {
    global $pdo;
    $sql = "SELECT * FROM User WHERE email = ?"; 
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

   // Verify password
    if ($user && hash('sha256', $password) === $user['password']) {
        return $user;
    }
    return false;
}
// Create a new auction listing
function createAuction($description, $endTime, $reservePrice, $itemCondition, $image, $tags, $userId, $categoryId) {
    global $pdo;
    $sql = "INSERT INTO Item (description, endTime, reservePrice, itemCondition, image, tags, userId, categoryId) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$description, $endTime, $reservePrice, $itemCondition, $image, $tags, $userId, $categoryId]);
}
// Retrieve all active auction listings
function browseItems() {
    global $pdo;
    $sql = "SELECT * FROM Item";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Search items by description
function searchItemByDescription($searchTerm) {
    global $pdo;
    $sql = "SELECT * FROM Item WHERE description LIKE CONCAT('%', ?, '%')";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$searchTerm]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
// Filter items by category
function filterItemsByCategory($categoryId) {
    global $pdo;
    $sql = "SELECT * FROM Item WHERE categoryId = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$categoryId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
// Sort items by price
function sortItemsByPrice() {
    global $pdo;
    $sql = "SELECT * FROM Item ORDER BY reservePrice ASC";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
// Sort items by auction end time
function sortItemsByEndTime() {
    global $pdo;
    $sql = "SELECT * FROM Item ORDER BY endTime ASC";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Check the outcome of the auction
function checkAuctionOutcome($itemId) {
    global $pdo;
    $sql = "SELECT 
                i.itemId,
                i.description,
                i.reservePrice,
                i.userId AS sellerId,
                MAX(b.amount) AS highestBid,
                b2.userId AS highestBidderId,
                (SELECT email FROM User WHERE userId = i.userId) AS sellerEmail,
                (SELECT email FROM User WHERE userId = b2.userId) AS winnerEmail
            FROM
                Item i
            LEFT JOIN
                Bid b ON i.itemId = b.itemId
            LEFT JOIN
                Bid b2 ON b.itemId = b2.itemId AND b2.amount = (SELECT MAX(amount) FROM Bid WHERE itemId = i.itemId)
            WHERE
                i.itemId = ? AND
                i.endTime < NOW()
            GROUP BY
                i.itemId, i.description, i.reservePrice, i.userId, b2.userId";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$itemId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
// Retrieve the emails of users who placed item on their watchlist
function getWatchersEmails($itemId) {
    global $pdo;
    $sql = "SELECT DISTINCT u.email
            FROM User u
            JOIN WatchList w ON u.userId = w.userId
            JOIN WatchListEntry we ON w.watchListId = we.watchListId
            WHERE we.itemId = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$itemId]);
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}
// Retrieve the most recent highest bider
function getPreviousHighestBidder($itemId, $newBidAmount) {
    global $pdo;
    $sql = "SELECT u.email
            FROM User u
            JOIN Bid b ON u.userId = b.userId
            WHERE b.itemId = ?
            AND b.amount = (
                SELECT MAX(amount)
                FROM Bid
                WHERE itemId = ? AND amount < ?
            )";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$itemId, $itemId, $newBidAmount]);
    return $stmt->fetch(PDO::FETCH_COLUMN);
}
// Add item to watchlist
function addItemToWatchList($userId, $itemId) {
    global $pdo;
    // Create a new watch list for the user if not exists
    $pdo->beginTransaction();
    
    // Insert WatchList if not exists (simplified)
    $sql = "INSERT IGNORE INTO WatchList (userId) VALUES (?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$userId]);
    $watchListId = $pdo->lastInsertId();

    // Insert item into WatchListEntry
    $sql = "INSERT INTO WatchListEntry (watchListId, itemId) VALUES (?, ?)";
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute([$watchListId, $itemId]);

    $pdo->commit();
    return $result;
}
// Remove items from watchlist
function removeItemFromWatchList($watchListId, $itemId) {
    global $pdo;
    $sql = "DELETE FROM WatchListEntry WHERE watchListId = ? AND itemId = ?";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$watchListId, $itemId]);
}
// Notify watchers of new bid
function notifyWatchersOfNewBid($itemId, $newBidAmount) {
    $watchers = getWatchersEmails($itemId);
    $previousBidder = getPreviousHighestBidder($itemId, $newBidAmount);
    $itemDetails = getItemDetails($itemId);

    // Notify watchers
    foreach ($watchers as $email) {
        $subject = "New bid on watched item: {$itemDetails['description']}";
        $body = "A new bid of £{$newBidAmount} has been placed.";
        sendEmail($email, $subject, $body);
    }

    // Notify outbid user
    if ($previousBidder) {
        $subject = "You've been outbid!";
        $body = "Someone has placed a higher bid of £{$newBidAmount} on {$itemDetails['description']}";
        sendEmail($previousBidder, $subject, $body);
    }
}
// Retrieve item details
function getItemDetails($itemId) {
    global $pdo;
    $sql = "SELECT description FROM Item WHERE itemId = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$itemId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// ?>
