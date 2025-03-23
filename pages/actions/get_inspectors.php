<?php
require '../../includes/config.php';

header('Content-Type: application/json');

try {
    $sql = "SELECT id, name FROM inspectors";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $inspectors = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(["success" => true, "inspectors" => $inspectors]);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
