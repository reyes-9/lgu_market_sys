<?php
require_once '../../includes/config.php';
require_once '../../includes/session.php';

header('Content-Type: application/json');

try {
    $input = json_decode(file_get_contents("php://input"), true);
    $selectedId = intval($input['id']);

    if (!$selectedId) {
        throw new Exception("Missing or invalid market ID");
    }

    $stmt = $pdo->prepare("
    SELECT 
        s.id, 
        s.stall_number, 
        s.rental_fee, 
        s.stall_size, 
        s.status, 
        CONCAT(u.first_name, ' ', 
               COALESCE(NULLIF(u.middle_name, ''), ''), ' ', 
               u.last_name) AS user_name, 
        sec.section_name
    FROM stalls s
    LEFT JOIN users u ON s.user_id = u.id
    LEFT JOIN sections sec ON s.section_id = sec.id
    WHERE s.market_id = :id
");

    $stmt->execute([':id' => $selectedId]);
    $stalls = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['success' => true, 'stalls' => $stalls]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
