<?php

require_once "../../includes/config.php";
require "../../includes/session.php";
require "../actions/get_user_id.php";

// Handle file upload and insert payment into the database
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $account_id = $_SESSION['account_id'];

    try {
        // Check if the stall ID, paid amount, and receipt file are set
        if (!isset($_POST['selected_stall_id']) || !isset($_POST['paid_amount']) || !isset($_POST['source_type']) || !isset($_FILES['receipt_file'])) {
            echo json_encode(['success' => false, 'message' => 'Missing Inputs.']);
            exit();
        }

        $stall_id = $_POST['selected_stall_id'];
        $payment_amount = $_POST['paid_amount'];
        $user_id = getUserIdByAccountId($pdo, $account_id);
        $source_type = $_POST['source_type'];

        $file = $_FILES['receipt_file'];
        $uploadResult = handleFileUpload($file);

        if (!$uploadResult['success']) {
            echo json_encode($uploadResult);
            exit();
        }

        $filePath = $uploadResult['filePath'];

        $stmt = $pdo->prepare("INSERT INTO payments (user_id, stall_id, source_type, amount, payment_date, receipt_path) 
                               VALUES (:user_id, :stall_id, :source_type, :amount, NOW(), :receipt_path)");

        // Bind parameters and execute the statement
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':stall_id', $stall_id, PDO::PARAM_INT);
        $stmt->bindParam(':source_type', $source_type, PDO::PARAM_STR);
        $stmt->bindParam(':amount', $payment_amount, PDO::PARAM_STR);
        $stmt->bindParam(':receipt_path', $filePath, PDO::PARAM_STR);

        $stmt->execute();

        // Send success response
        echo json_encode(['success' => true, 'message' => 'Payment successfully submitted!']);
    } catch (PDOException $e) {
        // Handle database errors
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    } catch (Exception $e) {
        // Handle other errors
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
}


function handleFileUpload($file)
{
    $allowedExtensions = ['pdf', 'jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
    $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);

    // Check for allowed file extensions
    if (!in_array(strtolower($fileExtension), $allowedExtensions)) {
        return ['success' => false, 'message' => 'Invalid file type.'];
    }

    // Define the upload directory
    $uploadDir = '../../uploads/receipts/';
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Generate a unique file name
    $fileName = uniqid('receipt_', true) . '.' . $fileExtension;

    // Move the uploaded file to the server
    if (!move_uploaded_file($file['tmp_name'], $uploadDir . $fileName)) {
        return ['success' => false, 'message' => 'Failed to upload receipt file.'];
    }

    return ['success' => true, 'filePath' => $uploadDir . $fileName];
}
