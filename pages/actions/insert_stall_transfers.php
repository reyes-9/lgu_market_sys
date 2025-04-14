<?php
require_once '../../includes/session.php';

function insertStallTransfer(
    $pdo,
    $ownerId,
    $applicationId,
    $transferType,
    $transferReason,
    $recipient_id
) {
    // Determine which column to use based on application type
    $currentOwnerId = null;
    $deceasedOwnerId = null;

    if ($transferType === "Transfer") {
        $currentOwnerId = $ownerId;
    } elseif ($transferType === "Succession") {
        $deceasedOwnerId = $ownerId;
    }

    $sql = "INSERT INTO stall_transfers (
                current_owner_id, deceased_owner_id, application_id, transfer_type, transfer_reason, recipient_id, created_at 
            ) 
            VALUES (
                :current_owner_id, :deceased_owner_id, :application_id, :transfer_type, :transfer_reason, 
                :recipient_id, NOW())
            ";

    $stmt = $pdo->prepare($sql);

    // Bind the appropriate owner ID based on transfer type
    $stmt->bindParam(':current_owner_id', $currentOwnerId, PDO::PARAM_INT);
    $stmt->bindParam(':deceased_owner_id', $deceasedOwnerId, PDO::PARAM_INT);
    $stmt->bindParam(':application_id', $applicationId, PDO::PARAM_INT);
    $stmt->bindParam(':transfer_type', $transferType, PDO::PARAM_STR);
    $stmt->bindParam(':transfer_reason', $transferReason, PDO::PARAM_STR);
    $stmt->bindParam(':recipient_id', $recipient_id, PDO::PARAM_INT);



    if ($stmt->execute()) {
        return ["success" => true, "message" => "Stall transfer recorded successfully."];
    } else {
        return ["success" => false, "error" => "Failed to insert stall transfer record."];
    }
}
