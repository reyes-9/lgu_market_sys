<?php
require_once '../../includes/config.php';
require_once '../../includes/session.php';

ob_start();

function countStalls($pdo, $input)
{
    try {
        $selectedId = intval($input['id']);

        $stmt = $pdo->prepare("SELECT COUNT(*) FROM stalls WHERE market_id = :id");
        $stmt->execute([':id' => $selectedId]);

        return $stmt->fetchColumn();
    } catch (PDOException $e) {
        error_log("Error counting stalls: " . $e->getMessage());
        return false;
    }
}

function countStallsByStatus($pdo, $input)
{
    try {
        $selectedId = intval($input['id']);

        $stmtVacant = $pdo->prepare("SELECT COUNT(*) FROM stalls WHERE status = 'available' AND market_id = :id");
        $stmtVacant->execute([':id' => $selectedId]);
        $vacantCount = $stmtVacant->fetchColumn();

        $stmtOccupied = $pdo->prepare("SELECT COUNT(*) FROM stalls WHERE status = 'occupied' AND market_id = :id");
        $stmtOccupied->execute([':id' => $selectedId]);
        $occupiedCount = $stmtOccupied->fetchColumn();

        return [
            'vacant' => $vacantCount,
            'occupied' => $occupiedCount
        ];
    } catch (PDOException $e) {
        error_log("Error counting stalls: " . $e->getMessage());
        return false;
    }
}

function getMapsLink($pdo, $input)
{
    try {
        $selectedId = intval($input['id']);

        $stmt = $pdo->prepare("SELECT google_maps_links FROM market_locations WHERE id = :id");
        $stmt->execute([':id' => $selectedId]);
        $link = $stmt->fetchColumn();

        return $link ?: null; // Return null if no link is found
    } catch (PDOException $e) {
        error_log("Error fetching map link: " . $e->getMessage());
        return null;
    }
}

function getMarketInfo($pdo)
{
    try {
        $input = json_decode(file_get_contents("php://input"), true);

        if (!isset($input['id'])) {
            echo json_encode([
                'success' => false,
                'message' => "No ID provided."
            ]);
            return;
        }

        $stall_count = countStalls($pdo, $input);
        $status_count = countStallsByStatus($pdo, $input);
        $gmap_link = getMapsLink($pdo, $input);

        if ($stall_count !== false && $status_count !== false) {
            echo json_encode([
                'success' => true,
                's_count' => $stall_count,
                's_vacant' => $status_count['vacant'],
                's_occupied' => $status_count['occupied'],
                'gmap_link' => $gmap_link
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => "Failed to retrieve market information."
            ]);
        }
    } catch (Exception $e) {
        error_log("Error fetching market info: " . $e->getMessage());
        echo json_encode([
            'success' => false,
            'message' => "Error: " . $e->getMessage()
        ]);
    }
}

header('Content-Type: application/json');
getMarketInfo($pdo);
