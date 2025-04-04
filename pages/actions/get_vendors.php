<?php
require_once '../../includes/config.php';
require_once '../../includes/session.php';

try {

    // Prepare the SQL query to fetch vendor data
    $sql = "SELECT 
                id, 
                account_id,
                first_name, 
                middle_name, 
                last_name, 
                email, 
                alt_email, 
                contact_no, 
                sex, 
                civil_status, 
                nationality, 
                address, 
                application_status, 
                application_date
            FROM vendors_application";


    $stmt = $pdo->query($sql);
    $vendors = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($vendors) {
        // Return the vendor data in JSON format
        echo json_encode(['success' => true, 'vendors' => $vendors]);
    } else {
        // No vendors found
        echo json_encode(['success' => false, 'message' => 'No vendors found.']);
    }
} catch (PDOException $e) {
    // Handle any errors by returning a JSON error message
    echo json_encode(['success' => false, 'message' => 'Error fetching vendors: ' . $e->getMessage()]);
}
