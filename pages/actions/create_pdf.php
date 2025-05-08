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

    $dataSet = $payload;
    $headers = $dataSet['headers'] ?? [];
    $rows = $dataSet['rows'] ?? [];

    if (empty($headers) || empty($rows)) {
        http_response_code(422);
        echo json_encode(['success' => false, 'message' => 'Headers or rows are missing.']);
        exit;
    }

    $postKey = isset($_POST['postKey']) ? $_POST['postKey'] : null;
    $title = isset($_POST['title']) ? $_POST['title'] : null;

    if (!$postKey) {
        echo json_encode(['success' => false, 'message' => 'Invalid data provided.']);
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
            // This assumes generatePDFReport returns [$pdf, $html]
            list($pdf, $html) = generatePDFReport($rows, $headers, $summaryHeaders, $title);
            break;
        case "vendorMasterListReport":
            $summaryHeaders = [
                'Title',
                'Market Name',
                'Total Vendors'
            ];
            // This assumes generatePDFReport returns [$pdf, $html]
            list($pdf, $html) = generatePDFReport($rows, $headers, $summaryHeaders, $title);
            break;

        default:
            echo json_encode(['success' => false, 'message' => 'Unknown report key.']);
            exit;
    }

    // Output the PDF
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

function generatePDFReport($rows, $headers, $summaryHeaders, $reportTitle)
{
    // Initialize TCPDF
    $pdf = new TCPDF();
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('LGU1 Market System');
    $pdf->SetTitle($reportTitle);
    $pdf->SetMargins(10, 20, 10);
    $pdf->AddPage();
    $pdf->SetFont('helvetica', '', 12);

    $html = '';

    // Title
    $title = isset($rows[0]['title']) ? htmlspecialchars($rows[0]['title']) : 'Market Report';
    $html .= "<h2 align=\"center\">$title</h2>";

    // ===== Summary Table =====
    $html .= '<table border="1" cellpadding="10" cellspacing="0">';
    $html .= "<tr bgcolor=\"#e0e0e0\">";
    foreach ($summaryHeaders as $header) {
        $html .= "<th><strong>" . htmlspecialchars($header) . '</strong></th>';
    }
    $html .= "</tr>";

    $html .= "<tr>";
    foreach ($summaryHeaders as $header) {
        $key = strtolower(str_replace(' ', '_', $header));
        $value = isset($rows[0][$key]) ? $rows[0][$key] : '';
        $html .= "<td>" . htmlspecialchars($value) . "</td>";
    }
    $html .= "</tr>";
    $html .= "</table><br><br><br><br>";

    // ===== Detailed Table =====
    $html .= '<table border="1" cellpadding="5" cellspacing="0">';
    $html .= "<tr bgcolor=\"#e0e0e0\">";
    foreach ($headers as $header) {
        if (in_array($header, $summaryHeaders)) continue;
        $html .= "<th><strong>" . htmlspecialchars($header) . '</strong></th>';
    }
    $html .= "</tr>";

    foreach ($rows as $row) {
        $html .= "<tr>";
        foreach ($headers as $header) {
            if (in_array($header, $summaryHeaders)) continue;
            $key = strtolower(str_replace(' ', '_', $header));
            $value = isset($row[$key]) ? $row[$key] : '';
            $html .= "<td>" . htmlspecialchars($value) . "</td>";
        }
        $html .= "</tr>";
    }

    $html .= "</table>";


    return [$pdf, $html];
}
