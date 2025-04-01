<?php
require_once '../../includes/config.php';

$currentDate = date('Y-m-d');
$query = "
    UPDATE expiration_dates
    SET status = 'expired'
    WHERE expiration_date <= :currentDate
      AND status != 'expired'
";

try {
    $stmt = $pdo->prepare($query);

    $stmt->bindParam(':currentDate', $currentDate);
    $stmt->execute();  // Execute the query

    echo "Expiration dates have been updated successfully.";
} catch (PDOException $e) {

    $errorMessage = "Error: " . $e->getMessage() . "\n";
    error_log($errorMessage);
}
