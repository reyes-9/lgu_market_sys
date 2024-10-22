<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Public Market Monitoring System</title>
    <link rel="icon" type="image/png" href="images/favicon_192.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="assets/css/index.css">
</head>

<body class="body light">

    <!-- NAVBAR SECTION-->
    <nav class="navbar navbar-expand-lg shadow-sm light" id="navbar">
        <div class="container">
            <a class="navbar-brand light" href="#">
                <img src="images/favicon_192.png" alt="Logo" width="30" height="30" class="d-inline-block align-text-top">
                Public Market Monitoring System
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto text-light">
                    <li class="nav-item">
                        <a class="nav-link" href="http://localhost/lgu_market_sys/">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">About Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#services">Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">Contact Us</a>
                    </li>
                    <li class="nav-item m-1 p-1">
                        <button class="btn-toggle" id="theme-toggle">
                            <i class="bi bi-moon"></i>
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </nav>s
    <!-- Main content -->
    <div class="content-wrapper text-light">
        <h1>Public Market Monitoring System</h1>
        <p>Ensuring transparency for thriving markets.</p>

        <div class="button-container">
            <a class="btn btn-custom" href="/lgu_market_sys/pages/portal">Vendor Portal</a>
            <a class="btn btn-custom" href="/lgu_market_sys/pages/feedback">Feedback Services</a>
            <a class="btn btn-custom disabled" href="/market-monitoring/pages/maps">Vendor Mapping</a>
        </div>
    </div>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>
    <?php include 'includes/theme.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>