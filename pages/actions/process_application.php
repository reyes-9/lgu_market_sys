<?php
require_once '../../includes/config.php';
require "../../includes/session.php";
require "log_admin_actions.php";
require "notifications.php";
require "get_user_id.php";

header('Content-Type: application/json');

$admin_id = $_SESSION['admin_id'];

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action = $_POST['action'] ?? '';
        $application_id = $_POST['application_id'] ?? '';
        $account_id = $_POST['account_id'] ?? '';
        $application_type = $_POST['application_type'] ?? '';
        $application_number = $_POST['application_number'] ?? '';
        $rejection_reason = $_POST['rejection_reason'] ?? 'No specific reason provided.';
        $stall_number = $_POST['stall_number'] ?? '';

        if (empty($application_id)) {
            echo json_encode(['success' => false, 'message' => 'Application ID is required.']);
            exit;
        }

        $new_status = ($action === 'approved') ? 'Approved' : 'Rejected';

        $pdo->setAttribute(PDO::ATTR_AUTOCOMMIT, false);
        $pdo->beginTransaction();

        // If approved and application type is "stall", assign stall to user
        if ($action === 'approved' && $application_type === "stall") {
            $stallAssignmentResult = assignStallOwner($pdo, $account_id, $stall_number);
            if (!$stallAssignmentResult['success']) {
                throw new Exception($stallAssignmentResult['message']);
            }
        }

        // Update application status
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

        // Send notification
        if ($action === 'approved') {
            $message = "Congratulations! Your application for " . $application_type .
                " (Application Number: " . $application_number . ") has been approved.";
        } else {
            $message = "We're sorry, but your application for " . $application_type .
                " (Application Number: " . $application_number . ") has been rejected.\n" .
                "Reason: " . $rejection_reason . ".\n\n" .
                "Please contact the administration for further details.";
        }

        logAdminAction($pdo, $admin_id, $new_status . " Application", $new_status . " application ID: " . $application_id);
        insertNotification($pdo, $account_id, $new_status . " Application", $message, 'unread');

        echo json_encode(['success' => true, 'message' => "Application successfully $new_status."]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    }
} catch (Exception $e) {
    $pdo->rollBack();
    $pdo->setAttribute(PDO::ATTR_AUTOCOMMIT, true);
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}


function assignStallOwner($pdo, $account_id, $stall_number)
{
    try {

        $user_id = getUserIdByAccountId($pdo, $account_id);
        if (!$user_id) {
            return ['success' => false, 'message' => "Account ID $account_id does not exist."];
        }

        // Check if stall exists and is available
        $stmt = $pdo->prepare("SELECT id, user_id FROM stalls WHERE stall_number = :stall_number");
        $stmt->bindParam(':stall_number', $stall_number, PDO::PARAM_STR);
        $stmt->execute();
        $stall = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$stall) {
            return ['success' => false, 'message' => "Stall number $stall_number does not exist."];
        }

        if (!empty($stall['user_id'])) {
            return ['success' => false, 'message' => "Stall number $stall_number is already assigned."];
        }
        $status = "occupied";
        // Assign the stall to the user
        $stmt = $pdo->prepare("UPDATE stalls SET user_id = :user_id, status = :status WHERE stall_number = :stall_number");
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);
        $stmt->bindParam(':stall_number', $stall_number, PDO::PARAM_STR);
        $stmt->execute();

        return ['success' => true, 'message' => "Stall number $stall_number successfully assigned."];
    } catch (PDOException $e) {
        return ['success' => false, 'message' => "Database error: " . $e->getMessage()];
    }
}
