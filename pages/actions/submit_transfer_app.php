<?php
require_once "../../includes/config.php";
require_once '../../includes/session.php';
include_once "notifications.php";
include "validate_document.php";
include "upload_document.php";
include "upload_application.php";
include "get_user_id.php";
include "insert_stall_transfers.php";
include "insert_applicant.php";
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE); // Disable warnings & notices

try {

    $pdo->beginTransaction();

    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        http_response_code(403);
        exit(json_encode(['success' => false, 'message' => 'CSRF token invalid.']));
    }

    if (!isset($_SESSION['account_id'])) {
        http_response_code(401);
        echo json_encode(["success" => false, "message" => "Unauthorized access."]);
        exit;
    }

    $account_id = $_SESSION['account_id'];
    $response = ['success' => false, 'message' => '', 'errors' => []];

    $data = $_POST;
    $transferType = $data['application_type'];
    $errors = validateApplicationData($data, $transferType);
    $documentErrors = validateDocuments($_FILES);

    if (!empty($errors) || !empty($documentErrors)) {
        $response['message'] = "Validation failed.";
        $response['errors'] = array_merge($errors, $documentErrors);
        http_response_code(400);
        echo json_encode($response);
        exit();
    }

    $application_number = $data['application_number'];

    if ($transferType === "Succession") {
        $deceasedOwnerId = getUserId(
            $pdo,
            intval($data['deceased_owner_id'] ?? 0), // Ensure it's an integer
            properCase($data['deceased_first_name'] ?? ''),
            properCase($data['deceased_middle_name'] ?? ''),
            properCase($data['deceased_last_name'] ?? '')
        );
        $type = 'Stall Succession';
    } else {
        $currentOwnerId = getUserId(
            $pdo,
            intval($data['current_owner_id'] ?? 0), // Ensure it's an integer
            properCase($data['current_first_name'] ?? ''),
            properCase($data['current_middle_name'] ?? ''),
            properCase($data['current_last_name'] ?? '')
        );
        $type = 'Stall Transfer';
    }

    $applicationId = uploadApplication(
        $pdo,
        $application_number,
        $account_id,
        intval($data['stall_id']),
        intval($data['section_id']),
        intVal($data['market_id']),
        strtolower($type)
    );

    if (!$applicationId || !is_numeric($applicationId)) {
        throw new Exception("Failed to submit application.");
    }

    $userId = getUserIdByAccountId($pdo, $account_id);

    $isApplicantInserted = insertApplicant($pdo, $userId, intval($applicationId));
    if (!is_array($isApplicantInserted) || !$isApplicantInserted['success']) {
        throw new Exception("Failed to insert applicant. Database Error: " . $isApplicantInserted['error']);
    }

    $ownerId = ($transferType === "Succession") ? $deceasedOwnerId : $currentOwnerId;

    if (!$ownerId) {
        throw new Exception("User not found.");
    }

    $transferReason = $data['transfer_reason'] ?? null;
    $recipientId = $userId;

    $stallTransferResponse = insertStallTransfer(
        $pdo,
        $ownerId,
        $applicationId,
        $transferType,
        $transferReason,
        $recipientId
    );

    if (!$stallTransferResponse['success']) {
        throw new Exception("Failed to insert extension. " . $extensionResponse['error']);
    }

    $transferDocuments = [
        'deed_of_transfer'               => "Deed of Transfer",
        'valid_id_file_curr'             => $data['valid_id_type_curr'] . " (Current Owner)",
        'barangay_clearance_transfer'    => "Barangay Clearance for Transfer",
        'community_tax_cert_transfer'    => "Community Tax Certificate for Transfer",
        'valid_id_file_new'              => $data['valid_id_type_new'] . " (New Owner)"
    ];

    $successionDocuments = [
        'death_cert'                       => "Death Certificate",
        'proof_of_relationship'            => "Proof of Relationship to Deceased",
        'barangay_clearance_succession'    => "Barangay Clearance for Succession",
        'community_tax_cert_succession'    => "Community Tax Certificate for Succession",
        'valid_id_file_succession'         => $data['valid_id_type_succession'] . " (Successor)"
    ];

    $uploadErrors = [];

    $documents = ($transferType === "Succession") ? $successionDocuments : $transferDocuments;

    foreach ($documents as $fieldName => $docType) {
        if (isset($_FILES[$fieldName]) && $_FILES[$fieldName]['error'] === UPLOAD_ERR_OK) {
            $uploadStatus = uploadDocument($pdo, $fieldName, $applicationId, $docType, 'uploads/');
            if (!$uploadStatus) {
                $uploadErrors[] = "$docType upload failed.";
            }
        }
    }

    if (!empty($uploadErrors)) {
        throw new Exception("Failed to upload files.");
    }

    unset($_SESSION['csrf_token']);
    $pdo->commit();
    $pdo->setAttribute(PDO::ATTR_AUTOCOMMIT, true);

    $response['success'] = true;
    $response['message'] = "Application submitted successfully.";
    $message = sprintf('Your application for %s has been successfully submitted. Your Application Form Number is: %s.', $type, $application_number);

    insertNotification($pdo, $account_id, $type, $message, 'unread');
    http_response_code(201);
    echo json_encode($response);
    exit();
} catch (Exception $e) {

    $pdo->rollBack();
    $pdo->setAttribute(PDO::ATTR_AUTOCOMMIT, true);

    error_log("Transaction failed: " . $e->getMessage());
    http_response_code(500);
    $response['success'] = false;
    $response['message'] = "There is an error. Please try again.";
    $response['errors'][] = $e->getMessage();
    echo json_encode($response);
    exit();
}

function properCase($name)
{
    return preg_replace_callback("/\b[a-z']+\b/i", function ($match) {
        return ucfirst(strtolower($match[0]));
    }, $name);
}


function validateApplicationData($data, $app_type)
{
    $errors = [];

    // Validate Application Number
    if (empty($data['application_number'])) {
        $errors[] = "Application Number is required.";
    }

    // Validate Market Selection
    if (empty(intVal($data['market_id']))) {
        $errors[] = "Market is required.";
    }
    if (empty($data['section_id'])) {
        $errors[] = "Section is required.";
    }
    if (empty($data['stall_id'])) {
        $errors[] = "Stall is required.";
    }

    if ($app_type === "Transfer") {
        // Validate Current Owner Information
        if (empty($data["current_first_name"])) {
            $errors[] = "Current First Name is required.";
        }

        if (empty($data["current_middle_name"])) {
            $errors[] = "Current Middle Name is required.";
        }

        if (empty($data["current_last_name"])) {
            $errors[] = "Current Last Name is required.";
        }

        if (empty($data["current_owner_id"])) {
            $errors[] = "Current Owner ID is required.";
        }
    } else if ($app_type === "Succession") {
        // Validate Deceased Owner Information
        if (empty($data["deceased_first_name"])) {
            $errors[] = "Deceased First Name is required.";
        }

        if (empty($data["deceased_middle_name"])) {
            $errors[] = "Deceased Middle Name is required.";
        }

        if (empty($data["deceased_last_name"])) {
            $errors[] = "Deceased Last Name is required.";
        }

        if (empty($data["deceased_owner_id"])) {
            $errors[] = "Deceased Owner ID is required.";
        }
    }
    return $errors;
}
