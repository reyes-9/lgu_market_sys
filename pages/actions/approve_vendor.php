<?php
require_once '../../includes/config.php';
require_once '../../includes/session.php';
include_once "notifications.php";
error_reporting(E_ERROR);  // Disable warnings and notices, only show errors

header('Content-Type: application/json');

if (!isset($_POST['account_id'])) {
    echo json_encode(['success' => false, 'message' => 'Missing account ID.']);
    exit;
}

$account_id = $_POST['account_id'];
$application_id = $_POST['application_id'];

try {
    // Start the transaction to ensure both queries are executed successfully
    $pdo->beginTransaction();

    // Step 1: Update the vendor application status to "Approved" and set the status_date
    $stmt = $pdo->prepare("UPDATE vendors_application 
                            SET application_status = 'Approved', status_date = NOW() 
                            WHERE account_id = :account_id AND id = :application_id");

    $stmt->bindParam(':account_id', $account_id, PDO::PARAM_INT);
    $stmt->bindParam(':application_id', $application_id, PDO::PARAM_INT);
    $stmt->execute();

    // Check if the update was successful
    if ($stmt->rowCount() > 0) {
        // Step 2: Copy the approved vendor's data to the users table
        $stmt = $pdo->prepare("SELECT first_name, middle_name, last_name, email, alt_email, contact_no, sex, civil_status, nationality, address 
                               FROM vendors_application 
                               WHERE account_id = :account_id AND id = :application_id");
        $stmt->bindParam(':account_id', $account_id, PDO::PARAM_INT);
        $stmt->bindParam(':application_id', $application_id, PDO::PARAM_INT);
        $stmt->execute();

        $vendor = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($vendor) {
            // Insert the vendor data into the users table with user_type = 'vendor'
            $insertStmt = $pdo->prepare("INSERT INTO users 
                                         (account_id, first_name, middle_name, last_name, email, alt_email, contact_no, sex, civil_status, nationality, address, user_type)
                                         VALUES 
                                         (:account_id, :first_name, :middle_name, :last_name, :email, :alt_email, :contact_no, :sex, :civil_status, :nationality, :address, 'vendor')");

            $insertStmt->bindParam(':account_id', $account_id, PDO::PARAM_INT);
            $insertStmt->bindParam(':first_name', $vendor['first_name'], PDO::PARAM_STR);
            $insertStmt->bindParam(':middle_name', $vendor['middle_name'], PDO::PARAM_STR);
            $insertStmt->bindParam(':last_name', $vendor['last_name'], PDO::PARAM_STR);
            $insertStmt->bindParam(':email', $vendor['email'], PDO::PARAM_STR);
            $insertStmt->bindParam(':alt_email', $vendor['alt_email'], PDO::PARAM_STR);
            $insertStmt->bindParam(':contact_no', $vendor['contact_no'], PDO::PARAM_STR);
            $insertStmt->bindParam(':sex', $vendor['sex'], PDO::PARAM_STR);
            $insertStmt->bindParam(':civil_status', $vendor['civil_status'], PDO::PARAM_STR);
            $insertStmt->bindParam(':nationality', $vendor['nationality'], PDO::PARAM_STR);
            $insertStmt->bindParam(':address', $vendor['address'], PDO::PARAM_STR);

            $insertStmt->execute();

            // Commit the transaction
            $pdo->commit();

            $type = 'Vendor Application';
            $message = sprintf('Your %s has been successfully approved. Your Application Form Number is: %s.', $type, $application_number);
            insertNotification($pdo, $account_id, $type, $message, 'unread');
            echo json_encode(['success' => true, 'message' => 'Vendor approved and data copied to users table.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Vendor data not found.']);
            $pdo->rollBack(); // Rollback if vendor data was not found
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Vendor not found or already approved.']);
    }
} catch (PDOException $e) {
    $pdo->rollBack(); // Rollback in case of error
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
