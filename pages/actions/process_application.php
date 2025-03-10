<?php
require_once '../../includes/config.php';
require "../../includes/session.php";
require "log_admin_actions.php";
require "notifications.php";

header('Content-Type: application/json');

$admin_id = $_SESSION['admin_id'];

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action = $_POST['action'] ?? '';

        $application_id = $_POST['application_id'] ?? '';
        $application_type = $_POST['application_type'] ?? '';
        $application_number = $_POST['application_number'] ?? '';

        if (empty($application_id)) {
            echo json_encode(['success' => false, 'message' => 'Application ID is required.']);
            exit;
        }

        $new_status = ($action === 'approved') ? 'Approved' : 'Rejected';

        $pdo->setAttribute(PDO::ATTR_AUTOCOMMIT, false);
        $pdo->beginTransaction();

        // Remove the reviewing ID (assuming it's stored in a column like `reviewed_by`)
        $stmt = $pdo->prepare("
        UPDATE applications 
        SET reviewing_admin_id = NULL, 
            reviewed_by = :admin_id, 
            status = :status 
        WHERE id = :id
        ");

        $stmt->bindParam(':status', $new_status, PDO::PARAM_STR);
        $stmt->bindParam(':admin_id', $admin_id, PDO::PARAM_INT);
        $stmt->bindParam(':id', $application_id, PDO::PARAM_INT);
        $stmt->execute();

        $pdo->commit();
        $pdo->setAttribute(PDO::ATTR_AUTOCOMMIT, true);

        if ($action === 'approved') {
            $message = "Congratulations, Your application for " . $application_type .
                " (Application Number: " . $application_number . ") has been approved.";
        } else {
            $rejection_reason = $_POST['rejection_reason'] ?? 'No specific reason provided.';
            $message = "We're sorry, but your application for " . $application_type .
                " (Application Number: " . $application_number . ") has been rejected.\n" .
                "Reason: " . $rejection_reason . ".\n\n" .
                "Please contact the administration for further details.";
        }

        logAdminAction($pdo, $admin_id, $new_status . " Application", $new_status . " application ID: " . $application_id);
        insertNotification($pdo, $account_id, $new_status . " Application", $message, $status = 'unread');

        echo json_encode(['success' => true, 'message' => "Application successfully $new_status."]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
        $pdo->setAttribute(PDO::ATTR_AUTOCOMMIT, true);
    }
} catch (PDOException $e) {
    $pdo->rollBack(); // Rollback changes if an error occurs
    $pdo->setAttribute(PDO::ATTR_AUTOCOMMIT, true);
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
