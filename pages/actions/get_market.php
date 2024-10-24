<?php

require_once '../../includes/config.php';
if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    $errors[] = "Invalid request. Please try again.";
    header('Location: login.php');
    exit();
}

try {
    $stmt = $pdo->prepare("SELECT id, market_name, market_address FROM market_locations");
    
    if ($stmt->execute()) {
        $locations = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($locations) {
            header('Content-Type: application/json');
            echo json_encode($locations);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'No market locations found']);
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

?>
