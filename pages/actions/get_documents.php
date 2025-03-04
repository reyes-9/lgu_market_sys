<?php
require_once "../../includes/config.php";

if (!isset($_GET['application_id'])) {
    echo json_encode(['error' => 'No application ID provided']);
    exit;
}

$application_id = $_GET['application_id'];

try {
    $stmt = $pdo->prepare("SELECT id, document_name, document_path FROM documents WHERE application_id = :application_id");
    $stmt->bindParam(':application_id', $application_id, PDO::PARAM_INT);
    $stmt->execute();
    $documents = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($documents) {
        echo json_encode($documents);
    } else {
        echo json_encode(['error' => 'No documents found']);
    }
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
