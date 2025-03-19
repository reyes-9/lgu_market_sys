<?php

function uploadDocument($pdo, $fileInputName, $applicationId, $documentType, $uploadDir = 'uploads/')
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

    // Get file extension
    $fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    // Secure filename (Remove spaces/special characters)
    $safeDocumentType = preg_replace('/[^a-zA-Z0-9_-]/', '_', $documentType);
    $randomStr = bin2hex(random_bytes(5)); // Random string for uniqueness
    $finalFileName = $safeDocumentType . '_' . time() . '_' . $randomStr . '.' . $fileExt;

    // Correct full file path
    $fullFilePath = $uploadPath . DIRECTORY_SEPARATOR . $finalFileName;

    // Move file to uploads folder
    if (move_uploaded_file($file['tmp_name'], $fullFilePath)) {
        try {
            // Store file details in database
            $query = "INSERT INTO documents (application_id, document_name, document_type, document_path, uploaded_at, status) 
                      VALUES (:application_id, :document_name, :document_type, :document_path, NOW(), 'Pending')`";
            $stmt = $pdo->prepare($query);
            $stmt->execute([
                ':application_id' => $applicationId,
                ':document_name' => $finalFileName,  // Store file name only
                ':document_type' => $documentType,
                ':document_path' => $uploadDir . $finalFileName // Store relative path
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
