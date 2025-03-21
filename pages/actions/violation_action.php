<?php
require_once "../../includes/config.php";
require "../../includes/session.php";
require "get_user_id.php";

header("Content-Type: application/json");

$account_id = $_SESSION["account_id"];
$user_id = getUserIdByAccountId($pdo, $account_id);

try {
    $violations = getViolations($user_id, $pdo);
    $statusCount = getStatusCount($user_id, $pdo);
    echo json_encode(["success" => true, "data" => $violations, "count" => $statusCount]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "error" => "Database error", "details" => $e->getMessage()]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "error" => "Unexpected error", "details" => $e->getMessage()]);
}

function getViolations($user_id, $pdo)
{
    try {
        $sql = "SELECT v.id, DATE(v.violation_date) AS violation_date, 
                        v.status,
                        v.violation_description,
                        v.evidence_image_path,
                       vt.violation_name, vt.criticality, vt.fine_amount  
                FROM violations v
                JOIN violation_types vt ON v.violation_type_id = vt.id
                JOIN stalls s ON v.stall_id = s.id
                WHERE v.user_id = :user_id";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        throw new Exception("Database query failed: " . $e->getMessage());
    }
}
function getStatusCount($user_id, $pdo)
{
    $query = "
        SELECT 
            v.status, 
            COUNT(*) as count, 
            SUM(CASE WHEN vt.criticality = 'Critical' THEN 1 ELSE 0 END) as critical_count
        FROM violations v
        JOIN violation_types vt ON v.violation_type_id = vt.id
        WHERE v.user_id = :user_id 
        GROUP BY v.status
    ";

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $statusCounts = [
        'Critical' => 0,
        'Pending' => 0,
        'Resolved' => 0,
        'Rejected' => 0
    ];

    foreach ($results as $row) {
        $statusCounts[$row['status']] = $row['count'];
        $statusCounts['Critical'] += $row['critical_count'];
    }

    return $statusCounts;
}
