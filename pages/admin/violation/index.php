<?php

session_start();

if ($_SESSION['user_type'] !== 'Admin') {
    header("Location: /lgu_market_sys/errors/err403.php");
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Violations - Public Market Monitoring System</title>
    <link rel="icon" type="image/png" href="../favicon_192.png">
    <link rel="stylesheet" href="../../../assets/css/admin.css">
    <?php include '../../../includes/cdn-resources.php'; ?>
</head>

<body class="body light">


    <?php include '../../../includes/nav.php'; ?>

    <!-- Toast -->
    <!-- <div class="toast-container mt-5 p-3 top-0 end-0">
    <div role="alert" aria-live="assertive" aria-atomic="true" class="toast fade show" data-bs-autohide="false">
      <div class="toast-header text-bg-warning rounded-top">
        <svg class="mx-2" width="25" height="22" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
          <rect x="0" y="0" width="100" height="100" rx="20" fill="url(#grad1)" />
          <defs>
            <linearGradient id="grad1" x1="0%" y1="0%" x2="100%" y2="100%">
              <stop offset="0%" style="stop-color:#ff4c4c;stop-opacity:1" />
              <stop offset="100%" style="stop-color:#b30000;stop-opacity:1" />
            </linearGradient>
          </defs>
          <polygon points="50,20 75,75 25,75" fill="white" />
          <rect x="47" y="40" width="6" height="20" fill="#ff4c4c" />
          <circle cx="50" cy="70" r="3" fill="#ff4c4c" />
        </svg>
        <strong class="me-auto">System Alerts</strong>
        <small>11 mins ago</small>
      </div>
      <div class="toast-body text-light rounded-bottom p-4">
        New system update available <br>
        Market maintenance scheduled
      </div>
    </div>
  </div> -->

    <div class="text-start m-3 p-3 title d-flex align-items-center">
        <div class="icon-box me-3 shadow title-icon">
            <i class="bi bi-bar-chart-line-fill"></i>
        </div>
        <div>
            <h4 class="m-0">Admin - Violations</h4>
            <p class="text-muted mb-0">Manage and track vendor violations to ensure compliance with market regulations.</p>
        </div>
        <div class="ms-auto me-5">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="http://localhost/lgu_market_sys/pages/admin/home/">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Violations</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="container mt-4">
        <div class="d-flex justify-content-center align-items-center mb-4">
            <div class="container">
                <h4 class="fw-bold">Violation Management</h4>
                <p class="text-muted">Tracks and manages vendor violations, allowing reporting, searching, editing, and deletion for market regulation compliance.</p>
            </div>
            <div class="container text-end">
                <a class="btn btn-danger report-button" href="http://localhost/lgu_market_sys/pages/admin/report_violation/">
                    <i class="bi bi-clipboard-plus"></i> Report Violation
                </a>
            </div>
        </div>

        <!-- Violations Table -->
        <div class="table-responsive tables">
            <div class="text-center mb-4 mt-5">
                <h4>Violations Table</h4>
            </div>

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Vendor</th>
                        <th>Stall</th>
                        <th>Violation</th>
                        <th>Date Reported</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>Juan Dela Cruz</td>
                        <td>Stall #12</td>
                        <td>Unauthorized expansion</td>
                        <td>March 15, 2025</td>
                        <td>
                            <button class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i></button>
                            <button class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Violation Modal -->
    <div class="modal fade" id="addViolationModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Report Violation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label class="form-label">Vendor Name</label>
                            <input type="text" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Stall Number</label>
                            <input type="text" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Violation Description</label>
                            <textarea class="form-control" rows="3" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-danger w-100">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <?php include '../../../includes/footer.php'; ?>
    <?php include '../../../includes/theme.php'; ?>

</body>

</html>