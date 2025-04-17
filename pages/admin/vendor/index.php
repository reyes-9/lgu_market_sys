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
    <?php include_once '../../../includes/cdn-resources.php'; ?>
</head>

<body class="body light">
    <?php include '../../../includes/nav.php'; ?>

    <div class="text-start m-3 p-3 title d-flex align-items-center">
        <div class="icon-box me-3 shadow title-icon">
            <i class="bi bi-bar-chart-line-fill"></i>
        </div>
        <div>
            <h4 class="m-0">Admin - Vendor Applications</h4>
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
                <h4 class="fw-bold">Vendor Applications Management</h4>
                <p class="text-muted">Manage and track payments related to stall rentals, stall extensions, violations using receipt uploads.</p>
            </div>
        </div>

        <div class="table-responsive tables mb-5 w-100">
            <div class="text-center mb-4 mt-5">
                <h4>Vendors Table</h4>
            </div>
            <table class="table table-striped text-center">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Vendor Name</th>
                        <th>Email</th>
                        <th>Contact No.</th>
                        <th>Sex</th>
                        <th>Civil Status</th>
                        <th>Nationality</th>
                        <th>Address</th>
                        <th>Application Status</th>
                        <th>Application Date</th>
                    </tr>
                </thead>
                <tbody id="recordsTable">

                    <!-- More rows can be added as needed -->
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="vendorModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="vendorModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="modal-container">
                        <div class="d-flex align-items-center justify-content-between">
                            <h4 class="modal-title fw-bold" id="paymentModalLabel">Show Vendor</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <hr class="mb-4">

                        <form id="vendorApprovalForm">
                            <input type="hidden" id="accountId" name="account_id">
                            <input type="hidden" id="applicationId" name="application_id">
                            <table class="table table-responsive table-borderless">
                                <tbody>
                                    <tr>
                                        <th>Vendor ID:</th>
                                        <td id="modalVendorId"></td>
                                    </tr>
                                    <tr>
                                        <th>Vendor Name:</th>
                                        <td id="modalVendorName"></td>
                                    </tr>
                                    <tr>
                                        <th>Email:</th>
                                        <td id="modalVendorEmail"></td>
                                    </tr>
                                    <tr>
                                        <th>Contact Number:</th>
                                        <td id="modalVendorContact"></td>
                                    </tr>
                                    <tr>
                                        <th>Address:</th>
                                        <td id="modalVendorAddress"></td>
                                    </tr>
                                    <tr>
                                        <th>Application Status:</th>
                                        <td id="modalVendorStatus"></td>
                                    </tr>
                                </tbody>
                            </table>



                            <div class="text-end mt-5">
                                <button type="button" class="btn btn-secondary" onclick="toggleRejectionReason()" id="rejectBtn">Reject</button>

                                <button id="confirmVendorBtn" onclick="approveVendor()" class="btn btn-success">
                                    Approve Application
                                </button>
                            </div>
                            <!-- Rejection Reason Input (Initially Hidden) -->
                            <div id="rejectionReasonContainer" class="mt-2" style="display: none;">
                                <label for="rejectionReason" class="form-label">Reason for Rejection:</label>
                                <textarea id="rejectionReason" class="form-control" rows="2" placeholder="Enter reason..."></textarea>
                                <button type="button" class="btn btn-danger mt-2" onclick="rejectVendor()">Submit Rejection</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include '../../../includes/footer.php'; ?>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            getVendors();
            document.getElementById('confirmVendorBtn').addEventListener('click', approveVendor);
        });

        function getVendors() {
            fetch('../../actions/get_vendors.php')
                .then(response => response.json())
                .then(data => {
                    const tableBody = document.getElementById('recordsTable');
                    tableBody.innerHTML = '';

                    if (data.success) {
                        data.vendors.forEach(vendor => {
                            const row = document.createElement('tr');

                            row.innerHTML = `
                                <td>${vendor.id}</td>
                                <td>${vendor.first_name} ${vendor.middle_name ? vendor.middle_name : ''} ${vendor.last_name}</td>
                                <td>${vendor.email}</td>
                                <td>${vendor.contact_no}</td>
                                <td>${vendor.sex}</td>
                                <td>${vendor.civil_status}</td>
                                <td>${vendor.nationality}</td>
                                <td>${vendor.address}</td>
                                <td class="fw-bold ${vendor.application_status === 'Approved' ? 'text-success' : vendor.application_status === 'Pending' ? 'text-warning' : 'text-danger'}">${vendor.application_status}</td>
                                <td>${vendor.application_date}</td>
                            `;
                            row.addEventListener('click', function() {
                                openVendorModal(vendor); // Modify or create this function as needed
                            });
                            tableBody.appendChild(row);
                        });
                    } else {
                        tableBody.innerHTML = '<tr><td colspan="12" class="text-center">No vendors found.</td></tr>';
                    }
                })
                .catch(error => {
                    console.error('Error fetching vendors:', error);
                    document.getElementById('recordsTable').innerHTML = '<tr><td colspan="12" class="text-center text-danger">Failed to load data.</td></tr>';
                });
        }


        function openVendorModal(vendor) {
            document.getElementById('accountId').value = vendor.account_id;
            document.getElementById('applicationId').value = vendor.id;
            document.getElementById('modalVendorId').textContent = vendor.id;
            document.getElementById('modalVendorName').textContent = vendor.first_name + " " + vendor.last_name;
            document.getElementById('modalVendorEmail').textContent = vendor.email;
            document.getElementById('modalVendorContact').textContent = vendor.contact_no;
            document.getElementById('modalVendorAddress').textContent = vendor.address;
            document.getElementById('modalVendorStatus').textContent = vendor.application_status;

            let confirmBtn = document.getElementById('confirmVendorBtn');
            let rejectBtn = document.getElementById('rejectBtn');

            // Set vendor ID as data attribute
            confirmBtn.setAttribute('data-vendor-id', vendor.id);
            confirmBtn.setAttribute('data-account-id', vendor.account_id);

            // Disable button if already approved or rejected
            if (vendor.application_status === "Approved" || vendor.application_status === "Rejected") {
                confirmBtn.disabled = true;
                rejectBtn.disabled = true;
            } else {
                confirmBtn.disabled = false;
                rejectBtn.disabled = false;
            }

            let myModal = new bootstrap.Modal(document.getElementById('vendorModal'));
            myModal.show();
        }

        function approveVendor(event) {
            event.preventDefault();

            if (!confirm("Are you sure you want to approve this vendor?")) {
                return;
            }

            let formData = new FormData(document.getElementById('vendorApprovalForm'));

            fetch('../../actions/approve_vendor.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert("Vendor approved successfully!");
                        location.reload();
                    } else {
                        alert("Error: " + data.message);
                    }
                })
                .catch(error => {
                    console.error("Error approving vendor:", error);
                    alert("Failed to approve vendor. Please try again.");
                });
        }

        function toggleRejectionReason() {
            let reasonContainer = document.getElementById('rejectionReasonContainer');
            reasonContainer.style.display = reasonContainer.style.display === 'none' ? 'block' : 'none';
        }

        function rejectVendor() {
            let rejectionReason = document.getElementById('rejectionReason').value.trim();
            let formData = new FormData(document.getElementById('vendorApprovalForm'));

            if (!rejectionReason) {
                alert("Please enter a reason for rejection.");
                return;
            }

            formData.append("rejection_reason", rejectionReason);

            fetch('../../actions/reject_vendor.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert("Vendor rejected successfully!");
                        location.reload();
                    } else {
                        alert("Error: " + data.message);
                    }
                })
                .catch(error => {
                    console.error("Error rejecting vendor:", error);
                    alert("Failed to reject vendor. Please try again.");
                });
        }
    </script>

</body>