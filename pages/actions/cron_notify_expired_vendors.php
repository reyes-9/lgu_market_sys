<?php
require_once '../../includes/config.php';
require_once '../../includes/session.php';
require_once 'mailer.php';

$stallIds = [];
$extensionIds = [];
$violationIds = [];

$getReferenceIdQuery = "
    SELECT
        reference_id, type
    FROM      
        expiration_dates
    WHERE
        status = 'expired'
";

$stmt = $pdo->prepare($getReferenceIdQuery);
$stmt->execute();
$expiredRecords = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($expiredRecords as $record) {

    switch ($record['type']) {
        case 'stall':
            $stallIds[] = $record['reference_id'];
            break;

        case 'extension':
            $extensionIds[] = $record['reference_id'];
            break;

        case 'violation':
            $violationIds[] = $record['reference_id'];
            break;
    }
}

$violatorEmails = getViolatorsEmails($pdo, $violationIds);
$stallEmails = getStallEmails($pdo, $stallIds);
$extensionEmails = getExtensionEmails($pdo, $extensionIds);

$violatorEmails[] = 'reyes.nelson.panong@gmail.com';
$stallEmails[] = 'reyes.nelson.panong@gmail.com';
$extensionEmails[] = 'reyes.nelson.panong@gmail.com';


$results = [
    'violation' => sendEmailNotifications($violatorEmails, 'violation'),
    'stall'     => sendEmailNotifications($stallEmails, 'stall'),
    'extension' => sendEmailNotifications($extensionEmails, 'extension'),
];

// Optionally log or display failed attempts
foreach ($results as $type => $result) {
    if (!$result['success']) {
        echo "<strong>[$type] Some emails failed to send:</strong><br>";
        foreach ($result['errors'] as $error) {
            echo htmlspecialchars($error) . "<br>";
        }
    }
}


function sendEmailNotifications($emails, $type)
{
    $isAllSent = true;
    $errors = [];
    $subject = '';
    $body = '';
    $altBody = '';

    try {
        switch ($type) {
            case 'violation':
                $subject = "Action Required: Your Violation Has Expired and Been Escalated";
                $body = "
                    <html>
                        <body>
                            <p>Dear Vendor,</p>
                            <p><strong>We would like to inform you that your violation has expired and has been escalated to the next level.</strong> Please log in to your account to view more details and take any necessary actions.</p>
                            <p>If you have any questions, feel free to contact us at <a href='mailto:info@publicmarketmonitoringsystem.lgu1.com'>info@publicmarketmonitoringsystem.lgu1.com</a>.</p>
                            <br>
                            <p>Best regards,</p>
                            <p>Your Public Market Monitoring Team</p>
                        </body>
                    </html>
                ";
                $altBody = "Dear Vendor,\n\nWe would like to inform you that your violation has expired and has been escalated to the next level. Please log in to your account to view more details and take any necessary actions.\n\nIf you have any questions, feel free to contact us at info@publicmarketmonitoringsystem.lgu1.com.\n\nBest regards,\nYour Public Market Monitoring Team";
                break;

            case 'stall':
                $subject = "Notice: Your Stall Permit Has Expired";
                $body = "
                    <html>
                        <body>
                            <p>Dear Vendor,</p>
                            <p><strong>This is a reminder that your stall permit has expired.</strong> To avoid interruption of your business operations, please renew your permit as soon as possible by logging into your account.</p>
                            <p>If you need assistance, please reach out to us at <a href='mailto:info@publicmarketmonitoringsystem.lgu1.com'>info@publicmarketmonitoringsystem.lgu1.com</a>.</p>
                            <br>
                            <p>Thank you,</p>
                            <p>Public Market Monitoring Team</p>
                        </body>
                    </html>
                ";
                $altBody = "Dear Vendor,\n\nThis is a reminder that your stall permit has expired. To avoid interruption of your business operations, please renew your permit as soon as possible by logging into your account.\n\nIf you need assistance, please contact us at info@publicmarketmonitoringsystem.lgu1.com.\n\nThank you,\nPublic Market Monitoring Team";
                break;

            case 'extension':
                $subject = "Reminder: Your Stall Extension Has Expired";
                $body = "
                    <html>
                        <body>
                            <p>Dear Vendor,</p>
                            <p><strong>We are notifying you that the extension period for your stall has expired.</strong> Kindly review your account for more information and proceed with the necessary renewal if applicable.</p>
                            <p>If you have any questions or concerns, feel free to contact us at <a href='mailto:info@publicmarketmonitoringsystem.lgu1.com'>info@publicmarketmonitoringsystem.lgu1.com</a>.</p>
                            <br>
                            <p>Sincerely,</p>
                            <p>Public Market Monitoring Team</p>
                        </body>
                    </html>
                ";
                $altBody = "Dear Vendor,\n\nWe are notifying you that the extension period for your stall has expired. Kindly review your account for more information and proceed with the necessary renewal if applicable.\n\nIf you have any questions or concerns, contact us at info@publicmarketmonitoringsystem.lgu1.com.\n\nSincerely,\nPublic Market Monitoring Team";
                break;

            default:
                throw new Exception("Invalid notification type: $type");
        }

        foreach ($emails as $email) {
            $flag = sendEmail($email, $subject, $body, $altBody);

            if ($flag !== true) {
                $isAllSent = false;
                $errors[] = "[$type] Failed to send email to: $email";
                error_log(end($errors));
            }
        }
    } catch (Exception $e) {
        $isAllSent = false;
        $errors[] = "Exception in sendEmailNotifications(): " . $e->getMessage();
        error_log(end($errors));
    }

    return [
        'success' => $isAllSent,
        'errors' => $errors
    ];
}


function getViolatorsEmails($pdo, $violationIds)
{
    $getViolationUserEmailQuery = "
    SELECT DISTINCT u.email 
    FROM users u 
    JOIN violations v ON v.user_id = u.id 
    WHERE v.id = :violation_id
    ";

    $stmt = $pdo->prepare($getViolationUserEmailQuery);
    $emails = []; // Array to store emails and avoid duplicates

    foreach ($violationIds as $violation_id) {

        $stmt->bindParam(':violation_id', $violation_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result && !in_array($result['email'], $emails)) {
            // Store unique emails
            $emails[] = $result['email'];
        }
    }

    return $emails;
}

function getStallEmails($pdo, $stallIds)
{
    $getStallUserEmailQuery = "
    SELECT DISTINCT u.email 
    FROM users u 
    JOIN stalls s ON s.user_id = u.id 
    WHERE s.id = :stall_id
    ";

    $stmt = $pdo->prepare($getStallUserEmailQuery);
    $emails = []; // Array to store emails and avoid duplicates

    foreach ($stallIds as $stall_id) {

        $stmt->bindParam(':stall_id', $stall_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result && !in_array($result['email'], $emails)) {
            // Store unique emails
            $emails[] = $result['email'];
        }
    }

    return $emails;
}

function getExtensionEmails($pdo, $extensionIds)
{
    $getStallUserEmailQuery = "
        SELECT DISTINCT u.email 
        FROM users u 
        JOIN stalls s ON s.user_id = u.id 
        JOIN extensions e ON e.stall_id = s.id
        WHERE e.id = :extension_id
    ";

    $stmt = $pdo->prepare($getStallUserEmailQuery);
    $emails = [];

    foreach ($extensionIds as $extension_id) {
        $stmt->bindValue(':extension_id', $extension_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result && !in_array($result['email'], $emails)) {
            $emails[] = $result['email'];
        }
    }

    return $emails;
}
