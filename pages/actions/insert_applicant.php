<?php

function insertApplicant($pdo, $user_id, $application_id)
{
    try {
        // Prepare SQL query for inserting applicant
        $query = "INSERT INTO applicants (user_id, application_id, created_at) 
                  VALUES (:user_id, :application_id, NOW())";

        $stmt = $pdo->prepare($query);

        // Bind values
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindValue(':application_id', $application_id, PDO::PARAM_INT);

        $stmt->execute();

        return ["success" => true];
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        return ["success" => false, "error" => $e->getMessage()];
    }
}
