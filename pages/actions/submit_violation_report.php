<?php
require_once "../../includes/config.php";
include "notifications.php";
include "log_admin_actions.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    try {
        // Validate required fields
        if (
            empty($_POST["vendor_first_name"]) || empty($_POST["vendor_middle_name"]) || empty($_POST["vendor_last_name"]) || empty($_POST["user_id"]) ||
            empty($_POST["stall"]) || empty($_POST["violation_type_id"]) || empty($_POST["violation_description"]) || empty($_POST["violation_date"])
        ) {
            throw new Exception("All fields are required.");
        }

        // Sanitize input
        $vendor_first_name = htmlspecialchars(trim(properCase($_POST["vendor_first_name"]))); // the vendor name is not used in the query
        $vendor_middle_name = htmlspecialchars(trim(properCase($_POST["vendor_middle_name"])));
        $vendor_last_name = htmlspecialchars(trim(properCase($_POST["vendor_last_name"])));
        $user_id = htmlspecialchars(trim($_POST["user_id"]));

        $stall_id = htmlspecialchars(trim($_POST["stall"]));
        $violation_date = htmlspecialchars(trim($_POST["violation_date"]));
        $violation_type_id = htmlspecialchars(trim($_POST["violation_type_id"]));
        $violation_description = htmlspecialchars(trim($_POST["violation_description"]));

        if (!checkUserInStalls($pdo, $user_id, $stall_id)) {
            throw new Exception("Vendor and stall doesn't match.");
        }

        $image_path = uploadEvidenceImage($_FILES["evidence_image"]);

        // Insert into database
        $stmt = $pdo->prepare("INSERT INTO violations (
            user_id, stall_id, violation_type_id, violation_description, 
            evidence_image_path, violation_date, created_at, updated_at 
        ) 
        VALUES (
            :user_id, :stall_id, :violation_type_id, :violation_description, 
            :evidence_image_path, :violation_date, NOW(), NOW()
        )");

        $stmt->execute([
            ':user_id'              => $user_id,
            ':stall_id'             => $stall_id,
            ':violation_type_id'    => $violation_type_id,
            ':violation_description' => $violation_description,
            ':evidence_image_path'  => $image_path,
            ':violation_date'       => $violation_date,
        ]);

        $violation_id = $pdo->lastInsertId();
        $account_id = getAccountID($pdo, $user_id);
        $admin_id = $_SESSION['admin_id'];

        if (!$account_id) {
            throw new Exception("Failed to get account ID.");
        }

        logAdminAction($pdo, $admin_id, "Issued Violation", "Issued Violation ID: $violation_id");
        insertNotification($pdo, $account_id, "Violation Issued", "A violation has been recorded under your stall. Please check your account for details.", 'unread');
        echo json_encode(["success" => true, "message" => "Violation reported successfully."]);
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request."]);
}


function properCase($name)
{
    return preg_replace_callback("/\b[a-z']+\b/i", function ($match) {
        return ucfirst(strtolower($match[0]));
    }, $name);
}

function checkUserInStalls($pdo, $user_id, $stall_id)
{
    $stmt = $pdo->prepare("SELECT id FROM stalls WHERE user_id = :user_id AND id = :stall_id");
    $stmt->execute([
        ':user_id' => $user_id,
        ':stall_id' => $stall_id
    ]);

    return $stmt->rowCount() > 0; // Returns true if user_id exists in stalls, false otherwise
}

function uploadEvidenceImage($file, $uploadDir = 'uploads/')
{
    if (!isset($file) || $file["error"] !== UPLOAD_ERR_OK) {
        throw new Exception("Error uploading image.");
    }

    $allowedTypes = ["image/jpeg", "image/png", "image/jpg", "image/gif"];

    if (!in_array($file["type"], $allowedTypes)) {
        throw new Exception("Invalid file type. Allowed: JPG, PNG, GIF.");
    }

    if ($file["size"] > 5 * 1024 * 1024) { // 5MB limit
        throw new Exception("Image size exceeds 5MB.");
    }

    // Generate unique filename
    $image_ext = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
    $image_name = "Evidence_" . time() . "_" . uniqid() . "." . $image_ext;

    // Ensure the uploads directory exists
    $uploadPath = __DIR__ . "/../../" . $uploadDir;
    if (!is_dir($uploadPath)) {
        mkdir($uploadPath, 0775, true);
    }

    $finalPath = $uploadPath . $image_name;

    // Move uploaded file
    if (!move_uploaded_file($file["tmp_name"], $finalPath)) {
        throw new Exception("Failed to save uploaded image.");
    }

    // Return the relative path for database storage
    return $uploadDir . $image_name;
}

function getAccountID($pdo, $user_id)
{
    $query = "SELECT account_id FROM users WHERE id = :user_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return $result ? $result['account_id'] : null;
}
