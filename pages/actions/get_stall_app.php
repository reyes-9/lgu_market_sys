<?php
require 'db_connection.php'; // Include your PDO database connection

header('Content-Type: application/json');

try {
    // Create PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Optional: Get status filter from request
    $statusFilter = isset($_GET['status']) ? $_GET['status'] : null;

    // Base SQL query
    $sql = "SELECT sa.id, sa.account_id, sa.stall_id, sa.status, sa.application_date, 
                   u.name AS applicant_name, s.stall_number, s.section
            FROM stall_applications sa
            JOIN users u ON sa.account_id = u.id
            JOIN stalls s ON sa.stall_id = s.id";

    // If status is provided, add a WHERE clause
    if ($statusFilter) {
        $sql .= " WHERE sa.status = :status";
    }

    $stmt = $pdo->prepare($sql);

    // Bind status parameter if needed
    if ($statusFilter) {
        $stmt->bindParam(':status', $statusFilter, PDO::PARAM_STR);
    }

    $stmt->execute();
    $applications = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return data as JSON
    echo json_encode(['success' => true, 'applications' => $applications]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error fetching stall applications.', 'error' => $e->getMessage()]);
}
?>
