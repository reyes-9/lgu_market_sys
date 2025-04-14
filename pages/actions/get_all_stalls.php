<?php
require_once "../../includes/config.php";
require_once '../../includes/session.php';

try {
    //STALL
    $stmt_stall = $pdo->prepare("
    SELECT 
        a.id, 
        a.application_type, 
        a.status, 
        a.created_at,
        ac.first_name,  
        ac.middle_name,  
        ac.last_name,  
        CONCAT(ac.first_name, ' ', COALESCE(ac.middle_name, ''), ' ', ac.last_name) AS account_name, 
        st.stall_number,          
        sec.section_name,     
        mk.market_name          
    FROM 
        applications a
    LEFT JOIN users ac ON a.account_id = ac.account_id          
    LEFT JOIN stalls st ON a.stall_id = st.id
    LEFT JOIN sections sec ON a.section_id = sec.id
    LEFT JOIN market_locations mk ON a.market_id = mk.id
    WHERE a.application_type = :application_type
");

    $stmt_stall->bindParam(':application_type', $applicationType);
    $applicationType = 'stall';
    $stmt_stall->execute();
    $stall_results = $stmt_stall->fetchAll(PDO::FETCH_ASSOC);

    // TRANSFER / SUCCESSION
    $stmt_transfer = $pdo->prepare("
    SELECT 
        a.id, 
        a.application_type, 
        a.status, 
        a.created_at,
        ac.first_name,  
        ac.middle_name,  
        ac.last_name,  
        CONCAT(ac.first_name, ' ', COALESCE(ac.middle_name, ''), ' ', ac.last_name) AS account_name, 
        st.stall_number,          
        sec.section_name,     
        mk.market_name           
    FROM 
        applications a
    LEFT JOIN users ac ON a.account_id = ac.account_id        
    LEFT JOIN stalls st ON a.stall_id = st.id
    LEFT JOIN sections sec ON a.section_id = sec.id
    LEFT JOIN market_locations mk ON a.market_id = mk.id
    WHERE a.application_type IN ('stall transfer', 'stall succession')
");

    $stmt_transfer->execute();
    $stall_transfer_results = $stmt_transfer->fetchAll(PDO::FETCH_ASSOC);

    // EXTENSION
    $stmt_extension = $pdo->prepare("
    SELECT 
        a.id, 
        a.application_type, 
        a.status, 
        a.created_at,
        e.duration AS ext_duration, -- Fetch duration from extensions table
        ac.first_name,  
        ac.middle_name,  
        ac.last_name,  
        CONCAT(ac.first_name, ' ', COALESCE(ac.middle_name, ''), ' ', ac.last_name) AS account_name, 
        st.stall_number,   
        sec.section_name,
        mk.market_name   
    FROM 
        applications a
    LEFT JOIN users ac ON a.account_id = ac.account_id         
    LEFT JOIN stalls st ON a.stall_id = st.id
    LEFT JOIN sections sec ON a.section_id = sec.id
    LEFT JOIN market_locations mk ON a.market_id = mk.id
    LEFT JOIN extensions e ON a.extension_id = e.id 
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
        CONCAT(h.first_name, ' ', COALESCE(h.middle_name, ''), ' ' ,h.last_name) AS helper_name, 
        ac.first_name,  
        ac.middle_name,  
        ac.last_name,  
        CONCAT(ac.first_name, ' ', COALESCE(ac.middle_name, ''), ' ', ac.last_name) AS account_name,
        st.stall_number,       
        sec.section_name,               
        mk.market_name                  
    FROM 
        applications a
    LEFT JOIN users ac ON a.account_id = ac.account_id
    LEFT JOIN stalls st ON a.stall_id = st.id           
    LEFT JOIN helpers h ON st.id = h.stall_id          
    LEFT JOIN sections sec ON a.section_id = sec.id
    LEFT JOIN market_locations mk ON a.market_id = mk.id
    WHERE a.application_type = :application_type
");

    $stmt_helper->bindParam(':application_type', $applicationType);
    $applicationType = 'helper';
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
