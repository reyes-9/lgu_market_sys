<?php
require_once "../../includes/config.php";

header("Content-Type: application/json");

// Read JSON Data from Fetch Request
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data["document_path"]) || !isset($data["reason"])) {
    echo json_encode(["success" => false, "message" => "Invalid request"]);
    exit;
}

$document_path = $data["document_path"];
$rejection_reason = $data["reason"];

try {
    // Update document status and rejection reason in the database
    $query = "UPDATE documents SET status = 'Rejected', rejection_reason = :reason WHERE document_path = :document_path";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':document_path', $document_path, PDO::PARAM_STR);
    $stmt->bindParam(':reason', $rejection_reason, PDO::PARAM_STR);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to update document status"]);
    }
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
}
