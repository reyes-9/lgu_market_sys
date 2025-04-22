<?php

require_once '../../includes/config.php';
require_once '../../includes/session.php';
require_once 'get_user_id.php';

$account_id = $_SESSION['account_id'];
$user_id = getUserIdByAccountId($pdo, $account_id);
try {
    $stmt = $pdo->prepare("
        SELECT s.id, s.stall_number, s.rental_fee, s.payment_status, s.market_id, s.section_id,
               e.expiration_date,
               m.market_name,
               sc.section_name
        FROM stalls s
        JOIN expiration_dates e ON s.id = e.reference_id
        JOIN market_locations m ON s.market_id = m.id
        JOIN sections sc ON s.section_id = sc.id
        WHERE e.type = 'stall' 
          AND e.status IN ('unpaid', 'expired', 'payment_period')
          AND s.payment_status IN ('Unpaid', 'Overdue', 'Payment_Period')
          AND s.user_id = :user_id;
    ");

    $stmt->bindParam(":user_id", $user_id);
    $stmt->execute();
    $unpaidStalls = $stmt->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    if ($unpaidStalls) {
        echo json_encode(['success' => true, 'unpaid_stalls' => $unpaidStalls]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No unpaid stalls found']);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
