<?php
require_once '../../vendor/autoload.php';

header('Content-Type: application/json');

try {
    // Check if POST data is present
    if (!isset($_POST['data'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'No data provided.']);
        exit;
    }

    $payload = json_decode($_POST['data'], true);
    if (!is_array($payload) || !isset($payload['headers']) || !isset($payload['rows'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid data structure.']);
        exit;
    }

    $headers = $payload['headers'];
    $rows = $payload['rows'];

    if (empty($headers) || empty($rows)) {
        http_response_code(422);
        echo json_encode(['success' => false, 'message' => 'Headers or rows are missing.']);
        exit;
    }

    $postKey = $_POST['postKey'] ?? null;
    $title = $_POST['title'] ?? null;

    if (!$postKey) {
        echo json_encode(['success' => false, 'message' => 'Invalid data provided.']);
        exit;
    }

    switch ($postKey) {
        case "stallUtilizationReport":
            $summaryHeaders = [
                'Title',
                'Market Name',
                'Total Stalls',
                'Occupied Stalls',
                'Occupancy Rate',
                'Availability Rate'
            ];
            list($pdf, $html) = generateCSVReport($rows, $headers, $summaryHeaders, $title);
            break;
        case "vendorMasterListReport":
            $summaryHeaders = [
                'Title',
                'Market Name',
                'Total Vendors'
            ];
            list($pdf, $html) = generateCSVReport($rows, $headers, $summaryHeaders, $title);
            break;

        case "violationSummaryReport":
            $summaryHeaders = [
                'Title',
                'Market Name',
                'Violation Count',
                'Pending Count',
                'Resolved Count',
                'Escalated Count',
                'Vendor Count',
                'Most Common Violation'
            ];
            list($pdf, $html) = generateCSVReport($rows, $headers, $summaryHeaders, $title);
            break;
        case "stallTransferRequestsReport":
            $summaryHeaders = [
                'Title',
                'Market Name',
                'Total Transfer Requests',
                'Pending Count',
                'Approved Count',
                'Rejected Count'
            ];
            list($pdf, $html) = generateCSVReport($rows, $headers, $summaryHeaders, $title);
            break;
        case "stallExtensionRequestsReport":
            $summaryHeaders = [
                'Title',
                'Market Name',
                'Total Extension Requests',
                'Pending Count',
                'Approved Count',
                'Rejected Count'
            ];
            list($pdf, $html) = generateCSVReport($rows, $headers, $summaryHeaders, $title);
            break;


        default:
            echo json_encode(['success' => false, 'message' => 'Unknown report key.']);
            exit;
    }

    ob_clean(); // Clean any buffered output
    $pdf->writeHTML($html, true, false, true, false, '');
    $pdf->Output('reports.pdf', 'D'); // Force download
    exit;
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Server error generating PDF.',
        'error' => $e->getMessage()
    ]);
    exit;
}

/**
 * Normalize a list of headers: trim whitespace and convert to lowercase.
 */
function normalizeHeaders(array $headers): array
{
    return array_map(fn($h) => strtolower(trim($h)), $headers);
}
function generateCSVReport($rows, $headers, $summaryHeaders, $reportTitle)
{
    // Set CSV headers for download
    header('Content-Type: text/csv');
    header("Content-Disposition: attachment; filename=\"$reportTitle.csv\"");

    $output = fopen('php://output', 'w');

    if (!$output) {
        throw new Exception('Unable to open output stream.');
    }

    // ===== Write Summary Section =====
    fputcsv($output, [$reportTitle]);
    fputcsv($output, []); // Empty row

    // Header row for summary
    fputcsv($output, $summaryHeaders);

    // Data row for summary
    $summaryRow = [];
    foreach ($summaryHeaders as $header) {
        $key = strtolower(str_replace(' ', '_', $header));
        $summaryRow[] = $rows[0][$key] ?? '';
    }
    fputcsv($output, $summaryRow);

    fputcsv($output, []); // Empty row for spacing

    // ===== Write Detailed Section =====
    $normalizedSummaryHeaders = normalizeHeaders($summaryHeaders);
    $filteredHeaders = array_filter($headers, function ($header) use ($normalizedSummaryHeaders) {
        $normalizedHeader = strtolower(trim($header));
        return !in_array($normalizedHeader, $normalizedSummaryHeaders);
    });

    // Header row for detailed data
    fputcsv($output, $filteredHeaders);

    // Data rows
    foreach ($rows as $row) {
        $dataRow = [];
        foreach ($filteredHeaders as $header) {
            $key = strtolower(str_replace(' ', '_', $header));
            $dataRow[] = $row[$key] ?? '';
        }
        fputcsv($output, $dataRow);
    }

    fclose($output);
    exit;
}
