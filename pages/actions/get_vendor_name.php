<?php
require_once '../../includes/config.php';
require_once '../../includes/session.php';

if (isset($_GET['stall_id'])) {
    $stallId = $_GET['stall_id'];

    $stmt = $pdo->prepare("
    SELECT CONCAT_WS(' ', 
        u.first_name,
        CASE 
            WHEN LOWER(u.middle_name) = 'n/a' THEN NULL 
            ELSE u.middle_name 
        END,
        u.last_name
    ) AS vendor_name,
    s.user_id
    FROM stalls s
    JOIN users u ON s.user_id = u.id
    WHERE s.id = ?
    ");
    $stmt->execute([$stallId]);
    $vendor = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($vendor) {
        echo json_encode(['success' => true, 'user_id' => $vendor['user_id'], 'vendor_name' => $vendor['vendor_name']]);
    } else {
        echo json_encode(['success' => false, 'vendor_name' => null]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Missing stall_id']);
}
