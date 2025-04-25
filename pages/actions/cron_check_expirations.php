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

  // Mark expired
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
  $violationResult = updateViolationPaymentStatuses($pdo);

  echo json_encode([
    "success" => $stallResult["success"] && $extensionResult["success"],
    "stall_message" => $stallResult["message"],
    "extension_message" => $extensionResult["message"],
    "violation_message" => $violationResult["message"]
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
    // Update extensions to 'Payment_Period'
    $updateExtensionPaymentPeriodQuery = "
          UPDATE extensions e
          JOIN expiration_dates ed ON e.id = ed.reference_id
          SET e.payment_status = 'Payment_Period'
          WHERE ed.type = 'extension'
            AND ed.status = 'payment_period'
      ";
    $stmt = $pdo->prepare($updateExtensionPaymentPeriodQuery);
    $stmt->execute();

    // Update extensions to 'Overdue'
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
    // Update stalls to 'Payment_Period'
    $updateStallsPaymentPeriodQuery = "
          UPDATE stalls s
          JOIN expiration_dates ed ON s.id = ed.reference_id
          SET s.payment_status = 'Payment_Period'
          WHERE ed.type = 'stall'
            AND ed.status = 'payment_period'
      ";
    $stmt = $pdo->prepare($updateStallsPaymentPeriodQuery);
    $stmt->execute();

    // Update stalls to 'Overdue'
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


// ========== Violation Update ==========
function updateViolationPaymentStatuses(PDO $pdo)
{
  try {
    // Update violation to 'Payment_Period'
    $updateViolationsPaymentPeriodQuery = "
          UPDATE violations v
          JOIN expiration_dates ed ON v.id = ed.reference_id
          SET v.payment_status = 'Payment_Period'
          WHERE ed.type = 'violation'
            AND ed.status = 'payment_period'
      ";
    $stmt = $pdo->prepare($updateViolationsPaymentPeriodQuery);
    $stmt->execute();

    $getViolationStatusQuery = "
    SELECT 
      v.id AS violation_id, 
      vt.escalation_status, 
      v.stall_id,
      v.suspension_end
    FROM violations v
    JOIN expiration_dates ed ON v.id = ed.reference_id
    JOIN violation_types vt ON v.violation_type_id = vt.id
    WHERE ed.type = 'violation'
      AND ed.status = 'expired'
  ";

    $stmt = $pdo->prepare($getViolationStatusQuery);
    $stmt->execute();
    $violations = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Group violations by stall_id
    $stallViolations = [];
    foreach ($violations as $violation) {
      $stallId = $violation['stall_id'];
      $stallViolations[$stallId][] = $violation;
    }

    // Process each stall
    foreach ($stallViolations as $stallId => $violationGroup) {
      $hasTerminated = false;
      $hasSuspended = false;

      foreach ($violationGroup as $violation) {
        if ($violation['escalation_status'] === 'Terminated') {
          $hasTerminated = true;
          break; // No need to check further
        } elseif ($violation['escalation_status'] === 'Suspended') {
          $hasSuspended = true;
        }
      }

      foreach ($violationGroup as $violation) {
        $violationId = $violation['violation_id'];

        if ($hasTerminated) {
          // Escalate all related violations (without suspension fields)
          $updateViolationQuery = "
                  UPDATE violations
                  SET payment_status = 'Overdue',
                      status = 'Escalated',
                      updated_at = NOW()
                  WHERE id = :violationId
              ";
          $stmt = $pdo->prepare($updateViolationQuery);
          $stmt->execute(['violationId' => $violationId]);

          $updateBothQuery = "
          UPDATE users u
          JOIN stalls s ON s.user_id = u.id
          SET u.status = 'terminated',
              u.updated_at = NOW(),
              s.status = 'terminated'
          WHERE s.id = :stallId
        ";
          $stmt = $pdo->prepare($updateBothQuery);
          $stmt->execute(['stallId' => $stallId]);


          break; // Already handled the highest priority
        } elseif ($hasSuspended) {

          $current_date = date('Y-m-d');

          // Escalate violations with suspension
          if ($violation['escalation_status'] === 'Suspended') {
            $suspension_end = new DateTime($violation['suspension_end']); // convert to DateTime object

            $updateViolationQuery = "
                      UPDATE violations v
                      JOIN users u ON v.user_id = u.id
                      SET v.payment_status = 'Overdue',
                          v.status = 'Escalated',
                          v.updated_at = NOW(),
                          v.suspension_started = NOW(),
                          v.suspension_end = DATE_ADD(NOW(), INTERVAL 1 MONTH),
                          u.status = 'suspended'
                      WHERE v.id = :violationId
                  ";
            $stmt = $pdo->prepare($updateViolationQuery);
            $stmt->execute(['violationId' => $violationId]);

            // Suspend the stall
            $updateStallQuery = "
                      UPDATE stalls
                      SET status = 'suspended'
                      WHERE id = :stallId
                  ";
            $stmt = $pdo->prepare($updateStallQuery);
            $stmt->execute(['stallId' => $stallId]);

            // Escalate the suspension if it is goes over the suspension end date
            if ($suspension_end <= $current_date) {
              echo "The Suspension Already Ends: " . $suspension_end->format('Y-m-d');
              // Escalate 'suspension' to 'terminated'
              $updateViolationQuery = "
                        UPDATE violations
                        SET payment_status = 'Overdue',
                            status = 'Escalated',
                            updated_at = NOW()
                        WHERE id = :violationId
                    ";
              $stmt = $pdo->prepare($updateViolationQuery);
              $stmt->execute(['violationId' => $violationId]);

              $updateBothQuery = "
                UPDATE users u
                JOIN stalls s ON s.user_id = u.id
                SET u.status = 'terminated',
                    u.updated_at = NOW(),
                    s.status = 'terminated'
                WHERE s.id = :stallId
              ";
              $stmt = $pdo->prepare($updateBothQuery);
              $stmt->execute(['stallId' => $stallId]);
            } else {
              echo "Still suspended.";
            }
          }
        }
      }
    }


    return [
      "success" => true,
      "message" => "Violation payment statuses updated successfully."
    ];
  } catch (PDOException $e) {

    return [
      "success" => false,
      "message" => "Failed to update violation payment statuses: " . $e->getMessage()
    ];
  }
}
