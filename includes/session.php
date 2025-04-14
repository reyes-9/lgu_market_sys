<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$isInActionsFolder = strpos($_SERVER['REQUEST_URI'], '/actions/') !== false;
$isInAdminFolder = strpos($_SERVER['REQUEST_URI'], '/admin/') !== false;
?>
<?php if (!$isInActionsFolder): ?>
    <?php require_once 'cdn-resources.php'; ?>
    <style>
        #logoutModal .modal-body {
            background: #f8f9fa;
        }

        .modal-container {
            padding: 30px 40px !important;
            color: #003366;
            border: 3px solid #003366;
            border-radius: 10px;
        }
    </style>

    <div class="modal fade text-light" id="logoutModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body rounded">
                    <div class="modal-container">
                        <div class="d-flex align-items-center justify-content-between">
                            <h4 class="modal-title" id="logoutModalLabel">Session Timeout Warning</h4>
                        </div>
                        <div class="container mt-5 text-center">
                            <p>Your session will expire in <b><span id="countdown"></span></b></p>
                            <p class="text-muted">You will be logged out, click to cancel.</p>
                            <button id="stayLoggedInBtn" class="btn btn-info mt-2 shadow">Stay Logged In</button>
                            <button id="forceLogoutBtn" class="btn btn-danger mt-2 shadow d-none">Logout Now</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // PHP value passed to JS
        const timeoutDuration = <?= $isInAdminFolder ? 50 * 60 * 1000 : 30 * 60 * 1000 ?>; // 50 mins for admin, 30 mins otherwise
        const warningBefore = 10 * 60 * 1000; // 10 minutes before logout

        let warningTimer, logoutTimer, countdownInterval;

        function resetSessionTimers() {
            clearTimeout(warningTimer);
            clearTimeout(logoutTimer);
            clearInterval(countdownInterval);

            warningTimer = setTimeout(() => {
                const modal = new bootstrap.Modal(document.getElementById('logoutModal'));
                modal.show();

                let secondsLeft = warningBefore / 1000;
                const countdownElement = document.getElementById("countdown");
                const stayBtn = document.getElementById("stayLoggedInBtn");
                const logoutBtn = document.getElementById("forceLogoutBtn");

                countdownElement.textContent = formatTime(secondsLeft);
                stayBtn.classList.remove("d-none");
                logoutBtn.classList.add("d-none");

                countdownInterval = setInterval(() => {
                    secondsLeft--;
                    countdownElement.textContent = formatTime(secondsLeft);

                    if (secondsLeft <= 0) {
                        clearInterval(countdownInterval);
                        stayBtn.classList.add("d-none");
                        logoutBtn.classList.remove("d-none");
                    }
                }, 1000);

                stayBtn.onclick = () => {
                    modal.hide();
                    resetSessionTimers();
                };

                logoutBtn.onclick = () => {
                    window.location.href = "http://localhost/lgu_market_sys/pages/actions/logout.php";
                };
            }, timeoutDuration - warningBefore);
        }

        document.addEventListener("DOMContentLoaded", resetSessionTimers);

        function formatTime(seconds) {
            const mins = Math.floor(seconds / 60);
            const secs = seconds % 60;
            return `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
        }
    </script>
<?php endif; ?>