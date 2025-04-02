<?php
header('Content-Type: application/json');
require_once '../../includes/config.php';

$response = ["success" => false, "payments" => []];

try {
    $query = $pdo->prepare("
    SELECT p.*, 
           CONCAT_WS(' ', u.first_name, u.middle_name, u.last_name) AS full_name
    FROM payments p
    JOIN users u ON p.user_id = u.id
    ORDER BY p.created_at DESC
");
    $query->execute();
    $payments = $query->fetchAll(PDO::FETCH_ASSOC);

    if ($payments) {
        $response["success"] = true;
        $response["payments"] = $payments;
    } else {
        $response["message"] = "No payments found.";
    }
} catch (PDOException $e) {
    $response["message"] = "Database error: " . $e->getMessage();
}

echo json_encode($response);
exit();
