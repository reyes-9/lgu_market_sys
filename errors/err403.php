<?php
// Start session (optional)
session_start();

// Get the error message (fallback if not set)
$error_message = isset($_GET['error']) ? htmlspecialchars($_GET['error']) : "An unexpected error occurred.";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error - Public Market Monitoring System</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f8f9fa;
        }

        .error-container {
            text-align: center;
            padding: 40px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .error-icon {
            font-size: 80px;
            color: #dc3545;
        }
    </style>
</head>

<body>

    <div class="error-container">
        <div class="error-icon">❌</div>
        <h2 class="mt-3 text-danger">Error</h2>
        <p><?php echo $error_message; ?></p>
        <a href="index.php" class="btn btn-primary">Go to Homepage</a>
        <a href="javascript:history.back()" class="btn btn-secondary">Go Back</a>
    </div>

</body>

</html>