<?php
require_once '../../includes/session.php';

function logAdminAction($pdo, $admin_id, $action, $details = null)
{
    $stmt = $pdo->prepare("INSERT INTO admin_logs (admin_id, action, details, timestamp) VALUES (:admin_id, :action, :details, NOW())");
    $stmt->execute([
        ':admin_id' => $admin_id,
        ':action' => $action,
        ':details' => $details
    ]);
}
