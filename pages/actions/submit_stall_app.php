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

    // Process Address
    $addressParts = [
        $data['house_no'],
        $data['street'],
        $data['barangay'],
        $data['city'],
        $data['province'],
        $data['zip_code']
    ];

    $fullAddress = implode(', ', array_filter($addressParts));
    $userInfo = getUserInfo($pdo, $account_id, $data['first_name'], $data['middle_name'], $data['last_name']);

    if ($userInfo) {
        $isApplicantInserted = insertApplicant(
            $pdo,
            $account_id,
            $data['first_name'],
            $data['middle_name'],
            $data['last_name'],
            $data['sex'],
            $data['email'],
            $data['alt_email'],
            $data['contact_no'],
            $data['civil_status'],
            $data['nationality'],
            $fullAddress
        );
    } else {
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

    // Insert Application
    $applicationId = uploadApplication(
        $pdo,
        $application_number,
        $account_id,
        intval($data['stall_id']),
        intval($data['section_id']),
        intval($data['market_id']),
        'stall'
    );

    if (!$applicationId || !is_numeric($applicationId)) {
        $response['message'] = "Failed to submit application.";
        $response['errors'][] = "Database error: Unable to submit application.";
        http_response_code(500);
        echo json_encode($response);
        exit();
    }

    // Upload Documents
    $proofResidencyUpload = uploadDocument($pdo, 'proof_residency', $applicationId, "Proof of Residency");
    $validIdFileUpload = uploadDocument($pdo, 'valid_id_file', $applicationId, $data['valid_id_type']);

    if (!$proofResidencyUpload['success'] || !$validIdFileUpload['success']) {
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
    $type = 'Stall Application';
    $message = sprintf('Your application for %s has been successfully submitted. Your Application Form Number is: %s.', $type, $application_number);

    insertNotification($pdo, $account_id, $type, $message, 'unread');
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

    // Validate Personal Information
    if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = "A valid Email is required.";
    }

    if (!empty($data['alt_email']) && !filter_var($data['alt_email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Alternate Email is invalid.";
    }
    if (empty($data['contact_no']) || !preg_match('/^\d{11}$/', trim($data['contact_no']))) {
        $errors[] = "A valid 11-digit Contact Number is required.";
    }
    if (empty($data['first_name'])) {
        $errors[] = "First Name is required.";
    }
    if (empty($data['last_name'])) {
        $errors[] = "Last Name is required.";
    }
    if (empty($data['sex'])) {
        $errors[] = "Sex is required.";
    }
    if (empty($data['civil_status'])) {
        $errors[] = "Civil Status is required.";
    }
    if (empty($data['nationality'])) {
        $errors[] = "Nationality is required.";
    }

    // Validate Address Information
    if (empty($data['house_no'])) {
        $errors[] = "House Number is required.";
    }
    if (empty($data['street'])) {
        $errors[] = "Street is required.";
    }
    if (empty($data['barangay'])) {
        $errors[] = "Barangay is required.";
    }
    if (empty($data['city'])) {
        $errors[] = "City is required.";
    }
    if (empty($data['province'])) {
        $errors[] = "Province is required.";
    }
    if (empty($data['zip_code']) || !preg_match('/^\d{4}$/', trim($data['zip_code']))) {
        $errors[] = "A valid 4-digit Zip Code is required.";
    }

    // Validate Market Information
    if (empty($data['market_items'])) {
        $errors[] = "Market Items field is required.";
    }

    // Validate Document Information
    if (empty($data['valid_id_type'])) {
        $errors[] = "Valid ID Type is required.";
    }

    return $errors;
}
function insertApplicant(
    $pdo,
    $accountId,
    $firstName,
    $middleName,
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
            (:account_id, :first_name, :middle_name,:last_name, :sex, :email, :alt_email, :phone_number, 
            :civil_status, :nationality, :address, NOW())";

        $stmt = $pdo->prepare($query);

        // Handle null values properly
        $stmt->bindValue(':account_id', $accountId, PDO::PARAM_INT);
        $stmt->bindValue(':first_name', $firstName, PDO::PARAM_STR);
        $stmt->bindValue(':middle_name', $middleName, PDO::PARAM_STR);
        $stmt->bindValue(':last_name', $lastName, PDO::PARAM_STR);
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
