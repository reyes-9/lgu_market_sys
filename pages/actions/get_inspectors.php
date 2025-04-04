<?php
require '../../includes/config.php';

header('Content-Type: application/json');

try {
    $sql = "SELECT 
                CONCAT(u.first_name, ' ', COALESCE(u.middle_name, ''), ' ', u.last_name) AS full_name,
                a.id
            FROM accounts a
            JOIN users u ON a.id = u.account_id
            WHERE a.user_type = 'Inspector';
";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $inspectors = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(["success" => true, "inspectors" => $inspectors]);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
