<?php
require_once "../../../includes/config.php";

// Fetch application data
$applicationId = $_GET['id'] ?? null;

if ($applicationId) {
    $query = "SELECT 
    s.stall_number, 
    sec.section_name AS section_name, 
    m.market_name AS market_name,
    CONCAT(h.first_name, ' ', COALESCE(h.middle_name, ''), ' ', h.last_name) AS full_name,
    e.duration AS extension_duration,
    app.id,
    app.application_type,
    app.application_number,
    app.status,
    app.created_at
    FROM applications app
    JOIN stalls s ON app.stall_id = s.id    
    JOIN sections sec ON app.section_id = sec.id
    JOIN market_locations m ON app.market_id = m.id
    JOIN applicants
    LEFT JOIN extensions e ON app.extension_id = e.id
    LEFT JOIN helpers h ON app.helper_id = h.id
    WHERE app.id = :id";

    $stmt = $pdo->prepare($query);
    $stmt->execute(['id' => $applicationId]);
    $app = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$app) {
        die("Application not found.");
    }
} else {
    die("Invalid application ID.");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Application - Public Market Monitoring System</title>
    <link rel="icon" type="image/png" href="../favicon_192.png">
    <link rel="stylesheet" href="../../../assets/css/review.css">
    <?php include '../../../includes/cdn-resources.php'; ?>
</head>

<body class="body light">
    <?php include '../../../includes/nav.php'; ?>

    <div class="review-container">

        <a class="btn btn-return" href="javascript:history.back();">
            <i class="bi bi-arrow-left"></i> Return to applications
        </a>

        <h4 class="text-center">Application Validation</h4>
        <h6><strong>Application Type:</strong> <?php echo !empty($app['application_type']) ? $app['application_type'] : 'N/A'; ?></h6>
        <h6><strong>Applicant Name:</strong> <?php echo !empty($app['applicant_name']) ? $app['applicant_name'] : 'N/A'; ?></h6>
        <h6><strong>Status:</strong> <span class="badge bg-warning"><?php echo !empty($app['status']) ? $app['status'] : 'N/A'; ?></span></h6>

        <h6><i class="bi bi-exclamation-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="Verifies the market's availability status."></i>
            Applicant Validation Result:
            <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>
        </h6>

        <hr>
        <h6> <strong>Market: </strong> </h6>
        <h6> <strong>Section: </strong> </h6>
        <h6> <strong>Stall: </strong> </h6>
        <h6><i class="bi bi-exclamation-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="Checks if the user exist in the database."></i>
            Market Validation Result:
            <span class="spinner-border spinner-border-sm" aria-hidden="true">
            </span> <span class="badge bg-success">Success</span>
            <span class="badge bg-danger">Rejected</span>
        </h6>
        <hr>

        <h6><strong>Violations:</strong></h6>
        <hr>
        <h6> <strong> Uploaded Documents</strong></h6>
        <p>
            <?php if (!empty($app['document'])): ?>
                <a href="uploads/<?php echo $app['document']; ?>" target="_blank" class="btn btn-secondary">View Document</a>
            <?php else: ?>
                <span class="text-muted">No document uploaded</span>
            <?php endif; ?>
        </p>

        <hr>

        <form method="post" action="process_application.php" class="approval-actions">
            <input type="hidden" name="application_id" value="<?php echo !empty($app['id']) ? $app['id'] : ''; ?>">
            <button type="submit" name="approve" class="btn btn-success">Approve</button>
            <button type="submit" name="reject" class="btn btn-danger">Reject</button>
        </form>
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

    <script>
        var appData = <?php echo json_encode($app, JSON_PRETTY_PRINT); ?>;
        console.log("Application Data:", appData);
    </script>
</body>