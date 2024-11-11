<style>
    .nav-link {
        transition: background-color 0.3s ease, color 0.3s ease;
    }

    .nav-link.active {
        background-color: #007bff;
        color: white;
        font-weight: bold;
        border-radius: 5px;
    }
</style>
<?php
$currentPage = basename($_SERVER['REQUEST_URI']);
?>
<nav class="nav nav-pills nav-fill m-5">
    <a class="nav-link <?php echo $currentPage == 'portal' ? 'active' : ''; ?>" href="/lgu_market_sys/pages/portal">Profile</a>

    <div class="nav-item dropdown">
        <a class="nav-link dropdown-toggle <?php echo ($currentPage == 'stall_app' || $currentPage == 'transfer_stall_app') ? 'active' : ''; ?>" href="#" id="transactionsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Transactions
        </a>
        <ul class="dropdown-menu" aria-labelledby="transactionsDropdown">
            <li><a class="dropdown-item <?php echo $currentPage == 'stall_app' ? 'active' : ''; ?>" href="/lgu_market_sys/pages/stall_app">Stall Application</a></li>
            <li><a class="dropdown-item <?php echo $currentPage == 'transfer_stall_app' ? 'active' : ''; ?>" href="/lgu_market_sys/pages/transfer_stall_app">Transfer Stall Application</a></li>
        </ul>
    </div>

    <a class="nav-link <?php echo $currentPage == 'stalls' ? 'active' : ''; ?>" href="/lgu_market_sys/pages/stalls">Stalls</a>
</nav>