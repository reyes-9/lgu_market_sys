<?php

require_once '../../includes/config.php';

if (isset($_GET['market_id']) && isset($_GET['section_id'])) {
    $market_id = $_GET['market_id'];
    $section_id = $_GET['section_id'];

    // Prepare the query
    $stmt = $pdo->prepare("SELECT id, stall_number, rental_fee, stall_size, status FROM stalls WHERE market_id = :market_id AND section_id = :section_id");
    $stmt->bindParam(':market_id', $market_id, PDO::PARAM_INT);
    $stmt->bindParam(':section_id', $section_id, PDO::PARAM_INT);

    try {
        $stmt->execute();
        $stalls = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($stalls) {

            // Filter out stalls that are not available
            $availableStalls = array_filter($stalls, function ($stall) {
                return $stall['status'] === 'available';
            });

            header('Content-Type: application/json');
            echo json_encode(array_values($availableStalls));
        } else {

            http_response_code(404); // Not Found
            echo json_encode(['message' => 'No available stalls found for this section']);
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Market ID not provided']);
}
