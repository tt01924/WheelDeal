<?php
require_once 'mail_function_test.php';
require_once 'db_connect.php';
require_once 'user_interactions.php';

function checkEndedAuctions() {
    global $pdo;

    // Get current time
    $currentTime = date('Y-m-d H:i:s');

    // Find ended auctions that haven't had notifications sent
    $sql = "SELECT itemId FROM Item
            WHERE endTime <= ?
            AND notificationSent = 0";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$currentTime]);

    // Process each ended auction
    while ($auction = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $itemId = $auction['itemId'];

        // Get auction outcome
        $outcome = checkAuctionOutcome($itemId);

        if ($outcome) {
            // Send notifications
            sendAuctionEndNotifications($outcome);
            $watchers = getWatchersEmails($itemId);
            foreach ($watchers as $email) {
                sendWatcherEndNotification($email, $outcome);
            }

            // Mark notification as sent
            $updateSql = "UPDATE Item SET notificationSent = 1 WHERE itemId = ?";
            $updateStmt = $pdo->prepare($updateSql);
            $updateStmt->execute([$itemId]);
        }
    }
}