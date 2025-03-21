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
if (!isset($data['account_id'])) {
    echo json_encode(["success" => false, "message" => "Invalid request. Account ID is missing."]);
    exit;
}

$id = $data['id'];
$account_id = $data['account_id'];
$admin_id = $_SESSION['admin_id'];

$stmt = $pdo->prepare("UPDATE violations SET status = 'Resolved' WHERE id = ?");

if ($stmt->execute([$id])) {

    logAdminAction($pdo, $admin_id, "Resolved Violation", "Resolved Violation ID: $id");
    insertNotification($pdo, $account_id, "Violation Resolved", "Violation ID #$id has been resolved.", 'unread');
    echo json_encode(["success" => true, "message" => "Violation ID #$id has been marked as resolved."]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to update violation status. Please try again."]);
}
