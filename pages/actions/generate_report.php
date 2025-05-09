<?php
require_once '../../includes/config.php';

// var_dump($_POST);

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Sanitize input
    $marketId = isset($_POST['marketId']) ? htmlspecialchars($_POST['marketId']) : null;
    $category = isset($_POST['reportCategory']) ? htmlspecialchars($_POST['reportCategory']) : null;
    $reportType = isset($_POST['reportType']) ? htmlspecialchars($_POST['reportType']) : null;
    $reportTitle = isset($_POST['reportTitle']) ? htmlspecialchars($_POST['reportTitle']) : null;
    $startDate = isset($_POST['startDate']) ? htmlspecialchars($_POST['startDate']) : null;
    $endDate = isset($_POST['endDate']) ? htmlspecialchars($_POST['endDate']) : null;

    if (!$marketId || !$category || !$reportType || !$reportTitle || !$startDate || !$endDate) {
        http_response_code(400);
        echo json_encode([
            "success" => false,
            "message" => "Missing data."
        ]);
        exit;
    }

    try {
        switch ($reportType) {
            case "Stall Utilization Report":
                $data = stallUtilizationReport($pdo, $reportTitle, $marketId, $startDate, $endDate);
                break;

            case "Vendor Master List Report":

                $data = vendorMasterListReport($pdo, $reportTitle, $marketId, $startDate, $endDate);
                break;

            case "Violation Summary Report":

                $data = violationSummaryReport($pdo, $reportTitle, $marketId, $startDate, $endDate);
                break;

            case "Stall Transfer Requests Report":

                $data = stallTransferRequestsReport($pdo, $reportTitle, $marketId, $startDate, $endDate);
                break;
            case "Stall Extension Requests Report":

                $data = stallExtensionRequestsReport($pdo, $reportTitle, $marketId, $startDate, $endDate);
                break;

            default:
                echo json_encode([
                    "success" => false,
                    "message" => "Unknown report type selected."
                ]);
                break;
        }

        if (!$data) {
            throw new Exception("No Record Found");
        }

        echo json_encode([
            "success" => true,
            "reports" => $data,
            "message" => $reportType . " generated successfully."
        ]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            "success" => false,
            "message" => "Server error: " . $e->getMessage()
        ]);
    }
} else {
    http_response_code(405);
    echo json_encode(["success" => false, "message" => "Method Not Allowed"]);
}

// Report function
function stallUtilizationReport($pdo, $reportTitle,  $marketId, $startDate, $endDate)
{
    $success = false;
    // Main occupancy report
    $sql = "SELECT 
                m.market_name AS market_name,
                sec.section_name,
                s.stall_number,
                CONCAT(
                    u.first_name, ' ',
                    CASE 
                        WHEN u.middle_name = 'n/a' THEN ''
                        ELSE CONCAT(u.middle_name, ' ')
                    END,
                    u.last_name
                ) AS vendor_name,
                s.occupancy_date
            FROM stalls s
            JOIN market_locations m ON s.market_id = m.id
            JOIN sections sec ON s.section_id = sec.id
            LEFT JOIN users u ON s.user_id = u.id
            WHERE 
                s.market_id = :market_id 
                AND s.occupancy_date BETWEEN :startDate AND :endDate
            ORDER BY m.market_name, sec.section_name, s.stall_number";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':market_id' => $marketId,
        ':startDate' => $startDate,
        ':endDate'   => $endDate
    ]);

    $reportData = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (!empty($reportData)) {
        $success = true;
    }

    // Occupancy percentage
    $occupancySQL = "SELECT 
        COUNT(*) AS total,
        SUM(CASE WHEN status = 'occupied' THEN 1 ELSE 0 END) AS occupied,
        ROUND(
            (SUM(CASE WHEN status = 'occupied' THEN 1 ELSE 0 END) / COUNT(*)) * 100, 
            2
        ) AS occupancy_rate,
        ROUND(
            100 - ((SUM(CASE WHEN status = 'occupied' THEN 1 ELSE 0 END) / COUNT(*)) * 100),
            2
        ) AS availability_rate
    FROM stalls
    WHERE market_id = :market_id
    ";

    $stmt2 = $pdo->prepare($occupancySQL);
    $stmt2->execute([':market_id' => $marketId]);
    $occupancyStats = $stmt2->fetch(PDO::FETCH_ASSOC);

    $finalReport = [];

    foreach ($reportData as $row) {
        $finalReport[] = [
            'title'            => $reportTitle,
            'market_name'      => $row['market_name'],
            'section'          => $row['section_name'],
            'stall_number'     => $row['stall_number'],
            'vendor_name'      => $row['vendor_name'],
            'occupancy_date'   => $row['occupancy_date'],
            'total_stalls'     => $occupancyStats['total'],
            'occupied_stalls'  => $occupancyStats['occupied'],
            'occupancy_rate'   => $occupancyStats['occupancy_rate'],
            'availability_rate'   => $occupancyStats['availability_rate'],
        ];
    }

    if ($success) {
        return [
            'headers' => ['Market Name', 'Section', 'Stall Number', 'Vendor Name', 'Occupancy Date', 'Title', 'Total Stalls', 'Occupied Stalls', 'Occupancy Rate', 'Availability Rate'],
            'rows'    => $finalReport
        ];
    } else {
        return false;
    }
}

function vendorMasterListReport($pdo, $reportTitle,  $marketId, $startDate, $endDate)
{
    $success = false;
    $sql = "SELECT 
                m.market_name AS market_name,
                sec.section_name,
                s.stall_number,
                CONCAT(
                    u.first_name, ' ',
                    CASE 
                        WHEN u.middle_name = 'n/a' THEN ''
                        ELSE CONCAT(u.middle_name, ' ')
                    END,
                    u.last_name
                ) AS vendor_name,
                u.contact_no AS contact_number,
                s.occupancy_date,
                s.product 
            FROM stalls s
            JOIN market_locations m ON s.market_id = m.id
            JOIN sections sec ON s.section_id = sec.id
            LEFT JOIN users u ON s.user_id = u.id
            WHERE 
                s.market_id = :market_id 
                AND
                u.status = 'active'
                AND 
                s.occupancy_date BETWEEN :startDate AND :endDate
            ORDER BY m.market_name, sec.section_name, s.stall_number";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':market_id' => $marketId,
        ':startDate' => $startDate,
        ':endDate'   => $endDate
    ]);

    $reportData = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (!empty($reportData)) {
        $success = true;
    }

    // Total Vendors Count
    $vendorsSQL = "SELECT 
        COUNT(DISTINCT u.id) AS total_vendors
    FROM stalls s
    JOIN users u ON u.id = s.user_id
    WHERE s.market_id = :market_id AND s.status = 'occupied';

    ";

    $stmt2 = $pdo->prepare($vendorsSQL);
    $stmt2->execute([':market_id' => $marketId]);
    $vendorCount = $stmt2->fetch(PDO::FETCH_ASSOC);

    $finalReport = [];

    foreach ($reportData as $row) {
        $finalReport[] = [
            'title'            => $reportTitle,
            'market_name'      => $row['market_name'],
            'section'          => $row['section_name'],
            'stall_number'     => $row['stall_number'],
            'vendor_name'      => $row['vendor_name'],
            'contact_number'   => $row['contact_number'],
            'products'         => $row['product'],
            'occupancy_date'   => $row['occupancy_date'],
            'total_vendors'    => $vendorCount['total_vendors'],
        ];
    }

    if ($success) {
        return [
            'headers' => ['Market Name', 'Section', 'Stall Number', 'Vendor Name', 'Contact Number', 'Products', 'Occupancy Date', 'Title', 'Total Vendors'],
            'rows'    => $finalReport
        ];
    } else {
        return false;
    }
}

function violationSummaryReport($pdo, $reportTitle, $marketId, $startDate, $endDate)
{
    $success = false;

    // Main violation details query
    $sql = "SELECT 
                m.market_name AS market_name,
                sec.section_name,
                s.stall_number,
                CONCAT(
                    u.first_name, ' ',
                    CASE 
                        WHEN u.middle_name = 'n/a' THEN ''
                        ELSE CONCAT(u.middle_name, ' ')
                    END,
                    u.last_name
                ) AS vendor_name,
                vt.violation_name,
                v.status AS violation_status
            FROM stalls s
            JOIN market_locations m ON s.market_id = m.id
            JOIN sections sec ON s.section_id = sec.id
            LEFT JOIN users u ON s.user_id = u.id
            LEFT JOIN violations v ON v.user_id = u.id
            LEFT JOIN violation_types vt ON v.violation_type_id = vt.id
            WHERE 
                s.market_id = :market_id 
                AND u.status = 'active'
                AND v.created_at BETWEEN :startDate AND :endDate
            ORDER BY m.market_name, sec.section_name, s.stall_number";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':market_id' => $marketId,
        ':startDate' => $startDate,
        ':endDate'   => $endDate
    ]);

    $reportData = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $success = !empty($reportData);

    // Violation summary counts
    $violationSQL = "SELECT
        (SELECT COUNT(*) FROM violations) AS violation_count,
        (SELECT COUNT(*) FROM violations WHERE status = 'Pending') AS pending_count,
        (SELECT COUNT(*) FROM violations WHERE status = 'Resolved') AS resolved_count,
        (SELECT COUNT(*) FROM violations WHERE status = 'Escalated') AS escalated_count,
        (SELECT COUNT(DISTINCT user_id) FROM violations) AS vendor_count,
        (
            SELECT vt.violation_name
            FROM violations v
            JOIN violation_types vt ON v.violation_type_id = vt.id
            GROUP BY v.violation_type_id
            ORDER BY COUNT(v.violation_type_id) DESC
            LIMIT 1
        ) AS most_common_violation,
        (
            SELECT COUNT(v.violation_type_id)
            FROM violations v
            GROUP BY v.violation_type_id
            ORDER BY COUNT(v.violation_type_id) DESC
            LIMIT 1
        ) AS most_common_total";

    $stmt2 = $pdo->prepare($violationSQL);
    $stmt2->execute();
    $violationSummary = $stmt2->fetch(PDO::FETCH_ASSOC);

    $finalReport = [];

    foreach ($reportData as $row) {
        $finalReport[] = [
            'title'            => $reportTitle,
            'market_name'      => $row['market_name'],
            'section'          => $row['section_name'],
            'stall_number'     => $row['stall_number'],
            'vendor_name'      => $row['vendor_name'],
            'violation_name'   => $row['violation_name'],
            'violation_status'   => $row['violation_status'],
            'violation_count'   => $violationSummary['violation_count'],
            'pending_count'   => $violationSummary['pending_count'],
            'resolved_count'   => $violationSummary['resolved_count'],
            'escalated_count'   => $violationSummary['escalated_count'],
            'vendor_count'   => $violationSummary['vendor_count'],
            'most_common_violation'   => $violationSummary['most_common_violation'],
        ];
    }

    if ($success) {
        return [
            'headers' => ['Market Name', 'Section', 'Stall Number', 'Vendor Name', 'Violation Name', 'Violation Status', 'Violation Count', 'Pending Count', 'Resolved Count', 'Escalated Count', 'Vendor Count', 'Most Common Violation'],
            'rows'    => $finalReport
        ];
    } else {
        return false;
    }
}

function stallTransferRequestsReport($pdo, $reportTitle,  $marketId, $startDate, $endDate)
{

    $success = false;
    $sql = "SELECT 
                    m.market_name AS market_name,
                    sec.section_name,
                    s.stall_number,
                    CONCAT(
                        u.first_name, ' ',
                        CASE 
        WHEN LOWER(u.middle_name) = 'n/a' THEN ''
        ELSE CONCAT(u.middle_name, ' ')
        END,
                        u.last_name
                    ) AS vendor_name
                FROM applications a
                JOIN stalls s ON a.stall_id = s.id
                JOIN market_locations m ON a.market_id = m.id
                JOIN sections sec ON a.section_id = sec.id
                LEFT JOIN users u ON a.account_id = u.account_id
                WHERE
                    a.market_id = :market_id
                    AND
                    a.status = 'Submitted'
                    AND 
                    a.application_type = 'stall transfer'
                    AND
                    a.created_at BETWEEN :startDate AND :endDate
                ORDER BY m.market_name, sec.section_name, s.stall_number
               ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':market_id' => $marketId,
        ':startDate' => $startDate,
        ':endDate'   => $endDate
    ]);

    $reportData = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (!empty($reportData)) {
        $success = true;
    }

    $transferSQL = "
    SELECT
      (SELECT COUNT(*) FROM applications WHERE application_type = 'stall transfer' AND market_id = ? AND created_at BETWEEN ? AND ?) AS total_transfer_requests,
      (SELECT COUNT(*) FROM applications WHERE application_type = 'stall transfer' AND market_id = ? AND status = 'Submitted' AND created_at BETWEEN ? AND ?) AS pending_count,
      (SELECT COUNT(*) FROM applications WHERE application_type = 'stall transfer' AND market_id = ? AND status = 'Approved' AND created_at BETWEEN ? AND ?) AS approved_count,
      (SELECT COUNT(*) FROM applications WHERE application_type = 'stall transfer' AND market_id = ? AND status = 'Rejected' AND created_at BETWEEN ? AND ?) AS rejected_count
    ";

    $stmt2 = $pdo->prepare($transferSQL);
    $stmt2->execute([
        $marketId,
        $startDate,
        $endDate,
        $marketId,
        $startDate,
        $endDate,
        $marketId,
        $startDate,
        $endDate,
        $marketId,
        $startDate,
        $endDate
    ]);
    $transferSummary = $stmt2->fetch(PDO::FETCH_ASSOC);

    $finalReport = [];

    foreach ($reportData as $row) {
        $finalReport[] = [
            'title'            => $reportTitle,
            'market_name'      => $row['market_name'],
            'section'          => $row['section_name'],
            'stall_number'     => $row['stall_number'],
            'vendor_name'      => $row['vendor_name'],
            'total_transfer_requests'    => $transferSummary['total_transfer_requests'],
            'pending_count'    => $transferSummary['pending_count'],
            'approved_count'    => $transferSummary['approved_count'],
            'rejected_count'    => $transferSummary['rejected_count']
        ];
    }

    if ($success) {
        return [
            'headers' => ['Market Name', 'Section', 'Stall Number', 'Vendor Name', 'Total Transfer Requests', 'Pending Count', 'Approved Count', 'Rejected Count'],
            'rows'    => $finalReport
        ];
    } else {
        return false;
    }
}
function stallExtensionRequestsReport($pdo, $reportTitle,  $marketId, $startDate, $endDate)
{

    $success = false;
    $sql = "SELECT 
                    m.market_name AS market_name,
                    sec.section_name,
                    s.stall_number,
                    CONCAT(
                        u.first_name, ' ',
                        CASE 
        WHEN LOWER(u.middle_name) = 'n/a' THEN ''
        ELSE CONCAT(u.middle_name, ' ')
        END,
                        u.last_name
                    ) AS vendor_name
                FROM applications a
                JOIN stalls s ON a.stall_id = s.id
                JOIN market_locations m ON a.market_id = m.id
                JOIN sections sec ON a.section_id = sec.id
                LEFT JOIN users u ON a.account_id = u.account_id
                WHERE
                    a.market_id = :market_id
                    AND
                    a.status = 'Submitted'
                    AND 
                    a.application_type = 'stall extension'
                    AND
                    a.created_at BETWEEN :startDate AND :endDate
                ORDER BY m.market_name, sec.section_name, s.stall_number
               ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':market_id' => $marketId,
        ':startDate' => $startDate,
        ':endDate'   => $endDate
    ]);

    $reportData = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (!empty($reportData)) {
        $success = true;
    }

    $transferSQL = "
    SELECT
      (SELECT COUNT(*) FROM applications WHERE application_type = 'stall extension' AND market_id = ? AND created_at BETWEEN ? AND ?) AS total_extension_requests,
      (SELECT COUNT(*) FROM applications WHERE application_type = 'stall extension' AND market_id = ? AND status = 'Submitted' AND created_at BETWEEN ? AND ?) AS pending_count,
      (SELECT COUNT(*) FROM applications WHERE application_type = 'stall extension' AND market_id = ? AND status = 'Approved' AND created_at BETWEEN ? AND ?) AS approved_count,
      (SELECT COUNT(*) FROM applications WHERE application_type = 'stall extension' AND market_id = ? AND status = 'Rejected' AND created_at BETWEEN ? AND ?) AS rejected_count
    ";

    $stmt2 = $pdo->prepare($transferSQL);
    $stmt2->execute([
        $marketId,
        $startDate,
        $endDate,
        $marketId,
        $startDate,
        $endDate,
        $marketId,
        $startDate,
        $endDate,
        $marketId,
        $startDate,
        $endDate
    ]);
    $transferSummary = $stmt2->fetch(PDO::FETCH_ASSOC);

    $finalReport = [];

    foreach ($reportData as $row) {
        $finalReport[] = [
            'title'            => $reportTitle,
            'market_name'      => $row['market_name'],
            'section'          => $row['section_name'],
            'stall_number'     => $row['stall_number'],
            'vendor_name'      => $row['vendor_name'],
            'total_extension_requests'    => $transferSummary['total_extension_requests'],
            'pending_count'    => $transferSummary['pending_count'],
            'approved_count'    => $transferSummary['approved_count'],
            'rejected_count'    => $transferSummary['rejected_count']
        ];
    }

    if ($success) {
        return [
            'headers' => ['Market Name', 'Section', 'Stall Number', 'Vendor Name', 'Total Extension Requests', 'Pending Count', 'Approved Count', 'Rejected Count'],
            'rows'    => $finalReport
        ];
    } else {
        return false;
    }
}
