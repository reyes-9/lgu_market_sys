<?php
require_once "../../includes/config.php";
require_once '../../includes/session.php';
include_once "notifications.php";
include_once "log_admin_actions.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = trim($_POST["title"]);
    $message = trim($_POST["message"]);
    $start_date = trim($_POST["start_date"]);
    $expiry_date = trim($_POST["expiry_date"]);
    $audience = trim($_POST["audience"]);

    // Check for missing fields
    if (empty($title) || empty($message) || empty($start_date) || empty($expiry_date) || empty($audience)) {
        echo json_encode(["success" => false, "message" => "All fields are required."]);
        exit;
    }

    $admin_id = $_SESSION['admin_id'];

    try {
        $stmt = $pdo->prepare("INSERT INTO announcements 
        (title, message, start_date, expiry_date, audience, created_by, created_at) 
        VALUES (:title, :message, :start_date, :expiry_date, :audience, :created_by, NOW())");

        $stmt->execute([
            ':title' => $title,
            ':message' => $message,
            ':start_date' => $start_date,
            ':expiry_date' => $expiry_date,
            ':audience' => $audience,
            ':created_by' => $admin_id
        ]);

        $announcement_id = $pdo->lastInsertId();

        logAdminAction($pdo, $admin_id, "Posted Announcement", "Posted Announcement ID: $announcement_id");

        echo json_encode(["success" => true, "message" => "Announcement posted successfully."]);
    } catch (PDOException $e) {
        echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request."]);
}
