<?php
require_once "../../includes/config.php";
include "notifications.php";
include "log_admin_actions.php";

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['id'])) {
    echo json_encode(["success" => false, "message" => "Invalid request. Violation ID is missing."]);
    exit;
}

$id = $data['id'];
$admin_id = $_SESSION['admin_id'];

$stmt = $pdo->prepare("UPDATE violations SET status = 'Deleted' WHERE id = ?");

if ($stmt->execute([$id])) {
    logAdminAction($pdo, $admin_id, "Deleted Violation", "Deleted Violation ID: $id");
    echo json_encode(["success" => true, "message" => "Violation ID #$id has been marked as resolved."]); 
} else {
    echo json_encode(["success" => false, "message" => "Failed to update violation status. Please try again."]);
}
