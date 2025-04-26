<?php

require_once '../../includes/config.php';
require_once '../../includes/session.php';

// get the id from hte users table and check if the status is suspended or terminated if it is suspended get the violations that equals to the user id and check if the payment_status is paid and status is resolved. if is paid and resolved, check the suspension_end. 
// if the suspension_end date is less than or equals today it means the suspension is over. update the violation table columns suspension_started and suspension_end to null. 
// also update the users table column status to active. and stalls table column table to active.
// if the status is terminated, update the users table column status to terminated and stalls table column table to terminated.

manageSuspensions($pdo);

function manageSuspensions($pdo)
{
    // Step 1: Get user IDs with status 'suspended' or 'terminated'
    $stmt = $pdo->prepare("
        SELECT id, status
        FROM users
        WHERE status IN ('suspended', 'terminated')
    ");
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Step 2: Loop through each user and check for violations
    foreach ($users as $user) {
        if ($user['status'] == 'suspended') {
            // Step 2a: Get violations related to the user with 'paid' payment_status and 'resolved' status
            $violationStmt = $pdo->prepare("
                SELECT v.id, v.suspension_end
                FROM violations v
                WHERE v.user_id = :user_id
                  AND v.payment_status = 'Paid'
                  AND v.status = 'Resolved'
            ");
            $violationStmt->bindParam(':user_id', $user['id'], PDO::PARAM_INT);
            $violationStmt->execute();
            $violations = $violationStmt->fetchAll(PDO::FETCH_ASSOC);

            // Step 2b: Check each violation for suspension_end date
            foreach ($violations as $violation) {
                $suspension_end_date = $violation['suspension_end'];
                $dateObj = new DateTime($suspension_end_date);
                $suspension_end = $dateObj->format('d-m-Y');
                $current_date = date('d-m-Y');

                echo "<pre>";
                echo "Suspension end date: " . $suspension_end . "<br>";
                echo "Current date: " . $current_date . "<br>";
                echo "User ID: " . $user['id'] . "<br>";
                echo "Violation ID: " . $violation['id'] . "<br>";
                echo "<pre>";

                // Check if the suspension_end date is today or in the past
                if ($suspension_end <= $current_date) {

                    // Step 3: Update the violation table (set suspension_started and suspension_end to NULL)
                    $updateViolationStmt = $pdo->prepare("
                        UPDATE violations
                        SET suspension_started = NULL,
                            suspension_end = NULL,
                            updated_at = NOW()
                        WHERE id = :violation_id
                    ");
                    $updateViolationStmt->bindParam(':violation_id', $violation['id'], PDO::PARAM_INT);
                    $updateViolationStmt->execute();

                    // Step 4: Update the users table (set status to 'active')
                    $updateUserStmt = $pdo->prepare("
                                UPDATE users
                                SET status = 'active'
                                WHERE id = :user_id
                            ");
                    $updateUserStmt->bindParam(':user_id', $user['id'], PDO::PARAM_INT);
                    $updateUserStmt->execute();

                    // Step 5: Update the stalls table (set status to 'active')
                    $updateStallsStmt = $pdo->prepare("
                                UPDATE stalls
                                SET status = 'occupied'
                                WHERE user_id = :user_id
                            ");
                    $updateStallsStmt->bindParam(':user_id', $user['id'], PDO::PARAM_INT);
                    $updateStallsStmt->execute();
                }
            }
        }
        if ($user['status'] == 'terminated') {
            // Step 2a: Get violations related to the user with 'paid' payment_status and 'resolved' status
            $violationStmt = $pdo->prepare("
                SELECT v.id, v.suspension_end
                FROM violations v
                WHERE v.user_id = :user_id
                  AND v.payment_status = 'Paid'
                  AND v.status = 'Resolved'
            ");
            $violationStmt->bindParam(':user_id', $user['id'], PDO::PARAM_INT);
            $violationStmt->execute();
            $violations = $violationStmt->fetchAll(PDO::FETCH_ASSOC);

            // Step 2b: Check each violation for suspension_end date
            foreach ($violations as $violation) {
                $suspension_end_date = $violation['suspension_end'];
                $dateObj = new DateTime($suspension_end_date);
                $suspension_end = $dateObj->format('d-m-Y');
                $current_date = date('d-m-Y');

                // Check if the suspension_end date is today or in the past
                if ($suspension_end <= $current_date) {

                    // Step 3: Update the violation table (set suspension_started and suspension_end to NULL)
                    $updateViolationStmt = $pdo->prepare("
                        UPDATE violations
                        SET suspension_started = NULL,
                            suspension_end = NULL,
                            updated_at = NOW()
                        WHERE id = :violation_id
                    ");
                    $updateViolationStmt->bindParam(':violation_id', $violation['id'], PDO::PARAM_INT);
                    $updateViolationStmt->execute();
                }
            }
        }
    }

    echo "Suspension management completed.";
}
