<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once '../../vendor/autoload.php';

loadEnv(__DIR__ . '/../../.env');

$key = getenv("EMAIL_APP_PASSWORD") ?: ($_ENV["EMAIL_APP_PASSWORD"] ?? null);
if (!$key) {
    echo json_encode(["error" => "Key not configured"]);
    exit;
}

function sendEmail($recipient_address, $subject, $body, $altBody)
{

    global $key;

    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'info.publicmarketmonitoring@gmail.com';
        $mail->Password   = $key;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Sender and recipient
        $mail->setFrom('info.publicmarketmonitoring@gmail.com', 'Public Market Monitoring System');
        $mail->addAddress($recipient_address);
        $mail->addCustomHeader('Auto-Submitted', 'auto-generated');
        $mail->addCustomHeader('Precedence', 'bulk');

        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;
        $mail->AltBody = $altBody;

        $mail->send();
        return true;
    } catch (Exception $e) {
        return "Mailer Error: {$mail->ErrorInfo}"; // Return error as a string
    }
}

function loadEnv($path)
{
    if (!file_exists($path)) {
        error_log("Warning: .env file not found at: $path");
        return;
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0 || strpos($line, '=') === false) continue;
        list($key, $value) = array_map('trim', explode('=', $line, 2));

        // Remove quotes if value is enclosed in single or double quotes
        $value = trim($value, "\"'");

        putenv("$key=$value");
        $_ENV[$key] = $value;
        $_SERVER[$key] = $value;
    }
}
