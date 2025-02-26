<style>
    .nav {
        z-index: 1050 !important;
    }

    .nav-link.menu {
        transition: background-color 0.3s ease, color 0.3s ease;
        color: #008080;
        margin: 0px 40px !important;
        font-size: 15px;
    }

    .nav-link.menu:hover {
        background-color: rgba(8, 66, 152, 0.46);
    }

    .nav-link.menu.active {
        background-color: #003366 !important;
        color: white;
        font-weight: bold;
        border-radius: 5px;
    }

    .nav-item.dropdown {
        margin: 0px 60px 0px 0px !important;
    }
</style>
<?php
$currentPage = basename($_SERVER['REQUEST_URI']);
include 'cdn-resources.php';
?>
<nav class="nav nav-pills nav-fill m-5">
    <a class="nav-link menu <?php echo $currentPage == 'portal' ? 'active' : ''; ?>" href="/lgu_market_sys/pages/portal">Profile</a>

    <div class="nav-item dropdown">
        <a class="nav-link menu dropdown-toggle <?php echo ($currentPage == 'stall_app' || $currentPage == 'transfer_stall_app') ? 'active' : ''; ?>" href="#" id="transactionsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Transactions
        </a>
        <ul class="dropdown-menu" aria-labelledby="transactionsDropdown">
            <li><a class="dropdown-item <?php echo $currentPage == 'stall_app' ? 'active' : ''; ?>" href="/lgu_market_sys/pages/stall_app">Stall Application</a></li>
            <li><a class="dropdown-item <?php echo $currentPage == 'transfer_stall_app' ? 'active' : ''; ?>" href="/lgu_market_sys/pages/transfer_stall_app">Transfer Stall Application</a></li>
        </ul>
    </div>

    <a class="nav-link menu <?php echo $currentPage == 'stalls' ? 'active' : ''; ?>" href="/lgu_market_sys/pages/stalls">Stalls</a>
</nav>