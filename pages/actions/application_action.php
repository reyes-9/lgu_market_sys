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
$selected_stall_id = filter_input(INPUT_POST, 'selected_stall_id', FILTER_VALIDATE_INT);
$duration = filter_input(INPUT_POST, 'duration', FILTER_VALIDATE_INT);
$duration = $duration !== false ? $duration : null;
$first_name = isset($_POST['first_name']) ? htmlspecialchars(trim($_POST['first_name']), ENT_QUOTES, 'UTF-8') : '';
$last_name = isset($_POST['last_name']) ? htmlspecialchars(trim($_POST['last_name']), ENT_QUOTES, 'UTF-8') : '';
$valid_id_type = $_POST['valid_id_type'] ?? null;
$helper_id = '';

if ($account_id === false || $market_id === false || $stall_id === false || $section_id === false || empty($application_type)) {
    $response['messages'][] = "Invalid input. Please check your data.";
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}

// Prepare application data
$application = [
    'account_id' => $account_id,
    'stall_id' => $stall_id,
    'section_id' => $section_id,
    'market_id' => $market_id,
    'application_type' => $application_type,
    'helper_id' => $helper_id,
    'ext_duration' => $duration
];

print_r($_POST);

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
            'helper_id' => $helper_id,
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
if ($application_type == "add helper") {

    print_r($_POST);

    $isFileUploaded = false;
    $uploadedFile = $_FILES['document'];

    if ($uploadedFile['error'] === UPLOAD_ERR_OK) {
        $uploadDirectory = '../../uploads/';

        if (!is_dir($uploadDirectory)) {
            mkdir($uploadDirectory, 0777, true); // Create the directory if it doesn't exist
        }

        $file_name = basename($uploadedFile['name']); // Sanitize file name
        $file_path = $uploadDirectory . $file_name; // Destination path

        // Attempt to move the uploaded file to the destination directory
        if (move_uploaded_file($uploadedFile['tmp_name'], $file_path)) {
            $isFileUploaded = true;
        } else {
            $response['success'] = false;
            $response['messages'][] = "Failed to move uploaded file: " . htmlspecialchars($file_name);
        }
    } else {
        $response['success'] = false;
        $response['messages'][] = "Error uploading file: " . htmlspecialchars($uploadedFile['name']) . " (Error code: " . $uploadedFile['error'] . ")";
    }

    // Insert helper data into the helper table
    $status = "Pending";
    $sql_insert_helper = "INSERT INTO helper (stall_id, first_name, last_name, status) VALUES (:stall_id, :first_name, :last_name, :status)";
    $stmt_application = $pdo->prepare($sql_insert_helper);
    $stmt_application->bindParam(':stall_id', $selected_stall_id);
    $stmt_application->bindParam(':first_name', $first_name);
    $stmt_application->bindParam(':last_name', $last_name);
    $stmt_application->bindParam(':status', $status);

    if (!$stmt_application->execute()) {
        $response['success'] = false;
        $response['messages'][] = "Failed to create application. Please try again.";
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }


    $helper_id = $pdo->lastInsertId();

    // Get the market and stall info 
    $sql_find_stall = "SELECT id, market_id, section_id, account_id FROM stalls WHERE id = :stall_id";
    $stmt_application = $pdo->prepare($sql_find_stall);
    $stmt_application->bindParam(':stall_id', $selected_stall_id);
    $stmt_application->execute();
    $result = $stmt_application->fetch(PDO::FETCH_ASSOC);

    // Set the application array 
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
            'helper_id' => $helper_id,
            'ext_duration' => $duration
        ];
    }

    // Insert the application details and the document details
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

        // Prepare document details for database insertion
        $document_type = $valid_id_type; // Get the document type for single file

        if ($document_type === null) {
            $response['success'] = false;
            $response['messages'][] = "Invalid document type for file: " . htmlspecialchars($file_name);
            header('Content-Type: application/json');
            echo json_encode($response);
            exit();
        }

        $document = [
            'application_id' => $application_id,
            'document_type' => $document_type,
            'file_name' => $file_name,
            'file_path' => $file_path,
        ];

        // Insert document details into the database
        if (!submitDocuments($document, $pdo)) {
            $response['success'] = false;
            $response['messages'][] = "Failed to save document details to the database: " . htmlspecialchars($file_name);
        }


        $response['success'] = true;
        $response['messages'][] = "Application Submitted.";
    } else {
        $response['messages'][] = "Application Failed.";
    }

    ob_end_clean();
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}

$uploadedFiles = $_FILES['documents'];

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
    if ($uploadedFiles['error'] == UPLOAD_ERR_OK) {
        $document_type = assignDocumentType($application_type, 0); // Get the document type for the single file
        $file_name = basename($uploadedFiles['name']); // Sanitize the file name
        $file_path = '../../uploads/' . $file_name; // Destination path

        // Check if the document type is valid
        if ($document_type === 'Unknown Document Type') {
            error_log("Invalid document type for file: " . $file_name); // Log the issue
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => "Failed to upload document."]);
            exit();
        }

        // Attempt to move the uploaded file to the destination directory
        if (move_uploaded_file($uploadedFiles['tmp_name'], $file_path)) {
            // Prepare document details for database insertion
            $document = [
                'application_id' => $application_id,
                'document_type' => $document_type,
                'file_name' => $file_name,
                'file_path' => $file_path,
            ];

            // Insert document details into the database
            if (!submitDocuments($document, $pdo)) {
                error_log("Failed to save document details to the database: " . $file_name); // Log the database error
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => "Failed to upload document."]);
                exit();
            } else {
                header('Content-Type: application/json');
                echo json_encode(['success' => true, 'message' => "Document uploaded successfully."]);
                exit();
            }
        } else {
            error_log("Failed to move uploaded file: " . $file_name); // Log the file system error
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => "Failed to upload document."]);
            exit();
        }
    } else {
        error_log("Error uploading file: " . $uploadedFiles['name'] . " (Error code: " . $uploadedFiles['error'] . ")"); // Log the error code
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => "Failed to upload document."]);
        exit();
    }


    $response['success'] = true;
    $response['messages'][] = "Application Submitted.";
} else {
    $response['messages'][] = "Application Failed.";
}

ob_end_clean();
header('Content-Type: application/json');
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
    $sql_application = "INSERT INTO applications (account_id, stall_id, section_id, market_id, application_type, helper_id, ext_duration) VALUES (:account_id, :stall_id, :section_id, :market_id, :application_type, :helper_id, :ext_duration)";
    $stmt_application = $pdo->prepare($sql_application);
    $stmt_application->bindParam(':account_id', $application['account_id']);
    $stmt_application->bindParam(':stall_id', $application['stall_id']);
    $stmt_application->bindParam(':section_id', $application['section_id']);
    $stmt_application->bindParam(':market_id', $application['market_id']);
    $stmt_application->bindParam(':application_type', $application['application_type']);
    $stmt_application->bindParam(':helper_id', $application['helper_id']);
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
