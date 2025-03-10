<?php
require_once "../../includes/config.php";

header("Content-Type: application/json");

// Read JSON Data from Fetch Request
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data["document_path"])) {
    echo json_encode(["success" => false, "message" => "Invalid request"]);
    exit;
}

$document_path = $data["document_path"];

try {
    // Update document status in the database
    $sql = "UPDATE documents SET 
                    status = 'Valid',   
                    rejection_reason = NULL
            WHERE document_path = :document_path";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':document_path', $document_path, PDO::PARAM_STR);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to update document status"]);
    }
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
}
