<?php
require_once "../../includes/config.php";
include_once "notifications.php";
include "validate_document.php";
include "upload_document.php";
include "upload_application.php";
include "get_user_info.php";
include "insert_stall_transfers.php";
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);
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

    $addressParts = [  // Process Address
        $data['house_no'],
        $data['street'],
        $data['barangay'],
        $data['city'],
        $data['province'],
        $data['zip_code']
    ];
    $fullAddress = implode(', ', array_filter($addressParts));

    if ($transferType === "Succession") {
        $userInfo = getUserInfo($pdo, $data['deceased_owner_id'], $data['deceased_first_name'], $data['deceased_middle_name'], $data['deceased_last_name']);
    } else {
        $userInfo = getUserInfo($pdo, $data['current_owner_id'], $data['current_first_name'], $data['current_middle_name'], $data['current_last_name']);
    }

    if (!$userInfo) {
        echo json_encode(["error" => "User not found"]);
        exit;
    }

    // Construct full address from user data
    $fullAddress = $userInfo['address'];

    // Insert Applicant
    $isApplicantInserted = insertApplicant(
        $pdo,
        $account_id, // Using correct variable name
        $userInfo['first_name'],
        $userInfo['last_name'],
        $userInfo['sex'],
        $userInfo['email'],
        $userInfo['alt_email'],
        $userInfo['contact_no'],
        $userInfo['civil_status'],
        $userInfo['nationality'],
        $userInfo['address']
    );

    if (!$isApplicantInserted) {
        echo json_encode(["error" => "Failed to insert applicant"]);
    }
    if (is_array($isApplicantInserted) && !$isApplicantInserted['success']) {
        http_response_code(500);
        $response['message'] = "Failed to insert applicant.";
        $response['errors'][] = "Database Error: " . $isApplicantInserted['error'];
        echo json_encode($response);
        exit;
    }

    // Insert Application
    $applicationId = uploadApplication(
        $pdo,
        $application_number,
        $account_id,
        intval($data['stall_id']),
        intval($data['section_id']),
        intval($data['market_id']),
        'stall transfer'
    );

    if (!$applicationId || !is_numeric($applicationId)) {
        $response['message'] = "Failed to submit application.";
        $response['errors'][] = "Database error: Unable to submit application.";
        http_response_code(500);
        echo json_encode($response);
        exit();
    }

    $deceasedOwnerId = $data['deceased_owner_id'] ?? null; // Handle if not provided
    $currentOwnerId = $data['current_owner_id'] ?? null;


    $ownerId = ($transferType === "Succession") ? $deceasedOwnerId : $currentOwnerId;


    $transferReason = $data['transfer_reason'] ?? null;
    $recipientName = $data['first_name'] . ' ' . $data['middle_name'] . ' ' . $data['last_name'];
    $recipientContact = $data['contact_no'];
    $recipientEmail = $data['email'];
    $recipientAltEmail = $data['alt_email'];
    $recipientSex = $data['sex'];
    $recipientCivilStatus = $data['civil_status'];
    $recipientNationality = $data['nationality'];
    $recipientAddress = $fullAddress;

    $stallTransferResponse = insertStallTransfer(
        $pdo,
        $ownerId,
        $applicationId,
        $transferType,
        $transferReason,
        $recipientName,
        $recipientContact,
        $recipientEmail,
        $recipientAltEmail,
        $recipientSex,
        $recipientCivilStatus,
        $recipientNationality,
        $recipientAddress
    );

    if (!$stallTransferResponse['success']) {
        echo json_encode($response);
        exit;
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

    // If there were any errors, return an error response
    if (!empty($uploadErrors)) {
        echo json_encode([
            "success" => false,
            "message" => "Failed to upload some files.",
            "errors"  => $uploadErrors
        ]);
        http_response_code(500);
        exit;
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

function validateApplicationData($data, $app_type)
{
    $errors = [];

    // Validate Application Number
    if (empty($data['application_number'])) {
        $errors[] = "Application Number is required.";
    }

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

    return $errors;
}
function insertApplicant(
    $pdo,
    $accountId,
    $firstName,
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
            (account_id, first_name, last_name, sex, email, alt_email, phone_number, 
            civil_status, nationality, address, created_at) 
            VALUES 
            (:account_id, :first_name, :last_name, :sex, :email, :alt_email, :phone_number, 
            :civil_status, :nationality, :address, NOW())";

        $stmt = $pdo->prepare($query);

        // Handle null values properly
        $stmt->bindValue(':account_id', $accountId, PDO::PARAM_INT);
        $stmt->bindValue(':first_name', $firstName, PDO::PARAM_STR);
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


// TRANSFER DETAILS

// deed_of_transfer: [object File]
// valid_id_type_curr: Umid
// valid_id_file_curr: [object File]
// barangay_clearance_transfer: [object File]
// community_tax_cert_transfer: [object File]
// valid_id_type_new: PRC
// valid_id_file_new: [object File]
// application_number: APP-20250221-000208
// market_id: 2
// section_id: 4
// stall_id: 5
// market: Eastside Market
// section: Dry Goods
// stall: 202
// csrf_token: 0adb2eeb128103b6b701cccd96370a3760853b745ebd76fed4abf628c775daf5
// transfer_reason: none
// current_first_name: test
// current_middle_name: test
// current_last_name: test
// current_owner_id: 4
// email: nreyesmine69@gmail.com
// alt_email: dsaa@yahoo.com
// contact_no: 09159088624
// first_name: test
// middle_name: test
// last_name: test
// sex: Male
// civil_status: Single
// nationality: Filipino
// house_no: test
// street: test
// subdivision: test
// province: test
// city: tset
// barangay: test
// zip_code: 1234




// SUCCESSION DETAILS

// death_cert: [object File]
// proof_of_relationship: [object File]
// barangay_clearance_succession: [object File]
// community_tax_cert_succession: [object File]
// valid_id_type_succession: Voters
// valid_id_file_succession: [object File]
// application_type: Succession
// market_id: 1
// section_id: 2
// stall_id: 2
// market: Central Market
// section: Vegetables
// stall: 102
// deceased_first_name: Nelson
// deceased_middle_name: Doe
// deceased_last_name: Reyes
// current_owner_id: 4
// email: reyes@gmail.com
// alt_email: asdf@yahoo.com
// contact_no: 09159088624
// first_name: test
// middle_name: test
// last_name: test
// sex: Male
// civil_status: Married
// nationality: Filipino
// house_no: 98
// street: test
// subdivision: test
// province: test
// city: test
// barangay: test
// zip_code: 1700
// relationship_to_deceased: none
