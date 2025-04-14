<?php
require_once '../../includes/config.php';

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (
        !isset($_POST['payment_id']) || empty($_POST['payment_id']) ||
        !isset($_POST['reference_id']) || empty($_POST['reference_id']) ||
        !isset($_POST['payment_type']) || empty($_POST['payment_type'])
    ) {
        echo json_encode(["success" => false, "message" => "Invalid payment data."]);
        exit;
    }

    $payment_id = intval($_POST['payment_id']);
    $reference_id = intval($_POST['reference_id']); // ID of stall, stall_extension, or violation
    $payment_type = $_POST['payment_type']; // Can be 'stall', 'stall_extension', or 'violation'

    try {
        // Begin transaction
        $pdo->beginTransaction();

        // Update payment status to 'Paid' in the payments table
        $query = $pdo->prepare("UPDATE payments SET payment_status = 'Paid' WHERE id = :payment_id");
        $query->bindParam(':payment_id', $payment_id, PDO::PARAM_INT);
        $query->execute();

        if ($query->rowCount() > 0) {
            if ($payment_type === "stall" || $payment_type === "helper") {
                // Find expiration date
                $stmt = $pdo->prepare("SELECT expiration_date FROM expiration_dates WHERE reference_id = :reference_id AND type = :payment_type");
                $stmt->bindParam(':reference_id', $reference_id, PDO::PARAM_INT);
                $stmt->bindParam(':payment_type', $payment_type, PDO::PARAM_STR);
                $stmt->execute();
                $expiration = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($expiration) {
                    // Extend expiration date by one month
                    $new_expiration_date = date('Y-m-d', strtotime($expiration['expiration_date'] . ' +1 month'));
                    $status = "active";
                    // Update expiration date
                    $updateStmt = $pdo->prepare("UPDATE expiration_dates SET expiration_date = :new_expiration_date, status = :status WHERE reference_id = :reference_id AND type = :payment_type");
                    $updateStmt->bindParam(':new_expiration_date', $new_expiration_date);
                    $updateStmt->bindParam(':reference_id', $reference_id, PDO::PARAM_INT);
                    $updateStmt->bindParam(':payment_type', $payment_type, PDO::PARAM_STR);
                    $updateStmt->bindParam(':status', $status, PDO::PARAM_STR);
                    $updateStmt->execute();
                }
            } elseif ($payment_type === "stall_extension" || $payment_type === "violation") {
                // Mark stall extension or violation as inactive
                $updateStmt = $pdo->prepare("UPDATE expiration_dates SET status = 'Inactive' WHERE reference_id = :reference_id AND type = :payment_type");
                $updateStmt->bindParam(':reference_id', $reference_id, PDO::PARAM_INT);
                $updateStmt->bindParam(':payment_type', $payment_type, PDO::PARAM_STR);
                $updateStmt->execute();
            }

            // Update payment status to 'Paid' in the stalls table
            $updateStallStmt = $pdo->prepare("UPDATE stalls SET payment_status = 'Paid' WHERE id = :stall_id");
            $updateStallStmt->bindParam(':stall_id', $reference_id, PDO::PARAM_INT);
            $updateStallStmt->execute();

            // Commit transaction
            $pdo->commit();
            echo json_encode(["success" => true, "message" => "Payment marked as Paid. Changes applied for $payment_type."]);
        } else {
            $pdo->rollBack();
            echo json_encode(["success" => false, "message" => "Payment not found or already marked as Paid."]);
        }
    } catch (Exception $e) {
        $pdo->rollBack();
        echo json_encode(["success" => false, "message" => "Error: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method."]);
}
