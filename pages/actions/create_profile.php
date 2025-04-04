<?php
require_once '../../includes/config.php';
require_once '../../includes/session.php';
header('Content-Type: application/json');

// CSRF Token Validation
if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    $response['message'] = "Invalid request. Please try again.";
    http_response_code(400);  // Bad Request
    echo json_encode($response);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);  // Method Not Allowed
    echo json_encode(["success" => false, "message" => "Invalid request method."]);
    exit;
}

// Validate required fields
$requiredFields = ['first_name', 'middle_name', 'last_name', 'email', 'alt_email', 'contact_no', 'zip_code', 'house_no', 'street', 'subdivision', 'province', 'city', 'barangay'];
foreach ($requiredFields as $field) {
    if (empty($_POST[$field])) {
        http_response_code(400);  // Bad Request
        echo json_encode(["success" => false, "message" => "All fields are required."]);
        exit;
    }
}

// Sanitize & Validate Inputs
$accountId = $_SESSION['account_id'] ?? 0;

$first_name = properCase(htmlspecialchars(trim($_POST['first_name'] ?? ''), ENT_QUOTES, 'UTF-8'));
$middle_name = properCase(htmlspecialchars(trim($_POST['middle_name'] ?? ''), ENT_QUOTES, 'UTF-8'));
$last_name = properCase(htmlspecialchars(trim($_POST['last_name'] ?? ''), ENT_QUOTES, 'UTF-8'));

$email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL) ? trim($_POST['email']) : null;
$altEmail = filter_var($_POST['alt_email'] ?? '', FILTER_VALIDATE_EMAIL) ? trim($_POST['alt_email']) : null;

$contact_no = isset($_POST['contact_no']) && preg_match('/^\d{11}$/', $_POST['contact_no'])
    ? trim($_POST['contact_no'])
    : null;

$zipcode = isset($_POST['zip_code']) && preg_match('/^\d{4}$/', $_POST['zip_code'])
    ? trim($_POST['zip_code'])
    : null;

// Sanitize Address Fields
$house_no = htmlspecialchars(trim($_POST['house_no'] ?? ''), ENT_QUOTES, 'UTF-8');
$street = htmlspecialchars(trim($_POST['street'] ?? ''), ENT_QUOTES, 'UTF-8');
$subdivision = htmlspecialchars(trim($_POST['subdivision'] ?? ''), ENT_QUOTES, 'UTF-8');
$province = htmlspecialchars(trim($_POST['province'] ?? ''), ENT_QUOTES, 'UTF-8');
$city = htmlspecialchars(trim($_POST['city'] ?? ''), ENT_QUOTES, 'UTF-8');
$barangay = htmlspecialchars(trim($_POST['barangay'] ?? ''), ENT_QUOTES, 'UTF-8');

$sex = htmlspecialchars(trim($_POST['sex'] ?? ''), ENT_QUOTES, 'UTF-8');
$civil_status = htmlspecialchars(trim($_POST['civil_status'] ?? ''), ENT_QUOTES, 'UTF-8');
$nationality = properCase(htmlspecialchars(trim($_POST['nationality'] ?? ''), ENT_QUOTES, 'UTF-8'));

// Check if required fields are valid
if (!$email || !$altEmail || !$contact_no || !$zipcode) {
    http_response_code(400);  // Bad Request
    echo json_encode(["success" => false, "message" => "Invalid input format."]);
    exit;
}

// Concatenate Address
$addressParts = [
    $house_no,
    $street,
    $subdivision,
    $barangay,
    $city,
    $province,
    $zipcode
];

// Remove empty values and join with a comma
$address = implode(', ', array_filter($addressParts));

// Check if email already exists in users table
$stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email OR alt_email = :altEmail");
$stmt->execute(['email' => $email, 'altEmail' => $altEmail]);

if ($stmt->fetch()) {
    http_response_code(409);  // Conflict (email already exists)
    echo json_encode(["success" => false, "message" => "Email already used."]);
    exit;
}

// Check if account exists in accounts table
$stmt = $pdo->prepare("SELECT id FROM accounts WHERE id = :accountId");
$stmt->execute(['accountId' => $accountId]);

if (!$stmt->fetch()) {
    http_response_code(404);  // Not Found (account not found)
    echo json_encode(["success" => false, "message" => "Account not found."]);
    exit;
}

// Check if account already has a vendor
$stmt = $pdo->prepare("SELECT id FROM users WHERE account_id = :accountId");
$stmt->execute(['accountId' => $accountId]);

if ($stmt->fetch()) {
    http_response_code(409);  // Conflict (vendor already exists for this account)
    echo json_encode(["success" => false, "message" => "This account is already registered as a vendor."]);
    exit;
}

// Begin transaction
$pdo->beginTransaction();

try {

    $application_status = "Pending";
    $stmt = $pdo->prepare("INSERT INTO vendors_application 
    (account_id, first_name, middle_name, last_name, email, alt_email, contact_no, sex, civil_status, nationality, address, application_status, application_date)
    VALUES 
    (:accountId, :first_name, :middle_name, :last_name, :email, :altEmail, :contact_no, :sex, :civil_status, :nationality, :address, :application_status, NOW())");

    $stmt->execute([
        'accountId' => $accountId,
        'first_name' => $first_name,
        'middle_name' => $middle_name,
        'last_name' => $last_name,
        'email' => $email,
        'altEmail' => $altEmail,
        'contact_no' => $contact_no,
        'sex' => $sex,
        'civil_status' => $civil_status,
        'nationality' => $nationality,
        'address' => $address,
        'application_status' => $application_status,
    ]);

    // Update user_type in accounts table
    $stmt = $pdo->prepare("UPDATE accounts SET user_type = 'Vendor' WHERE id = :accountId");
    $stmt->execute(['accountId' => $accountId]);

    // Commit transaction
    $pdo->commit();

    unset($_SESSION['csrf_token']);
    http_response_code(201);  // Created
    echo json_encode(["success" => true, "message" => "Registration successful! You are now a Vendor."]);
} catch (Exception $e) {
    $pdo->rollBack();
    http_response_code(500);  // Internal Server Error
    echo json_encode(["success" => false, "message" => "Registration failed: " . $e->getMessage()]);
}

function properCase($name)
{
    return preg_replace_callback("/\b[a-z']+\b/i", function ($match) {
        return ucfirst(strtolower($match[0]));
    }, trim($name));
}
