<style>
    a.login {
        background-color: #003366;
        color: white;
        font-weight: 600;
    }

    a.login:hover {
        background: rgb(10, 70, 129) !important;
        color: white;
    }

    a.links {
        outline: none;
        border: none;
        display: inline-block;
        position: relative;
        background: transparent;
        cursor: pointer;
        color: #003366;
    }

    a.links::after {
        content: '';
        position: absolute;
        width: 100%;
        transform: scaleX(0);
        height: 2px;
        bottom: 0;
        left: 0;
        background-color: #003366;
        transition: transform 0.25s ease-out;
        transform-origin: center center;
    }

    a.links:hover::after {
        visibility: visible;
        transform: scaleX(1);
        transform-origin: center center;
    }

    .dropdown-menu {
        position: absolute;
        background-color: #fff;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        border-radius: 4px;
        padding: 5px;
        z-index: 1000;
    }

    .dropdown-item {
        display: block;
        padding: 10px;
        color: #333;
        text-decoration: none;
    }

    .dropdown-item:hover {
        background-color: #f1f1f1;
    }

    .nav-item {
        margin: 5px;
    }
</style>

<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$isLogin = isset($_SESSION['account_id']) ? true : false;
$currentPage = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$homePath = "/lgu_market_sys/";

?>

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm px-4">

    <div class="container-fluid">
        <!-- Brand Logo -->
        <a class="navbar-brand" href="<?php echo ($currentPath === $homePath) ? '#' : $homePath; ?>">
            <img src="<?php echo $isLogin ? ' images/logo.png' : '../../images/logo.png'; ?>"
                alt="Logo" width="30" height="30" class="d-inline-block align-text-top">
            Public Market Monitoring System
        </a>

        <!-- Navbar Toggler for Mobile View -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <?php
            $allowedPages = ['portal', 'stall_app', 'transfer_stall_app', 'stalls', 'violation', 'track_app', 'stall_extend', 'helper_app'];
            $invalidPath = '/lgu_market_sys/pages/admin/';
            if (
                isset($_SESSION['user_type']) &&
                isset($currentPage) &&
                in_array($currentPage, $allowedPages) &&
                ($_SESSION['user_type'] === 'Vendor' || $_SESSION['user_type'] === 'Admin') &&
                strpos($_SERVER['REQUEST_URI'], $invalidPath) === false // Hide in admin pages
            ) :
            ?>
                <!-- Center Navigation Links -->
                <ul class="navbar-nav ms-auto profile">
                    <li class="nav-item">
                        <a class="nav-link menu <?php echo $currentPage == 'portal' ? 'active' : ''; ?>" href="/lgu_market_sys/pages/portal">Profile</a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link menu dropdown-toggle <?php echo ($currentPage == 'stall_app' || $currentPage == 'transfer_stall_app') ? 'active' : ''; ?>" href="#" id="transactionsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Stall / Transfers
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="transactionsDropdown">
                            <li><a class="dropdown-item <?php echo $currentPage == 'stall_app' ? 'active' : ''; ?>" href="/lgu_market_sys/pages/stall_app">Stall Application</a></li>
                            <li><a class="dropdown-item <?php echo $currentPage == 'transfer_stall_app' ? 'active' : ''; ?>" href="/lgu_market_sys/pages/transfer_stall_app">Transfer Stall Application</a></li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link menu <?php echo $currentPage == 'stalls' ? 'active' : ''; ?>" href="/lgu_market_sys/pages/stalls">Stalls</a>
                    </li>
                </ul>
            <?php endif; ?>

            <ul class="navbar-nav ms-auto d-flex align-items-center">
                <?php
                if (isset($_SESSION['user_type']) && ($_SESSION['user_type'] === 'Admin' || $_SESSION['user_type'] === 'Inspector')) : ?>
                    <li class="nav-item">
                        <a href="http://localhost/lgu_market_sys/pages/admin/home/" class="btn links">Admin</a>
                    </li>
                <?php endif; ?>
                <?php echo $isLogin ? '' : '       <li class="nav-item">
                    <a href="http://localhost/lgu_market_sys/pages/login/" class="btn login px-3 rounded-pill">Login</a>
                </li>' ?>
                <li class="nav-item">
                    <?php if ($isLogin): ?>
                        <a href="<?php echo ($currentPath === $homePath) ? '#' : $homePath; ?>" class="btn links">Home</a>
                    <?php endif; ?>
                </li>

                <li class="nav-item">
                    <a href="http://localhost/lgu_market_sys/pages/signup/" class="btn links">Sign up</a>
                </li>
            </ul>

            <!-- Right-side Icons -->
            <ul class="navbar-nav px-5 mx-5 d-flex align-items-center">
                <li class="nav-item">
                    <i class="bi bi-brightness-high me-3"></i>
                </li>

                <?php echo !$isLogin ? '' : '
                <li class="nav-item position-relative">
                    <i class="bi bi-bell me-3" id="notificationBell"></i>
                    <div class="notifications position-absolute bg-white shadow rounded p-2" id="notificationsList" style="display:none; width: 300px; right:0; top:40px;">
                        <p class="m-0">No new notifications</p>
                    </div>
                </li>
       
                <ul class="navbar-nav d-flex align-items-center justify-content-center w-100">
                    <li class="nav-item dropdown">
                       
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle" style="cursor: pointer;"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="http://localhost/lgu_market_sys/pages/actions/logout.php">Logout</a></li>
                        </ul>
                    </li>
                </ul> ' ?>
            </ul>

        </div>
    </div>
</nav>

<!-- JavaScript for Notifications -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        let notificationBell = document.getElementById("notificationBell");
        let notificationsList = document.getElementById("notificationsList");

        notificationBell.addEventListener("click", function(event) {
            event.stopPropagation();
            notificationsList.style.display = notificationsList.style.display === "block" ? "none" : "block";
        });

        document.addEventListener("click", function(event) {
            if (!notificationsList.contains(event.target) && !notificationBell.contains(event.target)) {
                notificationsList.style.display = "none";
            }
        });
    });

    document.addEventListener("DOMContentLoaded", function() {
        let dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
        let dropdownList = dropdownElementList.map(function(dropdownToggleEl) {
            return new bootstrap.Dropdown(dropdownToggleEl);
        });
    });
</script>