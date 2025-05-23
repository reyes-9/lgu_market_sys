<?php
session_start();
require_once '../../includes/config.php';
require_once '../../includes/session.php';
require_once 'get_user_id.php';

$account_id = $_SESSION['account_id'];
$user_id = getUserIdByAccountId($pdo, $account_id);

try {
    $stmt_user = $pdo->prepare("SELECT CONCAT_WS(' ',
                                    last_name,
                                CASE 
                                  WHEN LOWER(TRIM(middle_name)) = 'n/a' THEN NULL
                                  ELSE middle_name
                                END,
                                    first_name
                                ) AS name,
                                            email,
                                            address,
                                            contact_no 
                                FROM users WHERE account_id = :account_id");
    $stmt_user->execute([':account_id' => $account_id]);
    $users = $stmt_user->fetchAll(PDO::FETCH_ASSOC);

    $stmt_stall = $pdo->prepare("
            SELECT
                    s.id, 
                    s.stall_number, 
                    s.rental_fee, 
                    s.stall_size, 
                    s.section_id,
                    s.market_id,
                    s.user_id,
                    sec.section_name AS section_name, 
                    m.market_name AS market_name,
                    es.expiration_date AS stall_expiration_date,
                    ex.expiration_date AS extension_expiration_date,
          
                    CONCAT_WS(' ',
                                    h.first_name,
                                CASE 
                                  WHEN LOWER(TRIM(h.middle_name)) = 'N/A' THEN NULL
                                  ELSE h.middle_name
                                END,
                                    h.last_name
                                ) AS helper_name
            FROM stalls s
            JOIN sections sec ON s.section_id = sec.id
            JOIN market_locations m ON s.market_id = m.id
            LEFT JOIN helpers h ON h.stall_id = s.id 
            LEFT JOIN extensions ext ON s.id = ext.stall_id AND ext.status = 'active'
            LEFT JOIN expiration_dates es ON s.id = es.reference_id AND es.type = 'stall'
            LEFT JOIN expiration_dates ex ON ext.id = ex.reference_id AND ex.type = 'extension'
            WHERE s.user_id = :user_id
        ");

    $stmt_stall->execute([':user_id' => $user_id]);
    $stall = $stmt_stall->fetchAll(PDO::FETCH_ASSOC);

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
    header('Content-Type: application/json', true, 500);
    echo json_encode([
        'error' => 'Database error occurred: ' . $e->getMessage()
    ]);
} catch (Exception $e) {
    header('Content-Type: application/json', true, 500);
    echo json_encode([
        'error' => 'An error occurred: ' . $e->getMessage()
    ]);
}

exit();

// FUNCTIONS
function checkAccountExistence($pdo, $account_id)
{
    // Check if the account_id already exists in the users table
    $stmt = $pdo->prepare("SELECT account_id FROM users WHERE account_id = :account_id");
    $stmt->execute([':account_id' => $account_id]);
    $accountExists = $stmt->fetchColumn();
    return $accountExists;
}


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

// function createProfile($name, $email, $birthdate, $address, $contact, $pdo, $account_id)
// {
//     $accountExists = checkAccountExistence($pdo, $account_id);

//     if ($accountExists) {
//         echo "Error: A profile already exists for this account.";
//         exit();
//     }

//     $stmt = $pdo->prepare("INSERT INTO users (account_id, name, email, birthdate, address, contact) VALUES (:account_id, :name, :email, :birthdate, :address, :contact)");
//     if ($stmt->execute([':account_id' => $account_id, ':name' => $name, ':email' => $email, ':birthdate' => $birthdate, ':address' => $address, ':contact' => $contact])) {
//         echo "Profile created successfully.";
//         header('Location: profile.php');
//         exit();
//     } else {
//         echo "Error: Profile creation failed.";
//     }
// }

// function editProfile($name, $bio, $pdo, $account_id)
// {
//     $accountExists = checkAccountExistence($pdo, $account_id);

//     echo $account_id;

//     if (!$accountExists) {
//         echo "Error: A profile does not exists for this account.";
//         exit();
//     }

//     $stmt = $pdo->prepare("UPDATE users SET name = :name, bio = :bio WHERE account_id = :account_id");
//     if ($stmt->execute([':account_id' => $account_id, ':name' => $name, ':bio' => $bio])) {
//         echo "Profile updated successfully.";
//         header('Location: edit.php');
//         exit();
//     } else {
//         echo "Error: Profile modification failed.";
//     }
// }
