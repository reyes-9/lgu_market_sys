<?php
require_once '../../includes/config.php'; // Adjust the path as needed
require_once '../../includes/session.php'; // Adjust the path as needed
include 'get_user_id.php'; // Adjust the path as needed

$account_id = $_SESSION['account_id'];
$user_id = getUserIdByAccountId($pdo, $account_id);

// Function to submit a complaint when the request count reaches 20
function submitGarbageComplaint($market_id, $pdo)
{
    try {
        // Retrieve market name from market_locations table using the market_id
        $stmt = $pdo->prepare("SELECT market_name FROM market_locations WHERE id = ?");
        $stmt->execute([$market_id]);
        $marketRow = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$marketRow) {
            return json_encode(["success" => false, "message" => "Market name not found for the given market ID."]);
        }

        $market_name = $marketRow['market_name'];

        // Prepare complaint data if request count is 20 or more
        $subject = "Garbage Collection Request Limit Reached for Market: $market_name (ID: $market_id)";
        $email = "info.publicmarketmonitoring@gmail.com"; // Admin or system email
        $description = "The request count for garbage collection for Market '$market_name' (ID: $market_id) has reached 20. Please take action.";

        // Prepare the complaint API data
        $apiData = [
            'subject' => $subject,
            'email' => $email,
            'description' => $description
        ];

        // Send the complaint via the API
        $apiUrl = 'https://solidwastemanagementsystem.lgu1.com/api/complaints';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($apiData));

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            // Error sending complaint
            return json_encode(["success" => false, "message" => "Error submitting complaint: " . curl_error($ch)]);
        }

        curl_close($ch);

        // Handle API response
        $apiResponse = json_decode($response, true);
        if (isset($apiResponse['success']) && $apiResponse['success'] === true) {
            // Return complaint details from the API response
            $complaint = $apiResponse['complaint'];
            return json_encode([
                "success" => true,
                "message" => "Garbage request submitted successfully. Complaint submitted to the system.",
                "complaint" => [
                    "id" => $complaint['id'],
                    "subject" => $complaint['subject'],
                    "email" => $complaint['email'],
                    "description" => $complaint['description'],
                    "created_at" => $complaint['created_at'],
                    "updated_at" => $complaint['updated_at']
                ]
            ]);
        } else {
            return json_encode(["success" => false, "message" => "Failed to submit complaint via API."]);
        }
    } catch (PDOException $e) {
        return json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $market_id = trim($_POST["market_id"]);
    if (empty($market_id)) {
        echo json_encode(["success" => false, "message" => "Market ID is required."]);
        exit;
    }

    try {
        // Get today's date
        $today = date('Y-m-d'); // Current date in Y-m-d format

        // Check if the user has already requested for the given market today
        $stmt = $pdo->prepare("SELECT id FROM garbage_requests WHERE market_id = ? AND user_id = ? AND DATE(request_date) = ?");
        $stmt->execute([$market_id, $user_id, $today]);
        $userRequestToday = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($userRequestToday) {
            // User has already made a request today for this market.
            echo json_encode(["success" => false, "message" => "You have already submitted a request today."]);
            exit;
        }

        // Check if the market ID already exists in the garbage_requests table
        $stmt = $pdo->prepare("SELECT request_count FROM garbage_requests WHERE market_id = ?");
        $stmt->execute([$market_id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            // If market ID exists, increment request_count.
            $stmt = $pdo->prepare("UPDATE garbage_requests SET request_count = request_count + 1 WHERE market_id = ?");
            $stmt->execute([$market_id]);
        } else {
            // If market ID does not exist, insert a new row with the initial request_count.
            $stmt = $pdo->prepare("INSERT INTO garbage_requests (market_id, user_id, request_count, status, request_date) VALUES (?, ?, 1, 'Pending', NOW())");
            $stmt->execute([$market_id, $user_id]);
        }

        // Check if the request count has reached 20
        if ($row['request_count'] + 1 >= 20) {
            // Call the function to submit the complaint if request count reaches 20
            $response = submitGarbageComplaint($market_id, $pdo);
            echo $response;
            exit;
        }

        echo json_encode(["success" => true, "message" => "Garbage request submitted successfully."]);
    } catch (PDOException $e) {
        echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
    }
}
