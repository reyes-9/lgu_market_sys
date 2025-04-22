<?php

require_once '../../includes/config.php';
require_once '../../includes/session.php';
require_once 'get_user_id.php';

$account_id = $_SESSION['account_id'];
$user_id = getUserIdByAccountId($pdo, $account_id);
try {
    $stmt = $pdo->prepare("
        SELECT s.id AS stall_id, s.stall_number, s.market_id, s.section_id,
               e.expiration_date,
               m.market_name,
               sc.section_name,
               ex.extension_cost,
               ex.payment_status,
               ex.duration,
               ex.id AS extension_id
        FROM stalls s
        JOIN extensions ex ON s.id = ex.stall_id
        JOIN expiration_dates e ON ex.id = e.reference_id
        JOIN market_locations m ON s.market_id = m.id
        JOIN sections sc ON s.section_id = sc.id
        WHERE e.type = 'stall extension'
          AND e.status IN ('expired', 'payment_period')
          AND ex.payment_status IN ('Unpaid', 'Overdue', 'Payment_Period')
          AND s.user_id = :user_id
    ");

    $stmt->bindParam(":user_id", $user_id);
    $stmt->execute();
    $unpaidExtensions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    if ($unpaidExtensions) {
        echo json_encode(['success' => true, 'unpaid_extensions' => $unpaidExtensions]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No unpaid extensions found']);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
