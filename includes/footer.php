<style>
    a.links {
        outline: none;
        border: none;
        display: inline-block;
        position: relative;
        background: transparent;
        cursor: pointer;
        color: #003366;
    }

    .small-text {
        font-size: 0.1rem !important;
        /* Adjust as needed */
    }
</style>
<?php
$isLogin = isset($_SESSION['account_id']) ? true : false;
?>
<footer class="light shadow">
    <div class="footer-container">
        <div class="footer-section">
            <h4>Public Market Monitoring System</h4>
            <hr>
            <ul>
                <li><a class="footer-links" href="http://localhost/lgu_market_sys/pages/portal/">VENDOR PORTAL</a></li>
                <li><a class="footer-links" href="http://localhost/lgu_market_sys/pages/feedback/">FEEDBACK SERVICES</a></li>
                <li><a class="footer-links" href="http://localhost/lgu_market_sys/pages/map/">VENDOR MAPPING</a></li>
            </ul>
        </div>

        <div class="footer-section">
            <h4>Integrated Systems</h4>
            <hr>
            <ul>
                <li><a class="footer-links" href="#">WASTE MANAGEMENT SYSTEM</a></li>
            </ul>
        </div>

        <div class="footer-section">
            <h4>Contact Us</h4>
            <hr>
            <ul>
                <li>
                    <i class="bi bi-facebook" style="margin-right: 5px;"></i>
                    <a class="footer-links" href="#">Facebook</a>
                </li>
                <li>
                    <i class="bi bi-instagram" style="margin-right: 5px;"></i>
                    <a class="footer-links" href="#">Instagram</a>
                </li>
                <li>
                    <i class="bi bi-whatsapp" style="margin-right: 5px;"></i>
                    <a class="footer-links" href="#">WhatsApp</a>
                </li>
                <li>
                    <i class="bi bi-twitter-x" style="margin-right: 5px;"></i>
                    <a class="footer-links" href="#">X</a>
                </li>
            </ul>
        </div>

        <?php if (!empty($isLogin) && $_SESSION['user_type'] === 'Visitor') : ?>
            <div class="footer-section">
                <h4>Vendor Account</h4>
                <p class="text-muted small_text">Register now to access the Vendor Portal—manage your stalls, track applications, and grow your business with ease!</p>

                <a href="http://localhost/lgu_market_sys/pages/actions/vendor_register.php" class="btn links">Register now</a>
                <hr>
            </div>
        <?php endif; ?>

        <div class="footer-section">
            <h4>Account</h4>
            <hr>
            <div class="button-group mb-3">
                <a href="http://localhost/lgu_market_sys/pages/actions/login.php" class="btn login px-3 rounded-pill me-2">Login</a>
                <a href="http://localhost/lgu_market_sys/pages/actions/signup.php" class="btn links">Sign up</a>
            </div>
            <hr>

        </div>

    </div>
    <div class="footer-bottom">
        <p>&copy; 2024 Public Market Monitoring System. All rights reserved.</p>
    </div>