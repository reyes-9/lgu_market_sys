<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
ini_set('display_errors', 0);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once 'config.php';
include_once __DIR__ . '/../pages/actions/get_user_id.php';

$isLoginPage = strpos($_SERVER['REQUEST_URI'], '/pages/login/') !== false;
$isInActionsFolder = strpos($_SERVER['REQUEST_URI'], '/actions/') !== false;
$isInAdminFolder = strpos($_SERVER['REQUEST_URI'], '/admin/') !== false;
$isHomepage = defined('IS_HOMEPAGE') && IS_HOMEPAGE;

$account_id = $_SESSION['account_id'] ?? null;
$user_id = $account_id ? getUserIdByAccountId($pdo, $account_id) : null;
$restrictedFolders = [
    '/stall_app/',
    '/stall_transfer/',
    '/stall_extend/',
    '/helper_app/',
    '/track_app/',
    '/transfer_stall_app/'
];

// Check if user has a suspended stall
$stallStatusQuery = "
SELECT s.status AS stall_status, u.status AS user_status
FROM stalls s
JOIN applications a ON s.id = a.stall_id
JOIN users u ON a.account_id = u.account_id
WHERE a.account_id = :account_id
  AND a.status = 'Approved'
LIMIT 1
";

$stmt = $pdo->prepare($stallStatusQuery);
$stmt->execute(['account_id' => $account_id]);
$stall = $stmt->fetch(PDO::FETCH_ASSOC);

$shouldShowModal = false;
$shouldDisablePortal = false;

// If the user is terminated, disable portal access and add it to the restricted list
if ($stall && ($stall['stall_status'] === 'terminated' || $stall['user_status'] === 'terminated')) {
    $shouldDisablePortal = true;
    $restrictedFolders[] = '/portal/';
}

// Now check if current page is in a restricted folder
$currentURI = $_SERVER['REQUEST_URI'];
$isInRestrictedFolder = false;

foreach ($restrictedFolders as $folder) {
    if (strpos($currentURI, $folder) !== false) {
        $isInRestrictedFolder = true;
        break;
    }
}

// If in a restricted folder and suspended or terminated, show modal
if (
    $isInRestrictedFolder &&
    $stall &&
    (
        ($stall['stall_status'] === 'suspended' && $stall['user_status'] === 'suspended') ||
        ($stall['stall_status'] === 'terminated' && $stall['user_status'] === 'terminated')
    )
) {
    $shouldShowModal = true;
}

// Optional: homepage banner
if ($isHomepage) {
    include_once 'banner.php';
}

?>


<?php if ($shouldDisablePortal && $isHomepage): ?>
    <script>
        // Delegate the event to the parent container
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('modules-button-container').addEventListener('click', function(e) {
                if (e.target && e.target.matches('a.portal-btn')) {
                    e.preventDefault();
                    alert('This account has been terminated. If you believe this is a mistake, please reach out to the system administrator.');
                }
            });
        });
    </script>
<?php endif; ?>

<?php if ($shouldShowModal): ?>
    <div class="modal fade" id="suspendedModal" tabindex="-1" aria-labelledby="suspendedModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-center">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title w-100" id="suspendedModalLabel">Access Denied</h5>
                </div>
                <div class="modal-body">
                    <p>You cannot access this page.</p>
                </div>
                <div class="modal-footer justify-content-center">
                    <a href="http://localhost/lgu_market_sys/" class="btn btn-primary">Go Back to Home</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.onload = function() {
            const suspendedModal = new bootstrap.Modal(document.getElementById('suspendedModal'));
            suspendedModal.show();
        };
    </script>
<?php endif; ?>

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

    <?php if (!$isLoginPage): ?>
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
            const warningBefore = 15 * 60 * 1000; // 15 minutes before logout

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

    <?php if (!isset($_SESSION['account_id']) && !$isLoginPage): ?>
        <!-- Not Logged In Modal -->
        <div class="modal fade" id="notLoggedInModal" tabindex="-1" aria-labelledby="notLoggedInModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content text-dark">
                    <div class="modal-header">
                        <h5 class="modal-title" id="notLoggedInModalLabel">Login Required</h5>
                    </div>
                    <div class="modal-body">
                        You need to log in to continue using the system. Please enter your credentials.
                    </div>
                    <div class="modal-footer">
                        <a href="/lgu_market_sys/pages/login/" class="btn btn-primary">Go to Login</a>
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const notLoggedInModal = new bootstrap.Modal(document.getElementById('notLoggedInModal'));
                notLoggedInModal.show();
            });
        </script>
    <?php endif; ?>

<?php endif; ?>