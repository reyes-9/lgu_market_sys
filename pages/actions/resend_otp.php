<?php
require_once "../../includes/config.php";
require_once '../../includes/session.php';
require_once '../../vendor/autoload.php';

error_reporting(E_ALL & ~E_WARNING);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

header("Content-Type: application/json");

loadEnv(__DIR__ . '/../../.env');

$key = getenv("EMAIL_APP_PASSWORD") ?: ($_ENV["EMAIL_APP_PASSWORD"] ?? null);
if (!$key) {
    echo json_encode(["error" => "Key not configured"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$email = filter_var($data['email'] ?? '', FILTER_SANITIZE_EMAIL);

if (!$email) {
    echo json_encode(['success' => false, 'message' => 'Email is required.']);
    exit;
}

// Check if account exists
$stmt = $pdo->prepare("SELECT id, otp_sent_count, last_otp_sent FROM accounts WHERE email = :email");
$stmt->execute(['email' => $email]);
$user = $stmt->fetch();

if (!$user) {
    echo json_encode(['success' => false, 'message' => 'No account found for this email.']);
    exit;
}

// Check on how many otp's are sent this day
$last_otp_sent = strtotime($user['last_otp_sent']);
$now = time();

if ($last_otp_sent !== false && ($now - $last_otp_sent) <= 86400) { // within 24 hours
    if ($user['otp_sent_count'] >= 5) {
        echo json_encode(["success" => false, "message" => "You’ve reached the OTP resend limit for today."]);
        exit;
    }
    $otp_count = $user['otp_sent_count'] + 1;
} else {
    $otp_count = 1; // reset counter if more than 24 hours
}


// Generate new OTP
$otp = random_int(100000, 999999);
$otp_expiry = date("Y-m-d H:i:s", strtotime("+5 minutes"));

// Update OTP + resend count + timestamp in DB
$update = $pdo->prepare("UPDATE accounts 
    SET otp_code = :otp, 
        otp_expiry = :expiry, 
        otp_sent_count = :otp_count, 
        last_otp_sent = :last_otp_sent
    WHERE email = :email");

$current_datetime = date("Y-m-d H:i:s", $now);
$update->execute([
    'otp' => $otp,
    'expiry' => $otp_expiry,
    'otp_count' => $otp_count,
    'last_otp_sent' => $current_datetime,
    'email' => $email
]);

// Send OTP via email
$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'info.publicmarketmonitoring@gmail.com';
    $mail->Password   = $key;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    $mail->setFrom('info@publicmarketmonitoringsystem.lgu1.com', 'Public Market Monitoring System');
    $mail->addAddress($email);

    $mail->isHTML(true);
    $mail->Subject = "Your Verification Code";
    $mail->Body = "<p>Hello,</p><p>Your new verification code is: <strong>$otp</strong></p><p>This code will expire in 5 minutes.</p>";

    $mail->send();

    echo json_encode([
        'success' => true,
        'message' => 'OTP has been resent to your email.',
        'otp_expiry' => $otp_expiry,
        'expires_in' => 300
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Failed to send OTP. Please try again.',
        'error' => $mail->ErrorInfo
    ]);
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
