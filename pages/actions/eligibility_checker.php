<?php
require_once '../../includes/config.php';
require_once '../../includes/session.php';
error_reporting(E_ERROR | E_PARSE); // Show only errors, not warnings/notices
ini_set('display_errors', 0); // Disable output of errors on the page

header("Content-Type: application/json");

// Retrieve and sanitize input values
$user_id = trim($_GET['user_id'] ?? 0);
$stall_number = trim($_GET['stall_number'] ?? 0);
$application_id = trim($_GET['application_id'] ?? 0);
$deceased_owner_id = trim($_GET['deceased_owner_id'] ?? 0);
$current_owner_id = trim($_GET['current_owner_id'] ?? 0);
$helper_id = trim($_GET['helper_id'] ?? 0);
$application_type = trim($_GET['application_type'] ?? '');

// Ensure numeric values are properly cast
$user_id = (int) $user_id;
$application_id = (int) $application_id;
$helper_id = (int) $helper_id;

if ($user_id <= 0) {
    echo json_encode(["success" => false, "message" => "Invalid User ID."]);
    exit;
}

if ($stall_number === null) {
    echo json_encode(["success" => false, "message" => "Invalid Stall Number."]);
    exit;
}

// Initialize response array
$response = [
    "isApplicant" => false,
    "isStall" => false,
    "hasViolation" => false,
    "isTransferApproved" => false,
    "isHelper" => false,
    "isStallPaid" => false
];

if ($application_type === "helper") {
    // Check if helper exists and if the stall owner is the applicant
    if ($helper_id > 0) {
        $helper_check = $pdo->prepare("
            SELECT s.user_id AS stall_owner
            FROM helpers h
            INNER JOIN stalls s ON h.stall_id = s.id
            WHERE h.id = :helper_id 
            AND s.stall_number = :stall_number
        ");

        $helper_check->bindParam(':helper_id', $helper_id, PDO::PARAM_INT);
        $helper_check->bindParam(':stall_number', $stall_number, PDO::PARAM_INT);
        $helper_check->execute();
        $helper = $helper_check->fetch(PDO::FETCH_ASSOC);

        if ($helper) {
            if ($helper['stall_owner'] == $user_id) {
                $response["isHelper"] = true;
            } else {
                $response["error"] = "Applicant is not the stall owner associated with the helper.";
            }
        } else {
            $response["error"] = "Helper not found.";
        }
    } else {
        $response["error"] = "Helper Id is required.";
    }
}

// Check if user exists
$applicant_check = $pdo->prepare("
                SELECT 
                    u.id, 
                    u.account_id,
                    a.is_verified
                FROM users u
                JOIN accounts a ON u.account_id = a.id 
                WHERE u.id = :user_id LIMIT 1
            ");

$applicant_check->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$applicant_check->execute();
$result = $applicant_check->fetch(PDO::FETCH_ASSOC);

if ($result && $result['is_verified'] === 1) {       // Corrected this line
    $response["isApplicant"] = true;
}

// Check stall information, violations, and payment status
$stall_query = $pdo->prepare("
    SELECT s.status, s.user_id, s.payment_status,
           (SELECT COUNT(*) FROM violations WHERE user_id = :user_id AND status = 'Pending') AS violation_count 
    FROM stalls s 
    WHERE s.stall_number = :stall_number
");
$stall_query->bindParam(':stall_number', $stall_number, PDO::PARAM_INT);
$stall_query->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stall_query->execute();
$stall = $stall_query->fetch(PDO::FETCH_ASSOC);

if (!$stall) {
    $response["error"] = "Stall not found";
    echo json_encode($response);
    exit;
}

$response["hasViolation"] = ($stall["violation_count"] > 0);
$response["isStallPaid"] = ($stall["payment_status"] === "Paid");

switch ($application_type) {
    case "stall":
        $response["isStall"] = ($stall["status"] === "available");
        break;

    case "stall transfer":
        $response["isStall"] = ($stall["user_id"] == $current_owner_id);
        break;

    case "stall succession":
        $response["isStall"] = ($stall["user_id"] == $deceased_owner_id);
        break;

    default:
        // For other application types, fallback to simple ownership check
        $response["isStall"] = ($stall["user_id"] == $user_id);
        break;
}

// Check stall transfer approval status
$transfer_approval = $pdo->prepare("
    SELECT transfer_confirmation_status 
    FROM stall_transfers 
    WHERE application_id = :application_id
");
$transfer_approval->bindParam(':application_id', $application_id, PDO::PARAM_INT);
$transfer_approval->execute();
$approval = $transfer_approval->fetch(PDO::FETCH_ASSOC);

if ($approval && $approval['transfer_confirmation_status'] === "Approved") {
    $response["isTransferApproved"] = true;
} else {
    $response["isTransferApproved"] = false;
    $response["status"] = $approval['transfer_confirmation_status'] ?? "Unknown";
}

echo json_encode($response);
