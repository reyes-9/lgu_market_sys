<?php
require_once "../../../includes/config.php";
require "../../../includes/session.php";

if ($_SESSION['user_type'] !== 'Admin') {
    header("Location: /lgu_market_sys/errors/err403.php");
    exit;
}

// Fetch application data
$hashed_id = $_GET['id'] ?? null;
$applications_id = base64_decode($hashed_id);

if ($applications_id) {
    $query = "SELECT 
        s.stall_number, 
        sec.section_name AS section_name, 
        m.market_name AS market_name,
        CONCAT(h.first_name, ' ', IFNULL(h.middle_name, ''), ' ', h.last_name) AS helper_full_name,
        e.duration AS extension_duration,
        app.id,
        app.application_type,
        app.application_number,
        app.status,
        app.created_at,
        app.account_id,
        a.user_id,
        CONCAT(u.first_name, ' ', IFNULL(u.middle_name, ''), ' ', u.last_name) AS applicant_full_name,
        v.violation_type_id,
        vt.violation_name,
        d.document_name, 
        d.document_path, 
        d.status AS doc_status
    FROM applications app
    JOIN stalls s ON app.stall_id = s.id    
    JOIN sections sec ON app.section_id = sec.id
    JOIN market_locations m ON app.market_id = m.id
    JOIN applicants a ON app.id = a.application_id
    JOIN users u ON a.user_id = u.id
    JOIN documents d ON app.id = d.application_id 
    LEFT JOIN violations v ON a.user_id = v.user_id AND v.status = 'Pending'
    LEFT JOIN violation_types vt ON v.violation_type_id = vt.id
    LEFT JOIN extensions e ON app.id = e.application_id  
    LEFT JOIN helpers h ON h.id = app.helper_id
    WHERE app.id = :id;";

    $stmt = $pdo->prepare($query);
    $stmt->execute(['id' => $applications_id]); // Bind the application ID parameter
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch all rows

    if (empty($results)) {
        die("Application not found.");
    }

    // Group applications and their violations and documents
    $applications = [];

    foreach ($results as $row) {
        $applications_id = $row['id'];

        if (!isset($applications[$applications_id])) {
            // Initialize the application data
            $applications[$applications_id] = [
                'user_id' => $row['user_id'],
                'stall_number' => $row['stall_number'],
                'section_name' => $row['section_name'],
                'market_name' => $row['market_name'],
                'helper_full_name' => $row['helper_full_name'],
                'extension_duration' => $row['extension_duration'],
                'application_id' => $row['id'],
                'account_id' => $row['account_id'],
                'application_type' => $row['application_type'],
                'application_number' => $row['application_number'],
                'status' => $row['status'],
                'created_at' => $row['created_at'],
                'applicant_full_name' => $row['applicant_full_name'],
                'violations' => [],
                'documents' => []
            ];
        }

        // Add violations if not already included
        if (!empty($row['violation_type_id']) && !in_array($row['violation_name'], array_column($applications[$applications_id]['violations'], 'violation_name'))) {
            $applications[$applications_id]['violations'][] = [
                'violation_type_id' => $row['violation_type_id'],
                'violation_name' => $row['violation_name']
            ];
        }

        // Add documents if not already included
        if (!empty($row['document_name']) && !in_array($row['document_name'], array_column($applications[$applications_id]['documents'], 'document_name'))) {
            $applications[$applications_id]['documents'][] = [
                'document_name' => $row['document_name'],
                'document_path' => $row['document_path'],
                'document_status' => $row['doc_status'] // Add document status here
            ];
        }
    }

    // var_dump($applications, JSON_PRETTY_PRINT);
} else {
    die("Invalid application ID.");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Application - Public Market Monitoring System</title>
    <link rel="icon" type="image/png" href="../favicon_192.png">
    <link rel="stylesheet" href="../../../assets/css/review.css">
    <?php include '../../../includes/cdn-resources.php'; ?>
</head>

<body class="body light">
    <?php include '../../../includes/nav.php'; ?>

    <div class="text-start m-3 p-3 title d-flex align-items-center">
        <div class="icon-box me-3 shadow title-icon">
            <i class="bi bi-bar-chart-line-fill"></i>
        </div>
        <div>
            <h4 class="m-0">Admin - Applications Review</h4>
            <p class="text-muted mb-0">Evaluate and approve or reject stall applications based on eligibility criteria.</p>
        </div>
        <div class="ms-auto me-5">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="http://localhost/lgu_market_sys/pages/admin/home/">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="http://localhost/lgu_market_sys/pages/admin/table/">Tables</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Reviews</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="review-container">

        <a class="btn btn-return" href="/lgu_market_sys/pages/admin/table/">
            <i class="bi bi-arrow-left"></i> Return to applications
        </a>

        <h4 class="text-center">Application Validation</h4>
        <h6><i class="bi bi-exclamation-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="Checks if the user applicant in the database."></i>
            Applicant Validation Result:
            <span class="spinner-border spinner-border-sm" id="applicantSpinner" aria-hidden="true"></span>
            <i class="bi bi-check-circle-fill text-success d-none" id="applicantIconSuccess"></i>
            <i class="bi bi-x-circle-fill text-danger d-none" id="applicantIconFailed"></i>
        </h6>

        <div class="container applicant d-none">
            <table class="table table-borderless">
                <tbody>
                    <tr>
                        <th>Application Number: </th>
                        <td id="applicationNumber"></td>
                    </tr>
                    <tr>
                        <th>Application Type: </th>
                        <td id="applicationType"></td>
                    </tr>
                    <tr>
                        <th>Applicant Name: </th>
                        <td id="applicantName"></td>
                    </tr>
                    <tr>
                        <th>Status: </th>
                        <td id="applicationStatus"></td>
                    </tr>
                </tbody>
            </table>
        </div>



        <hr>
        <h6><i class="bi bi-exclamation-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="Verifies the market's availability status."></i>
            Market Validation Result:
            <span class="spinner-border spinner-border-sm" id="marketSpinner" aria-hidden="true"></span>
            <i class="bi bi-check-circle-fill text-success d-none" id="marketIconSuccess"></i> <i class="bi bi-x-circle-fill text-danger d-none" id="marketIconFailed"></i>
        </h6>
        <!-- Market Information -->
        <div class="container market d-none">
            <table class="table table-borderless">
                <tbody>
                    <tr>
                        <th>Market Name: </th>
                        <td id="marketName"></td>
                    </tr>
                    <tr>
                        <th>Section: </th>
                        <td id="sectionName"></td>
                    </tr>
                    <tr>
                        <th>Stall Number: </th>
                        <td id="stallNumber"></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <hr>
        <h6><i class="bi bi-exclamation-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="Checks if the applicant has an existing violations."></i>
            Violation Validation Result:
            <span class="spinner-border spinner-border-sm" id="violationSpinner" aria-hidden="true"></span>
            <i class="bi bi-check-circle-fill text-success d-none" id="violationIconSuccess"></i> <i class="bi bi-x-circle-fill text-danger d-none" id="violationIconFailed"></i>
        </h6>
        <div class="container violations d-none">
            <h6 id="violations"></h6>
        </div>

        <hr>
        <h6> <strong> Uploaded Documents</strong></h6>
        <div id="uploadedDocument" class="container mt-3">
            <div class="row">
                <div class="col">
                    <div class="list-group">
                        <!-- Documents will be dynamically inserted here -->
                    </div>
                </div>
            </div>
        </div>

        <hr>

        <div class="approval-section mt-3" id="approvalSection">
            <h5 class="fw-bold">Review & Approval</h5>
            <p class="text-muted">Approve or reject the application after reviewing the submitted documents.</p>

            <?php
            $firstKey = array_key_first($applications); // Get the first key (e.g., 317)
            $app = $applications[$firstKey]; // Now we can use $app for cleaner code
            ?>
            <?php
            $firstKey = isset($applications) ? array_key_first($applications) : null;
            ?>

            <form method="POST" action="" class="approval-actions">

                <input type="hidden" name="application_id" value="<?php echo !empty($app['application_id']) ? htmlspecialchars($app['application_id']) : ''; ?>">
                <input type="hidden" name="account_id" value="<?php echo !empty($app['account_id']) ? $app['account_id'] : ''; ?>">
                <input type="hidden" name="application_type" value="<?php echo !empty($app['application_type']) ? $app['application_type'] : ''; ?>">
                <input type="hidden" name="application_number" value="<?php echo !empty($app['application_number']) ? $app['application_number'] : ''; ?>">

                <button type="button" class="btn btn-secondary" onclick="toggleApplicationRejectionComment()">Reject</button>
                <button type="submit" id="approve-button" name="approved" class="btn review-btn ms-3" disabled>Approve</button>

                <div id="application-rejection-comment" class="d-none mt-2">
                    <textarea id="rejection_input" name="rejection_reason" placeholder="Enter rejection reason." class="rejection_input" rows="2"></textarea>
                    <button id="reject-button" name="rejected" type="submit" class="btn btn-danger btn-sm mt-2">Confirm Rejection</button>
                </div>
            </form>

        </div>

    </div>


    <?php include '../../../includes/footer.php'; ?>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            checkEligibility();
            initializeApprovalActions();

        });

        let invalidDocuments = 0;


        function hideRejectedApplicationSections(applicationStatus) {
            const approval_section = document.getElementById("approvalSection");
            const documents_btn_divs = document.getElementsByClassName("documentsBtn"); // Returns a collection
            console.log(applicationStatus);
            if (approval_section) {
                if (applicationStatus === "Rejected" || applicationStatus === "Approved") {
                    approval_section.classList.add("d-none");
                    console.log("hello")
                } else {
                    approval_section.classList.remove("d-none");
                }
            }

            // Loop through all elements with class "documentsBtn" and hide/show them
            for (let divs of documents_btn_divs) {
                if (applicationStatus === "Rejected" || applicationStatus === "Approved") {
                    divs.classList.add("d-none");
                } else {
                    divs.classList.remove("d-none");
                }
            }
        }

        // Initialize event listeners on all approval forms
        function initializeApprovalActions() {
            document.querySelectorAll(".approval-actions").forEach(form => {
                form.addEventListener("submit", function(event) {
                    event.preventDefault(); // Prevent default form submission

                    let formData = new FormData(this);
                    let action = event.submitter.name; // Determine which button was clicked

                    if (action === "approved") {
                        approveApplication(formData);
                    } else if (action === "rejected") {

                        let reject_input = document.getElementById('rejection_input').value;

                        if (!reject_input || reject_input.trim() === "") {
                            alert("Please, Enter reason for rejection.");
                            return;
                        }

                        rejectApplication(formData);
                    }
                });
            });
        }

        // Function to handle application approval
        function approveApplication(formData) {
            formData.append("action", "approved");

            fetch("../../actions/process_application.php", {
                    method: "POST",
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        setTimeout(() => {
                            window.location.href = "http://localhost/lgu_market_sys/pages/admin/table/";
                        }, 3000);
                    } else {
                        alert("Error: " + data.message);
                    }
                })
                .catch(error => console.error("Error:", error));
        }

        // Function to handle application rejection
        function rejectApplication(formData) {
            formData.append("action", "rejected");

            fetch("../../actions/process_application.php", {
                    method: "POST",
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        setTimeout(() => {
                            window.location.href = "http://localhost/lgu_market_sys/pages/admin/table/";
                        }, 3000);
                    } else {
                        alert("Error: " + data.message);
                    }
                })
                .catch(error => console.error("Error:", error));
        }

        function assignValues(applications) {

            // Extract the first (or only) application object
            let applicationId = Object.keys(applications)[0]; // Get the first application key (e.g., "317")
            let application = applications[applicationId]; // Extract the actual application data

            if (application) {

                setTimeout(() => {
                    hideRejectedApplicationSections(application.status);
                }, 100);

                // Show Containers
                document.querySelector(".container.applicant").classList.remove("d-none");
                document.querySelector(".container.market").classList.remove("d-none");

                // Assign values to Applicant Information
                document.getElementById("applicationNumber").innerHTML = ` ${application.application_number ?? 'N/A'}`;
                document.getElementById("applicationType").innerHTML =
                    `${application.application_type ? application.application_type.charAt(0).toUpperCase() + application.application_type.slice(1) : 'N/A'}`;

                document.getElementById("applicantName").innerHTML = ` ${application.applicant_full_name ?? 'N/A'}`;
                document.getElementById("applicationStatus").innerHTML = ` ${application.status ?? 'N/A'}`;

                // Assign values to Market Information
                document.getElementById("marketName").innerHTML = ` ${application.market_name ?? 'N/A'}`;
                document.getElementById("sectionName").innerHTML = ` ${application.section_name ?? 'N/A'}`;
                document.getElementById("stallNumber").innerHTML = `${application.stall_number ?? 'N/A'}`;

                // Handle Violations
                let violationsElement = document.getElementById("violations");

                if (application.violations && application.violations.length > 0) {
                    let violationList = application.violations.map(v => `<li>${v.violation_name}</li>`).join('');

                    violationsElement.innerHTML = `<strong class="text-danger">Violations:</strong> <ul>${violationList}</ul>`;

                    // Ensure the container is visible
                    document.querySelector(".container.violations").classList.remove("d-none");
                } else {
                    violationsElement.innerHTML = `<strong class="text-danger">Violations:</strong> None`;
                }

                let documentContainer = document.getElementById("uploadedDocument");

                if (application.documents && application.documents.length > 0) {
                    let documentList = application.documents.map((doc, index) => {

                        // Determine file type and set preview accordingly
                        let fileExtension = doc.document_path.split('.').pop().toLowerCase();
                        let preview = '';

                        if (['jpg', 'jpeg', 'png', 'gif'].includes(fileExtension)) {
                            preview = `<img src="http://localhost/lgu_market_sys/${doc.document_path}" alt="${doc.document_name}" class="img-thumbnail" style="max-width: 200px;">`;
                        } else if (fileExtension === 'pdf') {
                            preview = `<iframe src="http://localhost/lgu_market_sys/${doc.document_path}" style="width:100%; height:300px; border:1px solid #ccc;"></iframe>`;
                        } else {
                            preview = `<span class="text-muted">Cannot preview this file type</span>`;
                        }

                        // Determine the badge background color based on document status
                        let statusBadgeClass = "bg-warning";
                        if (doc.document_status.toLowerCase() === "valid") {
                            statusBadgeClass = "bg-success";
                        } else if (doc.document_status.toLowerCase() === "rejected") {
                            statusBadgeClass = "bg-danger";
                            invalidDocuments++;
                        } else if (doc.document_status.toLowerCase() === "pending") {
                            invalidDocuments++;
                        }

                        return `
                             <div class="document-item text-center p-3 border rounded mb-3 bg-light" id="docRow-${index}">
                                 <strong>${doc.document_name}</strong><br>
                                 ${preview}<br>
                                 <a href="http://localhost/lgu_market_sys/${doc.document_path}" target="_blank" class="btn btn-sm mt-2 border-0">View Document</a>
                                 <div class="mt-2 documentsBtn">
                                        <button class="btn btn-success btn-sm" onclick="approveDocument(${index}, '${doc.document_path}')">Valid</button>
                                        <button class="btn btn-danger btn-sm" onclick="toggleDocumentRejectionComment(${index})">Reject</button>
                                 </div>
                                 <div id="rejection-comment-${index}" class="d-none mt-2">
                                     <textarea id="rejection-text-${index}" placeholder="Enter rejection reason." class="rejection_input" rows="2"></textarea>
                                     <button class="btn btn-danger btn-sm mt-2" onclick="rejectDocument(${index}, '${doc.document_path}')">Confirm Rejection</button>
                                 </div>
                                 <p id="status-${index}" class="text-muted mt-2">Status: <span class="badge ${statusBadgeClass}">${doc.document_status}</span></p>
                             </div>`;
                    }).join('');

                    documentContainer.innerHTML += documentList;
                } else {
                    documentContainer.innerHTML += `<span class="text-muted">No document uploaded</span>`;
                }

            }
        }

        function approveDocument(index, docPath) {

            document.getElementById(`status-${index}`).innerHTML = 'Status: <span class="badge bg-success">Valid</span>';
            document.getElementById(`docRow-${index}`).classList.add("bg-success", "text-white");

            fetch('../../actions/approve_document.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        document_path: docPath
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert("Document approved successfully!");
                        location.reload();
                    } else {
                        alert("Failed to approve document.");
                    }
                })
                .catch(error => console.error("Error approving document:", error));
        }

        function rejectDocument(index, docPath) {
            let rejectionReason = document.getElementById(`rejection-text-${index}`).value.trim();
            if (!rejectionReason) {
                alert("Please enter a reason for rejection.");
                return;
            }

            // Update UI
            document.getElementById(`status-${index}`).innerHTML = `Status: <span class="badge bg-danger">Rejected</span> <br> <small>Reason: ${rejectionReason}</small>`;
            document.getElementById(`docRow-${index}`).classList.add("bg-danger", "text-white");
            document.getElementById(`rejection-comment-${index}`).classList.add("d-none");

            fetch('../../actions/reject_document.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        document_path: docPath,
                        reason: rejectionReason
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert("Document rejected successfully!");
                        document.getElementById(`rejection-text-${index}`).value = ""; // Clear input
                        location.reload();
                    } else {
                        alert("Failed to reject document: " + data.message);
                    }
                })
                .catch(error => console.error("Error rejecting document:", error));
        }


        function toggleDocumentRejectionComment(index) {
            let commentDiv = document.getElementById(`rejection-comment-${index}`);
            commentDiv.classList.toggle("d-none");
        }

        function toggleApplicationRejectionComment() {
            let commentDiv = document.getElementById(`application-rejection-comment`);
            commentDiv.classList.toggle("d-none");
        }

        function checkEligibility() {
            <?php
            $application = reset($applications); // Get the first application data
            $userId = !empty($application['user_id']) ? json_encode($application['user_id']) : 'null';
            $stallNumber = !empty($application['stall_number']) ? json_encode($application['stall_number']) : 'null';
            $application_type = !empty($application['application_type']) ? json_encode($application['application_type']) : 'null';
            ?>

            const userId = <?php echo $userId; ?>;
            const stallNumber = <?php echo $stallNumber; ?>;
            const application_type = <?php echo $application_type; ?>;

            let applications = <?php echo json_encode($applications, JSON_PRETTY_PRINT); ?>;

            if (!userId || !stallNumber) {
                alert("User ID and Stall Number are required.");
                return;
            }

            assignValues(applications);

            fetch(`../../actions/eligibility_checker.php?user_id=${encodeURIComponent(userId)}&stall_number=${encodeURIComponent(stallNumber)}&application_type=${encodeURIComponent(application_type)}`)
                .then(response => response.json())
                .then(data => {

                    // Applicant Validation
                    const applicantDiv = document.querySelector(".container.applicant");
                    const applicantSpinner = document.getElementById("applicantSpinner");
                    const applicantIconSuccess = document.getElementById("applicantIconSuccess");
                    const applicantIconFailed = document.getElementById("applicantIconFailed");

                    if (data.isApplicant) {
                        // applicantDiv.classList.remove("d-none");
                        setTimeout(() => {
                            applicantIconSuccess.classList.remove("d-none");
                        }, 400);

                    } else {
                        // applicantDiv.classList.add("d-none");
                        setTimeout(() => {
                            applicantIconFailed.classList.remove("d-none");
                        }, 400);
                    }
                    setTimeout(() => {
                        if (applicantSpinner) applicantSpinner.remove(); // Remove loading spinner
                    }, 400);


                    // Market (Stall) Validation
                    const marketDiv = document.querySelector(".container.market");
                    const marketSpinner = document.getElementById("marketSpinner");
                    const marketIconSuccess = document.getElementById("marketIconSuccess");
                    const marketIconFailed = document.getElementById("marketIconFailed");

                    if (data.isStall) {
                        // marketDiv.classList.remove("d-none");
                        setTimeout(() => {
                            marketIconSuccess.classList.remove("d-none");
                        }, 1000);

                    } else {
                        // marketDiv.classList.add("d-none");
                        setTimeout(() => {
                            marketIconFailed.classList.remove("d-none");
                        }, 900);

                    }
                    setTimeout(() => {
                        if (marketSpinner) marketSpinner.remove(); // Remove loading spinner
                    }, 900);


                    // Violation Validation
                    const violationDiv = document.querySelector(".container.violations");
                    const violationSpinner = document.getElementById("violationSpinner");
                    const violationIconSuccess = document.getElementById("violationIconSuccess");
                    const violationIconFailed = document.getElementById("violationIconFailed");

                    if (data.hasViolation) {
                        setTimeout(() => {
                            violationDiv.classList.remove("d-none");
                            violationIconFailed.classList.remove("d-none");
                        }, 1400);

                    } else {
                        setTimeout(() => {
                            violationDiv.classList.add("d-none");
                            violationIconSuccess.classList.remove("d-none");
                        }, 1400);

                    }
                    setTimeout(() => {
                        if (violationSpinner) violationSpinner.remove(); // Remove loading spinner
                    }, 1400);

                    if (data.isApplicant && data.isStall && !data.hasViolation && invalidDocuments === 0) {
                        document.getElementById("approve-button").disabled = false;
                    } else {
                        document.getElementById("approve-button").disabled = true;
                    }




                })
                .catch(error => console.error("Error:", error));
        }
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
</body>