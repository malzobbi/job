<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'autoload.php'; // Load PHPMailer (installed via Composer)

$mail = new PHPMailer(true);

try {
    // SMTP SETTINGS
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'yourgmail@gmail.com';       // Your Gmail address
    $mail->Password   = 'YOUR_APP_PASSWORD';         // Gmail App Password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    // SENDER
    $mail->setFrom('yourgmail@gmail.com', 'Your Name');

    // RECIPIENT
    $mail->addAddress('recipient@example.com'); // Change as needed

    // EMAIL CONTENT
    $mail->isHTML(true);
    $mail->Subject = 'Test Email from PHPMailer using Gmail SMTP';
    $mail->Body    = '<h3>Hello!</h3><p>This is a test email sent using <b>Gmail SMTP + PHPMailer</b>.</p>';
    $mail->AltBody = 'Hello! This is a test email sent using Gmail SMTP.';

    // SEND
    $mail->send();
    echo "Email sent successfully!";
} catch (Exception $e) {
    echo "Error sending email: {$mail->ErrorInfo}";
}
