<?php
require_once "../../includes/config.php";
include_once "notifications.php";
include "validate_document.php";
include "upload_document.php";
include "upload_application.php";
include "get_user_id.php";
include "insert_applicant.php";

try {
    $pdo->setAttribute(PDO::ATTR_AUTOCOMMIT, false);
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
    $errors = validateApplicationData($data);
    $documentErrors = validateDocuments($_FILES);

    if (!empty($errors) || !empty($documentErrors)) {
        $response['message'] = "Validation failed.";
        $response['errors'] = array_merge($errors, $documentErrors);
        http_response_code(400);
        echo json_encode($response);
        exit();
    }

    $application_number = $data['application_number'];
    $applicationId = uploadApplication(
        $pdo,
        $application_number,
        $account_id,
        intval($data['stall_id']),
        intval($data['section_id']),
        intVal($data['market_id']),
        'stall extension',
        NULL // Placeholder for extension_id
    );

    if (!$applicationId || !is_numeric($applicationId)) {
        throw new Exception("Failed to submit application.");
    }

    $userId = getUserId($pdo, $account_id, $data['owner_first_name'], $data['owner_middle_name'], $data['owner_last_name']);
    if (!$userId) {
        throw new Exception("User not found.");
    }

    $isApplicantInserted = insertApplicant($pdo, $userId, intval($applicationId));
    if (!is_array($isApplicantInserted) || !$isApplicantInserted['success']) {
        throw new Exception("Failed to insert applicant. Database Error: " . $isApplicantInserted['error']);
    }

    $duration = $data['duration'];
    $extensionResponse = insertExtension($pdo, $applicationId, $duration);

    if (!$extensionResponse['success']) {
        throw new Exception("Failed to insert extension. " . $extensionResponse['error']);
    }

    $extensionId = $extensionResponse['id'];

    $updateStmt = $pdo->prepare("UPDATE applications SET extension_id = :extension_id WHERE id = :application_id");
    $updateStmt->execute([':extension_id' => $extensionId, ':application_id' => $applicationId]);

    $currentIdPhotoUpload = uploadDocument($pdo, 'current_id_photo', $applicationId, "Current Id Photo");
    $proofOfPaymentUpload = uploadDocument($pdo, 'proof_of_payment', $applicationId, "Proof of Payment");

    if (!$currentIdPhotoUpload['success'] || !$proofOfPaymentUpload['success']) {
        throw new Exception("Failed to upload files.");
    }

    unset($_SESSION['csrf_token']);
    $pdo->commit();
    $pdo->setAttribute(PDO::ATTR_AUTOCOMMIT, true);

    $response['success'] = true;
    $response['message'] = "Application submitted successfully.";
    $type = 'Stall Extension';
    $message = sprintf('Your application for %s has been successfully submitted. Your Application Form Number is: %s.', $type, $application_number);

    insertNotification($pdo, $account_id, $type, $message, 'unread');
    http_response_code(201);
    echo json_encode($response);
    exit();
} catch (Exception $e) {

    unset($_SESSION['csrf_token']);
    $pdo->rollBack();
    $pdo->setAttribute(PDO::ATTR_AUTOCOMMIT, true);

    error_log("Transaction failed: " . $e->getMessage());
    http_response_code(500);
    $response['success'] = false;
    $response['message'] = "An error occurred. Please try again.";
    $response['errors'][] = $e->getMessage();
    echo json_encode($response);
    exit();
}



function validateApplicationData($data)
{
    $errors = [];

    if (empty(intVal($data['market_id']))) {
        $errors[] = "Market is required.";
    }
    if (empty($data['section_id'])) {
        $errors[] = "Section is required.";
    }
    if (empty($data['stall_id'])) {
        $errors[] = "Stall is required.";
    }

    if (empty($data['duration'])) {
        $errors[] = "Duration is required.";
    }

    return $errors;
}

function insertExtension(
    $pdo,
    $applicationId,
    $duration,
) {
    try {
        $query = "INSERT INTO extensions 
            (application_id, duration, created_at) 
            VALUES 
            (:application_id, :duration, NOW())";

        $stmt = $pdo->prepare($query);

        $stmt->bindValue(':application_id', $applicationId, PDO::PARAM_INT);
        $stmt->bindValue(':duration', $duration, PDO::PARAM_STR);

        $stmt->execute();
        $extensionId = $pdo->lastInsertId();
        return ["success" => true, "id" => $extensionId];
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        return ["success" => false, "error" => $e->getMessage()];
    }
}
