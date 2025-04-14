<?php
require_once '../../includes/config.php';
require_once '../../includes/session.php';
header('Content-Type: application/json');

try {
    // Only allow POST requests
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode([
            'success' => false,
            'message' => ['Invalid request method.']
        ]);
        exit;
    }

    // Get the input
    $input = json_decode(file_get_contents('php://input'), true);
    $application_id = isset($input['id']) ? (int)$input['id'] : 0;
    $application_type = isset($input['type']) ? trim($input['type']) : '';

    if ($application_id <= 0) {
        echo json_encode([
            'success' => false,
            'message' => ['Invalid application ID.']
        ]);
        exit;
    }

    // Update the status to Withdrawn
    $updateStmt = $pdo->prepare("UPDATE applications SET status = 'Withdrawn' WHERE id = ?");
    $updateStmt->execute([$application_id]);

    if ($updateStmt->rowCount() > 0) {
        echo json_encode([
            'success' => true,
            'message' => ["Application '{$application_type}' has been successfully withdrawn."]
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => ["No changes made. It's possible the application is already withdrawn."]
        ]);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => ['Server error: ' . $e->getMessage()]
    ]);
}
