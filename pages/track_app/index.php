<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Application - Public Market Monitoring System</title>
    <link rel="icon" type="image/png" href="../../images/favicon_192.png">
    <link rel="stylesheet" href="../../assets/css/track_app.css">
    <?php include '../../includes/cdn-resources.php'; ?>
</head>

<?php
require_once '../../includes/session.php';
?>

<body class="body">

    <?php include '../../includes/nav.php'; ?>
    <div class="content-wrapper">
        <div class="container mx-5 mt-5">
            <div class="container mt-3 p-0">
                <a href="../portal/" class="btn btn-outline btn-return mb-3">
                    <i class="bi bi-arrow-left"></i> Profile
                </a>
            </div>
            <h2 class="text-light">Your Applications</h2>

            <div class="container my-4">
                <div class="row g-5 fixed-row" id="cardsContainer">

                </div>
                <nav class="mt-4">
                    <ul class="pagination" id="pagination"></ul>
                </nav>
            </div>
        </div>

        <!-- Response Modal -->
        <div class="modal fade" id="responseModal" tabindex="-1" aria-labelledby="responseModalLabel" aria-hidden="true">
            <div class="modal-dialog  modal-dialog-centered">
                <div class="modal-content text-center">
                    <i class="bi bi-check-circle-fill icon-animation"></i>
                    <div class="modal-body" id="responseModalBody">
                        <!-- Message content will go here -->
                    </div>
                    <div class="text-center text-secondary">
                        <p id="reloadCounter"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <?php include '../../includes/footer.php'; ?>
    <?php include '../../includes/theme.php'; ?>

    <script>
        // Fetch initial data when the page loads
        document.addEventListener('DOMContentLoaded', function() {
            const initialPage = 1; // Set initial page number
            fetchData(initialPage);
        });

        document.addEventListener("DOMContentLoaded", function() {
            attachWithdrawListeners(); // Attach listeners on initial load
        });

        // Handle pagination clicks
        document.addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('page-link')) {
                const urlParams = new URLSearchParams(e.target.href.split('?')[1]);
                const page = parseInt(urlParams.get('page'));

                e.preventDefault();
                fetchData(page);
            }
        });


        function capitalizeWords(str) {
            return str.replace(/\b\w/g, (char) => char.toUpperCase());
        }

        async function generateCards(data) {

            const container = document.querySelector('.row.g-5');

            container.innerHTML = '';
            for (let index = 0; index < data.length; index++) {
                const app = data[index];
                const docsInputs = await generateRequiredDocsInputs(app.id, app.application_type);
                const delay = index * 0.1;
                const card = `
            <div class="col-md-4 slide-up">
                <div class="card fade-in-card fade-in-card-delay border-0" style="--delay: ${delay}s;">
                <span class="card-status ${
                    app.status === 'Draft' ? 'status-draft' :
                    app.status === 'Submitted' ? 'status-submitted' :
                    app.status === 'Under Review' && app.inspection_status === "Approved" ? 'status-inspection-approved' : 
                    app.status === 'Under Review' && app.inspection_status === "Rejected" ? 'status-inspection-rejected' : 
                    app.status === 'Under Review' ? 'status-under-review' : 
                    app.status === 'Approved' ? 'status-approved' :
                    app.status === 'Rejected' ? 'status-rejected' :
                    app.status === 'Withdrawn' ? 'status-withdrawn' : 
                    app.status === 'Document Resubmission' ? 'status-resubmit' : 
                    ''
                    }">
                    
                    ${
                    app.inspection_status === "Approved" && app.status === 'Under Review' ? 'Inspected' :
                    app.inspection_status === "Rejected" && app.status === 'Under Review' ? 'Inspected' :
                    app.status
                    }
                </span>
                <h4 class="my-4"><strong>${app.application_type} Application</strong></h4>
                <table class="table">

                    ${app.application_number !== null ? `
                    <tr>
                        <th>Application Number</th>
                        <td>${app.application_number}</td>
                    </tr>
                    ` : ""}

                     ${app.extension_duration !== null ? `
                    <tr>
                         <th>Extension Duration</th>
                         <td>${app.extension_duration}</td>
                    </tr>
                     ` : ""}

                     ${app.full_name !== null ? `<tr>
                        <th>Helper Name</th>
                        <td>${app.full_name}</td>
                    </tr>` : ""}
                    
                    <tr>
                        <th>Stall Number</th>
                        <td>${app.stall_number}</td>
                    </tr>
                    <tr>
                        <th>Section</th>
                        <td>${app.section_name}</td>
                 </tr>
                    <tr>
                        <th>Market</th>
                        <td>${app.market_name}</td>
                    </tr>
                    <tr>
                        <th>Submitted On</th>
                        <td>${app.created_at}</td>
                    </tr>
                </table>
                <button type="button" id="progressModal" class="btn btn-warning w-100 mt-3" data-bs-toggle="modal" data-bs-target="#viewProgressModal${app.id}">
                    View Progress
                </button>
                <button type="button" class="btn btn-outline-secondary w-100 mt-3 withdraw-btn" data-bs-toggle="modal" data-bs-target="#withdrawModal${app.id}">Withdraw Application</button>
                </div>
            </div>

            <!-- Progress Modal  -->
            <div class="modal fade" id="viewProgressModal${app.id}" tabindex="-1" aria-labelledby="viewProgressModalLabel${app.id}" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="modal-container">
                                <div class="d-flex align-items-center justify-content-between">
                                    <h4 class="modal-title fw-bold" id="viewProgressModalLabel${app.id}">Application Progress</h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                           
                                <div class="progress-tracker-modern my-5 d-flex flex-column flex-md-row align-items-center justify-content-between" data-status="${app.status}">
                                    ${generateProgressSteps(app.status, app.inspection_status)}
                                </div>
                                ${app.status === 'Document Resubmission' ? `
                                 <button class="btn bg-warning-subtle btn-sm shadow" data-bs-target="#resubmitDocumentsModal${app.id}" data-bs-dismiss="modal" data-bs-toggle="modal">Resubmit Documents</button>
                                ` : ''}
                               
                            </div>
                        </div>
                    </div>  
                </div>
            </div>

            <div class="modal fade" id="resubmitDocumentsModal${app.id}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="modal-container">
                                <div class="d-flex align-items-center justify-content-between">
                                    <h4 class="modal-title fw-bold">Document Resubmission (${app.application_type} Application)</h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form class="mt-4" id="resubmitForm${app.id}" enctype="multipart/form-data">
                                    <div id="requiredDocsContainer${app.id}">
                                     <input type="hidden" id="application_id" name="application_id" value="${app.id}">
                                        ${docsInputs}
                                    </div>
                                    <button type="submit" id="submitDocumentsBtn" class="btn bg-info-subtle mt-3">Submit</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

                
            <!-- Withdraw Modal  -->
            <div class="modal fade withdrawModal" id="withdrawModal${app.id}" tabindex="-1" aria-labelledby="withdrawModalLabel" aria-hidden="true">
               <div class="modal-dialog">
                   <div class="modal-content">
                       <div class="modal-header">
                           <h5 class="modal-title" id="withdrawModalLabel">Confirm Withdrawal</h5>
                           <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                       </div>
                       <div class="modal-body">
                           Are you sure you want to withdraw this application?
                       </div>
                        <div class="modal-footer">
                           <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-danger confirmWithdraw" data-id="${app.id}" data-app_name="${app.application_type}">Withdraw</button>
                        </div>
                    </div>
                </div>
            </div>
            `;
                container.innerHTML += card;
            };
            initializeDocumentResubmission();
        }

        function initializeDocumentResubmission() {
            document.querySelectorAll("[id^='resubmitForm']").forEach(form => {
                form.addEventListener("submit", async (e) => {
                    e.preventDefault();

                    const appId = form.id.replace("resubmitForm", "");
                    const submitBtn = form.querySelector("#submitDocumentsBtn");
                    const formData = new FormData(form);

                    let allFilesSelected = true;
                    for (let pair of formData.entries()) {
                        if (pair[1] instanceof File && pair[1].name === "") {
                            allFilesSelected = false;
                            break;
                        }
                    }

                    if (!allFilesSelected) {
                        alert("Please select a file for all required file inputs.");
                        return;
                    }


                    for (let [key, value] of formData.entries()) {
                        if (value instanceof File) {
                            console.log(`${key}:`, value.name, value.size, value.type);
                        } else {
                            console.log(`${key}:`, value);
                        }
                    }

                    submitBtn.disabled = true;
                    submitBtn.textContent = "Submitting...";

                    try {
                        const response = await fetch("../actions/resubmit_documents.php", {
                            method: "POST",
                            body: formData
                        });

                        const result = await response.json();

                        if (result.success) {
                            alert("Documents resubmitted successfully!");

                            const modalElement = document.getElementById(`resubmitDocumentsModal${appId}`);
                            const modalInstance = bootstrap.Modal.getInstance(modalElement);
                            modalInstance.hide();

                            // Optionally, refresh or update the card
                        } else {
                            alert(`Failed to resubmit: ${result.message}`);
                        }
                    } catch (error) {
                        console.error("Error:", error);
                        alert("An error occurred while submitting the documents.");
                    } finally {
                        submitBtn.disabled = false;
                        submitBtn.textContent = "Submit";
                    }
                });
            });
        }



        async function generateRequiredDocsInputs(applicationId, applicationType) {
            try {
                const response = await fetch('../actions/generate_docs_inputs.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `application_id=${encodeURIComponent(applicationId)}&application_type=${encodeURIComponent(applicationType)}`
                });

                const results = await response.json();
                let inputElements = '';

                if (Array.isArray(results.documents)) {
                    results.documents.forEach(document => {

                        // Create an input container (div element)
                        const inputContainer = `
                <div class="mb-3">
                    <label for="${document.id}" class="form-label">${document.label}</label>
                    <input type="file" class="form-control" id="${document.id}" name="${document.name}" accept=".pdf, .jpg, .jpeg, .png">
                </div>
            `;
                        inputElements += inputContainer; // Append the HTML string to the inputElements string
                    });
                } else {
                    console.error("Error: 'documents' is not an array", results.documents);
                }

                return inputElements;
            } catch (err) {
                console.error('Error fetching document inputs:', err);
                return '';
            }
        }


        function generateProgressSteps(status, inspection_status) {
            const steps = [{
                    id: 1,
                    label: 'Submitted',
                    icon: ''
                },
                {
                    id: 2,
                    label: 'Under Review',
                    icon: ''
                },
                {
                    id: 3,
                    label: 'Document Resubmission',
                    icon: ''
                },
                {
                    id: 4,
                    label: 'Inspection',
                    icon: ''
                },
                {
                    id: 5,
                    label: 'Official Result',
                    icon: ''
                }
            ];

            let statusIndex;

            statusIndex = steps.findIndex(step => step.label === status);

            if (status === "Rejected") {
                statusIndex = 5;
            }

            if (status === "Approved") {
                statusIndex = 5;
            }

            if (status === "Under Review" && inspection_status === "Approved") {
                statusIndex = 3;
            }

            if (status === "Under Review" && inspection_status === "Rejected") {
                statusIndex = 3;
            }

            console.log("Status Index: ", statusIndex)
            return steps.map((step, index) => {

                console.log("Index: ", index)

                let stepClass = 'pending';
                let icon = '';

                if (status === 'Submitted' && index === statusIndex) {
                    stepClass = 'completed';
                    icon = 'bi-check-circle-fill';
                } else if (status === 'Under Review' && inspection_status === "Approved" && index === statusIndex) {
                    stepClass = 'completed';
                    icon = 'bi-check-circle-fill';
                } else if (status === 'Under Review' && inspection_status === "Rejected" && index === statusIndex) {
                    stepClass = 'rejected';
                    icon = 'bi-x-circle-fill';
                } else if (status === 'Document Resubmission' && index === statusIndex) {
                    stepClass = 'ongoing';
                    icon = 'bi-arrow-repeat';
                } else if (status === 'Approved' && index === statusIndex) {
                    stepClass = 'completed';
                    icon = 'bi-check-circle-fill';
                } else if (status === 'Rejected' && index === statusIndex) {
                    stepClass = 'rejected';
                    icon = 'bi-x-circle-fill';
                } else if (index < statusIndex) {
                    stepClass = 'completed';
                    icon = 'bi-check-circle-fill';
                } else if (index === statusIndex) {
                    stepClass = 'ongoing';
                    icon = 'bi bi-hourglass-split';
                }


                return `<div class="step-modern ${stepClass}">
                                       <div class="circle">
                            ${icon ? `<i class="bi ${icon}"></i>` : `<span>${step.id}</span>`}
                                </div>
                                        <div class="label mt-2">${step.label}</div>
                                        <small class="timestamp">${
                                        stepClass === 'completed' ? 'Completed' : 
                                        stepClass === 'ongoing' ? 'Ongoing' : 
                                        stepClass === 'rejected' ? 'Rejected' : 

                                        'Pending'}</small>
                                </div>`
            }).join('');
            console.log("Status Index: ", statusIndex)

        }

        function generatePagination(totalPages, currentPage) {
            const pagination = document.getElementById('pagination');
            pagination.innerHTML = ''; // Clear existing pagination

            // Previous Button
            pagination.innerHTML +=
                `<li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
            <a class="page-link" href="?page=${currentPage - 1}">Previous</a>
            </li>
            `;

            // Add logic for pages with ellipsis
            const pageRange = 3; // Number of pages to show around the current page
            const startPage = Math.max(1, currentPage - pageRange);
            const endPage = Math.min(totalPages, currentPage + pageRange);

            if (startPage > 1) {
                pagination.innerHTML +=
                    `<li class="page-item">
                <a class="page-link" href="?page=1">1</a>
            </li>
             `;
                if (startPage > 2) {
                    pagination.innerHTML += `
                <li class="page-item disabled">
                    <span class="page-link">...</span>
                </li>
            `;
                }
            }

            // Display pages within the range
            for (let i = startPage; i <= endPage; i++) {
                pagination.innerHTML +=
                    `<li class="page-item ${i === currentPage ? 'active' : ''}">
                <a class="page-link" href="?page=${i}">${i}</a>
            </li>
            `;
            }

            if (endPage < totalPages) {
                if (endPage < totalPages - 1) {
                    pagination.innerHTML +=
                        `<li class="page-item disabled">
                    <span class="page-link">...</span>
                </li>
            `;
                }
                pagination.innerHTML += `
            <li class="page-item">
                <a class="page-link" href="?page=${totalPages}">${totalPages}</a>
            </li>
            `;
            }

            // Next Button
            pagination.innerHTML +=
                `<li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
            <a class="page-link" href="?page=${currentPage + 1}">Next</a>
            </li>`;
        }

        function fetchData(page) {
            fetch(`../actions/track_application.php?page=${page}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(json => {
                    const data = json.data.map(item => ({
                        ...item,
                        application_type: capitalizeWords(item.application_type)
                    }));

                    if (data.length === 0) {
                        const container = document.getElementById('cardsContainer');
                        container.innerHTML = '<h5 class="text-secondary shadow">Currently, you have no pending applications. </h5>';
                    } else {
                        const totalPages = json.pagination.total_pages;
                        console.log(data);
                        generateCards(data);
                        generatePagination(totalPages, page);
                    }
                })
                .catch(error => {
                    console.error("Error fetching data:", error);
                });
        }




        function attachWithdrawListeners() {
            document.querySelector(".row.g-5").addEventListener("click", function(event) {
                if (event.target.classList.contains("confirmWithdraw")) {
                    let appId = event.target.dataset.id;
                    let appType = event.target.dataset.app_name;

                    console.log("Application Id and Type: ", appId, "-", appType);

                    fetch("../actions/withdraw_application.php", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json"
                            },
                            body: JSON.stringify({
                                id: appId,
                                type: appType
                            })
                        })
                        .then(response => response.json())
                        .then(data => {

                            if (data.success) {
                                document.getElementById('responseModalBody').innerHTML = data.message.join('<br>');
                                document.getElementById('responseModalBody').classList.remove('text-danger');
                                document.getElementById('responseModalBody').classList.add('text-success');
                            } else {
                                document.getElementById('responseModalBody').innerHTML = data.message.join('<br>');
                                document.getElementById('responseModalBody').classList.remove('text-success');
                                document.getElementById('responseModalBody').classList.add('text-danger');
                            }


                            document.querySelectorAll('.withdrawModal').forEach(modal => {
                                const instance = bootstrap.Modal.getInstance(modal);
                                if (instance) instance.hide();
                            });

                            // Show the modal
                            const responseModal = new bootstrap.Modal(document.getElementById('responseModal'));
                            responseModal.show();

                            let count = 4;
                            let countdownInterval = setInterval(function() {
                                count--;
                                document.getElementById("reloadCounter").innerText = "This page will reload in " + count + " seconds";

                                if (count <= 0) {
                                    clearInterval(countdownInterval);
                                    location.reload();
                                }
                            }, 1000);
                            // setTimeout(() => location.reload(), 4000);

                        })
                        .catch(error => console.error("Error:", error));
                }
            });
        }
    </script>
</body>

</html>