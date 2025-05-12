
<?php
require_once '../../includes/config.php'; // Adjust as needed

header('Content-Type: application/json');

// Validate input
if (!isset($_POST['application_id']) || !isset($_POST['application_type'])) {
    echo json_encode(['success' => false, 'message' => 'Missing application ID or application type.']);
    exit;
}

$applicationId = $_POST['application_id'];
$applicationType = $_POST['application_type'];

$documents = [];

$stmt = $pdo->prepare("SELECT document_type FROM documents WHERE application_id = :application_id AND status = 'rejected'");
$stmt->bindParam(':application_id', $applicationId, PDO::PARAM_INT);
$stmt->execute();

$rejectedDocuments = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($rejectedDocuments) {
    $documents = array_map(function ($doc) {
        $normalized = normalizeData($doc['document_type']);
        return [
            'id' => $normalized['id'],
            'name' => $normalized['name'],
            'label' => $normalized['label']
        ];
    }, $rejectedDocuments);
}

echo json_encode(['success' => true, 'documents' => $documents]);


function normalizeData($data)
{
    $proper_case = ucwords(str_replace('_', ' ', $data));
    $camel_case = lcfirst(str_replace(' ', '', ucwords(strtolower($proper_case))));
    $snake_case = strtolower(str_replace(' ', '_', $proper_case));

    return [
        'id'  => $camel_case,
        'name'  => $snake_case,
        'label' => $proper_case
    ];
}
