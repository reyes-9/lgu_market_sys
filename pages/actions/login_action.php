<?php
require_once '../../includes/config.php';
require_once "log_admin_actions.php";

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

            $stmt = $pdo->prepare("SELECT id, user_type FROM accounts WHERE email = :email");
            $stmt->execute([':email' => $email]);
            $account = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($account) {
                $_SESSION['user_type'] = $account['user_type'];
                $_SESSION['account_id'] = $account['id'];

                if ($account['user_type'] === 'Admin') {
                    $_SESSION['admin_id'] = $account['id'];
                    logAdminAction($pdo, $_SESSION['admin_id'], "Logged In", "IP: " . $_SERVER['REMOTE_ADDR']);
                }

                $response = [
                    'success' => true,
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
