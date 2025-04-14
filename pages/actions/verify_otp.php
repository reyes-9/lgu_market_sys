<?php
require_once "../../includes/config.php";
require_once '../../includes/session.php';
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['otp_email'] ?? '';
    $otp = $_POST['otp'] ?? '';

    if (empty($email) || empty($otp)) {
        echo json_encode(['success' => false, 'message' => 'Missing email or OTP.']);
        exit;
    }

    $stmt = $pdo->prepare("SELECT otp_code, otp_expiry, otp_sent_count, last_otp_sent FROM accounts WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    $last_otp_sent = strtotime($user['last_otp_sent']);
    $now = time();

    if (!$user) {
        echo json_encode(['success' => false, 'message' => 'Invalid email.']);
        exit;
    }

    if ($user['otp_code'] !== $otp) {
        echo json_encode(['success' => false, 'message' => 'Invalid OTP.']);
        exit;
    }

    if (strtotime($user['otp_expiry']) < time()) {
        echo json_encode(['success' => false, 'message' => 'OTP expired.']);
        exit;
    }

    if ($last_otp_sent !== false && ($now - $last_otp_sent) <= 86400) { // within 24 hours
        if ($user['otp_sent_count'] >= 5) {
            echo json_encode(["success" => false, "message" => "You’ve reached the OTP resend limit for today."]);
            exit;
        }
        $otp_count = $user['otp_sent_count'] + 1;
    } else {
        $otp_count = 1; // reset counter if more than 24 hours
    }

    // Mark account as verified
    $update = $pdo->prepare("UPDATE accounts SET is_verified = 1, otp_code = NULL, otp_expiry = NULL WHERE email = :email");
    $update->execute(['email' => $email]);

    echo json_encode(['success' => true, 'message' => 'Account verified successfully.']);
    exit;
}
