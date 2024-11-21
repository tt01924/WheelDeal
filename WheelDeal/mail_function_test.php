<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

// Create a new PHPMailer instance
$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';  // Set the SMTP server to send through
    $mail->SMTPAuth = true;
    $mail->Username = 'wheel.deal.project@gmail.com';  // Your Gmail email address
    $mail->Password = 'zckh esta jite ndxy';  // Your Gmail password or app password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    //Recipients
    $mail->setFrom('wheel.deal.project@gmail.com', 'Mailer');
    $mail->addAddress('jdc.simpson@icloud.com', 'Recipient Name');  // ADD A RECEIPIENT E.G. YOUR EMAIL

    //Content
    $mail->isHTML(true);
    $mail->Subject = 'WheelDeal Test Email';
    $mail->Body    = 'This is a test email sent using PHPMailer for the WheelDeal Project.';

    // Send the email
    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>