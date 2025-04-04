<?php
require_once '../../includes/config.php';

try {
  $currentDate = date('Y-m-d');

  // 1. Mark as 'payment_period' if within 7 days BEFORE expiration date
  $updatePaymentPeriodQuery = "
        UPDATE expiration_dates
        SET status = 'payment_period'
        WHERE expiration_date BETWEEN :fromDate AND :toDate
          AND status = 'active'
    ";

  $currentDate = date('Y-m-d');
  $threeDaysLater = date('Y-m-d', strtotime($currentDate . ' +7 days'));

  $stmt = $pdo->prepare($updatePaymentPeriodQuery);
  $stmt->bindParam(':fromDate', $currentDate);
  $stmt->bindParam(':toDate', $threeDaysLater);
  $stmt->execute();

  // 2. Mark as 'expired' if expiration date has passed
  $updateExpiredQuery = "
        UPDATE expiration_dates
        SET status = 'expired'
        WHERE expiration_date <= :currentDate
          AND status != 'expired'
    ";
  $stmt = $pdo->prepare($updateExpiredQuery);
  $stmt->bindParam(':currentDate', $currentDate);
  $stmt->execute();

  // 3. Set stalls.payment_status = 'Payment_Period' if status is in payment_period
  $updateStallsPaymentPeriodQuery = "
        UPDATE stalls s
        JOIN expiration_dates ed ON s.id = ed.reference_id
        SET s.payment_status = 'Payment_Period'
        WHERE ed.type = 'stall'
          AND ed.status = 'payment_period'
    ";
  $stmt = $pdo->prepare($updateStallsPaymentPeriodQuery);
  $stmt->execute();

  // 4. Set stalls.payment_status = 'Expired' if status is expired
  $updateStallsExpiredQuery = "
        UPDATE stalls s
        JOIN expiration_dates ed ON s.id = ed.reference_id
        SET s.payment_status = 'Overdue'
        WHERE ed.type = 'stall'
          AND ed.status = 'expired'
    ";
  $stmt = $pdo->prepare($updateStallsExpiredQuery);
  $stmt->execute();

  echo "✅ Expiration dates and stall payment statuses have been updated successfully.";
} catch (PDOException $e) {
  $errorMessage = "❌ Error: " . $e->getMessage() . "\n";
  error_log($errorMessage);
  echo $errorMessage;
}
