<?php
session_start();
require_once "../../includes/config.php";
require_once '../../includes/session.php';
require_once 'mailer.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    try {
        // Get form data & sanitize input
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);
        $confirm_password = trim($_POST['confirm_password']);
        $csrf_token = $_POST['csrf_token'];

        // Validate CSRF token
        if (!isset($_SESSION['csrf_token']) || $csrf_token !== $_SESSION['csrf_token']) {
            http_response_code(400); // Bad Request
            echo json_encode(['success' => false, 'message' => 'Error signing in.']);
            exit;
        }

        // Validate required fields
        if (empty($email) || empty($password) || empty($confirm_password)) {
            http_response_code(400); // Bad Request
            echo json_encode(['success' => false, 'message' => 'All fields are required.']);
            exit;
        }

        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            http_response_code(400); // Bad Request
            echo json_encode(['success' => false, 'message' => 'Invalid email format.']);
            exit;
        }

        // Check if passwords match
        if ($password !== $confirm_password) {
            http_response_code(400); // Bad Request
            echo json_encode(['success' => false, 'message' => 'Passwords do not match.']);
            exit;
        }

        // Check password length
        if (strlen($password) < 8) {
            http_response_code(400); // Bad Request
            echo json_encode(['success' => false, 'message' => 'Password must be at least 8 characters long.']);
            exit;
        }

        // Hash the password securely
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Start PDO Transaction
        $pdo->beginTransaction();

        // Generate OTP
        $otp_code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $otp_expiry = date('Y-m-d H:i:s', strtotime('+5 minutes'));

        // Check if email is already registered
        $stmt = $pdo->prepare("SELECT id FROM accounts WHERE email = :email");
        $stmt->execute(['email' => $email]);

        if ($stmt->rowCount() > 0) {
            $pdo->rollBack(); // Rollback transaction if email exists
            http_response_code(400); // Bad Request
            echo json_encode(['success' => false, 'message' => 'Email already registered.']);
            exit;
        }

        $otp_sent_count = 1;
        $last_otp_sent = date("Y-m-d H:i:s");

        // Insert new account
        $stmt = $pdo->prepare("INSERT INTO accounts 
            (email, password, otp_code, otp_expiry, otp_sent_count, last_otp_sent)
            VALUES (:email, :password, :otp_code, :otp_expiry, :otp_sent_count, :last_otp_sent)");

        $insertSuccess = $stmt->execute([
            'email' => $email,
            'password' => $hashed_password,
            'otp_code' => $otp_code,
            'otp_expiry' => $otp_expiry,
            'otp_sent_count' => $otp_sent_count,
            'last_otp_sent' => $last_otp_sent
        ]);

        // Send OTP email
        $subject = "Your Verification Code";
        $body = "
        Hello,

        This is an automated message from Public Market Monitoring System. Please DO NOT reply to this email.
                <br><br>
        Your OTP code is: <b>$otp_code</b>
                <br><br>
        Keep it secure and do not share it with anyone.
                <br><br>
        Thank you,<br>
        Public Market Monitoring System
        ";
        $altBody = "Hello,\n\nYour OTP code is: $otp_code\n\nPlease do not share this code with anyone.\n\nRegards,\nPublic Market Monitoring System";

        if (sendEmail($email, $subject, $body, $altBody)) {
            // If OTP is sent successfully, commit the transaction
            $pdo->commit();
            unset($_SESSION['csrf_token']);
            http_response_code(201); // Created
            echo json_encode(['success' => true, 'message' => 'Verification code sent! Please check your email for the OTP and enter it below to continue.']);
            exit;
        } else {
            // If email sending failed, rollback transaction
            $pdo->rollBack();
            $error_message = sendEmail($email, $subject, $body, $altBody);
            // error_log($error_message);
            http_response_code(500); // Internal Server Error
            echo json_encode(['success' => false, 'message' => 'Failed to send the otp verification.']);
            exit;
        }
    } catch (PDOException $e) {
        // Ensure rollback on exception
        $pdo->rollBack();
        http_response_code(500); // Internal Server Error
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
        exit;
    }
}
