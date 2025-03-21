<?php

require_once "config.php";

$user_type = $_SESSION['user_type'] ?? 'visitor';
$audience_conditions = [
    'visitor' => "audience = 'all'",
    'vendor' => "audience IN ('vendor', 'all')",
    'admin'  => "audience IN ('admin', 'vendor', 'all')"
];

// Prepare the SQL query with the appropriate audience condition
$condition = $audience_conditions[$user_type] ?? "audience = 'all'";

// Fetch the latest announcement for the user
$stmt = $pdo->prepare(" 
    SELECT title, message, created_at 
    FROM announcements 
    WHERE ($condition) 
    AND (expiry_date IS NULL OR expiry_date >= NOW()) 
    ORDER BY created_at DESC 
    LIMIT 1
");
$stmt->execute();
$latest_announcement = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch all valid announcements for the user
$stmt = $pdo->prepare(" 
    SELECT title, message, created_at 
    FROM announcements 
    WHERE ($condition) 
    AND (expiry_date IS NULL OR expiry_date >= NOW()) 
    ORDER BY created_at DESC 
");
$stmt->execute();
$announcements = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Announcements</title>

    <style>
        .toast {
            min-width: 350px;
            font-size: 0.9rem;
            background-color: #343a40 !important;
            padding: 10px 0px;
            box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
            transition: ease-in-out 0.3s;
        }

        .toast:hover {
            transform: scale(1.02);
        }

        .toast-header {
            color: #f8f9fa;
            background-color: #343a40 !important;
            border-bottom: 0;
        }

        .toast-body {
            width: 350px;
            height: auto;
        }

        .body-container {
            margin: 20px;
            padding: 25px;
        }

        .btn-close-white {
            filter: invert(1) brightness(1.5);
        }

        .small-title {
            color: #c4c4c4;
        }

        .card {
            border-radius: 8px;
            background: linear-gradient(45deg, #23282c, #1e2125);
            /* box-shadow: 25px -25px 51px #191c20, -25px 25px 51px #292e32; */
            padding: 20px 30px;
            transition: all 0.4s ease-in-out;
        }

        .card:hover {
            transform: scale(0.8);
            box-shadow: rgba(172, 170, 164, 0.2) 0px 0px 70px;
        }

        .card {
            opacity: 0;
            transform: scale(0.9);

        }

        .show .card {
            opacity: 1;
            transform: scale(1);
        }

        .modal-body {
            background: rgb(37, 87, 136);
            background: radial-gradient(circle, rgba(37, 87, 136, 1) 0%, rgba(24, 57, 90, 1) 0%, rgba(22, 51, 81, 1) 24%, rgba(0, 0, 0, 1) 100%);
        }
    </style>
</head>

<body>

    <!-- Toast Container (Latest Announcement) -->
    <div class="position-fixed bottom-0 end-0 p-4" style="z-index: 1050">
        <?php if ($latest_announcement) : ?>
            <div class="toast align-items-center text-white bg-primary border-0" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="false">
                <div class="toast-header mx-2 gap-2">
                    <i class="bi bi-bell-fill text-warning"></i>
                    <strong> Announcement</strong>
                    <small class="ms-5"><?= date("M j, g:i A", strtotime($latest_announcement['created_at'])) ?></small>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="mx-2">
                    <hr class="m-2 text-warning">
                </div>
                <strong class="me-auto mx-4"><?= htmlspecialchars($latest_announcement['title']) ?></strong>
                <div class="toast-body d-flex align-items-center justify-content-between px-4 py-3">
                    <span><?= nl2br(htmlspecialchars($latest_announcement['message'])) ?></span>
                </div>
                <div class="text-end pe-3 pb-2">
                    <a href="#" class="text-warning" data-bs-toggle="modal" data-bs-target="#announcementModal">Read More</a>
                </div>
            </div>
        <?php else : ?>
            <div class="toast align-items-center text-white bg-secondary border-0" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="false">
                <div class="toast-header mx-2 gap-2">
                    <strong>No Announcements</strong>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">There are no new announcements at this time.</div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Announcement Modal -->
    <div class="modal fade" id="announcementModal" tabindex="-1" aria-labelledby="announcementModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-body text-light b-0">
                    <div class="body-container">
                        <div class="d-flex justify-content-between">
                            <div class="head text-warning fw-bold">
                                Announcements
                            </div>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="title text-center m-5">
                            <span class="m-2 small-title">
                                Stay Updated with the Latest Market News!
                            </span>
                            <h3>Market Updates</h3>
                        </div>

                        <?php foreach ($announcements as $announcement) : ?>
                            <div class="card bg-dark text-light p-3 mb-5">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-pin-angle-fill text-warning fs-4 me-5"></i>
                                    <h5 class="modal-title">
                                        <?= htmlspecialchars($announcement['title']) ?>
                                    </h5>
                                </div>
                                <hr class="text-warning">
                                <p><?= nl2br(htmlspecialchars($announcement['message'])) ?></p>
                                <small class="small-title d-block">
                                    Posted on <?= date("F j, Y, g:i A", strtotime($announcement['created_at'])) ?>
                                </small>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let toastElList = document.querySelectorAll(".toast");
            toastElList.forEach(toastEl => {
                let toast = new bootstrap.Toast(toastEl);
                toast.show(); // Show each toast on page load
            });
        });
    </script>
</body>

</html>