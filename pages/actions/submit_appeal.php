<?php
require_once "../../includes/config.php";
include "../../includes/session.php";
header("Content-Type: application/json");
error_reporting(E_ALL & ~E_WARNING);

if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    http_response_code(403); // Forbidden
    exit(json_encode(['success' => false, 'message' => "Invalid Request"]));
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405); // Method Not Allowed
    exit(json_encode(["success" => false, "message" => "Invalid Request"]));
}

$violation_id = $_POST["violation_id"] ?? null;
$result = checkForAppeals($pdo, $violation_id);
if (!$result["success"]) {
    unset($_SESSION['csrf_token']);
    http_response_code(409); // Conflict
    echo json_encode($result);
    exit;
}

try {
    $response = ["success" => false, "message" => ""];
    $appealText = htmlspecialchars(trim($_POST["appeal_text"] ?? ''), ENT_QUOTES, 'UTF-8');

    if (empty($appealText) || empty($violation_id)) {
        http_response_code(400); // Bad Request
        exit(json_encode(["success" => false, "message" => "Appeal text and Violation ID are required."]));
    }

    $appeal_document_path = null;
    if (!empty($_FILES["appeal_file"]["name"])) {
        $uploadResult = validateAndUploadFile($_FILES["appeal_file"]);

        if (!$uploadResult["success"]) {
            http_response_code(400); // Bad Request
            exit(json_encode(["message" => $uploadResult["message"]]));
        }

        $appeal_document_path = $uploadResult["filePath"];
    }

    $sql = "UPDATE violations SET 
                appeal_text = :appeal_text, 
                appeal_document_path = :appeal_document_path, 
                appeal_submitted_at = NOW()
            WHERE id = :violation_id";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":appeal_text", $appealText, PDO::PARAM_STR);
    $stmt->bindParam(":appeal_document_path", $appeal_document_path, PDO::PARAM_STR);
    $stmt->bindParam(":violation_id", $violation_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        unset($_SESSION['csrf_token']);
        http_response_code(200);
        echo json_encode(["success" => true, "message" => "Appeal submitted successfully."]);
    } else {
        unset($_SESSION['csrf_token']);
        http_response_code(500);
        echo json_encode(["success" => false, "message" => "Failed to submit appeal."]);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Error: " . $e->getMessage()]);
    unset($_SESSION['csrf_token']);
}


function validateAndUploadFile($file, $uploadDir = "../../uploads/", $maxSize = 5 * 1024 * 1024)
{
    $allowedTypes = [
        "image/jpeg",
        "image/png",
        "application/pdf",
        "application/msword",
        "application/vnd.openxmlformats-officedocument.wordprocessingml.document"
    ];

    if (empty($file["name"])) {
        return ["success" => false, "message" => "No file uploaded."];
    }

    // Validate file type
    $fileType = mime_content_type($file["tmp_name"]);
    if (!in_array($fileType, $allowedTypes)) {
        return ["success" => false, "message" => "Invalid file type."];
    }

    // Validate file size
    if ($file["size"] > $maxSize) {
        return ["success" => false, "message" => "File exceeds the 5MB limit."];
    }

    // Ensure upload directory exists
    if (!is_dir($uploadDir) && !mkdir($uploadDir, 0777, true)) {
        return ["success" => false, "message" => "Failed to create upload directory."];
    }

    // Secure file name: Add "Appeal_" and timestamp
    $fileExtension = pathinfo($file["name"], PATHINFO_EXTENSION);
    $fileName = "Appeal_" . time() . "_" . pathinfo($file["name"], PATHINFO_FILENAME) . "." . $fileExtension;
    $filePath = $uploadDir . $fileName;

    // Move uploaded file
    if (!move_uploaded_file($file["tmp_name"], $filePath)) {
        return ["success" => false, "message" => "Failed to upload file."];
    }

    return ["success" => true, "filePath" => $filePath];
}

function checkForAppeals($pdo, $violation_id)
{
    try {
        $sql = "SELECT appeal_text, appeal_document_path, appeal_submitted_at 
                FROM violations 
                WHERE id = :violation_id AND appeal_text IS NOT NULL";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":violation_id", $violation_id, PDO::PARAM_INT);
        $stmt->execute();

        $appeal = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($appeal) {
            return [
                "success" => false,
                "message" => "You cannot submit another appeal for this violation as one has already been recorded."
            ];
        } else {
            return [
                "success" => true,
            ];
        }
    } catch (Exception $e) {
        return [
            "success" => false,
            "message" => "Error: " . $e->getMessage()
        ];
    }
}
