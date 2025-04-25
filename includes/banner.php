<?php

$account_id = $_SESSION['account_id'];
$user_id = getUserIdByAccountId($pdo, $account_id);

$getExpiringViolationsQuery = "
 SELECT e.expiration_date,
       u.status AS user_status
FROM expiration_dates e
LEFT JOIN violations v ON e.reference_id = v.id
LEFT JOIN users u ON v.user_id = u.id
WHERE 
    v.user_id = :userId
    AND e.type = 'violation'
    AND DATEDIFF(e.expiration_date, NOW()) <= 10
    AND DATEDIFF(e.expiration_date, NOW()) >= 0

";

$stmt = $pdo->prepare($getExpiringViolationsQuery);
$stmt->execute(['userId' => $user_id]);
$expiringViolations = $stmt->fetchAll(PDO::FETCH_ASSOC);
$user_status = $expiringViolations[0]['user_status'];

$showSuspensionBanner = count($expiringViolations) > 0;
$showTerminatedBanner = false;

if ($user_status === "terminated") {
    $showSuspensionBanner = false;
    $showTerminatedBanner = true;
}
?>

<style>
    .violation-banner {
        background-color: #121212;
        margin: 20px;
        padding: 20px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.4);
    }

    .violation-banner h2 {
        font-size: 2rem;
        background: linear-gradient(to right, #ff3b3b, #003366);
        /* Red to blue gradient */
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 10px;
    }

    .violation-banner p {
        font-size: 1rem;
        margin-bottom: 1rem;
        color: #e0e0e0;
        /* Slightly off-white for readability */
    }

    .banner-icon img {
        animation: float 2s ease-in-out infinite;
    }

    @keyframes float {

        0%,
        100% {
            transform: translateY(0px);
        }

        50% {
            transform: translateY(-10px);
        }
    }
</style>


<?php if ($showSuspensionBanner): ?>
    <div class="violation-banner d-flex align-items-center justify-content-between p-5 m-0 text-white">
        <div>
            <h2><strong>Violation</strong> Alert Notification</h2>
            <p>Your account has a pending market violation. Please resolve it before the deadline to avoid escalation.</p>
            <a href="http://localhost/lgu_market_sys/pages/violation/" class="btn btn-danger text-light fw-bold mt-4">View Violation</a>
        </div>
        <div class="banner-icon">
            <img src="https://cdn-icons-png.flaticon.com/512/564/564619.png" alt="Warning Icon" class="img-fluid" width="100">
        </div>
    </div>
<?php endif; ?>

<?php if ($showTerminatedBanner): ?>
    <div class="violation-banner d-flex align-items-center justify-content-between p-5 m-0 text-white">
        <div>
            <h2><strong>Termination </strong> Alert Notification</h2>
            <p>Your account has an escalated market violation, resulting in termination. Please contact support for further assistance.</p>

            <!-- <a href="http://localhost/lgu_market_sys/pages/violation/" class="btn btn-danger text-light fw-bold mt-4">View Violation</a> -->
        </div>
        <div class="banner-icon">
            <img src="https://cdn-icons-png.flaticon.com/512/564/564619.png" alt="Warning Icon" class="img-fluid" width="100">
        </div>
    </div>
<?php endif; ?>