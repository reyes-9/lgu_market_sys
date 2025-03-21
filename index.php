<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Public Market Monitoring System</title>
    <link rel="icon" type="image/png" href="images/favicon_192.png">
    <link rel="stylesheet" href="assets/css/index.css">
    <?php include_once "includes\cdn-resources.php" ?>
</head>
<?php

session_start();
// Check if user is logged in
if (!isset($_SESSION['account_id'])) {
    echo '<script>
            alert("Please log in to continue.");
            window.location.href = "/lgu_market_sys/pages/actions/signup.php";
           </script>';
    exit();
}

// Only allow vendors
if ($_SESSION['user_type'] !== 'Vendor' && $_SESSION['user_type'] !== 'Visitor' && $_SESSION['user_type'] !== 'Admin') {
    echo '<script>
   alert("Please log in to continue.");
   window.location.href = "/lgu_market_sys/pages/actions/signup.php";
  </script>';
    exit();
}
?>

<body class="body light">

    <?php include 'includes/nav.php'; ?>
    <?php include 'includes/announcements.php'; ?>
    <!-- Main content -->
    <div class="content-wrapper text-light">
        <h1>Public Market Monitoring System</h1>
        <p>Ensuring transparency for thriving markets.</p>

        <div class="button-container">
            <a class="btn btn-custom" href="/lgu_market_sys/pages/feedback">Feedback Services</a>
            <?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'Vendor' || $_SESSION['user_type'] === 'Admin'): ?>
                <a class="btn btn-custom" href="/lgu_market_sys/pages/portal">Vendor Portal</a>
            <?php else: ?>
                <a class="btn btn-custom disabled" href="#" onclick="alert('Only vendors can access this page.');">Vendor Portal</a>
            <?php endif; ?>
            <a class="btn btn-custom" href="/lgu_market_sys/pages/map">Vendor Mapping</a>
        </div>
    </div>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>
    <?php include 'includes/theme.php'; ?>


</body>

</html>