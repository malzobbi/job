<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../autoload.php'; // if using Composer

$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'your_email@gmail.com';   // SMTP email
    $mail->Password   = 'your_app_password';      // Gmail App Password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    // Sender
    $mail->setFrom('your_email@gmail.com', 'Job Portal');

    // Recipient
    $mail->addAddress('company@example.com', 'Company Name');

    // Email content
    $mail->isHTML(true);
    $mail->Subject = 'Password Reset Notification';
    $mail->Body    = '
        <h3>Password Reset</h3>
        <p>Your password has been reset.</p>
        <p><strong>New Password:</strong> R4rr$AweeR1442</p>
        <p>Please change it after logging in.</p>
    ';

    $mail->AltBody = 'Your password has been reset. New password: R4rr$AweeR1442';

    $mail->send();
    echo "Email sent successfully";

} catch (Exception $e) {
    echo "Email could not be sent. Error: {$mail->ErrorInfo}";
}
