<?php
require_once '../../includes/config.php';

global $pdo;
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

function getMarketInfo($pdo)
{
    try {

        $input = json_decode(file_get_contents("php://input"), true);

        if (isset($input['id'])) {

            $stall_count = countStalls($pdo, $input);

            $status_count = [];
            $status_count['status'] = countStallsByStatus($pdo, $input);
            $vacant_count = $status_count['status']['vacant'];
            $occupied_count = $status_count['status']['occupied'];

            if ($stall_count) {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => true,
                    's_count' => $stall_count,
                    's_vacant' => $vacant_count,
                    's_occupied' => $occupied_count
                ]);
            } else {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false,
                    'message' => "No stall found with the provided ID."
                ]);
            }
        } else {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => "No ID provided."
            ]);
        }
    } catch (Exception $e) {

        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'message' => "Error: " . $e->getMessage()
        ]);
    }
}

getMarketInfo($pdo);
