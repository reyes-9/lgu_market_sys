<?php
require_once '../../includes/config.php';

session_start();
header('Content-Type: application/json'); // Set JSON response type

$response = ['success' => false, 'message' => '', 'user_type' => null]; // Default response

// Check CSRF token
if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    $response['message'] = "Invalid request. Please try again.";
    http_response_code(400); // Bad Request
    echo json_encode($response);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    $errors = [];

    if (!isset($_SESSION['login_attempts'])) {
        $_SESSION['login_attempts'] = 0;
        $_SESSION['lockout_time'] = null;
    }

    // Check if account is locked
    if ($_SESSION['lockout_time'] && new DateTime() < new DateTime($_SESSION['lockout_time'])) {
        $response['message'] = "Your account is locked. Please try again later.";
        http_response_code(429); // Too Many Requests (Locked Out)
        echo json_encode($response);
        exit();
    }

    // Validate inputs
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if (empty($password) || empty($email)) {
        $errors[] = "Both fields are required.";
    }

    if (empty($errors)) {
        // Check user credentials
        if (!checkUserPassword($pdo, $email, $password)) {
            $_SESSION['login_attempts']++;
            $remaining_attempts = 3 - $_SESSION['login_attempts'];

            if ($_SESSION['login_attempts'] >= 4) {
                $_SESSION['lockout_time'] = (new DateTime())->add(new DateInterval('PT1M'))->format('Y-m-d H:i:s');
                $response['message'] = "Too many failed login attempts. Your account is locked for 1 minute.";
                http_response_code(429); // Too Many Requests
            } else {
                $response['message'] = "Incorrect email or password. You have $remaining_attempts attempt(s) left.";
                http_response_code(401); // Unauthorized
            }
        } else {
            $_SESSION['login_attempts'] = 0;
            $_SESSION['lockout_time'] = null;

            $stmt = $pdo->prepare("SELECT id, user_type FROM accounts WHERE email = :email");
            $stmt->execute([':email' => $email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            $_SESSION['user_type'] = $user['user_type'];
            $_SESSION['user_id'] = $user['id'];

            $response['success'] = true;
            $response['user_type'] = $user['user_type'];
            http_response_code(200); // OK (successful login)

            unset($_SESSION['csrf_token']);
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
    $stmt = $pdo->prepare("SELECT password FROM accounts WHERE email = :email");
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    return $user && password_verify($password, $user['password']);
}
