<?php
require_once "../../includes/config.php";

session_start();
ob_start();

$response = ['success' => false, 'messages' => []];
$errors = []; // Array to store detailed error messages

// Check for CSRF token
if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    $response['messages'][] = "Invalid request. Please try again.";
    ob_clean();
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}

// Check request method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['messages'][] = "Invalid request.";
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}

var_dump($_POST);

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

echo $account_id;
echo $market_id;
echo $stall_id;
echo $section_id;
echo $application_type;

if ($account_id === false || $market_id === false || $stall_id === false || $section_id === false || empty($application_type)) {
    $response['messages'][] = "Invalid input. Please check your data.";
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}
// Access the uploaded files
$uploadedFiles = $_FILES['documents'];

// Access the file names from hidden inputs
$transferNames = isset($_POST['transfer_names']) ? $_POST['transfer_names'] : '';
$successionNames = isset($_POST['succession_names']) ? $_POST['succession_names'] : '';
$qcIdNames = isset($_POST['qc_id_names']) ? $_POST['qc_id_names'] : '';
$currentIdNames = isset($_POST['current_id_names']) ? $_POST['current_id_names'] : '';

// Prepare to upload files
$file_upload_success = true;
$file_names = []; // Store names of uploaded files
$file_types = []; // Store types of uploaded documents

// Process uploaded files
for ($i = 0; $i < count($uploadedFiles['name']); $i++) {
    if ($uploadedFiles['error'][$i] == UPLOAD_ERR_OK) {
        // Get the file name and temporary path
        $fileName = $uploadedFiles['name'][$i];
        $fileTmpPath = $uploadedFiles['tmp_name'][$i];

        // Define where to move the uploaded file
        $destinationPath = 'uploads/' . $fileName; // Ensure 'uploads' directory exists

        // Move the file to the desired location
        if (move_uploaded_file($fileTmpPath, $destinationPath)) {
            $file_names[] = $fileName; // Keep track of successfully uploaded files
        } else {
            $file_upload_success = false;
            $errors[] = "Error moving file: " . $fileName;
        }
    } else {
        $file_upload_success = false;
        $errors[] = "Error uploading file: " . $uploadedFiles['name'][$i];
    }
}

// Log detailed errors (if any)
if (!empty($errors)) {

    foreach ($errors as $error) {
        error_log($error);
    } 

    // $response['messages'][] = "Some files failed to upload. Please check your uploads.";
}

if ($file_upload_success) {

    $sql_application = "INSERT INTO applications (account_id, stall_id, section_id, market_id, application_type) VALUES (:account_id, :stall_id, :section_id, :market_id, :application_type)";
    $stmt_application = $pdo->prepare($sql_application);
    $stmt_application->bindParam(':account_id', $account_id);
    $stmt_application->bindParam(':stall_id', $stall_id);
    $stmt_application->bindParam(':section_id', $section_id);
    $stmt_application->bindParam(':market_id', $market_id);
    $stmt_application->bindParam(':application_type', $application_type);

    if (!$stmt_application->execute()) {
        $response['messages'][] = "Failed to create application. Please try again.";
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }

    $application_id = $pdo->lastInsertId();

    $sql_document = "INSERT INTO documents (application_id, document_type, document_name, document_path) VALUES (:application_id, :document_type, :document_name, :document_path)";
    $stmt_document = $pdo->prepare($sql_document);
    $stmt_document->bindParam(':application_id', $application_id);
    $stmt_document->bindParam(':document_type', $document_type);
    $stmt_document->bindParam(':document_name', $file_name);
    $stmt_document->bindParam(':document_path', $target_file);

    foreach ($file_names as $index => $file_name) {
        $target_file = $upload_dir . $file_name; // Reconstruct the target path
        $document_type = $file_types[$index]; // Get the corresponding document type
        $stmt_document->execute();
    }

    $response['success'] = true;
    $response['messages'][] = 'Application and documents submitted successfully!';
} else {
    $response['messages'][] = "Some files failed to upload. Please check your uploads.";
}

header('Content-Type: application/json');
echo json_encode($response);
