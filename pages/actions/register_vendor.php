<?php
require_once '../../includes/config.php';
session_start();
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

// Sanitize and validate inputs
$accountId = $_SESSION['user_id'];
$first_name = htmlspecialchars(trim($_POST['first_name']));
$middle_name = htmlspecialchars(trim($_POST['middle_name']));
$last_name = htmlspecialchars(trim($_POST['last_name']));
$email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) ? $_POST['email'] : null;
$altEmail = filter_var($_POST['alt_email'], FILTER_VALIDATE_EMAIL) ? $_POST['alt_email'] : null;
$contact_no = preg_match('/^\d{11}$/', $_POST['contact_no']) ? $_POST['contact_no'] : null;
$zipcode = preg_match('/^\d{4}$/', $_POST['zip_code']) ? $_POST['zip_code'] : null;
$house_no = htmlspecialchars(trim($_POST['house_no']));
$street = htmlspecialchars(trim($_POST['street']));
$subdivision = htmlspecialchars(trim($_POST['subdivision']));
$province = htmlspecialchars(trim($_POST['province']));
$city = htmlspecialchars(trim($_POST['city']));
$barangay = htmlspecialchars(trim($_POST['barangay']));
$sex = htmlspecialchars(trim($_POST['sex']));
$civil_status = htmlspecialchars(trim($_POST['civil_status']));
$nationality = htmlspecialchars(trim($_POST['nationality']));

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

// Check if email already exists in vendors table
$stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email OR alt_email = :altEmail");
$stmt->execute(['email' => $email, 'altEmail' => $altEmail]);

if ($stmt->fetch()) {
    http_response_code(409);  // Conflict (email already exists)
    echo json_encode(["success" => false, "message" => "Email already exists as a vendor."]);
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

// Begin transaction
$pdo->beginTransaction();

try {
    // Insert into users table
    $stmt = $pdo->prepare("INSERT INTO users (account_id, first_name, middle_name, last_name, email, alt_email, contact_no, address, sex, civil_status, nationality, created_at) 
                           VALUES (:accountId, :first_name, :middle_name, :last_name, :email, :altEmail, :contact_no, :address, :sex, :civil_status, :nationality, NOW())");
    $stmt->execute([
        'accountId' => $accountId,
        'first_name' => $first_name,
        'middle_name' => $middle_name,
        'last_name' => $last_name,
        'email' => $email,
        'altEmail' => $altEmail,
        'contact_no' => $contact_no,
        'address' => $address,
        'sex' => $sex,
        'civil_status' => $civil_status,
        'nationality' => $nationality,
    ]);

    // Update user_type in accounts table
    $stmt = $pdo->prepare("UPDATE accounts SET user_type = 'vendor' WHERE id = :accountId");
    $stmt->execute(['accountId' => $accountId]);

    // Commit transaction
    $pdo->commit();

    unset($_SESSION['csrf_token']);
    http_response_code(201);  // Created
    echo json_encode(["success" => true, "message" => "Registration successful! You are now a vendor."]);
} catch (Exception $e) {
    $pdo->rollBack();
    http_response_code(500);  // Internal Server Error
    echo json_encode(["success" => false, "message" => "Registration failed: " . $e->getMessage()]);
}
