<?php
require_once "../../includes/config.php";

// Fetch the last application ID from the database
$query = "SELECT id FROM applications ORDER BY id DESC LIMIT 1"; // Get the last ID inserted
$stmt = $pdo->query($query);

if ($stmt) {
    // Fetch the result
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $lastApplicationId = $row ? $row['id'] : 0; // If no records, start from 0
    echo json_encode(['last_application_id' => $lastApplicationId]);
} else {
    echo json_encode(['error' => 'Error fetching last application ID']);
}
