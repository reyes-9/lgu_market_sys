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

// Initialize login attempts and lockout time
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
    $_SESSION['lockout_time'] = null;
}

// Check if lockout time is expired and reset if needed
if (isset($_SESSION['lockout_time']) && new DateTime() >= new DateTime($_SESSION['lockout_time'])) {
    $_SESSION['login_attempts'] = 0;
    $_SESSION['lockout_time'] = null;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    $errors = [];

    // Check if the user is still locked out
    if ($_SESSION['lockout_time'] && new DateTime() < new DateTime($_SESSION['lockout_time'])) {
        $remaining_lockout = (new DateTime($_SESSION['lockout_time']))->getTimestamp() - (new DateTime())->getTimestamp();
        $response['message'] = "Too many failed login attempts. Try again in $remaining_lockout seconds.";
        http_response_code(429); // Too Many Requests
        echo json_encode($response);
        exit();
    }

    // Validate inputs
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if (empty($email) || empty($password)) {
        $errors[] = "Both fields are required.";
    }

    if (empty($errors)) {
        // Check user credentials
        if (!checkUserPassword($pdo, $email, $password)) {
            $_SESSION['login_attempts']++;
            $remaining_attempts = 5 - $_SESSION['login_attempts'];

            if ($_SESSION['login_attempts'] >= 5) {
                $_SESSION['lockout_time'] = (new DateTime())->add(new DateInterval('PT30S'))->format('Y-m-d H:i:s');
                $response['message'] = "Too many failed login attempts. Your account is locked for 30 seconds.";
                http_response_code(429); // Too Many Requests
            } else {
                $response['message'] = "Incorrect email or password. You have $remaining_attempts attempt(s) left.";
                http_response_code(401); // Unauthorized
            }
        } else {
            // Successful login
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
