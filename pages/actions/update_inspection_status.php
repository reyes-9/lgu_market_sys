<?php
require_once "../../includes/config.php";

// Check if the required POST data is present
if (isset($_POST['id']) && isset($_POST['status'])) {

    $application_id = intval($_POST['id']);
    $status = trim($_POST['status']);

    $validStatuses = ['approved', 'rejected'];
    if (!in_array(strtolower($status), $validStatuses)) {
        echo json_encode(['success' => false, 'message' => 'Invalid status provided.']);
        exit;
    }

    $sql = "UPDATE applications SET inspection_status = :status WHERE id = :application_id";

    try {
        // Prepare and execute the SQL query
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);
        $stmt->bindParam(':application_id', $application_id, PDO::PARAM_INT);

        // Execute the query
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => "Inspection status updated to $status."]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update inspection status.']);
        }
    } catch (PDOException $e) {
        // Handle any database errors
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Missing required data (id, status).']);
}
