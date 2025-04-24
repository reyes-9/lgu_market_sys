<?php

// Check if the POST request contains the "violators" parameter
if (!isset($_POST['violators']) || empty($_POST['violators'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'No violators data received.']);
    exit();
}

// Decode the JSON data
$suspendedAndTerminatedUsers = json_decode($_POST['violators'], true);

// Check if decoding was successful
if ($suspendedAndTerminatedUsers === null) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Invalid data format received.']);
    exit();
}

// Call the export function with the data, file name, and column headers
exportToCSV($suspendedAndTerminatedUsers, 'terminated_users_' . date('Y-m-d') . '.csv', [
    'User ID',
    'Name',
    'Status',
    'Reason - Date Issued',
    'Date of Termination',
    'Market',
    'Stall Number'
]);



/**
 * Function to export data to a CSV file
 * 
 * @param array $data         The data to export
 * @param string $fileName    The name of the CSV file
 * @param array $headers      The headers for the CSV file
 */
function exportToCSV($data, $fileName, $headers)
{
    // Set headers to prompt the user to download the file
    header('Content-Type: application/csv');
    header('Content-Disposition: attachment; filename="' . $fileName . '"');

    // Open the output stream for writing the CSV data
    $output = fopen('php://output', 'w');

    // Add CSV column headers
    fputcsv($output, $headers);

    // Write the data to the CSV
    foreach ($data as $item) {
        $row = [];
        foreach ($headers as $header) {
            // If the key exists in the data array, add it to the row
            $row[] = isset($item[strtolower(str_replace(' ', '_', $header))]) ? cleanHtml($item[strtolower(str_replace(' ', '_', $header))]) : '';
        }
        fputcsv($output, $row);
    }

    // Close the output stream
    fclose($output);
    exit();
}

/**
 * Function to clean up HTML entities and tags
 * 
 * @param string $input  The input string to clean
 * @return string        The cleaned string
 */
function cleanHtml($input)
{
    // Decode HTML entities and strip HTML tags
    return strip_tags(html_entity_decode($input, ENT_QUOTES, 'UTF-8'));
}
