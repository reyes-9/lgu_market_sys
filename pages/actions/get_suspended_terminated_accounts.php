<?php

require_once '../../includes/config.php';

try {
    $getSuspendedAndTerminatedQuery =
        "SELECT 
    u.id AS `user_id`,
    
    -- Full name, skip middle name if N/A
    CONCAT(
        u.first_name, ' ',
        CASE 
            WHEN LOWER(u.middle_name) = 'n/a' THEN ''
            ELSE CONCAT(u.middle_name, ' ')
        END,
        u.last_name
    ) AS `name`,
    
    -- Status with only the first character uppercased
    CONCAT(UPPER(LEFT(u.status, 1)), SUBSTRING(u.status, 2)) AS `status`,
    
    -- Combine multiple violation reasons and their dates, each on a new line
    GROUP_CONCAT(
        CONCAT( vt.violation_name, ' - (', DATE_FORMAT(u.updated_at, '%Y-%m-%d'), ')')
        ORDER BY v.updated_at DESC
        SEPARATOR '<br>'
        
    ) AS `reason`,

    -- Latest violation date (useful for remarks)
    MAX(v.updated_at) AS `date`,

    m.market_name AS `market`,
    s.stall_number AS `stall_number`,

    -- Remarks based on latest violation date
    CASE 
        WHEN MAX(v.updated_at) >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) THEN 'Terminated within last 7 days'
        WHEN MAX(v.updated_at) IS NOT NULL THEN 'Repeated violations' 
        ELSE '—'
    END AS `remarks`

FROM users u
LEFT JOIN violations v ON v.user_id = u.id
LEFT JOIN violation_types vt ON vt.id = v.violation_type_id
LEFT JOIN stalls s ON s.user_id = u.id
LEFT JOIN market_locations m ON m.id = s.market_id

WHERE u.status = 'terminated'

GROUP BY u.id, u.first_name, u.middle_name, u.last_name, u.status, m.market_name, s.stall_number;
";
    // Prepare and execute the query
    $stmt = $pdo->prepare($getSuspendedAndTerminatedQuery);
    if (!$stmt->execute()) {
        echo json_encode([
            'success' => false,
            'message' => 'Failed to execute the query.'
        ]);
        exit;
    }

    $violators = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$violators) {
        echo json_encode([
            'success' => true,
            'message' => 'No terminated users found.'
        ]);
        exit;
    }

    // Send data
    echo json_encode([
        'success' => true,
        'message' => '',
        'data' => $violators
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
