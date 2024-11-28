<?php
/*
* Filename: check_ended_auctions.php
* Purpose: Monitors and processes completed auctions
* Dependencies: mail_function_test.php, db_connect.php, user_interactions.php
* Flow: Checks for ended auctions, processes outcomes, sends notifications
*/

require_once 'mail_function_test.php';
require_once 'db_connect.php';
require_once 'user_interactions.php';

// Main function to process completed auctions
function checkEndedAuctions() {
    global $pdo;

    // Get current timestamp for comparison
    $currentTime = date('Y-m-d H:i:s');

    // Query to find auctions that have ended but haven't sent notifications
    $sql = "SELECT itemId FROM Item
            WHERE endTime <= ?
            AND notificationSent = 0";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$currentTime]);

    // Process each completed auction
    while ($auction = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $itemId = $auction['itemId'];

        // Determine auction winner and final price
        $outcome = checkAuctionOutcome($itemId);

        if ($outcome) {
            // Notify relevant parties about auction completion
            sendAuctionEndNotifications($outcome);
            $watchers = getWatchersEmails($itemId);
            foreach ($watchers as $email) {
                sendWatcherEndNotification($email, $outcome);
            }

            // Update auction status to prevent duplicate notifications
            $updateSql = "UPDATE Item SET notificationSent = 1 WHERE itemId = ?";
            $updateStmt = $pdo->prepare($updateSql);
            $updateStmt->execute([$itemId]);
        }
    }
}
?>