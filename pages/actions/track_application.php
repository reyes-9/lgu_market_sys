<?php
require_once '../../includes/config.php';
require_once '../../includes/session.php';

header('Content-Type: application/json');

$account_id = $_SESSION['account_id'];

try {
    // **Check if it's a withdrawal request (POST)**
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Decode JSON from request body
        $data = json_decode(file_get_contents("php://input"), true);

        // Check if 'id' exists in the JSON data
        if (!isset($data['id'])) {
            echo json_encode(['success' => false, 'message' => 'Application ID is required.']);
            exit;
        }

        // Check if 'id' exists in the JSON data
        if (!isset($data['name'])) {
            echo json_encode(['success' => false, 'message' => 'Application name is required.']);
            exit;
        }

        $applicationId = $data['id'];
        $applicationName = $data['name'];

        // Prepare SQL to update the application's status
        $sql = "UPDATE applications SET status = 'Withdrawn' WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $applicationId, PDO::PARAM_INT);
        $stmt->execute();

        // Check if the application was updated
        if ($stmt->rowCount() > 0) {

            include_once 'notifications.php';
            ob_clean();
            echo json_encode([
                'success' => true,
                'message' => ["Application successfully withdrawn."]
            ]);

            $type = $notifications['track']['withdrawn']['type'];
            $message = sprintf($notifications['track']['withdrawn']['message'], $applicationName);

            insertNotification($pdo, $account_id, $type, $message, 'unread');
            exit();
        } else {
            throw new Exception('Application not found or already withdrawn.');
        }

        exit;
    }

    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $limit = 3; // Number of cards per page
    $offset = ($page - 1) * $limit;

    // Fetch total number of records for pagination
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM applications WHERE account_id = :account_id AND status != 'Withdrawn'");
    $stmt->bindParam(':account_id', $account_id, PDO::PARAM_INT);
    $stmt->execute();
    $total_rows = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    $total_pages = ceil($total_rows / $limit);

    $query = "SELECT 
                s.stall_number, 
                sec.section_name AS section_name, 
                m.market_name AS market_name,
                CONCAT(h.first_name, ' ', COALESCE(h.middle_name, ''), ' ', h.last_name) AS full_name,
                e.duration AS extension_duration,
                app.id,
                app.application_type,
                app.application_number,
                app.status,
                app.reviewing_admin_id,
                app.inspection_status,
                app.created_at
            FROM applications app
            JOIN stalls s ON app.stall_id = s.id    
            JOIN sections sec ON app.section_id = sec.id
            JOIN market_locations m ON app.market_id = m.id
            LEFT JOIN extensions e ON app.extension_id = e.id
            LEFT JOIN helpers h ON app.helper_id = h.id
            WHERE app.account_id = :account_id
            AND app.status != 'Withdrawn'
            ORDER BY app.created_at DESC
            LIMIT :limit OFFSET :offset
        ";

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':account_id', $account_id, PDO::PARAM_INT);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    $applications = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'data' => $applications,
        'pagination' => [
            'total_pages' => $total_pages,
            'current_page' => $page
        ]
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
