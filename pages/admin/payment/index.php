<?php
require_once '../../../includes/session.php';

if ($_SESSION['user_type'] !== 'Admin' && $_SESSION['user_type'] !== 'Inspector') {
    echo '<script>
    alert("Please log in to continue.");
    window.location.href = "/lgu_market_sys/pages/login/index.php";
   </script>';
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Application - Public Market Monitoring System</title>
    <link rel="icon" type="image/png" href="../logo.png">
    <link rel="stylesheet" href="../../../assets/css/admin.css">
    <?php include '../../../includes/cdn-resources.php'; ?>
</head>

<body class="body light">
    <?php include '../../../includes/nav.php'; ?>

    <div class="text-start m-3 p-3 title d-flex align-items-center">
        <div class="icon-box me-3 shadow title-icon">
            <i class="bi bi-bar-chart-line-fill"></i>
        </div>
        <div>
            <h4 class="m-0">Admin - View Expired Records</h4>
            <p class="text-muted mb-0">View and manage all expired records, including stalls, extensions, and helpers.</p>
        </div>
        <div class="ms-auto me-5">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="http://localhost/lgu_market_sys/pages/admin/home/">Dashboard</a></li>
                    <li class="breadcrumb-item acitve" aria-current="page">View</li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="container-fluid w-75 mt-5">
        <div class="d-flex justify-content-center align-items-center mb-4">
            <div class="container">
                <h4 class="fw-bold">Payment Management</h4>
                <p class="text-muted">Manage and track payments related to stall rentals, stall extensions, violations using receipt uploads.</p>
            </div>
        </div>

        <div class="table-responsive tables mb-5 w-100">
            <div class="text-center mb-4 mt-5">
                <h4>Payments Table</h4>
            </div>
            <table class="table table-striped text-center">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User ID</th>
                        <th>Stall ID</th>
                        <th>Extension ID</th>
                        <th>Violation ID</th>
                        <th>Source Type</th>
                        <th>Amount</th>
                        <th>Payment Status</th>
                        <th>Payment Date</th>
                        <th>Receipt</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody id="recordsTable">

                    <!-- More rows can be added as needed -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Payment Status Modal -->
    <!-- <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <h4 class="modal-title fw-bold" id="announcementModalLabel">New Announcement</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <p><strong>Payment ID:</strong> <span id="modalPaymentId"></span></p>
                    <p><strong>Current Status:</strong> <span id="modalPaymentStatus"></span></p>
                    <p>Are you sure you want to mark this payment as <strong>Paid</strong>?</p>

                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success" id="confirmPaymentBtn">Mark as Paid</button>
                </div>
            </div>
        </div>
    </div> -->

    <!-- Announcement Modal -->
    <div class="modal fade" id="paymentModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="modal-container">
                        <div class="d-flex align-items-center justify-content-between">
                            <h4 class="modal-title fw-bold" id="paymentModalLabel">Accept Receipt</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <p class="text-muted me-5">
                            Manage and track payments related to stall rentals, stall extensions, violations using receipt uploads.
                        </p>
                        <hr class="mb-4">

                        <form id="announcementForm">


                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        <th>Payment ID :</th>
                                        <td id="modalPaymentId"></td>
                                    </tr>
                                    <tr>
                                        <th>Current Status :</th>
                                        <td id="modalPaymentStatus"></td>
                                    </tr>
                                    <tr>
                                        <th>Stall Id :</th>
                                        <td id="modalPaymentStallId"></td>
                                    </tr>
                                    <tr>
                                        <th>Vendor Name :</th>
                                        <td id="modalPaymentUserName"></td>
                                    </tr>
                                </tbody>
                            </table>


                            <div class="text-end mt-5">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button id="confirmPaymentBtn" onclick="confirmPayment()" class="btn btn-success">
                                    Mark as Paid
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include '../../../includes/footer.php'; ?>
    <script>
        document.addEventListener('DOMContentLoaded', getPayments);

        function getPayments() {
            fetch('../../actions/get_payments.php')
                .then(response => response.json())
                .then(data => {
                    const tableBody = document.getElementById('recordsTable');
                    tableBody.innerHTML = ''; // Clear previous data

                    if (data.success) {
                        data.payments.forEach(payment => {
                            const row = document.createElement('tr');

                            row.innerHTML = `
                            <td>${payment.id}</td>
                            <td>${payment.user_id}</td>
                            <td>${payment.stall_id || '-'}</td>
                            <td>${payment.extension_id || '-'}</td>
                            <td>${payment.violation_id || '-'}</td>
                            <td>${payment.source_type}</td>
                            <td>₱${parseFloat(payment.amount).toFixed(2)}</td>
                            <td class="fw-bold ${payment.payment_status === 'Paid' ? 'text-success' : 'text-warning'}">${payment.payment_status}</td>
                            <td>${payment.payment_date || 'N/A'}</td>
                            <td>
                                ${payment.receipt_path ? `<a class="btn btn-sm btn-primary" href="../${payment.receipt_path}" target="_blank">View</a>` : 'No Receipt'}
                
                            </td>
                            <td>${payment.created_at}</td>
                        `;
                            row.addEventListener('click', function() {
                                openPaymentModal(payment);
                            });
                            tableBody.appendChild(row);
                        });
                    } else {
                        tableBody.innerHTML = '<tr><td colspan="11" class="text-center">No payments found.</td></tr>';
                    }
                })
                .catch(error => {
                    console.error('Error fetching payments:', error);
                    document.getElementById('recordsTable').innerHTML = '<tr><td colspan="11" class="text-center text-danger">Failed to load data.</td></tr>';
                });
        }

        function openPaymentModal(payment) {
            document.getElementById('modalPaymentId').textContent = payment.id;
            document.getElementById('modalPaymentStatus').textContent = payment.payment_status;
            document.getElementById('modalPaymentStallId').textContent = payment.stall_id;
            document.getElementById('modalPaymentUserName').textContent = payment.full_name;

            let confirmBtn = document.getElementById('confirmPaymentBtn');

            // Set payment ID as data attribute
            confirmBtn.setAttribute('data-payment-id', payment.id);
            confirmBtn.setAttribute('data-stall-id', payment.stall_id);
            confirmBtn.setAttribute('data-payment-type', payment.source_type);

            // Disable button if already paid
            if (payment.payment_status === "Paid") {
                confirmBtn.disabled = true;
            } else {
                confirmBtn.disabled = false;
            }

            let myModal = new bootstrap.Modal(document.getElementById('paymentModal'));
            myModal.show();
        }


        function confirmPayment() {
            let confirmBtn = document.getElementById('confirmPaymentBtn');
            let paymentId = confirmBtn.getAttribute('data-payment-id');
            let stallId = confirmBtn.getAttribute('data-stall-id');
            let paymentType = confirmBtn.getAttribute('data-payment-type');

            if (!paymentId) {
                alert("Invalid payment ID.");
                return;
            }

            if (!confirm("Are you sure you want to mark this payment as Paid?")) return;

            fetch("../../actions/confirm_payment.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded",
                    },
                    body: new URLSearchParams({
                        payment_id: paymentId,
                        reference_id: stallId,
                        payment_type: paymentType
                    })
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    if (data.success) {
                        // location.reload();
                    }
                })
                .catch(error => console.error("Error:", error));
        }
    </script>

</body>