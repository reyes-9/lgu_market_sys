<?php

function getUserId($pdo, $account_id, $first_name, $middle_name, $last_name)
{
    $first_name = trim($first_name);
    $middle_name = trim($middle_name);
    $last_name = trim($last_name);

    $query = "SELECT id
              FROM users 
              WHERE account_id = :account_id AND first_name = :first_name AND middle_name = :middle_name AND last_name = :last_name";

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':account_id', $account_id, PDO::PARAM_INT);
    $stmt->bindParam(':first_name', $first_name, PDO::PARAM_STR);
    $stmt->bindParam(':middle_name', $middle_name, PDO::PARAM_STR);
    $stmt->bindParam(':last_name', $last_name, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$result) {
        var_dump("No user found for:", $account_id, $first_name, $middle_name, $last_name);
    }

    return  $result; // Returns user data or false if not found
}
