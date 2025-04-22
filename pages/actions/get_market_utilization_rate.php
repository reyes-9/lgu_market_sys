<?php
require '../../includes/config.php'; // adjust this to your database connection file

header('Content-Type: application/json');

$marketId = isset($_GET['market_id']) ? intval($_GET['market_id']) : 0;
if ($marketId <= 0) {
    echo json_encode(['error' => 'Invalid market ID']);
    exit;
}

try {
    // 1. Get total stalls for this market
    $stmt = $pdo->prepare("SELECT COUNT(*) AS total FROM stalls WHERE market_id = ?");
    $stmt->execute([$marketId]);
    $totalStalls = $stmt->fetchColumn();

    if ($totalStalls == 0) {
        echo json_encode(['error' => 'No stalls found for this market']);
        exit;
    }

    // 2. Get occupied stall count grouped by day
    $stmt = $pdo->prepare("
        SELECT DATE(approved_at) AS date, COUNT(*) AS occupied
        FROM applications
        WHERE market_id = ? AND status = 'approved'
        GROUP BY DATE(approved_at)
        ORDER BY DATE(approved_at)
    ");
    $stmt->execute([$marketId]);
    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 3. Compute utilization per day
    $trend = [];
    foreach ($records as $row) {
        $utilization = ($row['occupied'] / $totalStalls) * 100;
        $trend[] = [
            'date' => $row['date'],
            'utilization' => round($utilization, 2)
        ];
    }

    echo json_encode($trend);
} catch (Exception $e) {
    echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
}
