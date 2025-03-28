<?php
require_once '../../includes/config.php';
error_reporting(E_ERROR | E_PARSE); // Show only errors, not warnings/notices
ini_set('display_errors', 0); // Disable output of errors on the page
header("Content-Type: application/json");

// Retrieve and sanitize input values
// Get user ID and stall number from AJAX request
$user_id = $_GET['user_id'] ?? 0;
$stall_number = $_GET['stall_number'] ?? 0;
$application_id = $_GET['application_id'] ?? 0;
$application_type = $_GET['application_type'] ?? "";

// Ensure numeric values are properly cast
$user_id = (int) $user_id;
$stall_number = (int) $stall_number;
$application_id = (int) $application_id;

if ($user_id <= 0) {
    echo json_encode(["success" => false, "message" => "Invalid User ID."]);
    exit;
}

if ($stall_number <= 0) {
    echo json_encode(["success" => false, "message" => "Invalid Stall Number."]);
    exit;
}

// Initialize response array
$response = [
    "isApplicant" => false,
    "isStall" => false,
    "hasViolation" => false,
    "isTransferApproved" => false
];

// Check if user exists
$applicant_check = $pdo->prepare("SELECT id FROM users WHERE id = :user_id LIMIT 1");
$applicant_check->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$applicant_check->execute();
if ($applicant_check->fetch()) {
    $response["isApplicant"] = true;
}

$stall_query = $pdo->prepare("
    SELECT s.status, s.user_id, 
           (SELECT COUNT(*) FROM violations WHERE user_id = :user_id AND status = :status) AS violation_count 
    FROM stalls s 
    WHERE s.stall_number = :stall_number
");
$status = "Pending";
$stall_query->bindParam(':stall_number', $stall_number, PDO::PARAM_INT);
$stall_query->bindParam(':status', $status, PDO::PARAM_STR);
$stall_query->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stall_query->execute();
$stall = $stall_query->fetch(PDO::FETCH_ASSOC);

if ($stall) {
    // Stall exists, check if the user owns it
    $response["isStall"] = ($stall["user_id"] == $user_id);

    // Stall availability check (only for new stall applications)
    if ($application_type === "stall") {
        $response["isStall"] = ($stall["status"] === 'available');
    }

    $response["hasViolation"] = ($stall["violation_count"] > 0);
} else {
    $response["error"] = "Stall not found";
}

$transfer_approval = $pdo->prepare("SELECT transfer_confirmation_status 
                                    FROM stall_transfers 
                                    WHERE application_id = :application_id");

$transfer_approval->bindParam(':application_id', $application_id, PDO::PARAM_INT);
$transfer_approval->execute();
$approval = $transfer_approval->fetch(PDO::FETCH_ASSOC);

if ($approval['transfer_confirmation_status'] !== "Approved") {
    $response["isTransferApproved"] = false;
    $response["status"] = $approval['transfer_confirmation_status'];
} else {
    $response["isTransferApproved"] = true;
}

echo json_encode($response);
