<?php

function validateDocuments($files)
{
    $errors = [];
    $allowedTypes = ["application/pdf", "application/vnd.openxmlformats-officedocument.wordprocessingml.document", "image/jpeg", "image/png"];
    $maxFileSize = 5 * 1024 * 1024; // 5MB

    // Validate Proof of Residency
    if (!isset($files['proof_residency']) || $files['proof_residency']['error'] !== UPLOAD_ERR_OK) {
        $errors[] = "Proof of Residency file is required.";
    } else {
        $file = $files['proof_residency'];
        if (!in_array($file['type'], $allowedTypes)) {
            $errors[] = "Proof of Residency must be a PDF, DOCX, JPG, or PNG.";
        }
        if ($file['size'] > $maxFileSize) {
            $errors[] = "Proof of Residency file must not exceed 5MB.";
        }
    }

    // Validate Valid ID File
    if (!isset($files['valid_id_file']) || $files['valid_id_file']['error'] !== UPLOAD_ERR_OK) {
        $errors[] = "Valid ID file is required.";
    } else {
        $file = $files['valid_id_file'];
        if (!in_array($file['type'], $allowedTypes)) {
            $errors[] = "Valid ID must be a PDF, DOCX, JPG, or PNG.";
        }
        if ($file['size'] > $maxFileSize) {
            $errors[] = "Valid ID file must not exceed 5MB.";
        }
    }

    return $errors;
}
