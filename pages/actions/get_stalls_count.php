<?php
require_once '../../includes/config.php';

header("Content-Type: application/json");

$marketId = $_GET['market_id'] ?? '';

if (empty($marketId)) {
    echo json_encode(["error" => "Market ID is required"]);
    exit;
}

$sections = [
    1 => "Meat",
    2 => "Vegetables",
    3 => "Fish",
    4 => "Dry Goods",
    5 => "Carinderia",
    6 => "Grocery"
];

$sectionCounts = [];

try {
    // Prepare the query once to prevent multiple prepares
    $stmt = $pdo->prepare("SELECT COUNT(id) FROM stalls WHERE section_id = :section_id AND market_id = :market_id");

    foreach ($sections as $sectionId => $sectionName) {
        $stmt->execute([
            'section_id' => $sectionId,
            'market_id' => $marketId
        ]);

        $count = $stmt->fetchColumn();
        $sectionCounts[$sectionName] = $count;
    }

    echo json_encode($sectionCounts);
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
