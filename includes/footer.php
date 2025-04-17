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


    /* Footer styles */
    footer {
        padding: 20px 0;
    }

    footer.dark {
        background-color: #020d18;
        color: #c9d1d9;
    }

    footer.light {
        background-color: #ffffff;
        color: #003366;
    }

    .footer-links.dark {
        color: #a0c3e8;
    }

    .footer-links.light {
        color: #003366;
    }

    .footer-container {
        display: flex;
        justify-content: space-between;
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }

    .footer-section {
        flex: 1;
        margin-right: 20px;
    }

    .footer-section:last-child {
        margin-right: 0;
    }

    .footer-section h4 {
        font-size: 18px;
        margin-bottom: 10px;
        font-weight: bold;
    }

    .footer-section p {
        margin-bottom: 10px;
    }

    .footer-section ul {
        list-style-type: none;
        padding: 0;
    }

    .footer-section ul li {
        margin-bottom: 8px;
    }

    .footer-section ul li a {
        text-decoration: none;
    }

    .footer-section ul li a:hover {
        text-decoration: underline;
    }

    .footer-bottom {
        text-align: center;
        padding: 10px 0;
        border-top: 1px solid #ccc;
    }

    .footer-bottom p {
        margin: 0;
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
                <h4>Vendor Profile</h4>
                <p class="text-muted small_text">Create now to access the Vendor Portal—manage your stalls, track applications, and grow your business with ease!</p>

                <a href="http://localhost/lgu_market_sys/pages/create_profile/index.php" class="btn links">Create now</a>
                <hr>
            </div>
        <?php endif; ?>

        <div class="footer-section">
            <h4>Account</h4>
            <hr>
            <div class="button-group mb-3">
                <a href="http://localhost/lgu_market_sys/pages/login/" class="btn login px-3 rounded-pill me-2">Login</a>
                <a href="http://localhost/lgu_market_sys/pages/signup/" class="btn links">Sign up</a>
            </div>
            <hr>

        </div>

    </div>
    <div class="footer-bottom">
        <p>&copy; 2024 Public Market Monitoring System. All rights reserved.</p>
    </div>

    <?php
    $isVendor = ($isLogin === true && $_SESSION['user_type'] === 'Vendor') ? 'true' : 'false';
    ?>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let isVendor = <?php echo $isVendor; ?>;
            let isLogin = <?php echo json_encode($isLogin); ?>;
            let vendorPortalLink = document.querySelector("a.footer-links[href*='portal']");
            let feedbackLink = document.querySelector("a.footer-links[href*='feedback']");
            let mapsLink = document.querySelector("a.footer-links[href*='map']");

            console.log(isLogin);

            vendorPortalLink.addEventListener("click", function(event) {
                if (!isVendor) {
                    event.preventDefault();
                    alert("Access Denied! Only vendors can access the Vendor Portal.");
                }
            });

            feedbackLink.addEventListener("click", function(event) {
                if (!isLogin) {
                    event.preventDefault();
                    alert("Access Denied! Login to continue.");
                }
            });

            mapsLink.addEventListener("click", function(event) {
                if (!isLogin) {
                    event.preventDefault();
                    alert("Access Denied! Login to continue.");
                }
            });


        });
    </script>