<?php
require_once '../../includes/config.php';
require_once '../../includes/session.php';

header('Content-Type: application/json');
$account_id = $_SESSION['account_id'];
$user_id = getUserIdByAccountId($pdo, $account_id);
try {
    $stmt = $pdo->prepare("
        SELECT v.id, 
               v.violation_date, 
               v.violation_description, 
               v.status,
               vt.fine_amount,
               vt.violation_name,
               vt.escalation_fee
        FROM violations v
        JOIN expiration_dates e ON v.id = e.reference_id
        JOIN violation_types vt ON v.violation_type_id = vt.id
        WHERE e.type = 'violation' 
          AND e.status IN ('expired', 'payment_period')
          AND v.payment_status IN ('Unpaid', 'Overdue', 'Pending')
          AND v.user_id = :user_id;
    ");
    $stmt->bindParam(":user_id", $user_id);
    $stmt->execute();
    $violations = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'violations' => $violations
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Failed to fetch violations: ' . $e->getMessage()
    ]);
}
