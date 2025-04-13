<?php
require_once "../../includes/config.php";
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['otp_email'] ?? '';
    $otp = $_POST['otp'] ?? '';

    if (empty($email) || empty($otp)) {
        echo json_encode(['success' => false, 'message' => 'Missing email or OTP.']);
        exit;
    }

    $stmt = $pdo->prepare("SELECT otp_code, otp_expiry FROM accounts WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

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

    // Mark account as verified
    $update = $pdo->prepare("UPDATE accounts SET is_verified = 1, otp_code = NULL, otp_expiry = NULL WHERE email = :email");
    $update->execute(['email' => $email]);

    echo json_encode(['success' => true, 'message' => 'Account verified successfully.']);
    exit;
}
