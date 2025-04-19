<?php

require_once "../../includes/config.php";
require "../../includes/session.php";
require "../actions/get_user_id.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $account_id = $_SESSION['account_id'];

    $user_id = getUserIdByAccountId($pdo, $account_id);
    $source_type = $_POST['source_type'];
    $extension_id = null;
    $stall_id = $_POST['selected_stall_id'];

    try {
        switch ($source_type) {
            case 'stall':
                $paid_amount = $_POST['stall_paid_amount'];
                $file = $_FILES['stall_receipt_file'];
                break;
            case 'extension':
                $extension_id = $_POST['selected_extension_id'];
                $paid_amount = $_POST['extension_paid_amount'];
                $file = $_FILES['extension_receipt_file'];
                break;
        }

        $result = insertPayment($pdo, $user_id, $stall_id, $extension_id, $source_type, $paid_amount, $file);

        if ($result['success'] === false) {
            error_log($result['message']);
            throw new Exception('Failed to insert payment record.');
            exit;
        }

        echo json_encode(['success' => true, 'message' => 'Payment successfully submitted and status updated to Pending!']);
    } catch (PDOException $e) {

        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    } catch (Exception $e) {

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

function insertPayment($pdo, $user_id, $stall_id, $extension_id, $source_type, $payment_amount, $file)
{
    $uploadResult = handleFileUpload($file);

    if (!$uploadResult['success']) {
        echo json_encode($uploadResult);
        exit();
    }

    $filePath = $uploadResult['filePath'];

    try {

        // Insert payment record

        switch ($source_type) {
            case 'stall':
                $stmt = $pdo->prepare("
                INSERT INTO payments (user_id, stall_id, source_type, amount, payment_date, receipt_path) 
                VALUES (:user_id, :stall_id, :source_type, :amount, NOW(), :receipt_path)
            ");
                $stmt->execute([
                    ':user_id'      => $user_id,
                    ':stall_id'     => $stall_id,
                    ':source_type'  => $source_type,
                    ':amount'       => $payment_amount,
                    ':receipt_path' => $filePath
                ]);
                break;

            case 'extension':
                $stmt = $pdo->prepare("
                INSERT INTO payments (user_id, stall_id, extension_id, source_type, amount, payment_date, receipt_path) 
                VALUES (:user_id, :stall_id, :extension_id, :source_type, :amount, NOW(), :receipt_path)
            ");
                $stmt->execute([
                    ':user_id'      => $user_id,
                    ':stall_id'     => $stall_id,
                    ':extension_id' => $extension_id,
                    ':source_type'  => $source_type,
                    ':amount'       => $payment_amount,
                    ':receipt_path' => $filePath
                ]);
                break;
        }


        // Update stall payment status
        $updateStmt = $pdo->prepare("UPDATE stalls SET payment_status = 'Pending' WHERE id = :stall_id");
        $updateStmt->execute([':stall_id' => $stall_id]);

        return [
            'success' => true,
        ];
    } catch (PDOException $e) {
        $pdo->rollBack();
        return [
            'success' => false,
            'message' => 'Database error: ' . $e->getMessage()
        ];
    }
}
