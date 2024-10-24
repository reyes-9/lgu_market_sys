<?php
session_start();

require_once '../../includes/config.php';

if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    $errors[] = "Invalid request. Please try again.";
    header('Location: login.php');
    exit();
} else {

    // If the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        // Retrieve input values and sanitize
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'];
        $errors = [];

        // Check if session login attempts exist, if not initialize
        if (!isset($_SESSION['login_attempts'])) {
            $_SESSION['login_attempts'] = 0;
            $_SESSION['lockout_time'] = null;
        }

        // Check if the user is locked out
        if ($_SESSION['lockout_time'] && new DateTime() < new DateTime($_SESSION['lockout_time'])) {
            $errors[] = "Your account is locked. Please try again later.";
            echo htmlspecialchars($errors[0]);
            exit();
        }

        // Validate email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format.";
        }

        // Check if fields are empty
        if (empty($password) || empty($email)) {
            $errors[] = "Both fields are required.";
        }

        // Validate password if no errors so far
        if (empty($errors)) {
            if (!checkUserPassword($pdo, $email, $password)) {
                $_SESSION['login_attempts']++; // Increment failed attempts
                $remaining_attempts = 3 - $_SESSION['login_attempts'];

                // If the user has 3 failed attempts, lock them out for 2 minutes
                if ($_SESSION['login_attempts'] >= 3) {
                    $_SESSION['lockout_time'] = (new DateTime())->add(new DateInterval('PT2M'))->format('Y-m-d H:i:s');
                    $errors[] = "Too many failed login attempts. Your account is locked for 2 minutes.";
                } else {
                    $errors[] = "Incorrect email or password. You have $remaining_attempts attempt(s) left.";
                }
            } else {
                // Successful login - reset login attempts and lockout time
                $_SESSION['login_attempts'] = 0;
                $_SESSION['lockout_time'] = null;
                // Successful login - get the user id to know that they're logged in
                $stmt = $pdo->prepare("SELECT id, email, password FROM accounts WHERE email = :email");
                $stmt->execute([':email' => $email]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                $_SESSION['user_id'] = $user['id'];
                if (!checkUserType($pdo, $email)) {      //if not admin
                    header('Location: ../../');
                    exit();
                }
                header('Location: ../admin/home/');
                exit();
            }
        }

        if (!empty($errors)) {
            foreach ($errors as $error) {
                echo htmlspecialchars($error) . "<br>";
            }
        }
    }
}

function checkUserPassword($pdo, $email, $password)
{
    $stmt = $pdo->prepare("SELECT password FROM accounts WHERE email = :email");
    $stmt->execute([
        ':email' => $email,
    ]);

    // Fetch the user hashed password
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user) {
        $hashed_password = $user['password'];
        if (password_verify($password, $hashed_password)) {
            return true; // Return true if password is valid
        }
        return false; // Return false if password is invalid
    } else {
        return false;
    }
}

function checkUserType($pdo, $email)
{
    $stmt = $pdo->prepare("SELECT user_type FROM accounts WHERE email = :email");
    $stmt->execute([
        ':email' => $email,
    ]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && $user['user_type'] === 'Admin') {
        return true;
    }
    return false;
}
