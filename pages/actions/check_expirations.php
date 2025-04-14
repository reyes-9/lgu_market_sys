<?php
require_once '../../includes/config.php';
require_once '../../includes/session.php';

try {
  $currentDate = date('Y-m-d');

  // Mark as 'payment_period' for the whole month before the expiration date
  $updatePaymentPeriodQuery = "
      UPDATE expiration_dates
      SET status = 'payment_period'
      WHERE expiration_date = :expirationDate
      AND status = 'active'
      AND :currentDate BETWEEN DATE_SUB(expiration_date, INTERVAL 1 MONTH) AND expiration_date
  ";

  $stmt = $pdo->prepare($updatePaymentPeriodQuery);
  $stmt->bindParam(':expirationDate', $expirationDate);  // The expiration date from your database
  $stmt->bindParam(':currentDate', $currentDate);  // Today's date
  $stmt->execute();

  // Mark as 'expired' if expiration date has passed
  $updateExpiredQuery = "
        UPDATE expiration_dates
        SET status = 'expired'
        WHERE expiration_date <= :currentDate
          AND status != 'expired'
    ";
  $stmt = $pdo->prepare($updateExpiredQuery);
  $stmt->bindParam(':currentDate', $currentDate);
  $stmt->execute();

  // Set stalls.payment_status = 'Payment_Period' if status is in payment_period
  $updateStallsPaymentPeriodQuery = "
        UPDATE stalls s
        JOIN expiration_dates ed ON s.id = ed.reference_id
        SET s.payment_status = 'Payment_Period'
        WHERE ed.type = 'stall'
          AND ed.status = 'payment_period'
    ";
  $stmt = $pdo->prepare($updateStallsPaymentPeriodQuery);
  $stmt->execute();

  // Set stalls.payment_status = 'Expired' if status is expired
  $updateStallsExpiredQuery = "
        UPDATE stalls s
        JOIN expiration_dates ed ON s.id = ed.reference_id
        SET s.payment_status = 'Overdue'
        WHERE ed.type = 'stall'
          AND ed.status = 'expired'
    ";
  $stmt = $pdo->prepare($updateStallsExpiredQuery);
  $stmt->execute();

  echo "Expiration dates and stall payment statuses have been updated successfully.";
} catch (PDOException $e) {
  $errorMessage = "Error: " . $e->getMessage() . "\n";
  error_log($errorMessage);
  echo $errorMessage;
}
