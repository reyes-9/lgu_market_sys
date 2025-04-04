<?php
require_once '../../includes/config.php';
require_once '../../includes/session.php';

// Check if account_id is provided
if (isset($_SESSION['account_id'])) {
    $accountId = $_SESSION['account_id'];

    try {

        $stmt = $pdo->prepare("SELECT email FROM accounts WHERE id = :account_id");
        $stmt->bindParam(':account_id', $accountId, PDO::PARAM_INT);

        $stmt->execute();

        if ($stmt->rowCount() > 0) {

            $vendor = $stmt->fetch(PDO::FETCH_ASSOC);
            $email = $vendor['email'];

            echo json_encode(['success' => true, 'email' => $email]);
        } else {
            // No vendor found
            echo json_encode(['success' => false, 'message' => 'Vendor not found.']);
        }
    } catch (PDOException $e) {
        // Catch and display any errors
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    // If account_id is not provided
    echo json_encode(['success' => false, 'message' => 'No account_id provided.']);
}
