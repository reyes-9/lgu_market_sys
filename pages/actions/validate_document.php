<?php
require_once '../../includes/session.php';
function validateDocuments($files)
{
    $errors = [];
    $allowedTypes = ["image/jpeg", "image/png"];
    $maxFileSize = 5 * 1024 * 1024; // 5MB

    // Loop through each uploaded file
    foreach ($files as $key => $file) {
        if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
            $errors[] = ucfirst(str_replace('_', ' ', $key)) . " file is required.";
            continue;
        }

        // Validate file type
        if (!in_array($file['type'], $allowedTypes)) {
            $errors[] = ucfirst(str_replace('_', ' ', $key)) . " must be a JPG or PNG.";
        }

        // Validate file size
        if ($file['size'] > $maxFileSize) {
            $errors[] = ucfirst(str_replace('_', ' ', $key)) . " must not exceed 5MB.";
        }
    }

    return $errors;
}
