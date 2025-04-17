<?php
require_once '../../includes/session.php';
function uploadApplication($pdo, $applicationNumber, $accountId, $stallId, $sectionId, $marketId, $applicationType, $products, $helperId = null, $extensionId = null)
{
    try {
        $query = "INSERT INTO applications (application_number, account_id, stall_id, section_id, market_id, application_type, products, helper_id, extension_id, status, created_at) 
                  VALUES (:application_number, :account_id, :stall_id, :section_id, :market_id, :application_type, :products, :helper_id, :ext_duration, 'Submitted', NOW())";

        $stmt = $pdo->prepare($query);
        $stmt->execute([
            ':application_number' => $applicationNumber,
            ':account_id' => $accountId,
            ':stall_id' => $stallId,
            ':section_id' => $sectionId,
            ':market_id' => $marketId,
            ':application_type' => $applicationType,
            ':products' => $products,
            ':helper_id' => $helperId,
            ':ext_duration' => $extensionId
        ]);

        return $pdo->lastInsertId(); // Return the inserted application ID
    } catch (PDOException $e) {
        return ["success" => false, "message" => "Application upload failed: " . $e->getMessage()];
    }
}
