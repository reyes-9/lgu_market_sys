<?php
require_once "../../includes/config.php";
header('Content-Type: application/json');

try {

    // Get stall ID from request
    $stallId = isset($_GET['stall_id']) ? intval($_GET['stall_id']) : 0;

    if ($stallId <= 0) {
        echo json_encode(["success" => false, "message" => "Invalid stall ID"]);
        exit;
    }

    $stmt = $pdo->prepare("
    SELECT 
          CONCAT(u.first_name, ' ', u.last_name) AS author, 
        s.comment, 
        s.rating, 
        s.created_at
    FROM stall_reviews AS s
    JOIN users AS u ON s.user_id = u.id
    WHERE s.stall_id = :stall_id
    ORDER BY s.created_at DESC;
    ");
    $stmt->execute(['stall_id' => $stallId]);
    $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return JSON response
    echo json_encode(["success" => true, "comments" => $comments]);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
}
