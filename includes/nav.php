<style>
    /* Top Navigation Bar */
    .top-nav {
        width: 100%;
        height: 60px !important;
        background-color: #fff;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 15px 100px !important;
        position: relative !important;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        /* border-radius: 10px; */
    }

    .nav {
        display: flex !important;
        flex: 1 !important;
        justify-content: center;
        gap: 100px;

    }

    .nav-icons {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .nav-icons i {
        font-size: 22px;
        cursor: pointer;
        color: #333;
        transition: color 0.3s ease-in-out;
    }

    .nav-icons i:hover {
        color: #1e40af;
    }

    .nav-brand {
        font-size: 20px;
        cursor: pointer;
        color: #333;
        transition: color 0.3s ease-in-out;
        text-decoration: none;
    }

    .nav-link.menu {
        transition: background-color 0.3s ease, color 0.3s ease;
        color: #008080;
        font-size: 15px;
    }

    .nav-link.menu.active {
        background-color: #003366 !important;
        color: white;
        font-weight: bold;
        border-radius: 5px;
    }

    .nav-item.dropdown {
        margin: 0px 0px 0px 0px !important;
    }

    /* Notifications */
    .notifications {
        display: none;
        position: absolute;
        right: 20px;
        top: 60px;
        background: white;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        border-radius: 5px;
        width: 300px;
        padding: 10px;
        z-index: 1000;
    }

    .notifications.show {
        display: block;
    }
</style>

<?php
$currentPage = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
?>
<div class="top-nav" id="topNav">
    <a class="nav-brand light" href=" <?php echo ($currentPage == 'index.php' ||  $currentPage == 'lgu_market_sys' ? '#' : '../../index.php') ?>">
        <img src="../../images/favicon_192.png" alt="Logo" width="30" height="30" class="d-inline-block align-text-top">
        Public Market Monitoring System
    </a>

    <?php
    if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'Vendor') : ?>
        <div class="nav">
            <a class="nav-link menu <?php echo $currentPage == 'portal' ? 'active' : ''; ?>" href="/lgu_market_sys/pages/portal">Profile</a>

            <div class="nav-item dropdown position-relative m-0">
                <a class="nav-link menu dropdown-toggle <?php echo ($currentPage == 'stall_app' || $currentPage == 'transfer_stall_app') ? 'active' : ''; ?>"
                    href="#" id="transactionsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Transactions
                </a>
                <ul class="dropdown-menu" aria-labelledby="transactionsDropdown">
                    <li><a class="dropdown-item <?php echo $currentPage == 'stall_app' ? 'active' : ''; ?>" href="/lgu_market_sys/pages/stall_app">Stall Application</a></li>
                    <li><a class="dropdown-item <?php echo $currentPage == 'transfer_stall_app' ? 'active' : ''; ?>" href="/lgu_market_sys/pages/transfer_stall_app">Transfer Stall Application</a></li>
                </ul>
            </div>

            <a class="nav-link menu <?php echo $currentPage == 'stalls' ? 'active' : ''; ?>" href="/lgu_market_sys/pages/stalls">Stalls</a>
        </div>
    <?php endif; ?>

    <div class="nav-icons">
        <i class="bi bi-brightness-high"></i>
        <i class="bi bi-bell" id="notificationBell"></i>
        <div class="notifications" id="notificationsList">
            <p>No new notifications</p>
        </div>
        <i class="bi bi-person-circle"></i>
    </div>
</div>

<!-- JavaScript -->
<script>
    document.addEventListener("DOMContentLoaded", function() {

        // Notifications
        let notificationBell = document.getElementById("notificationBell");
        let notificationsList = document.getElementById("notificationsList");

        notificationBell.addEventListener("click", function(event) {
            event.stopPropagation();
            notificationsList.classList.toggle("show");
        });

        document.addEventListener("click", function(event) {
            if (!notificationsList.contains(event.target) && !notificationBell.contains(event.target)) {
                notificationsList.classList.remove("show");
            }
        });
    });
</script>