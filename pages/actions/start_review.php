
<?php
require_once "../../includes/config.php";
require "../../includes/session.php";
require "log_admin_actions.php";

header('Content-Type: application/json');

$hashed = json_decode(file_get_contents('php://input'), true);

// Validate JSON input
if (!is_array($hashed) || !isset($hashed['application_id'])) {
    echo json_encode(["success" => false, "message" => "Invalid request"]);
    exit;
}

$data = base64_decode($hashed['application_id']);

// Validate application_id as an integer
$application_id = filter_var($data, FILTER_VALIDATE_INT);
if (!$application_id) {
    echo json_encode(["success" => false, "message" => "Invalid application ID"]);
    exit;
}

try {
    // Start the transaction
    $pdo->setAttribute(PDO::ATTR_AUTOCOMMIT, false);
    $pdo->beginTransaction();

    // Check if the application is available for review (not already locked by another admin)
    $checkQuery = "SELECT * FROM applications 
                   WHERE id = :id AND (reviewing_admin_id IS NULL OR reviewing_admin_id = :admin_id) 
                   FOR UPDATE";
    $checkStmt = $pdo->prepare($checkQuery);
    $admin_id = $_SESSION['account_id']; // Assuming session contains the admin's ID
    $checkStmt->bindParam(':id', $application_id, PDO::PARAM_INT);
    $checkStmt->bindParam(':admin_id', $admin_id, PDO::PARAM_INT);
    $checkStmt->execute();

    if ($checkStmt->rowCount() == 0) {
        // If no rows are returned, it means the application is already locked by another admin
        $pdo->rollBack(); // Rollback the transaction
        $pdo->setAttribute(PDO::ATTR_AUTOCOMMIT, true);
        echo json_encode(["success" => false, "message" => "This application is already being reviewed by another admin."]);
        exit;
    }

    // Lock the application for this admin
    $updateQuery = "UPDATE applications 
                    SET status = 'Under Review', 
                        reviewed_at = COALESCE(reviewed_at, NOW()), 
                        reviewing_admin_id = :admin_id 
                    WHERE id = :id";
    $updateStmt = $pdo->prepare($updateQuery);
    $updateStmt->bindParam(':id', $application_id, PDO::PARAM_INT);
    $updateStmt->bindParam(':admin_id', $admin_id, PDO::PARAM_INT);

    // Execute the update and commit the transaction
    if ($updateStmt->execute()) {

        $pdo->commit(); // Commit the transaction
        $pdo->setAttribute(PDO::ATTR_AUTOCOMMIT, true);
        logAdminAction($pdo, $admin_id, "Start Review", "Started review for application ID: " . $application_id);
        echo json_encode(["success" => true, "message" => "Review started"]);
    } else {
        $pdo->rollBack(); // Rollback if update failed
        $pdo->setAttribute(PDO::ATTR_AUTOCOMMIT, true);
        $errorInfo = $updateStmt->errorInfo();
        echo json_encode(["success" => false, "message" => "Failed to update status", "error" => $errorInfo[2]]);
    }
} catch (Exception $e) {
    $pdo->rollBack(); // Rollback the transaction in case of an error
    $pdo->setAttribute(PDO::ATTR_AUTOCOMMIT, true);
    echo json_encode(["success" => false, "message" => "Error: " . $e->getMessage()]);
}
?>
