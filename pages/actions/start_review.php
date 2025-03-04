<?php
require_once "../../includes/config.php";

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['application_id'])) {
    $application_id = $data['application_id'];

    $query = "UPDATE applications 
    SET status = 'Under Review', 
        reviewed_at = COALESCE(reviewed_at, NOW()) 
    WHERE id = :id";

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $application_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Review started"]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to update status"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request"]);
}
