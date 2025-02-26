<?php
require_once "../../includes/config.php";
include_once "notifications.php";
include "validate_document.php";
include "upload_document.php";
include "upload_application.php";
include "get_user_info.php";

try {

    $pdo->beginTransaction();

    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        http_response_code(403);
        exit(json_encode(['success' => false, 'message' => 'CSRF token invalid.']));
    }


    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode(["success" => false, "message" => "Unauthorized access."]);
        exit;
    }

    $account_id = $_SESSION['user_id'];
    $response = ['success' => false, 'message' => '', 'errors' => []];

    // Validate Form Data
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

    $userInfo = getUserInfo($pdo, $account_id, $data['owner_first_name'], $data['owner_middle_name'], $data['owner_last_name']);

    if ($userInfo) {
        $isApplicantInserted = insertApplicant(
            $pdo,
            $account_id,
            $userInfo['first_name'],
            $userInfo['last_name'],
            $userInfo['middle_name'],
            $userInfo['sex'],
            $userInfo['email'],
            $userInfo['alt_email'],
            $userInfo['contact_no'],
            $userInfo['civil_status'],
            $userInfo['nationality'],
            $userInfo['address']
        );
    } else {
        http_response_code(404);
        echo json_encode(["error" => "User not found"]);
        exit();
    }

    if (is_array($isApplicantInserted) && !$isApplicantInserted['success']) {
        http_response_code(500);
        $response['message'] = "Failed to insert applicant.";
        $response['errors'][] = "Database Error: " . $isApplicantInserted['error'];
        echo json_encode($response);
        exit();
    }


    // Step 1: Insert Application First (Extension ID is NULL for now)
    $applicationId = uploadApplication(
        $pdo,
        $application_number,
        $account_id,
        intval($data['stall_id']),
        intval($data['section_id']),
        intval($data['market_id']),
        'stall extension',
        NULL // Placeholder for extension_id
    );

    if (!$applicationId || !is_numeric($applicationId)) {

        $response['message'] = "Failed to submit application.";
        $response['errors'][] = "Database error: Unable to submit application.";
        http_response_code(500);
        echo json_encode($response);
        exit();
    }

    $duration = $data['duration'];
    // Step 2: Insert into Extensions using the newly created Application ID
    $extensionResponse = insertExtension(
        $pdo,
        $applicationId, // Now we have the correct application_id
        $duration
    );
    if (!$extensionResponse['success']) {
        http_response_code(500); // Internal Server Error
        echo json_encode(["error" => "Failed to insert extension", "details" => $extensionResponse['error']]);
        exit();
    }

    $extensionId = $extensionResponse['id'];

    // Step 3: Update the Application to Set the Extension ID
    $updateStmt = $pdo->prepare("UPDATE applications SET extension_id = :extension_id WHERE id = :application_id");
    $updateStmt->execute([':extension_id' => $extensionId, ':application_id' => $applicationId]);


    // Upload Documents
    $currentIdPhotoUpload = uploadDocument($pdo, 'current_id_photo', $applicationId, "Current Id Photo");
    $proofOfPaymentUpload = uploadDocument($pdo, 'proof_of_payment', $applicationId, "Proof of Payment");

    if (!$currentIdPhotoUpload['success'] || !$proofOfPaymentUpload['success']) {
        $response['message'] = "Failed to upload files.";
        $response['errors'][] = "Error uploading documents.";
        http_response_code(500);
        echo json_encode($response);
        exit();
    }

    $pdo->commit();
    // Success Response
    $response['success'] = true;
    $response['message'] = "Application submitted successfully.";
    $type = 'Stall Extension';
    $message = sprintf('Your application for %s has been successfully submitted. Your Application Form Number is: %s.', $type, $application_number);

    insertNotification($pdo, $account_id, $type, $message, 'unread');
    http_response_code(201);
    echo json_encode($response);
    exit();
} catch (Exception $e) {
    // Rollback if any error occurs
    $pdo->rollBack();

    // Log error and return response
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

    // Validate Market Selection
    if (empty($data['market_id'])) {
        $errors[] = "Market is required.";
    }
    if (empty($data['section_id'])) {
        $errors[] = "Section is required.";
    }
    if (empty($data['stall_id'])) {
        $errors[] = "Stall is required.";
    }

    // Validate Duration Value
    if (empty($data['duration'])) {
        $errors[] = "Duration is required.";
    }

    return $errors;
}
function insertApplicant(
    $pdo,
    $accountId,
    $firstName,
    $middle_name,
    $lastName,
    $sex,
    $email,
    $altEmail,
    $phoneNumber,
    $civilStatus,
    $nationality,
    $fullAddress
) {
    try {
        // Prepare SQL query for inserting applicant
        $query = "INSERT INTO applicants 
            (account_id, first_name, middle_name, last_name, sex, email, alt_email, phone_number, 
            civil_status, nationality, address, created_at) 
            VALUES 
            (:account_id, :first_name, :middle_name, :last_name, :sex, :email, :alt_email, :phone_number, 
            :civil_status, :nationality, :address, NOW())";

        $stmt = $pdo->prepare($query);

        // Handle null values properly
        $stmt->bindValue(':account_id', $accountId, PDO::PARAM_INT);
        $stmt->bindValue(':first_name', $firstName, PDO::PARAM_STR);
        $stmt->bindValue(':last_name', $lastName, PDO::PARAM_STR);
        $stmt->bindValue(':middle_name', $middle_name, PDO::PARAM_STR);
        $stmt->bindValue(':sex', $sex, PDO::PARAM_STR);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->bindValue(':alt_email', !empty($altEmail) ? $altEmail : null, PDO::PARAM_STR);
        $stmt->bindValue(':phone_number', $phoneNumber, PDO::PARAM_STR);
        $stmt->bindValue(':civil_status', $civilStatus, PDO::PARAM_STR);
        $stmt->bindValue(':nationality', $nationality, PDO::PARAM_STR);
        $stmt->bindValue(':address', $fullAddress, PDO::PARAM_STR);

        $stmt->execute();

        return ["success" => true];
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        return ["success" => false, "error" => $e->getMessage()];
    }
}

function insertExtension(
    $pdo,
    $applicationId,
    $duration,
) {
    try {
        // Prepare SQL query for inserting applicant
        $query = "INSERT INTO extensions 
            (application_id, duration, created_at) 
            VALUES 
            (:application_id, :duration, NOW())";

        $stmt = $pdo->prepare($query);

        // Handle null values properly
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
