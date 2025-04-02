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
        $application_type = $_POST['application_type'] ?? '';
        $application_number = $_POST['application_number'] ?? '';
        $rejection_reason = $_POST['rejection_reason'] ?? 'No specific reason provided.';
        $stall_number = $_POST['stall_number'] ?? '';
        $deceased_owner_id = $_POST['deceased_owner_id'] ?? '';
        $current_owner_id = $_POST['current_owner_id'] ?? '';
        $applicants_user_id = $_POST['user_id'] ?? '';
        $helper_id = $_POST['helper_id'] ?? '';
        $extension_duration = $_POST['extension_duration'] ?? '';

        if (empty($application_id)) {
            echo json_encode(['success' => false, 'message' => 'Application ID is required.']);
            exit;
        }

        $new_status = ($action === 'approved') ? 'Approved' : 'Rejected';

        $pdo->setAttribute(PDO::ATTR_AUTOCOMMIT, false);
        $pdo->beginTransaction();

        // If approved and application type is "stall", assign stall to user
        if ($action === 'approved' && $application_type === "stall") {
            $stallAssignmentResult = assignStallOwner($pdo, $applicants_user_id, $stall_number);
            if (!$stallAssignmentResult['success']) {
                throw new Exception($stallAssignmentResult['message']);
            }
            $isSetExpiration = setExpirationDate(
                $pdo,
                $application_id,
                $stallAssignmentResult['stall_id'],
                $application_type,
                $extension_duration
            );
            if (!$isSetExpiration['success']) {
                throw new Exception($isSetExpiration['message']);
            }
        }

        if ($action === 'approved' && $application_type === "stall succession") {
            $transferStallOwnershipResult = transferStallOwnership($pdo, $deceased_owner_id, $stall_number, $applicants_user_id);
            if (!$transferStallOwnershipResult['success']) {
                throw new Exception($transferStallOwnershipResult['message']);
            }
            $isSetExpiration = setExpirationDate($pdo, $application_id, $transferStallOwnershipResult['stall_id'], "stall", $extension_duration);
            if (!$isSetExpiration['success']) {
                throw new Exception($isSetExpiration['message']);
            }
        }


        if ($action === 'approved' && $application_type === "stall transfer") {
            $transferStallOwnershipResult = transferStallOwnership($pdo, $current_owner_id, $stall_number, $applicants_user_id);
            if (!$transferStallOwnershipResult['success']) {
                throw new Exception($transferStallOwnershipResult['message']);
            }
            $isSetExpiration = setExpirationDate($pdo, $application_id, $transferStallOwnershipResult['stall_id'], "stall", $extension_duration);
            if (!$isSetExpiration['success']) {
                throw new Exception($isSetExpiration['message']);
            }
        }

        if ($action === 'approved' && $application_type === "stall extension") {
            $extensionActivationResult = activateExtension($pdo, $applicants_user_id, $stall_number);
            if (!$extensionActivationResult['success']) {
                throw new Exception($extensionActivationResult['message']);
            }
            $isSetExpiration = setExpirationDate($pdo, $application_id, $extensionActivationResult['extension_id'],  $application_type, $extension_duration);
            if (!$isSetExpiration['success']) {
                throw new Exception($isSetExpiration['message']);
            }
        }

        if ($action === 'approved' && $application_type === "helper") {
            $assignStallHelperResult = assignStallHelper($pdo, $helper_id, $stall_number);
            if (!$assignStallHelperResult['success']) {
                throw new Exception($assignStallHelperResult['message']);
            }
            $isSetExpiration = setExpirationDate($pdo, $application_id, $assignStallHelperResult['helper_id'],  $application_type, $extension_duration);
            if (!$isSetExpiration['success']) {
                throw new Exception($isSetExpiration['message']);
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


function assignStallOwner($pdo, $applicants_user_id, $stall_number)
{
    try {
        // Check if the user exists
        $stmtUser = $pdo->prepare("SELECT id FROM users WHERE id = :user_id");
        $stmtUser->bindParam(':user_id', $applicants_user_id, PDO::PARAM_INT);
        $stmtUser->execute();
        $user = $stmtUser->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            return ['success' => false, 'message' => "User ID $applicants_user_id does not exist."];
        }

        // Check if the stall exists and is available
        $stmtStall = $pdo->prepare("SELECT id, user_id FROM stalls WHERE stall_number = :stall_number");
        $stmtStall->bindParam(':stall_number', $stall_number, PDO::PARAM_STR);
        $stmtStall->execute();
        $stall = $stmtStall->fetch(PDO::FETCH_ASSOC);

        if (!$stall) {
            return ['success' => false, 'message' => "Stall number $stall_number does not exist."];
        }

        if ($stall['user_id'] !== null) { // Explicit check instead of empty()
            return ['success' => false, 'message' => "Stall number $stall_number is already assigned."];
        }

        // Assign the stall to the user
        $status = "occupied";
        $stmtUpdate = $pdo->prepare("UPDATE stalls SET user_id = :user_id, status = :status WHERE stall_number = :stall_number");
        $stmtUpdate->bindParam(':user_id', $applicants_user_id, PDO::PARAM_INT);
        $stmtUpdate->bindParam(':status', $status, PDO::PARAM_STR);
        $stmtUpdate->bindParam(':stall_number', $stall_number, PDO::PARAM_STR);
        $stmtUpdate->execute();

        $updatedStallId = $stall['id'];

        return ['success' => true, 'stall_id' => $updatedStallId];
    } catch (PDOException $e) {
        $pdo->rollBack(); // Rollback on failure
        return ['success' => false, 'message' => "Database error: " . $e->getMessage()];
    }
}

function activateExtension($pdo, $applicants_user_id, $stall_number)
{
    try {

        $stmt = $pdo->prepare("SELECT id FROM users WHERE id = :user_id");
        $stmt->bindParam(':user_id', $applicants_user_id, PDO::PARAM_INT);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            return ['success' => false, 'message' => "User ID $applicants_user_id does not exist."];
        }

        // Check if the stall exists and belongs to the user
        $stmt = $pdo->prepare("SELECT id, user_id FROM stalls WHERE stall_number = :stall_number FOR UPDATE");
        $stmt->bindParam(':stall_number', $stall_number, PDO::PARAM_STR);
        $stmt->execute();
        $stall = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$stall) {
            return ['success' => false, 'message' => "Stall number $stall_number does not exist."];
        }

        if ($stall['user_id'] != $applicants_user_id) {
            return ['success' => false, 'message' => "Stall number $stall_number is not assigned to this account."];
        }

        $stall_id = $stall['id'];

        // Check if an extension exists for this stall
        $stmt = $pdo->prepare("SELECT id, status FROM extensions WHERE stall_id = :stall_id");
        $stmt->bindParam(':stall_id', $stall_id, PDO::PARAM_INT);
        $stmt->execute();
        $extension = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$extension) {
            return ['success' => false, 'message' => "No extension found for stall number $stall_number."];
        }

        if ($extension["status"] == "active") {
            return ['success' => false, 'message' => "Extension found for stall number $stall_number."];
        }

        // Activate the extension
        $stmt = $pdo->prepare("UPDATE extensions SET status = 'active' WHERE stall_id = :stall_id");
        $stmt->bindParam(':stall_id', $stall_id, PDO::PARAM_INT);
        $stmt->execute();

        return [
            'success' => true,
            'message' => "Stall extension for stall number $stall_number is now active.",
            'extension_id' => $extension['id']
        ];
    } catch (PDOException $e) {
        return ['success' => false, 'message' => "Database error: " . $e->getMessage()];
    }
}

function transferStallOwnership($pdo, $owner_id, $stall_number, $new_owner_id)
{
    try {
        // Check if the new owner exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE id = :user_id");
        $stmt->bindParam(':user_id', $new_owner_id, PDO::PARAM_INT);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            return ['success' => false, 'message' => "User ID $new_owner_id does not exist."];
        }

        // Check if the stall exists and is currently occupied by the owner_id
        $stmt = $pdo->prepare("SELECT id, user_id FROM stalls WHERE stall_number = :stall_number FOR UPDATE");
        $stmt->bindParam(':stall_number', $stall_number, PDO::PARAM_STR);
        $stmt->execute();
        $stall = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$stall) {
            return ['success' => false, 'message' => "Stall number $stall_number does not exist."];
        }

        if (empty($stall['user_id'])) {
            return ['success' => false, 'message' => "Stall number $stall_number is not currently assigned to any owner."];
        }

        if ($stall['user_id'] != $owner_id) {
            return ['success' => false, 'message' => "Stall number $stall_number is not owned by user ID $owner_id."];
        }

        if ($stall['user_id'] == $new_owner_id) {
            return ['success' => false, 'message' => "The new owner is already the current owner of stall number $stall_number."];
        }

        // Transfer the stall ownership to the new owner
        $stmt = $pdo->prepare("UPDATE stalls SET user_id = :user_id WHERE stall_number = :stall_number");
        $stmt->bindParam(':user_id', $new_owner_id, PDO::PARAM_INT);
        $stmt->bindParam(':stall_number', $stall_number, PDO::PARAM_STR);
        $stmt->execute();

        return [
            'success' => true,
            'message' => "Stall number $stall_number successfully transferred to user ID $new_owner_id.",
            'stall_id' => $stall['id']
        ];
    } catch (PDOException $e) {
        return ['success' => false, 'message' => "Database error: " . $e->getMessage()];
    }
}

function assignStallHelper($pdo, $helper_id, $stall_number)
{
    try {
        // First, check if the helper exists
        $stmtHelper = $pdo->prepare("SELECT id FROM helpers WHERE id = :helper_id");
        $stmtHelper->bindParam(':helper_id', $helper_id, PDO::PARAM_INT);
        $stmtHelper->execute();
        $helper = $stmtHelper->fetch(PDO::FETCH_ASSOC);

        if (!$helper) {
            return ["success" => false, "message" => "Helper with ID $helper_id does not exist."];
        }

        // Check if the stall exists
        $stmtStall = $pdo->prepare("SELECT id FROM stalls WHERE stall_number = :stall_number");
        $stmtStall->bindParam(':stall_number', $stall_number, PDO::PARAM_INT);
        $stmtStall->execute();
        $stall = $stmtStall->fetch(PDO::FETCH_ASSOC);

        if (!$stall) {
            return ["success" => false, "message" => "Stall with ID $stall_number does not exist."];
        }

        // Assign the helper to the stall (update status to active)
        $status = "Active";
        $assign_helper = $pdo->prepare("
            UPDATE helpers
            SET status = :status
            WHERE id = :helper_id
        ");
        $assign_helper->bindParam(':status', $status, PDO::PARAM_INT);
        $assign_helper->bindParam(':helper_id', $helper_id, PDO::PARAM_INT);

        if ($assign_helper->execute()) {
            return [
                "success" => true,
                "message" => "Helper successfully assigned to stall ID $stall_number.",
                "helper_id" => $helper_id
            ];
        } else {
            return ["success" => false, "message" => "Failed to assign helper to the stall."];
        }
    } catch (PDOException $e) {
        return ["success" => false, "message" => "Database error: " . $e->getMessage()];
    }
}


function setExpirationDate($pdo, $application_id, $reference_id, $application_type, $extension_duration)
{
    try {
        // Define the expiration date based on application type
        $expiration_date = null;

        // Calculate the expiration date based on the application type
        if ($application_type === 'stall') {

            $expiration_date = date('Y-m-d', strtotime('+1 month'));
        } elseif ($application_type === 'stall extension') {

            switch ($extension_duration) {
                case "3 months":
                    $expiration_date = date('Y-m-d', strtotime('+3 months'));
                    break;
                case "6 months":
                    $expiration_date = date('Y-m-d', strtotime('+6 months'));
                    break;
                case "12 months":
                    $expiration_date = date('Y-m-d', strtotime('+12 months'));
                    break;
                default:
                    throw new Exception("Invalid extension duration: $extension_duration");
            }
        } elseif ($application_type === 'helper') {
            $expiration_date = date('Y-m-d', strtotime('+1 months'));
        }

        // Check if expiration date is valid
        if ($expiration_date === null) {
            throw new Exception("Invalid application type.");
        }

        // Insert expiration date into the expiration_dates table
        $stmt = $pdo->prepare("INSERT INTO expiration_dates (application_id, reference_id, type, expiration_date, status) 
                               VALUES (:application_id, :reference_id, :type, :expiration_date, 'active')");
        $stmt->bindParam(':application_id', $application_id);
        $stmt->bindParam(':reference_id', $reference_id);
        $stmt->bindParam(':type', $application_type);
        $stmt->bindParam(':expiration_date', $expiration_date);
        $stmt->execute();

        // Return success message
        return ['success' => true];
    } catch (Exception $e) {
        // Handle error (e.g., log the error)
        return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
    }
}
