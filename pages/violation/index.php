<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Violations - Public Market Monitoring System</title>
    <link rel="icon" type="image/png" href="../../images/favicon_192.png">
    <link rel="stylesheet" href="../../assets/css/violation.css">
    <?php include '../../includes/cdn-resources.php'; ?>
</head>

<?php
require_once '../../includes/session.php';
?>

<body class="body light">

    <?php include '../../includes/nav.php'; ?>

    <div class="payment-banner d-flex justify-content-between align-items-center px-5 py-4">
        <div class="banner-text">
            <h4 class="text-white">Violation Payment</h4>
            <p class="text-white mt-3">Don't wait until the last minute! <br>Upload your payment receipt for verification today to avoid any violations. Be sure to submit it before the due date.</p>
        </div>
        <button class="btn btn-warning submit-receipt-btn" data-bs-toggle="modal" data-bs-target="#submitReceiptModal">Submit Receipt</button>
    </div>

    <!-- Submit Receipt Modal -->
    <div class="modal fade" id="submitReceiptModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="submitReceiptModal" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="modal-container">
                        <div class="d-flex align-items-center justify-content-between">
                            <h4 class="modal-title fw-bold" id="submitReceiptModalLabel">Upload Reciept</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <p class="text-muted me-5">
                            Upload your payment receipt for verification and to avoid violations.
                        </p>
                        <hr class="mb-4">

                        <form id="submitRecieptForm" enctype="multipart/form-data">

                            <table class="table table-borderless table-hover">
                                <thead>
                                    <tr>
                                        <th>Violation</th>
                                        <th>Date Issued <br> (YYYY-MM-DD)</th>
                                        <th>Fine Amount</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="recieptStallsContainer">

                                </tbody>
                            </table>
                            <h5 id="receipt_stall_message"></h5>
                            <div class="container text-start w-50 m-0">

                                <div class="mb-3">
                                    <label for="receiptFile" class="form-label">Upload Reciept (PDF or Image)</label>
                                    <input type="file" class="form-control" id="receiptFile" name="receipt_file" accept=".pdf, image/*">
                                </div>

                                <label for="receiptFile" class="form-label">Paid Amount</label>
                                <div class="input-group mb-3">
                                    <span class="input-group-text">₱</span>
                                    <input type="number" class="form-control" id="paidAmount" name="paid_amount" aria-label="Amount (to the nearest dollar)">
                                </div>
                                <input type="hidden" id="sourceType" name="source_type" value="stall">
                            </div>

                            <button class="btn btn-dark mt-3" id="uploadPaymentReceipt" type="button">Upload</button>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-5 vh-100">
        <div class="container mt-3 p-0">
            <a href="../portal/" class="btn btn-outline btn-return mb-3">
                <i class="bi bi-arrow-left"></i> Profile
            </a>
        </div>
        <div class="container content">

            <div class="text-center mb-4 mt-3">
                <h4 class="mb-5 table-title">Violations Table</h4>
            </div>

            <div class="mb-3">

                <div class="d-flex flex-wrap justify-content-center gap-5 mb-4 filter-container">
                    <button class="btn filter-btn" data-value="">All</button>
                    <button class="btn filter-btn" data-value="Resolved">Resolved</button>
                    <button class="btn filter-btn" data-value="Pending">Pending</button>
                    <button class="btn filter-btn" data-value="Critical">Critical</button>
                </div>


            </div>
            <div class="table-container">
                <table class="table table-borderless table-striped violations-table">
                    <thead>
                        <tr>
                            <th>Violation</th>
                            <th>Date Issued <br> (YYYY-MM-DD)</th>
                            <th>Fine Amount</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="violationsTableBody">

                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="violationDetailsModal" tabindex="-1" aria-labelledby="violationDetailsLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="modal-container">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h4 class="modal-title" id="violationDetailsLabel">Violation Details</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <hr>

                        <table class="table table-bordered table-striped modal-table">
                            <tbody>
                                <tr>
                                    <td><strong>Violation:</strong></td>
                                    <td id="modalViolation"></td>
                                </tr>
                                <tr>
                                    <td><strong>Date Issued:</strong></td>
                                    <td id="modalDate"></td>
                                </tr>
                                <tr>
                                    <td><strong>Fine Amount:</strong></td>
                                    <td id="modalFine"></td>
                                </tr>
                                <tr>
                                    <td><strong>Description:</strong></td>
                                    <td id="modalDescription"></td>
                                </tr>
                                <tr>
                                    <td><strong>Evidence:</strong></td>
                                    <td id="modalImageEvidence"></td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td id="modalStatus" class="badge"></td>
                                </tr>
                                <tr>
                                    <td><strong>Criticality:</strong></td>
                                    <td id="modalCriticality" class="badge"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    include "../../includes/session.php";

    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    ?>
    <div class="modal fade" id="appealViolationModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="appealViolationModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="modal-container">
                        <div class="d-flex align-items-center justify-content-between">
                            <h3 class="modal-title fw-bold" id="staticBackdropLabel">Submit an Appeal</h3>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <p class="text-muted me-5">This modal allows vendors to provide an explanation, upload supporting documents, and submit their request for admin review.</p>
                        <hr class="mb-4">

                        <form id="appealForm">
                            <small><b> Note: You can only submit one.</b></small>
                            <div class="mb-3">
                                <label for="appealText" class="form-label">Appeal Explanation: </label>
                                <textarea class="form-control" id="appealText" name="appeal_text" rows="4" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="appealFile" class="form-label">Upload Supporting Documents (Optional): </label>
                                <input type="file" class="form-control" id="appealFile" name="appeal_file" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary" id="submitAppeal">Submit Appeal</button>
                            </div>
                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                            <input type="hidden" id="violationId" name="violation_id" value="">
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include '../../includes/footer.php'; ?>
    <?php include '../../includes/theme.php'; ?>

    <script>
        document.addEventListener("DOMContentLoaded", function() {

            fetchViolations();
            addFilterToTable();

            // Set violation ID when appeal button is clicked
            document.body.addEventListener('click', function(event) {
                if (event.target && event.target.id === 'appealButton') {
                    const violationId = event.target.getAttribute('data-violation-id');
                    document.getElementById('violationId').value = violationId;
                }
            });

            document.getElementById("appealForm").addEventListener("submit", function(event) {
                event.preventDefault();

                if (confirm("Are you sure you want to submit this appeal?" + "\n You can only submit one.")) {
                    submitAppeal();
                }
            });

            document.getElementById("violationsTableBody").addEventListener("click", function(event) {
                if (event.target.classList.contains("view-details-btn")) {
                    updateViolationModal(event.target);
                }
            });
        });


        function submitAppeal() {

            const form = document.getElementById("appealForm");

            if (!form) {
                console.error("Form not found!");
                return;
            }

            let formData = new FormData(form);

            fetch("../actions/submit_appeal.php", {
                    method: "POST",
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert("Appeal submitted successfully!");
                        location.reload();
                    } else {
                        alert("Error: " + data.message);
                        location.reload();
                    }
                })
                .catch(error => {
                    console.error("Error submitting appeal:", error);
                    alert("An error occurred while submitting your appeal.");
                    form.reset();
                });
        }


        function validateFile(file) {
            if (!file) return true;

            const allowedTypes = ["image/jpeg", "image/png", "application/pdf", "application/msword", "application/vnd.openxmlformats-officedocument.wordprocessingml.document"];
            const maxSize = 5 * 1024 * 1024; // 5MB

            if (!allowedTypes.includes(file.type)) {
                alert("Invalid file type. Please upload an image (JPG, PNG), DOC/DOCX, or PDF.");
                return false;
            }

            if (file.size > maxSize) {
                alert("File size exceeds 5MB. Please upload a smaller file.");
                return false;
            }

            return true;
        }

        function updateViolationModal(button) {
            let modalViolation = document.getElementById('modalViolation');
            let modalDate = document.getElementById('modalDate');
            let modalFine = document.getElementById('modalFine');
            let modalDescription = document.getElementById('modalDescription');
            let modalStatus = document.getElementById('modalStatus');
            let modalCriticality = document.getElementById('modalCriticality');
            let modalImageEvidence = document.getElementById('modalImageEvidence');

            if (!modalViolation || !modalDate || !modalFine || !modalDescription || !modalStatus || !modalCriticality) {
                console.error("One or more modal elements not found.");
                return;
            }

            modalViolation.textContent = button.getAttribute('data-violation');
            modalDate.textContent = button.getAttribute('data-date');
            modalFine.textContent = button.getAttribute('data-fine');
            modalDescription.textContent = button.getAttribute('data-description');
            modalImageEvidence.innerHTML = `
                    <button class="btn btn-info btn-sm" onclick="window.open('../../${button.getAttribute('data-image-path')}', '_blank')">
                       View Image
                    </button>
            `;

            // Update status badge color
            let status = button.getAttribute('data-status');
            modalStatus.textContent = status;
            modalStatus.className = "badge " +
                (status === "Rejected" ? "bg-dark" :
                    status === "Pending" ? "bg-warning" :
                    status === "Resolved" ? "bg-success" : "");

            let criticality = button.getAttribute('data-criticality');
            modalCriticality.textContent = (criticality === "Critical" ? criticality : "");
            modalCriticality.className = "badge " + (criticality === "Critical" ? "bg-danger" : "");
        }

        function fetchViolations(filter) {

            console.log(filter)
            fetch('../actions/violation_action.php')
                .then(response => response.json())
                .then(data => {
                    const tableBody = document.getElementById("violationsTableBody");
                    tableBody.innerHTML = "";

                    data.data
                        .filter(row =>
                            !filter ||
                            (filter === "Critical" && row.criticality === "Critical") ||
                            (filter === row.status)
                        )
                        .forEach(row => {
                            const tr = document.createElement("tr");
                            tr.innerHTML = `
                        <td>${row.violation_name}</td>
                        <td>${row.violation_date.split(" ")[0]}</td>
                        <td> ₱ ${row.fine_amount}</td>
                        <td>
                            <span class="badge ${
                                row.status === 'Pending' ? 'bg-warning text-dark' :
                                row.status === 'Resolved' ? 'bg-success' : 
                                row.status === 'Rejected' ? 'bg-dark' : ''
                            }">${row.status}</span> 
                            ${row.criticality === 'Critical' ? `<span class="badge bg-danger">Critical</span>` : ''}
                        </td>
                        <td>
                            <a href="#" class="btn btn-sm btn-info view-details-btn"
                                data-bs-toggle="modal"
                                data-bs-target="#violationDetailsModal"
                                data-violation="${row.violation_name}"
                                data-date="${row.violation_date}"
                                data-fine=" ₱ ${row.fine_amount}"
                                data-description="${row.violation_description}" 
                                data-status="${row.status}"
                                data-criticality="${row.criticality}"
                                data-image-path="${row.evidence_image_path}">
                                View Details
                            </a>
                            <button type="button" class="btn btn-warning btn-sm" id="appealButton" data-bs-toggle="modal" data-bs-target="#appealViolationModal"
                                data-violation-id="${row.id}"
                            >
                                Appeal
                            </button>
                        </td>
                    `;
                            tableBody.appendChild(tr);
                        });
                })
                .catch(error => console.error("Error fetching data:", error));
        }

        function addFilterToTable() {
            const filterButtons = document.querySelectorAll(".filter-btn");

            if (filterButtons.length === 0) {
                console.error("Filter options not found.");
                return;
            }

            setActiveFilter(filterButtons, "");

            filterButtons.forEach(option => {
                option.addEventListener("click", function(event) {
                    event.preventDefault();

                    const selectedValue = this.dataset.value;

                    setActiveFilter(filterButtons, selectedValue);
                    fetchViolations(selectedValue);
                });
            });
        }

        function setActiveFilter(filterButtons, selectedValue) {

            filterButtons.forEach(opt => opt.classList.remove('active'));

            const activeButton = Array.from(filterButtons).find(btn => btn.dataset.value === selectedValue);
            if (activeButton) {
                activeButton.classList.add('active');
            }
        }

        function fetchvUnpaidViolations() {
            fetch('backend/get_unpaid_violations.php') // Adjust path as needed
                .then(response => {
                    if (!response.ok) throw new Error('Network error');
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        displayUnpaidViolations(data.violations);
                    } else {
                        console.error("Error:", data.message);
                    }
                })
                .catch(error => {
                    console.error("Fetch error:", error);
                });
        }

        function displayUnpaidViolations(violations) {
            const container = document.getElementById('unpaidViolationsList');
            container.innerHTML = ''; // Clear previous content

            if (violations.length === 0) {
                container.innerHTML = '<p>No unpaid violations found.</p>';
                return;
            }

            violations.forEach(v => {
                const div = document.createElement('div');
                div.className = 'violation-card';
                div.innerHTML = `
            <h4>Violation ID: ${v.id}</h4>
            <p><strong>Date:</strong> ${v.violation_date}</p>
            <p><strong>Description:</strong> ${v.violation_description}</p>
            <p><strong>Status:</strong> ${v.status}</p>
        `;
                container.appendChild(div);
            });
        }
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var modal = document.getElementById('violationDetailsModal');
            var modalViolation = document.getElementById('modalViolation');
            var modalDate = document.getElementById('modalDate');
            var modalFine = document.getElementById('modalFine');
            var modalDescription = document.getElementById('modalDescription');
            var modalStatus = document.getElementById('modalStatus');

            document.querySelectorAll('.view-details-btn').forEach(button => {
                button.addEventListener('click', function() {
                    modalViolation.textContent = this.getAttribute('data-violation');
                    modalDate.textContent = this.getAttribute('data-date');
                    modalFine.textContent = this.getAttribute('data-fine');
                    modalDescription.textContent = this.getAttribute('data-description');

                    // Update status badge color
                    var status = this.getAttribute('data-status');
                    modalStatus.textContent = status;
                    modalStatus.className = "badge " + (status === "Unresolved" ? "bg-danger" : "bg-success");
                });
            });
        });
    </script>
</body>

</html>