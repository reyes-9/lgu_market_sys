<?php
require_once '../../includes/config.php';
header("Content-Type: application/json");

// Get user ID and stall number from AJAX request
$user_id = isset($_GET['user_id']) ? (int) $_GET['user_id'] : 0;
$stall_number = isset($_GET['stall_number']) ? (int) $_GET['stall_number'] : 0;
$application_type = isset($_GET['application_type']) ? $_GET['application_type'] : "";

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
    "hasViolation" => false
];

// Check if user exists
$applicant_check = $pdo->prepare("SELECT id FROM users WHERE id = :user_id LIMIT 1");
$applicant_check->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$applicant_check->execute();
if ($applicant_check->fetch()) {
    $response["isApplicant"] = true;
}

// Fetch stall details and violations in one go if needed
$stall_query = $pdo->prepare("
    SELECT s.status, s.user_id, 
           (SELECT COUNT(*) FROM violations WHERE user_id = :user_id) AS violation_count 
    FROM stalls s 
    WHERE s.stall_number = :stall_number
");
$stall_query->bindParam(':stall_number', $stall_number, PDO::PARAM_INT);
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

    // Check for violations
    $response["hasViolation"] = ($stall["violation_count"] > 0);
} else {
    $response["error"] = "Stall not found";
}


echo json_encode($response);
