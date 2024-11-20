<?php
// db_connect.php
include 'db_connect.php';

function registerUser($username, $password, $email, $phoneNumber, $userType) {
    global $pdo;
    $sql = "INSERT INTO User (username, password, email, phoneNumber, userType) VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $hashedPassword = hash('sha256', $password);
    return $stmt->execute([$username, $hashedPassword, $email, $phoneNumber, $userType]);
}

function getUserId($username) {
    global $pdo;
    $sql = "SELECT userId FROM User WHERE username = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$username]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result ? $result['userId'] : null;
}


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

function createAuction($description, $endTime, $reservePrice, $itemCondition, $image, $tags, $userId, $categoryId) {
    global $pdo;
    $sql = "INSERT INTO Item (description, endTime, reservePrice, itemCondition, image, tags, userId, categoryId) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$description, $endTime, $reservePrice, $itemCondition, $image, $tags, $userId, $categoryId]);
}

function browseItems() {
    global $pdo;
    $sql = "SELECT * FROM Item";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function searchItemByDescription($searchTerm) {
    global $pdo;
    $sql = "SELECT * FROM Item WHERE description LIKE CONCAT('%', ?, '%')";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$searchTerm]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function filterItemsByCategory($categoryId) {
    global $pdo;
    $sql = "SELECT * FROM Item WHERE categoryId = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$categoryId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function sortItemsByPrice() {
    global $pdo;
    $sql = "SELECT * FROM Item ORDER BY reservePrice ASC";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function sortItemsByEndTime() {
    global $pdo;
    $sql = "SELECT * FROM Item ORDER BY endTime ASC";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function getCurrentHighestBid($itemId) {
    global $pdo;
    $sql = "SELECT MAX(amount) AS highestBid FROM Bid WHERE itemId = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$itemId]);
    return $stmt->fetch(PDO::FETCH_ASSOC)['highestBid'];
}

function checkAuctionOutcome($itemId) {
    global $pdo;
    $sql = "SELECT 
                i.itemId, 
                i.userId AS sellerId, 
                MAX(b.amount) AS highestBid, 
                b.userId AS highestBidder
            FROM 
                Item i 
            LEFT JOIN 
                Bid b ON i.itemId = b.itemId
            WHERE 
                i.itemId = ? AND 
                i.endTime < NOW()
            GROUP BY 
                i.itemId";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$itemId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}


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


function removeItemFromWatchList($watchListId, $itemId) {
    global $pdo;
    $sql = "DELETE FROM WatchListEntry WHERE watchListId = ? AND itemId = ?";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$watchListId, $itemId]);
}


function recommendItems($userId) {
    global $pdo;
    $sql = "SELECT DISTINCT i.itemId, i.description, i.reservePrice, i.endTime
            FROM Item i
            JOIN Bid b ON i.itemId = b.itemId
            WHERE b.userId IN (
                SELECT DISTINCT b2.userId
                FROM Bid b2
                WHERE b2.itemId IN (
                    SELECT itemId
                    FROM Bid
                    WHERE userId = ?
                )
            ) AND i.itemId NOT IN (
                SELECT itemId
                FROM Bid
                WHERE userId = ?
            )";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$userId, $userId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
