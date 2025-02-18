<?php

function uploadApplication($pdo, $applicationNumber, $accountId, $stallId, $sectionId, $marketId, $applicationType, $helperId = null, $extDuration = null)
{
    try {
        $query = "INSERT INTO applications (application_number, account_id, stall_id, section_id, market_id, application_type, helper_id, ext_duration, status, created_at) 
                  VALUES (:application_number, :account_id, :stall_id, :section_id, :market_id, :application_type, :helper_id, :ext_duration, 'Submitted', NOW())";

        $stmt = $pdo->prepare($query);
        $stmt->execute([
            ':application_number' => $applicationNumber,
            ':account_id' => $accountId,
            ':stall_id' => $stallId,
            ':section_id' => $sectionId,
            ':market_id' => $marketId,
            ':application_type' => $applicationType,
            ':helper_id' => $helperId,
            ':ext_duration' => $extDuration
        ]);

        return $pdo->lastInsertId(); // Return the inserted application ID
    } catch (PDOException $e) {
        return ["success" => false, "message" => "Application upload failed: " . $e->getMessage()];
    }
}
