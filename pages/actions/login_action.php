<?php
require_once '../../includes/config.php';
require_once '../../includes/session.php';
require_once "log_admin_actions.php";
require_once 'mailer.php';

session_start();
header('Content-Type: application/json');

$response = ['success' => false, 'message' => '', 'user_type' => null];

if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    $response['message'] = "Invalid request. Please try again.";
    http_response_code(400); // Bad Request
    echo json_encode($response);
    exit();
}

if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
    $_SESSION['lockout_time'] = null;
}

if (isset($_SESSION['lockout_time']) && new DateTime() >= new DateTime($_SESSION['lockout_time'])) {
    $_SESSION['login_attempts'] = 0;
    $_SESSION['lockout_time'] = null;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    $errors = [];

    if ($_SESSION['lockout_time'] && new DateTime() < new DateTime($_SESSION['lockout_time'])) {
        $remaining_lockout = (new DateTime($_SESSION['lockout_time']))->getTimestamp() - time();
        $response['message'] = "Too many failed login attempts. Try again in $remaining_lockout seconds.";
        http_response_code(429); // Too Many Requests
        echo json_encode($response);
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }
    if (empty($email) || empty($password)) {
        $errors[] = "Both fields are required.";
    }

    if (empty($errors)) {

        if (!checkUserPassword($pdo, $email, $password)) {
            $_SESSION['login_attempts']++;
            $remaining_attempts = 5 - $_SESSION['login_attempts'];

            if ($_SESSION['login_attempts'] >= 10) {
                // Lockout user for 30 seconds
                $_SESSION['lockout_time'] = (new DateTime())->add(new DateInterval('PT30S'))->format('Y-m-d H:i:s');
                $response['message'] = "Too many failed login attempts. Your account is locked for 30 seconds.";
                http_response_code(429); // Too Many Requests
            } else {
                $response['message'] = "Incorrect email or password.";
                http_response_code(401); // Unauthorized
            }
        } else {
            $_SESSION['login_attempts'] = 0;
            $_SESSION['lockout_time'] = null;

            $stmt = $pdo->prepare("SELECT id, user_type, is_verified, otp_sent_count, last_otp_sent FROM accounts WHERE email = :email");
            $stmt->execute([':email' => $email]);
            $account = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($account) {

                if ($account['is_verified'] === 0) {

                    $last_otp_sent = strtotime($account['last_otp_sent']);
                    $now = time();

                    if ($last_otp_sent !== false && ($now - $last_otp_sent) <= 86400) { // within 24 hours
                        if ($account['otp_sent_count'] >= 5) {
                            echo json_encode(["success" => false, "message" => " You’ve reached the OTP resend limit for today."]);
                            exit;
                        }
                        $otp_count = $account['otp_sent_count'] + 1;
                    } else {
                        $otp_count = 1; // reset counter if more than 24 hours
                    }

                    // Generate OTP
                    $otp_code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
                    $otp_expiry = date('Y-m-d H:i:s', strtotime('+5 minutes'));

                    $otp_sent_count = 1;
                    $last_otp_sent = date("Y-m-d H:i:s");

                    // Insert the otp code in the database
                    $stmt = $pdo->prepare("UPDATE accounts 
                    SET otp_code = :otp_code,
                        otp_expiry = :otp_expiry,
                        otp_sent_count = :otp_sent_count,
                        last_otp_sent = :last_otp_sent
                    WHERE email = :email");

                    $insertSuccess = $stmt->execute([
                        'otp_code' => $otp_code,
                        'otp_expiry' => $otp_expiry,
                        'otp_sent_count' => $otp_sent_count,
                        'last_otp_sent' => $last_otp_sent,
                        'email' => $email
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

                        unset($_SESSION['csrf_token']);
                        http_response_code(201); // Created
                        echo json_encode([
                            'success' => true,
                            'message' => 'Verification code sent! Please check your email for the OTP and enter it below to continue.',
                            'isVerified' => false
                        ]);
                        exit;
                    } else {
                        $error_message = sendEmail($email, $subject, $body, $altBody);

                        http_response_code(500); // Internal Server Error
                        echo json_encode(['success' => false, 'message' => 'Failed to send the otp verification.']);
                        exit;
                    }

                    $response = [
                        'success' => false,
                        'isVerified' => false,
                        'message' => "Account not verified.<br><br>Check your email for the otp."
                    ];
                    http_response_code(401); // Unauthorized
                    echo json_encode($response);
                    exit();
                }

                // // Send Login Email Notification
                // $subject = "Login Alert - Public Market Monitoring System";
                // $body = "
                //     Hello,

                //     This is an automated message from the Public Market Monitoring System. Please DO NOT reply to this email.
                //     <br><br>
                //     We noticed a login to your account just now.
                //     <br><br>
                //     <b>Date & Time:</b> " . date("F j, Y, g:i a") . "<br>
                //     <b>IP Address:</b> " . $_SERVER['REMOTE_ADDR'] . "<br>
                //     <b>Device/Browser:</b> " . $_SERVER['HTTP_USER_AGENT'] . "
                //     <br><br>
                //     If this was you, no further action is needed.
                //     <br>
                //     If this wasn't you, please reset your password immediately or contact our support team.
                //     <br><br>
                //     Thank you,<br>
                //     Public Market Monitoring System
                // ";
                // $altBody = "Hello,\n\nThis is an automated login alert from the Public Market Monitoring System.\n\n"
                //     . "Date & Time: " . date("F j, Y, g:i a") . "\n"
                //     . "IP Address: " . $_SERVER['REMOTE_ADDR'] . "\n"
                //     . "Device/Browser: " . $_SERVER['HTTP_USER_AGENT'] . "\n\n"
                //     . "If this was not you, please reset your password immediately or contact support.\n\n"
                //     . "Thank you,\nPublic Market Monitoring System";


                // if (!sendEmail($email, $subject, $body, $altBody)) {
                //     $error_message = sendEmail($email, $subject, $body, $altBody);
                //     error_log($error_message);
                // }

                $_SESSION['user_type'] = $account['user_type'];
                $_SESSION['account_id'] = $account['id'];

                switch ($account['user_type']) {
                    case 'Admin':
                        $_SESSION['admin_id'] = $account['id'];
                        logAdminAction($pdo, $_SESSION['admin_id'], "Logged In", "IP: " . $_SERVER['REMOTE_ADDR']);
                        break;

                    case 'Inspector':
                        $_SESSION['inspector_id'] = $account['id'];
                        logAdminAction($pdo, $_SESSION['inspector_id'], "Logged In", "IP: " . $_SERVER['REMOTE_ADDR']);
                        break;
                }

                $response = [
                    'success' => true,
                    'isVerified' => true,
                    'user_type' => $account['user_type']
                ];

                http_response_code(200); // OK
                unset($_SESSION['csrf_token']);
            } else {

                $response['message'] = "Account not found.";
                http_response_code(404); // Not Found
            }
        }
    } else {
        $response['message'] = implode(" ", $errors);
        http_response_code(400); // Bad Request (validation errors)
    }
}

echo json_encode($response);
exit();


function checkUserPassword($pdo, $email, $password)
{
    $stmt = $pdo->prepare("SELECT password FROM accounts WHERE email = :email LIMIT 1");
    $stmt->execute([':email' => $email]);

    if ($account = $stmt->fetch(PDO::FETCH_ASSOC)) {
        return password_verify($password, $account['password']);
    }

    return false;
}
