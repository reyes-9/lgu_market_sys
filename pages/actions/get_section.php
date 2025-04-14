<?php

require_once '../../includes/config.php';
require_once '../../includes/session.php';

try {
    $stmt = $pdo->prepare("SELECT id, section_name FROM sections");

    if ($stmt->execute()) {
        $sections = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($sections) {
            header('Content-Type: application/json');
            echo json_encode($sections);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'No market sections found']);
        }
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to execute query']);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Unexpected error: ' . $e->getMessage()]);
}
