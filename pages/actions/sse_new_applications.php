<?php
set_time_limit(0);
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
header('Connection: keep-alive');

require_once '../../includes/config.php';

function streamAllEvents($pdo)
{
    $applications = [
        'new_stall_app_count' => "application_type = 'stall'",
        'new_transfer_app_count' => "application_type IN ('stall transfer', 'stall succession')",
        'new_extension_app_count' => "application_type = 'stall extension'",
        'new_helper_app_count' => "application_type = 'helper'"
    ];

    while (true) {
        $toShowMarketAppBadge = false;
        $toShowViolationBadge = false;
        $toShowVendorAppBadge = false;
        $toShowPaymentBadge = false;

        // Market Application Events
        foreach ($applications as $eventName => $condition) {
            $stmtMarket = $pdo->query("SELECT COUNT(*) AS count FROM applications WHERE {$condition} AND status = 'Submitted'");
            $count = (int) $stmtMarket->fetchColumn();

            if ($count >= 1) {
                $toShowMarketAppBadge = true;
            }

            sendSSE($eventName, $count);
        }

        sendSSE('market_app_badge', $toShowMarketAppBadge ? 'true' : 'false');

        // Violation Event
        $stmtViolation = $pdo->query("SELECT COUNT(*) AS count FROM violations WHERE status = 'Pending'");
        $violationCount = (int) $stmtViolation->fetchColumn();

        if ($violationCount >= 1) {
            $toShowViolationBadge = true;
        }

        sendSSE('violation_badge', $toShowViolationBadge ? 'true' : 'false');

        // Vendor Applcation Event
        $stmtVendor = $pdo->query("SELECT COUNT(*) AS count FROM vendors_application WHERE application_status = 'Pending'");
        $vendorAppCount = (int) $stmtVendor->fetchColumn();

        if ($vendorAppCount >= 1) {
            $toShowVendorAppBadge = true;
        }

        sendSSE('vendor_app_badge', $toShowVendorAppBadge ? 'true' : 'false');

        // Payment Event
        $stmtPayment = $pdo->query("SELECT COUNT(*) AS count FROM payments WHERE payment_status = 'Pending'");
        $paymentCount = (int) $stmtPayment->fetchColumn();

        if ($paymentCount >= 1) {
            $toShowPaymentBadge = true;
        }

        sendSSE('payment_badge', $toShowPaymentBadge ? 'true' : 'false');

        // Inspection Event
        $stmtInspection = $pdo->query("SELECT COUNT(*) AS count FROM applications WHERE inspection_status = 'Pending'");
        $inspectionCount = (int) $stmtInspection->fetchColumn();

        if ($inspectionCount >= 1) {
            $toShowInspectionBadge = true;
        }

        sendSSE('inspection_badge', $toShowInspectionBadge ? 'true' : 'false');

        ob_flush();
        flush();
        sleep(5);
    }
}

function sendSSE($event, $data)
{
    echo "event: {$event}\n";
    echo "data: {$data}\n\n";
}

// Start streaming
streamAllEvents($pdo);
