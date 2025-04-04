<?php
require_once '../../includes/config.php';
require_once '../../includes/session.php';
require_once 'get_user_id.php';

header('Content-Type: application/json');

$account_id = $_SESSION['account_id']; // Assuming the user ID is stored in the session
$user_id = getUserIdByAccountId($pdo, $account_id);

var_dump($account_id);

$sql = "SELECT * FROM users WHERE id = :user_id"; // Query to get user details
$stmt = $pdo->prepare($sql);
$stmt->execute(['user_id' => $user_id]);

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    echo json_encode($user);
} else {
    echo json_encode(['error' => 'User not found']);
}
