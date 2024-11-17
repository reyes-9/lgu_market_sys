<?php
require_once "../../includes/config.php";

session_start();
ob_start();

$response = ['success' => false, 'messages' => []];

if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    $_SESSION['error'] = "Invalid request. Please try again.";
    header('Location: profile.php');
    exit();
}

// Check request method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['messages'][] = "Invalid request.";
    header('Content-Type: application/json');
    // echo json_encode($response);
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
$selected_stall_id = filter_input(INPUT_POST, 'selected_stall_id', FILTER_VALIDATE_INT);
$duration = filter_input(INPUT_POST, 'duration', FILTER_VALIDATE_INT);
$duration = $duration !== false ? $duration : null;

// print_r($_POST);

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
    'ext_duration' => $duration
];

if ($application_type == "stall extension") {

    $sql_find_stall = "SELECT id, market_id, section_id, account_id FROM stalls WHERE id = :stall_id";
    $stmt_application = $pdo->prepare($sql_find_stall);
    $stmt_application->bindParam(':stall_id', $selected_stall_id);
    $stmt_application->execute();
    $result = $stmt_application->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $stall_id = $result['id'];
        $market_id = $result['market_id'];
        $section_id = $result['section_id'];
        $account_id = $result['account_id'];

        $application = [
            'account_id' => $account_id,
            'stall_id' => $stall_id,
            'section_id' => $section_id,
            'market_id' => $market_id,
            'application_type' => $application_type,
            'ext_duration' => $duration
        ];
    }

    try {
        $isAppSubmitted = submitApplication($application, $pdo);

        if ($isAppSubmitted['status'] === false) {
            $response['messages'][] = "Application Failed.";
            header('Content-Type: application/json');
            echo json_encode($response);
            exit();
        }

        $response['success'] = true;
        $response['messages'][] = "Application Submitted.";
    } catch (Exception $e) {
        $response['messages'][] = "Application Failed.";
    }
    ob_end_clean();
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}

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
            $document_type = assignDocumentType($application_type, $i); // Get the document type
            $file_name = $uploadedFiles['name'][$i];
            $file_path = '../../uploads/' . $file_name; // Destination path

            // Insert the document into the database
            $document = [
                'application_id' => $application_id,
                'document_type' => $document_type,
                'file_name' => $file_name,
                'file_path' => $file_path,
            ];

            // print_r($document);
            // print_r($_POST);

            if (!submitDocuments($document, $pdo)) {
                $response['messages'][] = "Failed to upload document: " . $file_name;
            }
        }
    }

    $response['success'] = true;
    $response['messages'][] = "Application Submitted.";
} else {
    $response['messages'][] = "Application Failed.";
}

ob_end_clean();
header('Content-Type: application/json');
// print_r($response);
echo json_encode($response);




// Function Definitions
function assignDocumentType($application_type, $index)
{

    switch ($application_type) {
        case 'stall transfer':

            $documentNames = [
                'Transfer Document',
                'QC ID Document',
                'Current ID Document',
            ];

            break;
        case 'stall succession':
            $documentNames = [
                'Succession Document',
                'QC ID Document',
                'Current ID Document',
            ];
            break;
    }


    return isset($documentNames[$index]) ? $documentNames[$index] : 'Unknown Document Type';
}

function submitDocuments(array $document, $pdo)
{
    $sql_document = "INSERT INTO documents (application_id, document_type, document_name, document_path) VALUES (:application_id, :document_type, :document_name, :document_path)";
    $stmt_document = $pdo->prepare($sql_document);
    $stmt_document->bindParam(':application_id', $document['application_id']);
    $stmt_document->bindParam(':document_type', $document['document_type']);
    $stmt_document->bindParam(':document_name', $document['file_name']);
    $stmt_document->bindParam(':document_path', $document['file_path']);

    return $stmt_document->execute();
}

function submitApplication(array $application, $pdo)
{
    $sql_application = "INSERT INTO applications (account_id, stall_id, section_id, market_id, application_type, ext_duration) VALUES (:account_id, :stall_id, :section_id, :market_id, :application_type, :ext_duration)";
    $stmt_application = $pdo->prepare($sql_application);
    $stmt_application->bindParam(':account_id', $application['account_id']);
    $stmt_application->bindParam(':stall_id', $application['stall_id']);
    $stmt_application->bindParam(':section_id', $application['section_id']);
    $stmt_application->bindParam(':market_id', $application['market_id']);
    $stmt_application->bindParam(':application_type', $application['application_type']);
    $stmt_application->bindParam(':ext_duration', $application['ext_duration']);


    if (!$stmt_application->execute()) {
        return ['status' => false];
    }

    if ($application['application_type'] == "stall extension") {
        return [
            'status' => true,
        ];
    }

    return [
        'status' => true,
        'application_id' => $pdo->lastInsertId(),
    ];
}

function uploadFiles($uploadedFiles)
{
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
            error_log($message);
        }
    }

    return $file_upload_success;
}
