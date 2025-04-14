<?php
require_once '../../includes/config.php';
require_once '../../includes/session.php';

header('Content-Type: application/json');

try {
    $stmt = $pdo->prepare("
        SELECT id, violation_date, violation_description, status 
        FROM violations 
        WHERE status = 'unpaid'
    ");
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
