<?php
require_once "../../includes/config.php";

try {
    //STALL
    $stmt_stall = $pdo->prepare("
    SELECT 
        a.id, 
        a.application_type, 
        a.status, 
        a.created_at,
        ac.name AS account_name, 
        st.stall_number ,          
        sec.section_name,     
        mk.market_name          
    FROM 
        applications a
    LEFT JOIN profiles ac ON a.account_id = ac.account_id          
    LEFT JOIN stalls st ON a.stall_id = st.id
    LEFT JOIN sections sec ON a.section_id = sec.id
    LEFT JOIN market_locations mk ON a.market_id = mk.id
    WHERE a.application_type = :application_type
");

    $stmt_stall->bindParam(':application_type', $applicationType);
    $applicationType = 'stall';
    $stmt_stall->execute();
    $stall_results = $stmt_stall->fetchAll(PDO::FETCH_ASSOC);

    // TRANSFER 
    $stmt_transfer = $pdo->prepare("
    SELECT 
        a.id, 
        a.application_type, 
        a.status, 
        a.created_at,
        ac.name AS account_name,  
        st.stall_number ,          
        sec.section_name,     
        mk.market_name           
    FROM 
        applications a
    LEFT JOIN profiles ac ON a.account_id = ac.account_id        
    LEFT JOIN stalls st ON a.stall_id = st.id
    LEFT JOIN sections sec ON a.section_id = sec.id
    LEFT JOIN market_locations mk ON a.market_id = mk.id
    WHERE a.application_type = :application_type
");

    $stmt_transfer->bindParam(':application_type', $applicationType);
    $applicationType = 'stall transfer';
    $stmt_transfer->execute();
    $stall_transfer_results = $stmt_transfer->fetchAll(PDO::FETCH_ASSOC);

    // EXTENSION
    $stmt_extension = $pdo->prepare("
        SELECT 
            a.id, 
            a.application_type, 
            a.status, 
            a.created_at,
            a.ext_duration,
            ac.name AS account_name,  
            st.stall_number ,   
            sec.section_name,
            mk.market_name   
        FROM 
            applications a
        LEFT JOIN profiles ac ON a.account_id = ac.account_id         
        LEFT JOIN stalls st ON a.stall_id = st.id
        LEFT JOIN sections sec ON a.section_id = sec.id
        LEFT JOIN market_locations mk ON a.market_id = mk.id
        WHERE a.application_type = :application_type
    ");

    $stmt_extension->bindParam(':application_type', $applicationType);
    $applicationType = 'stall extension';
    $stmt_extension->execute();
    $stall_extension_results = $stmt_extension->fetchAll(PDO::FETCH_ASSOC);

    // HELPER
    $stmt_helper = $pdo->prepare("
    SELECT 
        a.id, 
        a.application_type, 
        a.status, 
        a.created_at,
        CONCAT(h.first_name, ' ' , h.last_name) AS helper_name,                    -- Get the helper's name from the helpers table
        ac.name AS account_name,          -- Fetch the account name
        st.stall_number,                  -- Stall number from the stalls table
        sec.section_name,                 -- Section name
        mk.market_name                    -- Market name
    FROM 
        applications a
    LEFT JOIN profiles ac ON a.account_id = ac.account_id
    LEFT JOIN stalls st ON a.stall_id = st.id           -- Join stalls to applications
    LEFT JOIN helper h ON st.id = h.stall_id           -- Join helpers to stalls
    LEFT JOIN sections sec ON a.section_id = sec.id
    LEFT JOIN market_locations mk ON a.market_id = mk.id
    WHERE a.application_type = :application_type
");

    $stmt_helper->bindParam(':application_type', $applicationType);
    $applicationType = 'add helper';
    $stmt_helper->execute();
    $stall_helper_results = $stmt_helper->fetchAll(PDO::FETCH_ASSOC);

    $response = [
        'stall' => $stall_results,
        'stall_transfer' => $stall_transfer_results,
        'stall_extension' => $stall_extension_results,
        'helper' => $stall_helper_results,
    ];

    echo json_encode($response);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database query failed: ' . $e->getMessage()]);
}
