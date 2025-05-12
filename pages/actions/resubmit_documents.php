<?php
require_once "../../includes/config.php";

header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception("Invalid request method.");
    }

    if (!isset($_POST['application_id']) || !is_numeric($_POST['application_id'])) {
        throw new Exception("Missing or invalid application ID.");
    }

    $applicationId = (int) $_POST['application_id'];

    if (empty($_FILES)) {
        throw new Exception("No documents uploaded.");
    }

    $document_id = '';
    $rejectedDocs = getRejectedDocuments($pdo, $applicationId);

    $rejectedDocIds = array_column($rejectedDocs, 'id');

    $uploadResults = [];
    $index = 0;

    foreach ($_FILES as $inputName => $file) {
        // Use the corresponding document ID in order
        $document_id = isset($rejectedDocIds[$index]) ? $rejectedDocIds[$index] : null;
        $index++;

        $documentType = formatDocumentType($inputName);
        $result = uploadDocument($pdo, $inputName, $applicationId, $document_id, $documentType);

        $uploadResults[] = array_merge(["input" => $inputName], $result);
    }

    echo json_encode([
        "success" => true,
        "message" => "Resubmission attempt completed.",
        "results" => $uploadResults
    ]);
} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
}

function uploadDocument($pdo, $fileInputName, $applicationId, $document_id, $documentType, $uploadDir = 'uploads/')
{
    if (!isset($_FILES[$fileInputName]) || $_FILES[$fileInputName]['error'] !== UPLOAD_ERR_OK) {
        return ["success" => false, "message" => "No file uploaded or file upload error."];
    }

    $file = $_FILES[$fileInputName];
    $uploadPath = dirname(__DIR__, 2) . '/' . $uploadDir;

    // Create upload directory if it doesn't exist
    if (!is_dir($uploadPath)) {
        mkdir($uploadPath, 0777, true);
    }

    $fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    // Secure filename (Remove spaces/special characters)
    $safeDocumentType = preg_replace('/[^a-zA-Z0-9_-]/', '_', $documentType);
    $randomStr = bin2hex(random_bytes(5)); // Random string for uniqueness
    $finalFileName = $safeDocumentType . '_' . time() . '_' . $randomStr . '.' . $fileExt;

    $fullFilePath = $uploadPath . DIRECTORY_SEPARATOR . $finalFileName;

    // Move file to uploads folder
    if (move_uploaded_file($file['tmp_name'], $fullFilePath)) {
        try {

            $query = "UPDATE applications 
              SET status = 'Under Review'
              WHERE id = :application_id";
            $stmt = $pdo->prepare($query);
            $stmt->execute([
                ':application_id' => $applicationId,
            ]);

            // Store file details in database
            $query = "UPDATE documents 
              SET document_name = :document_name,
                  document_type = :document_type,
                  document_path = :document_path,
                  uploaded_at = NOW(),
                  status = 'Pending'
              WHERE application_id = :application_id
                AND status = 'Rejected'
                AND id = :document_id";
            $stmt = $pdo->prepare($query);
            $stmt->execute([
                ':document_id' => $document_id,
                ':application_id' => $applicationId,
                ':document_name' => $finalFileName,
                ':document_type' => $documentType,
                ':document_path' => $uploadDir . $finalFileName
            ]);

            return [
                "success" => true,
                "message" => "File uploaded successfully.",
                "file_name" => $finalFileName,
                "file_path" => $uploadDir . $finalFileName
            ];
        } catch (PDOException $e) {
            return ["success" => false, "message" => "Database error: " . $e->getMessage()];
            var_dump($e->getMessage());
        }
    } else {
        return ["success" => false, "message" => "Failed to move uploaded file."];
    }
}

function formatDocumentType($input)
{
    // Replace underscores and parentheses with spaces
    $input = str_replace(['_', '(', ')'], ' ', $input);

    // Collapse multiple spaces
    $input = preg_replace('/\s+/', ' ', $input);

    // Trim and capitalize each word
    return ucwords(trim($input));
}

function getRejectedDocuments(PDO $pdo, int $applicationId): array
{
    try {
        $query = "SELECT id, document_type
                  FROM documents
                  WHERE application_id = :application_id
                    AND status = 'Rejected'";

        $stmt = $pdo->prepare($query);
        $stmt->execute([':application_id' => $applicationId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return ["error" => "Database error: " . $e->getMessage()];
    }
}
