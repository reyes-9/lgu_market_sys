<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../vendor/autoload.php'; // Path to PHPMailer

function sendEmail($to, $subject, $body)
{
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP(); // Set mailer to use SMTP
        $mail->Host       = 'publicmarketmonitoringsystem.lgu1.com'; // SMTP server for your domain
        $mail->SMTPAuth   = true;
        $mail->Username   = 'info@publicmarketmonitoringsystem.lgu1.com';
        $mail->Password   = 'sp-37ADJpeMiI11G';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Use STARTTLS encryption
        $mail->Port       = 587; // Port for STARTTLS (use 465 for SSL)

        // Set sender information
        $mail->setFrom('info@publicmarketmonitoringsystem.lgu1.com', 'System');
        $mail->addAddress($to); // Recipient's email address

        // Set email format to HTML
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;
        $mail->SMTPDebug = 2;

        $mail->send();
        return true; // Email sent successfully
    } catch (Exception $e) {
        error_log("Mailer Error: {$mail->ErrorInfo}");

        return false; // Error occurred 
    }
}
