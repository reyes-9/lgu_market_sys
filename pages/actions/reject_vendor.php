<?php
require_once '../../includes/config.php';

header('Content-Type: application/json');

if (!isset($_POST['account_id']) || !isset($_POST['application_id']) || !isset($_POST['rejection_reason'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid request. Missing parameters']);
    exit;
}

$account_id = filter_var($_POST['account_id'], FILTER_SANITIZE_NUMBER_INT);
$application_id = filter_var($_POST['application_id'], FILTER_SANITIZE_NUMBER_INT);
$rejection_reason = preg_replace("/[^a-zA-Z0-9 ]/", "", $_POST['rejection_reason']);

try {
    $stmt = $pdo->prepare("UPDATE vendors_application 
                            SET application_status = 'Rejected', status_date = NOW(), rejection_reason = :rejection_reason 
                            WHERE account_id = :account_id AND id = :application_id");

    $stmt->bindParam(':account_id', $account_id, PDO::PARAM_INT);
    $stmt->bindParam(':application_id', $application_id, PDO::PARAM_INT);
    $stmt->bindParam(':rejection_reason', $rejection_reason, PDO::PARAM_STR);

    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true, 'message' => 'Vendor rejected successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Vendor not found or already rejected.']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
