<?php
require_once "../../includes/config.php";
require_once '../../includes/session.php';
require_once "get_user_id.php";
header("Content-Type: application/json");

try {

    $account_id = $_SESSION["account_id"];

    $user_id = getUserIdByAccountId($pdo, $account_id);
    $rating = isset($_POST['rating']) ? (int) $_POST['rating'] : null;
    $comment = isset($_POST['comment']) ? trim($_POST['comment']) : "";
    $stall_id = isset($_POST['stall_id']) ? trim($_POST['stall_id']) : null;
    $sentiment = isset($_POST['sentiment']) ? trim($_POST['sentiment']) : "undefined";

    if (!$user_id) {
        throw new Exception("User not found.");
    }

    // Validate inputs
    if (empty($user_id) || empty($rating) || $rating < 1 || $rating > 5 || empty($stall_id)) {
        echo json_encode(["success" => false, "message" => "Invalid input"]);
        throw new Exception("Invalid Inputs.");
    }

    // Prepare and execute the query
    $stmt = $pdo->prepare("INSERT INTO stall_reviews ( stall_id, user_id, rating, comment, comment_sentiment) VALUES (:stall_id, :user_id, :rating, :comment, :comment_sentiment)");
    $stmt->execute([
        ':stall_id' => $stall_id,
        ':user_id' => $user_id,
        ':rating' => $rating,
        ':comment' => $comment,
        ':comment_sentiment' => $sentiment
    ]);

    // Send success response
    echo json_encode(["success" => true, "message" => "Review submitted successfully"]);
} catch (PDOException $e) {

    echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
} catch (Exception $e) {

    echo json_encode(["success" => false, "message" => "Error: " . $e->getMessage()]);
}
