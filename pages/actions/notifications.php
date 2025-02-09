<?php
require_once '../../includes/config.php';
session_start();
$account_id = $_SESSION['user_id'];
$notifications = [
    'application' => [
        'submitted' => [
            'type' => '',
            'message' => 'Your application for %s is submitted successfully.',
        ],
        'approved' => [
            'type' => 'Application Approved',
            'message' => 'Congratulations! Your stall application has been approved.',
        ],
        'rejected' => [
            'type' => 'Application Rejected',
            'message' => 'Unfortunately, your stall application has been rejected.',
        ],
        'ownership_transferred' => [
            'type' => 'Stall Ownership Transferred',
            'message' => 'The stall ownership has been transferred to you.',
        ]
    ],
    'track' => [
        'withdrawn' => [
            'type' => 'Application Successfully Withdrawn',
            'message' => 'Your application for %s has been withdrawn successfully.',
        ]
    ],
    'violation' => [
        'issued' => [
            'type' => 'Violation Issued',
            'message' => 'A violation has been recorded for your stall.',
        ],
        'warning_issued' => [
            'type' => 'Warning Issued',
            'message' => 'You have received a warning for non-compliance.',
        ],
        'violation_cleared' => [
            'type' => 'Violation Cleared',
            'message' => 'Your compliance submission has been reviewed and cleared.',
        ]
    ],
    'market' => [
        'maintenance_scheduled' => [
            'type' => '',
            'message' => '',
        ],
        'announcement' => [
            'type' => 'Market Announcements',
            'message' => '',
        ],
        'scheduled_downtime' => [
            'type' => 'Scheduled Downtime',
            'message' => '',
        ]
    ],
    'system' => [
        'update_available' => [
            'type' => 'System Update Available',
            'message' => 'A new feature has been added to the Vendor Portal.',
        ]
    ],
    'feedback' => [
        'submitted' => [
            'type' => 'Feedback Submitted',
            'message' => 'Your feedback has been received. Thank you for your input!',
        ]
    ],
    'support' => [
        'submitted' => [
            'type' => 'Support Request Submitted',
            'message' => 'Your request has been received. Thank you for your input!',
        ]
    ]
];
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] != 'POST') {

    getNotifications($pdo, $account_id);
}

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['action']) && $data['action'] === 'mark_all_read') {
    $success = markAllAsRead($pdo, $account_id);

    if ($success != true) {
        echo json_encode(['read_status' => 'error', 'message' => 'Failed to update notifications']);
        exit;
    }
    echo json_encode(['read_status' => 'success']);
    exit;
}


function getNotifications($pdo, $account_id)
{

    header('Content-Type: application/json');

    try {
        $query = "SELECT message, type, status, created_at FROM notifications WHERE user_id = :user_id ORDER BY created_at DESC";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':user_id', $account_id, PDO::PARAM_INT);
        $stmt->execute();

        $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Check if notifications are returned
        if (empty($notifications)) {
            echo json_encode(['status' => 'success', 'notifications' => []]); // Empty array if no notifications found
        } else {
            echo json_encode(['status' => 'success', 'notifications' => $notifications]);
        }
    } catch (PDOException $e) {
        // Return error as JSON

        echo json_encode(['status' => 'error', 'message' => 'Failed to fetch notifications']);
    }
}

function insertNotification($pdo, $user_id, $type, $message, $status = 'unread')
{
    try {
        $query = "INSERT INTO notifications (user_id, type, message, status, created_at) 
                  VALUES (:user_id, :type, :message, :status, NOW())";

        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':type', $type, PDO::PARAM_STR);
        $stmt->bindParam(':message', $message, PDO::PARAM_STR);
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);

        return $stmt->execute();
    } catch (PDOException $e) {
        // Handle error
        return false;
    }
}

function markAllAsRead($pdo, $account_id)
{
    try {
        $query = "UPDATE notifications SET status = 'read' WHERE user_id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $account_id, PDO::PARAM_INT);

        return $stmt->execute();
    } catch (PDOException $e) {
        // Handle error
        return false;
    }
}

function markAsRead($pdo, $account_id)
{
    try {
        $query = "UPDATE notifications SET status = 'read' WHERE user_id = :user_id AND id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $account_id, PDO::PARAM_INT);

        return $stmt->execute();
    } catch (PDOException $e) {
        // Handle error
        return false;
    }
}
