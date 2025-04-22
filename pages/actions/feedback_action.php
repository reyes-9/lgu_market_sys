<?php
require_once '../../includes/config.php';
require_once '../../includes/session.php';
include_once 'notifications.php';

ob_start();

$account_id = $_SESSION['account_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {


    $type = trim($_POST['type']) ?? '';
    $message = htmlspecialchars(trim($_POST['message']), ENT_QUOTES, 'UTF-8');
    $sentiment = htmlspecialchars(trim($_POST['sentiment']), ENT_QUOTES, 'UTF-8');

    if (empty($message)) {
        echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
        exit();
    }

    try {

        if ($type === 'feedback') {
            try {

                $stmt = $pdo->prepare("INSERT INTO feedback (account_id, message, sentiment) VALUES (:account_id, :message, :sentiment)");
                $stmt->execute([
                    ':account_id' => $account_id,
                    ':message' => $message,
                    ':sentiment' => $sentiment
                ]);

                ob_clean();
                echo json_encode(['status' => 'success', 'message' => 'Your feedback has been submitted!', 'type' => 'feedback']);

                $type = $notifications['feedback']['submitted']['type'];
                $message = $notifications['feedback']['submitted']['message'];

                insertNotification($pdo, $account_id, $type, $message, 'unread');

                exit();
            } catch (PDOException $e) {
                echo json_encode(['status' => 'error', 'message' => 'Error submitting feedback: ' . $e->getMessage()]);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid submission type.']);
        }

        if ($type === 'support') {
            try {
                $stmt = $pdo->prepare("INSERT INTO support_tickets (account_id, message) VALUES (:account_id, :message)");
                $stmt->execute([
                    ':account_id' => $account_id,
                    ':message' => $message
                ]);

                ob_clean();
                echo json_encode(['status' => 'success', 'message' => 'Your support request has been submitted.', 'type' => 'support']);

                $type = $notifications['support']['submitted']['type'];
                $message = $notifications['support']['submitted']['message'];

                insertNotification($pdo, $account_id, $type, $message, 'unread');

                exit();
            } catch (PDOException $e) {
                echo json_encode(['status' => 'error', 'message' => 'Error submitting support request: ' . $e->getMessage()]);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid submission type.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'An error occurred while processing your request.']);
    }
    ob_end_clean();
} else {
    echo json_encode(['status' => 'error', 'message' => 'An error occurred while processing your request.']);
}
