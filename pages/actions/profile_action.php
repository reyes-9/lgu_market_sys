<?php
session_start();
require_once '../../includes/config.php';

$account_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $_SESSION['error'] = "Invalid request. Please try again.";
        header('Location: profile.php');
        exit();
    }

    if (isset($_POST['action'])) {

        $name = htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');
        $email = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');
        $birthdate = htmlspecialchars($_POST['birthdate'], ENT_QUOTES, 'UTF-8');
        $address = htmlspecialchars($_POST['address'], ENT_QUOTES, 'UTF-8');
        $contact = htmlspecialchars($_POST['contact'], ENT_QUOTES, 'UTF-8');
        $bio = htmlspecialchars($_POST['bio'], ENT_QUOTES, 'UTF-8');

        switch ($_POST['action']) {
            case 'create':
                createProfile($name, $email, $birthdate, $address, $contact, $pdo, $account_id);
                break;
            case 'edit':
                editProfile($name, $bio, $pdo, $account_id);
                break;
            case 'transfer':
                break;
            case 'extend':
                break;
            case 'helper':
                break;
        }
    }
} else {
    try {
        $stmt_user = $pdo->prepare("SELECT name, bio, email, birthdate, address, contact FROM profiles WHERE account_id = :account_id");
        $stmt_user->execute([':account_id' => $account_id]);
        $users = $stmt_user->fetchAll(PDO::FETCH_ASSOC);

        $stmt_stall = $pdo->prepare("
            SELECT 
                s.stall_number, 
                s.rental_fee, 
                s.stall_size, 
                sec.section_name AS section_name, 
                m.market_name AS market_name 
            FROM stalls s
            JOIN sections sec ON s.section_id = sec.id
            JOIN market_locations m ON s.market_id = m.id
            WHERE s.account_id = :account_id
        ");

        $stmt_stall->execute([':account_id' => $account_id]);
        $stall = $stmt_stall->fetchAll(PDO::FETCH_ASSOC);

        // Prepare response
        header('Content-Type: application/json');

        if (empty($stall)) {
            echo json_encode([
                'user' => $users,
                'message' => [["message" => "You do not have any stalls associated with your account."]]
            ]);
        } else {
            echo json_encode([
                'user' => $users,
                'stalls' => $stall
            ]);
        }
    } catch (PDOException $e) {
        // Handle database error
        header('Content-Type: application/json', true, 500);
        echo json_encode([
            'error' => 'Database error occurred: ' . $e->getMessage()
        ]);
    } catch (Exception $e) {
        // Handle other types of errors
        header('Content-Type: application/json', true, 500);
        echo json_encode([
            'error' => 'An error occurred: ' . $e->getMessage()
        ]);
    }

    exit();
}



// FUNCTIONS
function checkAccountExistence($pdo, $account_id)
{
    // Check if the account_id already exists in the profiles table
    $stmt = $pdo->prepare("SELECT account_id FROM profiles WHERE account_id = :account_id");
    $stmt->execute([':account_id' => $account_id]);
    $accountExists = $stmt->fetchColumn();
    return $accountExists;
}

function createProfile($name, $email, $birthdate, $address, $contact, $pdo, $account_id)
{
    $accountExists = checkAccountExistence($pdo, $account_id);

    if ($accountExists) {
        echo "Error: A profile already exists for this account.";
        exit();
    }

    $stmt = $pdo->prepare("INSERT INTO profiles (account_id, name, email, birthdate, address, contact) VALUES (:account_id, :name, :email, :birthdate, :address, :contact)");
    if ($stmt->execute([':account_id' => $account_id, ':name' => $name, ':email' => $email, ':birthdate' => $birthdate, ':address' => $address, ':contact' => $contact])) {
        echo "Profile created successfully.";
        header('Location: profile.php');
        exit();
    } else {
        echo "Error: Profile creation failed.";
    }
}

function editProfile($name, $bio, $pdo, $account_id)
{
    $accountExists = checkAccountExistence($pdo, $account_id);

    echo $account_id;

    if (!$accountExists) {
        echo "Error: A profile does not exists for this account.";
        exit();
    }

    $stmt = $pdo->prepare("UPDATE profiles SET name = :name, bio = :bio WHERE account_id = :account_id");
    if ($stmt->execute([':account_id' => $account_id, ':name' => $name, ':bio' => $bio])) {
        echo "Profile updated successfully.";
        header('Location: edit.php');
        exit();
    } else {
        echo "Error: Profile modification failed.";
    }
}


function getSection($pdo, $section_id) {}
function getStall($pdo, $stall_id)
{

    // Prepare the query
    $stmt = $pdo->prepare("SELECT section_id, stall_number, rental_fee, stall_size FROM stalls WHERE stall_id = :stall_id");
    $stmt->bindParam(':stall_id', $stall_id, PDO::PARAM_INT);

    $stmt->execute();
    $stalls = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($stalls) {

        return $stalls;
    } else {
   
        return false;
    }
}




function stallExtension() {}

function transferStall() {}

function addHelper() {}
