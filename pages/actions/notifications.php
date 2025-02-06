<?php

session_start();
// Define notification types and their corresponding messages in an array
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
// Function to fetch notifications for a user
function getNotifications()
{
    require_once '../../includes/config.php';
    header('Content-Type: application/json');
    $account_id = $_SESSION['user_id'];

    try {
        $query = "SELECT message, type, status FROM notifications WHERE user_id = :user_id ORDER BY created_at DESC";
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
        header('Content-Type: application/json');
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

// Function to mark notification as read
function markNotificationAsRead($pdo, $notification_id)
{
    try {
        $query = "UPDATE notifications SET status = 'read' WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $notification_id, PDO::PARAM_INT);

        return $stmt->execute();
    } catch (PDOException $e) {
        // Handle error
        return false;
    }
}

getNotifications();
