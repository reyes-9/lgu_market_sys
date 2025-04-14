<?php

require '../../../includes/session.php';
// Check if user is logged in
if (!isset($_SESSION['account_id'])) {
    echo '<script>
            alert("Please log in to continue.");
            window.location.href = "/lgu_market_sys/pages/login/index.php";
           </script>';
    exit();
}

// Only allow vendors
if ($_SESSION['user_type'] !== 'Vendor' && $_SESSION['user_type'] !== 'Visitor' && $_SESSION['user_type'] !== 'Admin' && $_SESSION['user_type'] !== 'Inspector') {
    echo '<script>
   alert("Please log in to continue.");
   window.location.href = "/lgu_market_sys/pages/login/index.php";
  </script>';
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Application - Public Market Monitoring System</title>
    <link rel="icon" type="image/png" href="../logo.png">
    <link rel="stylesheet" href="../../../assets/css/admin.css">
    <?php include '../../../includes/cdn-resources.php'; ?>
</head>

<body class="body light">
    <?php include '../../../includes/nav.php'; ?>

    <div class="text-start m-3 p-3 title d-flex align-items-center">
        <div class="icon-box me-3 shadow title-icon">
            <i class="bi bi-bar-chart-line-fill"></i>
        </div>
        <div>
            <h4 class="m-0">Admin - View Expired Records</h4>
            <p class="text-muted mb-0">View and manage all expired records, including stalls, extensions, and helpers.</p>
        </div>
        <div class="ms-auto me-5">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="http://localhost/lgu_market_sys/pages/admin/home/">Dashboard</a></li>
                    <li class="breadcrumb-item acitve" aria-current="page">View</li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="container-fluid w-75 mt-5">
        <div class="d-flex justify-content-center align-items-center mb-4">
            <div class="container">
                <h4 class="fw-bold">Expired Records Management</h4>
                <p class="text-muted">View and manage all expired records, including stalls, extensions, and helpers.</p>
            </div>
        </div>

        <div class="table-responsive tables mb-5 w-100">
            <div class="text-center mb-4 mt-5">
                <h4>Expired Records Table</h4>
            </div>
            <table class="table table-striped text-center">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Vendor Name</th>
                        <th>Stall Number</th>
                        <th>Expiration Date</th>
                        <th>Status</th>
                        <th>Renewal / Extension</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="recordsTable">
                    <tr>
                        <td>1</td>
                        <td>John Doe</td>
                        <td>101</td>
                        <td>2025-03-01</td>
                        <td>Expired</td>
                        <td>
                            <button class="btn btn-success btn-sm mb-1 w-75">
                                <i class="bi bi-check-circle-fill"></i> Renew
                            </button>
                            <button class="btn btn-warning btn-sm mb-1 w-75">
                                <i class="bi bi-arrow-clockwise"></i> Extend
                            </button>
                        </td>
                        <td>2024-02-01</td>
                        <td>
                            <button class="btn btn-danger btn-sm w-75">
                                <i class="bi bi-trash"></i> Delete
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Jane Smith</td>
                        <td>102</td>
                        <td>2025-02-25</td>
                        <td>Expired</td>
                        <td>
                            <button class="btn btn-success btn-sm mb-1 w-75">
                                <i class="bi bi-check-circle-fill"></i> Renew
                            </button>
                            <button class="btn btn-warning btn-sm mb-1 w-75">
                                <i class="bi bi-arrow-clockwise"></i> Extend
                            </button>
                        </td>
                        <td>2024-01-20</td>
                        <td>
                            <button class="btn btn-danger btn-sm w-75">
                                <i class="bi bi-trash"></i> Delete
                            </button>
                        </td>
                    </tr>
                    <!-- More rows can be added as needed -->
                </tbody>
            </table>
        </div>
    </div>



    <?php include '../../../includes/footer.php'; ?>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
</body>