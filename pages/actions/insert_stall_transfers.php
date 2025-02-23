<?php
function insertStallTransfer(
    $pdo,
    $ownerId,
    $applicationId,
    $transferType,
    $transferReason,
    $recipientName,
    $recipientContact,
    $recipientEmail,
    $recipientAltEmail,
    $recipientSex,
    $recipientCivilStatus,
    $recipientNationality,
    $recipientAddress
) {
    // Determine which column to use based on application type
    $currentOwnerId = null;
    $deceasedOwnerId = null;

    if ($transferType === "Transfer") {
        $currentOwnerId = $ownerId;  // Assign to current_owner_id
    } elseif ($transferType === "Succession") {
        $deceasedOwnerId = $ownerId; // Assign to deceased_owner_id
    }

    $sql = "INSERT INTO stall_transfers (
                current_owner_id, deceased_owner_id, application_id, transfer_type, transfer_reason, 
                recipient_name, recipient_contact, recipient_email, recipient_alt_email, 
                recipient_sex, recipient_civil_status, recipient_nationality, recipient_address, created_at
            ) 
            VALUES (
                :current_owner_id, :deceased_owner_id, :application_id, :transfer_type, :transfer_reason, 
                :recipient_name, :recipient_contact, :recipient_email, :recipient_alt_email, 
                :recipient_sex, :recipient_civil_status, :recipient_nationality, :recipient_address, NOW())
            ";

    $stmt = $pdo->prepare($sql);

    // Bind the appropriate owner ID based on transfer type
    $stmt->bindParam(':current_owner_id', $currentOwnerId, PDO::PARAM_INT);
    $stmt->bindParam(':deceased_owner_id', $deceasedOwnerId, PDO::PARAM_INT);
    $stmt->bindParam(':application_id', $applicationId, PDO::PARAM_INT);
    $stmt->bindParam(':transfer_type', $transferType, PDO::PARAM_STR);
    $stmt->bindParam(':transfer_reason', $transferReason, PDO::PARAM_STR);
    $stmt->bindParam(':recipient_name', $recipientName, PDO::PARAM_STR);
    $stmt->bindParam(':recipient_contact', $recipientContact, PDO::PARAM_STR);
    $stmt->bindParam(':recipient_email', $recipientEmail, PDO::PARAM_STR);
    $stmt->bindParam(':recipient_alt_email', $recipientAltEmail, PDO::PARAM_STR);
    $stmt->bindParam(':recipient_sex', $recipientSex, PDO::PARAM_STR);
    $stmt->bindParam(':recipient_civil_status', $recipientCivilStatus, PDO::PARAM_STR);
    $stmt->bindParam(':recipient_nationality', $recipientNationality, PDO::PARAM_STR);
    $stmt->bindParam(':recipient_address', $recipientAddress, PDO::PARAM_STR);

    if ($stmt->execute()) {
        return ["success" => true, "message" => "Stall transfer recorded successfully."];
    } else {
        return ["success" => false, "error" => "Failed to insert stall transfer record."];
    }
}
