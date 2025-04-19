<?php
require_once '../../includes/config.php';
require_once '../../includes/session.php';

try {
  $currentDate = date('Y-m-d');

  $updatePaymentPeriodQuery = "
          UPDATE expiration_dates
          SET status = 'payment_period'
          WHERE status = 'active'
            AND :currentDate BETWEEN DATE_SUB(expiration_date, INTERVAL 1 MONTH) AND expiration_date
      ";
  $stmt = $pdo->prepare($updatePaymentPeriodQuery);
  $stmt->bindParam(':currentDate', $currentDate);
  $stmt->execute();

  // Step 2: Mark expired
  $updateExpiredQuery = "
          UPDATE expiration_dates
          SET status = 'expired'
          WHERE expiration_date <= :currentDate
            AND status != 'expired'
      ";
  $stmt = $pdo->prepare($updateExpiredQuery);
  $stmt->bindParam(':currentDate', $currentDate);
  $stmt->execute();

  $stallResult = updateStallPaymentStatuses($pdo);
  $extensionResult = updateExtensionsPaymentStatuses($pdo);

  echo json_encode([
    "success" => $stallResult["success"] && $extensionResult["success"],
    "stall_message" => $stallResult["message"],
    "extension_message" => $extensionResult["message"]
  ]);
} catch (PDOException $e) {

  $errorMessage = "Error: " . $e->getMessage() . "\n";
  error_log($errorMessage);
  echo $errorMessage;
}


// ========== Extension Update ==========
function updateExtensionsPaymentStatuses(PDO $pdo)
{
  try {
    // Update stalls to 'Payment_Period'
    $updateExtensionPaymentPeriodQuery = "
          UPDATE extensions e
          JOIN expiration_dates ed ON e.id = ed.reference_id
          SET e.payment_status = 'Payment_Period'
          WHERE ed.type = 'extension'
            AND ed.status = 'payment_period'
      ";
    $stmt = $pdo->prepare($updateExtensionPaymentPeriodQuery);
    $stmt->execute();

    // Update stalls to 'Overdue'
    $updateExtensionExpiredQuery = "
          UPDATE extensions e
          JOIN expiration_dates ed ON e.id = ed.reference_id
          SET e.payment_status = 'Overdue'
          WHERE ed.type = 'extension'
            AND ed.status = 'expired'
      ";
    $stmt = $pdo->prepare($updateExtensionExpiredQuery);
    $stmt->execute();

    return [
      "success" => true,
      "message" => "Stall Extension payment statuses updated successfully."
    ];
  } catch (PDOException $e) {

    return [
      "success" => false,
      "message" => "Failed to update stall extension payment statuses: " . $e->getMessage()
    ];
  }
}

// ========== Stall Update ==========
function updateStallPaymentStatuses(PDO $pdo)
{
  try {
    // Step 3: Update stalls to 'Payment_Period'
    $updateStallsPaymentPeriodQuery = "
          UPDATE stalls s
          JOIN expiration_dates ed ON s.id = ed.reference_id
          SET s.payment_status = 'Payment_Period'
          WHERE ed.type = 'stall'
            AND ed.status = 'payment_period'
      ";
    $stmt = $pdo->prepare($updateStallsPaymentPeriodQuery);
    $stmt->execute();

    // Step 4: Update stalls to 'Overdue'
    $updateStallsExpiredQuery = "
          UPDATE stalls s
          JOIN expiration_dates ed ON s.id = ed.reference_id
          SET s.payment_status = 'Overdue'
          WHERE ed.type = 'stall'
            AND ed.status = 'expired'
      ";
    $stmt = $pdo->prepare($updateStallsExpiredQuery);
    $stmt->execute();

    return [
      "success" => true,
      "message" => "Stall payment statuses updated successfully."
    ];
  } catch (PDOException $e) {

    return [
      "success" => false,
      "message" => "Failed to update stall payment statuses: " . $e->getMessage()
    ];
  }
}
