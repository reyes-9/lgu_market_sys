<?php
require_once '../../includes/config.php';
require_once '../../includes/session.php';

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
    $payment_type = $_POST['payment_type'];

    try {

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
                    // Update expiration date
                    $updateStmt = $pdo->prepare("UPDATE expiration_dates SET expiration_date = :new_expiration_date, status = 'active' WHERE reference_id = :reference_id AND type = :payment_type");
                    $updateStmt->bindParam(':new_expiration_date', $new_expiration_date);
                    $updateStmt->bindParam(':reference_id', $reference_id, PDO::PARAM_INT);
                    $updateStmt->bindParam(':payment_type', $payment_type, PDO::PARAM_STR);
                    $updateStmt->execute();

                    if ($updateStmt->rowCount() === 0) {
                        throw new Exception("No expiration record updated");
                    }
                }

                // Update payment status to 'Paid' in the stalls table
                $updateStallStmt = $pdo->prepare("UPDATE stalls SET payment_status = 'Paid' WHERE id = :stall_id");
                $updateStallStmt->bindParam(':stall_id', $reference_id, PDO::PARAM_INT);
                $updateStallStmt->execute();

                if ($updateStallStmt->rowCount() === 0) {
                    throw new Exception("No stall record updated");
                }
            }

            if ($payment_type === "extension") {
                // Get current expiration
                $stmt = $pdo->prepare("
                  SELECT e.expiration_date,
                         ex.duration                   
                  FROM expiration_dates e
                  JOIN extensions ex ON e.reference_id = ex.id
                  WHERE e.reference_id = :reference_id 
                    AND e.type = :payment_type
                ");
                $stmt->execute([
                    ':reference_id' => $reference_id,
                    ':payment_type' => $payment_type
                ]);
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($row) {
                    // Compute new expiration by adding the requested months
                    $duration = intval($row['duration']);            // e.g. 3 months
                    $newDate = date(
                        'Y-m-d',
                        strtotime("{$row['expiration_date']} +{$duration} month")
                    );

                    // Update expiration_dates
                    $updateExp = $pdo->prepare("
                      UPDATE expiration_dates 
                      SET expiration_date = :newDate, status = 'active' 
                      WHERE reference_id = :reference_id 
                        AND type = :payment_type
                    ");
                    $updateExp->execute([
                        ':newDate'       => $newDate,
                        ':reference_id'  => $reference_id,
                        ':payment_type'  => $payment_type
                    ]);
                    if ($updateExp->rowCount() === 0) {
                        throw new Exception("No extension record updated");
                    }

                    $updateExtension = $pdo->prepare("UPDATE extensions SET payment_status = 'Paid', updated_at = NOW() WHERE id = :reference_id");
                    $updateExtension->execute([':reference_id' => $reference_id]);

                    if ($updateExtension->rowCount() === 0) {
                        throw new Exception("No violation record updated");
                    }
                } else {
                    throw new Exception("No expiration record updated");
                }
            }

            if ($payment_type === "violation") {

                // echo "reference_id: $reference_id, payment_type: $payment_type";

                // Mark violation as inactive

                $updateStmt = $pdo->prepare("UPDATE expiration_dates SET status = 'inactive' WHERE reference_id = :reference_id AND type = :payment_type");
                $updateStmt->bindParam(':reference_id', $reference_id, PDO::PARAM_INT);
                $updateStmt->bindParam(':payment_type', $payment_type, PDO::PARAM_STR);
                $updateStmt->execute();

                // Update violation status to paid
                $updateViolation = $pdo->prepare("UPDATE violations 
                   SET status = 'Resolved', 
                       payment_status = 'Paid',
                       updated_at = NOW()
                   WHERE id = :reference_id");
                $updateViolation->bindParam(':reference_id', $reference_id, PDO::PARAM_INT);
                $updateViolation->execute();

                if ($updateViolation->rowCount() === 0) {
                    throw new Exception("No violation record updated");
                }
            }


            echo json_encode(["success" => true, "message" => "Payment marked as Paid. Changes applied for $payment_type."]);
        } else {
            echo json_encode(["success" => false, "message" => "Payment not found or already marked as Paid."]);
        }
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => "Error: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method."]);
}
