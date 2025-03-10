<?php
require_once "../../includes/config.php";
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['account_id'])) {
    echo json_encode(["success" => false, "message" => "User not logged in."]);
    exit;
}

$account_id = $_SESSION['account_id'];

try {
    // Prepare the SQL statement using PDO
    $stmt = $pdo->prepare("
        SELECT 
            id, email, alt_email, contact_no, 
            first_name, middle_name, last_name, sex, civil_status, nationality, address
        FROM users
        WHERE account_id = :account_id
    ");

    // Execute the query securely
    $stmt->execute([":account_id" => $account_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Format the response
        $response = [
            "success" => true,
            "user" => [
                "id" => $user['id'],
                "email" => $user['email'],
                "alt_email" => $user['alt_email'],
                "contact_number" => $user['contact_no'],
                "name" => [
                    "first_name" => $user['first_name'],
                    "middle_name" => $user['middle_name'],
                    "last_name" => $user['last_name']
                ],
                "sex" => $user['sex'],
                "civil_status" => $user['civil_status'],
                "nationality" => $user['nationality'],
                "address" => $user['address'] // Keeping address as a single field
            ]
        ];
    } else {
        $response = ["success" => false, "message" => "User not found."];
    }

    echo json_encode($response);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
}
