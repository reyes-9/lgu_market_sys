<?php
require_once "../../includes/config.php";
require_once '../../includes/session.php';

header("Content-Type: application/json");
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['inspector_id']) || !isset($data['inspection_date']) || !isset($data['application_id'])) {
    http_response_code(400); // Bad Request
    echo json_encode(["success" => false, "message" => "Missing required fields."]);
    exit;
}

$inspector_id = $data['inspector_id'];
$inspection_date = $data['inspection_date'];
$application_id = $data['application_id'];

try {
    $sql = "UPDATE applications 
            SET inspector_id = :inspector_id, 
                inspection_date = :inspection_date, 
                inspection_status = 'Scheduled'
            WHERE id = :application_id;";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ":inspector_id" => $inspector_id,
        ":inspection_date" => $inspection_date,
        "application_id" => $application_id
    ]);

    if ($stmt->rowCount() > 0) {
        http_response_code(201); // Created
        echo json_encode(["success" => true, "message" => "Inspection confirmed successfully."]);
    } else {
        http_response_code(500); // Internal Server Error
        echo json_encode(["success" => false, "message" => "Failed to confirm inspection."]);
    }
} catch (PDOException $e) {
    http_response_code(500); // Internal Server Error
    echo json_encode(["success" => false, "message" => "Database error"]);
    error_log($e->getMessage());
}
