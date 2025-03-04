<?php
session_start();
require_once "../../includes/config.php";

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

        // Check if email is already registered
        $stmt = $pdo->prepare("SELECT id FROM accounts WHERE email = :email");
        $stmt->execute(['email' => $email]);

        if ($stmt->rowCount() > 0) {
            $pdo->rollBack(); // Rollback transaction if email exists
            http_response_code(400); // Bad Request
            echo json_encode(['success' => false, 'message' => 'Email is already registered.']);
            exit;
        }

        // Insert new account
        $stmt = $pdo->prepare("INSERT INTO accounts (email, password) VALUES (:email, :password)");
        $insertSuccess = $stmt->execute([
            'email' => $email,
            'password' => $hashed_password
        ]);

        if ($insertSuccess) {
            $pdo->commit(); // Commit transaction if everything is successful
            unset($_SESSION['csrf_token']);
            http_response_code(201); // Created
            echo json_encode(['success' => true, 'message' => 'Account successfully created!']);
        } else {
            $pdo->rollBack(); // Rollback transaction on failure
            http_response_code(500); // Internal Server Error
            echo json_encode(['success' => false, 'message' => 'Error signing up.']);
        }
    } catch (PDOException $e) {
        $pdo->rollBack(); // Ensure rollback on exception
        http_response_code(500); // Internal Server Error
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
    exit;
}
