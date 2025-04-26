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
            <h4>Violation Payment</h4>
            <p class="mt-3">Don't wait until the last minute! <br>Upload your payment receipt for verification today to avoid any violations. Be sure to submit it before the due date.</p>
        </div>
        <button class="btn btn-warning submit-receipt-btn" data-bs-toggle="modal" data-bs-target="#violationReceiptModal">Submit Receipt</button>
    </div>

    <!-- Submit Receipt Modal -->
    <div class="modal fade" id="violationReceiptModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="violationReceiptModal" aria-hidden="true">
        <div class="modal-dialog modal-xl" style="max-width: 100%; margin: 0;">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="modal-container">
                        <div class="d-flex align-items-center justify-content-between">
                            <h4 class="modal-title fw-bold" id="violationReceiptModalLabel">Upload Reciept</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <p class="text-muted me-5">
                            Upload your payment receipt for verification and to avoid violations.
                        </p>
                        <hr class="mb-4">

                        <form id="submitViolationRecieptForm" enctype="multipart/form-data">

                            <table class="table table-borderless table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th>Select</th>
                                        <th>Violation</th>
                                        <th>Date Issued <br> (YYYY-MM-DD)</th>
                                        <th>Fine Amount</th>
                                        <th>Escalation Fee</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody id="unpaidViolationsList">

                                </tbody>
                            </table>


                            <h5 id="receipt_vioaltion_message"></h5>
                            <div class="container text-start w-50 m-0">

                                <div class="mb-3">
                                    <label for="receiptFile" class="form-label">Upload Reciept (PDF or Image)</label>
                                    <input type="file" class="form-control" id="violationReceiptFile" name="violation_receipt_file" accept=".pdf, image/*">
                                </div>

                                <label for="receiptFile" class="form-label">Paid Amount</label>
                                <div class="input-group mb-3">
                                    <span class="input-group-text">₱</span>
                                    <input type="number" class="form-control" id="violationPaidAmount" name="violation_paid_amount" aria-label="Amount (to the nearest dollar)">
                                </div>
                                <input type="hidden" id="sourceType" name="source_type" value="violation">
                            </div>

                            <button class="btn btn-dark mt-3" id="uploadViolationPaymentReceipt" type="button">Upload</button>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-5">
        <div class="row m-5 p-5 shadow rounded-4 violation-container">
            <div class="col-md-12">
                <div class="stall-card">
                    <div class="text-center mb-4 mt-3">
                        <h4 class="mb-5 table-title">Violations Table</h4>
                    </div>

                    <div class="d-flex flex-wrap justify-content-center gap-5 mb-4 filter-container">
                        <button class="btn filter-btn" data-value="">All</button>
                        <button class="btn filter-btn" data-value="Resolved">Resolved</button>
                        <button class="btn filter-btn" data-value="Pending">Pending</button>
                        <button class="btn filter-btn" data-value="Critical">Critical</button>
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
                                    <td><span id="modalStatus" class="badge"></span></td>
                                </tr>
                                <tr>
                                    <td><strong>Criticality:</strong></td>
                                    <td><span id="modalCriticality" class="badge"></span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php

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
            fetchUnpaidViolations();

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

            // Violation
            const violationModal = document.getElementById('violationReceiptModal');
            violationModal.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault(); // Stops the form from submitting or closing the modal
                }
            });

            const uploadViolationButton = document.getElementById('uploadViolationPaymentReceipt');
            uploadViolationButton.addEventListener('click', function() {

                console.log("Upload button clicked");

                const selectedStall = document.querySelector('input[name="selected_violation_id"]:checked'); // fixed typo
                if (!selectedStall) {
                    alert("No violation selected.");
                    return;
                }

                const selectedRow = selectedStall.closest('tr');
                const fileInput = document.getElementById('violationReceiptFile');
                const paid_amount = parseFloat(document.getElementById('violationPaidAmount').value);
                const status = selectedRow.querySelector('td:nth-child(6)').textContent.trim();
                const fine_amount = parseFloat(selectedRow.querySelector('td:nth-child(4)').textContent.trim());
                const escalation_fee = parseFloat(selectedRow.querySelector('td:nth-child(5)').textContent.trim());

                console.log("Selected Violation Details:");
                console.log("Fine Amount:", fine_amount);
                console.log("Escalation Fee:", escalation_fee);
                console.log("Paid Amount:", paid_amount);
                console.log("Status:", status);
                console.log("File Selected:", fileInput.files[0]);

                if (validateReceiptForm(selectedRow, fileInput, paid_amount, status, fine_amount, escalation_fee)) {
                    const violation_form = document.getElementById('submitViolationRecieptForm')
                    submitPayment(violation_form);
                }

            });
        });

        function submitPayment(form) {
            const formData = new FormData(form);
            for (let [key, value] of formData.entries()) {
                console.log(`${key}:`, value);
            }
            fetch('../actions/submit_receipt.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    console.log(data);
                    if (data.success) {
                        alert('Payment successfully submitted!');
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while submitting the payment.');
                });

        }

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
                (status === "Rejected" ? "bg-danger" :
                    status === "Pending" ? "bg-warning" :
                    status === "Resolved" ? "bg-success" :
                    status === "Escalated" ? "bg-dark text-light" : "");

            let criticality = button.getAttribute('data-criticality');
            modalCriticality.textContent = (criticality === "Critical" ? criticality : "");
            modalCriticality.className = "badge " + (criticality === "Critical" ? "bg-danger text-light" : "");
        }

        function fetchViolations(filter) {

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
                                row.status === 'Rejected' ? 'bg-danger' :
                                row.status === 'Escalated' ? 'bg-dark' : '' 
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

        function fetchUnpaidViolations() {
            fetch('../actions/get_unpaid_violations.php') // Adjust path as needed
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
                const tr = document.createElement('tr');
                tr.innerHTML = `
                 <td>
                       <label class="radio-modern">
                           <input type="radio" name="selected_violation_id" value="${v.id}">
                           <span class="radio-checkmark"></span>
                       </label>
                  </td>
              <td>${v.violation_name}</td>
              <td>${v.violation_date}</td>
              <td>${v.fine_amount}</td>
              <td>${v.escalation_fee}</td>
              <td class="${
                    v.status === 'Pending' ? 'text-secondary' :
                    v.status === 'Escalated' ? 'text-danger' :
                    v.status === 'Payment_Period' ? 'text-success' :
                    '' }">
                       <strong>${v.status || 'N/A'}</strong> 
                  </td>
        
              
            `;
                container.appendChild(tr);
                // Click to select radio button
                tr.addEventListener("click", () => {
                    const radio = tr.querySelector('input[name="selected_violation_id"]');
                    if (radio) {
                        radio.checked = true;
                    }
                });
            });
        }

        // Validate Receipt Form
        function validateReceiptForm(violation, fileInput, paid_amount, status, fine_amount, escalation_fee) {
            let valid = true;

            // Validate file input and stall
            if (!fileInput.files.length || !violation || !paid_amount) {
                alert("Please complete the form.");
                valid = false;
            } else {
                const file = fileInput.files[0];
                const allowedExtensions = /(\.pdf|\.jpg|\.jpeg|\.png|\.gif|\.bmp|\.webp)$/i; // Added more image formats
                if (!allowedExtensions.exec(file.name)) {
                    alert("Invalid file type. Only PDF or image files are allowed.");
                    valid = false;
                }
            }

            if (status === "Escalated") {
                const total_fee = fine_amount + escalation_fee;
                if (valid && paid_amount < total_fee) {
                    alert("Paid amount cannot be less than the Total Due = (Fine + Escalation Fee).");
                    valid = false;
                }
                if (valid && paid_amount > total_fee) {
                    alert("Paid amount cannot be greater than the Total Due = (Fine + Escalation Fee).");
                    valid = false;
                }
            } else {
                // Additional validation for paid_amount and amount_due
                if (valid && paid_amount < fine_amount) {
                    alert("Paid amount cannot be less than the amount due.");
                    valid = false;
                }

                if (valid && paid_amount > fine_amount) {
                    alert("Paid amount cannot be greater than the amount due.");
                    valid = false;
                }
            }

            return valid;
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
                    console.log("Status: ", status);
                    modalStatus.textContent = status;
                    modalStatus.className = "badge " + (status === "Escalated" ? "bg-dark" : status === "Unresolved" ? "bg-danger" : "bg-success");

                });
            });
        });
    </script>
</body>

</html>