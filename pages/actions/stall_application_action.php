<?php
require_once "../../includes/config.php";

session_start();
ob_start();

$response = ['success' => false, 'messages' => []];

// Check request method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['messages'][] = "Invalid request.";
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}

// Validate user session
if (!isset($_SESSION['user_id'])) {
    $response['messages'][] = "User not logged in. Please log in first.";
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}

// Validate and sanitize input fields
$account_id = filter_var($_SESSION['user_id'], FILTER_VALIDATE_INT);
$market_id = filter_input(INPUT_POST, 'market', FILTER_VALIDATE_INT);
$stall_id = filter_input(INPUT_POST, 'stall', FILTER_VALIDATE_INT);
$section_id = filter_input(INPUT_POST, 'section', FILTER_VALIDATE_INT);
$application_type = isset($_POST['application_type']) ? htmlspecialchars(trim($_POST['application_type']), ENT_QUOTES, 'UTF-8') : '';

if ($account_id === false || $market_id === false || $stall_id === false || $section_id === false || empty($application_type)) {
    $response['messages'][] = "Invalid input. Please check your data.";
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}

$uploadedFiles = $_FILES['documents'];

// Prepare application data
$application = [
    'account_id' => $account_id,
    'stall_id' => $stall_id,
    'section_id' => $section_id,
    'market_id' => $market_id,
    'application_type' => $application_type,
];

$isFileUploaded = uploadFiles($uploadedFiles);

if ($isFileUploaded === true) {
    // Submit the application
    $isAppSubmitted = submitApplication($application, $pdo);
    
    if ($isAppSubmitted['status'] === false) {
        $response['messages'][] = "Failed to create application. Please try again.";
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }

    $application_id = $isAppSubmitted['application_id'];

    for ($i = 0; $i < count($uploadedFiles['name']); $i++) {
        if ($uploadedFiles['error'][$i] == UPLOAD_ERR_OK) {
            $document_type = assignDocumentType($i); // Get the document type
            $file_name = $uploadedFiles['name'][$i];
            $file_path = '../../uploads/' . $file_name; // Destination path

            // Insert the document into the database
            $document = [
                'application_id' => $application_id,
                'document_type' => $document_type,
                'file_name' => $file_name,
                'file_path' => $file_path,
            ];

            print_r($document);

            if (!submitDocuments($document, $pdo)) {
                $response['messages'][] = "Failed to upload document: " . $file_name;
            }
        }
    }

    $response['success'] = true;
    $response['messages'][] = "All files uploaded successfully.";
} else {
    $response['messages'][] = "File upload failed.";
}

header('Content-Type: application/json');
echo json_encode($response);







// Function Definitions

function assignDocumentType($index) {
    $documentNames = [
        'Transfer Document',
        'Succession Document',
        'QC ID Document',
        'Current ID Document',
    ];

    return isset($documentNames[$index]) ? $documentNames[$index] : 'Unknown Document Type';
}

function submitDocuments(array $document, $pdo) {
    $sql_document = "INSERT INTO documents (application_id, document_type, document_name, document_path) VALUES (:application_id, :document_type, :document_name, :document_path)";
    $stmt_document = $pdo->prepare($sql_document);
    $stmt_document->bindParam(':application_id', $document['application_id']);
    $stmt_document->bindParam(':document_type', $document['document_type']);
    $stmt_document->bindParam(':document_name', $document['file_name']);
    $stmt_document->bindParam(':document_path', $document['file_path']);

    return $stmt_document->execute();
}

function submitApplication(array $application, $pdo) {
    $sql_application = "INSERT INTO applications (account_id, stall_id, section_id, market_id, application_type) VALUES (:account_id, :stall_id, :section_id, :market_id, :application_type)";
    $stmt_application = $pdo->prepare($sql_application);
    $stmt_application->bindParam(':account_id', $application['account_id']);
    $stmt_application->bindParam(':stall_id', $application['stall_id']);
    $stmt_application->bindParam(':section_id', $application['section_id']);
    $stmt_application->bindParam(':market_id', $application['market_id']);
    $stmt_application->bindParam(':application_type', $application['application_type']);

    if (!$stmt_application->execute()) {
        return ['status' => false];
    }

    return [
        'status' => true,
        'application_id' => $pdo->lastInsertId(),
    ];
}

function uploadFiles($uploadedFiles) {
    $file_upload_success = true;
    $error_messages = [];

    for ($i = 0; $i < count($uploadedFiles['name']); $i++) {
        if ($uploadedFiles['error'][$i] == UPLOAD_ERR_OK) {
            $fileName = $uploadedFiles['name'][$i];
            $fileTmpPath = $uploadedFiles['tmp_name'][$i];
            $destinationPath = '../../uploads/' . $fileName;

            if (!move_uploaded_file($fileTmpPath, $destinationPath)) {
                $file_upload_success = false;
                $error_messages[] = "Error moving file: " . $fileName;
            }
        } else {
            $file_upload_success = false;
            $error_messages[] = "Error uploading file: " . $uploadedFiles['name'][$i];
        }
    }

    if (!empty($error_messages)) {
        foreach ($error_messages as $message) {
            echo "<p class='error-message'>$message</p>";
        }
    }
    
    return $file_upload_success;
}
