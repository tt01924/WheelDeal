<?php
/*
* Filename: mail_notifications.php
* Purpose: Handles email notifications for auction events
* Dependencies: PHPMailer library, vendor/autoload.php
* Flow: Receives auction outcome -> Generates appropriate email -> Sends via SMTP
*/


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

// Main function to handle auction end notifications
function sendAuctionEndNotifications($auctionOutcome) {
    // Extract data from auction outcome
    $itemDescription = $auctionOutcome['description'];
    $reservePrice = $auctionOutcome['reservePrice'];
    $highestBid = $auctionOutcome['highestBid'];
    $sellerEmail = $auctionOutcome['sellerEmail'];
    $winnerEmail = $auctionOutcome['winnerEmail'];
    $itemId = $auctionOutcome['itemId'];

    // Create base URL for item link
    $itemUrl = "http://localhost/WheelDeal/listing.php?item_id=" . $itemId;

    // Handle different auction outcomes with appropriate emails
    if ($highestBid === null) {
        // No bids case
        $subject = "Your auction has ended - No bids received";
        $body = "
        <html>
        <body style='font-family: Arial, sans-serif;'>
            <div style='max-width: 600px; margin: 0 auto; padding: 20px;'>
                <h2>Auction Ended: No Bids Received</h2>
                <p>Your auction has ended without receiving any bids.</p>
                <hr>
                <h3>Auction Details:</h3>
                <ul>
                    <li>Item: {$itemDescription}</li>
                    <li>Reserve Price: £{$reservePrice}</li>
                </ul>
                <p>You can <a href='{$itemUrl}'>view your listing here</a>.</p>
                <p>Feel free to relist your item at any time.</p>
                <br>
                <p>Best regards,<br>WheelDeal Team</p>
            </div>
        </body>
        </html>";
        sendEmail($sellerEmail, $subject, $body);
    }
    else if ($highestBid < $reservePrice) {
        // Reserve price not met
        $subject = "Your auction has ended - Reserve price not met";
        $body = "
        <html>
        <body style='font-family: Arial, sans-serif;'>
            <div style='max-width: 600px; margin: 0 auto; padding: 20px;'>
                <h2>Auction Ended: Reserve Price Not Met</h2>
                <p>Your auction has ended, but the highest bid did not meet the reserve price.</p>
                <hr>
                <h3>Auction Details:</h3>
                <ul>
                    <li>Item: {$itemDescription}</li>
                    <li>Reserve Price: £{$reservePrice}</li>
                    <li>Highest Bid Received: £{$highestBid}</li>
                </ul>
                <p>You can <a href='{$itemUrl}'>view your listing here</a>.</p>
                <p>You may want to consider relisting with a lower reserve price.</p>
                <br>
                <p>Best regards,<br>WheelDeal Team</p>
            </div>
        </body>
        </html>";
        sendEmail($sellerEmail, $subject, $body);
    }
    else {
        // Successful sale - Send email to winner
        $winnerSubject = "Congratulations! You've won the auction";
        $winnerBody = "
        <html>
        <body style='font-family: Arial, sans-serif;'>
            <div style='max-width: 600px; margin: 0 auto; padding: 20px;'>
                <h2>🎉 Congratulations! You've Won!</h2>
                <p>You are the winning bidder for this auction.</p>
                <hr>
                <h3>Auction Details:</h3>
                <ul>
                    <li>Item: {$itemDescription}</li>
                    <li>Your Winning Bid: £{$highestBid}</li>
                </ul>
                <p>You can <a href='{$itemUrl}'>view the item here</a>.</p>
                <p>Next Steps:</p>
                <ol>
                    <li>Please complete payment within 48 hours</li>
                    <li>The seller will contact you with shipping details</li>
                    <li>Leave feedback once you receive your item</li>
                </ol>
                <br>
                <p>Best regards,<br>WheelDeal Team</p>
            </div>
        </body>
        </html>";
        sendEmail($winnerEmail, $winnerSubject, $winnerBody);

        // Send email to seller
        $sellerSubject = "Your item has sold!";
        $sellerBody = "
        <html>
        <body style='font-family: Arial, sans-serif;'>
            <div style='max-width: 600px; margin: 0 auto; padding: 20px;'>
                <h2>🎉 Your Item Has Sold!</h2>
                <p>Your auction has ended successfully.</p>
                <hr>
                <h3>Auction Details:</h3>
                <ul>
                    <li>Item: {$itemDescription}</li>
                    <li>Final Selling Price: £{$highestBid}</li>
                </ul>
                <p>You can <a href='{$itemUrl}'>view the listing here</a>.</p>
                <p>Next Steps:</p>
                <ol>
                    <li>Wait for the buyer's payment (48 hours)</li>
                    <li>Contact the buyer with shipping details</li>
                    <li>Ship the item promptly once payment is received</li>
                    <li>Leave feedback for the buyer</li>
                </ol>
                <br>
                <p>Best regards,<br>WheelDeal Team</p>
            </div>
        </body>
        </html>";
        sendEmail($sellerEmail, $sellerSubject, $sellerBody);
    }
}


// Helper function to send emails via SMTP
function sendEmail($to, $subject, $body) {
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'wheel.deal.project@gmail.com';
        $mail->Password = 'zckh esta jite ndxy';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        //Recipients
        $mail->setFrom('wheel.deal.project@gmail.com', 'WheelDeal Auctions');
        $mail->addAddress($to);

        //Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body;

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Mail Error: {$mail->ErrorInfo}");
        return false;
    }
}


// Notification for users watching an auction
function sendWatcherEndNotification($email, $itemDetails) {
    $finalPrice = isset($itemDetails['highestBid']) ? $itemDetails['highestBid'] : 'No bids';
    $subject = "Watched auction has ended";
    $body = "
    <html>
    <body style='font-family: Arial, sans-serif;'>
        <div style='max-width: 600px; margin: 0 auto; padding: 20px;'>
            <h2>Auction Ended</h2>
            <p>An auction you were watching has ended:</p>
            <ul>
                <li>Item: {$itemDetails['description']}</li>
                <li>Final Price: £{$finalPrice}</li>
            </ul>
        </div>
    </body>
    </html>";
    sendEmail($email, $subject, $body);
}
?>