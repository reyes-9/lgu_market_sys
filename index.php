<?php
define('IS_HOMEPAGE', true);
require_once __DIR__ . '/includes/session.php';



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
    <title>Home - Public Market Monitoring System</title>
    <link rel="icon" type="image/png" href="images/logo.png">
    <link rel="stylesheet" href="assets/css/index.css">
    <?php include_once "includes/cdn-resources.php" ?>
</head>

<body class="body light">

    <?php include_once 'includes/nav.php'; ?>
    <?php include_once 'includes/announcements.php'; ?>
    <!-- Main content -->

    <div class="content-wrapper text-light">
        <h1>Public Market Monitoring System</h1>
        <p>Ensuring transparency for thriving markets.</p>

        <div class="button-container" id="modules-button-container">
            <a class="btn btn-custom" href="/lgu_market_sys/pages/feedback">Feedback Services</a>
            <?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'Vendor' || $_SESSION['user_type'] === 'Admin'): ?>
                <a class="btn btn-custom portal-btn" href="/lgu_market_sys/pages/portal">Vendor Portal</a>
            <?php else: ?>
                <a class="btn btn-custom disabled" href="#" onclick="alert('Only vendors can access this page.');">Vendor Portal</a>
            <?php endif; ?>
            <a class="btn btn-custom" href="/lgu_market_sys/pages/map">Vendor Mapping</a>
        </div>
    </div>

    <!-- Footer -->
    <?php include_once 'includes/footer.php'; ?>

</body>

</html>